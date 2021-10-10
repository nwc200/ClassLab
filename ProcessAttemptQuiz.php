<?php
require 'objects/autoload.php';

var_dump($_POST);
$dao = new QuizDAO();

$username = $_POST["username"];
$quizid = $_POST["quizid"];
$passingmark = $_POST["passingmark"];
unset($_POST['passingmark']);

$attemptno = $dao->getAttemptNo($quizid, $username) + 1;
$quiz = $dao->getTrainerCourse("Wei Cheng");
$quiz = $dao->getQuiz($quizid);

$markarr=[];
$ansarr = [];
$corrarr=[];
$questionarr=[];
foreach ($_POST as $key => $value) {
    if (strpos($key, "mark")) {
        $questionmark = substr($key, -1);
        $questionnum = substr($key, 0, 1);
        array_push($markarr, $questionmark);
        array_push($questionarr, $questionnum);
    }
    if (strpos($value, "corr")) {
        $ansnum = substr($value, 0, 1);
        $anscorr = substr($value, -1);
        array_push($ansarr, $ansnum);
        array_push($corrarr, $anscorr);
    }
}
var_dump($markarr);
var_dump($ansarr);
var_dump($corrarr);
var_dump($questionarr);

$marks = 0;
//check correct

for ($i=0; $i<count($markarr); $i++) {
    if ($corrarr[$i] == 1) {
        $marks += $markarr[$i];
    }
}


if ($marks >= $passingmark) {
    $dao->addStudentQuizAttempt($username, $quizid, $attemptno, true);
} else {
    $dao->addStudentQuizAttempt($username, $quizid, $attemptno, false);
}

for ($i=0; $i<count($markarr); $i++) {
    if ($corrarr[$i] == 0) {
        $dao->addStudentQuizRecord($username, $quizid, 0, $attemptno, $questionarr[$i], $ansarr[$i]);
    } elseif ($corrarr[$i] == 1) {
        $dao->addStudentQuizRecord($username, $quizid, $markarr[$i], $attemptno, $questionarr[$i], $ansarr[$i]);
        $marks += $markarr[$i];
    }
}


//header('Location: QuizSuccess.html');
//exit;



?>