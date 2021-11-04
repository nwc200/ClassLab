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

    // Retrieve Pending Enrolment
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
        $enrolmentStatus = "Approved";
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
        
        $sql = "select userName from enrolment where courseID=:courseId and completed=:completed and enrolmentStatus=:enrolmentStatus";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":completed", $yetCompleted);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $nonQualifiedLearners[] = $row["userName"];
            }
        }

        $enrolmentStatus = "Pending";
        $selfEnrol = "1";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select userName from enrolment where courseID=:courseId and enrolmentStatus=:enrolmentStatus and selfEnrol=:selfEnrol";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $stmt->bindParam(":selfEnrol", $selfEnrol);
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
        $enrolments = [];
        $numOfCourses = 0;
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where userName=:username and enrolmentStatus=:enrolmentStatus and completed=:yetcompleted";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $learnerID);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $stmt->bindParam(":yetcompleted", $completed);
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $enrolments[] = [$row["courseID"], $row["classID"]];
            }

            foreach ($enrolments as $enrolment) {
                $conn_manager = new ConnectionManager();
                $pdo = $conn_manager->getConnection("class");
    
                $sql = "select * from class where courseID=:courseId and classID=:classId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":courseId", $enrolment[0]);
                $stmt->bindParam(":classId", $enrolment[1]);
                $stmt->execute();
    
                if ($row = $stmt->fetch()) {
                    $today = date("Y-m-d H:i:s");
                    $schedule = $row["endDate"] . $row["endTime"];
                    if ($schedule > $today) {
                        $numOfCourses += 1;
                    }
                }
    
                $stmt = null;
                $pdo = null;
            }
        }
            
        $stmt = null;
        $pdo = null;
        return $numOfCourses;
    }
    
    // Check class capacity before enrollment
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
        date_default_timezone_set( 'Asia/Singapore');
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
        return false;
    }

    // Insert pending enrolment
    public function addPendingEnrolment($name, $courseID, $classID)
    {
        date_default_timezone_set( 'Asia/Singapore');
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

    // Withdraw self-enrollment
    public function withdrawSelfEnrol($username, $classid)
    {
        $enrolmentstatus = "Pending";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
          
        $sql = "delete from enrolment where username =:username and classid =:classid and enrolmentstatus =:enrolmentstatus";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":enrolmentstatus", $enrolmentstatus);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":classid", $classid);
        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }
        
    }

    // Retrieve pending enrolments
    public function retrievePendingEnrolments()
    {
        $status = "Pending";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where enrolmentStatus=:status";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":status", $status);

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
                $sql = "select * from user where userName=:name";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":name", $array[2]);
                $stmt->execute();

                $user = null;
                if ($row = $stmt->fetch()) {
                    $user = new User($row["userName"], $row["name"], $row["emailAddr"], $row["department"], $row["designation"], $row["roles"]);
                }
                $classes[] = [$course, $user];
            }

            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("enrolment");
            
            $sql = "select * from enrolment where enrolmentStatus=:status";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":status", $status);

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
        return $enrolments;
    }

    // Update enrollment status
    public function updateEnrolmentStatus($enrollmentId, $status)
    {
        if ($status == "Rejected") {
            $today = "";
        } else {
            date_default_timezone_set( 'Asia/Singapore');
            $today = date("Y-m-d H:i:s");
        }
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "update enrolment set enrolmentStatus=:status, dateTimeEnrolled=:today where enrolmentID=:enrollmentId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":today", $today);
        $stmt->bindParam(":enrollmentId", $enrollmentId);
        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }
        
        $stmt = null;
        $pdo = null;
        return false;
    }

    // Retrieve all engineers enrolled in the course class
    public function retrieveEnrolledEngineers($courseID, $classID)
    {
        $enrolmentStatus = "Approved";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "select * from enrolment where courseID=:courseId and classID=:classId and enrolmentStatus=:enrolmentStatus";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $stmt->execute();
        
        $names = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $names[] = $row["userName"];
        }
        
        $stmt = null;
        $pdo = null;
        return $names;
    }

    // Withdraw engineer from a course class
    public function withdrawEngineer($courseID, $classID, $username)
    {
        $enrolmentStatus = "Withdrawn";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        
        $sql = "update enrolment set enrolmentStatus=:enrolmentStatus where courseID=:courseId and classID=:classId and userName=:username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":enrolmentStatus", $enrolmentStatus);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
        $stmt->bindParam(":username", $username);
        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }
        
        $stmt = null;
        $pdo = null;
        return false;
    }
}
?>