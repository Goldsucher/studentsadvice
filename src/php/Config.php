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

        // paths
        $this->config['path.app'] = __DIR__;

        //smarty
        $this->config['path.smarty.templates'] = $this->config['path.app']. '/../../templates/';
        $this->config['path.smarty.cache'] = $this->config['path.app']. '/../../temp/smarty/cache/';
        $this->config['path.smarty.compile'] = $this->config['path.app']. '/../../temp/smarty/templates_c/';

    }

    public function getConfigValue($key) {
        if(isset($this->config[$key])){
            return $this->config[$key];
        } else {
            return false;
        }
    }

}