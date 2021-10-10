<?php
require_once "objects/autoload.php";

if (isset($_POST["submit"])) {
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $courseid = $_POST["courseid"];
    $classid = $_POST["classid"];

    $dao = new CourseDAO();
    $status = $dao->updateEnrolmentPeriod($courseid, $classid, $startDate, $endDate);

    if ($status) {
        $_SESSION["status"] = true;
    } else {
        $_SESSION["status"] = false;
    }
    header("Location: UpdateConfirmation.php");
    exit();
}
?>