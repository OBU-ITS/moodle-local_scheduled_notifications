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
 * Scheduled notifications - list notifications
 *
 * @package    local_scheduled_notifications
 * @author     Peter Welham
 * @copyright  2019, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once('../../config.php');
require_once('./locallib.php');
require_once('./notifications_form.php');

require_login();
$home = new moodle_url('/');
if (is_siteadmin()) {
	$owner_id = 0;
} else if (is_authorised()) {
	$owner_id = $USER->id;
} else {
	redirect($home);
}

$context = context_system::instance();
if (!has_capability('local/obu_application:update', $context)) {
	redirect($home);
}

$url = $home . 'local/scheduled_notifications/notifications.php';
$add = $home . 'local/scheduled_notifications/notification.php';

$PAGE->set_pagelayout('standard');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_title(get_string('notifications', 'local_scheduled_notifications'));

$message = '';

$parameters = [
	'notifications' => get_notifications($owner_id)
];

$mform = new notifications_form(null, $parameters);

if ($mform->is_cancelled()) {
    redirect($home);
} 
else if ($mform_data = $mform->get_data()) {
	if ($mform_data->submitbutton == get_string('add_notification', 'local_scheduled_notifications')) {
		redirect($add);
    }
}	

echo $OUTPUT->header();

if ($message) {
    notice($message, $url);    
}
else {
    $mform->display();
}

echo $OUTPUT->footer();
