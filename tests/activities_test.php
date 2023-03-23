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

namespace block_activities;

use advanced_testcase;
use block_activities;
use context_course;

class activities_test extends advanced_testcase {
    public static function setUpBeforeClass(): void {
        require_once(__DIR__ . '/../../moodleblock.class.php');
        require_once(__DIR__ . '/../block_activities.php');
    }

    /**
     * Test the behaviour of can_block_be_added() method.
     *
     * @covers ::can_block_be_added
     */
    public function test_can_block_be_added(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        // Create a course and prepare the page where the block will be added.
        $course = $this->getDataGenerator()->create_course();
        $forum = $this->getDataGenerator()->create_module('forum', array('course' => $course->id));
        $glossary = self::getDataGenerator()->create_module('glossary', ['course' => $course]);

        $page = new \moodle_page();
        $page->set_context(context_course::instance($course->id));
        $page->set_pagelayout('course');

        $block = new block_activities();

        // If activities advanced feature is enabled, the method should return true.
        set_config('enableactivities', true);
        $this->assertTrue($block->can_block_be_added($page));

        // However, if the activities advanced feature is disabled, the method should return false.
        set_config('enableactivities', false);
        $this->assertFalse($block->can_block_be_added($page));
    }
}
