<?php
/***************************************
*  Battlefield 2 Private Stats Config  *
****************************************
* All comments have been removed from  *
* this file. Please use the Web Admin  *
* to change values.                    *
***************************************/
$db_expected_ver = '1.4.7';
$db_host = 'localhost';
$db_port = 3306;
$db_name = 'bf2stats';
$db_user = 'admin';
$db_pass = 'admin';
$admin_user = 'admin';
$admin_pass = 'admin';
$admin_hosts = array('127.0.0.1','192.168.2.0/24','localhost','192.168.1.102','192.168.1.110');
$admin_log = 'system/logs/admin_event.log';
$admin_backup_path = 'C:/wamp/www/new/system/database/backups/';
$admin_backup_ext = '.bak';
$admin_page_size = 150;
$admin_ignore_ai = 0;
$stats_ext = '.txt';
$stats_logs = 'system/logs/';
$stats_logs_store = 'system/logs/snapshots/';
$stats_move_logs = 1;
$stats_min_game_time = 0;
$stats_min_player_game_time = 0;
$stats_players_min = 1;
$stats_players_max = 256;
$stats_rank_check = 0;
$stats_rank_tenure = 7;
$stats_awds_complete = 0;
$stats_lan_override = '174.49.21.221';
$stats_local_pids = array('LocalPlayer01','210.84.29.151','LocalPlayer02','210.84.29.151');
$debug_lvl = 1;
$debug_log = 'system/logs/stats_errors.log';
$game_hosts = array('24.19.62.127','127.0.0.1','192.168.2.0/24','192.168.1.102','192.168.1.110','localhost','::1');
$game_custom_mapid = 700;
$game_unlocks = 0;
$game_unlocks_bonus = 2;
$game_unlocks_bonus_min = 1;
$game_awds_ignore_time = 0;
$game_default_pid = 29000000;
?>