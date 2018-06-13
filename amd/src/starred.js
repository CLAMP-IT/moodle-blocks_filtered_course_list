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
 * This file contains an AMD/jQuery module to handle starring/unstarring of courses.
 *
 * @package    block_filtered_course_list
 * @copyright  2018 CLAMP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery'], function($) {
    return {
        init: function() {
            $('.block-fcl__starlink').on({
                'mouseenter mouseleave': function() {
                    $(this).children('i').toggleClass('fa-star fa-star-o');
                },
                'click': function(e) {
                    require(['core/ajax'], function(ajax) {
                        ajax.call([{
                            methodname: 'block_filtered_course_list_toggle_starred',
                            args: {},
                            done: function() {
                                $('.block-fcl__starlink i').toggleClass('fa-star fa-star-o');
                            },
                        }]);
                    });
                    e.stopPropagation();
                }
            });
        }
    };
});
