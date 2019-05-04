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
 * Strings for component 'qtype_regexp', language 'en', branch 'MOODLE_23_STABLE'
 *
 * @package   qtype_regexp
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['addahint'] = 'Add a hint';
$string['addmoreanswers'] = 'Add {no} more answer';
$string['answer'] = 'Answer: ';
$string['answermustbegiven'] = 'You must enter an answer if there is a grade or feedback.';
$string['answer1mustbegiven'] = 'Answer 1 cannot be empty.';
$string['answerno'] = 'Answer: {$a}';
$string['addingregexp'] = 'Adding regular expression question';
$string['bestcorrectansweris'] = '<strong>The best correct answer is:</strong><br />{$a}';
$string['calculatealternate'] = '(Re-)Calculate alternate answers';
$string['caseno'] = 'No, case is unimportant';
$string['casesensitive'] = 'Case sensitivity';
$string['caseyes'] = 'Yes, case must match';
$string['clicktosubmit'] = 'Click the <strong>Check</strong> button to submit this <strong>complete</strong> answer.';
$string['correctansweris'] = '<strong>The correct answer is:</strong><br />{$a}';
$string['correctanswersare'] = '<strong>The other accepted answers are:</strong>';
$string['editingregexp'] = 'Editing regular expression question';
$string['filloutoneanswer'] = '<strong>Answer 1</strong> must be a correct answer (grade = 100%) and it will not be analysed as a regular expression.';
$string['hidealternate'] = 'Hide alternate answers';
$string['illegalcharacters'] = '<strong>ERROR!</strong> In Answers with a grade > 0 these <em>unescaped</em> metacharacters are not allowed: <strong>{$a}</strong>';
$string['letter'] = 'Letter';
$string['notenoughanswers'] = 'This type of question requires at least one answer';
$string['penaltyforeachincorrecttry'] = 'Penalty for incorrect tries and Buying a letter or word';
$string['penaltyforeachincorrecttry_help'] = 'When you run your questions using the \'Interactive with multiple tries\' or \'Adaptive mode\' behaviour,
so that the student will have several tries to get the question right, then this option controls how much they are penalised for each incorrect try.

The penalty is a proportion of the total question grade, so if the question is worth three marks, and the penalty is 0.3333333,
then the student will score 3 if they get the question right first time, 2 if they get it right second try, and 1 if they get it right on the third try.

If you have set the <strong>Help Button</strong> mode to <strong>Letter</strong> or <strong>Word</strong> for this question,
<strong><em>the same penalty</em></strong> applies each time the student clicks the <strong>Buy Letter/Word</strong> Button.';
$string['pleaseenterananswer'] = 'Please enter an answer.';
$string['pluginname'] = 'Regular expression short answer';
$string['pluginname_help'] = 'Right-click on the <em>More Help</em> link below to open the Help page in a new tab/window.';
$string['pluginname_link'] = 'question/type/regexp';
$string['pluginnameadding'] = 'Adding a regular expression question';
$string['pluginnameediting'] = 'Editing regular expression question';
$string['pluginnamesummary'] = 'Like short answer but the analysis of the student\'s responses is based on regular expressions';
$string['regexp'] = 'Regular expression short answer';
$string['regexp_help'] = 'Right-click on the <em>More Help</em> link below to open the Help page in a new tab/window.';
$string['regexp_link'] = 'question/type/regexp';
$string['regexperror'] = 'Error in regular expression:<strong>{$a}</strong>';
$string['regexperrorclose'] = 'closed: <strong>{$a}</strong>';
$string['regexperroropen'] = 'opened: <strong>{$a}</strong>';
$string['regexperrorparen'] = '<strong>ERROR!</strong> Check your parentheses or square brackets!';
$string['regexperrornopermutations'] = '<strong>ERROR!</strong> There are no permuted words inside your double square brackets!';
$string['regexperroroddunderscores'] = '<strong>ERROR!</strong> There is an ODD number of underscores inside your double square brackets!';
$string['regexperrortoomanypermutations'] = '<strong>ERROR!</strong> No more than 2 permutation sets allowed per answer (double square brackets)!';
$string['regexperrorsqbrack'] = 'Square brackets';
$string['regexpsensitive'] = 'Use regular expressions to check answers';
$string['regexpsummary'] = 'Like short answer but the analysis of the student\'s responses is based on regular expressions';
$string['settingsformultipletries'] = 'Settings for multiple tries and Buying letters or words';
$string['showhidealternate'] = 'Show/Hide alternate answers';
$string['showhidealternate_help'] = 'Calculate and display all correct answers in this form? This may take quite some time on your server,
depending on the number and complexity of the regular expressions you have entered in the Answer fields!

On the other hand, it is the recommended way to check that your "correct answers" expressions are correctly written.';
$string['studentshowalternate'] = 'Show alternate';
$string['studentshowalternate_help'] = 'Show <strong>all</strong> correct alternative answers to student when on review page? If there are a lot
of automatically generated correct alternative answers, displaying them all can make the review page quite long.';
$string['usehint'] = 'Help button mode';
$string['usehint_help'] = 'Selecting a mode other than <strong>None</strong> will display
a button to allow the student to get the next letter or word.

In <strong>Adaptive</strong> mode the button displayed will say "<strong>Buy next letter</strong>" or "<strong>Buy next word</strong>"
according to the mode selected by the teacher. For setting the "cost" of buying a letter or word, see the
<strong>Penalty for incorrect tries and Buying a letter or word</strong> settings further down this page.

In <strong>Adaptive No penalty</strong> mode the button displayed will say "<strong>Get next letter</strong>" or "<strong>Get next word</strong>"

By default Help button mode value is set at <b>None</b>';
$string['word'] = 'Word';
$string['wordorpunctuation'] = 'Word or Punctuation';
$string['privacy:metadata'] = 'The RegExp Question Type plugin does not store any personal data.';
$string['wrongwords'] = 'Wrong words';
$string['misplacedwords'] = 'Misplaced words';


//cloud poodll settings
// CloudPoodll settings and options
$string['showhidecloudpoodll']="Cloud Poodll Recorder Settings";
$string['formataudio']="Audio recording";
$string['formatvideo']="Video recording";
$string['formatupload']="Upload media file";

$string['recorder'] = 'Recorder type';
$string['recorderaudio'] = 'Audio recorder';
$string['recordervideo'] = 'Video recorder';
$string['defaultrecorder'] = 'Recorder type';
$string['defaultrecorder_details'] = '';

$string['apiuser'] = 'Poodll API User ';
$string['apiuser_details'] = 'The Poodll account username that authorises Poodll on this site.';
$string['apisecret'] = 'Poodll API Secret ';
$string['apisecret_details'] = 'The Poodll API secret. See <a href= "https://support.poodll.com/support/solutions/articles/19000083076-cloud-poodll-api-secret">here</a> for more details';
$string['language'] = 'Speaker language';

$string['useast1'] = 'US East';
$string['tokyo'] = 'Tokyo, Japan';
$string['sydney'] = 'Sydney, Australia';
$string['dublin'] = 'Dublin, Ireland';
$string['ottawa'] = 'Ottawa, Canada (slow)';
$string['frankfurt'] = 'Frankfurt, Germany (slow)';
$string['london'] = 'London, U.K (slow)';
$string['saopaulo'] = 'Sao Paulo, Brazil (slow)';
$string['forever'] = 'Never expire';
$string['en-us'] = 'English (US)';
$string['es-us'] = 'Spanish (US)';
$string['en-au'] = 'English (Aus.)';
$string['en-uk'] = 'English (UK)';
$string['fr-ca'] = 'French (Can.)';
$string['awsregion'] = 'AWS Region';
$string['region'] = 'AWS Region';
$string['expiredays'] = 'Days to keep file';

$string['timelimit'] = 'Recording time limit';
$string['currentsubmission'] = 'Current Submission:';

$string['recordertype'] = 'Cloud Poodll recording type';
$string['audioskin'] = 'Audio recorder skin';
$string['videoskin'] = 'Video recorder skin';
$string['skinplain'] = 'Plain';
$string['skinbmr'] = 'Burnt Rose';
$string['skinfresh'] = 'Fresh (audio only)';
$string['skin123'] = 'One Two Three';
$string['skinonce'] = 'Once';
$string['skinupload'] = 'Upload';
$string['skinpush'] = 'Push';

$string['fallback'] = 'non-HTML5 Fallback';
$string['fallback_details'] = 'If the browser does not support HTML5 recording for the selected mediatype, fallback to an upload screen or a warning.';
$string['fallbackupload'] = 'Upload';
$string['fallbackiosupload'] = 'iOS: upload, else warning';
$string['fallbackwarning'] = 'Warning';

$string['displaysubs'] = '{$a->subscriptionname} : expires {$a->expiredate}';
$string['noapiuser'] = 'No API user entered. Plugin will not work correctly.';
$string['noapisecret'] = 'No API secret entered. Plugin will not work correctly.';
$string['credentialsinvalid'] = 'The API user and secret entered could not be used to get access. Please check them.';
$string['appauthorised']= 'Speak(auto grade) is authorised for this site.';
$string['appnotauthorised']= 'Speak(auto grade) is NOT authorised for this site.';
$string['refreshtoken']= 'Refresh license information';
$string['notokenincache']= 'Refresh to see license information. Contact support if there is a problem.';
$string['transcode']= 'Transcode';
$string['transcode_details']= 'Transcode audio to MP3 and video to MP4.';
$string['transcriber'] = 'Transcriber';
$string['transcriber_details'] = 'The transcription engine to use';
$string['transcriber_amazontranscribe'] = 'Amazon Transcribe';
$string['transcriber_chrome'] = 'Chrome Speech API';
$string['transcriptnotready'] = 'Transcript not ready yet';
$string['transcripttitle'] = 'Transcript';

$string['notimelimit'] = 'No time limit';
$string['xsecs'] = '{$a} seconds';
$string['onemin'] = '1 minute';
$string['xmins'] = '{$a} minutes';
$string['oneminxsecs'] = '1 minutes {$a} seconds';
$string['xminsecs'] = '{$a->minutes} minutes {$a->seconds} seconds';

$string['recordingheader'] = 'Recording options';