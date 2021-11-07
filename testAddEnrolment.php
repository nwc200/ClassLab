<?php

require_once "objects/autoload.php";

$dao = new EnrollmentDAO();

//Test 1 - (Database Add)
echo '<hr>';
echo "<h1>Database Add</h1>";
$name = 'Xi Hwee';
$courseID = 2;
$classID = 1;
if ( $dao->addPendingEnrolment($name, $courseID, $classID) ) {
    echo "<font color='blue'>
    Enrolment Success! Enrollment application will be send to the HR for approval.
        </font>";
    var_dump( $dao->retrieveEnrolment($courseID, $classID) );
} else {
    echo "<font color='red'>Enrolment failure! Please try again.</font>";
}

echo '<hr>';
echo "<h1>Database Add</h1>";
$name = 'Xi Hwee';
$courseID = 6;
$classID = 2;
if ( $dao->addPendingEnrolment($name, $courseID, $classID) ) {
    echo "<font color='blue'>
    Enrolment Success! Enrollment application will be send to the HR for approval.
        </font>";
    var_dump( $dao->retrieveEnrolment($courseID, $classID) );
} else {
    echo "<font color='red'>Enrolment failure! Please try again.</font>";
}
