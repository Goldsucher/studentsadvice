<?php
require_once('src/php/setup.inc.php');
require_once('src/php/DbHelper.php');

$dbHelper = new dbHelper($dbConn);


if(isset($_GET['show_id'])){
    $student = $dbHelper->getStudentDataWithColumnnames($_GET['show_id']);

    $smarty->assign('student', $student);
    $smarty->display('show_student.tpl');
}else {
    $hzbData = $dbHelper->getAllHzbWithColumnnames('hzb');

    $smarty->assign('hzbData', $hzbData);
    $smarty->display('filter.tpl');
}


