<?php
require_once 'objects/autoload.php';

// if (isset($_SESSION["user"])) {
//     $username = $_SESSION["user"];
// } else {
//     header("Location: before_home.html");
// }

$_SESSION["username"] = "Yu Hao";
$username = $_SESSION["username"];
$courseid = $_GET["courseid"];
$classid = $_GET["classid"];

$dao = new CourseDAO();
//$dao2= new EnrollmentDAO();
$course = $dao->retrieve($courseid);
$coursename = $course->getCourseName();
//$enrollment = $dao2->retrieveEnrolment($courseID, $classID)


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>Confirm Self-Enrolment Page</title>
</head>

<body>
<header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" >Learning Management System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">


                </ul>
                Welcome, <?=$username?>
            </div>
        </nav>
    </header>
    <main style="margin-top: 10px;">
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h2>Confirm Enrolment</h2>
                <hr>
                <h5><?= $coursename ?></h5>

                <?php
                $class = $dao->retrieveCourseClass($courseid, $classid);
                // $ConfirmEnrolPageHref = "confirmEnrolment.php?courseid=$courseid&classid=$classid";
                // $confirmEnrolPageHref = "confirmEnrolment.php?courseid=$courseid&classid={$class->getClassID()}";

                echo "<div class='row'>
                            <div class='col-sm-8'>
                                ClassID<br>
                                Class Starting Date<br>
                                Class Ending Date<br>
                                Trainer<br>
                                Self-enrolment Period<br>
                            </div>
                            <div class='col-auto'>
                                {$class->getClassID()}<br>
                                {$class->getStartDate()}, {$class->getStartTime()}<br>
                                {$class->getEndDate()}, {$class->getEndTime()}<br>
                                {$class->getTrainerUserName()}<br>
                                {$class->getSelfEnrollmentStart()} ~ {$class->getSelfEnrollmentEnd()}<br>
                            </div>
                        </div>";


                ?>


                <hr>

                <div class="row">

                    <div class="col text-right">
                        <a :href="'ViewCourseByEligibility.php?classid='+classid" button type="cancel" class="btn btn-danger mb-3 text-center">Cancel</a>
                    </div>
                    <div class="col">

                        <a :href="'confirmEnrolment.php?classid='+classid+'&courseid='+courseid" button type="confirm" class="btn btn-primary mb-3">Confirm</a>
                    </div>
                </div>
                <input type="hidden" name="courseid" value="<?= $courseid ?>">
                <input type="hidden" name="classid" value="<?= $classid ?>">
            </div>
            </form>
        </div>
    </div>
    </div>
    </main>

    <script>
        var app = new Vue({
            el: "#app",
            data: {

                courseid: <?php print json_encode($courseid) ?>,
                classid: <?php print json_encode($classid) ?>,

            },

        })
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>