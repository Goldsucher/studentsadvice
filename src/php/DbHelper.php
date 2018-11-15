<?php

class dbHelper
{
    private $dbConn;

    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getAllHzbWithColumnnames($table) {
        $result = array();

        $stmt = "SELECT * FROM ".$table . " ORDER BY ID ASC";
        $rs = mysqli_query($this->dbConn, $stmt);

        $i = 0;
        while($row = mysqli_fetch_object($rs))
        {
            foreach ($row as $key => $value) {
                if($key == "Semester"){
                    $result['data'][$i][$key] = $this->convertSemester($value);
                } else {
                    $result['data'][$i][$key] = $value;
                }

            }

            $i++;
        }
        $result['columns'] = array_keys($result['data'][0]);

        return $result;
    }

    public function getStudentDataWithColumnnames($id, $gradesConditions){
        $student = array();

        //hzb-Table
        $stmt = "SELECT ID, HZBNote, Art, Semester as BeginSemester FROM hzb WHERE ID = ".$id;
        $rs = mysqli_query($this->dbConn, $stmt);

        foreach ((array) mysqli_fetch_object($rs) as $key => $value) {
            if($key == "BeginSemester"){
                $student['hzb'][$key] = $this->convertSemester($value);
            } else {
                $student['hzb'][$key] = $value;
            }
        }

        //abschluss-Table
        $stmt = "SELECT Semester as AbschlussSemester, FachEndNote FROM abschluss WHERE ID = ".$id;
        $rs = mysqli_query($this->dbConn, $stmt);


        $abschluss = (array) mysqli_fetch_object($rs);
        if(!empty($abschluss)){
            foreach ($abschluss as $key => $value) {
                if($key == "AbschlussSemester"){
                    $student['abschluss'][$key] = $this->convertSemester($value);
                } else {
                    $student['abschluss'][$key] = $value;
                }
            }
        } else {
            $student['abschluss'] = NULL;
        }


        //noten-table und units-table
        $stmt = "SELECT DISTINCT noten.Semester, noten.Unit, units.Titel, noten.Note, noten.ID FROM noten INNER JOIN units ON noten.Unit = units.Unit WHERE ID = ".$id;
        if(!empty($gradesConditions)){
           foreach($gradesConditions as $value) {
               $stmt .= " AND noten.Note != '" .$value. "'";
           }
        }
        $stmt .="ORDER BY noten.Semester DESC";
        echo $stmt;
        $rs = mysqli_query($this->dbConn, $stmt);

        $i = 0;
        while($row = mysqli_fetch_object($rs))
        {
            foreach ($row as $key => $value) {
                if($key == "Semester") {
                    $student['noten'][$i][$key] = $this->convertSemester($value);
                } elseif ($key == "Unit") {
                    // get number of course reservation
                    $stmt ="SELECT COUNT(noten.Unit) AS Versuche FROM noten WHERE ID = ".$id." and noten.Unit = ".$value." ORDER BY noten.Semester ASC";
                    $rs2 = mysqli_query($this->dbConn, $stmt);
                    foreach (mysqli_fetch_object($rs2) as $key2 => $value2) {
                        $student['noten'][$i][$key2] = $value2;
                    }
                    $student['noten'][$i][$key] = $value;
                } else {
                    $student['noten'][$i][$key] = $value;
                }

            }
            $i++;
        }

        $student['columns']['hzb'] = array_keys($student['hzb']);
        if(!empty($student['abschluss'])) {
            $student['columns']['abschluss'] = array_keys($student['abschluss']);
        }
        $student['columns']['noten'] = array_keys($student['noten'][0]);

        return $student;
    }

    private function convertSemester($semester) {
        if($semester % 2 == 0) {
            return $semester/2 ." (SoSe)";
        } else {
            return floor($semester/2) . "/" . ceil($semester/2) . " (WiSe)";
        }
    }
}