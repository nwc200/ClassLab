<?php
class SectionDAO
{

    // Retrieve all approved enrolments
    public function retrieveUserApprovedEnrolment($userName, $enrolmentStatus)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where enrolmentStatus=:enrolmentStatus and userName=:userName";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $stmt->bindParam(":userName", $userName);

        $objectClasses = [];
        $enrolments = [];
        
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $objectClasses[] = [$row["courseID"], $row["classID"], $row["userName"]];
            }
            
            // Retrieve enrolled course
            $classes = [];
            foreach ($objectClasses as $array) {
                $conn_manager = new ConnectionManager();
                $pdo = $conn_manager->getConnection("course");
                $sql = "select * from course where courseID=:courseId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":courseId", $array[0]);
                $stmt->execute();

                $course = null;
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                if ($row = $stmt->fetch()) {
                    $course = new Course($row["courseID"], $row["courseName"], $row["courseDescription"]);

                    // Retrieve enrolled class
                    $conn_manager = new ConnectionManager();
                    $pdo = $conn_manager->getConnection("class");
                    $sql = "select * from class where classID=:classId";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":classId", $array[1]);
                    $stmt->execute();

                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $stmt->fetch()) {
                        $course->addClass1($row["classID"], $row["classSize"], $row["trainerUserName"], $row["startDate"], $row["endDate"], $row["startTime"], $row["endTime"], $row["selfEnrollmentStart"], $row["selfEnrollmentEnd"]);
                    }
                }
                
                // Retrieve user
                $conn_manager = new ConnectionManager();
                $pdo = $conn_manager->getConnection("user");
                $sql = "select * from user where userName=:userName";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":userName", $userName);
                $stmt->execute();

                $user = null;
                if ($row = $stmt->fetch()) {
                    // var_dump($row);
                    $user = new User($row["userName"], $row["name"], $row["emailAddr"], $row["department"], $row["designation"], $row["roles"]);
                }
                $classes[] = [$course, $user];
            }

            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("enrolment");
            
            $sql = "select * from enrolment where enrolmentStatus=:enrolmentStatus and userName=:userName";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
            $stmt->bindParam(":userName", $userName);

            $count = 0;
            if ($stmt->execute()) {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) {
                    $enrolments[] = new Enrollment($row["enrolmentID"], $row["enrolmentStatus"], $row["selfEnrol"], $row["dateTimeEnrolled"], $classes[$count][0], $row["completed"], $classes[$count][1]);
                    $count += 1;
                }
            }
        }

        $stmt = null;
        $pdo = null;
        // var_dump($enrolments);
        return $enrolments;
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

        $sql = "select * from course where courseID=:courseID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseID", $courseID);
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
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $complete = '';
        while ($row = $stmt->fetch()) {
            $complete = $row['completed'];
        }
        
        $stmt = null;
        $pdo = null;
        // var_dump($complete);
        return $complete;
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

    public function insertProgress($classID, $sectionNum, $materialNum, $userName, $completed)
    {
        // get course of class
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");

        $sql = "INSERT INTO `materialprogress` 
                values (:classID, :sectionNum, :materialNum, :userName,:completed)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classID", $classID);
        $stmt->bindParam(":sectionNum", $sectionNum);
        $stmt->bindParam(":materialNum", $materialNum);
        $stmt->bindParam(":userName", $userName);
        $stmt->bindParam(":completed", $completed);
        $status = $stmt->execute();


        $stmt = null;
        $pdo = null;
        return $status;
    }


    public function studentQuizAttemptRetrieve($userName, $quizID)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");

        $sql = "select * from studentQuizAttempt where userName=:userName and quizID=:quizID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userName", $userName);
        $stmt->bindParam(":quizID", $quizID);
        $stmt->execute();


        $attempts = [];
        while ($row = $stmt->fetch()) {
            // var_dump($row);
            $attempts[] = [$row['quizID'], $row['attemptNo'], $row['passFail']];
        }

        // var_dump($attempts);
        $stmt = null;
        $pdo = null;
        return $attempts;
    }
}
