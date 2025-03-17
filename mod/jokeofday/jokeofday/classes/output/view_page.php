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



use mod_jokeofday\request\jokeapi;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class view_page implements templatable, renderable {
    /**
     * Exports the navigation buttons around the book.
     *
     * @param renderer_base $output renderer base output.
     * @return stdClass Data to render.
     * @throws \coding_exception
     */
    public function export_for_template(renderer_base $output): stdClass
    {
        global $DB;

        $cmid = optional_param('id', 0, PARAM_INT);

        $cm = get_coursemodule_from_id('jokeofday', $cmid, 0, false, MUST_EXIST);

        // Obtener la instancia del módulo 'jokeofday' usando el id de la instancia
        $instance = $DB->get_record('jokeofday', ['id' => $cm->instance], '*', MUST_EXIST);

        $data = new stdClass();
        $data->title = get_string('pluginname', 'mod_jokeofday');
        $data->question = get_string('question', 'mod_jokeofday');

        $data->category = $instance->category ?? null;
        $data->language = $instance->language ?? null;
        $data->flags = $instance->flags ?? null;
        $data->flagsArray = explode(',', $data->flags);
        $data->type = $instance->type ?? null;
        $data->joke = $instance->joke ?? null;
        $data->amount = $instance->amount ?? null;
        $data->rate = get_string('rate', 'mod_jokeofday');


        $jokeapi = new jokeapi();
        $jokeData = $jokeapi->get_joke($data->category, $data->language, $data->flags, $data->type, $data->amount);

        $flags = [];

        if (isset($jokeData->flags) && is_array($jokeData->flags)) {
            foreach ($jokeData->flags as $flag => $value) {
                if ($value === true) {
                    $flags[] = get_string($flag, 'mod_jokeofday');
                }
            }

        }
        // Inicializar el array de chistes
        $data->jokes = [];
        $jokeText = '';

        // Si la cantidad es mayor a uno, creamos un array para almacenarlos
        if (isset($jokeData->amount) && $jokeData->amount > 1) {
            foreach ($jokeData->jokes as $joke) {
                if (isset($joke->type) && $joke->type === 'twopart') {
                    // Si es un chiste de dos partes, concatenar las dos partes
                    $jokeText = $joke->setup . "<br>" . $joke->delivery;
                } else {

                    $jokeText = $joke->joke;
                }

                $data->jokes[] = (object) [
                    'category' => $joke->category,
                    'jokeText' => $jokeText
                ];
            }
        } else {
            if (isset($jokeData->type)) {
                if ($jokeData->type === 'twopart') {
                    $jokeText = $jokeData->setup . "<br>" . $jokeData->delivery;
                } elseif ($jokeData->type === 'single') {
                    // Si es un chiste de una sola parte, usamos el valor de 'joke'
                    $jokeText = isset($jokeData->joke) ? $jokeData->joke : '';
                }
            }

            $data->joke = $jokeText;
        }

        // Devolver los datos procesados a la plantilla
        return $data;
    }
}