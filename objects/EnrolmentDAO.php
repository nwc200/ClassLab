<?php
    class EnrolmentDAO{

        public function retrieveEnrolment($username){
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("enrolment");
            
            $sql = "select * from enrolment where username=:username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            
            $quiz = null;
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if ($row = $stmt->fetch()){
                $enrolment = new Enrollment($row["enrolmentID"], $row["enrolmentStatus"], $row["selfEnrol"], $row["dateTimeEnrolled"], $row["courseID"], $row["classID"], $row["completed"], $row["userName"]);
            }
            
            $stmt = null;
            $pdo = null;
            return $enrolment;
        }

        public function getLearnerCourse($username,$status){
          
            $conn_manager = new ConnectionManager(); 
            $pdo = $conn_manager->getConnection("enrolment"); 
            
            //Get classids for the user who is enrolled in the class, enrolmentstatus = approved 
    
            $sql = "select courseID from enrolment where userName=:userName and enrolmentStatus=:enrolmentStatus"; 
            $stmt = $pdo->prepare($sql); 
            $stmt->bindParam(":userName", $username);
            $stmt->bindParam(":enrolmentStatus", $status);
            $stmt->execute(); 
            // $stmt->setFetchMode(PDO::FETCH_ASSOC);
             
            $classidarr= []; 
            while ($row = $stmt->fetch()){ 
                $classidarr[] = $row['courseID']; 
            }
     
            // //get class information based on classid 
            $conn_manager = new ConnectionManager(); 
            $pdo = $conn_manager->getConnection("course"); 
             
            $results = []; 
     
            foreach($classidarr as $courseID){ 
                $sql = "select * from course where courseID=:courseID"; 
                $stmt = $pdo->prepare($sql); 
                $stmt->bindParam(":courseID", $courseID); 
                $stmt->execute(); 
                $course = null; 
                $stmt->setFetchMode(PDO::FETCH_ASSOC); 
                if ($row = $stmt->fetch()){ 
                    $class= new Course($row["courseID"],  $row["courseName"], $row["courseDescription"]);
                } 
                $results[] = $class; 
            }  
            $stmt = null; 
            $pdo = null; 
            //return list of class1 classes storing the class information
            return $results; 
        }

    }

?>