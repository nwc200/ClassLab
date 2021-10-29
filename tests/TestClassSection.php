<!-- Done by: Alexandra Ni Jia Xin (01390268) -->

<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsBool;
use function PHPUnit\Framework\assertIsObject;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

require 'objects/Course.php';
require 'objects/Enrollment.php';
require 'objects/Progress.php'; 
require 'objects/User.php';

class TestClassSection extends \PHPUnit\Framework\TestCase
{
    public function testAddSection()
    {
        
        $class = new Class1(1, 20, "Wei Cheng", "2021-10-5", "2021-10-20", "10:00:00", "16:00:00", "2021-09-15", "2021-09-30");

        $class->addSection(1, 'Intro to Data Management');
        $class->addSection(2, 'Data Management');
        
        $getSection = $class->getSection();
        $counter = 1;
        foreach($getSection as $section){
            $this->assertSame($section->getSectionNum(), $counter);
            $counter ++;
        }
    }

    public function testAddSectionMaterials()
    {
        $section =  new Section(1, "Software Product Management");

        $newMaterial1 = new SectionMaterial(1, "pdf", "./materials/week1/Week1b-SPM-fundamentals-v1.0.pdf");
        $newMaterial2 = new SectionMaterial(2, "docs", "./materials/week1/AWS-Docs.docx");
    
        $section->addSectionMaterial($newMaterial1);
        $section->addSectionMaterial($newMaterial2);
        $this->assertSame($newMaterial1->getMaterialNum(), 1);
        $this->assertSame($newMaterial2->getMaterialNum(), 2);
        
    }

    public function testSectionMaterialProgress()
    {
        $preReq = new CoursePrereq("true");
        $class = new Class1(1, 20, "Wei Cheng", "2021-10-5", "2021-10-20", "10:00:00", "16:00:00", "2021-09-15", "2021-09-30");
        $course = new Course(1, "Business Data Management", "This course aims to fundamental of data management", $class, $preReq);
        $user = new User("Yu Hao", "Neo Yu Hao", "yuhaon@aio.com", "password", "Operations", "Roving Service Engineer - Junior", "Learner");

        $enrol = new Enrollment(1, "Approved", true, "2021-11-22 00:00:01", $course, false, $user);
 
        $progress1 = new MaterialProgress($class, $user, true);
        $getComplete1 = $progress1->getComplete();

        $this->assertTrue($getComplete1, true);      
    }
}
?>

<!-- php vendor/bin/phpunit tests/TestClassSection.php -->