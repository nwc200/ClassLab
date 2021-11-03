<?php
    require_once "objects/autoload.php";

    // if (isset($_SESSION["user"])) {
    //     $username = $_SESSION["user"];
    // } else {
    //     header("Location: before_home.html");
    // }
    $_SESSION["username"] = "Neo Yu Hao";
    $username = $_SESSION["username"];

    $dao = new CourseDAO();
    $courses = $dao->retrieveAll();
    $courseEligible= $dao->getEligibleCourseID($username);
    

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
    <title>LMS - View Course</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="adminHomePage.php">Learning Management System</a>
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
                    <h2>View Course</h2>

                    <hr>

                    <div class="mb-3 row">
                        <label for="searchCourse" class="col-sm-1 col-form-label"><b>Course</b></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="searchCourse" size="30"
                                placeholder="Search course here" onkeyup="search()">
                        </div>
                    </div>

                    <?php
                    echo "<ul id='myUL' style='list-style-type: none; padding: 0;'>";
                    foreach ($courses as $course) {
                        $courseid = $course->getCourseID();
                        $coursename = $course->getCourseName();
                        $coursedes = $course->getCourseDescription();
                        $prerequisiteIDs = $dao->setPrerequisite($courseid, $course);
                        echo "<li value='$coursename'>
                                <div class='row'>
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

                        $nextPageHref = "ViewClassByEligibility.php?courseid=$courseid";
                         //echo("<script> console.log('testing: " . $courseid . "');</script>");
                        if (!in_array($courseid, $courseEligible) ) {
                            echo "<br> 
                            </div>
                                <div class='col-auto mt-4'>
                                    <a class='btn btn-success' href='$nextPageHref' role='button'>View Course</a>
                                </div>
                            </div>
                            <hr>";
                        } else {

                            echo" <br> 
                            </div>
                                <div class='col-auto mt-4'>
                                 <a class='btn btn-secondary disabled'> View Course</a>
                                </div>
                            </div>
                            <hr>
                            </li>";
                        }
                    }
                    echo "</ul>";

                    ?>

                </div>
            </div>
        </div>
    </main>
    <script>
        function search() {
            input = document.getElementById("searchCourse");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = document.getElementsByTagName("li");
            for (i = 2; i < li.length; i++) {
                courseName = li[i].getAttribute("value");
                if (courseName.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script> 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>