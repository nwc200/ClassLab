<?php
//Done by Ng Wei Cheng G5T7

class TestQuiz extends \PHPUnit\Framework\TestCase
{
    public function testQuizGetTotalMarksSum()
    {
        require 'objects/Course.php';
        require 'objects/Enrollment.php';
        require 'objects/Progress.php';
        require 'objects/User.php';

        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);
        $quiz->addQuizQuestion(1, "Question1", "MCQ", 10);
        $quiz->addQuizQuestion(2, "Question1", "MCQ", 5);
        $score = $quiz->getTotalMarks();
        //5+10=15
        $correctScore = 15;

        $this->assertSame($score, $correctScore);
    }
    public function testQuizGetTotalMarksAttemptMade()
    {
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("There are no quiz questions.");
        $score = $quiz->getTotalMarks();
    }
    public function testQuizGetTotalMarksPositive()
    {
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);
        $quiz->addQuizQuestion(1, "Question1", "MCQ", -1);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Score cannot be negative.");
        $score = $quiz->getTotalMarks();
    }
    public function testQuizGetNumberQuestionsEqual()
    {
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);
        $quiz->addQuizQuestion(1, "Question1", "MCQ", 1);
        $quiz->addQuizQuestion(2, "Question1", "MCQ", 1);
        $count = $quiz->getNumberOfQuestions();
        $this->assertSame($count, 2);
    }
    public function testQuizDuplicate()
    {
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);
        $quiz->addQuizQuestion(1, "Question1", "MCQ", 1);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Duplicated Quiz Question Added.");
        $quiz->addQuizQuestion(1, "Question1", "MCQ", 1);

    }
    public function testConstructType()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid quiz type entered.");
        $quiz = new Quiz(1, "Quiz1", 1, 10, "fail", 5);
    }
}
?>