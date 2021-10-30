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
    public function testAddPrereq()
    {
        
        $course = new Course(7,"Intro to VersaLink C555", "This course aims to teach the set up of VersaLink C405.");

        $course->addCoursePrereq(7, 5);
        $course->addCoursePrereq(7, 6);
        
        $getPrerequisite = $course->getPrerequisite;
        $counter = 1;
        foreach($getPrerequisite as $prerequiste){
            $this->assertSame($prerequiste->getPrerequisite(), $counter);
            $counter ++;
        }
    }

    // public function testAddPrerequisite()
    // {
        
        
    // }

    
}
?>

<!-- php vendor/bin/phpunit tests/TestCourseSection.php -->