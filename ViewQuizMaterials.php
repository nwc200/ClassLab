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


$quizDAO = new QuizDAO();

if (isset($_GET['whichCourse'])) {
    $zero = $_GET['whichCourse'];
}


foreach ($enrolments as $enrol) {
    $courseID = $enrol->getCourseID();
    $courses = $enrolDAO->retrieveCourses($courseID); //return user enrolled courses
    $courseName = $courses->getCourseName();
    $classID = $enrolDAO->getLearnerClassID($courseID, $username); //get class id
    $sectionIDs = $enrolDAO->retrieveClassSection($classID); //get section ids

    $getQuizzes = $quizDAO->retrieveClassQuiz($classID);  //get quizes for that classid 

    $quizAttempts = [];
    foreach ($getQuizzes as $getQuiz) {
        $quizid = $getQuiz[0];
        $attempts = $enrolDAO->studentQuizAttemptRetrieve($username, $quizid);
        array_push($quizAttempts, $attempts);
    }

    $getCompletedArr = [];
    $arrayMaterials = [];

    foreach ($sectionIDs as $sec) { //$sec is individual section num
        $getCompleted = $enrolDAO->retrieveSectionMaterialsProgress($username, $classID, $sec, 1); //get completed
        array_push($getCompletedArr, $getCompleted);

        $materials = $enrolDAO->retrieveClassSectionMaterials($classID, $sec); // get section materials -- section 1, 2 materials
        array_push($arrayMaterials, $materials);
    }
    $userCourses->$counter = [$courseID, $courseName, $classID, $sectionIDs, $getCompletedArr, $quizAttempts, $arrayMaterials, $getQuizzes];

    $counter++;
}
// var_dump($userCourses);

$firstpage = $userCourses->$zero;
$firstpageNoOfSec = count($firstpage[3]);
$totalPercentage = 0;
$percentage = $totalPercentage . "%";
$quiz = [];
$getQuizAttempts = $firstpage[5];
$getMaterials = $firstpage[6];
$isCompleted = $firstpage[4];
$quizInformation = $firstpage[7];
$getClassID = $firstpage[2];

if (count($quizInformation) != 0) {
    for ($j = 0; $j < count($quizInformation); $j++) {
        if (count($getQuizAttempts[$j]) != 0) {
            for ($k = 0; $k < count($getQuizAttempts[$j]); $k++) {
                if ($getQuizAttempts[$j][$k][2] == 1) {
                    $totalPercentage = number_format(($getQuizAttempts[$j][$k][1] / $firstpageNoOfSec) * 100, 2, '.', '');
                    $percentage = $totalPercentage . "%";
                    break;
                }
            }
        }
    }
}
// var_dump($firstpage);

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
    <title>View Quiz Available</title>
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
          <li class="nav-item">
            <a class="nav-link" href="ViewCourseMaterials.php" active>Course Materials <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item active">
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
            {{firstpage[1]}}
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
                        <td>Pass/Fail</td>
                        <td>View Attempt</td>

                    </tr>
                </thead>

                <tbody>
                    <!-- if there is no quiz1 for that course -->
                    <tr v-if="quizInformation.length == 0">

                    </tr>

                    <!-- if there is a quiz list for that course -->
                    <tr v-for="(each, i) in isCompleted" v-else>
                        <td v-if="isCompleted[i] == 1">
                            <b>Section {{i+1}} </b>

                        </td>

                        <td v-if="isCompleted[i] == 1">
                            <div v-for="(each, j) in quizInformation">
                                <p v-if="j == i">
                                    <a :href="'AttemptQuiz.php?classid='+classID+'&quizid='+ parseInt(quizInformation[i][0])+'&whichCourse='+zero">
                                        {{quizInformation[i][3]}}
                                    </a>
                                </p>
                            </div>
                        </td>

                        <td v-if="isCompleted[i] == 1">
                            <div v-if="quizAttempts.length != 0">
                                <div v-for="(each, j) in quizAttempts">
                                    <div v-if="j == i">
                                        <div v-for="(each, k) in quizAttempts[i]">
                                            <p>
                                                Attempt {{quizAttempts[i][k][1]}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td v-if="isCompleted[i] == 1">
                            <div v-if="quizAttempts.length != 0">
                                <div v-for="(each, j) in quizAttempts">
                                    <div v-if="j == i">
                                        <div v-for="(each, k) in quizAttempts[i]">
                                            <p v-if="quizAttempts[i][k][2]==1" v-bind:style="{color:'green'}">
                                                <b>Pass</b>
                                            </p>
                                            <p v-else v-bind:style="{color:'red'}">
                                                <b>Fail</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>


                        <td v-if="isCompleted[i] == 1">
                            <div v-if="quizAttempts.length != 0">
                                <div v-for="(each, j) in quizAttempts">
                                    <div v-if="j == i">
                                        <div v-for="(each, k) in quizAttempts[i]">
                                            <a :href="'viewStudentAttempts.php?classid='+classID+'&quizid='+parseInt(i+1)+'&attemptNo='+parseInt(quizAttempts[i][k][1])+'&whichCourse='+zero" class="btn btn-outline-primary" style="margin-top:3px;">View Attempt {{quizAttempts[i][k][1]}} </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>

                </tbody>

            </table>
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
                getNoOfSections: <?php print json_encode($firstpageNoOfSec) ?>,
                totalPercentage: <?php print json_encode($totalPercentage) ?>,
                percentage: <?php print json_encode($percentage) ?>,
                quizAttempts: <?php print json_encode($getQuizAttempts) ?>,
                getMaterials: <?php print json_encode($getMaterials) ?>,
                isCompleted: <?php print json_encode($isCompleted) ?>,
                quizInformation: <?php print json_encode($quizInformation) ?>,
                classID: <?php print json_encode($getClassID) ?>,
                zero: <?php print json_encode($zero) ?>,
            },
            methods: {
                test: function(i) {
                    this.coursename = this.usercourses[i][1]
                    this.getCurrentCourse = this.usercourses[i]
                    this.getNoOfSections = this.usercourses[i][3].length //
                    this.classID = this.getCurrentCourse[2]
                    this.totalPercentage = 0
                    this.percentage = this.totalPercentage + "%"
                    this.quizAttempts = this.getCurrentCourse[5]
                    this.getMaterials = this.getCurrentCourse[6]
                    this.isCompleted = this.getCurrentCourse[4]
                    this.quizInformation = this.getCurrentCourse[7]
                    this.zero = i


                    if (this.quizInformation.length != 0) {
                        for (j = 0; j < this.quizInformation.length; j++) {
                            if (this.quizAttempts[j].length != 0) {
                                for (k = 0; k < this.quizAttempts[j].length; k++) {
                                    if (this.quizAttempts[j][k][2] == 1) {
                                        this.totalPercentage = parseFloat((this.quizAttempts[j][k][1] / this.getNoOfSections) * 100).toFixed(2)
                                        this.percentage = this.totalPercentage + "%";
                                        break;
                                    }
                                }
                            }
                        }
                    }

                }

            }

        })
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>