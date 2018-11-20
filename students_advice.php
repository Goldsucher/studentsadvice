<?php
require_once('src/php/setup.inc.php');
require_once('src/php/DbHelper.php');

$dbHelper = new dbHelper($dbConn);

$gradesConditions = array('NT', 'belegt', '5,0', 'o.E.');


if(isset($_GET['show_studentDetails1'])) {
    $student = $dbHelper->getStudentDataWithColumnnames($_GET['show_studentDetails1'], $gradesConditions);

    $smarty->assign('student', $student);
    $smarty->display('show_student_details.tpl');

} elseif(isset($_GET['show_studentDetails2']) and (isset($_GET['course']) || isset($_GET['semester']))) {
    $course = "";
    $semester = "";

    if (isset($_GET['course'])) {
        $course = $_GET['course'];
    }

    if (isset($_GET['semester'])) {
        $semester = $_GET['semester'];
    }

    $details = array(
        'Unit' => $course,
        'Semester' => $semester
    );

    $moredetails = $dbHelper->getStudentMoreDetails($_GET['show_studentDetails2'], $details);

    $smarty->assign('moredetails', $moredetails);
    $smarty->display('show_student_moreDetails.tpl');
} elseif(isset($_GET['timeline'])) {
    $timeline = $dbHelper->getAndPrepareTimelineInformationsForAStudent($_GET['timeline']);

    $smarty->assign('timeline', $timeline);
    $smarty->display('show_student_timeline.tpl');
}else {
    $hzbData = $dbHelper->getAllHzbWithColumnnames('hzb');

    $smarty->assign('hzbData', $hzbData);
    $smarty->display('filter.tpl');
}


