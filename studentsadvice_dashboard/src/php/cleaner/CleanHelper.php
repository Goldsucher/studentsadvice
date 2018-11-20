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

    public function __construct($dbConn){
        $this->dbConn = $dbConn;
    }

    public function removeNonBachelor(){

        $allNonBachelorIDAbschluss = $this->getAllNonBacholorFromTable('abschluss', 'hzb');
        $allNonBachelorIDNoten = $this->getAllNonBacholorFromTable('noten', 'hzb');

        $this->deleteAllIdsFromTable($allNonBachelorIDAbschluss, "abschluss");
        $this->deleteAllIdsFromTable($allNonBachelorIDNoten, "noten");
    }

    public function deleteAllIdsFromTable($ids, $table) {

        foreach($ids as $id) {
            $this->deleteIdfromTable($id, $table);
        }
    }

    public function deleteIdfromTable($id, $table) {

        $stmt = "DELETE FROM " .$table. " WHERE ID = ". $id;
        $rs = mysqli_query($this->dbConn, $stmt);

        if(!$rs) {
            echo 'stmt: '. $stmt . '<br/>';
            echo 'error: '. $this->dbConn->error . '<br/>';
        }

    }

    public function getAllNonBacholorFromTable($table, $referenzTable){
        $stmt = "SELECT ".$table.".ID FROM ".$table." LEFT JOIN hzb ON ".$referenzTable.".ID = ".$table.".ID WHERE ".$referenzTable.".ID IS NULL";
        $rs = mysqli_query($this->dbConn, $stmt);

        $allNonBachelor = array();
        $i = 0;
        while($row = mysqli_fetch_object($rs))
        {
            foreach ($row as $value) {
                $allNonBachelor[$i] = $value;
            }
            $i++;
        }

        return $allNonBachelor;
    }

    public function markStudentsFromOtherUniversity() {

        $allChangingStudentIds = $this->getChangingStudentsIds();
        $this->updateChangingStudentsByIds($allChangingStudentIds);

    }

    public function updateChangingStudentsByIds($ids){

        foreach($ids as $id) {
            $this->updateChangingStudentById($id);
        }
    }

    public function updateChangingStudentById($id) {

        $stmt = "UPDATE hzb SET wechsel = 1 WHERE hzb.ID = ". $id;
        mysqli_query($this->dbConn, $stmt);
    }

    public function getChangingStudentsIDs() {

        $stmt = "SELECT DISTINCT(hzb.ID) FROM hzb Left JOIN noten ON hzb.ID = noten.ID WHERE hzb.Semester > noten.Semester";
        $rs = mysqli_query($this->dbConn, $stmt);

        $i = 0;
        $students = array();
        while($row = mysqli_fetch_object($rs))
        {
            foreach ($row as $value) {
                $students[$i] = $value;
            }
            $i++;
        }

        return $students;
    }

    public function removeDoubleGradesFromTable($table) {

        $getDoubleGrades = $this->getDoubleGrades($table);

    }

    public function getDoubleGrades($table) {

        //SELECT name, COUNT(*) c FROM table GROUP BY name HAVING c > 1;
        //SELECT ID, Semester, Note, Unit, COUNT(*) FROM noten GROUP BY ID, Semester, Note, Unit HAVING COUNT(*) > 1
        //ORDER BY `noten`.`ID`  ASC
        $stmt = 'SELECT ID, Semester, Note, Unit FROM ' . $table . ' GROUP BY ID, Semester, Note, Unit  HAVING COUNT(*) > 1';
        $rs = mysqli_query($this->dbConn, $stmt);
        $i = 0;
        $grades = array();
        while($row = mysqli_fetch_object($rs))
        {
            foreach ($row as $key => $value) {
                $grades[$i][$key] = $value;
            }
            $i++;
        }

        echo "<pre>";
            var_dump($stmt, $grades);
        echo "</pre>";
        die();
    }

    public function getDoubleGradesAndWriteInExtraTable($table) {

    }
}
