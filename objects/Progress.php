<?php
class MaterialProgress
{
    private $Class1; //Class1 Class (Aggregate)
    private $User; //User Class (Aggregate)
    private $Completed;

    public function __construct($Class1, $User, $SectionMaterial, $Completed)
    {
        $this->Class1 = $Class1;
        $this->User = $User;
        $this->SectionMaterial = $SectionMaterial;
        $this->Completed = $Completed;
    }
    public function getComplete()
    {
        return $this->Completed;
    }
}

class StudentQuizAttempt
{
    private $User; //User Class (Aggregate)
    private $Quiz; //Quiz Class (Aggregate)
    private $AttemptNo;
    private $Type;
    private $PassFail;
    private $StudentQuizRecord = array();

    public function __construct($User, $Quiz, $AttemptNo, $PassFail)
    {
        $this->User = $User;
        $this->Quiz = $Quiz;
        $this->AttemptNo = $AttemptNo;
        $this->PassFail = $PassFail;
    }

    public function jsonSerialize()
    {
        return [
            $this->User,
            $this->Quiz,
            $this->AttemptNo,
            $this->PassFail
        ];
    }

    public function addStudentQuizRecord($QuizRecord, $Marks, $QuestionNum, $StudentAns)
    {
        $this->StudentQuizRecord[] = new StudentQuizRecord($QuizRecord, $Marks, $QuestionNum, $StudentAns);
        $checkarr = [];
        foreach ($this->StudentQuizRecord as $record ) {
            if (in_array($record->getQuizRecord(), $checkarr)) {
                array_pop($this->StudentQuizRecord);
                throw new Exception("Duplicated Student Quiz Record Added.");
            } else {
                array_push($checkarr, $record->getQuizRecord());
            }
        }
    }

    public function getStudentQuizRecord()
    {
        return $this->StudentQuizRecord;
    }

    public function calculateTotalMarksScored()
    {
        if (count($this->StudentQuizRecord)==0) {
            throw new Exception("There are no student quiz records.");
        }
        $sumTotal=0;
        foreach ($this->StudentQuizRecord as $Record) {
            $sumTotal = $sumTotal + $Record->getMarks();
        }
        if ($sumTotal<0) {
            throw new Exception("Score cannot be negative.");
        }
        return $sumTotal;
    }
}

class StudentQuizRecord
{
    private $QuizRecord;
    private $Marks;
    private $QuestionNum;
    private $StudentAnsNum;

    public function __construct($QuizRecord, $Marks, $QuestionNum, $StudentAnsNum)
    {
        $this->QuizRecord = $QuizRecord;
        $this->Marks = $Marks;
        $this->QuestionNum = $QuestionNum;
        $this->StudentAnsNum = $StudentAnsNum;
    }

    public function getQuizRecord()
    {
        return $this->QuizRecord;
    }

    public function getMarks()
    {
        return $this->Marks;
    }
}

?>
