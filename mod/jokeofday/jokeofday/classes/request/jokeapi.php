<?php
/**
 * Moodle api used to connect with api
 *
 * @package   mod_jokeofday
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_jokeofday\request;

use curl;
use stdClass;

class jokeapi
{
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
    public function get_joke($category, $language, $flags, $type, $amount)
    {
        global $DB, $COURSE;

        $url = $url = get_config('mod_jokeofday', 'jokeofdayhost');
        $url .= "joke/$category?lang=$language&blacklistFlags=$flags&type=$type&amount=$amount";

        $curl = new curl();
        $response = $curl->get($url);

        $joke = json_decode($response);

        if ($amount > 1) {
            $jokes = $joke->jokes;
        }
        else{
            $jokes = [$joke->joke];
        }

        foreach ($jokes as $joke) {
            $data = new stdClass();
            $data->joke_id = $joke->id;
            $data->lang = $joke->lang;
            $data->category = $joke->category;
            if ($joke->type === 'twopart') {
                $data->joke = $joke->setup . "<br>" . $joke->delivery;
            } else {
                $data->joke = $joke->joke;
            }

            $data->flags = $flags;

            $exists = $DB->record_exists('jokeofday_joke', ['joke_id' => $data->joke_id]);

            if( !$exists){
                $data->id = $DB->insert_record('jokeofday_joke',$data);
            }

        }

        return json_decode($response);

    }
}

