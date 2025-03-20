<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace mod_jokeofday\external;

use core\exception\moodle_exception;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use stdClass;

class jokeofday_external extends external_api {
    public static function execute_parameters() {
        return new external_function_parameters([
            'jokeid' => new external_value(
                PARAM_INT,
                'id of joke'
            ),
            'rating' => new external_value(
                PARAM_INT,
                'rating of joke'
            ),
        ]);
    }

    /**
     * @throws \coding_exception
     * @throws \invalid_parameter_exception
     */
    public static function execute($jokeid, $rating) {
        global $DB, $USER;

        $params = self::validate_parameters(self::execute_parameters(), [
            'jokeid' => $jokeid,
            'rating' => $rating,
        ]);

        try {
            $record = new stdClass();
            $record->user_id = $USER->id;
            $record->joke_id = $params['jokeid'];
            $record->score = $params['rating'];
            $record->timecreated = time();

            $DB->insert_record('jokeofday_score', $record);
        } catch (moodle_exception $e) {
            return ['status' => 'error', 'message' => get_string('error_saving_rating', 'mod_jokeofday')];
        }
        return ['status' => true, 'message' => get_string('succes_missage', 'mod_jokeofday')];
    }
    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the request'),
            'message' => new external_value(PARAM_TEXT, 'Message returned'),
        ]);
    }
}
