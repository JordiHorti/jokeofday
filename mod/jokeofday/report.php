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

global $USER, $PAGE, $CFG, $OUTPUT, $DB;
require_once('../../config.php');

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('jokeofday', $cmid, 0, false, MUST_EXIST);
$course = get_course($cm->course);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

if (!has_capability('mod/jokeofday:view_reports', $context)) {
    throw new required_capability_exception($context, 'mod/jokeofday:view_reports', 'nopermissions', '');
}

echo '<div class="container w-800">';

echo $OUTPUT->header();

$table = new table_sql('joke_report');

$fields = 'DISTINCT j.joke_id AS id,
    j.joke AS joke,
    j.category AS category,
    COUNT(s.joke_id) AS votes,
     COALESCE(AVG(s.score), 0) AS average';

$from = '{jokeofday_joke} j LEFT JOIN {jokeofday_score} s ON j.joke_id = s.joke_id';

$where = '1=1 GROUP BY j.joke_id, j.joke, j.category 
          HAVING 1=1 ORDER BY average DESC, j.joke_id ASC';

$table->sortable(false);

$table->set_sql($fields, $from, $where);

$table->set_count_sql(
    "SELECT COUNT(DISTINCT j.joke_id) FROM {jokeofday_joke} j",
    []
);

//$DB->set_debug(true);

$table->define_columns(['id', 'joke', 'category', 'votes', 'average']);
$table->define_headers(['id', 'joke', 'category', 'votes', 'average']);

//$table->sortable(false, 'average');

$table->set_attribute('class', 'generaltable generaltablefullwidth');

$table->out(20, true);


$renderer = $PAGE->get_renderer('mod_jokeofday');

echo '</div>';

echo $OUTPUT->footer();
