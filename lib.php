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
 * @copyright  2019, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/local/scheduled_notifications/locallib.php');

function local_scheduled_notifications_extend_navigation($navigation) {
    global $USER;
	
	if (!isloggedin() || isguestuser()) {
		return;
	}
	
	if (!is_siteadmin() && !is_authorised()) {
		return;
	}

	$nodeHome = $navigation->children->get('1')->parent;
	$node = $nodeHome->add(get_string('scheduled_notifications', 'local_scheduled_notifications'), '/local/scheduled_notifications/notifications.php', navigation_node::TYPE_SYSTEM);
	$node->showinflatnavigation = true;
	
	return;	
}
