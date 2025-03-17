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
global $OUTPUT, $PAGE, $DB;

/**
 * Index page 'index'
 *
 * @package   local_test
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_login();

$id = optional_param('id', 0, PARAM_INT);

$course = required_param('course', PARAM_INT);

$category_url = required_param('category', PARAM_INT);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/test/index.php'));
$PAGE->set_title('Moodle courses');
$PAGE->set_heading('Moodle courses');

$sql = "SELECT * FROM mdl_course WHERE category = :category_url";
$params = ['category_url' => $category_url];
$courses = $DB->get_records_sql($sql,$params);

$courses_list = array();
foreach ($courses as $c){
    $courses_list[]=[
        'id' => $c->id,
        'fullname' => $c->fullname,
        'shortname' => $c->shortname
    ];
}

$sql_count = "SELECT COUNT(*) FROM mdl_course WHERE category = :category_url";
$courses_categoria_count = $DB->count_records_sql($sql_count, $params);

$sqlCategory = "SELECT * FROM mdl_course WHERE category != :category_url";
$params = array('category_url' => $category_url);
$courses_categoria = $DB->get_records_sql($sqlCategory,$params);

$courses_other_list = [];
foreach ($courses_categoria as $c) {
    $courses_other_list[] = [
        'id' => $c->id,
        'fullname' => $c->fullname,
        'shortname' => $c->shortname
    ];
}
$sql_count = "SELECT COUNT(*) FROM mdl_course WHERE category != :category_url";
$params = array('category_url' => 1);

$courses_otras_categorias_count = $DB->count_records_sql($sql_count, $params);

$sql_totales = "SELECT COUNT(*) FROM mdl_course";;
$cursos_totales = $DB->count_records_sql( $sql_totales);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = optional_param('fullname', '', PARAM_TEXT);
    $shortname = optional_param('shortname', '', PARAM_TEXT);
    $selected_category = optional_param('category', '', PARAM_INT);
    $idnumber = optional_param('idnumber', '', PARAM_TEXT);
    $summary = optional_param('summary', '', PARAM_RAW);

    if (empty($fullname) || empty($shortname) || empty($selected_category) || empty($idnumber)) {
        echo "Por favor, complete todos los campos obligatorios.";
    } else {

        $existing_course = $DB->get_record('course', ['idnumber' => $idnumber]);

        if ($existing_course) {
            echo "El curso con el ID proporcionado ya existe.";
        } else {

            $new_course = new stdClass();
            $new_course->fullname = $fullname;
            $new_course->shortname = $shortname;
            $new_course->category = $selected_category;
            $new_course->idnumber = $idnumber;
            $new_course->summary = $summary;
            $new_course->summaryformat = FORMAT_HTML;
            $new_course->startdate = time();
            $new_course->enddate = time() + (60 * 60 * 24 * 30);
            $new_course->visible = 1;

            $new_course->id = $DB->insert_record('course', $new_course);

            echo "Curso insertado con éxito!";

            redirect(new moodle_url('/local/test/index.php', [
                'id' => $id,
                'course' => $course,
                'category' => $category_url
            ]));


        }
    }
}
// Transferir datos a Mustache
$templatecontext = (object)[
    'form_action' => new moodle_url('/local/test/index.php'),
    'fullname' => $fullname,
    'shortname' => $shortname,
    'summary' => $summary,
    'category_url' => $category_url,
    'idnumber' => $idnumber,
    'selected_category' => $selected_category,
    'course' => $course,
    'image_url' => (new moodle_url('/local/test/pix/antique-1854416_1280.jpg'))->out(),
    'courses' => $courses_list,
    'courses_count' => $courses_categoria_count,
    'other_courses' => $courses_other_list,
    'other_courses_count' => $courses_otras_categorias_count,
    'total_courses' => $cursos_totales
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_test/index', $templatecontext);
echo $OUTPUT->footer();
