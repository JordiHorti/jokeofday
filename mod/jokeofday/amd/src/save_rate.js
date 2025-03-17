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
 * @package mod_jokeofday
 * @author  2025 3iPunt <https://www.tresipunt.com/>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* eslint-disable no-unused-vars */
/* eslint-disable no-console */

define([
    'jquery',
    'core/ajax',

], function($, Str, Ajax) {
    "use strict";

    return {
        /**
         * @param {int} region
         * @return {Prueba}
         */
        init: function(selector) {
            console.log('Iniciamos JS');
            const jokeContainer = $(selector);
            const ratingSelect = jokeContainer.find(".form-select");

            ratingSelect.on('change', function() {

                const score = $(this).val();
                const timecreated = new Date().toISOString();

                let request = {
                    args: {
                        userid: jokeContainer.data('user-id'),
                        jokeid: jokeContainer.data('joke-id'),
                        score: score,
                        joke: jokeContainer.find('.joke-text').text(),
                        timecreated: timecreated
                    }
                };

                Ajax.call([request])[0].done(function(response){
                    alert('Puntuación guardada con éxito' + response);
                }).fail(function (error){
                  alert('Error guardando la puntuación: ' +  error)
                });
            });
        }
    };
});