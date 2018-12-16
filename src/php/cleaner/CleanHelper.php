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

        $this->getAndDeleteAllNonBacholorFromTable('abschluss', 'hzb');
        $this->getAndDeleteAllNonBacholorFromTable('noten', 'hzb');
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

    public function getAndDeleteAllNonBacholorFromTable($table, $referenzTable){
        $stmt = "SELECT DISTINCT ".$table.".ID FROM ".$table." LEFT JOIN hzb ON ".$referenzTable.".ID = ".$table.".ID WHERE ".$referenzTable.".ID IS NULL";
        $rs = mysqli_query($this->dbConn, $stmt);

        while($row = mysqli_fetch_object($rs))
        {

            foreach ($row as $id) {
                $this->deleteIdfromTable($id, $table);
            }
        }
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

    public function exportAndRemoveDoubleGrades($table, $extraTable){

        $stmt = "DROP TABLe ".$extraTable;
        mysqli_query($this->dbConn, $stmt);

        $stmt = "CREATE TABLE " .$extraTable. " SELECT DISTINCT * FROM ".$table;
        mysqli_query($this->dbConn, $stmt);

        $stmt = "ALTER TABLE ".$table." RENAME temp";
        mysqli_query($this->dbConn, $stmt);

        $stmt = "ALTER TABLE ".$extraTable." RENAME " .$table;
        mysqli_query($this->dbConn, $stmt);

        $stmt = "ALTER TABLE temp RENAME " .$extraTable;
        mysqli_query($this->dbConn, $stmt);
    }

    public function changeColumnValue($table, $column, $oldValue, $newValue){

        $stmt = "UPDATE ".$table. " SET " .$column. " = "."'$newValue'". " WHERE " .$column. " = "."'$oldValue'";
        mysqli_query($this->dbConn, $stmt);
    }

    public function getAllStudentsWithoutGraduation(){

        $stmt = "SELECT DISTINCT(hzb.ID) FROM hzb Left JOIN abschluss ON hzb.ID = abschluss.ID WHERE abschluss.ID IS NULL";
        $rs = mysqli_query($this->dbConn, $stmt);

        if(!$rs) {
            echo "<pre>";
                var_dump($this->dbConn->error);
            echo "</pre>";
            die();

        } else {
            $i = 0;
            $withoutGraduations = array();
            while($row = mysqli_fetch_object($rs))
            {

                foreach ($row as $key => $value) {
                    $withoutGraduations[$i][$key] = $value;
                }
                $i++;
            }
        }




        return $withoutGraduations;
    }

    public function getAbortSemester(){

        $stmt= "SELECT MAX(semester) FROM hzb";
        $rs = mysqli_query($this->dbConn, $stmt);


        $result = null;
        while($row = mysqli_fetch_object($rs))
        {

            foreach ($row as $value) {
                $result =  $value-2;
            }
        }

        return $result;
    }

    public function getDropouts($students, $abortSemester){

        $i=0;
        $dropouts = array();
        foreach($students as $student) {
            $stmt = "SELECT MAX(Semester) as lastActiveSemester FROM noten WHERE ID=" . $student['ID'];
            $rs = mysqli_query($this->dbConn, $stmt);

            while ($row = mysqli_fetch_object($rs)) {
                foreach ($row as $key => $lastActiveSemester) {
                    if ((int)$lastActiveSemester <= $abortSemester) {

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

    public function markDropouts($allDropouts){
        foreach($allDropouts as $dropout){
            $stmt = "UPDATE hzb SET abbruch = 1 WHERE ID =" .$dropout['ID'];
            mysqli_query($this->dbConn, $stmt);
        }
    }



    public function markAllCollegeDropout() {

        $studentsWithoutGraduation = $this->getAllStudentsWithoutGraduation();
        $abortSemester = $this->getAbortSemester();
        $allDropouts = $this->getDropouts($studentsWithoutGraduation, $abortSemester);
        $this->markDropouts($allDropouts);
    }
}
