<?php
class SectionDAO
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
    public function retrieveCourses($courseID)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("course");

        $sql = "select * from course where courseID=:courseId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($row = $stmt->fetch()) {
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

    public function retrieveClassSectionMaterials($classID, $sectionNum)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");


        $sql = "select * from sectionMaterial where classID=:classID and sectionNum=:sectionNum";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classID", $classID);
        $stmt->bindParam(":sectionNum", $sectionNum);
        $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $materials = [];
        while ($row = $stmt->fetch()) {
            $materials[] = new SectionMaterial($row["materialNum"], $row["materialType"], $row["link"]);
        }

        $stmt = null;
        $pdo = null;
        //return list of class1 classes storing the class information
        // var_dump($materials);
        return $materials;
    }

    public function retrieveSectionMaterialsProgress($userName, $classID, $sectionNum, $materialNum)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");


        $sql = "SELECT completed FROM `materialprogress` 
            WHERE classID=:classID and sectionNum=:sectionNum and materialNum=:materialNum and userName=:userName
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userName", $userName);
        $stmt->bindParam(":classID", $classID);
        $stmt->bindParam(":sectionNum", $sectionNum);
        $stmt->bindParam(":materialNum", $materialNum);
        $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $completed = '';
        while ($row = $stmt->fetch()) {
            // var_dump($row);
            $completed =  $row["completed"];
        }

        $stmt = null;
        $pdo = null;
        //return list of class1 classes storing the class information
        // var_dump($completed);
        return $completed;
    }

    public function updateMaterialProgress($classID, $sectionNum, $materialNum)
    {
        // get course of class
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");

        $sql = "UPDATE `materialprogress` 
                SET completed = 1
                WHERE classID=:classID AND sectionNum=:sectionNum AND materialNum=:materialNum";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classID", $classID);
        $stmt->bindParam(":sectionNum", $sectionNum);
        $stmt->bindParam(":materialNum", $materialNum);
        $status = $stmt->execute();


        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function retrieveClassQuiz($classID, $sectionNum)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");

        $sql = "select * from quiz where classID=:classID and sectionNum=:sectionNum";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classID", $classID);
        $stmt->bindParam(":sectionNum", $sectionNum);
        $stmt->execute();

        $quiz = [];
        while ($row = $stmt->fetch()) {
            $quiz = new Quiz($row["quizID"], $row["classID"], $row["quizName"], $row["quizNum"], $row["quizDuration"], $row["type"], $row["passingMark"]);
        }
        // var_dump($quiz);
        $stmt = null;
        $pdo = null;
        return $quiz;
    }
}
