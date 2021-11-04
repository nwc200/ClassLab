<?php
    require_once "objects/autoload.php";

    // if (isset($_SESSION["user"])) {
    //     $username = $_SESSION["user"];
    // } else {
    //     header("Location: before_home.html");
    // }
    $_SESSION["username"] = "Xi Hwee";
    $username = $_SESSION["username"];
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
    <title>LMS - Home Page</title>
    
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
                    <li class="nav-item">
                        <a class="nav-link" href="ViewEnrollment.php">View Self-Enrollment </a>
                    </li>
                </ul>
                Welcome, <?=$username?>
            </div>
        </nav>
    </header>

    <main>
        <div class="container-fluid text-center my-5" id="select">
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-3 col-sm-6 mx-5">
                    <div class="card card-cascade wider" style="margin-top:30px">

                        <!-- Card image -->
                        <div class="view view-cascade overlay">
                            <img class="card-img-top" src="img/assignClass.jpg" height="180">
                        </div>

                        <!-- Card content -->
                        <div class="card-body card-body-cascade" style="height: 150px;">
                            <!-- Title -->
                            <h4 class="card-title">Assign Engineers</h4>
                            <!-- Subtitle -->
                            <p class="card-text">Click here to assign engineers to class</p>
                        </div>

                        <!-- Button -->
                        <div class="card-footer" style="background-color: white;">
                            <a class="btn btn-outline-info" style="margin-top: 5px;" href="ViewCourse.php" role="button">Go &raquo;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 mx-4">
                    <div class="card card-cascade wider" style="margin-top:30px">

                        <!-- Card image -->
                        <div class="view view-cascade overlay">
                            <img class="card-img-top" src="img/viewSelfEnrollment.png" >
                        </div>

                        <!-- Card content -->
                        <div class="card-body card-body-cascade text-center" style="height: 150px;">

                            <!-- Title -->
                            <h4 class="card-title">View Self-Enrollment</h4>
                            <!-- Subtitle -->
                            <p class="card-text pb-2">Click here to approve or reject self-enrollment record</p>
                        </div>
                        
                        <!-- Button -->
                        <div class="card-footer" style="background-color: white;">
                            <a class="btn btn-outline-info" style="margin-top: 5px;" href="ViewEnrollment.php" role="button">Go &raquo;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>