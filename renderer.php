<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Regexp question renderer class.
 *
 * @package    qtype_regexp
 * @copyright  2011 Joseph REZEAU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use qtype_regexp\cloudpoodll\utils;
use qtype_regexp\cloudpoodll\constants;


/**
 * Generates the output for regexp questions.
 *
 * @copyright  2011 Joseph REZEAU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_regexp_renderer extends qtype_renderer {

    /**
     * Generate the display of the formulation part of the question shown at runtime
     * in a quiz.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param question_display_options $options controls what should and should not be displayed.
     * @return string HTML fragment.
     */
    public function formulation_and_controls(question_attempt $qa, question_display_options $options) {
        global $CFG, $currentanswerwithhint;
        require_once($CFG->dirroot.'/question/type/regexp/locallib.php');
        $question = $qa->get_question();

        $answer_field_name = $qa->get_qt_field_name('answer');
        $audiourl_field_name = $qa->get_qt_field_name('audiourl');


        $ispreview = !isset($options->attempt);
        $currentanswer = remove_blanks ($qa->get_last_qt_var('answer') );
        $currentaudiourl = remove_blanks ($qa->get_last_qt_var('audiourl') );
        $response = $qa->get_last_qt_data();
        $laststep = $qa->get_reverse_step_iterator();
        $hintadded = false;
        foreach ($qa->get_reverse_step_iterator() as $step) {
            $hintadded = $step->has_behaviour_var('_helps') === true;
                break;
        }
        $closest = find_closest($question, $currentanswer, $correctresponse = false, $hintadded);
        $question->closest = $closest;
        // If regexpadaptive behaviours replace current student response with correct beginning.
        $currbehaviourname = get_class($qa->get_behaviour() );
        $currstate = $qa->get_state();
        if (strpos ($currbehaviourname, 'adaptive') && $currstate == 'todo') {
            $currentanswer = $closest[0];
        }

        // Showing / hiding regexp generated alternative sentences (for teacher only).
        // Changed from javascript to print_collapsible_region OCT 2012.
        // Removed for compatibility with the Embed questions plugin see https://moodle.org/plugins/filter_embedquestion.

        $inputattributes = array(
            'type' => 'hidden',
            'name' => $answer_field_name,
            'value' => $currentanswer,
            'id' => $answer_field_name
        );

        if ($options->readonly) {
            $inputattributes['readonly'] = 'readonly';
        }

        $feedbackimg = '';
        if ($options->correctness) {
            $answer = $question->get_matching_answer(array('answer' => $currentanswer));
            if ($answer) {
                $fraction = $answer->fraction;
            } else {
                $fraction = 0;
            }
            $inputattributes['class'] = $this->feedback_class($fraction);
            $feedbackimg = $this->feedback_image($fraction);
        }
        $questiontext = $question->format_questiontext($qa);
        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
            $inputattributes['size'] = round(strlen($placeholder) * 1.1);
        }

        $input = html_writer::empty_tag('input', $inputattributes) . $feedbackimg;

        //insert audio url field
        $audiourl = html_writer::empty_tag(
            'input', array('type' => 'hidden',
            'name' => $audiourl_field_name,
            'value' => $currentaudiourl));
        $input .= $audiourl;


        //insert recorder
        if ($options->readonly) {
            $inputattributes['readonly'] = 'readonly';
            $audiourl = ($qa->get_last_qt_var('audiourl') );
            $input .= '<br>' . $this->fetch_player($question->recordertype,$audiourl, $question->language, false);
        }else {
            $r_options = get_config('qtype_speakautograde');
            $input .= $this->fetch_recorder($r_options, $question, $answer_field_name,$audiourl_field_name);
        }


        if ($placeholder) {
            $questiontext = substr_replace($questiontext, $input,
                    strpos($questiontext, $placeholder), strlen($placeholder));
        }

        $result = html_writer::tag('div', $questiontext, array('class' => 'qtext'));

        if (!$placeholder) {
            $result .= html_writer::start_tag('div', array('class' => 'ablock'));
            $result .= get_string('answer', 'qtype_shortanswer',
                    html_writer::tag('div', $input, array('class' => 'answer')));
            $result .= html_writer::end_tag('div');
        }

        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error(array('answer' => $currentanswer)),
                    array('class' => 'validationerror'));
        }

        return $result;
    }

    /**
     * Get feedback.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param question_display_options $options controls what should and should not be displayed.
     * @return string HTML fragment.
     */
    public function feedback(question_attempt $qa, question_display_options $options) {
        $result = '';
        $hint = null;
        if ($options->feedback) {
            $result .= html_writer::nonempty_tag('div', $this->specific_feedback($qa),
                    array('class' => 'specificfeedback qtype-regexp'));
            $hint = $qa->get_applicable_hint();
        }

        if ($options->numpartscorrect) {
            $result .= html_writer::nonempty_tag('div', $this->num_parts_correct($qa),
                    array('class' => 'numpartscorrect'));
        }

        if ($hint) {
            $result .= $this->hint($qa, $hint);
        }

        if ($options->generalfeedback) {
            $result .= html_writer::nonempty_tag('div', $this->general_feedback($qa),
                    array('class' => 'generalfeedback'));
        }

        if ($options->rightanswer) {
            $displaycorrectanswers = $this->correct_response($qa);
            $result .= html_writer::nonempty_tag('div', $displaycorrectanswers,
                    array('class' => 'rightanswer'));
        }

        return $result;
    }

    /**
     * Get feedback/hint information
     *
     * @param question_attempt $qa
     * @return string
     */
    public function specific_feedback(question_attempt $qa) {
        $question = $qa->get_question();
        $currentanswer = remove_blanks($qa->get_last_qt_var('answer') );
        $ispreview = false;
        $completemessage = '';
        $closestcomplete = false;
        foreach ($qa->get_reverse_step_iterator() as $step) {
            $hintadded = $step->has_behaviour_var('_helps') === true;
            break;
        }
        $closest = $question->closest;
        if ($hintadded) { // Hint added one letter or hint added letter and answer is complete.
            $answer = $question->get_matching_answer(array('answer' => $closest[0]));
            // Help has added letter OR word and answer is complete.
            $isstateimprovable = $qa->get_behaviour()->is_state_improvable($qa->get_state());
            if ($closest[2] == 'complete' && $isstateimprovable) {
                $closestcomplete = true;
                $class = '"correctness correct"';
                $completemessage = '<div class='.$class.'>'.get_string("clicktosubmit", "qtype_regexp").'</div>';

            }
        } else {
            $answer = $question->get_matching_answer(array('answer' => $qa->get_last_qt_var('answer')));
        }

        $labelerrors = '';
        $guesserrors = $closest[5];
        if ($guesserrors) {
            $labelwrongwords = '<span class="labelwrongword">'.get_string("wrongwords", "qtype_regexp").'</span>';
            $labelmisplacedwords = '<span class="labelmisplacedword">'.get_string("misplacedwords", "qtype_regexp").'</span>';
            switch ($guesserrors) {
                case 1 :
                    $labelerrors = '<div>'.$labelmisplacedwords.'</div>';
                    break;
                case 10 :
                    $labelerrors = '<div>'.$labelwrongwords.'</div>';
                    break;
                case 11 :
                    $labelerrors = '<div>'.$labelwrongwords. ' '. $labelmisplacedwords.'</div>';
                    break;
            }
        }

        // Student's response with corrections to be displayed in feedback div.
        $f = '<div><span class="correctword">'.$closest[1].'<strong>'.$closest[4].'</strong></span> '.$closest[3].'</div>';
        if ($answer && $answer->feedback || $closestcomplete == true) {
            return $question->format_text($f.$labelerrors.$answer->feedback.$completemessage,
                $answer->feedbackformat, $qa, 'question', 'answerfeedback', $answer->id);
        } else {
            return $f.$labelerrors;
        }
    }

    /**
     * Get correct response
     *
     * @param question_attempt $qa
     * @return string
     */
    public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();
        $displayresponses = '';
        $alternateanswers = get_alternateanswers($question);
        $bestcorrectanswer = $alternateanswers[1]['answers'][0];

        if (count($alternateanswers) == 1 ) { // No alternative answers besides the only "correct" answer.
            $displayresponses .= get_string('correctansweris', 'qtype_regexp', $bestcorrectanswer);
            // No need to display alternate answers!
            return $displayresponses;
        } else {
            $displayresponses .= get_string('bestcorrectansweris', 'qtype_regexp', $bestcorrectanswer).'<br />';
        }
        // Teacher can always view alternate answers; student can only view if question is set to studentshowalternate.
        $canview = question_has_capability_on($question, 'view');
        if ($question->studentshowalternate || $canview) {
            $displayresponses .= print_collapsible_region_start('expandalternateanswers', 'id'.
                            $question->id, get_string('showhidealternate', 'qtype_regexp'),
                            'showhidealternate', true, true);
            foreach ($alternateanswers as $key => $alternateanswer) {
                if ($key == 1) { // First (correct) Answer.
                    if (count($alternateanswers) > 1) {
                        $displayresponses .= get_string('correctanswersare', 'qtype_regexp').'<br />';
                    }
                } else {
                    $fraction = $alternateanswer['fraction'];
                    $displayresponses .= "<strong>$fraction</strong><br />";
                    foreach ($alternateanswer['answers'] as $alternate) {
                        $displayresponses .= $alternate.'<br />';
                    }
                }
            }
            $displayresponses .= print_collapsible_region_end(true);
        }
        return $displayresponses;
    }

    /**
     * @return string the HTML for the player
     */
    protected function fetch_player($recordertype, $mediaurl,$language, $havesubtitles=false) {
        global $PAGE;

        $playerid= html_writer::random_id(constants::M_COMPONENT . '_');

        //audio player template
        $audioplayer = "<audio id='@PLAYERID@' crossorigin='anonymous' controls='true'>";
        $audioplayer .= "<source src='@MEDIAURL@'>";
        if($havesubtitles){$audioplayer .= "<track src='@VTTURL@' kind='captions' srclang='@LANG@' label='@LANG@' default='true'>";}
        $audioplayer .= "</audio>";

        //video player template
        $videoplayer = "<video id='@PLAYERID@' crossorigin='anonymous' controls='true'>";
        $videoplayer .= "<source src='@MEDIAURL@'>";
        if($havesubtitles){$videoplayer .= "<track src='@VTTURL@' kind='captions' srclang='@LANG@' label='@LANG@' default='true'>";}
        $videoplayer .= "</video>";

        //template -> player
        $theplayer = ($recordertype == constants::REC_VIDEO ? $videoplayer : $audioplayer);
        $theplayer =str_replace('@PLAYERID@',$playerid,$theplayer);
        $theplayer =str_replace('@MEDIAURL@',$mediaurl,$theplayer);
        $theplayer =str_replace('@LANG@',$language,$theplayer);
        $theplayer =str_replace('@VTTURL@',$mediaurl . '.vtt',$theplayer);

        $ret = $theplayer;

        //if we have subtitles add the transcript AMD and html (we never need subtitles/interactive transcripts .. do we?)
        if($havesubtitles) {
            $transcript_containerid= html_writer::random_id(constants::M_COMPONENT . '_');
            $transcript_container = html_writer::div('',constants::M_COMPONENT . '_transcriptcontainer',array('id'=>$transcript_containerid));
            $ret  .= $transcript_container;

            //prepare AMD javascript for displaying transcript
            $transcriptopts = array('component' => constants::M_COMPONENT, 'playerid' => $playerid, 'containerid' => $transcript_containerid, 'cssprefix' => constants::M_COMPONENT . '_transcript');
            $PAGE->requires->js_call_amd(constants::M_COMPONENT . "/interactivetranscript", 'init', array($transcriptopts));
            $PAGE->requires->strings_for_js(array('transcripttitle'), constants::M_COMPONENT);
        }
        return $ret;

    }

    /**
     * @return string the HTML for the textarea.
     */
    protected function fetch_recorder($r_options, $question, $answer_field_name,$audiourl_field_name) {
        global $CFG;

        $width = '';
        $height = '';
        switch($question->recordertype) {

            case constants::REC_AUDIO:
                $recordertype = constants::REC_AUDIO;
                $recorderskin = $question->audioskin;
                switch ($question->audioskin) {
                    case constants::SKIN_FRESH:
                        $width = '400';
                        $height = '300';
                        break;
                    case constants::SKIN_PLAIN:
                        $width = '360';
                        $height = '190';
                        break;
                    default:
                        // bmr 123 once standard
                        $width = '360';
                        $height = '240';
                }
                break;

            case constants::REC_VIDEO:
            default:
                $recordertype = constants::REC_VIDEO;
                $recorderskin = $question->videoskin;
                switch ($question->videoskin) {
                    case constants::SKIN_BMR:
                        $width = '360';
                        $height = '450';
                        break;
                    case constants::SKIN_123:
                        $width = '450';
                        $height = '550';
                        break;
                    case constants::SKIN_ONCE:
                        $width = '350';
                        $height = '290';
                        break;
                    default:
                        $width = '360';
                        $height = '410';
                }
        }

        // amazon transcribe
        $transcriber = "chrome";
        if ($question->transcriber == constants::TRANSCRIBER_AMAZON_TRANSCRIBE) {
            $can_transcribe = utils::can_transcribe($r_options);
            $amazontranscribe = ($can_transcribe ? '1' : '0');
            $transcriber = "amazon";
        } else {
            $amazontranscribe = 0;
        }

        // chrometranscribe
        if ($question->transcriber == constants::TRANSCRIBER_CHROME) {
            $chrometranscribe = '1';
        } else {
            $chrometranscribe = 0;
        }

        // transcode
        $transcode = ($question->transcode  ? '1' : '0');

        // time limit
        $timelimit = $question->timelimit;

        // fetch cloudpoodll token
        $api_user = get_config(constants::M_COMPONENT, 'apiuser');
        $api_secret = get_config(constants::M_COMPONENT, 'apisecret');
        $token = utils::fetch_token($api_user, $api_secret);


        // any recorder hints ... go here..
        $hints = new \stdClass();
        $string_hints = base64_encode (json_encode($hints));

        // the elementid of the div in the DOM
        $dom_id = html_writer::random_id('');

        $recorderdiv = \html_writer::div('', constants::M_COMPONENT.'_notcenter',
            array('id' => $dom_id,
                'data-id' => 'therecorder_'.$dom_id,
                'data-parent' => $CFG->wwwroot,
                'data-localloader' => constants::LOADER_URL,
                'data-media' => $recordertype,
                'data-appid' => constants::APPID,
                'data-type' => $recorderskin,
                'data-width' => $width,
                'data-height' => $height,
                'data-updatecontrol' => $answer_field_name,
                'data-timelimit' => $timelimit,
                'data-transcode' => $transcode,
                'data-transcribe' => $amazontranscribe,
                'data-subtitle' => $amazontranscribe,
                'data-speechevents' => $chrometranscribe,
                'data-language' => $question->language,
                'data-expiredays' => $question->expiredays,
                'data-region' => $r_options->awsregion,
                'data-fallback' => $r_options->fallback,
                'data-hints' => $string_hints,
                'data-token' => $token // localhost
                //'data-token' => '643eba92a1447ac0c6a882c85051461a' // cloudpoodll
            )
        );

        $containerdiv = \html_writer::div($recorderdiv, constants::CLASS_REC_CONTAINER.' ',
            array('id' => constants::CLASS_REC_CONTAINER.$dom_id));

        // this is the finalhtml
        $recorderhtml = \html_writer::div($containerdiv , constants::CLASS_REC_OUTER);

        // set up the AMD for the recorder
        $opts = array(
            'component' => constants::M_COMPONENT,
            'dom_id' => $dom_id,
            'answerfieldname' => $answer_field_name,
            'audiourlfieldname' => $audiourl_field_name,
            'transcriber'=>$transcriber
        );

        $this->page->requires->js_call_amd(constants::M_COMPONENT.'/cloudpoodllhelper', 'init', array($opts));
        //$PAGE->requires->strings_for_js(array('reallydeletesubmission'), constants::M_COMPONENT);

        return $recorderhtml;
    }
}
