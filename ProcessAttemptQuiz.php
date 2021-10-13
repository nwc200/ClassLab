<?php
require 'objects/autoload.php';

$dao = new QuizDAO();

$username = $_POST["username"];
$quizid = $_POST["quizid"];
$passingmark = $_POST["passingmark"];
unset($_POST['passingmark']);
$classid = $_POST["classid"];
$type = $_POST["type"];

$attemptno = $dao->getAttemptNo($quizid, $username) + 1;
$quiz = $dao->getTrainerCourse("Wei Cheng");
$quiz = $dao->getQuiz($quizid);

$markarr=[];
$ansarr = [];
$corrarr=[];
$questionarr=[];
foreach ($_POST as $key => $value) {
    if (strpos($key, "mark")) {
        $pieces = explode("mark", $key);
        $questionmark = $pieces[1];
        $questionnum = $pieces[0];
        array_push($markarr, $questionmark);
        array_push($questionarr, $questionnum);
    }
    if (strpos($value, "corr")) {
        $pieces = explode("corr", $value);
        $ansnum = $pieces[0];
        $anscorr = $pieces[1];
        array_push($ansarr, $ansnum);
        array_push($corrarr, $anscorr);
    }
}
// var_dump($markarr);
// var_dump($ansarr);
// var_dump($corrarr);
// var_dump($questionarr);

$marks = 0;
//check correct

for ($i=0; $i<count($markarr); $i++) {
    if ($corrarr[$i] == 1) {
        $marks += $markarr[$i];
    }
}


if ($marks >= $passingmark) {
    $dao->addStudentQuizAttempt($username, $quizid, $attemptno, 1);
} else {
    $dao->addStudentQuizAttempt($username, $quizid, $attemptno, 0);
}

for ($i=0; $i<count($markarr); $i++) {
    if ($corrarr[$i] == 0) {
        $dao->addStudentQuizRecord($username, $quizid, 0, $attemptno, $questionarr[$i], $ansarr[$i]);
    } elseif ($corrarr[$i] == 1) {
        $dao->addStudentQuizRecord($username, $quizid, $markarr[$i], $attemptno, $questionarr[$i], $ansarr[$i]);
    }
}

var_dump($marks);
var_dump($passingmark);
if ($marks>=$passingmark && $type == "Graded") {
    $dao->quizUpdateEnrolment($username, $classid, 1);
}

header('Location: QuizAttemptSuccess.html');

exit;



?>
