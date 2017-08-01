<?php


$params = array();
session_start();
require_once "includes/kanfih.php";

#echo  INC_DIR;

require INC_DIR.'dbconnect.php';
require_once INC_DIR.'functions.php';
$footer_view = 111;

if(!file_exists(INC_DIR.'site.class.php')){
    error_log("Failed to open file: ".INC_DIR.'site.class.php');
	header("HTTP/1.1 301 Moved Permanently");
	header("Location:http://".$_SERVER['HTTP_HOST']);
}

require_once "config.php";
require_once INC_DIR.'site.class.php';

$site = Site::gI();

//echo $site;

$sets = $site->GetSettings();

/*echo '<br>';

foreach($sets as $set) {
    echo $set, '<br>';
}*/

$gmess="";
$gcounter="";


if (isset($sets['debug']) && $sets['debug']==1 && (!(strpos($_SERVER['REMOTE_ADDR'],$sets['ofip'])===false) || $_SESSION['user']==1024)) define('DEBUG',1); else define('DEBUG',0);
if (isset($sets['sql_log']) && $sets['sql_log']==1 && (!(strpos($_SERVER['REMOTE_ADDR'],$sets['ofip'])===false) || $_SESSION['user']==1024)) define('SQLLOG',1); else define('SQLLOG',0);

if(DEBUG)
{
    //echo '<br>', "DEBUG Enabled";
	ini_set('error_reporting', E_ALL );
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
}
else
{
    //echo '<br>', "DEBUG Disabled";
	ini_set('error_reporting', 0);
}


if (file_exists(ROOT_DIR."login.php")) {
    require(ROOT_DIR . "login.php");
}
else
{
    //echo '<br>', "Failed to find login page code by path: ". ROOT_DIR."login.php";
}

if (isset($sets['yak']) && $sets['yak']==1) {
	require_once INC_DIR.'kassayandex/Settings.php';
	require_once INC_DIR.'kassayandex/YaMoneyCommonHttpProtocol.php';
}

$modules = array(
	'ajax' => 'ajax',
	'user' => 'reg',
	'filter' => 'filter',
	'service' => 'service',
	'pay' => 'payments',
	'cart' => 'MyCart',
	'site' => 'sitemenu',
	'podpiska' => 'podpiska',
	'wishlist' => 'wishlist',
	'ishop' => 'ishop2',
	'news' => 'news',
	'search' => 'search',
	'map' => 'map'
);
//    URI,    .
//print_r($_SERVER['REQUEST_URI']);
if ($_SERVER['REQUEST_URI'] != '/') {
    //echo '<br>', "Request to root";
	$flag=true;
		$url_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/');

       // echo '<br>', $url_path;
		if (substr($url_path,-5)=='/ajax') {
			$site->ajax=true;
			$url_path=substr($url_path,0,-5);
		}
		$gmess.='_'.$url_path.(($site->ajax==true)?'_ajax':'');

		
		//   URL   "/"
		$uri_parts = explode('/', trim($url_path, ROOT_PREF));
		if (count($uri_parts)>0 && strtoupper($uri_parts[count($uri_parts)-1])=="AJAX") {
			array_pop($uri_parts);
		}



		if (count($uri_parts)>0) $_GET['module'] = array_shift($uri_parts); //
    //
		$fl=true;
		$rv1=array_keys($modules);
		$rv1[]='catalog';

		foreach($rv1 as $val){
			$fl=$fl && preg_match('/^'.$val.'\b/i',$_GET['module'])===0;
		}  // $fl      

		if ($sets['cpu']==1 && $fl) {  //       ,

			$rvl=$site->db->get(TABLE_SITEMENU,array('vlink'=>trim($url_path, ROOT_PREF)));
			if (count($rvl)>0) {
              //  echo '<br>', "test";
                /*foreach($rv1 as $val){
                    echo '<br>', $val;
                }*/

                $_GET['module']='site';
				$_GET['id']=$rvl[0]['id'];
				$flag=false; //    
			}
		}

		$fl=true;
		$rv1=array_keys($modules);
		foreach($rv1 as $val){
			$fl=$fl && preg_match('/^'.$val.'\b/i',$_GET['module'])===0;
		}  // $fl      
		
		if ($sets['cpucat']==1 && $flag && $fl) { //             ,       
			$uri_parts = explode('/', trim($url_path, ROOT_PREF));
			if (preg_match('/_id\d+?$/i',$uri_parts[count($uri_parts)-1])>0 && count($uri_parts)>1)	{
				$_GET['id']=preg_replace('/^.*?_id(\d+?)/i','\1',$uri_parts[count($uri_parts)-1]);
				$_GET['module']='ishop';
				$_GET['type']='product';
				$flag=false;
			} else {
				
				if (preg_match('/^p\d+?/i',$uri_parts[count($uri_parts)-1])>0 && count($uri_parts)>1){
					$_GET['page']=preg_replace('/^p(\d+?)/i','\1',$uri_parts[count($uri_parts)-1]);
					$sse=array_pop($uri_parts);
				} 
				$sse=implode($uri_parts,ROOT_PREF);
				$rvl=$site->db->get(TABLE_CATEGORIES,array('vlink'=>$sse));
				if (count($rvl)>0) {
					$_GET['module']='ishop';
					$_GET['id']=$rvl[0]['id'];
					$flag=false;
				}
			}
		}
		
		if ($flag){
			$uri_parts = explode('/', trim($url_path, ROOT_PREF));
			if (count($uri_parts)>0) $_GET['module'] = array_shift($uri_parts);
			if (count($uri_parts)>1) $_GET['type'] = array_shift($uri_parts); //   
			if (count($uri_parts)>0) $_GET['id'] = array_shift($uri_parts); //   
			if (isset($_GET['id']) && preg_match("/[a-zA-Z]+/is",$_GET['id'])>0 && empty($_GET['type'])){
					$_GET['type']=$_GET['id'];
					unset($_GET['id']);
				}
			if (isset($_GET['module']) && preg_match("/sitemap.xml/i",$_GET['module'])>0){
				$_GET['module']='map';
				$_GET['type']='sitemap';
			}		
		}		


		//   $params  
		for ($i=0; $i < count($uri_parts); $i++) {
			$_GET['params'][$i] = $uri_parts[$i];
		}
		Site::gI()->url=$url_path;
}



if (isset($_GET['q']) && (!(stripos($_GET['q'],'index')===false) || !(stripos($_GET['q'],'home')===false))){
    error_log("Fatal in start.php:187");
	header("HTTP/1.1 301 Moved Permanently");
	header("Location:http://".$_SERVER['HTTP_HOST']);
	exit();
}

if (isset($sets['debug'])) $gmess.=' req '.print_r($_REQUEST,true);

if (isset($_GET['type']) && $_GET['type']=='advsearch' && isset($_GET['id'])){
	$_GET['page']=$_GET['id'];
	unset($_GET['id']);
}

if (isset($_GET['q']) || (!isset($_GET['module']) && !empty($_GET)))  {
    error_log("Call 404 from start.php: 201");
    $site->_404();
}

$ebox = $site->GetEditBoxes(array_keys($edit_boxes));
if(!empty($sets['sess_ip']) || !empty($sets['sess_agent'])){
	if(!empty($sets['sess_ip']) && !empty($sets['sess_agent'])){
		$protect_str = ip().$_SERVER['HTTP_USER_AGENT'];
	}
	elseif(!empty($sets['sess_ip'])){
		$protect_str = ip();
	}
	else{
		$protect_str = $_SERVER['HTTP_USER_AGENT'];
	}

	if(empty($_SESSION['sess_ip_user_agent'])){
		$_SESSION['sess_ip_user_agent'] = $protect_str;
	}
	else{
		if($_SESSION['sess_ip_user_agent'] != $protect_str){
			session_regenerate_id();
			session_unset();
			$_SESSION['sess_ip_user_agent'] = $protect_str;
		}
	}
}

define('DEF_MODULE', 'site');
define('DEF_ID', $ebox['id_main']);


if(empty($_GET['module']) && empty($_GET['id'])){
	$_GET['module'] = DEF_MODULE;
	$_GET['id'] = DEF_ID;
	if(!empty($_SESSION['langv']) && $_SESSION['langv'] == 'eng') $_GET['id'] = 13;
}
elseif(!isset($_GET['id'])){
//	$_GET['id'] = '';
}

if (!(stripos($_GET['module'],'temperature')===false)){
	$_GET['module']='ishop';
	$_GET['type']='temperature';
}

if($_SERVER['REQUEST_URI'] == '/'.DEF_MODULE.'/'.DEF_ID || $_SERVER['REQUEST_URI'] == '/'.DEF_MODULE.'/1'){
    error_log("Fatal in start.php:245");
    header("HTTP/1.1 301 Moved Permanently");
	header("Location:".SITE_URL);
	exit();
}

if($_SERVER['REQUEST_URI'] == '/'.DEF_MODULE.'/'.DEF_ID || $_SERVER['REQUEST_URI'] == '/i.php') {
    error_log("Call 404 from start.php: 255");
    $site->_404();
} // || strpos($_SERVER['REQUEST_URI'], '?') !== false
//=================

if (!empty($_SESSION['user']) && !empty($_SESSION['user_type'])){
	define('USER_ID',$_SESSION['user']);
	define('USER_TYPE',$_SESSION['user_type']);
}
//=================

if(DEBUG==1){
	$chk_dirs = array('data', 'cache');
	foreach($chk_dirs as $dir){
		if(!is_writable(ROOT_DIR.$dir))	{
			echo '<div style="padding:20px; color:red; font-weight:bold;"> '.$dir.'    </div>';
		}
	}
}

$content = array (
	'html' => '',
	'meta_title' => $sitename,
	'meta_keys' => $sitename,
	'meta_desc' => $sitename,
	'left_menu' => true,
	'path' => ''
);


if (!isset($_GET['module'])){
	$_GET['module'] = 'sitemenu';
}


foreach($modules as $module){
	$file = ROOT_DIR.'modules/'.$module.'.php';
	if (file_exists($file))	require_once($file);
}

if((empty($content['path']) || $content['path'] == '') && $_GET['type']=='kvit') die($content['html']);

if(empty($content['path']) || $content['path'] == '')
{
    error_log("Call 404 from start.php: 299");
    $site->_404();
}

if(!empty($content['html'])){
	$content['html'] = fix_content($content['html']);
}

if (isset($sets['soft_upd']) && $sets['soft_upd']==1 && !(User::gI()->user_role>0 || $_SERVER['REMOTE_ADDR']=='109.72.249.88') ){
	header('HTTP/1.1 503 Service temporary down');
	echo $site->view('ishop/soft_upd');
	exit;
}

if ($site->ajax==true) die($content['html']);

$ind_file = TEMP_FOLDER.'html/main.php';

if($_GET['module'] == 'site' && $_GET['id'] == '1') $ind_file = TEMP_FOLDER.'html/main.php';

if(file_exists($ind_file)){
	require TEMP_FOLDER.'config.php';
	include $ind_file;
}else{
	header('HTTP/1.1 503 Service temporary down');
	die("  ".$ind_file."");
}

echo $site->db->write_dump();