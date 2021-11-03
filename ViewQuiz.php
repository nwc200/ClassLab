<?php
    require_once "objects/autoload.php";
    $username = $_SESSION['username'];
    $quizid = $_GET['quizid'];
    
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
                <span class="text-primary">**Blue answers are correct</span>
                <br><br>
                <div v-for="question in quiz[6]"> 
                    <b>Question {{question[0]}}: {{question[1]}}</b> [Marks: {{question[3]}}] 
                    <div v-for="answer in question[4]">
                        <div class="row">
                            <div class="col-sm-9" v-if="answer[2] ==0">
                                {{answer[0]}} {{answer[1]}}
                            </div>
                            <div class="col-sm-9" v-else="answer[2] ==0">
                                <span class="text-primary">{{answer[0]}} {{answer[1]}}</span>
                            </div>
                        </div>
                    </div>
                    <br><br>
                </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>