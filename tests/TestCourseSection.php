<!---Done by Wee Xi Hwee !-->

<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsBool;
use function PHPUnit\Framework\assertIsObject;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

require 'objects/Course.php';
require 'objects/Enrollment.php';
require 'objects/User.php';


class TestCourseSection extends \PHPUnit\Framework\TestCase
{
    public function testPrerequisite()
    {
        // courseID, CourseName, CourseDescription
        $course = new Course (4, "How to set up VersaLink C405", "This course aims to teach the set up of VersaLink C405.");
        $permissions = new Permissions("Xi Hwee", "3", "Learner");
        $coursePrereq = $course->getCoursePrereq(4, 3);
        $prereq = 3;
        
        $this->assertTrue($coursePrereq, $prereq); //Should return true...
        
    }

    // public function testAddCourse()
    // {
        
        
    // }

    
}
?>

<!-- php vendor/bin/phpunit tests/TestCourseSection.php -->