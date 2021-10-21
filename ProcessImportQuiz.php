<?php
require_once "objects/autoload.php";
$quizid = $_GET["quizid"];
$classid = $_GET["classid"];
$sectionnum = $_GET["sectionnum"];
$username = $_SESSION["username"];

$dao = new QuizDAO();
$course = $dao->getTrainerCourse($username);
$quiz= $dao->getQuiz($quizid);
$dao->addQuiz($classid, $sectionnum, $quiz->getQuizName(), 1, $quiz->getQuizDuration(), "Ungraded", 0);
$newquizid = $dao->getQuizID($classid, $sectionnum, 1, "Ungraded")[0];
var_dump($newquizid);
foreach ($quiz->getQuizQuestion() as $quizquestion) {
    $dao->addQuizQuestion($newquizid, $quizquestion->getQuestionNum(), $quizquestion->getQuestion(), $quizquestion->getQuestionType(), 0);
    foreach ($quizquestion->getQuizAnswer() as $quizanswer) {
        $dao->addQuizAnswer($quizanswer->getAnswerNum(), $newquizid, $quizquestion->getQuestionNum(), $quizanswer->getAnswer(), $quizanswer->getAnswerCorrect());
    }
}

header('Location: QuizSuccess.html');
exit;
?>