<?php
namespace mod_jokeofday\external;

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use stdClass;

class jokeofday_services  extends \core_external\external_api {
    public static function execute_parameters() {
        return new external_function_parameters([
            'user_id' => new external_value(PARAM_INT, 'id of user'),
            'joke_id' => new external_value(
                PARAM_INT,
                'id of joke'
            ),
            'score' => new external_value(
                PARAM_INT,
                'score of joke'
            ),
            'joke' => new external_value(
                PARAM_RAW,
                'text of joke'
            ),
            'timecreated' => new external_value(
                PARAM_INT,
                'timecreated of joke'
            )
        ]);
    }
    function jokeofday_insert_score($user_id, $joke_id, $score, $joke_text) {
        global $DB;
        $params = self::validate_parameters(self::execute_parameters(), ['jokeofday_score' => $user_id, $joke_id, $score, $joke_text]);

        $joke_score = new stdClass();
        $joke_score->user_id = $user_id;
        $joke_score->joke_id = $joke_id;
        $joke_score->score = $score;
        $joke_score->joke = $joke_text;
        $joke_score->timecreated = time();

        $joke_score->id = $DB->insert_record('jokeofday_joke', $joke_score);
    }
}