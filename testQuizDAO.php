<?php 
    require_once "objects/autoload.php";

    $username = "Wei Cheng";

    // Get user information
    $dao = new QuizDAO();
    $course = $dao->getTrainerCourse($username);
    // var_dump($course);

    // $course = $dao->getClassSectionQuiz($username, 1);
    // var_dump($course);
    // var_dump($course->getSection());

    //$test = $dao->getQuizID(1, 1, 1, "Ungraded");
    //$test = $dao->addGradedQuiz(1, 2, "test", 5, 10, "Graded", 10);
    //var_dump($test);
    $quiz = $dao->getQuiz(1);
    // var_dump($quiz->getQuizQuestion()[0]);

    // var_dump($dao->retrieveClassQuiz(1));

?>
