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
 * A class to turn filter config text into data
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list\local;

defined('MOODLE_INTERNAL') || die();

class config_parser {
    /** @var array Array of arrays representing lines of config */
    public $lines;

    /**
     * Constructor
     *
     * @param array $text raw text from the filter config textarea
     */
    public function __construct($text) {
        $this->lines = $this->parse_textarea($text);
        foreach ($this->lines as &$line) {
            $line = $this->parse_line($line);
        }
    }

    /**
     * Parse a line of config text into a data array
     *
     * @param array $line raw text from one line of the filter config
     * @param return parsed array of config values
     */
    public function parse_line($line) {
        return array_map(function($item) {
            return trim($item);
        }, explode('|', $line));
    }

    /**
     * Parse the text from the filter config textarea into lines of text
     *
     * @param array $line raw text from the filter config textarea
     * @return array parsed array config lines
     */
    public function parse_textarea($text) {
        return array_map(function($item) {
            return trim($item);
        }, explode("\n", $text));
    }
}
