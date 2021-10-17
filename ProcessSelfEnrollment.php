<?php
    require_once "objects/autoload.php";

if (isset($_POST["approve"])) {
    $enrollmentId = $_POST["approve"];
    $dao = new EnrollmentDAO();
    $status = $dao->updateEnrolmentStatus($enrollmentId, "Approved");

    if ($status) {
        $_SESSION["status"] = [true, "You have approved successfully!"];
    } else {
        $_SESSION["status"] = [false, "Unable to approve! Please try again."];
    }

    header("Location: ApprovalConfirmation.php");
    exit();
} else if (isset($_POST["reject"])) {
    $enrollmentId = $_POST["reject"];
    $dao = new EnrollmentDAO();
    $status = $dao->updateEnrolmentStatus($enrollmentId, "Rejected");

    if ($status) {
        $_SESSION["status"] = [true, "You have rejected successfully!"];
    } else {
        $_SESSION["status"] = [false, "Unable to reject! Please try again."];
    }

    header("Location: ApprovalConfirmation.php");
    exit();
}
?>