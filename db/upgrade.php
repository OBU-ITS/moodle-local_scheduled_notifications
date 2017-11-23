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
 * @author     Peter Welham
 * @copyright  2017, Oxford Brookes University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

function xmldb_local_scheduled_notifications_upgrade($oldversion = 0) {
    global $DB;
    $dbman = $DB->get_manager();

    $result = true;

    if ($oldversion < 2017110800) {

		// Define table local_scheduled_notification (name is max 28 chars)
		$table = new xmldb_table('local_scheduled_notification');

		// Add fields
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('owner_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('title', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
		$table->add_field('text', XMLDB_TYPE_TEXT, 'small', null, XMLDB_NOTNULL, null, null);
		$table->add_field('start_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('stop_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('updater_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('update_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

		// Add keys
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

		// Conditionally create table
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}

		// obu_forms savepoint reached
		upgrade_plugin_savepoint(true, 2017110800, 'local', 'scheduled_notifications');
    }
    
    return $result;
}
