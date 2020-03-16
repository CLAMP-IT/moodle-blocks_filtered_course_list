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
 * Mobile output functions.
 *
 * @package mod_oucontent
 * @copyright 2018 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list\output;

defined('MOODLE_INTERNAL') || die();

use block_filtered_course_list;

/**
 * Mobile output functions.
 */
class mobile
{

    /**
     * Returns the SC document view page for the mobile app.
     *
     * @param array $args Arguments from tool_mobile_get_content WS
     * @return array HTML, javascript and otherdata
     */
    public static function mobile_block_view(array $args): array
    {
        $args = (object)$args;
        global $OUTPUT;
        global $CFG;
        $block_content = new \block_filtered_course_list\block_content($args->instanceid, true);
        $rubrics = $block_content->get_content();
        foreach ($rubrics as $rubric) {
            unset($rubric->expanded);
            unset($rubric->config);
            $rubric->courses = array_values($rubric->courses);
            foreach ($rubric->courses as $course) {
                $course->fullname = format_string(strip_tags($course->fullname));
            }
        }
        $data = [];
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('block_filtered_course_list/mobile_block_view', $data),
                ],
            ],
            'javascript' => '',
            'otherdata' => ['rubrics' => json_encode($rubrics)],
            'files' => []
        ];

    }
}
