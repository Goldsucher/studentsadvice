<?php

class ImportHelper
{
    private $dbConn = null;

    public function __construct($dbConn){
        $this->dbConn = $dbConn;
    }

    public function importDataInTable($filePath, $table){

        $errors = array();
        foreach($this->getFileData($filePath) as $item){
            if($table == "hzb"){
                if($item['HZBNote'] === "" ){
                    $item['HZBNote'] = '5';
                }
            }

            $query = $this->buildInsertString($item, $table);
            $result = mysqli_query($this->dbConn, $query);

            if(!$result){
                if(strstr($this->dbConn->error, 'Duplicate entry')){
                    $errors['duplicates'][] = $this->dbConn->error;
                    continue;
                } else {
                    $errors[] = $this->dbConn->error;
                    $errors['status'] = false;
                    return $errors;
                }
            }
        }
        $errors['status'] = true;

        return $errors;
    }

    private function buildInsertString($data, $table){

        $columns = "";
        $values = "";
        $insertString = "";

        foreach($data as $col => $val){
            $columns .= "`".$col."`,";
            $values .= "'".$val."',";
        }

        $columns = substr($columns,0, -1);
        $values = substr($values,0,-1);

        $insertString .= "INSERT INTO ". $table . "(". $columns . ") VALUES (". $values .");";

        return $insertString;
    }

    public function getFileData($filePath){
        $fileContent = array();
        $columns =array();

        $fileHandle = fopen($filePath, "r");
        $firstLine = true;
        $i=0;
        while (!feof($fileHandle)) {
            if($firstLine){
                $line = fgets($fileHandle);
                $columns = explode(';', $line);
                $firstLine = false;
            } elseif (!$firstLine) {
                $line = fgets($fileHandle);
                $tmp = explode(';', $line);
                $j = 0;
                foreach($columns as $col){
                    $fileContent[$i][trim($col)] = trim($tmp[$j]);
                    $j++;
                }
            }
            $i++;
        }
        fclose($fileHandle);

        return $fileContent;
    }

}