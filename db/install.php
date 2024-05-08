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
* Scheduled notifications - database upgrade
*
* @package    local_scheduled_notifications
* @category   local
* @author     Joe Souch
* @copyright  2024, Oxford Brookes University
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*
*/

defined('MOODLE_INTERNAL') || die();

function xmldb_local_scheduled_notifications_install() {
    global $CFG;

    if(!$CFG->isdev) {
        return;
    }

    // TODO : Create course (Idnumber: 'SUBS_NOTIFICATIONS')

    // TODO : Create user (Name: 'Test Schedule notifications user')

    // TODO : Grant user capability (Capability: 'local/scheduled_notifications:update')

    // TODO : Enrol user on course (Method: Manual)
}

