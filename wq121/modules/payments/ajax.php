<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
$buff = '';
include_once "../../../includes/kanfih.php";
include_once "../../vars.php";
define('SQLLOG', 1);
include_once INC_DIR."dbconnect.php";
//include_once INC_DIR."functions.php";
//include_once SA_DIR."inc/susfunction.php";

//$site = Site::gI();
//$sets = $site->GetSettings();
//$ebox = $site->GetEditBoxes(array_keys($edit_boxes));
switch($_GET['act']){
	case 'deletepay':
	{
		$buff = $db->delete(TABLE_PAYMENTS,['ordernumber'=>$_GET['id']]);

	}
	
	default:
		break;
}
echo $buff;
$_SESSION['sql_log']=$db->write_dump();
?>