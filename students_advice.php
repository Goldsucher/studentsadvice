<?php
require_once('src/php/setup.inc.php');
require_once('src/php/DbHelper.php');

$dbHelper = new dbHelper($dbConn);

$gradesConditions = array('NT', 'belegt', '5,0', 'o.E.');


if(isset($_GET['show_studentDetails1'])) {
    $student = $dbHelper->getStudentDataWithColumnnames($_GET['show_studentDetails1'], $gradesConditions);

    $smarty->assign('student', $student);
    $smarty->display('show_student_details.tpl');

} elseif(isset($_GET['show_studentDetails2']) and isset($_GET['course'])) {
    $smarty->display('show_course_details.tpl');
} elseif(isset($_GET['show_studentDetails2']) and isset($_GET['semester'])) {
    $smarty->display('show_course_details.tpl');
}else {
    $hzbData = $dbHelper->getAllHzbWithColumnnames('hzb');

    $smarty->assign('hzbData', $hzbData);
    $smarty->display('filter.tpl');
}


