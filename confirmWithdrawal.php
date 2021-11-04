<?php
require_once "objects/autoload.php";
$dao = new EnrollmentDAO;

$_SESSION["username"] = "Yu Hao";
$username = $_SESSION["username"];

$courseid = $_GET['courseid'];
$classid = $_GET['classid'];
$name = $username;
$enrolmentstatus = "Pending";



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
    <title>LMS - Confirm Withdrawal</title>

</head>

<body>
<br>
<div class="" id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="ViewCourseByEligibility.php">LMS Self Enrollment System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link active" href="ViewCourseMaterials.php" active>View Course <span
                                class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link " href="ViewQuizMaterials.php">Course Materials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ViewQuizMaterials.php">Quizzes Available </a>
                    </li>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Courses Enrolled
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <div v-for="(each, i) in usercourses">
                                <a class="dropdown-item" :value="i" @click='test([i])'> {{usercourses[i][1]}} - Class
                                    {{usercourses[i][2]}}</a>
                            </div>
                        </div>
                    </div>

                </ul>
                Learner: <?=$username?>

            </div>
        </nav>
        <br>

    <main style="margin-top: 10px;">
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h2>Confirm Withdrawal</h2>
               
                <hr>

                <?php
                $enrolment = $dao->retrievePendingEnrolment($courseid, $classid);

                $enrolmentstatus = "Pending";

                if ($dao->withdrawSelfEnrol($name, $classid, $enrolmentstatus)) {


                    echo "<div class='alert alert-success' role='alert'>
                        Withdrawal Success! Your enrolment application had been withdrawn.
                    </div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>
                        Withdrawal failure! Please try again. 
                    </div>";
                }

                ?>
                <a class="btn btn-primary" href="ViewCourseByEligibility.php?courseid=$courseid" role="button">Back to Home</a>
            </div>
        </div>
    </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>