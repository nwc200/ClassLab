<?php
class QuizDAO
{

    // // Add a new user to the database
    // public function add($username, $hashedPassword){
    //     $conn_manager = new ConnectionManager();
    //     $pdo = $conn_manager->getConnection();
        
    //     $sql = "insert into user (username, password)
    //             values (:username, :hashed_password)";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->bindParam(":username", $username);
    //     $stmt->bindParam(":hashed_password", $hashedPassword);
    //     $status = $stmt->execute();

    //     $stmt = null;
    //     $pdo = null;
    //     return $status;
    // }

    // Retrieve a quiz with a given classid
    // Return null if no such user exists
    public function retrieveClassQuiz($classid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        
        $sql = "select * from quiz where classid=:classid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classid);
        $stmt->execute();
        
        $quiz = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $quiz[] = [$row["quizID"],$row["sectionNum"], $row["quizName"], $row["quizNum"], $row["quizDuration"], $row["type"], $row["passingMark"]];
        }
        
        $stmt = null;
        $pdo = null;
        return $quiz;
    }

    // // Add a new Quiz to the database
    public function addQuiz($classid, $sectionnum, $quizname, $quiznum, $quizduration, $type, $passingmark)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        
        $sql = "insert into quiz (classid, sectionnum, quizname, quiznum, quizduration, type, passingmark) 
        values (:classid, :sectionnum, :quizname, :quiznum, :quizduration, :type, :passingmark)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classid);
        $stmt->bindParam(":sectionnum", $sectionnum);
        $stmt->bindParam(":quizname", $quizname);
        $stmt->bindParam(":quiznum", $quiznum);
        $stmt->bindParam(":quizduration", $quizduration);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":passingmark", $passingmark);
        $status = $stmt->execute();

        $stmt = null;
        $pdo = null;
        return $status;
    }

    // // Add a new Quiz to the database
    public function addQuizQuestion($quizid, $questionnum, $question, $questiontype, $marks)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        
        $sql = "insert into quizquestion (quizid, questionnum, question, questiontype, marks) 
        values (:quizid, :questionnum, :question, :questiontype, :marks)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":quizid", $quizid);
        $stmt->bindParam(":questionnum", $questionnum);
        $stmt->bindParam(":question", $question);
        $stmt->bindParam(":questiontype", $questiontype);
        $stmt->bindParam(":marks", $marks);
        $status = $stmt->execute();

        $stmt = null;
        $pdo = null;
        return $status;
    }

    // // Add a new Quiz to the database
    public function addQuizAnswer($answernum, $quizid, $questionnum, $answer, $correct)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        
        $sql = "insert into quizanswer (answernum, quizid, questionnum, answer, correct) 
        values (:answernum, :quizid, :questionnum, :answer, :correct)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":answernum", $answernum);
        $stmt->bindParam(":quizid", $quizid);
        $stmt->bindParam(":questionnum", $questionnum);
        $stmt->bindParam(":answer", $answer);
        $stmt->bindParam(":correct", $correct);
        $status = $stmt->execute();

        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function getQuizID($classid, $sectionnum, $quiznum, $type)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        
        $sql = "select quizid from quiz where classid=:classid and sectionnum=:sectionnum and quiznum=:quiznum and type=:type";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classid);
        $stmt->bindParam(":sectionnum", $sectionnum);
        $stmt->bindParam(":quiznum", $quiznum);
        $stmt->bindParam(":type", $type);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $quizid = [];
        while ($row = $stmt->fetch()) {
            $quizid[] = $row['quizid'];
        }
        
        $stmt = null;
        $pdo = null;
        return $quizid;
    }


// // Get Course & Class Trainer Teach
    public function getTrainerCourse($trainerusername)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("class");
        //Get CourseIDs
        $sql = "select courseid from class where trainerusername=:trainerusername";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":trainerusername", $trainerusername);
        $stmt->execute();
        
        $courseidarr = [];
        while ($row = $stmt->fetch()) {
            $courseidarr[] = $row['courseid'];
        }
        //Create Course Classes based on ID
        //create course
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("course");
        
        $results = [];

        foreach ($courseidarr as $courseid) {
            $sql = "select * from course where courseid=:courseid";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":courseid", $courseid);
            $stmt->execute();
            $course = null;
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if ($row = $stmt->fetch()) {
                $course = new Course($courseid, $row["courseName"], $row["courseDescription"]);
            }
            $results[] =$course;
        }
        $stmt = null;
        $pdo = null;
        return $results;
    }
    

    public function getClassSectionQuiz($trainerusername, $courseid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("class");

        $sql = "select * from class where trainerusername=:trainerusername and courseid=:courseid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseid", $courseid);
        $stmt->bindParam(":trainerusername", $trainerusername);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($row = $stmt->fetch()) {
            $class = new Class1($row["classID"], $row["classSize"], $row["trainerUserName"], $row["startDate"], $row["endDate"], $row["startTime"], $row["endTime"], $row["selfEnrollmentStart"], $row["selfEnrollmentEnd"]);
        }

        //get section
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");
        $classid = $class->getClassID();
        $sql = "select * from section where classid=:classid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $class->addSection($row["sectionNum"], $row["sectionName"]);
        }

        //get quiz
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        foreach ($class->getSection() as $section) {
            $sectionnum = $section->getSectionNum();
            $sql = "select * from quiz where sectionnum=:sectionnum and classid=:classid";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":sectionnum", $sectionnum);
            $stmt->bindParam(":classid", $classid);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $quiz = new Quiz($row["quizID"], $row["quizName"], $row["quizNum"], $row["quizDuration"], $row["type"], $row["passingMark"]);
                $section->addQuiz($quiz);
            }
        }
        $stmt = null;
        $pdo = null;
        return $class;
    }

    public function getClassStudentNum($classid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        $sql = "select * from enrolment where classid=:classid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $count =0;
        while ($row = $stmt->fetch()) {
            if ($row["enrolmentStatus"] =="Approved") {
                $count+=1;
            }
        }
        $stmt = null;
        $pdo = null;
        return $count;
    }

//add quiz to all classes of same course
    public function addGradedQuiz($classid, $sectionnum, $quizname, $quiznum, $quizduration, $type, $passingmark)
    {
        // get course of class
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("class");
        $sql = "select courseid from class where classid=:classid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $courseid=null;
        if ($row = $stmt->fetch()) {
            $courseid =$row["courseid"];
        }

        // get classes of course
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("class");
        $sql = "select classid from class where courseid=:courseid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":courseid", $courseid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $classidarr =[];
        while ($row = $stmt->fetch()) {
            $classidarr[]=$row['classid'];
        }

        // add quiz to all classes
        foreach ($classidarr as $finalclassid) {
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("quiz");
            $sectionnum = $this->getLastSectionNum($finalclassid);
            $sql = "insert into quiz (classid, sectionnum, quizname, quiznum, quizduration, type, passingmark) 
            values (:classid, :sectionnum, :quizname, :quiznum, :quizduration, :type, :passingmark)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":classid", $finalclassid);
            $stmt->bindParam(":sectionnum", $sectionnum);
            $stmt->bindParam(":quizname", $quizname);
            $stmt->bindParam(":quiznum", $quiznum);
            $stmt->bindParam(":quizduration", $quizduration);
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":passingmark", $passingmark);
            $status = $stmt->execute();
        }
        
        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function getQuiz($quizid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        $sql = "select * from quiz where quizid=:quizid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":quizid", $quizid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $quiz=null;
        if ($row = $stmt->fetch()) {
            $quiz = new Quiz($row['quizID'], $row["quizName"], $row["quizNum"], $row["quizDuration"], $row["type"], $row["passingMark"]);
        }

        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        $sql = "select * from quizquestion where quizid=:quizid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":quizid", $quizid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $quiz->addQuizQuestion($row["questionNum"], $row["question"], $row["questionType"], $row["marks"]);
        }

        foreach ($quiz->getQuizQuestion() as $question) {
            $questionnum = $question->getQuestionNum();
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("quiz");
            $sql = "select * from quizanswer where quizid=:quizid and questionnum=:questionnum";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":quizid", $quizid);
            $stmt->bindParam(":questionnum", $questionnum);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $question->addQuizAnswer($row["answerNum"], $row["answer"], $row["correct"]);
            }
        }
        $stmt = null;
        $pdo = null;
        return $quiz;
    }

    public function getAttemptNo($quizid, $username)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        $sql = "select * from studentquizattempt where quizid=:quizid and username=:username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":quizid", $quizid);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $attemptno = 0;
        while ($row = $stmt->fetch()) {
            $attemptno += 1;
        }
        $stmt = null;
        $pdo = null;
        return $attemptno;
    }

    public function addStudentQuizAttempt($username, $quizid, $attemptno, $passfail)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        // echo('hi');
        $sql = "insert into studentquizattempt (username, quizid, attemptno, passfail) 
        values (:username, :quizid, :attemptno, :passfail)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":quizid", $quizid);
        $stmt->bindParam(":attemptno", $attemptno);
        $stmt->bindParam(":passfail", $passfail);
        $status = $stmt->execute();

        var_dump($passfail);
        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function addStudentQuizRecord($username, $quizid, $marks, $attemptno, $questionnum, $studentansnum)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("quiz");
        
        $sql = "insert into studentquizrecord (username, quizid, marks, attemptno, questionnum, studentansnum) 
        values (:username, :quizid, :marks, :attemptno, :questionnum, :studentansnum)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":quizid", $quizid);
        $stmt->bindParam(":marks", $marks);
        $stmt->bindParam(":attemptno", $attemptno);
        $stmt->bindParam(":questionnum", $questionnum);
        $stmt->bindParam(":studentansnum", $studentansnum);
        $status = $stmt->execute();

        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function getLastSectionNum($classid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("section");
        $sql = "select sectionnum from section where classid=:classid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":classid", $classid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $sectionnum = 0;
        while ($row = $stmt->fetch()) {
            $sectionnum = max($sectionnum, (int)$row["sectionnum"]);
        }

        $stmt = null;
        $pdo = null;
        return $sectionnum;
    }

    public function quizUpdateEnrolment($username, $classid, $completed)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection("enrolment");
        $sql = "update enrolment set completed=:completed 
        where username=:username and classid=:classid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":completed", $completed);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":classid", $classid);
        $status = $stmt->execute();

        $stmt = null;
        $pdo = null;
        return $status;
    }
}

?>