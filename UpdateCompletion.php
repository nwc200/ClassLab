<?php
require 'objects/autoload.php';

$_SESSION['username'] = "Yu Hao";
$username = $_SESSION['username'];

$enrolDAO = new SectionDAO();
$classID = $_GET['classID'];
$sectionNum = $_GET['sectionNum'];
$materialNum = $_GET['materialNum'];
$get = $enrolDAO->updateMaterialProgress($classID, $sectionNum, $materialNum);
var_dump($get);

?>