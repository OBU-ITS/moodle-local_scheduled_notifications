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
 * Scheduled notifications - Provide left hand navigation links
 *
 * @package    local_scheduled_notifications
 * @author     Peter Welham
 * @copyright  2017, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


function local_scheduled_notifications_extend_navigation($navigation) {
    global $USER;
	
	if (!isloggedin() || isguestuser()) {
		return;
	}
	
	if (!is_siteadmin() && ($USER->lastname != '- Notifications')) {
		return;
	}

	// Find the 'notifications' node
	$nodeParent = $navigation->find(get_string('notifications', 'local_scheduled_notifications'), navigation_node::TYPE_SYSTEM);
	
	// If necessary, add the 'notifications' node to 'home'
	if (!$nodeParent) {
		$nodeHome = $navigation->children->get('1')->parent;
		if ($nodeHome) {
			$nodeParent = $nodeHome->add(get_string('notifications', 'local_scheduled_notifications'), null, navigation_node::TYPE_SYSTEM);
		}
	}

	// Add the option to list, add or amend their notifications
	if ($nodeParent) {
		$node = $nodeParent->add(get_string('scheduled_notifications', 'local_scheduled_notifications'), '/local/scheduled_notifications/notifications.php');
	}
}
