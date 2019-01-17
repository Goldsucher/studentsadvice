<?php
/**
 * Created by PhpStorm.
 * User: locke
 * Date: 14.11.18
 * Time: 09:46
 */

class CleanHelper
{
    private $dbConn = null;

    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function removeNonBachelor()
    {

        $this->getAndDeleteAllNonBacholorFromTable('abschluss', 'hzb');
        $this->getAndDeleteAllNonBacholorFromTable('noten', 'hzb');
    }

    public function deleteAllIdsFromTable($ids, $table)
    {

        foreach ($ids as $id) {
            $this->deleteIdfromTable($id, $table);
        }
    }

    public function deleteIdfromTable($id, $table)
    {

        $stmt = "DELETE FROM " . $table . " WHERE Student_id = " . $id;
        $rs = mysqli_query($this->dbConn, $stmt);

        if (!$rs) {
            echo 'stmt: ' . $stmt . '<br/>';
            echo 'error: ' . $this->dbConn->error . '<br/>';
        }

    }

    public function getAndDeleteAllNonBacholorFromTable($table, $referenzTable)
    {
        $stmt = "SELECT DISTINCT " . $table . ".Student_id FROM " . $table . " LEFT JOIN hzb ON " . $referenzTable . ".Student_id = " . $table . ".Student_id WHERE " . $referenzTable . ".Student_id IS NULL";
        $rs = mysqli_query($this->dbConn, $stmt);

        while ($row = mysqli_fetch_object($rs)) {

            foreach ($row as $id) {
                $this->deleteIdfromTable($id, $table);
            }
        }
    }

    public function markStudentsFromOtherUniversity()
    {

        $allChangingStudentIds = $this->getChangingStudentsIds();
        $this->updateChangingStudentsByIds($allChangingStudentIds);
    }

    public function updateChangingStudentsByIds($ids)
    {

        foreach ($ids as $id) {
            $this->updateChangingStudentById($id);
        }
    }

    public function updateChangingStudentById($id)
    {

        $stmt = "UPDATE hzb_extension SET Wechsel = 1 WHERE hzb_extension.Student_id = " . $id;
        mysqli_query($this->dbConn, $stmt);
    }

    public function getChangingStudentsIDs()
    {

        $stmt = "SELECT DISTINCT(hzb.Student_id) FROM hzb Left JOIN noten ON hzb.Student_id = noten.Student_id WHERE hzb.Semester > noten.Semester";
        $rs = mysqli_query($this->dbConn, $stmt);

        $i = 0;
        $students = array();
        while ($row = mysqli_fetch_object($rs)) {
            foreach ($row as $value) {
                $students[$i] = $value;
            }
            $i++;
        }

        return $students;
    }

    public function exportAndRemoveDoubleGrades($table, $extraTable)
    {

        $stmt = "DROP TABLE " . $extraTable;
        mysqli_query($this->dbConn, $stmt);

        $stmt = "CREATE TABLE " . $extraTable . " SELECT DISTINCT * FROM " . $table;
        mysqli_query($this->dbConn, $stmt);

        $stmt = "ALTER TABLE " . $table . " RENAME temp";
        mysqli_query($this->dbConn, $stmt);

        $stmt = "ALTER TABLE " . $extraTable . " RENAME " . $table;
        mysqli_query($this->dbConn, $stmt);

        $stmt = "ALTER TABLE temp RENAME " . $extraTable;
        mysqli_query($this->dbConn, $stmt);
    }

    public function changeColumnValue($table, $column, $oldValue, $newValue)
    {

        $stmt = "UPDATE " . $table . " SET " . $column . " = " . "'$newValue'" . " WHERE " . $column . " = " . "'$oldValue'";
        mysqli_query($this->dbConn, $stmt);
    }

    public function getAllStudentsWithoutGraduation() {
        $stmt = "SELECT DISTINCT(hzb.Student_id) FROM hzb Left JOIN abschluss ON hzb.Student_id = abschluss.Student_id WHERE abschluss.Student_id IS NULL";
        $rs = mysqli_query($this->dbConn, $stmt);

        if (!$rs) {
            echo "<pre>";
            var_dump($this->dbConn->error);
            echo "</pre>";
            die();

        } else {
            $i = 0;
            $withoutGraduations = array();
            while ($row = mysqli_fetch_object($rs)) {

                foreach ($row as $key => $value) {
                    $withoutGraduations[$i][$key] = $value;
                }
                $i++;
            }
        }

        return $withoutGraduations;
    }

    public function getAbortSemester()
    {
        $stmt = "SELECT MAX(semester) FROM hzb";
        $rs = mysqli_query($this->dbConn, $stmt);


        $result = null;
        while ($row = mysqli_fetch_object($rs)) {

            foreach ($row as $value) {
                $result = $value - 2;
            }
        }

        return $result;
    }

    public function getDropouts($students, $abortSemester)
    {

        $i = 0;
        $dropouts = array();
        foreach ($students as $student) {
            $stmt = "SELECT MAX(Semester) as lastActiveSemester FROM noten WHERE Student_id=" . $student['Student_id'];
            $rs = mysqli_query($this->dbConn, $stmt);

            while ($row = mysqli_fetch_object($rs)) {
                foreach ($row as $key => $lastActiveSemester) {
                    if ((int)$lastActiveSemester < $abortSemester) {

                        $dropouts[$i] = $student;
                        $dropouts[$i][$key] = $lastActiveSemester;
                        $dropouts[$i]['abortSemester'] = $abortSemester;
                    }

                }
            }
            $i++;
        }


        return $dropouts;
    }

    public function markDropouts($allDropouts)
    {
        foreach ($allDropouts as $dropout) {
            $stmt = "UPDATE hzb_extension SET Abbruch = 1 WHERE hzb_extension.Student_id =" . $dropout['Student_id'];
            mysqli_query($this->dbConn, $stmt);
        }
    }


    public function markAllCollegeDropout()
    {

        $studentsWithoutGraduation = $this->getAllStudentsWithoutGraduation();
        $abortSemester = $this->getAbortSemester();
        $allDropouts = $this->getDropouts($studentsWithoutGraduation, $abortSemester);
        $this->markDropouts($allDropouts);
    }

    public function identfyCommonCore($filePath)
    {
        $fileHandle = fopen($filePath, "r");

        while (!feof($fileHandle)) {
            $id = trim(fgets($fileHandle));
            $this->markElective($id);
        }
        fclose($fileHandle);
    }

    public function markElective($id) {

        $stmt = "UPDATE units_extension SET Wahlpflicht = 1 WHERE units_extension.Unit_id =" . $id;
        mysqli_query($this->dbConn, $stmt);
    }

    public function calculateAverageGrade($table, $col, $extTable, $destAvgCol, $withfiveDotZero = false) {

        $ids = $this->getAllEntriesByGivenColumnAndTable($col, $table);

        foreach ($ids as $id) {
            if($withfiveDotZero == false) {
                $stmt = 'SELECT AVG(Note) as average FROM noten WHERE '.$col.'='.$id.' AND Note NOT IN("belegt", "NT","5") and BNF = "5"';
            } elseif ($withfiveDotZero == true) {
                $stmt = 'SELECT AVG(Note) as average FROM noten WHERE '.$col.'='.$id.' AND Note NOT IN("belegt", "NT") and BNF = "5"';
            }

            $rs = mysqli_query($this->dbConn, $stmt);

            if (!$rs) {
                echo "<pre>";
                 var_dump($this->dbConn->error);
                echo "</pre>";
                die();

            } else {
                while ($avg = mysqli_fetch_object($rs)) {
                    foreach ($avg as  $v) {
                        if(!empty($v)) {
                            $stmt = 'UPDATE '.$extTable.' SET '.$destAvgCol.' = '.$v.' WHERE '.$extTable.'.'.$col.' = ' . $id;
                            mysqli_query($this->dbConn, $stmt);

                        }
                    }
                }
            }
        }
    }

    public function getAllEntriesByGivenColumnAndTable($col, $table) {
        $stmt = "SELECT ".$col." FROM ".$table;
        $rs = mysqli_query($this->dbConn, $stmt);

        $ids = array();

        if (!$rs) {
            echo "<pre>";
            var_dump($this->dbConn->error);
            echo "</pre>";
            die();

        } else {
            while ($data = mysqli_fetch_object($rs)) {
                foreach ($data as $value) {
                    if(!empty($value)) {
                        $ids[] = $value;
                    }
                }
            }
        }

        return $ids;
    }

    public function specialCustomizations() {

        //Table: abschluss // change Semester value from 3901 to 4024 - Student_id = 1425
        $stmt = "UPDATE abschluss SET Semester = '4024' WHERE Semester = '3901' AND Student_id = 1425";
        mysqli_query($this->dbConn, $stmt);

        //Table: hzb // change Semester value from 4034 to 4035 - Student_id = 407
        $stmt = "UPDATE hzb SET Semester = '4035' WHERE Semester = '4034' AND Student_id = 407";
        mysqli_query($this->dbConn, $stmt);

        //Table: hzb // change Semester value from 4034 to 4035 - Student_id = 2678
        $stmt = "UPDATE hzb SET Semester = '4035' WHERE Semester = '4034' AND Student_id = 2678";
        mysqli_query($this->dbConn, $stmt);

        //Table: hzb // change Semester value from 4028 to 4030 - Student_id = 2380
        $stmt = "UPDATE hzb SET Semester = '4030' WHERE Semester = '4028' AND Student_id = 2380";
        mysqli_query($this->dbConn, $stmt);

    }

    public function prepareExtensionTables() {
        $this->truncateTable('hzb_extension');
        $numberOfColumns =$this->getNumberOfColumns('hzb_extension');
        $this->insertIdsFromMainTable('hzb', 'hzb_extension', 'Student_id', $numberOfColumns);

        $this->truncateTable('units_extension');
        $numberOfColumns =$this->getNumberOfColumns('units_extension');
        $this->insertIdsFromMainTable('units','units_extension', 'Unit_id',$numberOfColumns);
    }

    public function calculateFinalGrade(){
        $results = $this->getInformationForCalculateFinalGrade();

        foreach ($results as $result) {
            $finalGrade = ((float) $result['FachEndNote'] + (float) $result['Durchschnittsnote']) / 2 ;

            $stmt = "UPDATE hzb_extension SET EndNote = ".$finalGrade." WHERE hzb_extension.Student_id = " . $result['id'];
            mysqli_query($this->dbConn, $stmt);
        }
    }

    public function getInformationForCalculateFinalGrade() {

        $stmt = "SELECT abschluss.Student_id as id, abschluss.FachEndNote, hzb_extension.Durchschnittsnote FROM abschluss JOIN hzb_extension ON hzb_extension.Student_id = abschluss.Student_id";
        $rs = mysqli_query($this->dbConn, $stmt);

        $result = array();

        if (!$rs) {
            echo "<pre>";
            var_dump($this->dbConn->error);
            echo "</pre>";
            die();

        } else {
            $i = 0;
            while ($data = mysqli_fetch_object($rs)) {
                foreach ($data as $key => $value) {
                    if(!empty($value)) {
                        $result[$i][$key] = $value;
                    }
                }
                $i++;
            }
        }

        return $result;
    }

    public function setScheduledSemester($file){
        $fileHandle = fopen($file, "r");

        while (!feof($fileHandle)) {
            $data = explode(';',trim(fgets($fileHandle)));

            $stmt = "UPDATE units_extension SET Plansemester = '".$data[1]."' WHERE units_extension.Unit_id = " . $data[0];
            if(mysqli_query($this->dbConn, $stmt) == false){
                var_dump($data, $this->dbConn->error);
                die();
            }

        }
        fclose($fileHandle);
    }

    private function truncateTable($table) {
        $stmt = ("SET FOREIGN_KEY_CHECKS = 0");
        mysqli_query($this->dbConn, $stmt);
        $stmt = ("TRUNCATE TABLE ". $table);
        mysqli_query($this->dbConn, $stmt);
        $stmt = ("SET FOREIGN_KEY_CHECKS = 1");
        mysqli_query($this->dbConn, $stmt);
    }

    private function getNumberOfColumns($table){
        $stmt = "SHOW COLUMNS FROM ".$table;
        $rs = mysqli_query($this->dbConn, $stmt);

        return $rs->num_rows;
    }

    private function insertIdsFromMainTable($main, $ext, $idNAme, $numOfCols) {
        $stmt = ("SELECT ".$idNAme. " FROM " .$main);
        $rs = mysqli_query($this->dbConn, $stmt);

        if (!$rs) {
            var_dump($this->dbConn->error);
            die();

        } else {
            while ($data = mysqli_fetch_object($rs)) {
                foreach ($data as  $value) {
                   $this->buildInsertStringForExtensionTable($ext, $value, $numOfCols);
                }
            }
        }
    }

    private function buildInsertStringForExtensionTable($table, $id, $numOfCols){
        $values = $id;

        for($i = 0; $i < $numOfCols-1; $i++) {
            $values .= ",'0'";
        }

        $stmt = 'INSERT INTO '. $table. ' VALUES ('.$values.')';
        mysqli_query($this->dbConn, $stmt);
    }
}
