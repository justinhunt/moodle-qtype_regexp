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
 * A qtype_regexp adhoc task
 *
 * @package    qtype_regexp
 * @copyright  2015 Justin Hunt (poodllsupport@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qtype_regexp\task;

defined('MOODLE_INTERNAL') || die();

use \qtype_regexp\cloudpoodll\constants;
use \qtype_regexp\cloudpoodll\utils;

require_once($CFG->dirroot.'/question/type/regexp/question.php');
require_once($CFG->dirroot.'/question/engine/questionattemptstep.php');
require_once($CFG->dirroot.'/question/engine/questionattempt.php');
require_once($CFG->dirroot.'/question/behaviour/manualgraded/behaviour.php');


/**
 * A mod_readaloud adhoc task to fetch back transcriptions from Amazon S3
 *
 * @package    qtype_regexp
 * @since      Moodle 2.7
 * @copyright  2015 Justin Hunt (poodllsupport@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class fetch_transcript_adhoc extends \core\task\adhoc_task {
                                                                     
   	 /**
     *  Run the tasks
     */
	 public function execute(){
	     global $DB;
		$trace = new \text_progress_trace();
         $cd_string = $this->get_custom_data_as_string();
         //cd = audiourl qa  and oldstep,  timecreated
         $cd =  unserialize($cd_string);


         $transcript = utils::fetch_transcript($cd->audiourl);
         $vtt = utils::fetch_vtt($cd->audiourl);
         if($transcript===false){
             if($cd->taskcreationtime + (HOURSECS * 24) < time()){
                 $this->do_forever_fail('No transcript could be found',$trace);
                 return;
             }else{
                 $this->do_retry_soon('Transcript appears to not be ready yet',$trace,$cd,$cd_string);
                 return;
             }
         } else {
             //yay!!! we have a transcript
             $qa=$cd->qa;
             $oldstep=$cd->oldstep;
             $stepdata = array('answertranscriptx' => $transcript,'answervttx' => $vtt);
             $qa->process_action($stepdata,time(), $oldstep->get_user_id(), $oldstep->get_id());
             $qa->finish(time(),$oldstep->get_id());
             return;
         }
	}

    protected function do_retry_soon($reason,$trace,$customdata,$customdata_string){
        if($customdata->taskcreationtime + (MINSECS * 15) < time()){
            $this->do_retry_delayed($reason,$trace);
        }else {
            $trace->output($reason . ": will try again next cron ");
            $fetch_task = new \qtype_regexp\task\fetch_transcript_adhoc();
            $fetch_task->set_component('qtype_regexp');
            $fetch_task->set_custom_data_as_string($customdata_string);
            // queue it
            \core\task\manager::queue_adhoc_task($fetch_task);
        }
    }

    protected function do_retry_delayed($reason,$trace){
        $trace->output($reason . ": will retry after a delay ");
        throw new \file_exception('retrievefileproblem', 'could not fetch transcript.');
    }

    protected function do_forever_fail($reason,$trace){
        $trace->output($reason . ": will not retry ");
	}

}

