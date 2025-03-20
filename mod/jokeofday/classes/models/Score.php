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

namespace mod_jokeofday\models;

use core\exception\moodle_exception;

defined('MOODLE_INTERNAL') || die();

/**
 * Object of scores
 *
 * Information of jokes: id,text,media,etc.
 *
 * @package    mod_jokeofday
 * @copyright  2025 Jordi
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Score {
    /** @var int Score ID */
    public int $id;

    /** @var int User ID */
    public int $userid;

    /** @var int Joke ID */
    public int $jokeid;

    /** @var int Score */
    public int $score;

    /** @var int Timestamp when the score was rated */
    public int $timecreated;

    public function __construct(int $id, int $userid, int $jokeid, int $score, int $timecreated) {
        $this->id = $id;
        $this->userid = $userid;
        $this->jokeid = $jokeid;
        $this->score = $score;
        $this->timecreated = $timecreated;
    }

    public static function get_by_jokeid(int $jokeid) {
        global $DB;

        try {
            $records = $DB->get_record('jokeofday_score', ['joke_id' => $jokeid]);

            if (!$records) {
                return new Score(0, 0, $jokeid, 0, time());
            }

            return new Score(
                $records->id ?? 0,
                $records->userid ?? 0,
                $records->joke_id ?? $jokeid,
                $records->score ?? 0,
                $records->timecreated ?? time()
            );
        } catch (moodle_exception $e) {
            return ['status' => 'error', 'message' => get_string('error_get_rating', 'mod_jokeofday')];
        }
    }
}
