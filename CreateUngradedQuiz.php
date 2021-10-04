<?php
    require_once "objects/autoload.php";
    $username = $_SESSION['username'];
    $quiznum = $_GET['quiznum'];
    $sectionnum = $_GET['sectionnum'];
    $classid =$_GET['classid'];
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
    <title>Ungraded Quiz Creation</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-sm-12 text-center">
                <h1>Quiz Creation</h1>
            </div>
            <div class="col-sm-6 text-center mt-4">
                <form action="ProcessAddQuiz.php" method="POST">
                    <!--Change to graded for Graded quiz-->
                    <input type="hidden" name="classid" value=<?php echo $classid?>>
                    <input type="hidden" name="sectionnum" value=<?php echo $sectionnum?>>
                    <input type="hidden" name="quiznum" value=<?php echo $quiznum?>>
                    <input type="hidden" name="quiztype" value="Ungraded">
                    <input type="hidden" name="quizpassingmark" value=0>
                    <div class="form-group">
                        <h4>Quiz Title<h4>
                        <input type="text" class="form-control" name="quiztitle" placeholder="Enter Quiz Title">
                    </div>
                    <div class="form-group">
                        <h4>Quiz Duration<h4>
                        <input type="number" class="form-control" name="quizduration" placeholder="Enter Duration in Minutes">
                    </div>
                    
                    <div class="form-group" v-for="n in divs">
                        <h4>Question {{n}}</h4>
                        <input type="text" class="form-control" v-bind:name="'setquestion'+n" placeholder="Set question">
                        <div class="form-check">
                            <label for="QuestionType"> Question Type</label><br>
                            
                            <input class="form-check-input" type="radio" v-bind:name="'questiontype'+n" value="MCQ" v-model="picked[n]">
                            <label class="form-check-label" for="MCQ">
                                MCQ
                            </label>
                            <br>
                            <input class="form-check-input" type="radio" v-bind:name="'questiontype'+n" value="TF" v-model="picked[n]">
                            <label class="form-check-label" for="TF">
                                True/False
                            </label>
                            <br><br>
                            <div v-if="picked[n] =='MCQ' || picked[n]=='TF'">
                                <h4>Answers</h4>
                                Select the correct answer <br><br>
                                <div v-if="picked[n] =='MCQ'" v-for="y in mcqnum[n-1].length">
                                    Answer {{y}}
                                    <input type="text" class="form-control" v-bind:name="'answer'+n+'ansnum'+y" placeholder="Set question answer">
                                    <input class="form-check-input" type="radio" v-bind:name="'questionMCQ'+n" v-bind:value="'answer'+n+'ansnum'+y">
                                    <label class="form-check-label">
                                        Correct Answer
                                    </label>
                                    <br><br>
                                </div>
                                <br>
                                <div class="btn-group" role="group">
                                    <button v-if="picked[n] =='MCQ'" type="button" v-on:click="mcqnum[n-1].push(1);" class="btn btn-sm btn-danger">
                                        Add Answer
                                    </button>
                                    <button v-if="picked[n] =='MCQ' && mcqnum[n-1].length>1" type="button" v-on:click="mcqnum[n-1].pop(1)" class="btn btn-sm btn-danger">
                                        Remove Answer
                                    </button>
                                </div>
                                <div v-if="picked[n] =='TF'">
                                    <input class="form-check-input" type="radio" v-bind:name="'questionTF'+n" value="True">
                                    <label class="form-check-label">
                                        True
                                    </label><br>
                                    <input class="form-check-input" type="radio" v-bind:name="'questionTF'+n" value="False">
                                    <label class="form-check-label">
                                        False
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                    <div class="btn-group" role="group">
                        <button type="button" v-on:click="divs+=1;mcqnum.push([1])" class="btn btn-info">
                            Add Question
                        </button>
                        <button v-if="divs >1" type="button" v-on:click="divs-=1;" class="btn btn-info">
                            Remove Question
                        </button>
                    </div>
                    <br><br><br>
                    <button name='submit' type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>    
    <script>
        var app = new Vue({
            el: "#app",
            data:{
                divs: 1,
                picked:[],
                mcqnum: [[0]]
            },

        })
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>