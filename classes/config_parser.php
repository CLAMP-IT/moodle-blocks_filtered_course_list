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
 * A class to handle lines of filter config
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list;

defined('MOODLE_INTERNAL') || die();

class config_parser {
    public function __construct($text) {
        $this->lines = $this->parse_textarea($text);
        foreach ($this->lines as &$line) {
            $line = $this->parse_line($line);
        }
    }

    public static function parse_line($line) {
        return array_map(function($item) {
            return trim($item);
        }, explode('|', $line, count($acceptedconfig)));
    }

    public static function parse_textarea($text) {
        return array_map(function($item) {
            return trim($item);
        }, explode("\n", $text));
    }
}
