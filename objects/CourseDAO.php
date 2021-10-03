<?php
    class CourseDAO{

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

        // Retrieve a user with a given username
        // Return null if no such user exists
        public function retrieve($courseID){
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection("course");
            
            $sql = "select * from course where courseID=:courseId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":courseId", $courseID);
            $stmt->execute();
            
            $user = null;
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if ($row = $stmt->fetch()){
                $course = new Course($row["courseID"], $row["courseName"], $row["courseDescription"]);
            }
            
            $stmt = null;
            $pdo = null;
            return $course;
        }
    }
?>