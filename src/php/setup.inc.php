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

$smarty = new Smarty();
$smarty->template_dir = $config->getConfigValue('path.smarty.templates');
$smarty->compile_dir = $config->getConfigValue('path.smarty.compile');
$smarty->cache_dir = $config->getConfigValue('path.smarty.cache');