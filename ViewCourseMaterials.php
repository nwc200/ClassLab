<?php

require_once "objects/autoload.php";

$_SESSION['username'] = "Yu Hao";
// $_SESSION['username'] = "Mei Lan";
$username = $_SESSION['username'];
$status = 'Approved';

$enrolDAO = new SectionDAO();
$enrolments = $enrolDAO->retrieveUserApprovedEnrolment($username, $status); //return user approved enrolments
// var_dump($enrolments);
$quizDAO = new QuizDAO();

$userCourses = (object)[];
$counter = 0;
$zero = 0;

if (isset($_GET['whichCourse'])) {
    $zero = $_GET['whichCourse'];
}


foreach ($enrolments as $enrol) {
    $getCourse= $enrol->getCourse();
    $courseID = $getCourse->getCourseID();
    $courses = $enrolDAO->retrieveCourses($courseID); //return user enrolled courses
    $courseName = $courses->getCourseName();
    $classID = $enrolDAO->getLearnerClassID($courseID, $username); //get class id
    $sectionIDs = $enrolDAO->retrieveClassSection($classID); //get section ids

    $getQuizzes = $quizDAO->retrieveClassQuiz($classID);

    $arraySecName = [];
    $arrayMaterials = [];
    $materialNumArr = [];
    $getMaterialsNum = [];
    $getCompletedArr = [];


    foreach ($sectionIDs as $sec) { //$sec is individual section num
        $sectionNames = $enrolDAO->retrieveClassSectionName($classID, $sec); //get section name
        array_push($arraySecName, $sectionNames);

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


        $getQuizzes = $quizDAO->retrieveClassQuiz($classID);  //get quizes for that classid
    // var_dump($getQuizzes);
        $quizAttempts = [];
        foreach ($getQuizzes as $getQuiz) {
            $quizid = $getQuiz[0];
            $attempts = $enrolDAO->studentQuizAttemptRetrieve($username, $quizid);
            array_push($quizAttempts, $attempts);
        }



        array_push($getMaterialsNum, $material);
        array_push($getCompletedArr, $completed);
    }


      $userCourses->$counter = [$courseID, $courseName, $classID, $sectionIDs, $arraySecName, $arrayMaterials, $getMaterialsNum, $getCompletedArr, $quizAttempts, $getQuizzes];
  // var_dump($userCourses->$counter[2]);
      $counter++;
}


$firstpage = $userCourses->$zero;
$firstpageNoOfSec = count($firstpage[3]);
$classID = $firstpage[2];
$getQuizAttempts = $firstpage[8];
$noOfQuizzez = $firstpage[9];
$noOfMaterials = $firstpage[6];

$percent = 0;
$completedPercent = $percent . '%';
$completed = [];

if (count($noOfQuizzez) != 0) {
    if (count($getQuizAttempts) != 0) {
        for ($i = 0; $i < count($getQuizAttempts); $i++) {
            for ($k = 0; $k < count($getQuizAttempts[$i]); $k++) {
                if ($getQuizAttempts[$i][$k][2] == 1) {
                    $percent =  number_format((($i + 1) / $firstpageNoOfSec) * 100, 2, '.', '');
                    $completedPercent = $percent . '%';
                    $completed[] = 1;
                    break;
                }
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"
        integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
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
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="ViewCourseMaterials.php" active>Course Materials <span
                                class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ViewQuizMaterials.php">Quizzes Available </a>
                    </li>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Courses Enrolled
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <div v-for="(each, i) in usercourses">
                                <a class="dropdown-item" :value="i" @click='test([i])'> {{usercourses[i][1]}} - Class
                                    {{usercourses[i][2]}}</a>
                            </div>
                        </div>
                    </div>
                </ul>
                Learner: {{username}}

            </div>
        </nav>
        <br>

        <!-- nav click -->
        <div class="container">
            <h4 style="text-align:center" v-if="coursename != ''">
                {{coursename}}
            </h4>
            <h4 style="text-align:center" v-else>
                {{firstpage[1]}}
            </h4>

            <div>
                <br>
                <div>
                    <p style="margin:2px;"><b>Course Progress </b></p>
                    <div class="col progress">
                        <div class="progress-bar progress-bar-striped" role="progressbar"
                            v-bind:style="{width: completedPercent}" aria-valuenow="10" aria-valuemin="0"
                            aria-valuemax="100">
                            {{completedPercent}}
                        </div>
                    </div>
                    <h6 style="text-align:center;margin:5px">
                        {{completedPercent}} of Course Completed
                    </h6>
                </div>
            </div>
        </div>

        <br>
        <br>

        <div class='container-fluid'>
            <form method='POST' action='UpdateCompletion.php'>
                <table class="table">
                    <thead>
                        <tr>
                            <td>Section</td>
                            <td>Course Materials</td>
                            <td>Download Materials</td>
                            <td>Checklist</td>
                            <td>Quiz</td>
                        </tr>
                    </thead>

                    <!-- first time -->
                    <tbody v-if="coursename ==''">
                        <tr v-if="firstpage[5][0].length != 0 ">
                            <td> <b>Section 1</b>
                                <br>
                                {{firstpage[4][0]}}
                            </td>

                            <td>

                                <div v-for="(each, j) in firstpage[5][0]">
                                    <p v-if="firstpage[5][0][j][1] == 'pdf'">
                                        <i class="fas fa-file-pdf"></i>
                                        <a v-bind:href="firstpage[5][0][j][2]" target=”_blank”>Lecture Note
                                            {{1}}.{{firstpage[5][0][j][0]}}</a>
                                    </p>
                                    <p v-else-if="firstpage[5][0][j][1] == 'docs'" target=”_blank”>
                                        <i class="fas fa-file-word"></i>
                                        <a v-bind:href="firstpage[5][0][j][2]"
                                            target=”_blank”>{{firstpage[5][0][j][2]}}</a>
                                    </p>
                                    <p v-else>
                                        <i class="fas fa-link"></i>
                                        <a v-bind:href="firstpage[5][0][j][2]"
                                            target=”_blank”>{{firstpage[5][0][j][2]}}</a>
                                    </p>
                                </div>
                            </td>

                            <td>
                                <div v-for="(each, j) in firstpage[5][0]">
                                    <p v-if="firstpage[5][0][j][2][0] == '.'">
                                        <i class="fa fa-download"></i>
                                        <a v-bind:href="firstpage[5][0][j][2]" :download="firstpage[5][0][j][2]">
                                            Download
                                        </a>
                                    </p>
                                    <p v-else>
                                        -
                                    </p>
                                </div>
                            </td>

                            <td>
                                <p v-if='firstpage[7][0][1] == 1'>
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Completed
                                    </label>
                                </p>
                                <p v-else-if='firstpage[7][0][1] == 0'>
                                    <a :href="'UpdateCompletion.php?classID='+classID+'&sectionNum='+parseInt(1)+'&materialNum='+noOfMaterials[0].length+'&whichCourse='+zero"
                                        class="btn btn-primary">Complete</a>
                                </p>
                            </td>

                            <td>
                                <div v-if="firstpage[7][0][0] == 1">
                                    <div v-for="(each, j) in noOfQuizzez">
                                        <p v-if="j == 0">
                                            <a
                                                :href="'AttemptQuiz.php?classid='+classID+'&quizid='+ parseInt(noOfQuizzez[0][0])+'&whichCourse='+zero">
                                                {{noOfQuizzez[0][3]}}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr v-for="(each, i) in getNoOfSections-1">
                            <td>
                                <div v-if="completed[i] == 1">
                                    <p>
                                        <b>Section {{i+2}}</b>
                                        <br>
                                        {{firstpage[4][i+1]}}
                                    </p>
                                </div>
                            </td>

                            <td>
                                <div v-if="completed[i] == 1">
                                    <div v-for="(each, j) in firstpage[5][i+1]">
                                        <p v-if="firstpage[5][i+1][j][1] == 'pdf'">
                                            <i class="fas fa-file-pdf"></i>
                                            <a v-bind:href="firstpage[5][i+1][j][2]" target=”_blank”>Lecture Note
                                                {{i+2}}.{{firstpage[5][i+1][j][0]}}</a>
                                        </p>
                                        <p v-else-if="firstpage[5][i+1][j][1] == 'docs'">
                                            <i class="fas fa-file-word"></i>
                                            <a v-bind:href="firstpage[5][i+1][j][2]"
                                                target=”_blank”>{{firstpage[5][i+1][j][2]}}</a>
                                        </p>
                                        <p v-else>
                                            <i class="fas fa-link"></i>
                                            <a v-bind:href="firstpage[5][i+1][j][2]"
                                                target=”_blank”>{{firstpage[5][i+1][j][2]}}</a>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div v-if="completed[i] == 1">
                                    <div v-for="(each, j) in firstpage[5][i+1]">
                                        <p v-if="firstpage[5][i+1][j][2][0] == '.'">
                                            <i class="fa fa-download"></i>
                                            <a v-bind:href="firstpage[5][i+1][j][2]"
                                                :download="firstpage[5][i+1][j][2]">
                                                Download
                                            </a>
                                        </p>
                                        <p v-else>
                                            -
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div v-if="completed[i] == 1">
                                    <p v-if='firstpage[7][i+1][1] == 1'>
                                        <label class="form-check-label" for="flexCheckIndeterminate">
                                            Completed
                                        </label>
                                    </p>
                                    <p v-else-if='firstpage[7][i+1][1] == 0'>
                                        <a :href="'UpdateCompletion.php?classID='+classID+'&sectionNum='+parseInt(i+2)+'&materialNum='+noOfMaterials[i+1].length+'&whichCourse='+zero"
                                            class="btn btn-primary">Complete</a>
                                    </p>
                            </td>

                            <td>
                                <div v-if="firstpage[7][i+1][0] == 1">
                                    <div v-for="(each, j) in noOfQuizzez">
                                        <p v-if="j == i+1">
                                            <a
                                                :href="'AttemptQuiz.php?classid='+classID+'&quizid='+ parseInt(noOfQuizzez[i+1][0])+'&whichCourse='+zero">
                                                {{noOfQuizzez[i+1][3]}}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>

                    <!-- nav click -->
                    <tbody v-else>
                        <tr v-if="getUserCourses[5][0].length != 0 ">

                            <td><b>Section 1</b>
                                <br>
                                {{getUserCourses[4][0]}}
                            </td>

                            <td>
                                <div v-for="(each, j) in getUserCourses[5][0]">
                                    <p v-if="getUserCourses[5][0][j][1] == 'pdf'">
                                        <i class="fas fa-file-pdf"></i>
                                        <a v-bind:href="getUserCourses[5][0][j][2]" target=”_blank”>Lecture Note
                                            {{1}}.{{getUserCourses[5][0][j][0]}}</a>
                                    </p>
                                    <p v-else-if="getUserCourses[5][0][j][1] == 'docs'">
                                        <i class="fas fa-file-word"></i>
                                        <a v-bind:href="getUserCourses[5][0][j][2]"
                                            target=”_blank”>{{getUserCourses[5][0][j][2]}}</a>
                                    </p>
                                    <p v-else>
                                        <i class="fas fa-link"></i>
                                        <a v-bind:href="getUserCourses[5][0][j][2]"
                                            target=”_blank”>{{getUserCourses[5][0][j][2]}}</a>
                                    </p>
                                </div>
                            </td>

                            <td>
                                <div v-for="(each, j) in getUserCourses[5][0]">
                                    <p v-if="getUserCourses[5][0][j][2][0] == '.'">
                                        <i class="fa fa-download"></i>
                                        <a v-bind:href="getUserCourses[5][0][j][2]"
                                            :download="getUserCourses[5][0][j][2]">
                                            Download
                                        </a>
                                    </p>
                                    <p v-else>
                                        -
                                    </p>
                                </div>
                            </td>

                            <td>
                                <p v-if='getUserCourses[7][0][1] == 1'>
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Completed
                                    </label>
                                </p>
                                <p v-else-if='getUserCourses[7][0][1] == 0'>
                                    <a :href="'UpdateCompletion.php?classID='+classID+'&sectionNum='+parseInt(1)+'&materialNum='+noOfMaterials[0].length+'&whichCourse='+zero"
                                        class="btn btn-primary">Complete</a>
                                </p>
                            </td>


                            <td>
                                <div v-if="getUserCourses[7][0][0] == 1">
                                    <div v-for="(each, j) in noOfQuizzez">
                                        <p v-if="j == 0">
                                            <a
                                                :href="'AttemptQuiz.php?classid='+classID+'&quizid='+ parseInt(noOfQuizzez[0][0])+'&whichCourse='+zero">
                                                {{noOfQuizzez[0][3]}}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </td>

                        </tr>

                        <tr v-for="(each, i) in getNoOfSections-1">
                            <td>
                                <div v-if="completed[i] == 1">
                                    <p>
                                        <b>Section {{i+2}}</b>
                                        <br>
                                        {{getUserCourses[4][i+1]}}
                                    </p>
                                </div>

                            </td>

                            <td>
                                <div v-if="completed[i] == 1">
                                    <div v-for="(each, j) in getUserCourses[5][i+1]">
                                        <p v-if="getUserCourses[5][i+1][j][1] == 'pdf'">
                                            <i class="fas fa-file-pdf"></i>
                                            <a v-bind:href="getUserCourses[5][i+1][j][2]" target=”_blank”>Lecture Note
                                                {{i+2}}.{{firstpage[5][i+1][j][0]}}</a>
                                        </p>
                                        <p v-else-if="getUserCourses[5][i+1][j][1] == 'docs'">
                                            <i class="fas fa-file-word"></i>
                                            <a v-bind:href="getUserCourses[5][i+1][j][2]"
                                                target=”_blank”>{{firstpage[5][i+1][j][2]}}</a>
                                        </p>
                                        <p v-else>
                                            <i class="fas fa-link"></i>
                                            <a v-bind:href="getUserCourses[5][i+1][j][2]"
                                                target=”_blank”>{{firstpage[5][i+1][j][2]}}</a>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div v-if="completed[i] == 1">
                                    <div v-for="(each, j) in getUserCourses[5][i+1]">
                                        <p v-if="firstpage[5][i+1][j][2][0] == '.'">
                                            <i class="fa fa-download"></i>
                                            <a v-bind:href="firstpage[5][i+1][j][2]"
                                                :download="firstpage[5][i+1][j][2]">
                                                Download
                                            </a>
                                        </p>
                                        <p v-else>
                                            -
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div v-if="completed[i] == 1">
                                    <p v-if='getUserCourses[7][i+1][0] == 1'>
                                        <label class="form-check-label" for="flexCheckIndeterminate">
                                            Completed
                                        </label>
                                    </p>
                                    <p v-else-if='getUserCourses[7][i+1][0] == 0'>
                                        <a :href="'UpdateCompletion.php?classID='+classID+'&sectionNum='+parseInt(i+2)+'&materialNum='+noOfMaterials[i+1].length+'&whichCourse='+zero"
                                            class="btn btn-primary">Complete</a>
                                    </p>
                            </td>

                            <td>
                                <div v-if="getUserCourses[7][i+1][0] == 1">
                                    <div v-for="(each, j) in noOfQuizzez">
                                        <p v-if="j == i+1">
                                            <a
                                                :href="'AttemptQuiz.php?classid='+classID+'&quizid='+ parseInt(noOfQuizzez[i+1][0])+'&whichCourse='+zero">
                                                {{noOfQuizzez[i+1][3]}}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </form>
        </div>

    </div>
    <br>
    <br>
    <br>
    <br>
    <hr>
    <p style="margin:10px">
        <small>Next section will only be shown once you pass the current session quiz.</small> <br>
        <small>Course will be completed once you pass the end of course quiz.</small>
    </p>


    <script>
    var app = new Vue({
        el: "#app",
        data: {
            username: <?php print json_encode($username) ?>,
            usercourses: <?php print json_encode($userCourses) ?>,
            firstpage: <?php print json_encode($firstpage) ?>,
            coursename: '',
            getUserCourses: '',
            getNoOfSections: <?php print json_encode($firstpageNoOfSec) ?>,
            percentage: <?php print json_encode($percent) ?>,
            completedPercent: <?php print json_encode($completedPercent) ?>,
            noOfMaterials: <?php print json_encode($noOfMaterials) ?>,
            classID: <?php print json_encode($classID) ?>,
            quizAttempts: <?php print json_encode($getQuizAttempts) ?>,
            noOfQuizzez: <?php print json_encode($noOfQuizzez) ?>,
            completed: <?php print json_encode($completed) ?>,
            zero: <?php print json_encode($zero) ?>,

        },
        methods: {
            test: function(i) {
                this.coursename = this.usercourses[i][1]
                this.getUserCourses = this.usercourses[i]
                this.getNoOfSections = this.getUserCourses[3].length //retrieve no of sections

                this.percentage = 0
                this.completedPercent = '0%'
                this.classID = this.getUserCourses[2]
                this.quizAttempts = this.getUserCourses[8]
                this.noOfQuizzez = this.getUserCourses[9]
                this.noOfMaterials = this.getUserCourses[6]
                this.completed = []
                this.zero = i

                if (this.noOfQuizzez.length != 0) {
                    if (this.quizAttempts.length != 0) {
                        for (j = 0; j < this.quizAttempts.length; j++) {
                            for (k = 0; k < this.quizAttempts[j].length; k++) {
                                if (this.quizAttempts[j][k][2] == 1) { //section 1 done, section 2 done
                                    this.percentage = parseFloat(((j + 1) / this.getNoOfSections) * 100)
                                        .toFixed(2)
                                    this.completedPercent = this.percentage + '%'
                                    this.completed.push(1)
                                    break
                                }
                            }
                        }
                    }
                }
            }
        }

    })
    </script>

    <script src="https://unpkg.com/axios/dist/axios.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</body>

</html>