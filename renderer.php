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
 * Helper class for list items.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class list_item implements \renderable, \templatable {

    /** @var array of CSS classes for the list item */
    public $classes = array('block_filtered_course_list_list_item');
    /** @var string Display text for the list item link */
    public $displaytext;
    /** @var array of CSS classes for the list item link */
    public $linkclasses = array('block_filtered_course_list_list_item_link');
    /** @var string Text to display when the list item link is hovered */
    public $title;
    /** @var moodle_url object The destination for the list item link */
    public $url;

    /**
     * An abstract constructor
     * Decendant classes should define the class properties.
     *
     * @param mixed $itemobject A object from which to derive the class properties
     * @param object $config The plugin options object
     * @param string $type The type of link object that we want to build
     */
    public function __construct($itemobject, $config, $type='course') {
        switch ($type){
            case 'course':
                $this->classes[] = 'fcl-course-link';
                $this->displaytext = \block_filtered_course_list_lib::coursedisplaytext($itemobject, $config->coursenametpl);
                if (!$itemobject->visible) {
                    $this->linkclasses[] = 'dimmed';
                }
                $this->title = format_string($itemobject->shortname);
                $this->url = new \moodle_url('/course/view.php?id=' . $itemobject->id);
                $this->summaryurl = new \moodle_url('/course/info.php?id=' . $itemobject->id);
                break;
            case 'category':
                $this->classes[] = 'fcl-category-link';
                $this->displaytext = format_string($itemobject->name);
                if (!$itemobject->visible) {
                    $this->linkclasses[] = 'dimmed';
                }
                $this->title = '';
                $this->url = new \moodle_url('/course/index.php?categoryid=' . $itemobject->id);
                $this->summaryurl = '';
                break;
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
            'classes'     => implode(' ', $this->classes),
            'displaytext' => $this->displaytext,
            'linkclasses' => implode(' ', $this->linkclasses),
            'title'       => $this->title,
            'url'         => $this->url,
            'summaryurl'  => $this->summaryurl,
        );
        return $data;
    }
}

/**
 * Helper class for rendering rubrics.
 *
 * @package    block_filtered_course_list
 * @copyright  2016 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rubric implements \renderable, \templatable {

    /** @var array Parameters for the rubric display */
    public $params = array(
        'divid'      => '', /* id for heading div */
        'divclasses' => '', /* classes for heading div */
        'exp'        => '', /* value for aria-expanded */
        'slct'       => '', /* value for aria-selected */
        'label'      => '', /* heading text */
        'ulid'       => '', /* id for item list */
        'ulclasses'  => '', /* classes for item list */
        'hidden'     => '', /* value for aria-hidden */
        'items'      => array(), /* courses to display */
        'fclconfig'  => array(), /* config settings for the FCL */
    );
    /** @var array item link data */
    public $items = array();

    /**
     * Constructor
     *
     * @param array $params A list of settings used to determine the rubric display
     */
    public function __construct($params = array()) {
        $this->params = array_merge($this->params, $params);
        $this->items = array_map(function($listitem) {
            return new list_item($listitem, $this->params['fclconfig'], 'course');
        }, $this->params['items']);
    }

    /**
     * Export the object data for use by a template
     *
     * @param renderer_base $output A renderer_base object
     * @return array $data Template-ready data
     */
    public function export_for_template(\renderer_base $output) {
        $itemdata = array_map(function($item) use ($output) {
            $export = $item->export_for_template($output);
            return $export;
        }, $this->items);
        $data = array(
            'divid'      => $this->params['divid'],
            'divclasses' => $this->params['divclasses'],
            'exp'        => $this->params['exp'],
            'slct'       => $this->params['slct'],
            'label'      => $this->params['label'],
            'ulid'       => $this->params['ulid'],
            'ulclasses'  => $this->params['ulclasses'],
            'hidden'     => $this->params['hidden'],
            'items'      => array_values($itemdata),
        );
        return $data;
    }
}

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

    /**
     * Render HTML for list item
     *
     * @param list_item $listitem
     * @return string The rendered html
     */
    protected function render_list_item (list_item $listitem) {
        $data = $listitem->export_for_template($this);
        return $this->render_from_template('block_filtered_course_list/list_item', $data);
    }

    /**
     * Render HTML for rubric
     *
     * @param rubric $rubric
     * @return string The rendered html
     */
    protected function render_rubric (rubric $rubric) {
        $data = $rubric->export_for_template($this);
        return $this->render_from_template('block_filtered_course_list/rubric', $data);
    }
}
