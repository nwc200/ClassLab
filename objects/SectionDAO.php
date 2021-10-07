<?php
    class SectionDAO
    {
        //courseID of user
        public function getLearnerCourseID($userName, $status)
        {

            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("enrolment");


            $sql = "select courseID from enrolment where userName=:userName and enrolmentStatus=:enrolmentStatus";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":userName", $userName);
            $stmt->bindParam(":enrolmentStatus", $status);
            $stmt->execute();
            // $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $courseIDAarr = [];
            while ($row = $stmt->fetch()) {
                $courseIDAarr[] = $row['courseID'];
            }
            $stmt = null; 
            $pdo = null; 
            //return list of class1 classes storing the class information
            return $courseIDAarr; 
        }
        //GET LEARNER COURSENAME
        public function getLearnerCourseName($courseID)
        {

            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("course");


            $sql = "select courseName from course where courseID=:courseID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":courseID", $courseID);
            $stmt->execute();
            // $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $courseName = [];
            while ($row = $stmt->fetch()) {
                $courseName[] = $row['courseName'];
            }
            $stmt = null; 
            $pdo = null; 
            //return list of class1 classes storing the class information
            return $courseName; 
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

            $classID = [];
            while ($row = $stmt->fetch()) {
                $classID[] = $row['classID'];
            }
            $stmt = null; 
            $pdo = null; 
            //return list of class1 classes storing the class information
            return $classID; 
        }

        public function getLearnerSection($classID)
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

        public function getLearnerSectionMaterials($classID, $sectionNum)
        {
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("section");

            $sql = "select materialNum from sectionMaterial where classID=:classID and sectionNum=:sectionNum";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":classID", $classID);
            $stmt->bindParam(":sectionNum", $sectionNum);
            $stmt->execute();
            // $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $materialNum = [];
            if ($row = $stmt->fetch()) {
         
                $materialNum[] = $row['materialNum'];
            }
            $stmt = null; 
            $pdo = null; 
            //return list of class1 classes storing the class information
            return $materialNum; 
        }

        //retrieve user enrolments
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

    }

?>