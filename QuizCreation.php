<?php
require_once "objects/autoload.php";
if (isset($_GET['user'])) {
    if (!isset($GET['user'])) {
        $_SESSION['username'] = $_GET['user'];
    } else {
        $_SESSION['username'] = "Wei Cheng";
    }
}
$userDao = new UserDAO();
$trainer = $userDao->getTrainer();
$username = $_SESSION['username'];
$dao = new QuizDAO();
$course = $dao->getTrainerCourse($username);
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
    <title>Quiz Creation Home</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h1>Quiz Creation</h1> 
                <p>Welcome {{username}}</p>
                <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Change User
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" v-bind:href="'QuizCreation.php?user=' + user" v-for="(user, index) in trainer">{{trainer[index]}}</a>
                </div>
                </div>
                <hr>
                
                <div v-for="value in course">
                    <div class="col-sm-9">
                        CourseID: {{value[0]}} <br>
                        CourseName: {{value[1]}} <br>    
                        CourseDescription: {{value[2]}} <br>    
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-success" v-bind:href="'CreateQuizHome.php/?courseid='+value[0]" role="button">View Quiz</a>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div> 

    <script>
        var app = new Vue({
            el: "#app",
            data:{
                course: <?php print json_encode($course)?>,
                username: <?php print json_encode($username)?>,
                trainer: <?php print json_encode($trainer)?>
                
            },
        })
        
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>