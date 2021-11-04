<?php
    require_once "objects/autoload.php";

    // if (isset($_SESSION["user"])) {
    //     $username = $_SESSION["user"];
    // } else {
    //     header("Location: before_home.html");
    // }
    $_SESSION["username"] = "Xi Hwee";
    $username = $_SESSION["username"];

    $dao = new EnrollmentDAO();
    $enrollments = $dao->retrievePendingEnrolments();
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
    <title>LMS - View Enrollment</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="ViewCourse.php">Assign Engineer</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="ViewEnrollment.php" active>View Self-Enrollment </a>
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
                    <h2>View Self-Enrollment</h2> 
                    <hr>
                    
                    <?php
                    if (empty($enrollments)) {
                        echo "<div class='alert alert-warning' role='alert'>
                            There is no pending enrollment record! Click <a href='adminHomePage.php'>here</a> to go back. 
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

                            <form action='ProcessSelfEnrollment.php' method='POST'>
                                <table class='table table-striped'>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Course</th>
                                            <th>Class Remaining Slot</th>
                                            <th>Enrollment Period</th>
                                            <th>Class Start Date</th>
                                            <th>Approve/ Reject</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                        foreach ($enrollments as $enrolment) {
                            $user = $enrolment->getUser();
                            $course = $enrolment->getCourse();
                            $classes = $course->getClass1();
                            $class = $classes[0];
                            $learners = $dao->retrieveEnrolment($course->getCourseID(), $class->getClassID());
                            $remainingSlot = (int)$class->getClassSize() - $learners;

                            echo "<tr>
                                    <td>{$user->getUserName()}</th>
                                    <td class='col-3'>{$course->getCourseName()}</td>
                                    <td>$remainingSlot</td>
                                    <td>{$class->getSelfEnrollmentStart()} to {$class->getSelfEnrollmentEnd()}</td>
                                    <td>{$class->getStartDate()}, {$class->getStartTime()}</td>";
                            
                            date_default_timezone_set( 'Asia/Singapore');
                            $today = date("Y-m-d");
                            if ($today < $class->getStartDate()) {
                                echo "<td>
                                        <button type='submit' class='btn btn-primary px-2' name='approve' value='{$enrolment->getEnrollmentID()}'>Approve</button>
                                        <button type='submit' class='btn btn-danger px-2' name='reject' value='{$enrolment->getEnrollmentID()}'>Reject</button>
                                    </td>
                                </tr>";
                            } else {
                                echo "<td>
                                        <button type='submit' class='btn btn-primary px-2' name='approve' value='{$enrolment->getEnrollmentID()}' disabled>Approve</button>
                                        <button type='submit' class='btn btn-danger px-2' name='reject' value='{$enrolment->getEnrollmentID()}'>Reject</button>
                                    </td>
                                </tr>";
                            }
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>