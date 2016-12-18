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
 * This file defines the renderer for the Filtered course list block.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_filtered_course_list\output;

defined('MOODLE_INTERNAL') || die;

require_once(dirname(__FILE__) . '/locallib.php');

/**
 * Helper class for the "All courses" link.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class footer implements \renderable, \templatable {

    /** @var string Text for footer link */
    public $linktext;
    /** @var string Link to the Course index page */
    public $url;
    /** @var boolean Whether or not to display the link */
    public $visible = false;

    /**
     * Constructor
     *
     * @param array $params A list of settings used to determine the link display
     */
    public function __construct($params = array()) {

        if ($params['usertype'] == 'manager' || $params['hideallcourseslink'] == BLOCK_FILTERED_COURSE_LIST_FALSE) {
            $this->visible = true;
        }
        if ($params['liststyle'] == 'empty_block') {
            $this->visible = false;
        }

        $this->url = new \moodle_url('/course/index.php');

        $this->linktext = get_string('fulllistofcourses');
        if ($params['liststyle'] == 'generic_list') {
            $this->linktext = get_string('searchcourses');
        }
    }

    /**
     * Export the object data for use by a template
     *
     * @param renderer_base $output A renderer_base object
     * @return array $data Template-ready data
     */
    public function export_for_template(\renderer_base $output) {
        $data = array(
            'linktext' => $this->linktext,
            'url'      => $this->url,
            'visible'  => $this->visible,
        );
        return $data;
    }
}

/**
 * Renderer for the Filtered course list block.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \plugin_renderer_base {

    /**
     * Render HTML for the "All courses" link
     *
     * @param footer $footer A footer object
     * @return string The rendered html
     */
    protected function render_footer (footer $footer) {
        $data = $footer->export_for_template($this);
        return $this->render_from_template('block_filtered_course_list/footer', $data);
    }
}
