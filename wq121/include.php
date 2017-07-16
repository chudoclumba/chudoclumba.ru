<?php
//print_r($_GET);
//phpinfo();
//exit;
require_once('inc/mobile_device_detect.php');
$mobile = mobile_device_detect();
//print_r($mobile);
if($mobile)	define('MDEVICE', 1); else define('MDEVICE', 0);
session_start();
include_once "../includes/kanfih.php";
require 'vars.php';
//require_once('backup.php');
require INC_DIR.'dbconnect.php';
require INC_DIR.'functions.php';
require INC_DIR.'site.class.php';
$site = Site::gI();
$sets = $site->GetSettings();
if (isset($sets['debug']) && $sets['debug']==1 ) define('DEBUG',1); else define('DEBUG',0);
if (isset($sets['sql_log']) && $sets['sql_log']==1 ) define('SQLLOG',1); else define('SQLLOG',0);
if(DEBUG)
{
	ini_set('error_reporting', E_ALL );
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
}
else
{
	ini_set('error_reporting', 0);
}
$ebox = $site->GetEditBoxes();
require SA_DIR."inc/susfunction.php";
require SA_DIR."inc/login.php";
require SA_DIR."inc/showtables.php";
$onls = $db->get(TABLE_ONLINE, array('sess_id'=>session_id()));
if(count($onls) > 0){
	$db->update(TABLE_ONLINE, array('sess_id'=>session_id()), array('ip'=>$_SERVER['REMOTE_ADDR'],'time'=>time(),
	'user'=>$_SESSION['auth']['user'].((MDEVICE==1 && isset($mobile[1]))?' '.$mobile[1]:'')	));
}
else{
	$db->insert(TABLE_ONLINE, array('sess_id'=>session_id(),'ip'=>$_SERVER['REMOTE_ADDR'],'time'=>time(),'user'=>$_SESSION['auth']['user'].((MDEVICE==1 && isset($mobile[1]))?' '.$mobile[1]:'')));
}
$db->exec("DELETE FROM ".TABLE_ONLINE." WHERE time < ".(time()-(60*60*24*7))."");
$_GET['btn_id'] = (!empty($_GET['btn_id'])) ? $_GET['btn_id'] : '1';
$module = array(
	'html'=>'',
	'map'=>'',
	'scripts'=>''
);
$btns = array();
if(!empty($_GET['place'])){
	$global_place = $_GET['place'];
	$prelink = "include.php?place=".$global_place;
	if(ModuleExists($global_place)){
		require MODULES_PATH.$global_place."/".$global_place.".php";
	}
	else{
		require SA_DIR."inc/nosuch.php";
	}
}
require TEMPLATE_DIR."html/i.php";
if (SQLLOG){
	echo "<div".((MDEVICE==0)?' class="mcc3"':'').">".$db->write_dump()."</div>";	
}
