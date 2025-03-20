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
 * Resource configuration form
 *
 * @package   mod_jokeofday
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
global $CFG;

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_jokeofday_mod_form extends moodleform_mod {
    /**
     * @throws coding_exception
     */
    function definition() {
        global $PAGE;

        $customcategories = [
            'Any' => get_string('any', 'jokeofday'),
            'Programming' => get_string('programming', 'jokeofday'),
            'Misc' => get_string('misc', 'jokeofday'),
            'Dark' => get_string('dark', 'jokeofday'),
            'Pun' => get_string('pun', 'jokeofday'),
            'Spooky' => get_string('spooky', 'jokeofday'),
            'Christmas' => get_string('christmas', 'jokeofday'),

        ];

        $customlanguages = [
            'en' => 'English',
            'cs' => 'Czech',
            'de' => 'German',
            'es' => 'ES',
            'fr' => 'French',
            'pt' => 'Portuguese',
        ];
        $customflags = [
            'nsfw' => 'nsfw',
            'religious' => 'religious',
            'political' => 'political',
            'racist' => 'racist',
            'sexist' => 'sexist',
            'explicit' => 'explicit',
        ];
        $customtypes = [
            'single' => 'single',
            'twopart' => 'twopart',
        ];


        $PAGE->force_settings_menu();

        $mform = $this->_form;

        // Campo para nombre
        $mform->addElement('text', 'name', get_string('name', 'jokeofday'), ['size' => '25', 'maxlength' => '255']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addHelpButton('name', 'name', 'jokeofday');

        $this->standard_intro_elements();

        // Campo para seleccionar categoría
        $category = $mform->addElement('select', 'category', get_string('category', 'jokeofday'), $customcategories);
        $category->setMultiple(true);

        // Campo para seleccionar idioma
        $mform->addElement('select', 'language', get_string('language', 'jokeofday'), $customlanguages);
        $mform->setType('language', PARAM_ALPHA); // PARAM_ALPHA porque es texto (por ejemplo, 'en', 'es').

        // Campo para seleccionar banderas
        $flags = $mform->addElement('select', 'flags', get_string('flags', 'jokeofday'), $customflags);
        $flags->setMultiple(true);

        // Campo para seleccionar tipo de broma
        $mform->addElement('select', 'type', get_string('type', 'jokeofday'), $customtypes);

        $mform->addElement('text', 'amount', get_string('amount', 'jokeofday'), ['size' => '10', 'maxlength' => '5']);
        $mform->setType('amount', PARAM_INT);
        $mform->addHelpButton('amount', 'amount', 'jokeofday');

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
    function validation($data, $files) {
        $errors = [];

        if (!is_numeric($data['amount']) || (int)$data['amount'] <= 0) {
            $errors['amount'] = get_string('amount_error', 'jokeofday');
        }

        return $errors;
    }
}
