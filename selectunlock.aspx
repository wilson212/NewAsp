<?php 

/*
    Copyright (C) 2006-2012  BF2Statistics

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
/*
| ---------------------------------------------------------------
| Define ROOT and system paths
| ---------------------------------------------------------------
*/
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('SYSTEM_PATH', ROOT . DS . 'system');

/*
| ---------------------------------------------------------------
| Require the needed scripts to launch the system
| ---------------------------------------------------------------
*/
require(SYSTEM_PATH . DS . 'core'. DS .'Registry.php');
require(SYSTEM_PATH . DS . 'functions.php');

// Set Error Reporting
error_reporting(E_ALL);
ini_set("log_errors", "1");
ini_set("error_log", SYSTEM_PATH . DS . 'logs' . DS . 'php_errors.log');
ini_set("display_errors", "0");

//Disable Zlib Compression
ini_set('zlib.output_compression', '0'); 
 
 // Make sure we have an ID and PID
$pid = (isset($_GET['pid'])) ? $_GET['pid'] : false;
$id = (isset($_GET['id'])) ? $_GET['id'] : false;
if (!$pid || !is_numeric($pid) || !$id || !is_numeric($id)) 
{
    print 'Invalid syntax!';
}
else
{
	// Import configuration
	$cfg = load_class('Config');

	// Establish database connection
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection);

	$query = "UPDATE unlocks SET state = 's' WHERE (id = {$pid}) AND (kit = {$id})";
	mysql_query($query) or die(mysql_error());

	$query = "SELECT availunlocks FROM player WHERE id = {$pid}";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$unlocks = $row['availunlocks'];
	$unlocks -= 1;

	$query = "UPDATE player SET availunlocks = {$unlocks} WHERE id = {$pid}";
	mysql_query($query) or die(mysql_error());

	$query = "SELECT usedunlocks FROM player WHERE id = {$pid}";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$used = $row['usedunlocks'];
	$used += 1;

	$query = "UPDATE player SET usedunlocks = {$used} WHERE id = {$pid}";
	mysql_query($query) or die(mysql_error());

	@mysql_close($connection);
}
?>