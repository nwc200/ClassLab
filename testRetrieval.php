<?php 
    require_once "../objects/autoload.php";

    $username = "Yu Hao";

    // Get user information
    $dao = new UserDAO();
    $user = $dao->retrieve($username);
    var_dump($user);


    $courseID = "1";
    // Get user information
    $dao2 = new CourseDAO();
    $course = $dao2->retrieve($courseID);
    var_dump($course);

?>