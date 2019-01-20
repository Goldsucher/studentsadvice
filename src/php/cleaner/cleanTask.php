<?php

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/../cleaner/CleanHelper.php');

if (php_sapi_name() == "cli") {
    $phpEOL = PHP_EOL;
} else {
    $phpEOL = '<br/>';
}

$idsWithElective = 'ids_elective.txt'; // path
$scheduledSemester = 'scheduled_semester.csv'; // path

$cleanHelper = new CleanHelper($dbConn);
echo "Cleaning Datasets started...".$phpEOL;

//special customizations
$cleanHelper->specialCustomizations();
echo 'do special customizations'.$phpEOL;

//remove non-bachelor students
$cleanHelper->removeNonBachelor();
echo "removed all non bachelor...".$phpEOL;

//prepare extension tables
$cleanHelper->prepareExtensionTables();
echo "prepared extension tables".$phpEOL;

//Mark students who have visited another university before
$cleanHelper->markStudentsFromOtherUniversity();
echo "marked all students, who have visited another university before..." . $phpEOL;

//Mark all college dropouts
$cleanHelper->markAllCollegeDropout();
echo "marked all college dropouts..." . $phpEOL;

//remove double grades from noten-Table
$cleanHelper->exportAndRemoveDoubleGrades('noten', 'doppelteNoten');
echo "removed double grades from noten-table...".$phpEOL;

////change BNF E to 6 in noten-table
//$cleanHelper->changeColumnValue("noten", "BNF", "E", "6");
//echo "changed BNF E to 6 from noten-table...".$phpEOL;

//Identify all common core
$cleanHelper->identfyCommonCore($idsWithElective);
echo "Identify all common core from units-table...".$phpEOL;

//set sheduled semester
$cleanHelper->setScheduledSemester($scheduledSemester);
echo "Set sheduled semester...".$phpEOL;

//calculate average grade from units-table per unit with 5.0
$cleanHelper->calculateAverageGrade("units", "Unit_id", "units_extension", "Durchschnittsnote_5", true);
echo "calculated average grade per unit with 5.0...".$phpEOL;

//calculate average grade from units-table per unit without 5.0
$cleanHelper->calculateAverageGrade("units", "Unit_id", "units_extension", "Durchschnittsnote", false);
echo "calculated average grade per unit without 5.0...".$phpEOL;

//calculate average grade from noten-table for per student without 5.0
$cleanHelper->calculateAverageGrade("hzb", "Student_id", "hzb_extension", "Durchschnittsnote",false);
echo "calculated average grade per student without 5.0...".$phpEOL;

//calculate final grade per student
$cleanHelper->calculateFinalGrade();
echo "calculated final grade per student";

echo "Cleaning Datasets finished...".$phpEOL;
