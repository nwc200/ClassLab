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
    //echo("<script> console.log('testing: " . $courseid . "');</script>");
    $startdate = $_GET["date"];
    $dao = new CourseDAO();
    $course = $dao->retrieve($courseid);
    $coursename = $course->getCourseName();


    $today_date = date("Y-m-d H:i:s");

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
    <style>
    


    </style>
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h1>View Class</h1> 
                <p>Welcome <?= $username?></p>
                <hr>
            <div style="text-align:right">
            <form action="ViewClassByDates.php" method="post">
            <label for="date" >Start Date:</label>
            <input type="date" id="" name="date">
            <input type="submit" value="Submit">
            </form>


            </div>   
            
                <h5><?= $coursename ?></h5>
                
                
                
                
                <?php
                $classes = $dao->retrieveCourseClassesByDate($courseID, $startdate);
                if (empty($classes)) {
                    echo "<div class='alert alert-danger my-4' role='alert'>
                        No Class Found. Click <a href=''>here</a> to create new class.
                    </div>";
                } else {
                    $dao2 = new EnrollmentDAO();
                    foreach ($classes as $class) {
                        $classid = $class->getClassID();
                        $students = $dao2->retrievePendingEnrolment($courseid, $class->getClassID());
                        $status = $dao2->retrievePendingEnrolment($courseid, $classid);
                        $enrolment = $dao2->retrieveEnrolment($courseid, $classid);
                        $SelfEnrolStart =$class->getSelfEnrollmentStart();
                        $SelfEnrolEnd = $class->getSelfEnrollmentEnd();
                        //$remainingSlot = $dao2->checkClassCapacity($classid);
                        $remainingSlot = (int)$class->getClassSize()-$students;
                        $enrolPageHref = "enrolpage.php?courseid=$courseid&classid=$classid";
                        

                         if ( ($SelfEnrolStart >= $today_date) && ( $today_date <= $SelfEnrolEnd) ) 
                            {
                                //If within self-enrolment period
                                echo"
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
                                        {$SelfEnrolStart} ~ {$SelfEnrolEnd} <br>
                                        </div>
                                        </div>
                                        <div class='row mt-3'>
                                            <div class='col-sm-8'>
                                            </div>
                                            <div class='col-2'>";
                                            
                                            $withdrawPageHref = "withdrawpage.php?courseid=$courseid&classid={$class->getClassID()}";
                                            if ($students == 0) {
                                                echo "<button type='button' class='btn btn-secondary' disabled>Withdraw</button>";
                                            } else {
                                                echo "<a class='btn btn-success' href='$withdrawPageHref' role='button'>Withdraw</a>";
                                            }
                                            echo "</div>
                                                <div class='col-2'>";
                    
                                            $enrolPageHref = "enrolpage.php?courseid=$courseid&classid={$class->getClassID()}";
                                            if($remainingSlot == 0 || $students == 1 )
                                            {
                                                echo "<button type='button' class='btn btn-secondary' disabled>Enrol</button>";
                                            } else {
                                                echo "<a class='btn btn-success' href='$enrolPageHref' role='button'>Enrol</a>";
                                            }

                                           
                                            echo "</div>
                                                </div>
                                            <hr>";
                        }
                                  
                        else {
                         //echo" There is no available class ";
                        }
                    }
                }
               
                
                ?>    
                <br>
                <a class='btn btn-success text-align: right' href='ViewCourseByEligibility.php?classid=$classid' role='button'>back</a>
            </div>
        </div>
    </div> 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>