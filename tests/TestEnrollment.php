<?php
// Done by Neo Yu Hao G5T7
require 'objects/Course.php';
require 'objects/User.php';
require 'objects/Enrollment.php';

class TestEnrollment extends \PHPUnit\Framework\TestCase
{
    public function testInvalidUserObject()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid data type! Expecting User object.");
        $course = new Course(1, "Fundamentals of Xerox WorkCentre 7845", "This course aims to teach the functionalities of Xerox WorkCentre 7845.");
        $enrolment = new Enrollment(1, "Approved", false, "2021-11-22 00:00:01", $course, false, "Yu Hao");
    }

    public function testInvalidCourseObject()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid data type! Expecting Course object.");
        $user = new User("Kai Hao", "Tan Kai Hao", "kaihaot@aio.com", "Operations", "Roving Service Engineer - Junior", "Learner");
        $enrolment = new Enrollment(1, "Approved", false, "2021-11-22 00:00:01", 1, false, $user);
    }

    public function testInvalidUserEnrolment()
    {
        $course = new Course(1, "Fundamentals of Xerox WorkCentre 7845", "This course aims to teach the functionalities of Xerox WorkCentre 7845.");
        $user = new User("Xi Hwee", "Wee Xi Hwee", "xihweew@aio.com", "HR and Admin", "Admin and Call Centre", "Administrator");

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid user! Expecting learner or trainer.");
        $enrolment = new Enrollment(1, "Approved", false, "2021-11-22 00:00:01", $course, false, $user);
    }

    public function testInvalidEnrolmentDateTime()
    {
        $course = new Course(1, "Fundamentals of Xerox WorkCentre 7845", "This course aims to teach the functionalities of Xerox WorkCentre 7845.");
        $user = new User("Kai Hao", "Tan Kai Hao", "kaihaot@aio.com", "Operations", "Roving Service Engineer - Junior", "Learner");

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid date time! Expecting no enrollment date time if pending.");
        $enrolment = new Enrollment(1, "Pending", true, "2021-11-22 00:00:01", $course, false, $user);
    }
}
?>