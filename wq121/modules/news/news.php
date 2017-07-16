<?php
$module['scripts'] = MODULES_PATH.$global_place.'/news.js';

// redirects //
if (!isset($_GET['action'])) $_GET['action'] = '';

switch ($_GET['action'])
{
	case 'sub' : include MODULES_PATH.$global_place."/_news.php"; break;
	case 'users' : include MODULES_PATH.$global_place."/news_users.php"; break;
	default: include MODULES_PATH.$global_place."/m.php"; 
}


//////////////
?>