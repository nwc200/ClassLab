<?php
    class Course{
        private $CourseID;
        private $CourseName;
        private $CourseDescription;
        //allow a course to have multiple classes and prereq
        private $Class1 = array();
        private $CoursePrereq = array();
 
        public function __construct($CourseID, $CourseName, $CourseDescription){
            $this->CourseID = $CourseID;
            $this->CourseName = $CourseName;
            $this->CourseDescription = $CourseDescription;
        } 

        public function addClass1($newClass1){
            $this->Class1[] = new $newClass1; //newClass1 is a Class1 class
        }

        public function getClass1(){
            return $this->Class1;
        }

        public function addCoursePrereq($CoursePrereq){
            $this->CoursePrereq[] = new CoursePrereq($CoursePrereq);
        }

        public function getCoursePrereq(){
            return $this->CoursePrereq;
        }
    }

    class CoursePrereq{
        private $CoursePrereq; 

        public function __construct($CoursePrereq){
            $this->CoursePrereq = $CoursePrereq;
        } 
    }

    class Class1{
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

        public function __construct($ClassID, $ClassSize, $TrainerUserName, $StartDate, $EndDate, $StartTime, $EndTime, $SelfEnrollmentStart, $SelfEnrollmentEnd){
            $this->ClassID = $ClassID;
            $this->ClassSize = $ClassSize;
            $this->TrainerUserName = $TrainerUserName;
            $this->StartDate = $StartDate;
            $this->StartTime = $StartTime;
            $this->EndTime = $EndTime;
            $this->SelfEnrollmentStart = $SelfEnrollmentStart;
            $this->SelfEnrollmentEnd = $SelfEnrollmentEnd;
        }   

        public function addSection($newSection){
            $this->Section[] = new Section($newSection); //newSection is a Section class
        }

        public function getSection(){
            return $this->Section;
        }
    }

    class Section{
        private $SectionNum;
        private $SectionName;
        private $SectionMaterial = array();
        private $Quiz = array();

        public function __construct($SectionNum, $SectionName){
            $this->SectionNum = $SectionNum;
            $this->SectionName = $SectionName;
        }   

        public function addSectionMaterial($MaterialNum, $MaterialType, $Link){
            $this->SectionMaterial[] = new SectionMaterial($MaterialNum, $MaterialType, $Link);
        }

        public function getSectionMaterial(){
            return $this->SectionMaterial;
        }

        public function addQuiz($newQuiz){
            $this->Quiz[] = $newQuiz; //newQuiz is a Quiz class
        }

        public function getQuiz(){
            return $this->Quiz;
        }
    } 


    class SectionMaterial{
        private $MaterialNum;
        private $MaterialType;
        private $Link;

        public function __construct($MaterialNum, $MaterialType, $Link){
            $this->MaterialNum = $MaterialNum;
            $this->MaterialType = $MaterialType;
            $this->Link = $Link;
        }   
    }

    class Quiz{
        private $QuizID;
        private $QuizName;
        private $QuizNum;
        private $QuizDuration;
        private $Type;
        private $PassingMark;
        private $QuizQuestion = array();

        public function __construct($QuizID, $QuizName, $QuizNum, $QuizDuration, $Type, $PassingMark){
            $this->QuizID = $QuizID;
            $this->QuizName = $QuizName;
            $this->QuizNum = $QuizNum;
            $this->QuizDuration = $QuizDuration;
            $this->Type = $Type;
            $this->PassingMark = $PassingMark;
        }   

        public function addQuizQuestion($newQuestion){  //newQuestion is a QuizQuestion class
            $this->QuizQuestion[] = $newQuestion;
        }

        public function getQuizQuestion(){
            return $this->QuizQuestion;
        }
    }

    class QuizQuestion{
        private $QuestionNum;
        private $Question;
        private $QuestionType;
        private $Marks;
        private $QuizAnswer = array();

        public function __construct($QuestionNum, $Question, $QuestionType, $Marks){
            $this->QuestionNum = $QuestionNum;
            $this->Question = $Question;
            $this->QuestionType = $QuestionType;
            $this->Marks = $Marks;
        }   

        public function addQuizAnswer($AnswerNum, $Answer, $Correct){
            $this->QuizAnswer[] = new QuizAnswer($AnswerNum, $Answer, $Correct); 
        }

        public function getQuizAnswer(){
            return $this->QuizQuestion; 
        }
    }

    class QuizAnswer{
        private $AnswerNum;
        private $Answer;
        private $Correct;

        public function __construct($AnswerNum, $Answer, $Correct){
            $this->AnswerNum = $AnswerNum;
            $this->Answer = $Answer;
            $this->Correct = $Correct;
        } 
    }
?>