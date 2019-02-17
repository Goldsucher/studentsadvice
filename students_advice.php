<?php
require_once('src/php/setup.inc.php');
require_once('src/php/DbHelper.php');

$dbHelper = new dbHelper($dbConn);

$gradesConditions = array('NT', 'belegt', '5.0', 'o.E.');


if(isset($_GET['timeline'])) {
    $student = $dbHelper->getAndPrepareTimelineInformationsForAStudent($_GET['timeline']);

    $smarty->assign('timeline', $student);
    $smarty->display('show_student_timeline.tpl');
} elseif(isset($_GET['timeline_2'])) {
    $student = json_encode($dbHelper->getAndPrepareTimelineInformationsForAStudent($_GET['timeline_2']));

    $smarty->assign('student', $student);
    $smarty->display('show_student_timeline_2.tpl');

}elseif(isset($_GET['line_chart'])) {
    $student = $_GET['line_chart'];

    $numberOfCourses = array();
    $numberOfCourses['numberOfCoursesTaken'] = $dbHelper->getNumberOfTakenCoursesPerSemester($student);
    $numberOfCourses['numberOfPassedCourses'] = $dbHelper->getNumberOfPassedCoursesPerSemester($student);

    $smarty->assign('student', $student);
    $smarty->assign('numberOfCourses', json_encode($numberOfCourses));
    $smarty->display('show_student_linechart.tpl');

} elseif(isset($_GET['avg_grades'])) {

    $gradesInformations = $dbHelper->getGradeInformations();

    $smarty->assign('grades', $gradesInformations);
    $smarty->display('show_grades_average.tpl');
}else {
    echo "NOTHING TO DO!!!!";
}


