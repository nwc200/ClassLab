<!-- Done by: G5T7 Alexandra Ni Jia Xin -->

<?php

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

        $countSections = count($getSection);
        $this->assertSame(2, $countSections);
    }

    public function testGetSectionNum()
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

    public function testGetSectionMaterialType()
    {
        $section =  new Section(1, "Software Product Management");
        $newMaterial1 = new SectionMaterial(1, "pdf", "./materials/week1/Week1b-SPM-fundamentals-v1.0.pdf");
    
        $section->addSectionMaterial($newMaterial1);
        $getMaterialType = $newMaterial1->getMaterialType();

        $this->assertSame("pdf", $getMaterialType);
    }

    public function testGetSectionMaterialNum()
    {
        $section =  new Section(1, "Software Product Management");

        $newMaterial1 = new SectionMaterial(1, "pdf", "./materials/week1/Week1b-SPM-fundamentals-v1.0.pdf");
        $newMaterial2 = new SectionMaterial(2, "docs", "./materials/week1/AWS-Docs.docx");
    
        $section->addSectionMaterial($newMaterial1);
        $section->addSectionMaterial($newMaterial2);

        $getMaterialNum1 = $newMaterial1->getMaterialNum();
        $getMaterialNum2 = $newMaterial2->getMaterialNum();
        $this->assertSame($getMaterialNum1, 1);
        $this->assertSame($getMaterialNum2, 2);
    }

    public function testSectionMaterialProgress()
    {
        $preReq = new CoursePrereq("true");
        $class = new Class1(1, 20, "Wei Cheng", "2021-10-5", "2021-10-20", "10:00:00", "16:00:00", "2021-09-15", "2021-09-30");
        $course = new Course(1, "Business Data Management", "This course aims to fundamental of data management", $class, $preReq);
        $user = new User("Yu Hao", "Neo Yu Hao", "yuhaon@aio.com", "password", "Operations", "Roving Service Engineer - Junior", "Learner");

        $enrol = new Enrollment(1, "Approved", true, "2021-11-22 00:00:01", $course, false, $user);
 
        $progress1 = new MaterialProgress($class, $user, 1,true);
        $getComplete1 = $progress1->getComplete();

        $this->assertTrue($getComplete1, true);
    }


    public function testGetSectionName()
    {
        
        $class = new Class1(1, 20, "Wei Cheng", "2021-10-5", "2021-10-20", "10:00:00", "16:00:00", "2021-09-15", "2021-09-30");
        $class->addSection(1, 'Intro to Data Management');
        
        $name = 'Intro to Data Management';
        $section = $class->getSection();
        $sec1 = $section[0];
    
        $secName = $sec1->getSectionName();
        $this->assertSame($name, $secName);
    }


}
?>

<!-- php vendor/bin/phpunit tests/TestClassSection.php -->