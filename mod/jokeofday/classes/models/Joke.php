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

defined('MOODLE_INTERNAL') || die();

/**
 * Object of jokes
 *
 * Information of jokes: id,text,lang,flags.
 *
 * @package    mod_jokeofday
 * @copyright  2025 Jordi
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Joke {
    /** @var int Joke ID */
    public int $jokeid;

    /** @var int Timestamp when the joke was created*/
    public int $timecreated;

    /** @var string Category of the joke */
    public string $category;

    /** @var string Text of the joke */
    public string $text;

    /** @var string Language of the joke */
    public string $lang;

    /** @var array Flags indicating content warnings ( NSFW, political, etc.) */
    public array $flags;

    /** @var object Score */
    public Score $score;


    public function __construct(
        int $jokeid,
        string $category = '',
        string $text = '',
        string $lang = '',
        array $flags = [],
    ) {
        $this->jokeid = $jokeid;
        $this->category = $category;
        $this->text = $text;
        $this->lang = $lang;
        $this->flags = $flags;
        $this->score = Score::get_by_jokeid($this->jokeid);
    }
    /**
     * Get the average media (rating) for the joke.
     *
     * @return float The average rating of the joke.
     */
    public function get_media() {
        global $DB;

        $sql = "SELECT AVG(score) AS rating FROM {jokeofday_score} WHERE joke_id = :jokeid";
        $param = ['jokeid' => $this->jokeid];

        $rating = $DB->get_field_sql($sql, $param);

        return round($rating, 2);
    }
    /**
     * Get the flags of the joke.
     *
     * @return array The flags of the joke.
     */
    public function get_flags() {
        foreach ($this->flags as $flag => $value) {
            if ($value === true) {
                $flags[] = $flag;
            }
        }
        return $flags ?? [];
    }
}
