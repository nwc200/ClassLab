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

    public function __construct($EnrollmentID, $EnrolmentStatus, $SelfEnrol, $DateTimeEnrolled, $Course, $Completed, $User)
    {
        $this->EnrollmentID = $EnrollmentID;
        $this->EnrolmentStatus = $EnrolmentStatus;
        $this->SelfEnrol = $SelfEnrol;

        if ($EnrolmentStatus == "Pending" && $DateTimeEnrolled != "") {
            throw new Exception("Invalid date time! Expecting no enrollment date time if pending.");
        } else {
            $this->DateTimeEnrolled = $DateTimeEnrolled;
        }

        if (getType($Course) == "object") {
            $this->Course = $Course;
        } else {
            throw new Exception("Invalid data type! Expecting Course object.");
        }

        $this->Completed = $Completed;

        if (getType($User) == "object") {
            if ($User->getRoles() == "Administrator") {
                throw new Exception("Invalid user! Expecting learner or trainer.");
            } else {
                $this->User = $User;
            }
        } else {
            throw new Exception("Invalid data type! Expecting User object.");
        }
    }

    public function getEnrollmentID()
    {
        return $this->EnrollmentID;
    }

    public function getEnrolmentStatus()
    {
        return $this->EnrolmentStatus;
    }

    public function getSelfEnrol()
    {
        return $this->SelfEnrol;
    }

    public function getDateTimeEnrolled()
    {
        return $this->DateTimeEnrolled;
    }

    public function getCourse()
    {
        return $this->Course;
    }

    public function getCompleted()
    {
        return $this->Completed;
    }
    
    public function getUser()
    {
        return $this->User;
    }
}

?>