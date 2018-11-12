<?php

require_once("Config.php");
require_once (__DIR__."/../../vendor/autoload.php");

$config = new Config();

// Establish database connection
$dbConn = mysqli_connect($config->getConfigValue("db.mysql.host"), $config->getConfigValue("db.mysql.user"), $config->getConfigValue("db.mysql.pass"), $config->getConfigValue("db.mysql.name"));
if(!$dbConn)
{
    exit("Verbindungsfehler: ".mysqli_connect_error());
}