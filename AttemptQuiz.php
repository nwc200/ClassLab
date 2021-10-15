<?php
    require_once "objects/autoload.php";
    $username = $_SESSION['username'];
    $quizid = $_GET['quizid'];
    $classid = $_GET['classid'];
    $zero = $_GET['whichCourse'];
    
    $dao = new QuizDAO();
    $quiz = $dao->getTrainerCourse("Wei Cheng");
    $quiz = $dao->getQuiz($quizid);
    $quizduration = $quiz->getQuizDuration();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Quiz</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
<body>
    
    <div class="timer fixed-top navbar navbar-light bg-light" onload="timer(1800)">
        <div class="time navbar-brand">
            <strong>Time left: <span id="time">Loading...</span></strong>
        </div>
    </div>
    <br>
    <br>
    <br>
    <div class="container" id="app">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-center"> 
                    <h1>Quiz Title: {{quiz[1]}}</h1>
                    <h4>Type: {{quiz[4]}}</h4>
                    <h4>Passing Mark: {{quiz[5]}}</h4>
                    <hr>
                </div>
                <br><br><br>
                <form action="ProcessAttemptQuiz.php" method="POST">
                    <input type="hidden" name="username" value="<?php echo $username;?>">
                    <input type="hidden" name="classid" value="<?php echo $classid;?>">
                    <input type="hidden" name="whichCourse" value="<?php echo $zero;?>">

                    <input type="hidden" name="quizid" v-bind:value="quiz[0]">
                    <input type="hidden" name="passingmark" v-bind:value="quiz[5]">
                    <input type="hidden" name="type" v-bind:value="quiz[4]">
                    <div v-for="question in quiz[6]"> 
                        <b>Question {{question[0]}}: {{question[1]}}</b> [Marks: {{question[3]}}] 
                        <div v-for="answer in question[4]">
                            <div class="row">
                                <div class="col-sm-9">
                                    {{answer[0]}} {{answer[1]}}
                                </div>
                                <div class="col-sm-3">
                                    <input class="text-right" type="radio" v-bind:name="question[0]+'mark'+question[3]" v-bind:value="answer[0]+'corr'+answer[2]">
                                </div>
                            </div>
                        </div>
                        <br><br>
                    </div>
                    <br><br>
                    <button id='submit' name='submit' type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        var app = new Vue({
            el: "#app",
            data:{
                quiz: <?php print json_encode($quiz)?>
            }
        })
        
    </script>
    
    <script>
        var duration = <?php echo json_encode($quizduration) ?>;
        var time = duration * 60;
        setInterval(function() {
        var seconds = time % 60;
        var minutes = (time - seconds) / 60;
        if (seconds.toString().length == 1) {
            seconds = "0" + seconds;
        }
        if (minutes.toString().length == 1) {
            minutes = "0" + minutes;
        }
        document.getElementById("time").innerHTML = minutes + ":" + seconds;
        time--;
        if (time == 0) {
            document.getElementById("submit").click();
            document.getElementById('submit').trigger('click');
        }
        }, 1000);
    </script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>
