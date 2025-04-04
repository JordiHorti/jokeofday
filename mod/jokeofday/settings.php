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

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_configtext(
            'mod_jokeofday/jokeofdayhost',
            get_string('config_server_url', 'mod_jokeofday'),
            get_string('config_server_url_description', 'mod_jokeofday'),
            'https://v2.jokeapi.dev/',
            PARAM_URL
        ));
        $settings->add(new admin_setting_configtext(
            'mod_jokeofday/cache_expiry_time',
            get_string('cache_expiry_time', 'mod_jokeofday'),
            get_string('cache_expiry_time_desc', 'mod_jokeofday'),
            1440,
            PARAM_INT
        ));
}
