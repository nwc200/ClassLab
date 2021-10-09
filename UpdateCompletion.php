<?php
    require 'objects/autoload.php';

    $enrolDAO = new SectionDAO();

    if(isset($_SESSION['username'])){
        $classID = $_REQUEST['classID'];
        $sectionNum = $_REQUEST['sectionNum'];
        $materialNum = $_REQUEST['materialNum'];
        $get = $enrolDAO->updateMaterialProgress($classID, $sectionNum, $materialNum);
        var_dump($get);
    }
?>