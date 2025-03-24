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

/**
 * Moodle api used to connect with api
 *
 * @package   mod_jokeofday
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_jokeofday\request;

use cache;
use curl;
use mod_jokeofday\models\Joke;
use stdClass;

class jokeapi {
    /**
     * get joke of the  API
     *
     * @param string $category Categoría del chiste (por defecto "Any").
     * @param string $language Idioma (por defecto "en").
     * @param array $flags Lista de flags a bloquear.
     * @param string $type Tipo de chiste ("single" o "twopart").
     * @param string $amount Cantidad de chistes.
     * @return mixed Devuelve un objeto con los datos del chiste o null en caso de error.
     * @throws \dml_exception
     */
    public function get_joke($category, $language, $flags, $type, $amount) {
        global $CFG;

        $expirytime = get_config('mod_jokeofday', 'cache_expiry_time') * 60;

        $cmid = required_param('id', PARAM_INT);
        $cache = cache::make('mod_jokeofday', 'data_cache');

        $cachekey = "$cmid";

        $cachedata = $cache->get($cachekey);

        if ($cachedata && (time() - $cachedata->timecreated) < $expirytime) {
            return $cachedata->joke;
        }

        $joke = $this->get_joke_from_api($category, $language, $flags, $type, $amount);

        $cachedata = new stdClass();
        $cachedata->joke = $joke;

        $cache->set($cachekey, $cachedata);

        return $joke;
    }

    /**
     * Obtiene un chiste de la API externa de chistes y lo guarda en la base de datos si es nuevo.
     *
     * Este método realiza una solicitud a la API externa para obtener chistes de acuerdo con los parámetros
     * proporcionados. Luego, procesa la respuesta y guarda los chistes en la base de datos si no existen
     * previamente. Si se obtienen varios chistes, los procesa y almacena todos en la base de datos.
     *
     * @param string $category Categoría de los chistes solicitados (por ejemplo, "Any").
     * @param string $language Idioma de los chistes solicitados (por ejemplo, "en" para inglés).
     * @param string $flags Flags para filtrar los chistes (por ejemplo, "nsfw" para no mostrar chistes inapropiados).
     * @param string $type Tipo de chistes solicitados ("single" o "twopart").
     * @param int $amount Número de chistes a obtener desde la API (por ejemplo, 1 o 5).
     *
     * @return array Devuelve un array con los chistes obtenidos de la API. Si la respuesta de la API no contiene
     *               chistes, devuelve un array vacío.
     *
     * @throws \coding_exception Si ocurre un error con la solicitud a la API o si los datos no pueden ser procesados correctamente.
     * @throws \dml_exception
     */
    private function get_joke_from_api($category, $language, $flags, $type, $amount) {
        global $DB;

        $url = get_config('mod_jokeofday', 'jokeofdayhost');
        $url .= "joke/$category?lang=$language&blacklistFlags=$flags&type=$type&amount=$amount";

        $curl = new curl();
        $response = $curl->get($url);

        $joke = json_decode($response);

        if ($joke->error != true) {
            if ($amount > 1) {
                $jokes = $joke->jokes;
            } else {
                $jokes = [$joke];
            }
            foreach ($jokes as $j) {
                $flagsarray = get_object_vars($j->flags);
                $j->flags = implode(',', array_keys(array_filter($flagsarray)));

                $data = new Joke(
                    $j->id,
                    $j->category,
                    $j->joke,
                    $j->lang,
                    $j->flags
                );
                $data->joke_id = $j->id;
                $data->lang = $j->lang;
                $data->category = $j->category;
                if ($joke->type === 'twopart') {
                    $data->joke = $j->setup . "<br>" . $j->delivery;
                } else {
                    $data->joke = $j->joke;
                }
                $data->flags = $j->flags;
                $exists = $DB->record_exists('jokeofday_joke', ['joke_id' => $data->joke_id]);
                if (!$exists) {
                    $data->id = $DB->insert_record('jokeofday_joke', $data);
                }
            }
        }
        return $jokes;
    }
}
