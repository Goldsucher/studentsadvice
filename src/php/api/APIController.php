<?php
/**
 * Created by PhpStorm.
 * User: locke
 * Date: 2019-01-20
 * Time: 14:27
 */

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/../DbHelper.php');

$dbHelper = new dbHelper($dbConn);

if(isset($_GET['apikey']) && $_GET['apikey'] == $config->getConfigValue("api.key")) {
    if (isset($_GET['table']) && !empty($_GET['table'])) {
        $orderBy = null;
        $orderMode = null;
        if (isset($_GET['orderBy']) && !empty($_GET['orderBy'])) {
            $orderBy = $_GET['orderBy'];
            if (isset($_GET['orderMode']) && !empty($_GET['orderMode'])) {
                $orderMode = $_GET['orderMode'];
            }
        }
        $result = $dbHelper->getSelectAllFromTable($_GET['table'], $orderBy, $orderMode);
        if(!empty($result)) {
            echo json_encode($result);
        }
    }elseif($_GET['diagram'] && !empty($_GET['diagram'])) {
        switch ($_GET['diagram']) {
            case 'timeline_student':
                if (isset($_GET['student']) && ((empty($_GET['student']) && $_GET['student'] == '0') || !empty($_GET['student']))) {
                    //$dbHelper->getAndPrepareTimelineInformationsForAStudent($_GET['student']);
                    echo "<pre>";
                    var_dump($dbHelper->getAndPrepareTimelineInformationsForAStudent($_GET['student']));
                    echo "</pre>";
                } else {
                    echo prepareFailedJsonMsg("no student id given");
                }
                break;

            case 'duration_of_study_dropouts':

                echo json_encode($dbHelper->getDataFromDurationOfStudyByAllDropOut());
                break;

            case 'duration_of_graduation':
                echo json_encode($dbHelper->getDurationOfGraduationAll());
                break;

            case 'grade_distribution_per_semester':
                echo json_encode($dbHelper->getGradeDistributionPerSemester());
                break;

            default:
                echo prepareFailedJsonMsg("wrong diagram mode given");
                break;
        }
    } else {
        echo prepareFailedJsonMsg("diagram or table parameter not exists'");
    }
} else {
    echo prepareFailedJsonMsg("wrong or no api key'");
}

function prepareFailedJsonMsg($msg) {

    $result = array();
    $result['status'] = false;
    $result['content'] = $msg;

    return json_encode($result);
}