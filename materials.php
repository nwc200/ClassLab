<?php

require_once "objects/autoload.php";

$_SESSION['username'] = "Yu Hao";
// $_SESSION['username'] = "Mei Lan";
$username = $_SESSION['username'];
$status = 'Approved';

$enrolDAO = new SectionDAO();
$enrolments = $enrolDAO->retrieveUserApprovedEnrolment($username, $status); //return user approved enrolments

// $quizDAO = new QuizDAO();

$userCourses = (object)[];
$counter = 0;
$zero = 0;
// $classQuizzes = (object)[];


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
  // $retrieveQuiz = [];

    foreach ($sectionIDs as $sec) { //$sec is individual section num
        $sectionNames = $enrolDAO->retrieveClassSectionName($classID, $sec); //get section name
        array_push($arraySecName, $sectionNames);

        $materials = $enrolDAO->retrieveClassSectionMaterials($classID, $sec); // get section materials -- section 1, 2 materials
        array_push($arrayMaterials, $materials);

      // $quizzez = [];
      // $getQuiz = $enrolDAO->retrieveClassQuiz($classID, $sec);  // retrieve quiz information for the section
      // array_push($retrieveQuiz, $getQuiz);
      // var_dump($getQuiz);

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
// var_dump($retrieveQuiz);

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

    <!-- nav click -->
    <div class="container" v-if="coursename !='' ">
      <h4 style="text-align:center">
        {{coursename}}
      </h4>
      <div>
        <br>
        <div v-if='percentage != 100'>
          <p style="margin:2px;"><b> Section {{wSection}} Progress - {{coursename}}</b></p>
          <div class="col progress">
            <div class="progress-bar progress-bar-striped" role="progressbar" v-bind:style="{width: completedPercent}" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
              {{percentage}}%
            </div>
          </div>
          <h6 style="text-align:center;margin:5px">
            {{percentage}}% of Section {{wSection }} Completed
          </h6>
        </div>
        <div v-else>
          <p style="margin:2px;"><b>Section {{wSection}} - Progress</b></p>
          <div class="col progress">
            <div class="progress-bar progress-bar-striped" role="progressbar" v-bind:style="{width:'0%'}" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
              {{0}}%
            </div>
          </div>
          <h6 style="text-align:center;margin:5px">
            {{0}}% of Section {{wSection}} Completed
          </h6>
        </div>
      </div>
    </div>

    <!-- first time -->
    <div v-else>
      <h4 style="text-align:center">
        {{usercourses[0][1]}}
      </h4>

      <div class="container">
        <br>
        <div v-if='percentage != 100'>
          <p style="margin:2px;"><b>Section {{wSection}} Progress - {{usercourses[0][1]}}</b></p>
          <div class="col progress">
            <div class="progress-bar progress-bar-striped" role="progressbar" v-bind:style="{width: completedPercent}" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
              {{percentage}}%
            </div>
          </div>
          <h6 style="text-align:center;margin:5px">
            {{percentage}}% of Section {{wSection}} Completed
          </h6>
        </div>
        <div v-else>
          <p style="margin:2px;"><b>Section {{wSection+1}} Progress - {{usercourses[0][1]}}</b></p>
          <div class="col progress">
            <div class="progress-bar progress-bar-striped" role="progressbar" v-bind:style="{width:'0%'}" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
              {{0}}%
            </div>
          </div>
          <h6 style="text-align:center;margin:5px">
            {{0}}% of Section {{wSection+1}} Completed
          </h6>
        </div>
      </div>
    </div>


    <div class='container-fluid' style="margin:50px; padding: 20px">
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
            <tr v-for="(each, i) in firstpage[3]">
              <td>
                <b>Section {{firstpage[3][i]}}</b>
                <br>
                {{firstpage[4][i]}}
              </td>

              <td>
                <p v-for="(each, j) in firstpage[5][i]">
                  <i class="fas fa-file-pdf"></i>
                  <a v-bind:href="firstpage[5][i][j][2]">Lecture Note {{i+1}}.{{j}}</a>
                </p>
              </td>

              <td>
                <p v-for="(each, j) in firstpage[5][i]">
                  <i class="fa fa-download"></i>
                  <a v-bind:href="firstpage[5][i][j][2]" download="LectureNote">
                    Download
                  </a>
                </p>
              </td>

              <td>
                <div v-for="(each, j) in firstpage[7][i]">
                  <p v-if='firstpage[7][i][j] == 0'>
                    <input class="form-check-input-group" type="checkbox" :model="selected" :name="[firstpage[2], i+1, j+1]" :value="[firstpage[2], i+1, j+1]" id="flexCheckIndeterminate" @change='complete(firstpage[2], i+1, j+1)'>
                    <label class="form-check-label" for="flexCheckIndeterminate">
                      Complete
                    </label>
                  </p>
                  <p v-else>
                    <label class="form-check-label" for="flexCheckIndeterminate">
                      Completed
                    </label>
                  </p>
                </div>

              </td>
              <td v-if="firstpage[7][i][(firstpage[7][i].length)-1] == 1">
                <a href=''>
                  <p v-if="i+1 != getNoOfSections">
                    Quiz {{i+1}}
                  </p>
                  <p v-else>
                    Final Quiz
                  </p>
                </a>
              </td>
              <td v-else>

              </td>


            </tr>
          </tbody>

          <!-- nav click -->
          <tbody v-else>
            <tr v-for="(each, i) in getUserCourses[3]">
              <td>
                <b>Section {{getUserCourses[3][i]}}</b>
                <br>
                {{getUserCourses[4][i]}}
              </td>
              <td>
                <p v-for="(each, j) in getUserCourses[5][i]">
                  <i class="fas fa-file-pdf"></i>
                  <a v-bind:href="getUserCourses[5][i][j][2]">Lecture Note {{i+1}}.{{j}}</a>
                </p>
              </td>
              <td>
                <p v-for="(each, j) in getUserCourses[5][i]">
                  <i class="fa fa-download"></i>
                  <a v-bind:href="getUserCourses[5][i][j][2]" download="LectureNote">
                    Download
                  </a>
                </p>
              </td>

              <td>
                <div v-for="(each, j) in getUserCourses[7][i]">
                  <p v-if='getUserCourses[7][i][j] == 0'>
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
                    <label class="form-check-label" for="flexCheckIndeterminate">
                      Complete
                    </label>
                  </p>
                  <p v-else>
                    <label class="form-check-label" for="flexCheckIndeterminate">
                      Completed
                    </label>
                  </p>
                </div>
              </td>

              <td v-if="getUserCourses[7][i][(getUserCourses[7][i].length)-1] == 1">
                <a href=''>
                  <p v-if="i+1 != getNoOfSections">
                    Quiz {{i+1}}
                  </p>
                  <p v-else>
                    Final Quiz
                  </p>
                </a>
              </td>
              <td v-else>

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
        getNoOfSections: <?php print json_encode($firstpageNoOfSec) ?>,
        percentage: <?php print json_encode($percent) ?>,
        wSection: <?php print json_encode($whichSection) ?>,
        completedPercent: <?php print json_encode($completedPercent) ?>,
        wMaterial: <?php print json_encode($whichMaterial) ?>,
        noOfMaterials: <?php print json_encode($noOfMaterials) ?>,
        noMat: [],
        selected: [],
      },
      methods: {
        test: function(i) {
          this.coursename = this.usercourses[i][1]
          this.getUserCourses = this.usercourses[i]
          this.getNoOfSections = this.getUserCourses[7].length //retrieve no of sections

          for (j = 0; j < this.getNoOfSections; j++) {
            if (this.getUserCourses[7][j].length != 0) {

              for (k = 0; k < this.getUserCourses[7][j].length; k++) { //find which material is completed
                if (this.getUserCourses[7][j][k] == 0) { // 0 = not completed
                  this.wMaterial = k // k is the position
                  this.wSection = j + 1 // j is section

                  if (k != 0) {
                    this.percentage = (k / this.getUserCourses[7][j].length) * 100
                    this.completedPercent = this.percentage + '%'

                  } else {
                    this.percentage = 0
                    this.completedPercent = this.percentage + '%'
                    this.wSection = j + 1

                    break
                  }
                }
              }
            } else {
              this.wMaterial = 0
              this.wSection = j + 1
              this.percentage = 0
              this.completedPercent = this.percentage + '%'

              break
            }
            break
          }
        },

        complete: function(classID, sectionNum, materialNum) {
          this.selected = [parseInt(classID), sectionNum, materialNum]
          // console.log(this.selected)
          axios.post("http://localhost/SPM-Proj/UpdateCompletion.php"), {
            classID: this.selected[0],
            sectionNum: this.selected[1],
            materialNum: this.selected[2]
          }
          console.log(this.selected)
        }

      }

    })
  </script>

  <script src="https://unpkg.com/axios/dist/axios.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>