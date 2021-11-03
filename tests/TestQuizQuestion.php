<?php
//Done by Ng Wei Cheng G5T7

class TestQuizQuestion extends \PHPUnit\Framework\TestCase
{
    public function testQuizQuestionAddMarkSame()
    {
        require 'objects/Course.php';
        require 'objects/Enrollment.php';
        require 'objects/Progress.php';
        require 'objects/User.php';

        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 10);
        $quizquestion->addMarks(1);
        $this->assertSame((int)$quizquestion->getQuestionMark(), 11);
    }
    public function testQuizQuestionAddMarkInt()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 10);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Input must be integer.");
        $quizquestion->addMarks(1.2);
    }
    public function testQuizQuestionAddMarkInt2()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 10);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Input must be integer.");
        $quizquestion->addMarks("hello");
    }

    public function testQuizQuestionMinusMarkSame()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 10);
        $quizquestion->minusMarks(1);
        $this->assertSame((int)$quizquestion->getQuestionMark(), 9);
    }
    public function testQuizQuestionMinusMarkInt()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 10);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Input must be integer.");
        $quizquestion->minusMarks(1.2);
    }
    public function testQuizQuestionMinusMarkInt2()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 10);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Input must be integer.");
        $quizquestion->minusMarks("hello");
    }

    public function testQuizQuestionMinusMarkNegative()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 0);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Marks cannot be less than 0.");
        $quizquestion->minusMarks(1);
    }
    public function testQuizQuestionGetNumberAnswersEqual()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "MCQ", 0);
        $quizquestion->addQuizAnswer(1, "True", 0);
        $quizquestion->addQuizAnswer(2, "False", 1);
        $count = $quizquestion->getNumberOfAnswers();
        $this->assertSame($count, 2);
    }
    public function testQuizQuestionAddQuizAnswer()
    {
        $quizquestion = new QuizQuestion(1, "Question1", "TF", 0);
        $quizquestion->addQuizAnswer(1, "True", 0);
        $quizquestion->addQuizAnswer(2, "True", 1);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Correct Answer Already Added.");
        $quizquestion->addQuizAnswer(3, "False", 1);
        $count = $quizquestion->getNumberOfAnswers();
    }
    public function testQuizQuestionConstructType()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid quiz type entered.");
        $quizquestion = new QuizQuestion(1, "Question1", "TEST", 0);
    }
}
?>