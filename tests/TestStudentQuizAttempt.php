<?php
//Done by Ng Wei Cheng G5T7

class TestStudentQuizAttempt extends \PHPUnit\Framework\TestCase
{
    public function testCalculateTotalMarksScoredCalculation()
    {
        require 'objects/Course.php';
        require 'objects/Enrollment.php';
        require 'objects/Progress.php';
        require 'objects/User.php';
        
        $user = new User("Test", "Test", "Test@gmail.com", "Dept1", "Design1", "Learner");
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);

        $testClass = new StudentQuizAttempt($user, $quiz, 1, "Pass");
        
        //$QuizRecord, $Marks, $QuestionNum, $StudentAns
        $testClass->addStudentQuizRecord(1, 5, 1, 1);
        $testClass->addStudentQuizRecord(2, 5, 1, 1);
        $score = $testClass->calculateTotalMarksScored();
        //5+5=10
        $correctScore = 10;

        $this->assertSame($score, $correctScore);
    }

    public function testCalculateTotalMarksScoredAttemptMade()
    {
        $user = new User("Test", "Test", "Test@gmail.com", "Dept1", "Design1", "Learner");
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);

        $testClass = new StudentQuizAttempt($user, $quiz, 1, "Pass");
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("There are no student quiz records.");
        $score = $testClass->calculateTotalMarksScored();
    }

    public function testCalculateTotalMarksScorePositive()
    {
        $user = new User("Test", "Test", "Test@gmail.com", "Dept1", "Design1", "Learner");
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);

        $testClass = new StudentQuizAttempt($user, $quiz, 1, "Pass");
        $testClass->addStudentQuizRecord(1, -1, 1, 1);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Score cannot be negative.");
        $score = $testClass->calculateTotalMarksScored();
    }

    public function testAddStudentQuizRecordDuplicate()
    {
        $user = new User("Test", "Test", "Test@gmail.com", "Dept1", "Design1", "Learner");
        $quiz = new Quiz(1, "Quiz1", 1, 10, "Graded", 5);

        $testClass = new StudentQuizAttempt($user, $quiz, 1, "Pass");
        $testClass->addStudentQuizRecord(1, 1, 1, 1);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Duplicated Student Quiz Record Added.");
        $testClass->addStudentQuizRecord(1, 1, 1, 1);

    }

}
?>