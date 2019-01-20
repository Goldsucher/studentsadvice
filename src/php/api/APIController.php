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

if(isset($_GET['mode']) && !empty($_GET['mode'])) {
    if ($_GET['mode'] == 'all') {

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
            echo json_encode($result);
        } else {

            echo prepareFailedJsonMsg("Missing table parameter");
        }

    } elseif($_GET['mode'] == 'query') {

        if (isset($_GET['stmt']) && !empty($_GET['stmt'])) {
            $result = $dbHelper->executeQuery($_GET['stmt']);
        } else {
            echo prepareFailedJsonMsg("no query statement given");
        }

    } elseif($_GET['mode'] == 'where') {

        if (isset($_GET['table']) && !empty($_GET['table'])) {
            $orderBy = null;
            $orderMode = null;
            if (isset($_GET['orderBy']) && !empty($_GET['orderBy'])) {
                $orderBy = $_GET['orderBy'];
                if (isset($_GET['orderMode']) && !empty($_GET['orderMode'])) {
                    $orderMode = $_GET['orderMode'];
                }
            }
            if(isset($_GET['where']) && !empty($_GET['where'])) {
                $result = $dbHelper->getSelectFromTableWithGivenWhere($_GET['table'], $_GET['where'],$orderBy, $orderMode);
            } else {
                echo prepareFailedJsonMsg("no where clause given");
            }


        } else {
            echo prepareFailedJsonMsg("Missing table parameter");
        }

    } else {
        echo prepareFailedJsonMsg("This mode does not exist'");
    }
    echo json_encode($result);
} else {
    echo prepareFailedJsonMsg("no parameter 'mode'");
}

function prepareFailedJsonMsg($msg) {

    $result = array();
    $result['status'] = false;
    $result['content'] = $msg;

    return json_encode($result);
}