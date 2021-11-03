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
    public function testAddCoursePrereq()
    {   $course = new Course (5, "Intro to programming", "This course aims to teach the programming");
        // courseID, CourseName, CourseDescription
        $course->addCoursePrereq(1);
        $course->addCoursePrereq(3);
        $course = $course->getCoursePrereq();
        $counter = count($course);
        $this->assertSame(2, $counter); //Should return true...
        
    }

    public function testAddCourse()
    {
       
        $course = new Course (5, "Intro to programming", "This course aims to teach the programming");
        $course = $course->getCourseName();
        $this->assertEquals("Intro to programming", $course);
        

        
    }

    //  public function testGetCourse()
    // {
        
        
    // }




    
}
?>

<!-- php vendor/bin/phpunit tests/TestCourseSection.php -->