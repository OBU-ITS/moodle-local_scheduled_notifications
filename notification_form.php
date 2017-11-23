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
 * Scheduled notifications - input form to add/amend/delete a notification
 *
 * @package    local_scheduled_notifications
 * @author     Peter Welham
 * @copyright  2017, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once("{$CFG->libdir}/formslib.php");

class notification_form extends moodleform {

    function definition() {
        $mform =& $this->_form;
		
        $data = new stdClass();
		$data->id = $this->_customdata['id'];
		$data->title = $this->_customdata['title'];
		$data->text = $this->_customdata['text'];
		$data->start_time = $this->_customdata['start_time'];
		$data->stop_time = $this->_customdata['stop_time'];

		// Start with the required hidden field
		$mform->addElement('hidden', 'id', $data->id);
		$mform->setType('id', PARAM_RAW);
		
		if ($data->id != 0) {
			$text['text'] = $data->text;
			$fields = [
				'title' => $data->title,
				'text' => $text,
				'start_time' => $data->start_time,
				'stop_time' => $data->stop_time
			];
			$this->set_data($fields);
		} else {
			$fields = [
				'start_time' => $data->start_time,
				'stop_time' => $data->stop_time
			];
			$this->set_data($fields);
		}

		$mform->addElement('html', '<h2>' . get_string('notification', 'local_scheduled_notifications') . '</h2>');

		$mform->addElement('text', 'title', get_string('title', 'local_scheduled_notifications'), 'size="40" maxlength="100"');
		$mform->setType('title', PARAM_RAW);
		$mform->addRule('title', null, 'required', null, 'server');
		$mform->addElement('editor', 'text', get_string('text', 'local_scheduled_notifications'));
		$mform->setType('text', PARAM_RAW);
		$mform->addRule('text', null, 'required', null, 'server');
		$mform->addElement('date_time_selector', 'start_time', get_string('start_time', 'local_scheduled_notifications'));
		$mform->addElement('date_time_selector', 'stop_time', get_string('stop_time', 'local_scheduled_notifications'));

		$buttonarray = array();
		$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('save_notification', 'local_scheduled_notifications'));
		if ($data->id != 0) {
			$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('delete_notification', 'local_scheduled_notifications'));
		}
		$buttonarray[] = &$mform->createElement('cancel');
		$mform->addGroup($buttonarray, 'buttonarray', '', array(' '), false);
    }
	
	function validation($data, $files) {
		$errors = parent::validation($data, $files); // Ensure we don't miss errors from any higher-level validation
		
		// Do our own validation and add errors to array
		foreach ($data as $key => $value) {
			if ($key == 'text') {
				$string = preg_replace('/\s+/', '', $value['text']); // Remove whitespace
				$string = strtoupper($string); // Capitalise
				if (strpos($string, '<SCRIPT')) {
					$errors['text'] = get_string('scripting_prohibited', 'local_scheduled_notifications');
				}
			} else if (($key == 'stop_time') && ($value <= $data['start_time'])) {
				$errors['stop_time'] = get_string('invalid_time', 'local_scheduled_notifications');
			}
		}
		
		return $errors;
	}
}