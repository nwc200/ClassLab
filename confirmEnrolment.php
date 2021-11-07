<?php
require_once "objects/autoload.php";
$dao = new EnrollmentDAO;
$_SESSION["username"] = "Yu Hao";
$username = $_SESSION["username"];
$courseid = $_GET['courseid'];
$classid = $_GET['classid'];
$name = $username;

$dao2 = new CourseDAO();
$courses = $dao2->retrieveAll();

$status = 'Approved';
$enrolDAO = new SectionDAO();
$enrolments = $enrolDAO->retrieveUserApprovedEnrolment($username, $status); 
$userCourses = (object)[];
$counter = 0;
if (isset($_GET['whichCourse'])) {
    $zero = $_GET['whichCourse'];
}

foreach ($enrolments as $enrol) {
    $getCourse= $enrol->getCourse();
    $courseID = $getCourse->getCourseID();
    $getcourses = $enrolDAO->retrieveCourses($courseID); //return user enrolled courses
    $courseName = $getcourses->getCourseName();
    $classID = $enrolDAO->getLearnerClassID($courseID, $username);
    $userCourses->$counter = [$courseName, $classID];
    $counter++;
}
// var_dump($userCourses);
    
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"
        integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>LMS - Confirm enrolment</title>

</head>

<body>
<header>
<br>
    <div class="" id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" >Learning Management System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                    <a class="nav-link active" href="ViewCourseByEligibility.php" active>View Course <span
                                class="sr-only">(current)</span></a>
                    </li>
                   
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Courses Enrolled
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <div v-for="(each, i) in usercourses">
                                <a :value="i" :href="'ViewCourseMaterials.php?whichCourse='+i" class="dropdown-item" > {{usercourses[i][0]}} - Class
                                    {{usercourses[i][1]}}</a>
                            </div>
                        </div>
                    
                </ul>
                Learner: <?=$username?>
            </div>
        </nav>
    </div>
</header>
    <main style="margin-top: 10px;">
        <div class="container" id="app">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Confirm Enrolment</h2>

                    <hr>

                    <?php
                    $enrolment = $dao->retrieveEnrolment($courseid, $classid);
                    $status = $dao->retrieveEnrolment($courseid, $classid);

                
                    if ($dao->addPendingEnrolment($name, $courseid, $classid) ) {
                        echo "<div class='alert alert-success' role='alert'>
                        Enrolment Success! Enrollment application will be send to the HR for approval.
                    </div>";
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>
                        Enrolment failure! Please try again. 
                    </div>";
                    }
               
                    ?>
                    <a class="btn btn-primary" href="ViewCourseByEligibility.php" role="button">Back to Home</a>
                </div>
            </div>
        </div>
    </main>
     <script>
        var app = new Vue({
            el: "#app",
            data: {
                usercourses: <?php print json_encode($userCourses) ?>,
            }

        })
        </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</body>

</html>