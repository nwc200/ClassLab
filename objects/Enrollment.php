<?php
class Enrollment
{
    private $EnrollmentID;
    private $EnrolmentStatus;
    private $SelfEnrol;
    private $DateTimeEnrolled;
    private $Course; //Course Class (Aggregate)
    private $Completed;
    private $User; //User Class (Aggregate)

<<<<<<< HEAD
        public function __construct($EnrollmentID, $EnrolmentStatus, $SelfEnrol, $DateTimeEnrolled, $Course, $Completed, $User){
            $this->EnrollmentID = $EnrollmentID;
            $this->EnrolmentStatus = $EnrolmentStatus;
            $this->SelfEnrol = $SelfEnrol;
            $this->DateTimeEnrolled = $DateTimeEnrolled;
            $this->Course = $Course;
            $this->Completed = $Completed;
            $this->User = $User;
        }

        public function getEnrollmentID(){
            return $this->EnrollmentID;
        }

        public function getEnrolmentStatus(){
            return $this->EnrolmentStatus;
        }

        public function getSelfEnrol(){
            return $this->SelfEnrol;
        }

        public function getDateTimeEnrolled(){ 
            return $this->DateTimeEnrolled;
        }
        public function getCourseID(){ 
            return $this->Course;
        }
        public function getCompleted(){ 
            return $this->Completed;
        }
        public function getUserName(){ 
            return $this->User;
        }

=======
    public function __construct($EnrollmentID, $EnrolmentStatus, $SelfEnrol, $DateTimeEnrolled, $Course, $Completed, $User)
    {
        $this->EnrollmentID = $EnrollmentID;
        $this->EnrolmentStatus = $EnrolmentStatus;
        $this->SelfEnrol = $SelfEnrol;
        $this->DateTimeEnrolled = $DateTimeEnrolled;
        $this->Course = $Course;
        $this->Completed = $Completed;
        $this->User = $User;
>>>>>>> 06906bd2572cd8739bc4af0d0519d5d71a12e7d5
    }
}
?>