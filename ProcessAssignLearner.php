<?php
    require_once "objects/autoload.php";

    if(isset($_POST["submit"])){
        $name = $_POST["submit"];
        $courseid = $_POST["courseID"];
        $classid = $_POST["classID"];

        $dao = new EnrollmentDAO();
        $status = $dao->addEnrolment($name, $courseid, $classid);

        if ($status) {
            $_SESSION["status"] = true;
        } else {
            $_SESSION["status"] = false;
        }

        $_SESSION["learner"] = $name;
        header("Location: InsertConfirmation.php");
        exit();
    }
?>