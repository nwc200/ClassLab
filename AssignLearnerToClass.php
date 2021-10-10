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
    <title>LMS - Assign Learner</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h1>Assign Learner</h1> 
                <p>Welcome <?= $username?></p>
                <hr>
                <h5><?= $coursename ?></h5>

                <?php
                    $class = $dao->retrieveCourseClass($courseid, $classid);
                    $dao2 = new EnrollmentDAO();
                    $students = $dao2->retrieveEnrolment($courseid, $classid);
                    $remainingSlot = (int)$class->getClassSize()-$students;

                    echo "<div class='row'>
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
                                {$classid}<br>
                                {$class->getStartDate()}, {$class->getStartTime()}<br>
                                {$class->getEndDate()}, {$class->getEndTime()}<br>
                                {$class->getTrainerUserName()}<br>
                                {$class->getSelfEnrollmentStart()} ~ {$class->getSelfEnrollmentEnd()}<br>
                            </div>
                        </div>";
                ?>
                <hr>

                <?php
                $learnersID = $dao2->retrieveQualifiedLearners($courseid);
                if (empty($learnersID)) {
                    echo "<div class='alert alert-warning' role='alert'>
                        There is no learner to assign! Click <a href='ViewClass.php?courseid=$courseid'>here</a> to go back. 
                    </div>";
                } else {
                    echo "<div class='mb-3 row'>
                            <label for='searchLearner' class='col-sm-1 col-form-label'><b>Learner</b></label>
                            <div class='col-sm-4'>
                                <input type='text' class='form-control' id='searchLearner' size='30' placeholder='Search name here'>
                            </div>
                            <div class='col-auto'>
                                <button type='submit' class='btn btn-primary mb-3'>Search</button>
                            </div>
                        </div>

                        <form action='ProcessAssignLearner.php' method='POST'>
                            <table class='table table-striped'>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Number of Enrolled Course</th>
                                        <th>Assign</th>
                                    </tr>
                                </thead>
                                <tbody>";

                    foreach ($learnersID as $name) {
                        $numOfCourses = $dao2->retrieveNumberOfCourses($name);

                        echo "<tr>
                                <td>$name</th>
                                <td>$numOfCourses</td>
                                <td>
                                    <button type='submit' class='btn btn-primary' name='submit' value='$name'>Assign</button>
                                    <input type='hidden' name='courseID' value='$courseid'>
                                    <input type='hidden' name='classID' value='$classid'>
                                </td>
                            </tr>";
                    }

                    echo "</tbody>
                        </table>
                    </form>";
                }
                ?>
            </div>
        </div>
    </div> 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>