<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
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
 * This file contains the Activity modules block.
 *
 * @package    block_activities
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');

class block_activities extends block_list {
    public function init() {
        $this->title = get_string('pluginname', 'block_activities');
    }

    public function get_content() {
        global $CFG, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->footer = '';

        $course = $this->page->course;

        require_once($CFG->dirroot . '/course/lib.php');

        $modinfo = get_fast_modinfo($course);

        // Set up completion object and check it is enabled.
        $completion = new completion_info($course);

        foreach ($modinfo->cms as $cm) {

            $completiondata = $completion->get_data($cm, false, $USER->id);
            $actcom = '';
            if ($completiondata->completionstate == 1) {
                $actcom = ' - Completed';
            }

            $createddate = userdate($cm->added, '%d-%b-%Y');
            $name = $cm->id . ' - ' . $cm->modname . ' - ' . $createddate . $actcom;

            if ($cm->modname == 'resource') {
                $link = $CFG->wwwroot . '/course/resources.php?id=' . $course->id;
                $this->content->items[] = '<a href="' . $link . '">' . $name. '</a>';
            } else {
                $link = $CFG->wwwroot . '/mod/' . $cm->modname . '/index.php?id=' . $course->id;
                $this->content->items[] = '<a href="' . $link . '">' . $name . '</a>';
            }
        }

        return $this->content;
    }

    /**
     * This block shouldn't be added to a page if the global search advanced feature is disabled.
     *
     * @param moodle_page $page
     * @return bool
     */
    public function can_block_be_added(moodle_page $page): bool {
        global $CFG;

        return $CFG->enableactivities;
    }
}
