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
 * Plugin internal classes, functions and constants are defined here.
 *
 * @package     local_mekorot
 * @copyright   2020 Noam Leibovitch <noamleib@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Handle the core\event\course_completed event.
 *
 * @param object $event The event object.
 */
class event_observers {

	    /**
		    *      * Message viewed event handler.
		    *           *
		    *                * @param \core\event\message_viewed $event The message viewed event.
		    *                     */
	    public static function message_viewed(\core\event\message_viewed $event) {
		            global $DB;

			            $userid = $event->userid;
			            $messageid = $event->other['messageid'];

				            $DB->delete_records('message_sms_messages', ['useridto' => $userid, 'messageid' => $messageid]);
				        }
	    public static function agent_log ($debug){

		            file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($debug), FILE_APPEND);
			            }
	    public static function updateSap($event) {
		            global $CFG, $DB;
			                   echo "User comleted a course event fired";
			                   agent_log("User comleted a course event fired");
					          $eventdata = $event->get_data();
					          $user = \core_user::get_user($eventdata['objectid']);
						  agent_log ($eventdata);
						                  $lmsid = $eventdata->{'userid'};
						                  $courseid = $eventdata->{'course'};
								                  $grade= grade_get_course_grade($lmsid, $courseid);
								                  $grade= $grade->str_grade;
										      return;
	    }

}

function agent_log ($debug){
	
	file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($debug), FILE_APPEND);
	}
	
function updateSap($event) {
	 global $CFG, $DB;
		echo "User comleted a course event fired";
		agent_log("User comleted a course event fired");
        $eventdata = $event->get_data();
        $user = \core_user::get_user($eventdata['objectid']);
		//Retrieve the course id and user id from the eventdata objects
		agent_log ($eventdata);
		$lmsid = $eventdata->{'userid'};
		$courseid = $eventdata->{'course'};
		$grade= grade_get_course_grade($lmsid, $courseid);	
		$grade= $grade->str_grade;
    return;
}
