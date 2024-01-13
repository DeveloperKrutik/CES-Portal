<?php

date_default_timezone_set("Asia/Calcutta");

@session_start();

@ob_start();

include_once("class.MySQLCN.php");

$obj = new MySQLCN();

$appname = "CES App";

$title = $appname;

$server = $_SERVER['HTTP_HOST'];

$server_dir = "web/ces/";

$tdate = date("Y-m-d");

$tdatetime = date("Y-m-d H:i:s");

$baseurl = "http://".$server."/".$server_dir;   

$infoEmailId = "info@project.com"; 

$_DEBUG = 0;

if($_DEBUG == 1){

	error_reporting( E_ALL );

	ini_set('display_errors', 1);

	ini_set('display_startup_errors', 1);

	error_reporting(-1);

}


$_SESSION['rdirectdurl'] = $_SERVER['REQUEST_URI'];
?>