<?php

class TestStudentQuizAttempt extends \PHPUnit\Framework\TestCase
{
    public function testCalculateTotalMarksScored()
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
        $correctScore = 10;

        $this->assertSame($score, $correctScore);
    }
}
?>