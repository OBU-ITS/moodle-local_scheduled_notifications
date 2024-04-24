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
 * Scheduled Notifications - library functions
 *
 * @package    local_scheduled_notifications
 * @author     Peter Welham
 * @copyright  2019, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/scheduled_notifications/db_update.php');

// Check if the user is authorised
function local_scheduled_notifications_is_authorised() {
	global $USER;

	if (is_siteadmin()) {
		return true;
	}

	$is_authorised = local_scheduled_notifications_has_notifications_role($USER->id, 5);

	return $is_authorised;
}

function local_scheduled_notifications_get_template_data($notifications) : array {
    $data = [
        'current' => array(),
        'future' => array(),
        'past' => array()
    ];

    foreach($notifications as $notification) {
        $owner = $notification->owner_id > 0
            ? get_complete_user_data('id', $notification->owner_id)
            : null;
        $updater = $notification->updater_id > 0
            ? get_complete_user_data('id', $notification->updater_id)
            : null;
        $item = [
            'link' => new moodle_url('/local/scheduled_notifications/notification.php?id=' . $notification->id) ,
            'name' => $notification->title,
            'from' => $notification->start_time,
            'to' => $notification->stop_time,
            'createdby' => $owner->firstname ?? ($updater->firstname ?? "Unknown"),
            'updatedby' => $updater->firstname ?? $notification->updater_id,
            'updatedon' => $notification->update_time,
            'hasupdated' => $updater != null
        ];
        if($notification->start_time >= time()) {
            $data['future'][] = $item;
        }
        else if ($notification->stop_time <= time()) {
            $data['past'][] = $item;
        }
        else {
            $data['current'][] = $item;
        }
    }

    $data['hascurrent'] = count($data['current']) > 0;
    $data['hasfuture'] = count($data['future']) > 0;
    $data['haspast'] = count($data['past']) > 0;

    return $data;
}