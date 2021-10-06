<?php
class Course implements JsonSerializable
{
    private $CourseID;
    private $CourseName;
    private $CourseDescription;
    //allow a course to have multiple classes and prereq
    private $Class1 = array();
    private $CoursePrereq = array();
 
    public function __construct($CourseID, $CourseName, $CourseDescription)
    {
        $this->CourseID = $CourseID;
        $this->CourseName = $CourseName;
        $this->CourseDescription = $CourseDescription;
    }

    public function addClass1($ClassID, $ClassSize, $TrainerUserName, $StartDate, $EndDate, $StartTime, $EndTime, $SelfEnrollmentStart, $SelfEnrollmentEnd)
    {
        $this->Class1[] = new Class1($ClassID, $ClassSize, $TrainerUserName, $StartDate, $EndDate, $StartTime, $EndTime, $SelfEnrollmentStart, $SelfEnrollmentEnd);
    }

    public function getClass1()
    {
        return $this->Class1;
    }

    public function addCoursePrereq($CoursePrereq)
    {
        $this->CoursePrereq[] = new CoursePrereq($CoursePrereq);
    }

    public function getCoursePrereq()
    {
        return $this->CoursePrereq;
    }

    public function getCourseID()
    {
        return $this->CourseID;
    }

    public function getCourseName()
    {
        return $this->CourseName;
    }

    public function getCourseDescription()
    {
        return $this->CourseDescription;
    }

    public function jsonSerialize()
    {
        return [
            $this->CourseID,
            $this->CourseName,
            $this->CourseDescription,
            $this->Class1,
            $this->CoursePrereq
        ];
    }

}

class CoursePrereq implements JsonSerializable
{
    private $CoursePrereq;

    public function __construct($CoursePrereq)
    {
        $this->CoursePrereq = $CoursePrereq;
    }

    public function jsonSerialize()
    {
        return [
            $this->CoursePrereq
        ];
    }
}

class Class1 implements JsonSerializable
{
    private $ClassID;
    private $ClassSize;
    private $TrainerUserName;
    private $StartDate;
    private $EndDate;
    private $StartTime;
    private $EndTime;
    private $SelfEnrollmentStart;
    private $SelfEnrollmentEnd;
    private $Section = array();

    public function __construct($ClassID, $ClassSize, $TrainerUserName, $StartDate, $EndDate, $StartTime, $EndTime, $SelfEnrollmentStart, $SelfEnrollmentEnd)
    {
        $this->ClassID = $ClassID;
        $this->ClassSize = $ClassSize;
        $this->TrainerUserName = $TrainerUserName;
        $this->StartDate = $StartDate;
        $this->EndDate = $EndDate;
        $this->StartTime = $StartTime;
        $this->EndTime = $EndTime;
        $this->SelfEnrollmentStart = $SelfEnrollmentStart;
        $this->SelfEnrollmentEnd = $SelfEnrollmentEnd;
    }

    public function addSection($SectionNum, $SectionName)
    {
        $this->Section[] = new Section($SectionNum, $SectionName);
    }

    public function getSection()
    {
        return $this->Section;
    }

    public function getClassID()
    {
        return $this->ClassID;
    }

    public function jsonSerialize()
    {
        return [
            $this->ClassID,
            $this->ClassSize,
            $this->TrainerUserName,
            $this->StartDate,
            $this->EndDate,
            $this->StartTime,
            $this->EndTime,
            $this->SelfEnrollmentStart,
            $this->SelfEnrollmentEnd,
            $this->Section
            ]
        ;
    }
}

class Section implements JsonSerializable
{
    private $SectionNum;
    private $SectionName;
    private $SectionMaterial = array();
    private $Quiz = array();

    public function __construct($SectionNum, $SectionName)
    {
        $this->SectionNum = $SectionNum;
        $this->SectionName = $SectionName;
    }

    public function addSectionMaterial($newSectionMaterial)
    {
        $this->SectionMaterial[] = $newSectionMaterial;//newSectionMaterial is a SectionMaterial class
    }

    public function getSectionMaterial()
    {
        return $this->SectionMaterial;
    }

    public function addQuiz($newQuiz)
    {
        $this->Quiz[] = $newQuiz; //newQuiz is a Quiz class
    }

    public function getQuiz()
    {
        return $this->Quiz;
    }

    public function getSectionNum()
    {
        return $this->SectionNum;
    }

    public function jsonSerialize()
    {
        return [
            $this->SectionNum,
            $this->SectionName,
            $this->SectionMaterial,
            $this->Quiz
        ];
    }
}
    
class SectionMaterial implements JsonSerializable
{
    private $MaterialNum;
    private $MaterialType;
    private $Link;

    public function __construct($MaterialNum, $MaterialType, $Link)
    {
        $this->MaterialNum = $MaterialNum;
        $this->MaterialType = $MaterialType;
        $this->Link = $Link;
    }

    public function jsonSerialize()
    {
        return [
            $this->MaterialNum,
            $this->MaterialType,
            $this->Link,
        ];
    }
}

class Quiz implements JsonSerializable
{
    private $QuizID;
    private $QuizName;
    private $QuizNum;
    private $QuizDuration;
    private $Type;
    private $PassingMark;
    private $QuizQuestion = array();

    public function __construct($QuizID, $QuizName, $QuizNum, $QuizDuration, $Type, $PassingMark)
    {
        $this->QuizID = $QuizID;
        $this->QuizName = $QuizName;
        $this->QuizNum = $QuizNum;
        $this->QuizDuration = $QuizDuration;
        $this->Type = $Type;
        $this->PassingMark = $PassingMark;
    }

    public function addQuizQuestion($QuestionNum, $Question, $QuestionType, $Marks)
    {
        $this->QuizQuestion[] = new QuizQuestion($QuestionNum, $Question, $QuestionType, $Marks);
    }

    public function getQuizQuestion()
    {
        return $this->QuizQuestion;
    }

    public function jsonSerialize()
    {
        return [
            $this->QuizID,
            $this->QuizName,
            $this->QuizNum,
            $this->QuizDuration,
            $this->Type,
            $this->PassingMark,
            $this->QuizQuestion
        ];
    }
}

class QuizQuestion implements JsonSerializable
{
    private $QuestionNum;
    private $Question;
    private $QuestionType;
    private $Marks;
    private $QuizAnswer = array();

    public function __construct($QuestionNum, $Question, $QuestionType, $Marks)
    {
        $this->QuestionNum = $QuestionNum;
        $this->Question = $Question;
        $this->QuestionType = $QuestionType;
        $this->Marks = $Marks;
    }

    public function addQuizAnswer($AnswerNum, $Answer, $Correct)
    {
        $this->QuizAnswer[] = new QuizAnswer($AnswerNum, $Answer, $Correct);
    }

    public function getQuizAnswer()
    {
        return $this->QuizQuestion;
    }
        
    public function getQuestionNum()
    {
        return $this->QuestionNum;
    }

    public function jsonSerialize()
    {
        return [
            $this->QuestionNum,
            $this->Question,
            $this->QuestionType,
            $this->Marks,
            $this->QuizAnswer
        ];
    }
}

class QuizAnswer implements JsonSerializable
{
    private $AnswerNum;
    private $Answer;
    private $Correct;

    public function __construct($AnswerNum, $Answer, $Correct)
    {
        $this->AnswerNum = $AnswerNum;
        $this->Answer = $Answer;
        $this->Correct = $Correct;
    }

    public function jsonSerialize()
    {
        return [
            $this->AnswerNum,
            $this->Answer,
            $this->Correct,
        ];
    }
}
?>
