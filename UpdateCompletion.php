<?php
require 'objects/autoload.php';

$_SESSION['username'] = "Yu Hao";
$username = $_SESSION['username'];

$enrolDAO = new SectionDAO();
$classID = $_GET['classID'];
$sectionNum = $_GET['sectionNum'];
$materialNum = $_GET['materialNum'];

for($i=1; $i<$materialNum+1;$i++){
    $get = $enrolDAO->insertProgress($classID, $sectionNum, $i, $username, 1);
}
// $get = $enrolDAO->updateMaterialProgress($classID, $sectionNum, $materialNum);

header("Location: ViewCourseMaterials.php");
exit();

?>