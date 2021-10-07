<?php

require_once "objects/autoload.php";

$_SESSION['username'] = "Mei Lan";
$username = $_SESSION['username'];

$status = 'Approved';

$enrolDAO = new EnrolmentDAO();
$enrolments = $enrolDAO->retrieveUserApprovedEnrolment($username, $status); //return user approved enrolments
$userCourses = (object)[];
$counter = 0;
$sectionCount = 0;


foreach ($enrolments as $enrol) {
  $courseID = $enrol->getCourseID();
  $courses = $enrolDAO->retrieveCourses($courseID); //return user enrolled courses
  $courseName = $courses->getCourseName();
  $classID = $enrolDAO->getLearnerClassID($courseID, $username);
  $sectionIDs = $enrolDAO->retrieveClassSection($classID); //get section ids
  // var_dump($sectionIDs);

  //courseid, coursename, classid, sections-array, 
  // $userCourses->$counter = [$courseID, $courseName, $classID, $sectionIDs];

  // $all = $userCourses->$counter;
  
  $arraySecName = [];
  foreach($sectionIDs as $sec){
    $sectionNames = $enrolDAO->retrieveClassSectionName($classID, $sec); //get section name
    array_push($arraySecName, $sectionNames);
  }
  $userCourses->$counter = [$courseID, $courseName, $classID, $sectionIDs,$arraySecName ];
  $counter++;
 
}
var_dump($userCourses);


$url = "./materials/week1/Week1b-SPM-fundamentals-v1.0.pdf";
$url2 = "./materials/week2/Week2-SWDevProcess-G45.pdf";
$percent = '0%';

$hide = 'hidden';
$empty = '';
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



        </table>
      </form>
    </div>
  </div>


  <script>
    var app = new Vue({
      el: "#app",
      data: {
        //$courseid, coursename, classid
        usercourses: <?php print json_encode($userCourses) ?>,
        counter: <?php print json_encode($counter) ?>,
        coursename: '',
        count: 0,
        percent: '0%'
      },
      methods: {
        test: function(i) {
          this.coursename = this.usercourses[i][1]
          this.counter = i
        }
      }
    })
  </script>


  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>