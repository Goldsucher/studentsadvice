<?php

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/../cleaner/CleanHelper.php');



$cleanHelper = new CleanHelper($dbConn);

//remove remove non-bachelor students
$cleanHelper->removeNonBachelor();

//Mark students who have visited another university before
$cleanHelper->markStudentsFromOtherUniversity();

//remove double grades from noten-Table
$cleanHelper->removeDoubleGradesFromTable('noten');

//TODO Change function name
// get double grades
$cleanHelper->getDoubleGradesAndWriteInExtraTable("engueltigeKursNoten");
