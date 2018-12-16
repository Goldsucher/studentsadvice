<?php

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/../import/importHelper.php');

$importHelper = new ImportHelper($dbConn);

foreach($config->getConfigValue('import.files') as $key => $item){

    $filePath = $config->getConfigValue('import.files.dir').$item['file'].$config->getConfigValue('import.file.type');

    if(file_exists($filePath)) {
        $import =$importHelper->writeCSVinTableLinewise($filePath, $item['table']);

        if($import){
            echo 'file: '.$item['file'].' | table: '.$item['table'].' - import successfull\n';
        } else {
            echo 'file: '.$item['file'].' | table: '.$item['table'].' - import failed\n';
        }
    }
}