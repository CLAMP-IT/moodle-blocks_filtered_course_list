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

defined('MOODLE_INTERNAL') || die();

$addons = array(
    "block_filtered_course_list" => array( // Plugin identifier.
        'handlers' => array( // Different places where the plugin will display content.
            'filteredcourselist' => array( // Handler unique name (alphanumeric).
                'delegate'    => 'CoreBlockDelegate', // Delegate (where to display the link to the plugin).
                'method' => 'mobile_block_view',
            )
        ),
        'lang' => [ // Language strings that are used in all the handlers.
            ['blockname', 'block_filtered_course_list'],
            ['pluginname', 'block_filtered_course_list'],
        ],

    )
);