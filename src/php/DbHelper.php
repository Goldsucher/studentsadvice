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
        $student['abschluss'] = $this->checkFinalExam($id);


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
                    $student['noten'][$i][$key] = $value;
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
        $stmt .="ORDER BY noten.Semester";
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

            $timeline['data'][$this->convertSemesterOnlyear($semester)] = $semesterData;
            $timeline['abschluss'] =$this->checkFinalExam($studentId);
        }

        return $timeline;
    }

    public function checkFinalExam($studentId) {
        $student = array();

        $stmt = "SELECT Semester as AbschlussSemester, FachEndNote FROM abschluss WHERE ID = ".$studentId;
        $rs = mysqli_query($this->dbConn, $stmt);


        $abschluss = (array) mysqli_fetch_object($rs);
        if(!empty($abschluss)){
            foreach ($abschluss as $key => $value) {
                if($key == "AbschlussSemester"){
                    $student[$key] = $this->convertSemester($value);
                } else {
                    $student[$key] = $value;
                }
            }
        } else {
            $student = NULL;
        }

        return $student;
    }

    public function removeDuplicateCoursesForTimeline($semesterData){
        $tmpData = array();
        $unique = array();

        $expulsion = array('NT', 'belegt', 'o.E.');

        foreach($semesterData as $index => $content) {
            if(in_array($content['Titel'], $unique)){
                $tmpDataIndex = array_search($content['Titel'],$unique);
                if(in_array($tmpData[$tmpDataIndex]['Note'], $expulsion)){
                    $tmpData[$tmpDataIndex]= $content;
                }
            } else {
                $unique[] = $content['Titel'];
                $tmpData[]= $content;
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
                   $result[$i][$key] = $value;
                   $result[$i]["Versuche"] = $this->getNumberOfAttemptsPerSemester($studentId, $value, $semester);
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

    public function getNumberOfTakenCoursesPerSemester($student) {

        $numberOfTakenCourses = array();
        $activeSemesters = $this->getAllActiveSemesters($student);


        foreach($activeSemesters as $semester) {

            $stmt = "SELECT COUNT(DISTINCT Unit) as count FROM `noten` WHERE `ID` = ".$student." AND `Semester` = ".$semester;
            $rs = mysqli_query($this->dbConn, $stmt);

            $numberOfTakenCourses['semester'][] = [$this->convertSemester($semester)];
            $numberOfTakenCourses['numberOfCourses'][]   = mysqli_fetch_object($rs)->count;
        }

        return $numberOfTakenCourses;
    }

    public function getNumberOfPassedCoursesPerSemester($student){

        $numberOfPassedCourses = array();
        $activeSemesters = $this->getAllActiveSemesters($student);
        $criteria = array("NT","5,0","belegt", "o.E.", "PR", "e.n.b.");

        foreach($activeSemesters as $semester) {

            $stmt = "SELECT COUNT(DISTINCT Unit) as count FROM `noten` WHERE `ID` = ".$student." AND `Semester` = ".$semester;
            foreach ($criteria as $criterion) {
                $stmt .= " AND Note != "."'$criterion'";
            }

            $rs = mysqli_query($this->dbConn, $stmt);

            $numberOfPassedCourses['semester'][] = [$this->convertSemester($semester)];
            $numberOfPassedCourses['numberOfCourses'][]   = mysqli_fetch_object($rs)->count;
        }

        return $numberOfPassedCourses;

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

    public function getGradeInformations(){
        $stmt = 'SELECT DISTINCT(Plansemester) FROM `units_extension` WHERE 1 ORDER BY `units_extension`.`Plansemester` ASC';
        $rs = mysqli_query($this->dbConn, $stmt);

        $sems = array();
        $grades = array();
        if (!$rs) {
            var_dump($this->dbConn->error);
            die();
        } else {
            while ($semesters = mysqli_fetch_object($rs)) {
                foreach ($semesters as $semester) {
                       $sems[] = $semester;
                }
            }
        }

        foreach ($sems as $sem) {
            $stmt2 = null;
            $stmt2 = "SELECT units_extension.*, units.Unit_id, units.Titel FROM units_extension JOIN units ON units.Unit_ID = units_extension.Unit_id WHERE units_extension.Durchschnittsnote != '0.00' AND Plansemester = '".$sem."'";
            $rs2 = mysqli_query($this->dbConn, $stmt2);

            if (!$rs2) {
                var_dump($this->dbConn->error);
                die();

            } else {
                $i = 0;
                while ($data = mysqli_fetch_object($rs2)) {
                    foreach ($data as $key  => $value) {
                        $grades[$sem][$i][$key] = $value;
                    }
                    $i++;
                }
            }
        }

        return $grades;
    }
}