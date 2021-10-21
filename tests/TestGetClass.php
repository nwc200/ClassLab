<?php

class TestGetClass extends \PHPUnit\Framework\TestCase
{
    public function testCalculateTotalMarksScored()
    {
        require 'objects/Course.php';
        require 'objects/Enrollment.php';
        require 'objects/Progress.php';
        require 'objects/User.php';
        
        $user = new User("Test", "Test", "Test@gmail.com", "Dept1", "Design1", "Learner");
        $course = new Course(6, "Fundamentals of VersaLink C405", "This course aims to teach the functionalities of VersaLink C405.");
        $getCourseID = $course->getCourseID();
        $class =  new Class1(1, $getCourseID, 2, "Wei Cheng", "2021-10-5", "2021-10-20", "10:00:00", "16:00:00", "2021-09-15", "2021-09-30");
        // $getClassID = $class->getClassID();
        // $section = new Section(1, $getClassID, "Intro to Xerox WorkCentre");
        // $secNum = $section->getSectionNum();
        // $secName = $section->getSectionName();
        // $addSecClass = $class->addSection($secNum, $secName);
        // $addPrereq = $course->addCoursePrereq($getCourseID, 1);
        // $addClass = $course->getClass1();
        $getClassID = $class->getClassID();
        $enrol = new Enrollment(6, "Approved", true, "2021-03-22 00:00:01", $getCourseID, $getClassID, false, $user);



        $tescase1 = $enrol->getCourseID();
        $tescase2 = $class->getClassID();
        //$QuizRecord, $Marks, $QuestionNum, $StudentAns
        $enrolledCourseID = 6;
        $enroledClassID = 1;
       
        $this->assertSame($enrolledCourseID, $tescase1);
        $this->assertSame($enroledClassID, $tescase2);
    }
}
?>
