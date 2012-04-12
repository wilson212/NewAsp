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

// Make sure we have a PID list
$pidlist = (isset($_GET['playerlist'])) ? $_GET['playerlist'] : 0;

// Get our Player Nick
if(isset($_POST['nick'])) 
{
	$nick = $_POST['nick'];
} 
else
{
	$nick = (isset($_GET['nick'])) ? $_GET['nick'] : '';
}

if(isset($nick) && $nick != '') 
{
	// Import configuration
	$cfg = load_class('Config');

	// Establish database connection
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection);
	
	$pid = $cfg->get('game_default_pid');
	
	$query = "SELECT id FROM `player` WHERE name = '" . quote_smart($nick) . "' LIMIT 1";
	$result = mysql_query($query);
	
	if ( $result && mysql_num_rows($result) ) 
	{
		$row = mysql_fetch_assoc($result);
		$pid = $row['id'];
	} 
	else 
	{
		// create new player at 'lowest id' - 1
		$query = "INSERT `player` (id, name, joined) SELECT LEAST(IFNULL(MIN(id),". $cfg->get('game_default_pid') ."), ". 
			$cfg->get('game_default_pid') .")-1 AS id, '". quote_smart($nick) ."' AS name, " . time() . " AS joined FROM `player`";
		if ( mysql_query($query) && mysql_affected_rows() ) 
		{
			// get that new minimum PID..
			$query = "SELECT MIN(id) AS min FROM `player`";
			$result = mysql_query($query);

			if(mysql_num_rows($result)) 
			{
				$row = mysql_fetch_assoc($result);
				$pid = $row['min'];
			}
			
			// Insert unlocks
			for ($i = 11; $i < 100; $i += 11)
			{
				$query = "INSERT INTO unlocks SET id = {$pid}, kit =$i , state = 'n'";
				$result = mysql_query($query);
				checkSQLResult ($result, $query);
			}
			for($i = 111; $i < 556; $i += 111)
			{
				$query = "INSERT INTO unlocks SET id = {$pid}, kit = $i, state = 'n'";
				$result = mysql_query($query);
				checkSQLResult ($result, $query);
			}
		}
	}
	@mysql_close($connection); 
	
	$out = "O\n" .
		"H\tpid\n" .
		"D\t$pid\n";
	
	$num = strlen(preg_replace('/[\t\n]/','',$out));
	print $out . "$\t" . $num . "\t$";
	
} 
elseif($pidlist) 
{
	// Import configuration
	$cfg = load_class('Config');

	// Establish database connection
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection);
	
	$query = "SELECT id FROM `player` WHERE ip <> '127.0.0.1'";
	$result = mysql_query($query);
	
	$out = "O\n" .
		"H\tpid\n";
	
	if (mysql_num_rows($result)) 
	{
		while ($row = mysql_fetch_array($result)) 
		{
			$pid = $row['id'];
			$out .= "D\t$pid\n";
		}
	}
	@mysql_close($connection);
	
	$num = strlen(preg_replace('/[\t\n]/','',$out));
	print $out . "$\t" . $num . "\t$";

} 
else 
{
	$out = "E\n" .
		"H\terr\n" .
		"D\tNo Nick Specified!\n";
	
	$num = strlen(preg_replace('/[\t\n]/','',$out));
	print $out . "$\t" . $num . "\t$";
}
?>