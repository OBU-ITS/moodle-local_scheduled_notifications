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
 
require_once($CFG->dirroot . '/local/scheduled_notifications/db_update.php');

// Check if the user is authorised
function is_authorised() {
	global $USER;
	
	if (is_siteadmin()) {
		return true;
	}
	
	$is_authorised = has_notifications_role($USER->id, 5);
	
	return $is_authorised;
}

?>
