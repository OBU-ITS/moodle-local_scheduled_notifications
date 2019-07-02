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
 * Scheduled notifications - db updates acting on the local_scheduled_notification table
 *
 * @package    local_scheduled_notifications
 * @author     Peter Welham
 * @copyright  2019, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function get_notifications_course() {
	global $DB;
	
	$course = $DB->get_record('course', array('idnumber' => 'SUBS_NOTIFICATIONS'), 'id', MUST_EXIST);
	return $course->id;
}

// Check if the given user has the given role in the notifications course
function has_notifications_role($user_id = 0, $role_id_1 = 0, $role_id_2 = 0, $role_id_3 = 0) {
	global $DB;
	
	if (($user_id == 0) || ($role_id_1 == 0)) { // Both mandatory
		return false;
	}
	
	$sql = 'SELECT ue.id'
		. ' FROM {user_enrolments} ue'
		. ' JOIN {enrol} e ON e.id = ue.enrolid'
		. ' JOIN {context} ct ON ct.instanceid = e.courseid'
		. ' JOIN {role_assignments} ra ON ra.contextid = ct.id'
		. ' JOIN {course} c ON c.id = e.courseid'
		. ' WHERE ue.userid = ?'
			. ' AND e.enrol = "manual"'
			. ' AND ct.contextlevel = 50'
			. ' AND ra.userid = ue.userid'
			. ' AND (ra.roleid = ? OR ra.roleid = ? OR ra.roleid = ?)'
			. ' AND c.idnumber = "SUBS_NOTIFICATIONS"';
	$db_ret = $DB->get_records_sql($sql, array($user_id, $role_id_1, $role_id_2, $role_id_3));
	if (empty($db_ret)) {
		return false;
	} else {
		return true;
	}
}

/**
 * A list of notifications of the given type
 */

function get_notifications($owner_id = 0) {
    global $DB;

    $conditions = array();
	if ($owner_id != 0) {
		$conditions['owner_id'] = $owner_id;
	}
	return $DB->get_records('local_scheduled_notification', $conditions, 'owner_id', '*');
}

function read_notification($id) {
    global $DB;

	return $DB->get_record('local_scheduled_notification', array('id' => $id), '*', MUST_EXIST);
}

function write_notification($id, $owner_id, $title, $text, $start_time, $stop_time) {
    global $DB, $USER;

    $record = new stdClass();
	$record->id = $id;
	$record->owner_id = $owner_id;
    $record->title = $title;
    $record->text = $text;
	$record->start_time = $start_time;
	$record->stop_time = $stop_time;
	$record->updater_id = $USER->id;
	$record->update_time = time();

	if ($id == 0) {
		$id = $DB->insert_record('local_scheduled_notification', $record);
	} else {
		$DB->update_record('local_scheduled_notification', $record);
	}
	
	return $id;
}

function delete_notification($id) {
    global $DB;

	return $DB->delete_records('local_scheduled_notification', array('id' => $id));
}
