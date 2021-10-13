<?php

require_once "objects/autoload.php";

$_SESSION['username'] = "Yu Hao";
$username = $_SESSION['username'];
$status = 'Approved';

$enrolDAO = new SectionDAO();
$enrolments = $enrolDAO->retrieveUserApprovedEnrolment($username, $status); //return user approved enrolments
$userCourses = (object)[];
$counter = 0;
$zero = 0;

foreach ($enrolments as $enrol) {
    $courseID = $enrol->getCourseID();
    $courses = $enrolDAO->retrieveCourses($courseID); //return user enrolled courses
    $courseName = $courses->getCourseName();
    $classID = $enrolDAO->getLearnerClassID($courseID, $username); //get class id
    $sectionIDs = $enrolDAO->retrieveClassSection($classID); //get section ids

    $arrayMaterials = [];
    $materialNumArr = [];
    $getMaterialsNum = [];
    $getCompletedArr = [];
    $quizAttempts = [];
    foreach ($sectionIDs as $sec) { //$sec is individual section num
        $materials = $enrolDAO->retrieveClassSectionMaterials($classID, $sec); // get section materials -- section 1, 2 materials
        array_push($arrayMaterials, $materials);
        $material = [];
        $completed = [];
        for ($i = 0; $i < count($materials); $i++) {
            $materialNum = $materials[$i]->getMaterialNum(); //get materials id by section num
            array_push($material, $materialNum);

            $getCompleted = $enrolDAO->retrieveSectionMaterialsProgress($username, $classID, $sec, $materialNum); //get completed
            array_push($completed, $getCompleted);
        }
        $attempts = $enrolDAO->studentQuizAttemptRetrieve($username, $sec);
        array_push($getMaterialsNum, $material);
        array_push($getCompletedArr, $completed);
        array_push($quizAttempts, $attempts);
    }


    $userCourses->$counter = [$courseID, $courseName, $classID, $sectionIDs, $getCompletedArr, $quizAttempts];

    $counter++;
}
// var_dump($userCourses);
// var_dump($quizAttempts);

$firstpage = $userCourses->$zero;
$firstpageNoOfSec = count($firstpage[3]);
$totalPercentage = 0;
$percentage = $totalPercentage . "%";
$quiz = [];
$getQuizAttempts = $firstpage[5];

for ($j = 0; $j < $firstpageNoOfSec; $j++) {
    $count = count($firstpage[4][$j]) - 1;
    if (count($firstpage[4][$j]) != 0 && ($firstpage[4][$j][$count]) == 1) {
        $quizlink = 'https://www.youtube.com/watch?v=AndFYq7u7-M';
        array_push($quiz, $quizlink);
        for ($c = 0; $c < count($firstpage[5][$j]); $c++) {
            if ($firstpage[5][$j][$c][2] == 1) {
                $totalPercentage = number_format(($j + 1 / ($firstpageNoOfSec) * 100), 2, '.', '');
                $percentage = $totalPercentage . '%';
                break;
            }
        }
    } else {
        array_push($quiz, "");
    }
}



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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>View Course Materials</title>
</head>

<body>
    <br>
    <div class="" id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="ViewCourseMaterials.php">LMS Self Enrollment System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="ViewCourseMaterials.php" active>Course Materials <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ViewQuizMaterials.php">Quizzes Available </a>
                    </li>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Courses Enrolled
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <div v-for="(each, i) in usercourses">
                                <a class="dropdown-item" :value="i" @click='test([i])'> {{usercourses[i][1]}} - Class {{usercourses[i][2]}}</a>
                            </div>
                        </div>
                    </div>
                </ul>
                Learner: {{username}}

            </div>
        </nav>
        <br>

        <h4 style="text-align:center" v-if="coursename !='' ">
            {{coursename}}
        </h4>
        <h4 style="text-align:center" v-else>
            {{usercourses[0][1]}}
        </h4>

        <div class="container">
            <br>
            <div>
                <p style="margin:2px;"><b>Quiz Progress</b></p>
                <div class="col progress">
                    <div class="progress-bar progress-bar-striped" role="progressbar" v-bind:style="{width: percentage}" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                        {{percentage}}
                    </div>
                </div>
                <h6 style="text-align:center;margin:5px">
                    {{percentage}} of Quizzes Completed
                </h6>
            </div>
        </div>

        <div class='container-fluid' style="margin:50px; padding: 20px">
            <table class="table">
                <thead>
                    <tr>
                        <td>Section</td>
                        <td>Quiz</td>
                        <td>Attempts</td>
                        <td>Pass/Failed</td>
                        <td>View Attempt</td>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(each, i) in getNoOfSections">
                        <td>
                            <b>Section {{i+1}}</b>
                        </td>

                        <td>
                            <div v-if="quiz[i] != ''">
                                <div v-if="quiz[i] != ''">

                                    <p v-if="quizAttempts[i].length !=0">
                                        <a class="quiz" :href="'AttemptQuiz.php?quizid='+ parseInt(i+1)"> Quiz {{i+1}}</a><br>
                                    </p>
                                    <p v-else>

                                    </p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div v-if="quiz[i] != ''">
                                <div v-for="(each, j) in quizAttempts">
                                    <p v-if="quizAttempts[i].length !=0">
                                        Attempt {{quizAttempts[i][j][1]}}
                                    </p>
                                    <p v-else>

                                    </p>
                                </div>
                            </div>
                            <div v-else>

                            </div>
                        </td>

                        <td>
                            <div v-if="quiz[i] != ''">
                                <div v-for="(each, j) in quizAttempts">
                                    <div v-if="quizAttempts[i].length !=0">
                                        <p v-if="quizAttempts[i][j][2] == 1" v-bind:style="{color:'green'}">
                                            <b>Pass</b>
                                        </p>
                                        <p v-else v-bind:style="{color:'red'}">
                                            Failed
                                        </p>
                                    </div>
                                </div>

                            </div>
                            <div v-else>

                            </div>
                        </td>

                        <td>
                            <div v-if="quiz[i] != ''">
                                <div v-for="(each, j) in quizAttempts">
                                    <p v-if="quizAttempts[i].length !=0">
                                        <button type="button" class="btn btn-outline-primary btn1" style="margin:1px;">View Attempt {{j+1}} </button>
                                    </p>
                                    <p v-else>

                                    </p>

                                </div>
                            </div>
                            <div v-else>

                            </div>
                        </td>

                    </tr>

                </tbody>

            </table>
            <!-- </form> -->
        </div>
    </div>


    <script>
        var app = new Vue({
            el: "#app",
            data: {
                username: <?php print json_encode($username) ?>,
                usercourses: <?php print json_encode($userCourses) ?>,
                firstpage: <?php print json_encode($firstpage) ?>,
                coursename: '',
                getCurrentCourse: '',
                getNoOfSections: <?php print json_encode($firstpage[3]) ?>,
                getNoOfMaterials: '',
                quiz: <?php print json_encode($quiz) ?>,
                totalPercentage: <?php print json_encode($totalPercentage) ?>,
                percentage: <?php print json_encode($percentage) ?>,
                quizAttempts: <?php print json_encode($getQuizAttempts) ?>,

            },
            methods: {
                test: function(i) {
                    this.coursename = this.usercourses[i][1]
                    this.getCurrentCourse = this.usercourses[i]
                    this.getNoOfSections = this.usercourses[i][3].length //
                    this.getNoOfMaterials = this.getCurrentCourse[4] // 
                    this.quiz = []
                    this.totalPercentage = 0
                    this.percentage = this.totalPercentage + "%"
                    this.quizAttempts = this.getCurrentCourse[5]


                    for (j = 0; j < this.getNoOfSections; j++) {
                        if (this.getNoOfMaterials[j] != 0 && this.getNoOfMaterials[j][this.getNoOfMaterials[j].length - 1] == 1) { //materials not 0 & completed all materials
                            this.quizlink = 'https://www.youtube.com/watch?v=AndFYq7u7-M'
                            this.quiz.push(this.quizlink)
                            for (c = 0; c < this.quizAttempts[j].length; c++) {
                                if (this.quizAttempts[j][c][2] == 1) {
                                    this.totalPercentage = parseFloat(((j + 1) / this.getNoOfSections) * 100).toFixed(2)
                                    this.percentage = this.totalPercentage + '%'
                                    break
                                }
                            }
                        } else {
                            this.quiz.push('')
                            // this.sec.push(j)
                        }
                    }
                    // console.log(this.percentage)
                }

            }

        })
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>