<?php
require_once "objects/autoload.php";

$_SESSION["username"] = "Yu Hao";
$username = $_SESSION["username"];
$courseid = $_GET["courseid"];

$dao = new CourseDAO();
$courses = $dao->retrieveAll();
$course = $dao->retrieve($courseid);
$coursename = $course->getCourseName();
$today_date = date("Y-m-d");


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
    // var_dump($userCourses);
}

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
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>LMS - View Class</title>
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
                    <h2>View Class</h2>
                    <hr>

                    <div class='row my-3'>
                        <div class='col-8'>
                            <h5><?= $coursename ?></h5>
                        </div>


                        <label for="date">Start Date:</label>
                        <input type="date" id="startDate" name="startDate">
                        <input type="submit" onclick="filterDate()" value="Submit">


                    </div>


                    <?php
                    $classes = $dao->retrieveCourseClasses($courseid);
                    
                    if (empty($classes)) {
                        echo "<div class='alert alert-danger my-4' role='alert'>
                            No Class Found!
                        </div>";
                    } else {
                        echo "<ul id='myUL' style='list-style-type: none; padding: 0;'>";
                        $dao2 = new EnrollmentDAO();
                        foreach ($classes as $class) {
                            $classid = $class->getClassID();
                            $students = $dao2->retrievePendingEnrolment($courseid, $class->getClassID());

                            $remainingSlot = (int)$class->getClassSize()-$students;
                            $enrolPageHref = "enrolpage.php?courseid=$courseid&classid=$classid";
                            $enrolStart = (new DateTime($class->getSelfEnrollmentStart()))->format('Y-m-d');
                            $enrolEnd = (new DateTime($class->getSelfEnrollmentEnd()))->format('Y-m-d');
                            
                            if ($today_date <= $enrolEnd && $today_date >= $enrolStart) {
                                //If within self-enrolment period
                                
                                echo
                                "<li value='{$class->getStartDate()}'>
                                
                                    <div class='row'>
                                        <div class='col-sm-8'>
                                        </div>
                                        <div class='col-2'>
                                            <b>Class Size:</b> {$class->getClassSize()}
                                        </div>
                                        <div class='col-2'>
                                            <b>Remaining slot:</b> $remainingSlot
                                        </div>

                                        <div class='col-sm-8'>
                                            ClassID<br>
                                            Class Starting Date<br>
                                            Class Ending Date<br>
                                            Trainer<br>
                                            Self-enrollment Period<br>
                                        </div>

                                        <div class='col-auto'>
                                            {$class->getClassID()}<br>
                                            {$class->getStartDate()}, {$class->getStartTime()}<br>
                                            {$class->getEndDate()}, {$class->getEndTime()}<br>
                                            {$class->getTrainerUserName()}<br>
                                            {$class->getSelfEnrollmentStart()} ~ {$class->getSelfEnrollmentEnd()} <br>
                                            </div>
                                        </div>
                                        <div class='row mt-3'>
                                            <div class='col-sm-8'>
                                            </div>
                                            <div class='col-2'>";

                                $withdrawPageHref = "withdrawpage.php?courseid=$courseid&classid={$class->getClassID()}";
                                if ($students == 0) {
                                    echo "<button type='button' class='btn btn-secondary' disabled>Withdraw</button>";
                                } else {
                                    echo "<a class='btn btn-success' href='$withdrawPageHref' role='button'>Withdraw</a>";
                                }
                                echo "</div>
                                <div class='col-2'>";

                                $enrolPageHref = "enrolpage.php?courseid=$courseid&classid={$class->getClassID()}";
                                if ($remainingSlot == 0 || $students == 1) {
                                    echo "<button type='button' class='btn btn-secondary' disabled>Enrol</button>";
                                } else {
                                    echo "<a class='btn btn-success' href='$enrolPageHref' role='button'>Enrol</a>";
                                }
                                echo "</div>
                                    </div>
                                <hr>";
                            } else {
                                //echo" There is no available class ";
                            }
                        }
                        echo "</ul>";
                    }


                    ?>
                    <br>
                    <a class='btn btn-success text-align: right' href='ViewCourseByEligibility.php?classid=$classid'
                        role='button'>back</a>
                </div>
            </div>
        </div>

        <script>
        function filterDate() {
            input = document.getElementById("startDate");
            ul = document.getElementById("myUL");
            li = document.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                courseStartDate = li[i].getAttribute("value");
                //console.log(input.value, courseStartDate);
                // console.log(input);
                // console.log(courseStartDate);
                if (input.value == courseStartDate) {
                    li[i].style.display = "";
                } else {

                    li[i].style.display = "none";
                }
            }

        }
        </script>
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