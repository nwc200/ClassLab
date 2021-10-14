<?php
    require_once "objects/autoload.php";
    $dao = new EnrollmentDAO;
  
    $_SESSION["username"] = "Xi Hwee";
    $username = $_SESSION["username"];

    $courseid = $_GET['courseid'];
    $classid = $_GET['classid'];
    $name = $username;
    


    
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
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h1>Confirm Withdrawal</h1> 
                <p>Welcome <?= $username?></p>
                <hr>

                <?php
                $enrolment = $dao->retrievePendingEnrolment($courseid, $classid);


                 if ($dao->withdrawSelfEnrol($name, $courseid, $classid) ) {

                    echo "<div class='alert alert-success' role='alert'>
                        Withdrawal Success! Your enrolment application had been withdrawn.
                    </div>";
                    
                    var_dump( $dao->retrievePendingEnrolment($courseid, $classid) );
                    

                } else {
                    echo "<div class='alert alert-danger' role='alert'>
                        Withdrawal failure! Please try again. 
                    </div>";
                }
               
                ?>
                <a class="btn btn-primary" href="ViewCourse.php" role="button">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>