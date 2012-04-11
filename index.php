<?php 
/* 
| --------------------------------------------------------------
| BF2 Statistics Admin Util
| --------------------------------------------------------------
| Author:       Steven Wilson 
| Copyright:    Copyright (c) 2012
| License:      GNU GPL v3
| ---------------------------------------------------------------
|
| I would like to take a moment and Thank all of those involved
| in the creation if BF2statistics. It is an amazing system in
| which I, myself have enjoyed so many hours on. This admin Util
| was written by me mostly, But i take no credit for creating
| the Original ASP.
|
*/

/*
| ---------------------------------------------------------------
| Define that we are here in the BF2 Admin area, prevents direct 
| linking of files, Also define ROOT and system paths
| ---------------------------------------------------------------
*/
define('BF2_ADMIN', 1);
define('CODE_VER', '1.4.7');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('SYSTEM_PATH', ROOT . DS . 'system');
define('TIME_START', microtime(true));

/*
| ---------------------------------------------------------------
| Require the needed scripts to launch the system
| ---------------------------------------------------------------
*/
require(SYSTEM_PATH . DS . 'core'. DS .'Registry.php');
require(SYSTEM_PATH . DS . 'functions.php');

/*
| ---------------------------------------------------------------
| Load the controller, which in turn loads the current task
| ---------------------------------------------------------------
*/
$Controller = load_class('Controller');
$Controller->Init();
?>