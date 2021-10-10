<?php
    require_once 'objects/autoload.php';

    // if (isset($_SESSION["user"])) {
    //     $username = $_SESSION["user"];
    // } else {
    //     header("Location: before_home.html");
    // }

    $_SESSION["username"] = "Xi Hwee";
    $username = $_SESSION["username"];
    $courseid = $_GET["courseid"];
    $classid = $_GET["classid"];

    $dao = new CourseDAO();
    $course = $dao->retrieve($courseid);
    $coursename = $course->getCourseName();
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
    <title>LMS - Edit Enrollment Period</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h1>Confirm Enrolment</h1> 
                <p>Welcome <?= $username?></p>
                <hr>
                <h5><?= $coursename ?></h5>

                <?php
                    $class = $dao->retrieveCourseClass($courseid, $classid);

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
                <!-- <h5 class="text-center">New Enrollment Period</h5>
                <form action="ProcessEditEnrollmentPeriod.php" method="POST">
                    <div class="row justify-content-center my-5" >
                        <label for="startDate" class="col-form-label"><b>Start Date</b></label>
                        <div class="col-sm-4 mr-5">
                            <input type="date" class="form-control" id="startDate" name="startDate">
                        </div>
                        <label for="endDate" class="col-form-label"><b>End Date</b></label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="endDate" name="endDate">
                        </div>
                        </div> -->

                   
                    <div class="row">
                  
                    <div class="col text-right">
                        <button type="cancel" class="btn btn-danger mb-3 text-center">Cancel</button>
                    </div>
                    <div class="col">
                        <a href="confirmEnrolment.php" button type="confirm" class="btn btn-primary mb-3">Confirm</a>
                    </div>
                </div>
                     <input type="hidden" name="courseid" value="<?= $courseid ?>">
                        <input type="hidden" name="classid" value="<?= $classid?>">
                    </div>
                </form>
            </div>
        </div>
    </div> 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>