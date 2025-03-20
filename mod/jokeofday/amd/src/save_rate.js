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
 * @package
 */

/* eslint-disable no-unused-vars */
/* eslint-disable no-console */

define([
    'jquery',
    'core/ajax',

], function ($, Ajax) {
    "use strict";

    let ACTIONS = {
        RATING: '[data-action="rating"]'
    };
    let SERVICES = {
        RATING: 'mod_jokeofday_save_rating'
    };


    /**
     * @param {String} region
     * @param {Number} id
     * @constructor
     */
    function Joke(region, id) {
        console.log('Entra por constructor', id);
        this.node = $(region);
        this.id = id;
        console.log('Joke ID:', this.id);
        this.text = this.node.find('.card-text').text();
        this.node.find(ACTIONS.RATING + '[data-id="' + id + '"]').on('change', this.changeRating.bind(this));
    }

    Joke.prototype.changeRating = function (e) {
        let rating = $(e.currentTarget).val();
        console.log("Rating:", rating);
        console.log("Joke ID:", this.id);

        const request = {
            methodname: SERVICES.RATING,
            args: {
                rating: rating,
                jokeid: this.id,
            }
        };
        Ajax.call([request])[0].done(function (response) {
            console.log(response);
            if (response.status == 1) {
                // eslint-disable-next-line no-alert
                alert(response.message);
            } else if (response.status === 'error') {
                // eslint-disable-next-line no-alert
                alert(response.message);
            }
        }).fail(function (fail) {
            console.log(fail);
        });
    };

    Joke.prototype.node = null;

    return {
        /**
         * @param {String} region
         * @param {Number} id
         * @return {Joke}
         */
        init: function (region, id) {
            console.log('Iniciamos JS', region, id);
            return new Joke(region, id);
        }
    };
});
