<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class EnrolmentDAO
{

    // Retrieve all approved enrolments
    public function retrieveUserApprovedEnrolment($userName, $enrolmentStatus)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");

        $sql = "select * from enrolment where userName=:userName and enrolmentStatus=:enrolmentStatus";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userName", $userName);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $stmt->execute();


        $enrolment = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            // var_dump($row);
            $enrol = new Enrollment($row["enrolmentID"], $row["enrolmentStatus"], $row["selfEnrol"], $row["dateTimeEnrolled"], $row["courseID"], $row["completed"], $row["userName"]);
            $enrolment[] = $enrol;
        }

        $stmt = null;
        $pdo = null;
        return $enrolment;
    }

    public function getLearnerClassID($courseID, $userName)
    {

        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");


        $sql = "select classID from enrolment where courseID=:courseID and userName=:userName";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseID", $courseID);
        $stmt->bindParam(":userName", $userName);
        $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $classID = '';
        while ($row = $stmt->fetch()) {
            $classID = $row['classID'];
        }
        $stmt = null; 
        $pdo = null; 
        //return list of class1 classes storing the class information
        return $classID; 
    }

    //retrieve all courses if courseid = true
    public function retrieveCourses($courseID){
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("course");
        
        $sql = "select * from course where courseID=:courseId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($row = $stmt->fetch()){
            $course = new Course($row["courseID"], $row["courseName"], $row["courseDescription"]);
        }
        
        $stmt = null;
        $pdo = null;
        return $course;
    }

    public function retrieveClassSection($classID)
    {

        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");


        $sql = "select sectionNum from section where classID=:classID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classID", $classID);
        $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $sectionNum = [];
        while ($row = $stmt->fetch()) {
            $sectionNum[] = $row['sectionNum'];
        }
        $stmt = null; 
        $pdo = null; 
        //return list of class1 classes storing the class information
        return $sectionNum; 
    }

    public function retrieveClassSectionName($classID, $sectionNum)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");


        $sql = "select sectionName from section where classID=:classID and sectionNum=:sectionNum";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classID", $classID);
        $stmt->bindParam(":sectionNum", $sectionNum);
        $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $sections = '';
        while ($row = $stmt->fetch()) {
            $sections = $row['sectionName'];
        }
        $stmt = null; 
        $pdo = null; 
        //return list of class1 classes storing the class information
        // var_dump($sections);
        return $sections; 

    }

}
