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

// This page prints a particular instance of chat.

use mod_jokeofday\output\view_page;

require(__DIR__.'/../../config.php');
global $PAGE,$OUTPUT;

$id = required_param('id', PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($id);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/jokeofday/view.php', ['id' => $cm->id]);
$PAGE->set_title('Este es el título');
$PAGE->set_heading('Este es el título');

// Print the page header.
echo $OUTPUT->header();

$renderer = $PAGE->get_renderer('mod_jokeofday');

$page = new view_page();

echo $renderer->render($page);

//echo '<div class="card" style="width: 45rem;">';
//echo '<div class="card-body">';
//echo '<h5 class="card-title">Chiste del día</h5>';
//echo '<h6 class="card-subtitle mb-2 text-muted">Nombre: ' . $jokeofday->name .' - Categoría: ' . $jokeofday->category . '</h6>';
//echo '<p class="card-text">' . $joke . '</p>';
//echo '<select class="form-select">
//      <option selected>Puntúa el chiste</option>
//      <option value="1">1</option>
//      <option value="2">2</option>
//      <option value="3">3</option>
//      <option value="4">4</option>
//      <option value="5">5</option>
//      </select>';
//echo '<button id="ptn" class="btn btn-primary ml-5">Enviar</button>';
//echo '</div>';
//echo '</div>';
//echo $OUTPUT->box_end();


echo $OUTPUT->footer();