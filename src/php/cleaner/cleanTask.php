<?php

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/../cleaner/CleanHelper.php');

if (php_sapi_name() == "cli") {
    $phpEOL = PHP_EOL;
} else {
    $phpEOL = '<br/>';
}


$cleanHelper = new CleanHelper($dbConn);
echo "Cleaning Datasets started...".$phpEOL;

//remove non-bachelor students
$cleanHelper->removeNonBachelor();
echo "removed all non bachelor...".$phpEOL;


//Mark students who have visited another university before
$cleanHelper->markStudentsFromOtherUniversity();
echo "marked all students, who have visited another university before..." . $phpEOL;

//Mark all college dropouts
$cleanHelper->markAllCollegeDropout();
echo "marked all college dropouts..." . $phpEOL;

//remove double grades from noten-Table
$cleanHelper->exportAndRemoveDoubleGrades('noten', 'doppelteNoten');
echo "removed double grades from noten-table...".$phpEOL;

//change BNF E to 6 in noten-table
$cleanHelper->changeColumnValue("noten", "BNF", "E", "6");
echo "changed BNF E to 6 from noten-table...".$phpEOL;

echo "Cleaning Datasets finished...".$phpEOL;
