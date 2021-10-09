<?php

require_once "objects/autoload.php";

$_SESSION['username'] = "Yu Hao";
// $_SESSION['username'] = "Mei Lan";
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
        // var_dump($materials);
        $material = [];
        $completed = [];
        for ($i = 0; $i < count($materials); $i++) {
            $materialNum = $materials[$i]->getMaterialNum(); //get materials id by section num
            array_push($material, $materialNum);
            // $getMaterials = [$classID, $sec, $materialNum]; //array of materialnum with classid & section num

            $getCompleted = $enrolDAO->retrieveSectionMaterialsProgress($username, $classID, $sec, $materialNum); //get completed
            array_push($completed, $getCompleted);

            // echo "<br>";
        }
        array_push($getMaterialsNum, $material);
        array_push($getCompletedArr, $completed);
    }
    // var_dump($getCompletedArr);
    // var_dump($getMaterialsNum);

    $userCourses->$counter = [$courseID, $courseName, $classID, $sectionIDs, $arraySecName, $arrayMaterials, $getMaterialsNum, $getCompletedArr];

    $counter++;
}
// var_dump($userCourses);


$firstpage = $userCourses->$zero;
$firstpageNoOfSec = count($firstpage[5]);
// var_dump($firstpage[6][0][0]); // get material num of section 1
// var_dump($firstpage[7]);

$whichSection = '';
$whichMaterial = '';
$noOfMaterials = 0;

for ($j = 0; $j < count($firstpage[7]); $j++) { //can alr know got how many sections
    for ($k = 0; $k < count($firstpage[7][$j]); $k++) { //completed inside the sections
        // var_dump($firstpage[7][$j][$k]);
        $noOfMaterials = count($firstpage[7][$j]);
        // echo"$noOfMaterials";
        if ($noOfMaterials > 0) {
            if (($firstpage[7][$j][$k]) == 0) {  //knows which section arr, which material is not completed
                $whichSection = $j + 1;
                $whichMaterial = $k;
                // echo $j, $k; 
                break;
            } else {
                $whichSection = $k + 1;
                $whichMaterial = 0;
            }
        } else {
            $whichSection = $j;
            $whichMaterial = 0;
        }
    }
}

// echo $whichSection;
$percent = ($whichMaterial / ($noOfMaterials) * 100);
$completedPercent = $percent . '%';

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
                        <!-- hello{{counter}} -->
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
                    <div class="progress-bar progress-bar-striped" role="progressbar" v-bind:style="{width:'0%'}" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                        {{0}}%
                    </div>
                </div>
                <h6 style="text-align:center;margin:5px">
                    {{0}}% of Quizzes Completed
                </h6>
            </div>
        </div>

        <div class='container-fluid' style="margin:50px; padding: 20px">
            <form method='POST' action='completionProgress.php'>
                <table class="table">
                    <thead>
                        <tr>
                            <td>Section</td>
                            <td>Quiz</td>
                            <td>Attempts</td>
                            <td>Scores</td>
                            <td>View Attempt</td>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(each, i) in getNoOfSections">
                            <td>
                                <b>Section {{i+1}}</b>
                            </td>
                            <td >
                                <a class="quiz" href=''>Quiz {{i+1}}</a><br>
                            </td>

                            <td>
                                <p>Attempt 1</p>
                            </td>

                            <td>
                                <p>4 / 10</p>
                            </td>

                            <td>
                                <button type="button" class="btn btn-outline-primary btn1" style="margin:1px;">View</button> <br>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </form>
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
                getUserCourses: '',
                getNoOfSections: <?php print json_encode($firstpage[5]) ?>,
            },
            methods: {
                test: function(i) {
                    this.coursename = this.usercourses[i][1]
                    this.getUserCourses = this.usercourses[i]
                    this.getNoOfSections = this.usercourses[i][5].length //currently only retrieve the 1st section materials 
                }

            }

        })
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>