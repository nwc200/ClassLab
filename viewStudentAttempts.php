<?php
require_once "objects/autoload.php";
$username = $_SESSION['username'];
$quizid = $_GET['quizid'];
$dao = new QuizDAO();
$quiz = $dao->getTrainerCourse("Wei Cheng");
$quiz = $dao->getQuiz($quizid);
$zero = $_GET['whichCourse'];
$attemptNo = $_GET['attemptNo'];

$getQuizQuestion = $quiz->getQuizQuestion();


$getStudentQuizRecord = $dao->getStudentQuizAttempt($quizid, $username, $attemptNo);

$counter = 0;
$question = $quiz->getQuizQuestion();
$array_correct = [];
$total = count($getStudentQuizRecord);


foreach ($question as $qns) {
    $ans = $qns->getQuizAnswer();
    foreach ($ans as $correct) {
        if ($correct->getAnswerCorrect() == 1) {
            $correctAns = $correct->getAnswerNum();
            array_push($array_correct, $correctAns);
        }
    }
}

for ($i = 0; $i < $total; $i++) {
    if ($array_correct[$i] == $getStudentQuizRecord[$i]) {
        $counter++;
    }
}


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
                <div class="text-center" v-bind:style="{margin:'15px'}">
                    <h1>Quiz Title: {{quiz[1]}}</h1>
                    <h4>Type: {{quiz[4]}}</h4>
                    <h4>Passing Mark: {{quiz[5]}}</h4>
                    <hr>
                    <h6 class="text-right"> Attempt No. {{attemptNo}}</h6>

                </div>
                <br><br><br>
                <form>
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <input type="hidden" name="quizid" v-bind:value="quiz[0]">
                    <input type="hidden" name="passingmark" v-bind:value="quiz[5]">
                    <div v-for="i in quiz[6].length">
                        <b>Question {{quiz[6][i-1][0]}}: {{quiz[6][i-1][1]}}</b> [Marks: {{quiz[6][i-1][3]}}]
                        <div v-for="answer in quiz[6][i-1][4]">
                            <div class="row">
                                <div class="col-sm-9">
                                    {{answer[0]}}. {{answer[1]}}
                                </div>
                            </div>
                        </div>
                        <br>
                        <div v-for="answer in quiz[6][i-1][4]">
                            <div v-if="answer[0] == getStudentQuizRecord[i-1]">
                                <b v-bind:style="{color:'grey'}">Attempt Answer: </b>{{getStudentQuizRecord[i-1]}}. {{answer[1]}}
                            </div>
                        </div>
                        <div v-for="answer in quiz[6][i-1][4]">
                            <div v-if="answer[2] == 1">
                                <b v-bind:style="{color:'blue'}">Correct Answer: </b>{{answer[0]}}. {{answer[1]}}
                            </div>
                        </div>

                        <hr>
                    </div>
                    <br>
                    <h6 class="text-right"> No. of Correct Answered Question: {{counter}}/{{total}}</h6>
                    
                </form>
                <a :href="'ViewQuizMaterials.php?whichCourse='+zero" class="btn btn-primary btn-lg btn-block" v-bind:style="{margin:'40px'}">Close</a>
            </div>
        </div>
    </div>


    <script>
        var app = new Vue({
            el: "#app",
            data: {
                quiz: <?php print json_encode($quiz) ?>,
                zero: <?php print json_encode($zero) ?>,
                counter: <?php print json_encode($counter) ?>,
                total: <?php print json_encode($total) ?>,
                attemptNo: <?php print json_encode($attemptNo) ?>,
                getStudentQuizRecord: <?php print json_encode($getStudentQuizRecord) ?>,

            }
        })
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>