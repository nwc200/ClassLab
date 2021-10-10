<?php
    require_once "objects/autoload.php";

    // if (isset($_SESSION["user"])) {
    //     $username = $_SESSION["user"];
    // } else {
    //     header("Location: before_home.html");
    // }
    $_SESSION["username"] = "Xi Hwee";
    $username = $_SESSION["username"];

    $dao = new CourseDAO();
    $courses = $dao->retrieveAll();
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
    <title>LMS - View Course</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h1>View Course</h1> 
                <p>Welcome <?= $username?></p>
                <hr>
                
                <div class="mb-3 row">
                    <label for="searchCourse" class="col-sm-1 col-form-label"><b>Course</b></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="searchCourse" size="30" placeholder="Search course here">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3">Search</button>
                    </div>
                    <div class="col-auto">
                        <a href="ViewCourseByEligibility.php" button type="filter" class="btn btn-secondary mb-3">Filter</a>
                    </div>
                </div>

                <?php
                foreach ($courses as $course) {
                    $courseid = $course->getCourseID();
                    $coursename = $course->getCourseName();
                    $coursedes = $course->getCourseDescription();
                    $prerequisiteIDs = $dao->setPrerequisite($courseid, $course);
                    echo "<div class='row'>
                            <div class='col-sm-9' >
                                CourseID: $courseid <br>
                                Course Name: $coursename <br>    
                                Course Description: $coursedes <br>
                                Course Prerequisite: ";
                    
                    $courseprereqs = $course->getCoursePrereq();

                    if (empty($courseprereqs)) {
                        echo "-";
                    } else {
                        foreach ($prerequisiteIDs as $id) {
                            $prereq = $dao->retrieve($id);
                            $name = $prereq->getCourseName();
                            $htmlcode = "$name,";
                        }
                        $htmlcode = rtrim($htmlcode, ",");
                        echo "$htmlcode";
                    }

                    $nextPageHref = "ViewClass.php?courseid=$courseid";

                    echo "<br>
                    </div>
                        <div class='col-auto mt-4'>
                            <a class='btn btn-success' href='$nextPageHref' role='button'>View Class</a>
                        </div>
                    </div>
                    <hr>";
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