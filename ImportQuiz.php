<?php
    require_once "objects/autoload.php";
    $username = $_SESSION['username'];
    $sectionnum= $_GET["sectionnum"];
    $classid= $_GET["classid"];
    $quiznum= $_GET["quiznum"];
    $courseid= $_GET["courseid"];
    $dao = new QuizDAO();
    $course = $dao->getTrainerCourse($username);
    $class1 = $dao->getCourseQuiz($courseid);
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
    <title>Import Quiz</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <h1>Import Quiz for Course <?php echo $courseid?></h1>
                <hr>
                <div v-for="classes in class1">
                    <div v-for="section in classes[9]" v-if="classes[0] != classid">
                        <div v-for="quiz in section[3]">
                            <h4>QuizID: {{quiz[0]}}</h4>
                            <h4>Quiz Name: {{quiz[1]}}</h4>
                            Quiz Duration: {{quiz[3]}} mins<br>
                            Trainer: {{classes[2]}} <br>
                            Class: {{classes[0]}} <br>
                            Section: {{section[0]}}<br>
                            Section Name: {{section[1]}} <br>
                            <a class="btn btn-primary" v-bind:href="'ProcessImportQuiz.php?quizid=' + quiz[0] + '&classid=' + classid + '&sectionnum=' + sectionnum" role="button">Import Quiz</a>
                            <hr>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: "#app",
            data:{
                class1: <?php print json_encode($class1)?>,
                classid: <?php print json_encode($classid)?>,
                sectionnum: <?php print json_encode($sectionnum)?>
            }
        })
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>