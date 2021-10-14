<?php
class EnrollmentDAO
{

    // Retrieve number of enrolment for a class
    public function retrieveEnrolment($courseID, $classID)
    {
        $status = "Approved";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where courseID=:courseId and classID=:classId and enrolmentStatus=:status";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        
        $enrolment = 0;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $enrolment += 1;
        }
        
        $stmt = null;
        $pdo = null;
        return $enrolment;
    }

    public function retrievePendingEnrolment($courseID, $classID)
    {
        $status = "Pending";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where courseID=:courseId and classID=:classId and enrolmentStatus=:status";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        
        $enrolment = 0;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $enrolment += 1;
        }
        
        $stmt = null;
        $pdo = null;
        return $enrolment;
    }

    
    // Retrieve qualified learners
    public function retrieveQualifiedLearners($courseID)
    {
        // Retrieve all learners who are currently taking the course and learners who have passed the course
        $completed = "1";
        $yetCompleted = "0";
        $nonQualifiedLearners = [];
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select userName from enrolment where courseID=:courseId and completed=:completed";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":completed", $completed);
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $nonQualifiedLearners[] = $row["userName"];
            }
        }
        
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select userName from enrolment where courseID=:courseId and completed=:completed";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":completed", $yetCompleted);
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $nonQualifiedLearners[] = $row["userName"];
            }
        }

        // Retrieve all learners, including trainers who want to learn
        $role = "Learner";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("user");
        
        $sql = "select * from user where roles=:role";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":role", $role);
        $stmt->execute();
            
        $qualifiedLearners = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            if (!in_array($row["userName"], $nonQualifiedLearners)) {
                $qualifiedLearners[] = $row["userName"];
            }
        }

        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("user");
        
        $sql = "select * from permissions where userType=:userType and courseID=:courseId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userType", $role);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->execute();
            
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            if (!in_array($row["userName"], $nonQualifiedLearners)) {
                $qualifiedLearners[] = $row["userName"];
            }
        }

        // Retrieve the prerequisite of the course
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("course");

        $sql = "select coursePrereq from coursePrereq where courseID=:courseId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->execute();

        $prerequisites = [];
        while ($row = $stmt->fetch()) {
            $prerequisites[] = $row["coursePrereq"];
        }

        // Check for qualified learners
        foreach ($prerequisites as $course) {
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("course");

            $sql = "select coursePrereq from coursePrereq where courseID=:courseId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":courseId", $course);
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {
                    if (!in_array($row["coursePrereq"], $prerequisites)) {
                        $prerequisites[] = $row["coursePrereq"];
                    }
                }
            }

            $checkingLearners = [];
            foreach ($qualifiedLearners as $name) {
                $conn_manager = new ConnectionManager();
                $pdo = $conn_manager->getConnection("enrolment");

                $sql = "select * from enrolment where courseID=:courseId and completed=:completed and userName=:username";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":courseId", $course);
                $stmt->bindParam(":completed", $completed);
                $stmt->bindParam(":username", $name);
                if ($stmt->execute()) {
                    if ($row = $stmt->fetch()) {
                        $checkingLearners[] = $name;
                    }
                }
            }
            $qualifiedLearners = $checkingLearners;
        }

        $stmt = null;
        $pdo = null;
        return $qualifiedLearners;
    }

    // Retrieve number of courses a learner is currently assigned
    public function retrieveNumberOfCourses($learnerID)
    {
        $completed = "0";
        $enrolmentStatus = "Approved";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where userName=:username and enrolmentStatus=:enrolmentStatus and completed=:yetcompleted";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $learnerID);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $stmt->bindParam(":yetcompleted", $completed);
        if ($stmt->execute()) {
            $numOfCourses = 0;
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $conn_manager = new ConnectionManager();
                $pdo = $conn_manager->getConnection("class");

                $sql = "select * from class where courseID=:courseId and classID=:classId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":courseId", $row["courseID"]);
                $stmt->bindParam(":classId", $row["classID"]);
                $stmt->execute();

                if ($row = $stmt->fetch()) {
                    $today = date("Y-m-d H:i:s");
                    $schedule = $row["endDate"] . $row["endTime"];
                    if ($schedule > $today) {
                        $numOfCourses += 1;
                    }
                }
            }
            
            $stmt = null;
            $pdo = null;
            return $numOfCourses;
        }

        $stmt = null;
        $pdo = null;
        return 0;
    }
    
    //check class capacity before enrollment
    public function checkClassCapacity($classID)
    {
        $completed = "0";
        $enrolmentStatus = "Approved";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where classid=:classid and enrolmentstatus=:enrolmentStatus"; 
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classID);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $status = $stmt->execute();
        $classEnrolment = 0;
        while ($row = $stmt->fetch() ) {
            $classEnrolment +=1;
                 $stmt = null;
                 $pdo = null;
                 return $classEnrolment;
        }

    }


    // Insert new enrolment
    public function addEnrolment($name, $courseID, $classID)
    {
        $today = date("Y-m-d H:i:s");
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "insert into enrolment (enrolmentStatus, selfEnrol, dateTimeEnrolled, courseID, classID, completed, userName) values ('Approved', false, :today, :courseId, :classId, false, :name);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":today", $today);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
        $stmt->bindParam(":name", $name);
        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }
        
        $stmt = null;
        $pdo = null;
        return $enrolment;
    }

    // Insert pending enrolment
    public function addPendingEnrolment($name, $courseID, $classID)
    {
        $today = date("Y-m-d H:i:s");
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "insert into enrolment (enrolmentStatus, selfEnrol, dateTimeEnrolled, courseID, classID, completed, userName) values ('Pending', false, :today, :courseId, :classId, false, :name);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":today", $today);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
        $stmt->bindParam(":name", $name);
        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }
        
        $stmt = null;
        $pdo = null;
        return $enrolment;
    }


    public function withdrawSelfEnrol($name, $courseID, $classID)
    {
        $enrolmentstatus = "Pending";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
          
        $sql = "delete from enrolment where username=:username and classid=:classid and enrolmentstatus =:enrolmentstatus";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":classid", $classid);
        $stmt->bindParam(":enrolmentstatus ", $enrolmentstatus );
 
        $status = $stmt->execute();
        $stmt = null;
        $pdo = null;
        return $status;
    }

    
?>