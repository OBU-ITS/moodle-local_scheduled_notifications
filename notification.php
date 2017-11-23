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
 * Scheduled notifications - add/amend/delete a notification
 *
 * @package    local_scheduled_notifications
 * @author     Peter Welham
 * @copyright  2017, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once('../../config.php');
require_once('./db_update.php');
require_once('./notification_form.php');

require_login();
$home = new moodle_url('/');
if (is_siteadmin()) {
	$owner_id = 0;
} else if ($USER->lastname == '- Notifications') {
	$owner_id = $USER->id;
} else {
	redirect($home);
}

$context = context_system::instance();

$url = $home . 'local/scheduled_notifications/notification.php';
$list = $home . 'local/scheduled_notifications/notifications.php';

$message = '';
$id = 0;
$title = '';
$text = '';
$start_datetime = new DateTime();
$stop_datetime = new DateTime;

if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	if ($id != 0) {
		$notification = read_notification($id);
		if (($owner_id != 0) && ($owner_id != $notification->owner_id)) {
			redirect($home);
		}
		$owner_id = $notification->owner_id;
		$title = $notification->title;
		$text = $notification->text;
		$start_datetime->setTimestamp($notification->start_time);
		$stop_datetime->setTimestamp($notification->stop_time);
	}
}

// Get the adjusted timestamps (that account for local timezone/DST)
$start_time = $start_datetime->getTimestamp();
$stop_time = $stop_datetime->getTimestamp();

$PAGE->set_pagelayout('standard');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_title(get_string('notification', 'local_scheduled_notifications'));

$parameters = [
	'id' => $id,
	'title' => $title,
	'text' => $text,
	'start_time' => $start_time,
	'stop_time' => $stop_time
];

$mform = new notification_form(null, $parameters);

if ($mform->is_cancelled()) {
    redirect($list);
} 

if ($mform_data = $mform->get_data()) {
	if ($mform_data->submitbutton == get_string('save_notification', 'local_scheduled_notifications')) {
		write_notification($mform_data->id, $owner_id, $mform_data->title, $mform_data->text['text'], $mform_data->start_time, $mform_data->stop_time);
	} else if (($mform_data->submitbutton == get_string('delete_notification', 'local_scheduled_notifications')) && ($id != 0)) {
		delete_notification($id);
    }
	redirect($list);
}	

echo $OUTPUT->header();

if ($message) {
    notice($message, $url);    
}
else {
    $mform->display();
}

echo $OUTPUT->footer();
