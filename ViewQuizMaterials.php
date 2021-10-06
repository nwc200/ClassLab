<?php

require_once "objects/autoload.php";

$_SESSION['username'] = "Mei Lan";
$username = $_SESSION['username'];

$status = 'Approved';

$getCourseDAO = new EnrolmentDAO();
$course = $getCourseDAO->getLearnerCourse($username, $status);
// var_dump($getCourse);
// $getCourseName = $getCourse['CourseName'];
// echo $getCourseName;

$url = "./materials/week1/Week1b-SPM-fundamentals-v1.0.pdf";
$percent = '20%';

$hide = 'hidden';
$empty = '';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>View Course Materials</title>
</head>

<body>
    <br>
    <div class="" id="app">
        <!-- {{course}} -->
        <!-- {{courseName(course)}} -->

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="ViewCourseMaterials.php">LMS Self Enrollment System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="ViewCourseMaterials.php" active>Course Materials </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="ViewQuizMaterials.php">Quizzes Available <span class="sr-only">(current)</span></a>
                    </li>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Courses Enrolled
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="" v-for="value in course" value={{value[1}}> {{value[1]}} </a>
                        </div>
                    </div>

                </ul>

                Learner: {{username}}

            </div>
        </nav>

        <br>

        <h4 style="text-align:center">
            {{coursename[1]}}
        </h4>

    </div>

    <div class="container" >



        <p style="margin:2 0px;">Quiz Progress</p>
        <div class="col progress">
            <div class="progress-bar" id='progressBar' role="progressbar" style="width:<?= $percent ?>;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $percent ?></div>
        </div>

    </div>


    <div class='container-fluid' style="margin:30px; padding: 20px">
        <form method='POST' action='completionProgress.php'>
            <table class="table">
                <thead>
                    <tr>
                        <td>Section</td>
                        <td>Quiz</td>
                        <td>Attempts</td>
                        <td>Scores</td>
                        <td>View Attempt</td>
                    </tr>
                </thead>

                <tr class="hide1">
                    <td>
                        <b>Session 1</b>
                    </td>
                    <td>
                        <a class="quiz" href='<?= $url ?>'>Quiz 1</a><br>
                    </td>

                    <td>
                        <p>Attempt 1</p>
                        <p>Attempt 2</p>
                    </td>

                    <td>
                        <p>5 / 10</p>
                        <p>8 / 10</p>
                    </td>

                    <td>
                        <button type="button" class="btn btn-outline-primary btn1" style="margin:1px;">View</button> <br>
                        <button type="button" class="btn btn-outline-primary btn1" style="margin:1px;">View</button>
                    </td>
                </tr>

                <tr class="hide2">
                    <td>
                        <b>Session 2</b>
                    </td>
                    <td>
                        <a class="quiz" href='<?= $url ?>'>Quiz 2</a><br>
                    </td>

                    <td>
                        <p>Attempt 1</p>

                    </td>

                    <td>
                        <p>4 / 10</p>

                    </td>

                    <td>
                        <button type="button" class="btn btn-outline-primary btn1" style="margin:1px;">View</button> <br>

                    </td>
                </tr>



            </table>
        </form>
    </div>


    <script>
        var app = new Vue({
            el: "#app",
            data: {
                course: <?php print json_encode($course) ?>,
                coursename: <?php print json_encode($course[0]) ?>,
                username: <?php print json_encode($username) ?>
            }
        })
    </script>





    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>