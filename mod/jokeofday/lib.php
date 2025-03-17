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
 * @package   mod_jokeofday
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * List of features supported in jokeofday module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know or string for the module purpose.
 */
function jokeofday_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return false;
        case FEATURE_SHOW_DESCRIPTION:        return true;
        case FEATURE_MOD_PURPOSE:             return MOD_PURPOSE_COLLABORATION;

        default: return null;
    }

}
function jokeofday_add_instance($data, $mform){
    global $CFG, $DB;

    $data->timemodified = time();
    $data->category = is_array($data->category) ? implode(',', $data->category) : $data->category;

    $data->flags = implode(',', $data->flags);

    $data->id = $DB->insert_record('jokeofday', $data);

    return $data->id;
}

/**
 * Update jokeday instance.
 *
 * @param stdClass $data
 * @param stdClass $mform
 * @return bool true
 */
function jokeofday_update_instance($data, $mform){
    global $CFG, $DB;

    $data->timemodified = time();

    $data->flags = implode(',', $data->flags);

    $data->id = $data->instance;

    $DB->update_record('jokeofday', $data);

    return true;
}
/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @global object
 * @param int $id
 * @return bool
 */
function jokeofday_delete_instance($id) {
    global $DB;

     if (! $jokeofday = $DB->get_record('jokeofday', ['id' => $id])) {
         return false;
     }

    $result = true;

    if (! $DB->delete_records('jokeofday', ['id' => $id])) {
        $result = false;
    }

    return $result;
}

