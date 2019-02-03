<?php

class ImportHelper
{
    private $dbConn = null;

    public function __construct($dbConn){
        $this->dbConn = $dbConn;
    }

    public function writeCSVinTableLinewise($filePath, $table, $mapper, $firstLine = true){

        $this->truncateTable($table);

        $columns =array();
        $fileHandle = fopen($filePath, "r");
        $i=0;

        $maxCounter= 100;
        $counter = 0;
        $increment = 100;

        while (!feof($fileHandle)) {
            $line = fgets($fileHandle);
            $tmpData = explode(';', $line);
            if($firstLine){
                $columns = $this->columnNameMapping($tmpData, $mapper);
                $firstLine = false;
            } elseif (!$firstLine) {
                $j = 0;
                foreach($columns as $col){
                    $rowContent[trim($col)] = trim($tmpData[$j]);
                    $j++;
                }

                //do mysql query
                $query = $this->buildInsertString($rowContent, $table);
                $result = mysqli_query($this->dbConn, $query);
                $counter++;
                if($counter == $maxCounter){
                    echo $counter. "imports". PHP_EOL;
                    $maxCounter += $increment;
                }

                if(!$result){
                    if(strstr($this->dbConn->error, 'Duplicate entry')){
                        $errors['duplicates'][] = $this->dbConn->error;
                            var_dump($query,$this->dbConn->error);
                        continue;
                    } else {
                        var_dump($this->dbConn->error);
                        $errors[] = $this->dbConn->error;
                        $errors['status'] = false;
                        $errors['numberOfImports'] = $i;
                        return $errors;
                    }
                }
            }
            $i++;
        }
        fclose($fileHandle);

        return true;
    }

    public function truncateTable($table){

        $query = ("SET FOREIGN_KEY_CHECKS = 0");
        mysqli_query($this->dbConn, $query);
        $query = ("TRUNCATE TABLE ". $table);
        mysqli_query($this->dbConn, $query);
        $query = ("SET FOREIGN_KEY_CHECKS = 1");
        mysqli_query($this->dbConn, $query);

    }

    private function columnNameMapping($data, $mapper) {

        foreach($data as $key => $value) {
            $data[$key] = $mapper[trim($value)];
        }

        return $data;
    }

    private function buildInsertString($data, $table){

        $columns = "";
        $values = "";
        $insertString = "";

        foreach($data as $col => $val){
            $columns .= "`".$col."`,";
            if($table == "noten") {
                $val = str_replace(',', '.', $val);
            }
            $values .= "'".$val."',";
        }

        $columns = substr($columns,0, -1);
        $values = substr($values,0,-1);

        $insertString .= "INSERT INTO ". $table . "(". $columns . ") VALUES (". $values .");";

        return $insertString;
    }
}
