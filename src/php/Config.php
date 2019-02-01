<?php

class Config
{

    private $config = array();

    public function __construct()
    {
        // Database configuration
        $this->config['db.mysql.host'] = '127.0.0.1';
        $this->config['db.mysql.name'] = 'uni_students_advice';
        $this->config['db.mysql.user'] = 'user';
        $this->config['db.mysql.pass'] = 'user123';

        // Database configuration
        $this->config['import.mysql.host'] = '127.0.0.1';
        $this->config['import.mysql.name'] = 'uni_students_advice';
        $this->config['import.mysql.user'] = 'user';
        $this->config['import.mysql.pass'] = 'user123';

        // paths
        $this->config['path.app'] = __DIR__;

        //smarty
        $this->config['path.smarty.templates'] = $this->config['path.app']. '/../../templates/';
        $this->config['path.smarty.cache'] = $this->config['path.app']. '/../../temp/smarty/cache/';
        $this->config['path.smarty.compile'] = $this->config['path.app']. '/../../temp/smarty/templates_c/';

        $this->config['api.key'] = "264c8c381bf16c982a4e59b0dd4c6f7808c51a05f64c35db42cc78a2a72875bb"; // student sha256
    }

    public function getConfigValue($key) {
        if(isset($this->config[$key])){
            return $this->config[$key];
        } else {
            return false;
        }
    }

}