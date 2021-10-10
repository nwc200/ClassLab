<?php
class CourseDAO
{

    // Retrieve a course
    public function retrieve($courseID)
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

    // Retrieve all courses
    public function retrieveAll()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("course");
        
        $sql = "select * from course";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $courses = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $course = new Course($row["courseID"], $row["courseName"], $row["courseDescription"]);
            $courses[] = $course;
        }
        
        $stmt = null;
        $pdo = null;
        return $courses;
    }

    // Retrieve course prerequisite
    public function setPrerequisite($courseID, $course)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("course");
        
        $sql = "select * from coursePrereq where courseID=:courseId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->execute();
        
        $prerequisiteIDs = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $prerequisiteIDs[] = $row["coursePrereq"];
            $course->addCoursePrereq($row["coursePrereq"]);
        }
        
        $stmt = null;
        $pdo = null;
        return $prerequisiteIDs;
    }

    // Retrieve all course classes
    public function retrieveCourseClasses($courseID)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("class");
        
        $sql = "select * from class where courseID=:courseId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->execute();
        
        $classes = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $classes[] = new Class1($row["classID"], $row["classSize"], $row["trainerUserName"], $row["startDate"], $row["endDate"], $row["startTime"], $row["endTime"], $row["selfEnrollmentStart"], $row["selfEnrollmentEnd"]);
        }
        
        $stmt = null;
        $pdo = null;
        return $classes;
    }

    // Retrieve a course class
    public function retrieveCourseClass($courseID, $classID)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("class");
        
        $sql = "select * from class where courseID=:courseId and classID=:classId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
        $stmt->execute();
        
        $courseClass = null;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($row = $stmt->fetch()) {
            $courseClass = new Class1($row["classID"], $row["classSize"], $row["trainerUserName"], $row["startDate"], $row["endDate"], $row["startTime"], $row["endTime"], $row["selfEnrollmentStart"], $row["selfEnrollmentEnd"]);
        }
        
        $stmt = null;
        $pdo = null;
        return $courseClass;
    }

    // Update enrolment period for a course class
    public function updateEnrolmentPeriod($courseID, $classID, $startDate, $endDate)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("class");
        
        $sql = "update class set selfEnrollmentStart=:startDate, selfEnrollmentEnd=:endDate where courseID=:courseId and classID=:classId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":startDate", $startDate);
        $stmt->bindParam(":endDate", $endDate);
        $stmt->bindParam(":courseId", $courseID);
        $stmt->bindParam(":classId", $classID);
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