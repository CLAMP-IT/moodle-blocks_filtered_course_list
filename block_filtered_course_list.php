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
 * This file contains the class used to display a Filtered course list block.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/lib.php');
require_once(dirname(__FILE__) . '/locallib.php');

/**
 * The Filtered course list block class
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_filtered_course_list extends block_base {

    /**
     * Set the initial properties for the block
     */
    public function init() {
        $this->title   = get_string('blockname', 'block_filtered_course_list');
    }

    /**
     * The FCL block uses a settings.php file
     *
     * @return bool Returns true
     */
    public function has_config() {
        return true;
    }

    /**
     * Set the instance title and merge instance config as soon as nstance data is loaded
     */
    public function specialization() {

        if (isset($this->config->title) && $this->config->title != '') {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        }

        if (isset($this->config->filters) && $this->config->filters != '') {
            $this->fclconfig->filters = $this->config->filters;
        }
    }

    /**
     * Allow multiple instances
     *
     * @return bool Returns true
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Returns the role that best describes the block... 'region'
     *
     * @return string 'region'
     */
    public function get_aria_role() {
        return 'region';
    }

    /**
     * Return the block contents
     *
     * @return stdClass The block contents
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }
        $block_content = new \block_filtered_course_list\block_content($this->instance->id);
        $this->content = $block_content->get_content();
        return $this->content;
    }

}
