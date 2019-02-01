<?php

require_once(__DIR__.'/../setup.inc.php');
require_once(__DIR__.'/ImportHelper.php');
require_once('ImportConfig.php');

$importHelper = new ImportHelper($importdbConn);
$importConfig = new ImportConfig();

// New Line: Differentiation between CLI and web browser
if (php_sapi_name() == "cli") {
    $phpEOL = PHP_EOL;
} else {
    $phpEOL = '<br/>';
}

foreach($importConfig->getConfigValue('import.files') as $key => $item){

    $filePath = $importConfig->getConfigValue('import.files.dir').$item['file'].$importConfig->getConfigValue('import.file.type');

    if(file_exists($filePath)) {
        try{

            $importHelper->writeCSVinTableLinewise($filePath, $item['table'], $importConfig->getConfigValue("import.column.mapping")[$key], $importConfig->getConfigValue("import.file.firstline"));
            echo 'file: '.$item['file'].$importConfig->getConfigValue('import.file.type').' | table: '.$item['table'].' - import successfull'.$phpEOL;

        } catch (Exception $e ) {
            echo 'file: '.$item['file'].$importConfig->getConfigValue('import.file.type').' | table: '.$item['table'].' - import failed'.$phpEOL;
            continue;
        }
    }
}
