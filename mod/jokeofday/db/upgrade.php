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

defined('MOODLE_INTERNAL') || die;

/**
 * Upgrade code for the chat activity
 *
 * @package   mod_jokeofday
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function xmldb_jokeofday_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2025031000) {

        $table = new xmldb_table('jokeofday');

        if (!$DB->get_manager()->table_exists($table)) {

            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null);
            $table->add_field('course', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
            $table->add_field('name', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, null);
            $table->add_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
            $table->add_field('joke_name', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');
            $table->add_field('joke', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');
            $table->add_field('category', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'timemodified');
            $table->add_field('language', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'timemodified');
            $table->add_field('flags', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');
            $table->add_field('type', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'timemodified');
            $table->add_field('amount', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timemodified');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

            if (!$DB->get_manager()->table_exists($table)) {
                $DB->get_manager()->create_table($table);
            }
        }

        upgrade_mod_savepoint(true, 2025031000, 'jokeofday');
    }

//    if ($oldversion < 2025031201) {
//
//        // Define la tabla 'jokeofday_joke'
//        $table = new xmldb_table('jokeofday_joke');
//
//        // Agrega los campos si no existen
//        if (!$DB->get_manager()->table_exists($table)) {
//            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null);
//            $table->add_field('course', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
//            $table->add_field('name', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, null);
//            $table->add_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
//            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
//            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', null);
//            $table->add_field('joke_name', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');
//            $table->add_field('joke', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');
//            $table->add_field('category', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'timemodified');
//            $table->add_field('language', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'timemodified');
//            $table->add_field('flags', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');
//            $table->add_field('type', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'timemodified');
//            $table->add_field('amount', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timemodified');
//
//            // Define la clave primaria
//            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
//
//            // Crea la tabla si no existe
//            $DB->get_manager()->create_table($table);
//        }
//
//        // Actualiza la versión de este plugin
//        upgrade_mod_savepoint(true, 2025031200, 'jokeofday');
//    }

    return true;

}

