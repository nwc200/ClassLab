<?php 
    require_once "objects/autoload.php";

    $username = "Yu Hao";

    // Get user information
    $dao = new UserDAO();
    $user = $dao->retrieve($username);
    // var_dump($user);


    // $courseID = "1";
    // // Get user information
    // $dao2 = new CourseDAO();
    // $course = $dao2->retrieve($courseID);
    // var_dump($course);
    $enrolDAO = new SectionDAO();
    // $getQuiz = $enrolDAO->retrieveClassQuiz(1);
    var_dump($getQuiz);

    $userName = "Mei Lan";
    $status = 'Approved';
    // Get user information
    $dao3 = new SectionDAO();
    // var_dump($dao3->updateMaterialProgress(1, 1, 2))
?> 