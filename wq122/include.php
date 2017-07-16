<?php

session_start();
include_once "../includes/kanfih.php";
require 'vars.php';

//require_once('backup.php');

require INC_DIR.'dbconnect.php';
require INC_DIR.'functions.php';
include_once INC_DIR."site.class.php";
$site = Site::gI();
$sets = $site->GetSettings();
$ebox = $site->GetEditBoxes();
/*if (!($_SERVER['REMOTE_ADDR']==$sets['ofip']))
//if(!( $_SERVER['HTTP_USER_AGENT']==='1C+Enterprise/8.2'))
{
	header("Location:http://chudoclumba.ru");
	die();
}
*/

require SA_DIR."inc/exchange.php";
//require SA_DIR."inc/login.php";
if ($_GET['action']==='set_deliveryid'){
	die(set_deliveryid());
}
if ($_GET['action']==='del_deliveryid'){
	die(del_deliveryid());
}
if ($_GET['action']==='set_trm')
{
	die(write_trm($_GET['dt'],$_GET['par']));
}
if ($_GET['action']==='get_callback')
{
	$prd=$_GET['call_id'];
	if (!($prd >= 0)) die('Error. ID not found.');
	die(load_callback($prd));
}
if ($_GET['action']==='load_sendord'){
	die(load_sendord());
}
if ($_GET['action']==='reset_sendord'){
	die(reset_sendord());
}
if ($_GET['action']==='get_allstate')
{
	die(get_allstate());
}

if ($_GET['action']==='cl_callback')
{
	$prd=$_GET['c_id'];
	if (!($prd >= 0)) die('Error. ID not found.');
	die(close_callback($prd));
}
if ($_GET['action']==='aclon_id')
{
	$prd=$_GET['prd_id'];
	if (!($prd > 0)) die('Error. ID not found.');
	die(acl_prd($prd,TRUE));
}
if ($_GET['action']==='acloff_id')
{
	$prd=$_GET['prd_id'];
	if (!($prd > 0)) die('Error. ID not found.');
	die(acl_prd($prd,false));
}

if ($_GET['action']==='open_id')
{
	$prd=$_GET['prd_id'];
	if (!($prd > 0)) die('Error. ID not found.');
	die(open_prd($prd));
}
if ($_GET['action']==='get_prdid')
{
	$prd=$_GET['kod'];
	if (!($prd > "00")) die('Error. Kod not found.');
	die(get_prdid($prd));
}
if ($_GET['action']==='delete_fin')
{
	$uuid=$_GET['uuid'];
	if (!($uuid > " ")) die('Error. UUID not found.');
	die(delfin($uuid));
}
elseif ($_GET['action']==='close_id')
{
	$prd=$_GET['prd_id'];
	if (!($prd > 0)) die('Error. ID not found.');
	die(close_prd($prd));
}
elseif ($_GET['action']==='getzak')
{
	die(getzak());
}
elseif ($_GET['action']==='setzakstate')
{
	die(setzakstate($_GET['id'],$_GET['state']));
}
elseif ($_GET['action']==='set_opl')
{
	die(setzakopl());
}
elseif ($_GET['action']==='get_state_id')
{
	$prd=$_GET['prd_id'];
	if (!($prd > 0)) die('Error. ID not found.');
	die(get_idstate($prd));
}
elseif ($_GET['action']==='upd_ord')
{
	$prd=$_GET['ord_id'];
	die(upd_ord($prd));
}
elseif ($_GET['action']==='set_orderstate')
{
	die(set_orderstate($_GET['id'],$_GET['state']));
}
elseif ($_GET['action']==='set_fin')
{
	die(setfin());
}
elseif ($_GET['action']==='reset_fin_err')
{
	die(reset_fin_err());
}

elseif ($_GET['action']==='set_assist')
{
	die(setAssist());
}
elseif ($_GET['action']==='get_udata')
{
	$prd=$_GET['uid'];
	if (!($prd > 0)) die('Error. UID not found.');
	die(get_udata($prd));
}
elseif ($_GET['action']==='get_newnom')
{
	die(get_newnom());
}
elseif ($_GET['action']==='get_allfoto')
{
	die(get_allfoto());
}
elseif ($_GET['action']==='get_updates')
{
	die(get_updates($_GET['id']));
}
elseif ($_GET['action']==='view_prd')
{
	die(get_prdv($_GET['id']));
}
elseif ($_GET['action']==='send_ordrep')
{
	die(send_ordrep());
}
elseif ($_GET['action']==='send_ordrep1')
{
	die(send_ordrep1());
}
elseif ($_GET['action']==='set_sklad')
{
	die(set_sklad());
}
elseif ($_GET['action']==='set_zsclose')
{
	die(set_zsclose());
}
elseif ($_GET['action']==='reset_orderreg')
{
	die(reset_orderreg());
}

else die('no action');
