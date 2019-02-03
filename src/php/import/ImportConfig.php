<?php
/**
 * Created by PhpStorm.
 * User: locke
 * Date: 2019-01-13
 * Time: 15:06
 */

class ImportConfig
{
    private $importConfig = array();

    public function __construct()
    {
        // path
        $this->importConfig['path.app'] = __DIR__;

        $this->importConfig['import.files.dir'] = $this->importConfig['path.app'] . '/../../../data/final_import/';
        $this->importConfig['import.file.type'] = '.csv';
        $this->importConfig['import.file.firstline'] = true; // if exists column names ???

        // The following import sequence is important:
        // 1.hzb
        // 2.units
        // 3.abschluss
        // 4.noten

        $this->importConfig['import.files'] = array(
            'hzb' => array(
                'file' => 'pseudohzb',
                'table' => 'hzb'
            ),
            'units' => array(
                'file' => 'units',
                'table' => 'units'
            ),
            'abschluss' => array(
                'file' => 'pseudoabschluss',
                'table' => 'abschluss'
            ),
            'noten' => array(
                'file' => 'pseudonoten',
                'table' => 'noten'
            ),
            'units_equivalence' => array(
                'file' => 'units_equivalence',
                'table' => 'units_equivalence'
            )
        );


        //example: array(tablename array(csv => mysql))
        $this->importConfig['import.column.mapping'] = array(
            'hzb' => array(
                'ID' => 'Student_id',
                'Semester' => 'Semester',
                'HZBNote' => 'HZBNote',
                'Art' => 'Art'
            ),
            'units' => array(
                'Unit' => 'Unit_id',
                'Titel' => 'Titel'
            ),
            'abschluss' => array(
                'ID' => 'Student_id',
                'Semester' => 'Semester',
                'FachEndNote' => 'FachEndNote'
            ),
            'noten' => array(
                'ID' => 'Student_id',
                'Unit' => 'Unit_id',
                'Semester' => 'Semester',
                'Note' => 'Note',
                'BNF' => 'BNF'
            ),
            'units_equivalence' => array(
                'ID (Unit)' => 'Unit_id',
                'ID (2005)' => 'Unit_id_2005',
                'Title (2005)' => 'Titel_2005',
                'Type (2005)' => 'Type_2005',
                'ID (2012)' => 'Unit_id_2012',
                'Title (2012)' => 'Titel_2012',
                'Type (2012)' => 'Type_2012',
                'ID (2017)' => 'Unit_id_2017',
                'Title (2017)' => 'Titel_2017',
                'Type (2017)' => 'Type_2017',
                'ID (Final)' => 'Unit_id_final'
            ),
        );
    }

    public function getConfigValue($key)
    {
        if(isset($this->importConfig[$key])){
            return $this->importConfig[$key];
        } else {
            return false;
        }
    }
}