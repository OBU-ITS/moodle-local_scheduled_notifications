<?php

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
 * Scheduled notifications - input form for notifications
 *
 * @package    local_scheduled_notifications
 * @author     Peter Welham
 * @copyright  2017, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once("{$CFG->libdir}/formslib.php");

class notifications_form extends moodleform {

    function definition() {
		global $USER;
		
        $mform =& $this->_form;
		
        $data = new stdClass();
		$data->notifications = $this->_customdata['notifications'];

		$url = new moodle_url('/local/scheduled_notifications/notification.php');

		$mform->addElement('html', '<h2>' . get_string('scheduled_notifications', 'local_scheduled_notifications') . '</h2>');

		$date = date_create();
		$date_format = 'd-m-y H:i';
		foreach ($data->notifications as $notification) {
			$html = '<h4><a href="' . $url . '?id=' . $notification->id . '">' . $notification->title;
			if ($notification->start_time > 0) {
				date_timestamp_set($date, $notification->start_time);
				$html .= ' - start ' . date_format($date, $date_format);
			}
			if ($notification->stop_time > 0) {
				date_timestamp_set($date, $notification->stop_time);
				$html .= ', stop ' . date_format($date, $date_format);
			}
			$html .= '</a>';
			if (($notification->owner_id > 0) && ($notification->owner_id != $USER->id)) {
				$owner = get_complete_user_data('id', $notification->owner_id);
				$html .= ' [' . $owner->firstname . ']';
			}
			if ($notification->updater_id > 0) {
				$html .= ' (';
				if ($notification->updater_id != $notification->owner_id) {
					$updater = get_complete_user_data('id', $notification->updater_id);
					$html .= $updater->firstname . ' ' . $updater->lastname . ' ';
				}
				date_timestamp_set($date, $notification->update_time);
				$update_time = date_format($date, $date_format);
				$html .= $update_time . ')';
			}
			$html .= '</h4>';
			$mform->addElement('html', $html);
		}

        $this->add_action_buttons(true, get_string('add_notification', 'local_scheduled_notifications'));
    }
}