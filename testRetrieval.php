<!-- <?php 
    require_once "objects/autoload.php";

    // $username = "Yu Hao";

    // // Get user information
    // $dao = new UserDAO();
    // $user = $dao->retrieve($username);
    // var_dump($user);


    // $courseID = "1";
    // // Get user information
    // $dao2 = new CourseDAO();
    // $course = $dao2->retrieve($courseID);
    // var_dump($course);


    $userName = "Mei Lan";
    $status = 'Approved';
    // Get user information
    $dao3 = new EnrolmentDAO();
    // $enrol = $dao3->retrieveEnrolment($username);
    // var_dump($enrol);

    $getClass = $dao3->getLearnerCourse($userName, $status);
    var_dump($getClass);

    

    // $dao4 = new SectionDAO();
    // $getSection = $dao4->getLearnerSection($userName,$status);
    // var_dump($getSection);

    // $username =  "Wei Cheng";
    // $dao = new QuizDAO();
    // $course = $dao->getTrainerCourse($username);
    // var_dump($course);

?> -->