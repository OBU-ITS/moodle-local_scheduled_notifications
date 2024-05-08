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
} else if (local_scheduled_notifications_is_authorised()) {
	$owner_id = $USER->id;
} else {
	redirect($home);
}

$context = context_system::instance();
if (!has_capability('local/scheduled_notifications:update', $context)) {
	redirect($home);
}

$url = $home . 'local/scheduled_notifications/notifications.php';
$add = $home . 'local/scheduled_notifications/notification.php';

$PAGE->set_pagelayout('standard');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('notifications', 'local_scheduled_notifications'));

$mform = new notifications_form();

if ($mform->is_cancelled()) {
    redirect($home);
}
else if ($mform_data = $mform->get_data()) {
	if ($mform_data->submitbutton == get_string('add_notification', 'local_scheduled_notifications')) {
		redirect($add);
    }
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_scheduled_notifications/title', null);
$mform->display();
$notifications = local_scheduled_notifications_get_notifications($owner_id);
$data = local_scheduled_notifications_get_template_data($notifications);
echo $OUTPUT->render_from_template('local_scheduled_notifications/notification_summaries', $data);
$mform->display();


echo $OUTPUT->footer();
