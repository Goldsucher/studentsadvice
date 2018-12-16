<?php

class ImportHelper
{
    private $dbConn = null;

    public function __construct($dbConn){
        $this->dbConn = $dbConn;
    }

    public function writeCSVinTableLinewise($filePath, $table){

        $this->truncateTable($table);

        $columns =array();
        $fileHandle = fopen($filePath, "r");
        $firstLine = true;
        $i=0;

        while (!feof($fileHandle)) {
            $line = fgets($fileHandle);
            $tmp = explode(';', $line);
            if($firstLine){
                $columns = $tmp;
                $firstLine = false;
            } elseif (!$firstLine) {
                $j = 0;
                foreach($columns as $col){
                    $rowContent[trim($col)] = trim($tmp[$j]);
                    $j++;
                }

                //do mysql query
                $query = $this->buildInsertString($rowContent, $table);
                $result = mysqli_query($this->dbConn, $query);

                if(!$result){
                    if(strstr($this->dbConn->error, 'Duplicate entry')){
                        $errors['duplicates'][] = $this->dbConn->error;
                        echo "<pre>";
                            var_dump($query,$this->dbConn->error);
                        echo "</pre>";
                        continue;
                    } else {
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

        $query = ("TRUNCATE TABLE ". $table);
        mysqli_query($this->dbConn, $query);
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
}