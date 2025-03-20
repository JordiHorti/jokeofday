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
 * Moodle renderer used to display special elements of the book module
 *
 * @package   mod_jokeofday
 * @copyright 2025 jordi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_jokeofday\output;

use core\exception\moodle_exception;
use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    /**
     * Renderers the main action menu.
     *
     * @param view_page $page
     * @return string The rendered html.
     * @throws moodle_exception
     */
    public function render_view_page(view_page $page): string {
        $data = $page->export_for_template($this);
        return $this->render_from_template('mod_jokeofday/view_page', $data);
    }
}
