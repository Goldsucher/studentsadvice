<?php

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/../import/importHelper.php');

$importHelper = new ImportHelper($dbConn);

foreach($config->getConfigValue('import.files') as $key => $item){
    $filePath = $config->getConfigValue('import.files.dir').$item['file'].$config->getConfigValue('import.file.type');

    if(file_exists($filePath)) {
        echo "<pre>";
            var_dump($item['table']);
        echo "</pre>";
        $import =$importHelper->importDataInTable($filePath, $item['table']);
        if($import['status']){
            echo 'table: '.$item['table'].' - import successfull<br/>';
        } else {
            echo 'table: '.$item['table'].' - import failed<br/>';
            echo "<pre>";
                var_dump($import);
            echo "</pre>";
        }
    }


}