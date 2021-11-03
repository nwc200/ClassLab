<?php
    require_once "objects/autoload.php";

    // if (isset($_SESSION["user"])) {
    //     $username = $_SESSION["user"];
    // } else {
    //     header("Location: before_home.html");
    // }

    $_SESSION["username"] = "Xi Hwee";
    $username = $_SESSION["username"];
    $courseid = $_GET["courseid"];

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
    <title>LMS - View Class</title>
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
                    <h2>View Class</h2> 
                    <hr>
                    <div class='row my-3'>
                        <div class='col-8'>
                            <h5><?= $coursename ?></h5>
                        </div>
                        <div class='col-auto'>
                            <b>Filter:</b>
                            <button type="button" id='all' class="btn btn-outline-info btn-sm active" onclick="filterSelection('all')">Show All</button>
                            <button type="button" id='notFull' class="btn btn-outline-info btn-sm" onclick="filterSelection('nf')">Not Full Class</button>
                        </div>
                    </div>

                    <?php
                        $classes = $dao->retrieveCourseClasses($courseid);

                    if (empty($classes)) {
                        echo "<div class='alert alert-danger my-4' role='alert'>
                            No Class Found. Click <a href=''>here</a> to create new class.
                        </div>";
                    } else {
                        echo "<ul id='myUL' style='list-style-type: none; padding: 0;'>";
                        $dao2 = new EnrollmentDAO();
                        foreach ($classes as $class) {
                            $classid = $class->getClassID();
                            $students = $dao2->retrieveEnrolment($courseid, $class->getClassID());
                            $remainingSlot = (int)$class->getClassSize()-$students;
                            $editEnrolmentPeriodHref = "EditEnrollmentPeriod.php?courseid=$courseid&classid=$classid";

                            echo "<li value='$remainingSlot'>
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
                                        {$class->getSelfEnrollmentStart()} ~ {$class->getSelfEnrollmentEnd()} <a class='btn btn-outline-primary btn-sm py-0' href='$editEnrolmentPeriodHref' role='button'>Edit</a><br>
                                    </div>
                                </div>
                                <div class='row mt-3'>
                                    <div class='col-sm-8'>
                                    </div>
                                    <div class='col-2'>";
                                
                            if ($students == 0) {
                                echo "<button type='button' class='btn btn-secondary' disabled>Withdraw</button>";
                            } else {
                                echo "<a class='btn btn-danger' href='WithdrawLearnerFromClass.php?courseid=$courseid&classid={$class->getClassID()}' role='button'>Withdraw</a>";
                            }
                            echo "</div>
                                <div class='col-2'>";

                            $assignEngineerHref = "AssignLearnerToClass.php?courseid=$courseid&classid={$class->getClassID()}";
                            if ($remainingSlot == 0) {
                                echo "<button type='button' class='btn btn-secondary' disabled>Assign</button>";
                            } else {
                                echo "<a class='btn btn-success' href='$assignEngineerHref' role='button'>Assign</a>";
                            }
                            echo "</div>
                                </div>
                            <hr>
                            </li>";
                        }
                        echo "</ul>";
                    }
                    ?>
                </div>
            </div>
        </div> 
    </main>

    <script>
        function filterSelection(selection) {
            li = document.getElementsByTagName("li");
            
            if (selection == "all") {
                btns = document.getElementsByClassName("active");
                btns[1].className = btns[1].className.replace(" active", "");

                btnAll = document.getElementById("all");
                btnAll.className += " active";

                for (i = 2; i < li.length; i++) {
                    li[i].style.display = "";
                }
            } else {
                btns = document.getElementsByClassName("active");
                btns[1].className = btns[1].className.replace(" active", "");

                btnNF = document.getElementById("notFull");
                btnNF.className += " active";

                for (i = 2; i < li.length; i++) {
                    remainingSlot = li[i].getAttribute("value");
                    if (remainingSlot == 0) {
                        li[i].style.display = "none";
                    } else {
                        li[i].style.display = "";
                    }
                }
            }
        }
    </script> 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>