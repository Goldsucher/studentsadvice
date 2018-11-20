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
        $student = array('ID' => $id);

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
        $rs = mysqli_query($this->dbConn, $stmt);

        $i = 0;
        while($row = mysqli_fetch_object($rs))
        {
            foreach ($row as $key => $value) {
                if($key == "Semester") {
                    $student['noten'][$i][$key] = $this->convertSemester($value);
                    $student['noten'][$i]['origSemester'] = $value;
                } elseif ($key == "Unit") {
                    // get number of course reservation
                    $student['noten'][$i]['Versuche'] = $this->getNumberOfAttempts($id, $value);
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

    public function getNumberOfAttempts($studentId, $unit){
        $stmt ="SELECT COUNT(noten.Unit) AS Versuche FROM noten WHERE ID = ".$studentId." AND noten.Unit = ".$unit." ORDER BY noten.Semester ASC";
        $rs = mysqli_query($this->dbConn, $stmt);

        return mysqli_fetch_object($rs)->Versuche;

    }

    public function getNumberOfAttemptsPerSemester($studentId, $unit, $semester){
        $stmt ="SELECT COUNT(noten.Unit) AS Versuche FROM noten WHERE ID = ".$studentId." AND noten.Unit = ".$unit."  AND noten.Semester = ".$semester." ORDER BY noten.Semester ASC";
        $rs = mysqli_query($this->dbConn, $stmt);

        return mysqli_fetch_object($rs)->Versuche;

    }

    public function getStudentMoreDetails($studentId, $details){
        $result = array();

        $stmt = "SELECT DISTINCT noten.Semester, noten.Unit, units.Titel, noten.Note, noten.ID FROM noten INNER JOIN units ON noten.Unit = units.Unit WHERE ID = ".$studentId;
        if(!empty($details)){
            foreach($details as $key => $detail) {
                if($detail) {
                    $stmt .= " AND noten.".$key." = '" .$detail. "'";
                }
            }
        }
        $stmt .="ORDER BY noten.Semester DESC";
        $rs = mysqli_query($this->dbConn, $stmt);

        $i = 0;
        while($row = mysqli_fetch_object($rs)) {
            foreach ($row as $key => $value) {
                if($key == "Semester") {
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

    public function getAndPrepareTimelineInformationsForAStudent($studentId){
        $timeline = array();
        $semesters = $this->getAllActiveSemesters($studentId);

        foreach($semesters as $semester){
            $semesterData = $this->getGradeInformationensForAStudent($studentId, $semester);
            $semesterData = $this->removeDuplicateCoursesForTimeline($semesterData);


            $timeline[$this->convertSemesterOnlyear($semester)] = $semesterData;

        }

        return $timeline;
    }

    public function removeDuplicateCoursesForTimeline($semesterData){
        $tmpData = array();
        $unique = array();
        foreach($semesterData as $index => $content) {
            if(in_array($content['Titel'], $unique)){
                    //ToDo richtigen Content löschen
            } else {
                $unique[] = $content['Titel'];
                $tmpData []= $content;
            }

        }

        return $tmpData;
    }

    public function getGradeInformationensForAStudent($studentId, $semester) {
        $result = array();

        $stmt = "SELECT units.Titel, noten.Note, noten.Unit FROM noten LEFT JOIN units ON units.Unit = noten.Unit WHERE noten.ID = ".$studentId. " AND noten.Semester = ".$semester . " ORDER BY noten.Semester, units.Titel";
        $rs = mysqli_query($this->dbConn, $stmt);

        $i = 0;
        while($row = mysqli_fetch_object($rs)) {
            foreach ($row as $key => $value) {
               if($key == "Unit") {
                   $attemps = $this->getNumberOfAttemptsPerSemester($studentId, $value, $semester);
                   $result[$i]["Versuche"] = $attemps;
                    //$result[$i]["Versuche"] = $this->getNumberOfAttempts($studentId, $value);
                } else {
                    $result[$i][$key] = $value;
                }
            }
            $i++;
        }

        return $result;
    }

    public function getAllActiveSemesters($studentId){

        $semesters = array();

        $stmt = "SELECT DISTINCT Semester FROM noten WHERE noten.ID = ".$studentId;
        $rs = mysqli_query($this->dbConn, $stmt);

        while($row = mysqli_fetch_object($rs)) {
            $semesters[] =  $row->Semester;
        }

        return $semesters;
    }

    private function convertSemester($semester) {
        if($semester % 2 == 0) {
            return $semester/2 ." (SoSe)";
        } else {
            return floor($semester/2) . "/" . ceil($semester/2) . " (WiSe)";
        }
    }

    private function convertSemesterOnlyear($semester) {
        if($semester % 2 == 0) {
            return $semester/2;
        } else {
            return floor($semester/2) . "/" . ceil($semester/2)." (WS)";
        }
    }
}