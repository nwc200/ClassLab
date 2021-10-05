<?php 
    require_once "objects/autoload.php";

    $username = "Wei Cheng";

    // Get user information
    $dao = new QuizDAO();
    $course = $dao->getTrainerCourse($username);
    var_dump($course);

    $course = $dao->getClassSectionQuiz($username, 1);
    var_dump($course);
    var_dump($course->getSection());

    $class = new Class1(1,1,1,1,1,1,1,1,1);
    var_dump(json_encode($class));

    //$test = $dao->getQuizID(1,1,1,"Ungraded");
    //$test = $dao->addGradedQuiz(1,2,"test",5,10,"Graded",10);
    //var_dump($test);


?>
