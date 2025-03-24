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
 * Output the action menu for this activity.
 *
 * @package   mod_jokeofday
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_jokeofday\output;


use mod_jokeofday\models\Joke;
use mod_jokeofday\request\jokeapi;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class view_page implements renderable, templatable {
    /**
     * Exports the navigation buttons around the book.
     *
     * @param renderer_base $output renderer base output.
     * @return stdClass Data to render.
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function export_for_template(renderer_base $output): stdClass {
        global $DB, $USER;

        $cmid = optional_param('id', 0, PARAM_INT);

        $cm = get_coursemodule_from_id('jokeofday', $cmid, 0, false, MUST_EXIST);

        $instance = $DB->get_record('jokeofday', ['id' => $cm->instance], '*', MUST_EXIST);

        $data = new stdClass();
        $data->title = get_string('pluginname', 'mod_jokeofday');
        $data->userId = $USER->id;
        $data->id = $instance->id;
        /*
        // Para pruebas de error
         $instance->category .= "error";
        */

        $jokeapi = new jokeapi();
        $jokedata = $jokeapi->get_joke(
            $instance->category,
            $instance->language,
            $instance->flags,
            $instance->type,
            $instance->amount
        );
//var_dump($jokedata);
        $jokes = [];
        if ($jokedata->error) {
            $error = true;
            $data->errormessage = $jokedata->additionalInfo;
        } else {
            $error = false;
             $jokesdata = $jokedata->jokes ?? [$jokedata->joke];
            foreach ($jokedata as $j) {
                $joke = new Joke(
                    $j->id,
                    $j->category,
                    $j->joke,
                    $j->lang,
                    $j->flags
                );

                $jokes[] = [
                    'id' => $joke->jokeid,
                    'category' => $joke->category,
                    'text' => $joke->text,
                    'flags' => $j->flags,
                    'media' => $joke->get_media(),
                    'score' => $joke->score->score,
                ];
            }
        }
        $data->jokes = $jokes;

       // var_dump($joke->get_flags());

        $data->error = $error;

        return $data;
    }
// protected function get_joke_text($joke) {
//
// return $joke->joke ?? $joke->jokes;
// }
// protected function get_joke_media($joke) {
// global $DB;
//
// $sql = "SELECT AVG(score) AS rating FROM {jokeofday_score} WHERE joke_id = :jokeid";
// $param = ['jokeid' => $joke->id];
//
// $rating = $DB->get_field_sql($sql, $param);
//
// return round($rating, 2);
// }
// protected function get_joke_flags($joke) {
// foreach ($joke->flags as $flag => $value) {
// if ($value === true) {
// $flags[] = $flag;
// }
// }
// return $flags ?? [];
// }
}
