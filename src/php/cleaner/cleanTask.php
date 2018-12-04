<?php

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/../cleaner/CleanHelper.php');



$cleanHelper = new CleanHelper($dbConn);

//remove remove non-bachelor students
$cleanHelper->removeNonBachelor();

//Mark students who have visited another university before
$cleanHelper->markStudentsFromOtherUniversity();

//remove double grades from noten-Table
$cleanHelper->exportAndRemoveDoubleGrades('noten', 'doppelteNoten');

//change BNF E to 6 in noten-Tablle
$cleanHelper->changeColumnValue("noten", "BNF", "E", "6");
