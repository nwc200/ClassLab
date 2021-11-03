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
    <title>LMS - Withdraw Engineer</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="adminHomePage.php">Learning Management System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="ViewCourse.php" active>Assign Engineer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ViewEnrollment.php">View Self-Enrollment </a>
                    </li>
                </ul>
                Welcome, <?=$username?>
            </div>
        </nav>
    </header>

    <main style="margin-top: 10px;">
        <div class="container" id="app">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Withdraw Engineer</h2> 
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
                    $names = $dao2->retrieveEnrolledEngineers($courseid, $classid);
                    if (empty($names)) {
                        echo "<div class='alert alert-warning' role='alert'>
                            There is no engineer to withdraw! Click <a href='ViewCourse.php'>here</a> to go back. 
                        </div>";
                    } else {
                        echo "<div class='mb-3 row'>
                                <label for='searchLearner' class='col-sm-1 col-form-label'><b>Engineer</b></label>
                                <div class='col-sm-4'>
                                    <input type='text' class='form-control' id='searchLearner' size='30' placeholder='Search name here' onkeyup='search()'>
                                </div>
                            </div>

                            <form action='ProcessWithdrawLearner.php' method='POST'>
                                <table class='table table-striped' id='myTable'>
                                    <thead>
                                        <tr>
                                            <th class='col-10'>Name</th>
                                            <th>Withdraw</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                        foreach ($names as $name) {
                            echo "<tr value='$name'>
                                    <td class='col-10'>$name</th>
                                    <td>
                                        <button type='submit' class='btn btn-danger' name='submit' value='$name'>Withdraw</button>
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
    </main>

    <script>
        function search() {
            input = document.getElementById("searchLearner");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tableRows = document.getElementsByTagName("tr");
            for (i = 1; i < tableRows.length; i++) {
                name = tableRows[i].getAttribute("value");
                if (name.toUpperCase().indexOf(filter) > -1) {
                    tableRows[i].style.display = "";
                } else {
                    tableRows[i].style.display = "none";
                }
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>