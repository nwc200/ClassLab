<?php
require 'objects/autoload.php';
if ( isset($_POST['submit']) ) {
    $quiznum = $_POST['quiznum'];
    $classid = $_POST['classid'];
    $sectionnum = $_POST['sectionnum'];
    $quiztitle = $_POST['quiztitle'];
    $quizduration = $_POST['quizduration'];
    $quiztype = $_POST['quiztype'];

    $questiontype=[];
    $quizquestion =[];
    $quizquestionans=[];
    $quizquestionanscorrect=[];
    $quizmarks=[];
    $questioncount=0;
    foreach ($_POST as $key => $value) {
        if (strpos($key, "uestiontype")) {
            array_push($questiontype, $value);
        }
        if (strpos($key, "etquestion")) {
            array_push($quizquestion, $value);
            array_push($quizquestionans, []);
            $questioncount+=1;
            $answercount=0;
        }
        if (strpos($key, "uestionTF")) {
            array_push($quizquestionans[$questioncount-1], "True", "False");
            if ($value == "True") {
                array_push($quizquestionanscorrect, 0);
            } else {
                array_push($quizquestionanscorrect, 1);
            }
        }
        if (strpos($key, "nswer")) {
            array_push($quizquestionans[$questioncount-1], $value);
            $answercount +=1;
        }

        if (strpos($key, "uestionMCQ")) {
            array_push($quizquestionanscorrect, $answercount-1);
        }
        if ($quiztype == "Graded") {
            if (strpos($key, "arks")) {
                array_push($quizmarks, $value);
            }
        }
    }

    var_dump($questiontype);
    var_dump($quizquestion);
    var_dump($quizquestionans);
    var_dump($quizquestionanscorrect);
    var_dump($quizmarks);

    if (empty($questiontype) || empty($quizquestion) || empty($quizquestionans) || empty($quizquestionanscorrect) ) {
        echo "Empty fields detected, please enter at least one question with an answer";
        exit();
    }

    if ($quiztype == "Ungraded") {
        $quizpassingmark = $_POST['quizpassingmark'];
        $dao = new QuizDAO();
        $add = $dao->addQuiz($classid, $sectionnum, $quiztitle, $quiznum, $quizduration, $quiztype, $quizpassingmark);
        $quizid = $dao->getQuizID($classid, $sectionnum, $quiznum, "Ungraded")[0];
        var_dump($quizid);
        for ($i=0; $i<count($quizquestion); $i++) {
            $addquestion = $dao->addQuizQuestion($quizid, $i+1, $quizquestion[$i], $questiontype[$i], 0);
            var_dump($addquestion);
        }
        for ($a=0; $a<count($quizquestionans); $a++) {
            for ($b=0; $b<count($quizquestionans[$a]); $b++) {
                if ($quizquestionanscorrect[$a] == $b) {
                    $addanswer = $dao->addQuizAnswer($b+1, $quizid, $a+1, $quizquestionans[$a][$b], true);
                    var_dump($addanswer);
                } else {
                    $addanswer = $dao->addQuizAnswer($b+1, $quizid, $a+1, $quizquestionans[$a][$b], false);
                    var_dump($addanswer);
                }
            }
        }
        var_dump($quizid);
    }
    //process graded and ungraded separately, graded need insert to all classes of the course
    //passing mark is 85% of total marks
    if ($quiztype == "Graded") {
        //calculate passing mark
        $totalmarks=0;
        foreach ($quizmarks as $num) {
            $totalmarks +=$num;
        }
        $quizpassingmark = floor($totalmarks * 0.85);
        $dao = new QuizDAO();
        // add quiz to all class of same course
        $add = $dao->addGradedQuiz($classid, $sectionnum, $quiztitle, $quiznum, $quizduration, $quiztype, $quizpassingmark);
        $quizidarr = $dao->getQuizID($classid, $sectionnum, $quiznum, "Graded");
        foreach ($quizidarr as $quizid) {
            for ($i=0; $i<count($quizquestion); $i++) {
                $addquestion = $dao->addQuizQuestion($quizid, $i+1, $quizquestion[$i], $questiontype[$i], $quizmarks[$i]);
            }
            for ($a=0; $a<count($quizquestionans); $a++) {
                for ($b=0; $b<count($quizquestionans[$a]); $b++) {
                    if ($quizquestionanscorrect[$a] == $b) {
                        $addanswer = $dao->addQuizAnswer($b+1, $quizid, $a+1, $quizquestionans[$a][$b], true);
                    } else {
                        $addanswer = $dao->addQuizAnswer($b+1, $quizid, $a+1, $quizquestionans[$a][$b], false);
                    }
                }
            }
        }
    }
    header('Location: QuizSuccess.html');
    exit;
} else {
    echo "Invalid submission";
}

?>