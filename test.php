<?php

require_once "objects/autoload.php";

$_SESSION['username'] = "Mei Lan";
$username = $_SESSION['username'];

$status = 'Approved';

$getSectionDAO = new SectionDAO();
$courseIDarr = $getSectionDAO->getLearnerCourseID($username, $status); //array of courseid


$classID = [];
$nameArr = [];
// $section = [];

foreach ($courseIDarr as $courseID) {
    // echo $courseID;
    $getID = $getSectionDAO->getLearnerClassID($courseID, $username);
    foreach ($getID as $getClassID) {
        $classID[] = $getClassID; //array of course ID
    }

    $getName = $getSectionDAO->getLearnerCourseName($courseID);
    foreach ($getName as $getCourseName) {
        // echo $getCourseName;
        $nameArr[] = $getCourseName; //array of courses name
    }
}

$userCourses = (object)[];
// $section = (object)[];
$section = (object)[];

//get section materials
for ($counter = 0; $counter < count($classID); $counter++) {
    $cID = $classID[$counter];
    // echo $cID;
    $getSectionNum = $getSectionDAO->getLearnerSection($cID); //get section number
    foreach ($getSectionNum as $sectionNum) {
        if (!isset($section->$counter)) {
            $section->$counter = [$sectionNum];
        } else {
            $section->$counter = [$sectionNum];
        }
        //     // $userSections = $getSectionDAO->getLearnerSectionMaterials($cID ,$sectionNum);
        //     echo $sectionNum;
        //     echo "<br>";
    }
    //courseID, classID, courseName
    $userCourses->$counter = [$courseIDarr[$counter], $classID[$counter], $nameArr[$counter]];
}
// var_dump($userCourses);
// var_dump($userSections);
// var_dump($section);


$url = "./materials/week1/Week1b-SPM-fundamentals-v1.0.pdf";
$url2 = "./materials/week2/Week2-SWDevProcess-G45.pdf";
$percent = '20%';

$hide = 'hidden';
$empty = '';
$counter = 0;
// $getSectionNum = $getSectionDAO->getLearnerSection($cID);
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
                            <div v-for="value in nArr.length" id='drop'>
                                <a class="dropdown-item" :value="value-1" @click='test([value-1])'> {{nArr[value-1]}} - Class {{usercourses[value-1][1]}}</a>
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
            {{nArr[0]}}
        </h4>


        <div class="container">
            <br>

            <p style="margin:2 0px;">Course Progress</p>
            <div class="col progress">
                <div class="progress-bar" id='progressBar' role="progressbar" style="width:<?= $percent ?>;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $percent ?></div>
            </div>

        </div>


        <div class='container-fluid' style="margin:50px; padding: 20px">
            <form method='POST' action='completionProgress.php'>
                <table class="table">
                    <thead>
                        <tr>
                            <td>Section</td>
                            <td>Course Materials</td>
                            <td>Download Materials</td>
                            <td>Quiz</td>
                            <td>Checklist</td>
                        </tr>
                    </thead>
                    
                    <div v-if='sec != 0'>
                        <tr v-for="i in sec" :key="i">
                            <td>
                                <?= $url ?>
                            </td>
                        </tr>
                    </div>
                    
                    <tr class="hide1">
                        <td>
                            <b>Section 1</b>

                        </td>
                        <td>
                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url ?>'>Lecture Notes 1.1</a><br>

                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url2 ?>'> Lecture Notes 1.2 </a><br>

                        </td>

                        <td>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url ?>' download="LectureNote 1-1">
                                    Download Lecture Notes 1.1
                                </a>
                            </button><br>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url ?>' download="LectureNotes 1-2">
                                    Download Lecture Notes 1.2
                                </a>
                            </button><br>
                        </td>


                        <td><a class="quiz" href="#item-1-2">Quiz 1</a></td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn1" <?= $hide ?>>Complete Session</button>
                            <p id='complete1' <?= $empty ?>> Completed </p>
                        </td>
                    </tr>

                    <tr class="hide2">
                        <td>
                            <b>Section 2</b>

                        </td>
                        <td>
                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url2 ?>'>Lecture Notes 2.1</a><br>
                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url ?>'>Lecture Notes 2.2</a><br>
                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url2 ?>'>Lecture Notes 2.3</a><br>

                        </td>

                        <td>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url2 ?>' download="Lecture Notes 2-1">
                                    Download Lecture Notes 2.1
                                </a>
                            </button><br>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url ?>' download="Lecture Notes 2-2">
                                    Download Lecture Notes 2.2
                                </a>
                            </button><br>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url2 ?>' download="Lecture Notes 2-3">
                                    Download Lecture Notes 2.3
                                </a>
                            </button><br>
                        </td>

                        <td><a class="quiz" href="#item-1-2">Quiz 2</a></td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn2">Complete Session</button>
                            <p id='complete2' <?= $hide ?>> Completed </p>
                        </td>
                    </tr>

                    <tr class="hide3" <?= $hide ?>>
                        <td>
                            <b>Section 3</b>

                        </td>
                        <td>
                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url ?>'>Lecture Notes 3.1</a><br>
                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url ?>'>Lecture Notes 3.2</a><br>

                        </td>

                        <td>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url ?>' download="Lecture Notes 3-1">
                                    Download Lecture Notes 3.1
                                </a>
                            </button><br>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url2 ?>' download="Lecture Notes 3-3">
                                    Download Lecture Notes 3.2
                                </a>
                            </button><br>
                        </td>
                        <td><a class="quiz" href="#item-1-2">Quiz 3</a></td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn3">Complete Session</button>
                            <p id="complete3" <?= $hide ?>> Completed </p>
                        </td>
                    </tr>

                    <tr class="hide4" <?= $hide ?>>
                        <td>
                            <b>Section 4</b>

                        </td>
                        <td>
                            <i class="fas fa-file-pdf"></i>
                            <a class="quiz" href='<?= $url ?>'>Lecture Notes 4.1</a><br>

                        </td>

                        <td>
                            <button class="btn"><i class="fa fa-download"></i>
                                <a href='<?= $url ?>' download="Lecture Notes 4-1">
                                    Download Lecture Notes 4.1
                                </a>
                            </button><br>

                        </td>
                        <td><a class="quiz" href="#item-1-2">Quiz 4</a></td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn4">Complete Session</button>
                            <p id="complete4" <?= $hide ?>> Completed </p>
                        </td>
                    </tr>

                </table>
            </form>
        </div>
    </div>


    <script>
        var app = new Vue({
            el: "#app",
            data: {

                username: <?php print json_encode($username) ?>,
                counter: <?php print json_encode($counter) ?>,
                coursename: "",
                counter: 0,
                sec: 0,
                nArr: <?php print json_encode($nameArr) ?>,
                usercourses: <?php print json_encode($userCourses) ?>,
                section: <?php print json_encode($section) ?>
            },
            methods: {
                test: function(i) {
                    this.coursename = this.nArr[i]
                    this.counter = i
                    this.sec = parseInt(this.section[i])
                }
            }
        })
    </script>






    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>