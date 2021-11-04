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
    {
        // courseID, CourseName, CourseDescription
        $course = new Course (5, "Intro to programming", "This course aims to teach the programming");
        $course->addCoursePrereq(1);
        $course->addCoursePrereq(3);
        $course = $course->getCoursePrereq();
        $counter = count($course);
        $this->assertSame(2, $counter);
        
    }

    public function testgetCourse()
    {
       
        $course = new Course (5, "Intro to programming", "This course aims to teach the programming");
        $course = $course->getCourseName();
        $this->assertEquals("Intro to programming", $course);
        
    }

    public function testgetCoursePrereq()
    {
        $course = new Course (5, "Programming for Xerox WorkCenter with Card Access .", "This course aims to teach the fundamentals of programming for Xerox WorkCentre.");
        $course->getCoursePrereq(4);
        $prereqResult = 4;
        $this->assertSame(4, $prereqResult);

        
    }
    public function testgetCourseID()
    {
        $course = new Course (1, "Fundamentals of Xerox WorkCentre 7845", "This course aims to teach the functionalities of Xerox WorkCentre 7845.");
        $eligibility = $course->getCourseID(1);

        $this->assertEquals(1, $eligibility);

        
    }

    public function testAddCourseClass()
    {
        
        $course = new Course (5, "Programming for Xerox WorkCenter with Card Access .", "This course aims to teach the fundamentals of programming for Xerox WorkCentre.");
        //$ClassID, $CourseID, $ClassSize, $TrainerUserName, $StartDate, $EndDate, $StartTime, $EndTime, $SelfEnrollmentStart, $SelfEnrollmentEnd
        $course->addClass1(1, 5, 3, "Wei Cheng", "2021-11-30", "2021-11-30", "08:00:00", "17:00:00", "2021-11-15", "2021-11-30");
        $course->addClass1(2, 5, 5, "Alexandra", "2021-11-25", "2021-11-25", "08:00:00", "17:00:00", "2021-11-15", "2021-11-25");
        
        $getClass1 = $course->getClass1();
        $counter = count($getClass1);
        $this->assertSame(2, $counter);
        
       
    }





}


?>

<!-- php vendor/bin/phpunit tests/TestCourseSection.php -->