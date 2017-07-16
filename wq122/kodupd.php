<?php
$dirp = dirname(__FILE__);
$dirc = explode('/',$dirp);
unset($dirc[count($dirc)-1]);
$pp=implode('/',$dirc).'/';
define('ROOT_DIR', $pp);
define('INC_DIR', ROOT_DIR.'includes/');
include INC_DIR.'dbconf.php';
include INC_DIR.'dbtables.php';
require INC_DIR.'dbconnect.php';
global $db;
$res=$db->get_rows('select distinct order_id from '.TABLE_ORDERS_PRD.' where kodstr=0');
foreach($res as $val){
	$res1=$db->get_rows('select * from '.TABLE_ORDERS_PRD.' where order_id='.$db->escape_string($val['order_id']));
	$cnt=1;
	foreach($res1 as  $stt){
		$db->update(TABLE_ORDERS_PRD,array('order_id'=>$stt['order_id'],'prd_id'=>$stt['prd_id'],'count'=>$stt['count']),array('kodstr'=>$cnt));
		$cnt+=1;
	}
}
?>