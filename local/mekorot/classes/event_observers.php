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

//defined('MOODLE_INTERNAL') || die();

/**
 * Handle the core\event\course_completed event.
 *
 * @param object $event The event object.
 */
namespace local_mekorot;

class event_observers {

	    /**
	    *
	    *  @param \core\event\course_completed $event .
	    */
	public static function agent_log($debug){
		file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($debug), FILE_APPEND);
		}
	public static function course_completed($event) {
		global $CFG, $DB;
		\local_mekorot\event_observers::agent_log(" *************** User comleted a course event fired, Updating SAP ***************");
        	$eventdata = $event->get_data();
        	//Retrieve the course id and user id from the eventdata objects
		$lmsid = $eventdata['relateduserid'];
                $courseid = $eventdata['courseid'];
		$course = get_course($courseid);
		$D = $course->idnumber;
		$user = \core_user::get_user($lmsid);
		$user = json_decode(json_encode($user), true);
		$employeeId = $user['idnumber'];
		$group = groups_get_user_groups($courseid, $lmsid);
		$group = json_decode(json_encode($group), true);
		$groupId = json_encode($group[0][0]);
		$E = groups_get_group($groupId);
		$E = $E->idnumber;
		$grade= grade_get_course_grade($lmsid, $courseid);
		$grade= $grade->str_grade;
		\local_mekorot\event_observers::agent_log ("updating SAP that lms User ".$lmsid." completed course: ".$courseid." with grade: " . $grade);
		\local_mekorot\event_observers::agent_log ('SAP Employee ID: '.$employeeId .', SAP E: '.$E. ', SAP D: '.$D);
		
$postfields = array('employeeId'=>$employeeId,'sapD'=>$D, 'sapE'=>$E, 'grade'=>$grade);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://noam.requestcatcher.com/test');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 1);
// Edit: prior variable $postFields should be $postfields;
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
$result = curl_exec($ch);
		\local_mekorot\event_observers::agent_log ("SAP Response: ");
		\local_mekorot\event_observers::agent_log (json_encode($result));
		\local_mekorot\event_observers::agent_log(" *************** User comleted a course event finished ***************");

		}
	

public static function course_completion_updated($event) {
                /*global $CFG, $DB;
                \local_mekorot\event_observers::agent_log($event);
                \local_mekorot\event_observers::agent_log("course__completion__updated event fired");
                $eventdata = $event->get_data();
                //Retrieve the course id and user id from the eventdata objects
                $userid = $eventdata['userid'];
                $courseid = $eventdata['courseid'];
		\local_mekorot\event_observers::agent_log($userid);
		\local_mekorot\event_observers::agent_log($courseid);

		if ($entry = $DB->get_record('local_iomad_track', array('userid' => $userid,
                                                                'courseid' => $courseid,
								'timestarted' => null,
                                                                'timecompleted' => null))) {
        	file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($entry), FILE_APPEND);
            	$entry = json_decode(json_encode($entry), true);
//		$entry = json_encode($entry);
		// We already have an entry.  Change the issue time.		
            	$timestarted = strtotime('now');
		file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($timestarted), FILE_APPEND);
		file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($entry), FILE_APPEND);
		$DB->set_field('local_iomad_track', 'timestarted', $timestarted, array('id' => $entry['id']));
		$DB->set_field('local_iomad_track', 'modifiedtime', $timestarted, array('id' => $entry['id']));
//            	file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($DB->update_record('local_iomad_track', array('id' => $entry['id'], 'timestarted' => $timestarted))), FILE_APPEND);
		file_put_contents ('/var/www/html/log.txt',PHP_EOL .date('D, d M Y H:i:s'). serialize($DB->get_record('local_iomad_track', array('userid' => $userid,
                                                                'courseid' => $courseid,
                                                                'timecompleted' => null))), FILE_APPEND);
        }
*/}
}
