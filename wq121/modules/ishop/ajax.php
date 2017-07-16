<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
$buff = '';
if($_GET['act'] == 'asql_log'){
	$buff=$_SESSION['sql_log'];
	unset($_SESSION['sql_log']);
	die($buff);
}
include_once "../../../includes/kanfih.php";
include_once "../../vars.php";
require_once(SA_DIR.'inc/mobile_device_detect.php');
$mobile = mobile_device_detect();
if($mobile)	define('MDEVICE', 1); else define('MDEVICE', 0);
include_once INC_DIR."dbconnect.php";
include_once INC_DIR."functions.php";
include_once INC_DIR."site.class.php";
include_once SA_DIR."inc/susfunction.php";
include_once SA_DIR."inc/showtables.php";
require "funcs.php";
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
if(isset($_GET['value'])) $value = decode_str($_GET['value']);
function get_tsubcat($cat){
	$ret=array();
	global $db;
	$rows = $db->get_rows("SELECT id from ".TABLE_CATEGORIES." where parent_id=".$cat.' and visible=1 and enabled=1');
	foreach($rows as $val){
		$ret[]=$val['id'];
		if(get_scatcnt($val['id'])>0) $ret=array_merge($ret,get_tsubcat($val['id']));
	}
	return $ret;
}
function set_errfile(&$errf,$zg,$ln,$txt){
	if(!($errf>' ')){
		$comma_separated = implode(";", $zg);
		$errf.=$comma_separated;
	}
	$ln1=$ln;
	$ln1[]=$txt;
	$comma_separated = implode(";", $ln1);
	$errf.="\n".$comma_separated;

}
function updates_title($cats = array(), $title){
	global $db;
	$rows = $db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." WHERE parent_id IN (".implode(',', $cats).")");
	$catn = array();
	foreach($rows as $id=>$val){
		$rows = $db->get_rows("UPDATE ".TABLE_CATEGORIES." SET  metatitle = ".quote_smart($title.', '.$val['title'])." WHERE id = ".$val['id']."");
		$catn[] = $val['id'];
	}

	if(count($catn) > 0) updates_title($catn, $title);
}
function Get_csvline($rows,$rr="",$ren=1,$pn=''){
	$file="";
	$co=0;
	$cnt=0;
	foreach($rows as $id=>$val){
		$tren=$ren*$val['enabled']*$val['visible'];
		$line='"'.conv_str($val['param_kodtovara']).'";';
		$line.=$val['id'].';';
		$line.=$tren.';';
		$line.='"'.conv_str($val['title']).'";';
		$line.=$val['tsena'].';';
		$line.=$val['param_starayatsena'].';';
		$line.=$val['skidka'].';';
		$line.=$val['tper'].';';
		$line.='"'.conv_str($val['param_vid']).'";';
		$line.='"'.conv_str($val['param_gruppa']).'";';
		$line.='"'.conv_str($val['param_sort']).'";';
		$line.='"'.conv_str($val['param_naimenovanielatinskoe']).'";';
		$line.='"'.conv_str($val['param_proizvoditel']).'";';
		$line.='"'.conv_str($val['param_visota']).'";';
		$line.='"'.conv_str($val['param_shirina']).'";';
		$line.='"'.conv_str($val['param_aromat']).'";';
		$line.='"'.conv_str($val['param_aromatdlyapoiska']).'";';
		$line.='"'.conv_str($val['param_tsvetenie']).'";';
		$line.=$val['param_razmertsvetka'].';';
		$line.='"'.conv_str($val['param_tiptsvetka']).'";';
		$line.='"'.conv_str($val['param_periodtsveteniya']).'";';
		$line.='"'.conv_str($val['param_okraskatsvetka']).'";';
		$line.='"'.conv_str($val['param_okraskatsvetkadlyapoiska']).'";';
		$line.='"'.conv_str($val['param_okraskalistvi']).'";';
		$line.='"'.conv_str($val['param_morozostoykost']).'";';
		$line.='"'.conv_str($val['param_muchnistayarosa']).'";';
		$line.='"'.conv_str($val['param_chernayapyatnistost']).'";';
		$line.='"'.conv_str($val['param_tippochvi']).'";';
		$line.='"'.conv_str($val['param_svetovoyrezhim']).'";';
		$line.='"'.conv_str($val['param_standartpostavki']).'";';
		$line.='"'.conv_str($val['param_srokpostavki']).'";';
		$line.='"'.date('d.m.Y',$val['datain']).'";';
		$line.='"'.conv_str($val['param_polnoeopisanie']).'";';
		$line.='"'.conv_str($val['opisanie']).'";';
		$line.='"'.conv_str($val['metatitle']).'";';
		$line.='"'.conv_str($val['metadesc']).'";';
		$line.='"'.conv_str($val['metakeys']).'";';
		$line.=$val['isupak'].';';
		$line.='"'.conv_str($val['param_kolichestvo']).'";';
		$line.='"'.conv_str($val['foto']).'";';
		$line.=$val['enabled'].';';
		$line.=$val['visible'].';';
		$line.=$val['hit'].';';
		$line.=$val['new'].';';
		$line.=$val['skidka_day'].';';
		$line.=$val['sort'].';';
		$line.=$val['spec'].';';
		$line.=$val['srcid'].';';
		$line.=$val['cat_id'].';';
		$line.='"'.$val['vlink'].'"';


		if($rr>" ") $line.=';;;'.conv_str($rr);

		$file.=$line."\r\n";
		$co++;
		if ($co>1000 && !empty($pn)){
			$co=0;
			$cnt=$cnt+file_put_contents($pn,$file,FILE_APPEND);
			$file="";
		}
	}
	if (!empty($pn) && !empty($file)){
		$co=0;
		$cnt=$cnt+file_put_contents($pn,$file,FILE_APPEND);
		$file="";
		return $cnt;
	}
	return $file;
}
function Get_SubCat($cat,$op=0,$filtit='',$filt='',$lvl=1,$nm="",$ren=1){
	global $db;
	$file="";
	$sstr='';
	if(!empty($filtit)) $sstr.= " and title like '".$filtit."'";
	if($op>0) $sstr.= " and visible=1 and enabled=1";
	$rows = $db->get_rows("SELECT * from ".TABLE_CATEGORIES." where parent_id=".$cat.$sstr." ORDER BY sort, title");
	foreach($rows as $id=>$val){
		$tren=$ren*$val['enabled']*$val['visible'];
		$line=';';
		$line.=$val['id'].';';
		$line.=$tren.';"';
		for($x=0; $x++<$lvl;) $line.="*";
		$line.=conv_str($val['title']).'";;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;';
		$line.='"'.conv_str($val['metatitle']).'";';
		$line.='"'.conv_str($val['metadesc']).'";';
		$line.='"'.conv_str($val['metakeys']).'";;;';
		$line.='"'.conv_str($val['foto']).'";';
		$line.=$val['enabled'].';';
		$line.=$val['visible'].';;;;';
		$line.=$val['sort'].';;;;';
		$line.='"'.$val['vlink'].'";';
		$line.=$val['parent_id'].';';
		$line.='"'.conv_str($val['text']).'";';
		$line.='"'.conv_str($nm).'"';
		$file.=$line."\r\n";
		if(get_prdcnt($val['id'])>0){
			if(!empty($filtit)) $sstr.= " and title like '".$filtit."'";
			if(!empty($filt)) $sstr.=" and param_kodtovara like '%".$filt."%'";
			if($op>0) $sstr.= " and visible=1 and enabled=1";
			$rowsp = $db->get_rows("SELECT ".TABLE_PRODUCTS.".* from ".TABLE_PRODUCTS." where cat_id=".$val['id'].$sstr." ORDER BY param_kodtovara");
			$file.=Get_csvline($rowsp,$nm.'->'.conv_str($val['title']),$tren);
		}
		if(get_scatcnt($val['id'])>0) $file.=Get_SubCat($val['id'],$op,$filtit,$filt,$lvl+1,$nm.'->'.conv_str($val['title']),$tren);
	}
	return $file;
}
if($_GET['act'] == 'save_order'){
	$db->update(TABLE_ORDERS, array('id' => $_GET['order_id']), array('status' => $_GET['value']));
	$buff = $_GET['value'];
}
elseif($_GET['act'] == 'save_order1'){
	if($_GET['tip']==1){
		$db->update(TABLE_ORDERS, array('id' => $_GET['order_id']), array('skidka' => $_GET['value']));
		$buff = $_GET['value'];
	}
	elseif($_GET['tip']==2){
		$db->update(TABLE_ORDERS, array('id' => $_GET['order_id']), array('user_id' => $_GET['value']));
		$buff = $_GET['value'];
	}

}
elseif($_GET['act'] == 'test'){
	echo mb_regex_encoding();
	mb_regex_encoding('utf-8');
	echo 'совп1-'.mb_ereg('[а-я]{2}','Тестовая СТРОКА');
	echo 'совп2-'.mb_ereg('[а-я]','Test sth');
	echo mb_strtolower('Тестовая СТРОКА');
	//	echo strtolower('Тестовая СТРОКА');
}
elseif($_GET['act'] == 'upd_feed'){
	$db->exec("UPDATE ".TABLE_FEED." SET sort = '1' WHERE `id` = '9'");
	$db->exec("UPDATE ".TABLE_FEED." SET sort = '2' WHERE `id` = '8'");
}
elseif($_GET['act'] == 'gl_find'){
	$schstr=$_POST['glfind'];
	$q1='select * from '.TABLE_ORDERS."  where (`fio` LIKE '%".str_replace ("'","_",$schstr)."%' or `email` LIKE '%".str_replace ("'","_",$schstr)."%') or id='".$schstr."' or (user_id>0 and user_id='".$schstr."') or (summa >0 and summa='".$schstr."')";
	//	$q1='select * from '.TABLE_ORDERS."  where `email` LIKE '%".str_replace ("'","_",$schstr)."%'";
	$q2='SELECT u.id, u.data, u.datep, u.login, u.block, u.pass, u.info, sum(o.summa*(100-o.skidka)/100) as sm, count(o.summa) as cn FROM '.TABLE_USERS.' u left join '.TABLE_ORDERS." o on u.id=o.user_id  where u.login LIKE '%".str_replace ("'","_",$schstr)."%' or u.info LIKE '%".str_replace("'","_",$schstr)."%' or u.pass LIKE '%".str_replace("'","_",$schstr)."%' or u.id='".$schstr."' group by u.id";
	$q3='SELECT c.*, o.fio FROM '.TABLE_PAYMENTS.' c left join '.TABLE_ORDERS.' o on o.id=c.orderid '." where (c.orderamount LIKE '%".str_replace ("'","_",$schstr)."%' or  o.fio LIKE '%".str_replace ("'","_",$schstr)."%') or c.orderid ='".$schstr."'";
	$r1=$db->get_rows($q1);
	$r2=$db->get_rows($q2);
	$r3=$db->get_rows($q3);
	$buff=get_module(array('Закрыть'=>array('id'=>1,'href'=>'swap_pan()')), 1, show_orders($r1).show_users($r2).show_payments($r3), 'Глобальный поиск');
}
elseif($_GET['act'] == 'add_char'){
	echo $_GET['name'];
	$db->insert(TABLE_CHARS, array('cat_id' => $_GET['cat'], 'name'=>$_GET['name'], 'sort'=> 0));
}
elseif($_GET['act'] == 'add_adv_foto'){
	$db->insert(TABLE_PRD_FOTO, array('product_id'=>$_GET['id'], 'filename'=>$_GET['name'], 'descr'=>'', 'sort'=>0));
	echo get_adv_photo($_GET['id']);
}
elseif($_GET['act'] == 'view_cattext'){
	$res = $db->get(TABLE_CATEGORIES,$_GET['id']);
	$catpinf = str_replace('data/','../data/', $res['text']);
	echo $catpinf;
}
elseif($_GET['act'] == 'load_data'){

	$p = array('param_stranaproizvoditel', 'param_brend', 'param_kategoriyatovara');

	foreach($p as $p_id=>$p_val){
		$rw1 = $db->get_rows("SELECT ".$p_val." FROM ".TABLE_PRODUCTS." WHERE enabled = 1 GROUP BY ".$p_val." ORDER BY ".$p_val." ASC");

		$data = array();
		foreach($rw1 as $id=>$val){
			$data[] = '"'.$val[$p_val].'"';
		}

		$end_data = 'var '.$p_val.' = ['.implode(', ', $data).'];';
		echo $end_data;
	}
}
elseif($_GET['act'] == 'load_price'){
ob_start();?>
<h3>Загрузка каталога в формате CSV</h3>
<div style="padding:20px;">
<p>Перед загрузкой будет сделана резервная копия всего каталога.<br />Восстановить каталог при сбое можно в разделе Дополнительно.</p>
<p>При выборе локального файла он будет предварительно загружен в data/csv</p>
<form enctype="multipart/form-data" action="modules/ishop/ajax.php?act=load_csv" method="post" id="upl_csv">
<input id="cat_id" type="hidden" value="<?php echo $_GET['cat_id'] ?>" />
<table><tr><td>
<fieldset>
	<legend>Тип Загрузки</legend>
	<label><input checked="checked" name="type_l" value="new" type="radio" id="r1" />Загрузка новых позиций (имеющиеся не обновляются)</label><br>
	<label><input value="update" name="type_l" type="radio" id="r2" />Обновление (обновление имеющихся, без добавления новых)</label>
</fieldset></td>
<td><fieldset id="sfs" style="display: none">
<legend>Ключевое поле</legend>
<label for="r3"><input checked="checked" name="key_f" value="param_kodtovara" type="radio" id="r3"/>Код товара</label><br>
<label for="r4"><input value="title" name="key_f" type="radio" id="r4" />Наименование товара</label>
<label for="r5"><input value="id" name="key_f" type="radio" id="r5" />ID товара</label>
</fieldset></td></tr></table>
<textarea style="display:none" class="mceEditor" style="width: 70%" id="editor1"></textarea>
<div class="block">
<label>Файл на сервере:</label>
<div style="display: inline-block;float:left "><input  name="server_csv" class="finput" value="" id="url_abs_nohost"/></div>
<div class="btn btn1" onclick="BrM();">Обзор...</div>
</div>
<div class="block">
<label>Или локальный файл:</label>
<div class="file">
<input type="hidden" name="MAX_FILE_SIZE" value="67108864" />
<input type="file" name="local_csv" />
<div class="inputbox"><span></span></div>
<div class="btn">Обзор...</div>
</div></div>
<br/><br/>
<?=button1('Загрузить', "$('#status').html('');$('#upl_csv').submit();",'','upload');?>
</form></div>
<div id="status" style="padding:10px 40px;"></div>
<script>
	$(document).ready(function () {
    $('input').iCheck({
	    checkboxClass: 'icheckbox_flat-green',
	    radioClass: 'iradio_flat-green'});	
	});
	$('#r2').on('ifChanged',function(){if ($('#r2').prop('checked')){$("#sfs").show();}else{$("#sfs").hide();}});
tinyMCEInit('editor1');
function BrM(){
	var urlm='data/csv/1.csv';
	if ($('#url_abs_nohost').val().length>0){urlm=$('#url_abs_nohost').val();}
	mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<? echo SITE_URL ?>',use_url_path : true,url:urlm});	
}

$('#upl_csv').ajaxForm(function(html) {$('#status').html(html);});</script>
<?
	$buff=ob_get_contents();
	ob_end_clean();
	$buff=get_module(get_cat_btns(), 0, $buff, 'Загрузка каталога');
	
}
elseif($_GET['act'] == 'restore_cat_show'){
	$na=[];
	$ndirct =ROOT_DIR."data/backup/"; 
	$nhdl=opendir($ndirct); 
	while ($nfile = readdir($nhdl)) {
		if (($nfile!=".")&&($nfile!="..") && is_file($ndirct.$nfile) && preg_match('/\.sql\.gz$/i',$nfile)==1) {
			$na[$nfile] = filemtime($ndirct.$nfile);
		}
	} 
	closedir($nhdl); 
	arsort($na);
	$buff=get_module(array('Закрыть'=>array('id'=>1,'href'=>'swap_pan()')), 1, show_rest($na), 'Восстановление каталога из резервной копии');

	
}
elseif($_GET['act'] == 'restore_file'){
	if (isset($value)) {
		$value=str_replace('___','.',$value).'.sql.gz';
		$handle = gzopen(ROOT_DIR."data/backup/".$value, 'r');
	    $buffer='';
		while (!gzeof($handle)) {
		   $buffer .= gzgets($handle, 4096);
		}
		gzclose($handle);
		$fl=true;
		$buffer=explode(";\n\n",$buffer);
		foreach($buffer as $key => $val){
			if (!empty($val)){
				$res=$db->exec($val);
				if ($res===false) $fl=false;
			}
		}
		$res=($fl)?'Успешно восстановлено из файла '.$value:'Ошибка при восстановлении!';
		
	} else $res="Файл не найден.";
	$buff=get_module(array('Закрыть'=>array('id'=>1,'href'=>'swap_pan()')), 1, $res, 'Восстановление каталога из резервной копии');
	
}
elseif($_GET['act'] == 'delete_restfile'){
	$buff=0;
	if (isset($value)) {
		$value=str_replace('___','.',$value).'.sql.gz';
		$buff=(int)unlink(ROOT_DIR."data/backup/".$value);
	}
}
elseif($_GET['act'] == 'load_csv'){
	
//print_r($_POST);die();
	$max_image_size=64*1024*1024;
	$valid_types=array("csv");
	if(isset($_POST['server_csv']) && !empty($_POST['server_csv'])){
		$file=ROOT_DIR.$_POST['server_csv'];
		$fbck=$_POST['server_csv'];
	} else{
		if(isset($_FILES["local_csv"])){
			if(is_uploaded_file($_FILES['local_csv']['tmp_name'])){
				$filename = $_FILES['local_csv']['tmp_name'];
				$ext = substr($_FILES['local_csv']['name'],
					1 + strrpos($_FILES['local_csv']['name'], "."));
				if(filesize($filename) > $max_image_size){
					echo 'Error: File size > 64M.';
					exit;
				} elseif(!in_array($ext, $valid_types)){
					echo 'Error: Invalid file type.';
					exit;
				} else{
					if(@move_uploaded_file($filename, ROOT_DIR."data/csv/".$_FILES['local_csv']['name'])){
						echo "Файл успешно загружен. Путь data/csv/".$_FILES['local_csv']['name'];
						$file=ROOT_DIR."data/csv/".$_FILES['local_csv']['name'];
						$fbck=$_FILES['local_csv']['name'];
					} else{
						echo 'Error: moving fie failed.';
						exit;
					}
				}
			} else{
				echo "Error: empty file.";
				exit;
			}
		} else {
				echo "Не выбран файл для загрузки!";
				exit;
		}
	}
	if(!preg_match("/\.csv$/i", $file)) die('Неверный тип файла.'.$fbck);
	if (!file_exists($file)) die('Файл не найден.'.$file);
	$fbck=preg_replace("#((.*/)|^)([^/]*)\.csv$#i",'\3',$fbck);
	$fbckr=ROOT_DIR."data/backup/".$fbck."_before.sql.gz";
	$iq=0;
	while (file_exists($fbckr)){
		$iq++;
		$fbckr=ROOT_DIR."data/backup/".$fbck."_before_{$iq}.sql.gz";
	}
	$result=array();
	$file = file_get_contents($file);
	$lines = explode("\r\n",$file);
	$rzag=array_shift($lines);			// строка с русскими заголовками
	$result[]=$rzag;
	$zag=array_shift($lines);
	$result[]=$zag;
	$zag = explode(";",strtolower($zag));// массив заголовков
	if($_POST['type_l'] == 'new'){
		if(array_search('id',$zag)!==false ) die('Невозможна загрузка новых позиций c колонкой ID!');
		if(array_search('title',$zag)===false)	die('Нет поля Наименование (title)!');
		if(array_search('param_kodtovara',$zag)===false)	die('Нет поля Код товара (param_kodtovara)!');
	} elseif ($_POST['type_l'] == 'update'){
		if( $_POST['key_f'] =='param_kodtovara' && array_search('param_kodtovara',$zag)===false) die('Нет колонки Код товара!');
		if( $_POST['key_f'] =='title' && array_search('title',$zag)===false) die('Нет колонки Наименование!');
		if( $_POST['key_f'] =='id' && array_search('id',$zag)===false) die('Нет колонки ID!');
	} else die('Неопределен тип загрузки!');
	backup_tables([TABLE_CATEGORIES,TABLE_PRODUCTS,TABLE_RECOM,TABLE_GRREC],$fbckr);
	echo "<p>Создана резервная копия каталога {$fbckr}</p>";
	$res=$db->get_rows("SHOW COLUMNS FROM  ".TABLE_PRODUCTS);
	foreach($res as $val){
		$txt[]=$val['Field'];
	}
	$n_id = 0;
	$n_id1 = 1;
	$n_id2 = 2;
	$cnt=0;
	$err=false;
	$errtxt='';
	$errfile='';
	$okcnt=0;
	$nccnt=0;
	$errcnt=0;
	$lvl=-1;
	if($_POST['type_l'] == 'new')   // здесь загрузка новых
	{
		foreach($lines as $id=>$line){
			$result[]=$line;
			if (empty($line)) continue;
			$line = explode(";",$line);
			$level = 3;
			$title = trim($line[array_search('title',$zag)]);
			$kod= trim($line[array_search('param_kodtovara',$zag)]);
			if(array_search('cat_id',$zag)===false && empty($kod)){  // нет явно категории и код товара пуст
				$pos1 = strpos($title, '***');
				$pos2 = strpos($title, '**');
				$pos3 = strpos($title, '*');
				if($pos3 !== false)	$level = 0;
				if($pos2 !== false)	$level = 1;
				if($pos1 !== false)	$level = 2;
				if ($level<3) {
					$title = str_replace('*', '', $title);
					if ($level == 0) {
						$rows = $db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." WHERE title = ".quote_smart($title)." && parent_id = 11");
					  	if (count($rows) > 0){
					  		$n_id = $rows['0']['id'];
					  	} else {
					    	$db->insert(TABLE_CATEGORIES, array('title'=>$title,'enabled'  =>1,'gr_id'=>5,'parent_id'=> 11));
					      	$n_id = $db->insert_id();
					  	}
					  	$lvl = 0;
					} 
					if ($level == 1 && $n_id>0){
						$rows = $db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." WHERE title = ".quote_smart($title)." && parent_id = ".$n_id."");
						if(count($rows) > 0){
							$n_id1 = $rows['0']['id'];
						} else {
							$db->insert(TABLE_CATEGORIES, array('title' => $title,'enabled' => 1,'gr_id' => 5,'parent_id' => $n_id));
							$n_id1 = $db->insert_id();
						}
						$lvl=1;
					} else {
						$errcnt++;
						set_errfile($errfile,$zag,$line,'Обнаружен 2й уровень (**) без первого.');
						break;
					}
					if($level == 2 && $n_id1>0){
						$rows = $db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." WHERE title = ".quote_smart($title)." && parent_id = ".$n_id1."");
						if(count($rows) > 0){
							$n_id2 = $rows['0']['id'];
						}else{
							$db->insert(TABLE_CATEGORIES, array('title' => $title,'enabled' => 1,'gr_id' => 5,'parent_id' => $n_id1));
							$n_id2 = $db->insert_id();
						}
						$lvl=2;
					} else {
						$errcnt++;
						set_errfile($errfile,$zag,$line,'Обнаружен 3й уровень (**) без второго.');
						break;
					}
				}
			}
			if($level == 3){
				$zag1=$zag;
				for($x=count($zag1); $x>=1; $x--){
					if(empty($zag1[$x-1]) || !in_array($zag1[$x-1],$txt)){
						array_splice($zag1,$x-1,1);
						array_splice($line,$x-1,1);
					}else{
						$zag1[$x-1]=trim($zag1[$x-1]);
						$line[$x-1]=trim($line[$x-1]);
						if($zag1[$x-1]=='tsena' || $zag1[$x-1]=='param_starayatsena'){
							$line[$x-1]=str_replace(' ', '', str_replace(',', '.',$line[$x-1]));
						}
						if(($zag1[$x-1]=='enabled' || $zag1[$x-1]=='new' || $zag1[$x-1]=='hit' || $zag1[$x-1]=='spec' || $zag1[$x-1]=='visible' || $zag1[$x-1]=='skidka_day' || $zag1[$x-1]=='isupak') && (!($line[$x-1]==0 || $line[$x-1]==1)))	$line[$x-1]=0;
						if($zag1[$x-1]=='sort' || $zag1[$x-1]=='skidka' || $zag1[$x-1]=='param_razmertsvetka'){
							$line[$x-1]=str_replace(' ', '', str_replace(',', '.',$line[$x-1]));
							if(!(strpos($line[$x-1],'.')===false))	$line[$x-1]=substr($line[$x-1],0,strpos($line[$x-1],'.'));
						}
						if($zag1[$x-1]=='datain') $line[$x-1]=strtotime($line[$x-1]);
						if (!empty($line[$x-1])){
							if ($line[$x-1]{0}=='"') $line[$x-1]=substr($line[$x-1],1);
							if ($line[$x-1]{strlen($line[$x-1])-1}=='"') $line[$x-1]=substr($line[$x-1],0,strlen($line[$x-1])-1);
							$line[$x-1]=str_replace('""','"',$line[$x-1]);
						}
					}
				}
				$line1=$line;
				$cat=1;
				if(array_search('cat_id',$zag1)===false){
					$zag1[]='cat_id';
					if ($lvl==0) $cat=$n_id;
					if ($lvl==1) $cat=$n_id1;
					if ($lvl==2) $cat=$n_id2;
					$line1[]=$cat;
				} else {
					$cat=@(int)$line1[array_search('cat_id',$zag1)];
					if ($cat==0) $cat=1;
					$line1[array_search('cat_id',$zag1)]=$cat;
				}
				$data=array_combine ($zag1, $line1);
				if(!(count($data)>1)){
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Пустой набор для записи!');
					continue;
				}
				$sstr1=" where param_kodtovara = ".quote_smart(trim($line[array_search('param_kodtovara',$zag)]));
				if(empty($kod)){
					set_errfile($errfile,$zag,$line,'Нет кода товара');
					$errcnt++;
					continue;
				}
				$sstr=" where title = ".quote_smart($title)." and cat_id=".quote_smart($cat);
				if(empty($title)){
					set_errfile($errfile,$zag,$line,'Нет наименования');
					$errcnt++;
					continue;
				}
				$kt1=preg_match("/^[0-9]{6}-[0-9]{4}-[0-9]{2}-[0-9]{2}[0-9,a-z]{2}/i", $kod);
				if(!($kt1===1 && strlen($kod)==19)){
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Ошибка в коде товара '.$kod);
					continue;
				}
				$rows = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS.$sstr1);
				if(count($rows) > 0){
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Такой код товара существует! ID='.$rows['0']['id']);
					continue;
				}
				$rows = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS.$sstr);
				if(count($rows) > 0){
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Наименование уже существует в данной группе! ID='.$rows['0']['id']);
					continue;
				}
				$db->SetErrMsgOn(false);
				$res=$db->insert(TABLE_PRODUCTS, $data);
				if ($res>0){
					$okcnt++; 
				} elseif ($res===false) {
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Ошибка при записи строки! SQLERR #'.$db->GetLastError().' '.$db->GetLastErrorMsg());
				} else {
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Неизвестная ошибка при записи строки!');
				}
			}
		} // end for
	} elseif ($_POST['type_l'] == 'update') // Обновление
	{
		foreach($lines as $id=>$line){
			$result[]=$line;
			if (empty($line)) continue;
			$line = explode(";",$line);
			$level = 3;
			$zag1=$zag;
			for($x=count($zag1); $x>=1; $x--){
				if(empty($zag1[$x-1]) || !in_array($zag1[$x-1],$txt)){
					array_splice($zag1,$x-1,1);
					array_splice($line,$x-1,1);
				}else{
					$zag1[$x-1]=trim($zag1[$x-1]);
					$line[$x-1]=trim($line[$x-1]);
					if($zag1[$x-1]=='tsena' || $zag1[$x-1]=='param_starayatsena'){
						$line[$x-1]=str_replace(' ', '', str_replace(',', '.',$line[$x-1]));
					}
					if(($zag1[$x-1]=='enabled' || $zag1[$x-1]=='new' || $zag1[$x-1]=='hit' || $zag1[$x-1]=='spec' || $zag1[$x-1]=='visible' || $zag1[$x-1]=='skidka_day' || $zag1[$x-1]=='isupak') && (!($line[$x-1]==0 || $line[$x-1]==1)))	$line[$x-1]=0;
					if($zag1[$x-1]=='sort' || $zag1[$x-1]=='skidka' || $zag1[$x-1]=='param_razmertsvetka'){
						$line[$x-1]=str_replace(' ', '', str_replace(',', '.',$line[$x-1]));
						if(!(strpos($line[$x-1],'.')===false))	$line[$x-1]=substr($line[$x-1],0,strpos($line[$x-1],'.'));
					}
					if($zag1[$x-1]=='datain') $line[$x-1]=strtotime($line[$x-1]);
					if (!empty($line[$x-1])){
						if ($line[$x-1]{0}=='"') $line[$x-1]=substr($line[$x-1],1);
						if ($line[$x-1]{strlen($line[$x-1])-1}=='"') $line[$x-1]=substr($line[$x-1],0,strlen($line[$x-1])-1);
						$line[$x-1]=str_replace('""','"',$line[$x-1]);
					}
				}
			}
			$line1=$line;
			$cat=1;
			if(!(array_search('cat_id',$zag1)===false)){
				$cat=@(int)$line1[array_search('cat_id',$zag1)];
				if ($cat==0) $cat=1;
				$line1[array_search('cat_id',$zag1)]=$cat;
			}
			$data=array_combine ($zag1, $line1);
			$key=array();
			if( $_POST['key_f'] =='param_kodtovara'){
				$sstr=" where param_kodtovara = ".quote_smart(trim($line[array_search('param_kodtovara',$zag1)]));
				if(!($line1[array_search('param_kodtovara',$zag1)]>' ')){
					set_errfile($errfile,$zag,$line,'Нет кода товара');
					continue;
				}
				$key['param_kodtovara']=$line1[array_search('param_kodtovara',$zag1)];
				unset($data['param_kodtovara']);
			}
			elseif( $_POST['key_f'] =='title'){
				if(!($line1[array_search('title',$zag1)]>' ')){
					set_errfile($errfile,$zag,$line,'Нет наименования');
					continue;
				}
				$sstr=" where title = ".quote_smart($line1[array_search('title',$zag1)]);
				$key['title']=$line1[array_search('title',$zag1)];
				unset($data['title']);
			}
			elseif( $_POST['key_f'] =='id'){
				if(!($line1[array_search('id',$zag1)]>0)){
					set_errfile($errfile,$zag,$line,'Нет ID');
					continue;
				}
				if($line1[array_search('param_kodtovara',$zag1)]==='DELETE'){
					$db->exec("DELETE FROM ".TABLE_PRODUCTS." WHERE id=".$line[array_search('id',$zag)]);
					$okcnt++;
					continue;
				}
				$sstr=" where id = ".quote_smart($line[array_search('id',$zag)]);
				$key['id']=$line1[array_search('id',$zag1)];
				unset($data['id']);

			}
			else die('Неизвестный код операции!') ;
			if(!(array_search('param_kodtovara',$zag)===false)){
				$kt=trim($line[array_search('param_kodtovara',$zag)]);
				$kt1=substr($kt,2,1).substr($kt,5,1).substr($kt,8,1).substr($kt,13,1).substr($kt,16,1);
				$kt1=preg_match("/^[0-9]{6}-[0-9]{4}-[0-9]{2}-[0-9]{2}[0-9,a-z]{2}/i", $kt);
				if(!($kt1===1 && strlen($kt)==19)){
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Ошибка в коде товара '.$kt);
					continue;
				}
			}
			if(!(count($data)>0)){
				$errcnt++;
				set_errfile($errfile,$zag,$line,'Пустой набор для записи!');
				continue;
			}
			$rows = $db->get_rows("SELECT id FROM ".TABLE_PRODUCTS.$sstr);
			$db->SetErrMsgOn(false);
			$cnt=count($rows);
			if($cnt == 1){
				$res=$db->update(TABLE_PRODUCTS, $key, $data);
				if ($res>0){
//					echo '<pre>';print_r($data);print_r($key);echo '</pre>';
					$okcnt++; 
				} elseif ($res===false) {
					$errcnt++;
					set_errfile($errfile,$zag,$line,'Ошибка при записи строки! SQLERR #'.$db->GetLastError().' '.$db->GetLastErrorMsg());
				} else {
					$nccnt++;
//					set_errfile($errfile,$zag,$line,'Изменений нет.');
				}
			} elseif ($cnt == 0){
				$errcnt++;
				set_errfile($errfile,$zag,$line,'Не найдено !');
				continue;
			} else {
				$errcnt++;
				set_errfile($errfile,$zag,$line,"Найдено {$cnt} записей. Множественное обновление невозможно!");
				continue;
			}

		} // end for
	}
	$ff= "<p>Загружено {$okcnt} cтрок.<br>";
	if ($errcnt>0) $ff.= "Не загружено {$errcnt} cтрок.<br>";
	if ($nccnt>0) $ff.= "Без изменений {$nccnt} cтрок.<br>";
	if($errfile>' '){
		$cnt =file_put_contents(ROOT_DIR.'data/out.csv',$errfile);
		$ff.= "</p><p>Сформирован файл отчета об ошибках (".$cnt.' байт) &nbsp;<a href="include.php?place=ishop&action=dwnload&file='.ROOT_DIR.'data/out.csv">Скачать файл.</a>';
	}
	$ff.='</p>';
	$buff=$ff;


}
elseif($_GET['act'] == 'load_opl'){
	?>
	<div style="padding:20px;">
		<span style="">Панель управления</span><br><br>
		Загрузка выполняется из файла формата CSV, начиная со 2-й строки.<br>Колонка 1 - Номер заказа<br>Колонка 2 - Сумма оплаты<br>Колонка 3 - Сумма отгрузки.<br>
		Все суммы должны быть в формате 0.00<br><br>
		<form enctype="multipart/form-data" action="include.php?place=ishop&action=price" method="post">
			<textarea style="display:none" class="mceEditor" style="width: 70%" id="editor1"></textarea>
			<script>tinyMCEInit('editor1')</script>
			<input name="url_abs_nohost" value="" id="url_abs_nohost" style="width:200px" />
			<a style="margin:0px 10px" href="javascript:;" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<? echo SITE_URL ?>'});">[Выбрать файл]</a>
			<input name="pricesb" type="button" onclick="load_opl_file()" value="Загрузить" />
		</form></div>
	<div id="status" style="padding:10px 40px;"></div><?
}
elseif($_GET['act'] == 'save_price'){
	?>
<h3>Выгрузка каталога в формате CSV</h3>
<div style="padding:20px;">
<form enctype="multipart/form-data" action="include.php?place=ishop&action=price" method="post">
<input id="cat_id" type="hidden" value="<?php echo $_GET['cat_id'] ?>" />
<table><tr><td><fieldset>
<legend>Тип выгрузки</legend><label for="s_all"><input name="type_o" type="radio" id="s_all" />Товары</label><br>
<label for="s_grp"><input name="type_o" checked="checked"  type="radio" id="s_grp" />Товары с группами</label></fieldset></td>
<td><fieldset><legend>Фильтр</legend>
<label for="o_op"><input  type="checkbox" id="o_op" />Только открытые</label><br>
<label for="o_tf"><input  type="checkbox" id="o_tf" />Текстовый фильтр по названию</label>
<input value="" id="title_tf" style="width:200px;display: none;" type="text"/><br>
<label for="o_kf"><input  type="checkbox" id="o_kf" />Фильтр по коду</label>
<input name="load_price" value="" id="out_tf" style="width:200px; display: none;" type="text"/>
</fieldset>
</td>
</tr>
</table>
<?=button1('Выгрузить', "save_price_file()",'','download')?>
<script>
	$(document).ready(function () {
	    $('input').iCheck({checkboxClass: 'icheckbox_flat-green',radioClass: 'iradio_flat-green'});	
	});
	$('#o_tf,#o_kf').on('ifChanged',function(){shhdin();});
tinyMCEInit('editor1');

function shhdin() {
if ($("#o_tf").prop('checked')){$("#title_tf").show();} else {$("#title_tf").hide();}
if ($("#o_kf").prop('checked')){$("#out_tf").show();} else {$("#out_tf").hide();}}
</script></form></div>
<div id="status" style="padding:10px 40px;"></div>
<?
}
elseif($_GET['act'] == 'run_sql'){
	$ss=(isset($_SESSION['rsql']))?$_SESSION['rsql']:'';
	?>
	<div style="padding:20px;">
		<form enctype="multipart/form-data" action="include.php?place=ishop&action=sql" method="post">
			<textarea style="width:500px; height:100px;" name="tsql" id="tsql"><?=$ss?></textarea>
			<div style="padding:10px 40px;"><input id="scat_btn" name="pricesb" type="button" onclick="run_sql_run()" value="Выполнить"/></div>
		</form></div>
	<div id="status" style="padding:10px 40px;">
	</div>
	<?
}
elseif($_GET['act'] == 'sqlrun'){
	$res = $db->query($_GET['tsql']);
	if(!($res===false)){
		echo '<p>Обработанно строк: '.$db->num_rows($res).'</p>';
		while($row =  $db->fetch_array($res, PDO::FETCH_ASSOC)){
			print_r ($row);
			echo '<br/>';
		}
		$db->free_result($res);
		$_SESSION['rsql']=$_GET['tsql'];
	}
}
elseif($_GET['act'] == 'update_titles'){
	$title = String_RusCharsDeCode($_GET['title']);
	updates_title(array($_GET['id']),$title);
}
elseif($_GET['act'] == 'back_change_urls'){
	$db->exec("UPDATE ".TABLE_CATEGORIES." SET vlink = ''");
	$db->exec("UPDATE ".TABLE_PRODUCTS." SET vlink = ''");
}
elseif($_GET['act'] == 'change_urls'){
	function upd_vlink($cat_id,$level=0){
		global $db;
		$err=array();
		if($cat_id>11 && $level==0){
			$rows_c = $db->get_rows("SELECT vlink FROM ".TABLE_CATEGORIES." WHERE id = ".$cat_id."");
//			if(!empty($rows_c['0']['vlink'])) $err=array_merge($err,upd_prd($cat_id, $rows_c['0']['vlink']));
		}
		$rows = $db->get_rows("SELECT id, title FROM ".TABLE_CATEGORIES." WHERE parent_id = ".$cat_id."");
		if(count($rows) > 0){
			if($cat_id != 0) $rows_c = $db->get_rows("SELECT vlink FROM ".TABLE_CATEGORIES." WHERE id = ".$cat_id."");
			foreach($rows as $id=>$val){
				$new_title = ru_to_en_lc2($val['title']);
				$pm=array('/skoro/i','/_+/i','/\-+/i','/_+$/i','/\-+$/i','/_?\-_?/i','/_g$/i','/_g(\W)/i');
				$pr=array('','_','-','','','-','','\1');
				$new_title = preg_replace($pm, $pr, $new_title);
				if($cat_id != 0) $new_title = $rows_c['0']['vlink'].'/'.$new_title;
				$rrr=$db->get_rows('select id,title from '.TABLE_CATEGORIES." where vlink='".$new_title."' and not id=".$val['id']);
				if(count($rrr)==0 && !empty($rows_c['0']['vlink'])){
					$db->update(TABLE_CATEGORIES, array('id' => $val['id']), array('vlink' => $new_title));
				}else{
					$err[$val['id'].' '.$val['title']]=$new_title.' *существует в '.$rrr[0]['id'].' '.$rrr[0]['title'];
				}
				$level++;
				$err=array_merge($err,upd_vlink($val['id'],$level));
//				if(!empty($rows_c['0']['vlink']) && !empty($new_title)) $err=array_merge($err,upd_prd($val['id'], $new_title));
			}
		}
		return $err;
	}
	if(isset($_GET['cat_id']) && $_GET['cat_id']>0){
		$res=upd_vlink($_GET['cat_id']);
		if(count($res)>0){
			echo 'Ошибки в:<br><pre>';
			print_r($res);
			echo '</pre><br>';
		} else echo 'Создано.';
	} else echo 'Не выбрана группа.';

}
elseif($_GET['act'] == 'change_urlsprd'){
	function upd_prd($cat_id, $cat_link){
		global $db;
		$err=array();
		$totr='';
		$rows = $db->get_rows("SELECT id, title ,param_naimenovanielatinskoe as latnm,param_sort as sort,param_kodtovara as kod FROM ".TABLE_PRODUCTS." WHERE cat_id = ".$cat_id."");
		foreach($rows as $id=>$val){
			if(!empty($val['latnm']) && !empty($val['sort'])) $new_title1=$val['latnm'].' '.$val['sort'];
			else $new_title1 = $val['title'];
			$new_title1=str_ireplace('*','_',$new_title1);
			$totr.=$val['id'].'in. '.$new_title1." * ";
/*			$new_title=ru_to_en_lc2($new_title1);
			$new_title=$new_title.'_id'.$val['id'];
			$new_title = $cat_link.'/'.$new_title;
			$rrr=$db->get_rows('select id,title from '.TABLE_PRODUCTS." where vlink='".$new_title."' and not id=".$val['id']);
			if(count($rrr)==0){
				$db->update(TABLE_PRODUCTS, array('id' => $val['id']), array('vlink' => $new_title));
			}else{
				$err['Товар '.$val['id'].' '.$val['title']]=$new_title.' *существует в '.$rrr[0]['id'].' '.$rrr[0]['title'];
			}*/
		}
		$slovo=str_ireplace('&','_',$totr);
		$slovo=str_ireplace('?','_',$slovo);
		$lnn='&text='.$slovo.'&lang=ru-en';
		$te=false;
		$te=@file_get_contents('https://translate.yandex.net/api/v1.5/tr/translate?key=trnsl.1.1.20150116T085751Z.3fdb2fff0ee5f5f7.34662d372f4d7ba7250a32e40977c001af00c53b'.$lnn);
		if (!($te===false)){
			$slovo=preg_replace('/.*\n*.*text>(.*)<\/text.*/im','\1',$te);
		}
		$slovo=explode('*',$slovo);
		foreach($slovo as  $val){
			if (empty(trim($val))) continue;
			$val=explode('in.',$val);
			if (count($val)<>2 || empty(trim($val[0])) || empty(trim($val[1]))) continue;
			$new_title=ru_to_en_lc2(trim($val[1]),false);
			$new_title = $cat_link.'/'.$new_title.'_id'.trim($val[0]);
			$rrr=$db->get_rows('select id,title from '.TABLE_PRODUCTS." where vlink='".trim($new_title)."' and not id='".trim($val[0])."'");
			if(count($rrr)==0){
				$db->update(TABLE_PRODUCTS, array('id' => $val[0]), array('vlink' => trim($new_title)));
			}else{
				$err['Товар '.$val[0].' '.$new_title]=' *существует в '.$rrr[0]['id'].' '.$rrr[0]['title'];
			}
			
		}
		return $err;
	}

	function upd_vlinkprd($cat_id,$level=0){
		global $db;
		$err=array();
		$rows_c = $db->get_rows("SELECT vlink FROM ".TABLE_CATEGORIES." WHERE id = ".$cat_id."");
		if(!empty($rows_c['0']['vlink'])) $err=array_merge($err,upd_prd($cat_id, $rows_c['0']['vlink']));
		$rows = $db->get_rows("SELECT id, vlink FROM ".TABLE_CATEGORIES." WHERE parent_id = ".$cat_id."");
		foreach($rows as $id=>$val){
			$err=array_merge($err,upd_vlinkprd($val['id'],$level));
		}
		return $err;
	}
	if(isset($_GET['cat_id']) && $_GET['cat_id']>0){
		$res=upd_vlinkprd($_GET['cat_id']);
		if(count($res)>0){
			echo 'Ошибки в:<br><pre>';
			print_r($res);
			echo '</pre><br>';
		} else echo 'Создано.';
	} else echo 'Не выбрана группа.';

}
elseif($_GET['act'] == 'change_urlt'){
	$tcat = $db->query_first("SELECT parent_id,title FROM ".TABLE_CATEGORIES." WHERE id = '".$_GET['cat_id']."'");
	$pcat = $db->query_first("SELECT vlink FROM ".TABLE_CATEGORIES." WHERE id = '".$tcat['parent_id']."'");
	$new_title = ru_to_en_lc2($tcat['title']);
	$pm=array('/skoro/i','/_+/i','/\-+/i','/_+$/i','/\-+$/i','/_?\-_?/i','/_g$/i','/_g(\W)/i');
	$pr=array('','_','-','','','-','','\1');
	$new_title = preg_replace($pm, $pr, $new_title);
	$new_vl='';
	if(!empty($pcat['vlink'])) $new_vl = $pcat['vlink'].'/'.$new_title;
	echo (empty($new_vl))?'Err':$new_vl;
	exit;
}
elseif($_GET['act'] == 'save_csv'){
	$sstr='';
	$cnt=0;
	$ofile='Код товара;ID;1С статус;Название товара;Цена;Старая цена;Скидка;ПерID;Вид (русское наименование);Группа;Сорт;Наименование латинское;Производитель;Высота, см;Ширина, см;Аромат;Аромат (для поиска);Цветение;Размер цветка;Тип цветка;Период цветения;Окраска цветка;Окраска цветка (для поиска);Окраска листвы;Морозостойкость;Устойчивость листвы к заболеваниям: мучнистая роса;Устойчивость листвы к заболеваниям: черная пятнистость;Тип почвы;Световой режим;Стандарт поставки;Срок поставки;Дата поставки;Описание;Краткое описание;Title страницы;МЕТА - Описание;МЕТА - ключевые слова;ЭтоУпак;Количество;ссылка на фото;Открыт;Видимость;Хит продаж;Новинка;Скидка дня;Сортировка;Спецпредложение;Cсылка на родителя;Ссылка на группу;ЧПУ;Родитель;Текст';
	$ofile.="\n".'param_kodtovara;id;ren;title;tsena;param_starayatsena;skidka;tper;param_vid;param_gruppa;param_sort;param_naimenovanielatinskoe;param_proizvoditel;param_visota;param_shirina;param_aromat;param_aromatdlyapoiska;param_tsvetenie;param_razmertsvetka;param_tiptsvetka;param_periodtsveteniya;param_okraskatsvetka;param_okraskatsvetkadlyapoiska;param_okraskalistvi;param_morozostoykost;param_muchnistayarosa;param_chernayapyatnistost;param_tippochvi;param_svetovoyrezhim;param_standartpostavki;param_srokpostavki;datain;param_polnoeopisanie;opisanie;metatitle;metadesc;metakeys;isupak;param_kolichestvo;foto;enabled;visible;hit;new;skidka_day;sort;spec;srcid;cat_id;vlink;parent_id;text'."\n";
	$file='';
	if($_GET['type']==1){
		if(!empty($value))	$sstr.=' and p.title like "%'.$value.'%"';
		if($_GET['f_op']==1)	$sstr.=' and p.enabled=1 and p.visible=1 and c.enabled=1 and c.visible=1';
		if(!empty($_GET['out_tf']))	$sstr.=' and p.param_kodtovara like "%'.$_GET['out_tf'].'%"';
		$q="SELECT * from ".TABLE_PRODUCTS.' p where 1 = 1'.$sstr." ORDER BY p.param_kodtovara";
		if($_GET['f_op']==1) $q="SELECT p.* from ".TABLE_PRODUCTS.' p, '.TABLE_CATEGORIES.' c where c.id = p.cat_id'.$sstr." ORDER BY p.param_kodtovara";
		$rows = $db->get_rows($q);
		$cnt =file_put_contents(ROOT_DIR.'data/out.csv',$ofile);
		$cnt=$cnt+Get_csvline($rows,'',1,ROOT_DIR.'data/out.csv');
	}
	elseif($_GET['type']==2){
		$file.=Get_SubCat(11,$_GET['f_op'],((isset($value))?$value:''),$_GET['out_tf']);
	}
	if(!empty($file)){
		$cnt =file_put_contents(ROOT_DIR.'data/out.csv',$ofile.$file);
	}
	if ($cnt>0){
		$ff=ROOT_DIR.'data/out.csv';
		echo '<p>Успешно выгружено '.$cnt.' байт. <a href="include.php?place=ishop&action=dwnload&file='.$ff.'">Скачать файл.</a></p>';
	} else{
		echo '<p>Ничего не выгружено</p>';
	}
}
elseif($_GET['act'] == 'save_userscsv'){
	$sstr='';
	$cnt=0;
	$file='';
	$feed_inf = $db->get(TABLE_FEED, array('enabled'=>1));
	$rows = $db->get_rows("SELECT * from ".TABLE_USERS." order by id");
	foreach($rows as $id=>$val){
		$line=$val['id'].';';
		$line.='"'.conv_str($val['email']).'";';
		$line.='"'.conv_str($val['login']).'";';
		$line.='"'.conv_str($val['pass']).'";';
		$inf = unserialize($val['info']);
		foreach($feed_inf as $id1=>$val1){
			$line.='"'.conv_str($inf[$val1['id']]).'";';
		}
		$file.=$line."\n";
	}
	$cnt =file_put_contents(ROOT_DIR.'data/outu.csv',$file);
	$ff=ROOT_DIR.'data/outu.csv';
	?>
	<p>Успешно выгружено <?php echo $cnt." байт." ?>&nbsp;
		<a href="include.php?place=ishop&action=dwnload&file=<? echo $ff?>">Скачать файл.</a></p>
	<?
}
elseif($_GET['act'] == 'load_opl_file'){
	if(!preg_match("/\.csv/i", $_GET['load_file'])){
		echo 'Неверный тип файла '.$_GET['load_file'];
		exit();
	}
	$file = file_get_contents(ROOT_DIR.$_GET['load_file']);
	$lines = explode("\n",$file);
	$cnt=0;
	$err=false;
	$errtxt='';
	$errfile='';
	$okcnt=0;
	$errcnt=0;
	foreach($lines as $id=>$line){
		if($id<1) continue;
		if(!($line>' ')) continue;
		$xline = explode(";",$line);
		$zakid=$xline[0];
		if(!($zakid>0)){
			$errcnt++;
			echo('Ошибка в строке: '.$line.'<br>');
			continue;
		}
		$zakopl=$xline[1];
		$zakotgr=$xline[2];
		if(!($zakopl>0)) $zakopl=0;
		if(!($zakotgr>0)) $zakotgr=0;
		$res=$db->exec('update '.TABLE_ORDERS.' set sumopl='.$zakopl.', sumotgr='.$zakotgr.' where id='.$zakid);
		if(!$res){
			echo('Ошибка при обновлении '.$zakid.'<br>');
			$errcnt++;
		}
		else{
			$okcnt++;
		}
	}
	echo('Успешно загружено: '.$okcnt.' строк, строк с ошибками: '.$errcnt );


}
elseif($_GET['act'] == 'load_user_file'){
	if(!preg_match("/\.csv/i", $_GET['load_file'])){
		echo 'Неверный тип файла '.$_GET['load_file'];
		exit();
	}
	$file = file_get_contents(ROOT_DIR.$_GET['load_file']);
	$lines = explode("\n",$file);
	$cnt=0;
	$err=false;
	$errtxt='';
	$errfile='';
	$okcnt=0;
	$errcnt=0;
	foreach($lines as $id=>$line){
		if(!($line>' ')) continue;
		$xline = explode(";",$line);
		$id=$xline[0];
		if(!($id>0)){
			$errcnt++;
			echo('Ошибка в строке: '.$line.'<br>');
			continue;
		}
		$ln=$xline[1];
		$fn=$xline[2];
		$mn=$xline[3];
		$inf = $db->get(TABLE_USERS, $id);
		$inf = unserialize($inf['info']);
		$inf[9]=$ln;
		$inf[8]=$fn;
		$inf[11]=$mn;
		$inf[14]=$xline[4];
		$data = array('info' => serialize($inf));
		$db->update(TABLE_USERS, array('id' => $id), $data);
		$okcnt++;
	}
	echo('Успешно загружено: '.$okcnt.' строк, строк с ошибками: '.$errcnt );


}
elseif($_GET['act'] == 'add_color'){
	$db->insert(TABLE_COLOR, array('id_mat'=>$_GET['id_mat'], 'color'=>$_GET['name']));
	echo edit_materials($_GET['id_mat']);
}
elseif($_GET['act'] == 'update_color'){
	$db->update(TABLE_COLOR, array('id'=>$_GET['id']), array('color'=>$_GET['name']));
	echo edit_materials($_GET['id_mat']);
}
elseif($_GET['act'] == 'del_color'){
	$db->delete(TABLE_COLOR, array('id'=>$_GET['id']));
	echo edit_materials($_GET['id_mat']);
}
elseif($_GET['act'] == 'back_material'){
	echo get_materials();
}
elseif($_GET['act'] == 'add_material'){
	$db->insert(TABLE_MATERIAL, array('name'=>$_GET['name']));
	echo get_materials();
}
elseif($_GET['act'] == 'edit_material'){
	echo edit_materials($_GET['id']);
}
elseif($_GET['act'] == 'update_material'){
	$db->update(TABLE_MATERIAL, array('id'=>$_GET['id']), array('name'=>$_GET['name']));
	echo get_materials();
}
elseif($_GET['act'] == 'del_material'){
	$db->delete(TABLE_MATERIAL, array('id'=>$_GET['id']));
	echo get_materials();
}
elseif($_GET['act'] == 'del_adv_foto'){
	$db->delete(TABLE_PRD_FOTO, array('id'=>$_GET['foto_id']));
}
elseif($_GET['act'] == 'sort_adv_foto'){
	$db->update(TABLE_PRD_FOTO, array('id'=>$_GET['foto_id']), array('sort'=>intval($_GET['value'])));
}
elseif($_GET['act'] == 'del_comments'){
	$db->exec("DELETE FROM ".TABLE_COMMENTS." WHERE id=".$_GET['id']." && module = 2");
}
elseif($_GET['act'] == 'comments_on'){
	$inf = $db->get(TABLE_COMMENTS, array('id' => $_GET['id']));
	$new = ($inf['0']['enabled'] == 1) ? 0 : 1;
	$db->update(TABLE_COMMENTS, array('id' => $_GET['id']), array('enabled' => $new));
	echo $new;
}
elseif($_GET['act'] == 'sort_cats'){
	$val = intval($_GET['value']);

	$p = array('sort' => $val);

	if($_GET['type'] == 'cat'){
		$db->update(TABLE_CATEGORIES, $_GET['id'], $p);
	}
	elseif($_GET['type'] == 'prd'){
		$db->update(TABLE_PRODUCTS, $_GET['id'], $p);
	}

	echo $val;
}
elseif($_GET['act'] == 'delete_cats'){
	$prds_cats = array();
	$d_arr = array();
	$buff = '0';
	if(!empty($_GET['cats'])){
		$cats = array_del_empty(explode('_',$_GET['cats']));
		$d_arr = get_all_sub_cats(array_del_empty($cats));
		$d_arr = array_merge($d_arr, $cats);
		$prds_cats = get_all_prds($d_arr);
	}

	$_GET['prds'] = (!empty($_GET['prds'])) ? $_GET['prds'] : '';
	$prds = array_del_empty(explode('_',$_GET['prds']));
	$prds = array_merge($prds, $prds_cats);

	if(count($prds) > 0){
		$q = "DELETE FROM ".TABLE_PRODUCTS." WHERE id IN (".implode(',',$prds).")";
		$q2 = "DELETE FROM ".TABLE_PRD_FOTO." WHERE product_id IN (".implode(',',$prds).")";
		$q3 = "DELETE FROM ".TABLE_CHARS_VALUES." WHERE prd_id IN (".implode(',',$prds).")";
		$cnt3=$db->exec($q);
		$cnt1=$db->exec($q2);
		$cnt2=$db->exec($q3);
	}

	if(count($d_arr) > 0){
		$q = "DELETE FROM ".TABLE_CATEGORIES." WHERE id IN (".implode(',',$d_arr).")";
		$q1 = "DELETE FROM ".TABLE_CHARS." WHERE cat_id IN (".implode(',',$d_arr).")";
		$cnt3=$db->exec($q);
		$cnt4=$db->exec($q1);
	}

	if($cnt3 > 0) $buff = '1';
}
elseif($_GET['act'] == 'delete_user'){
	$id=$_GET['id'];
	$buff = '0';
	if($id>0){

		$db->exec('update '.TABLE_ORDERS_I.' set user_id=0 where id='.$db->escape_string($id));
		$db->exec('update '.TABLE_ORDERS.' set user_id=0 where id='.$db->escape_string($id));
		$cnt=$db->delete(TABLE_USERS,array('id'=>$id));
		if($cnt > 0){
			$buff = '1';
		}
	}
}
elseif($_GET['act'] == 'delete_order'){
	$q = "DELETE FROM ".TABLE_ORDERS_PRD." WHERE order_id='".$_GET['id']."'";
	$cnt=$db->exec($q);
	$q2 = "DELETE FROM ".TABLE_ORDERS." WHERE id='".$_GET['id']."'";
	$q = "DELETE FROM ".TABLE_ORDERS_REG." WHERE orderid='".$_GET['id']."'";
	$cnt=$db->exec($q);
	$q = "DELETE FROM ".TABLE_ORDERS_LOG." WHERE orderid='".$_GET['id']."'";
	$cnt=$db->exec($q);
	$cnt2=$db->exec($q2);
	if($cnt2 > 0){
		$buff = '1';
	}
	else{
		$buff = '0';
	}
}
elseif($_GET['act'] == 'order_swload'){
	$row=$db->get(TABLE_ORDERS,$_GET['id']);
	$res=($row['ld']==0)?1:0;
	$db->update(TABLE_ORDERS,$_GET['id'],array('ld'=>$res));
	$row=$db->get(TABLE_ORDERS,$_GET['id']);
	$buff = $row['ld'];
}
elseif($_GET['act'] == 'delete_spec'){
	$buff = '0';
	$prds_cats = array();
	$_GET['prds'] = (!empty($_GET['prds'])) ? $_GET['prds'] : '';
	$prds = array_del_empty(explode('_',$_GET['prds']));
	if(count($prds) > 0){
		$q = "Update ".TABLE_PRODUCTS." set spec=0 WHERE id IN (".implode(',',$prds).")";
		if($db->exec($q)>0) $buff = '1';;
	}
}
elseif($_GET['act'] == 'delete_new'){
	$buff = '0';
	$prds_cats = array();
	$_GET['prds'] = (!empty($_GET['prds'])) ? $_GET['prds'] : '';
	$prds = array_del_empty(explode('_',$_GET['prds']));
	if(count($prds) > 0){
		$q = "Update ".TABLE_PRODUCTS." set new=0 WHERE id IN (".implode(',',$prds).")";
		if($db->exec($q)>0) $buff = '1';
	}
}
elseif($_GET['act'] == 'delete_hit'){
	$buff = '0';
	$prds_cats = array();
	$_GET['prds'] = (!empty($_GET['prds'])) ? $_GET['prds'] : '';
	$prds = array_del_empty(explode('_',$_GET['prds']));
	if(count($prds) > 0){
		$q = "Update ".TABLE_PRODUCTS." set hit=0 WHERE id IN (".implode(',',$prds).")";
		if($db->exec($q)>0) $buff = '1';
	}
}
elseif($_GET['act'] == 'copy_cats'){
	$cats = array_del_empty(explode('_',$_GET['cats']));
	$prds = array_del_empty(explode('_',$_GET['prds']));
	$buff = ajax_copy_window($cats, $prds, $_GET['cat_id']);
}
elseif($_GET['act'] == 'set_recpar'){
	$cats = array_del_empty(explode('_',$_GET['cats']));
	$buff = ajax_setrecpar($cats);
}
elseif($_GET['act'] == 'move_cats'){
	$cats = array_del_empty(explode('_',$_GET['cats']));
	$prds = array_del_empty(explode('_',$_GET['prds']));
	$buff = ajax_move_window($cats, $prds,$_GET['cat_id']);
}
elseif($_GET['act'] == 'makegr'){
	$prds = array_del_empty(explode('_',$_GET['prds']));
	$buff = ajax_makegr($prds);
}
elseif($_GET['act'] == 'makegr_ok'){
	$prds = array_del_empty(explode(',',$_GET['prds']));
	foreach($prds as $val){
		$db->update(TABLE_PRODUCTS,$val,array('srcid'=>$_GET['val']));
	}
	$buff = '1';
}
elseif($_GET['act'] == 'copy_to'){
	$cats = array_del_empty(explode(',',$_GET['cats']));
	$prds = array_del_empty(explode(',',$_GET['prds']));

	for($i=0; $i < $_GET['kolvo']; $i++){
		foreach($cats as $id){
			CopyCategoryFull($id, $_GET['id']);
		}

		foreach($prds as $id){
			CopyProduct($id, $_GET['id']);
		}
	}

	$buff = get_sub_cats_page($_GET['id']);
}
elseif($_GET['act'] == 'move_to'){
	$cats = array_del_empty(explode(',',$_GET['cats']));
	$prds = array_del_empty(explode(',',$_GET['prds']));

	foreach($cats as $id){
		if($_GET['id'] != $id){
			$db->update(TABLE_CATEGORIES, array('id'=>$id), array('parent_id '=>$_GET['id'],'vlink'=>''));
		}
	}

	foreach($prds as $id){
		$db->update(TABLE_PRODUCTS, array('id'=>$id), array('cat_id'=>$_GET['id'],'vlink'=>''));
	}
	$buff = get_sub_cats_page($_GET['id']);
}
elseif($_GET['act'] == 'get_cats_s'){
	$_SESSION['katalogid']=$_GET['id'];
	$_SESSION['katalogpg']=$_GET['page'];
	$content =  get_sub_cats_page($_GET['id'], $_GET['page'], $_GET['type'],$_GET['srt']);
	$btns = get_cat_btns();
	$buff = get_module($btns, 1, $content, 'Редактирование каталога');

}
elseif($_GET['act'] == 'get_upak'){
	$content =  get_upak($_GET['id'], $_GET['page']);
	$btns = array();
	$buff = get_module($btns, 1, $content, 'Cтандарты поставки');

}
elseif($_GET['act'] == 'get_tper'){
	$content =  get_tper($_GET['id'], $_GET['page']);
	$btns = array();
	$buff = get_module($btns, 1, $content, 'Таблица группировки заказов');

}
elseif($_GET['act'] == 'get_cats'){
	$content =  get_sub_cats_page($_GET['id'], $_GET['page']);
	$_SESSION['katalogid']=$_GET['id'];
	$_SESSION['katalogpg']=$_GET['page'];
	$btns = get_cat_btns(1);
	$buff = get_module($btns, 1, $content, 'Редактирование каталога');

}
elseif($_GET['act'] == 'open_prdinord'){
	$content =  get_open_prdinord($_GET['page']);
	$btns = get_cat_btns(4);
	$buff = get_module($btns, 1, $content, 'Дерево каталога');
}
elseif($_GET['act'] == 'get_catstree'){
	if(isset($_GET['v1']) && $_GET['v1']==1){
		$buff=  get_newtreesubcat(0,1,'disable_controls','tre0');
	}
	elseif(isset($_GET['vlink']) && $_GET['vlink']==1){
		$content =  get_newtreesubcat(0,1,'vlink','mtr');
		$content.=button1('Развернуть все','setop($("#11"));','','list');
		$content.='&nbsp;<input style="height: 12px;" type="checkbox" class="check" id="markrec"/> Отобразить рекомендованные товары<script type="text/javascript">$(document).ready(function () {	if ($(this).prop("checked")==true){$(".trec").css("display","block");} else {$(".trec").css("display","none");} $("#markrec").click(function(){
		if ($(this).prop("checked")==true){$(".trec").css("display","block");} else {$(".trec").css("display","none");}});});</script>';
		$buff=$content;
	} else{

		$_SESSION['katalogid']=$_GET['id'];
		$_SESSION['katalogpg']=$_GET['page'];
		$content =  get_newtreesubcat(0,1,'','mtr');
		$content.=button1('Развернуть все','setop($("#11"));','','list');
		$content.='&nbsp;<input style="height: 12px;" type="checkbox" class="check" id="markrec"/> Отобразить рекомендованные товары<script type="text/javascript">$(document).ready(function () {	if ($(this).prop("checked")==true){$(".trec").css("display","block");} else {$(".trec").css("display","none");} $("#markrec").click(function(){
		if ($(this).prop("checked")==true){$(".trec").css("display","block");} else {$(".trec").css("display","none");}});});</script>';
		$btns = get_cat_btns(2);
		$buff = get_module($btns, 1, $content, 'Дерево каталога');
	}
}
elseif($_GET['act'] == 'get_cats1'){
	$content =$_GET['id'];
	$res= $db->get(TABLE_PRODUCTS,$_GET['id']);
	$id=$res['cat_id'];
	$content =  get_sub_cats_page($id, 0);
	$btns = get_cat_btns(1);
	$buff = get_module($btns, 1, $content, 'Редактирование каталога');
}
elseif($_GET['act'] == 'delete_upak'){
	$q = "DELETE FROM ".TABLE_UPAK." WHERE id=".quote_smart(trim($_GET['ID']));
	$buff = '0';
	if($db->exec($q) > 0) $buff = '1';
}
elseif($_GET['act'] == 'save_upak'){
	$ret=FALSE;
	if(count($_POST)>0){
		if(!empty($_POST['k_name'])){
			$data = array('name' => $_POST['k_name'],'descr' => $_POST['k_descr'],'id'=>$_POST['f_id']);
			$ret=$db->update(TABLE_UPAK, array('id' => $_POST['k_id']), $data);
		}
	}
	if($ret===false) echo '0';
	else echo '1';
	exit;
}
elseif($_GET['act'] == 'save_tper'){
	$ret=FALSE;
	if(count($_POST)>0){
		if(!empty($_POST['k_name'])){
			$data = array('name' => $_POST['k_name'],'id'=>$_POST['f_id']);
			$dt=DateTime::createFromFormat('d.m.Y', $_POST['k_dt']);
			$data['date']=$dt->getTimestamp();
			$ret=$db->update(TABLE_TPER, array('id' => $_POST['k_id']), $data);
		}
	}
	if($ret===false) echo '0';
	else echo '1';
	exit;
}

elseif($_GET['act'] == 'save_cat'){
	$ret=0;
	if(count($_POST)>0){
		$last_id = 0;
		if(isset($_POST['cat_desc1'])) $_POST['cat_desc']=$_POST['cat_desc1'];
		$params = Array (
			'title' => $_POST['title'],
			'metatitle' => $_POST['metatitle'],
			'metadesc' => $_POST['metadesc'],
			'metakeys' => $_POST['metakeys'],
			'text' => $_POST['cat_desc'],
			'foto' => $_POST['foto'],
			'vlink' => $_POST['vlink'],
			'parent_id' => $_POST['pid'],
			'promokey' => $_POST['promokey']
		);
		if(!empty($params['vlink'])){
			$res=$db->query_first('select count(id) as cnt from '.TABLE_CATEGORIES." WHERE NOT id='".$_POST['cid']."' and vlink='".$params['vlink']."'");
			if($res['cnt']>0){
				echo "alert('Не уникальное значение поля ЧПУ ссылка!');";
				exit;
			}
		}
		$params['onprom'] = intval(!empty($_POST['onprom']) && $_POST['onprom'] == 1);
		$params['enabled'] = intval((!empty($_POST['enabled']) && $_POST['enabled'] == 1) || (!empty($_POST['onprom']) && $_POST['onprom'] == 1));
		$params['visible'] = intval((!empty($_POST['visible']) && $_POST['visible'] == 1) || (!empty($_POST['onprom']) && $_POST['onprom'] == 1));
		$params['recpar'] = intval(!empty($_POST['recpar']) && $_POST['recpar'] == 1);
		if($_POST['cid'] == -1){
			$params['gr_id'] = '5';
			$db->insert(TABLE_CATEGORIES,$params);
			$ret=$db->insert_id();
		}
		else{
			$db->update_notrim(TABLE_CATEGORIES,$_POST['cid'],$params);
			$ret=$_POST['cid'];
		}
		if($sets['mod_rec_prds']){
			$db->delete(TABLE_GRREC, array('grid'=>$_POST['cid']));
			if(!empty($_POST['r_prds'])){
				$r_prdsv = explode(',', $_POST['r_prds']);
				foreach($r_prdsv as $s_id=>$val){
					if($val>0) $db->insert(TABLE_GRREC, array('prdid'=>$val, 'grid'=>$_POST['cid']));
				}
			}
		}

	}
	if($ret>0) echo "save_ok".$ret;
	else echo "alert('Неизвестная ошибка!');";

}
elseif($_GET['act'] == 'save_new_upak'){
	$ret=FALSE;
	if(count($_POST)>0){
		if(!empty($_POST['k_name'])){
			$data = array('name' => $_POST['k_name'],'descr' => $_POST['k_descr'],'id'=> strtoupper($_POST['k_id']));
			$ret=$db->insert(TABLE_UPAK, $data);
		}
	}
	if($ret>0) echo '1';
	else echo '0';

}

elseif($_GET['act'] == 'readtree'){
	$_GET['st']=((isset($_GET['st']))?$_GET['st']:11);
	echo get_newtreesubcat($_GET['st'],1,'disable_controls');

}
elseif($_GET['act'] == 'getcatnm'){
	echo get_catnm($_GET['id'],0);

}
elseif($_GET['act'] == 'new_upak'){
	$inf = $db->fetch_array($db->query("select max(id) as id from ".TABLE_UPAK));

	?>
	<div align="left">
		<form id="fuser" action="" enctype="multipart/form-data">

			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="news_td" style="padding-right:20px;">ID</td>
					<td class="news_td"><input id="fid" class="fbinp" style="width:50px" type="text" name="k_id" value="<?php  echo $inf['id']?>"/></td>
				</tr>
				<tr>
					<td class="news_td" >Название кратко</td>
					<td class="news_td"><input id="fnm" class="fbinp" style="width:150px" type="text" name="k_name" value=""/>
					</td>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;">Описание</td>
					<td class="news_td">
						<textarea class="fbinp" name="k_descr"></textarea></td>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;"></td>
					<td class="news_td"><?php echo button('Cохранить',"SaveNewUpak(this);")?></td>
				</tr>

			</table>
		</form>
	</div>
	<?
	exit;
}
elseif($_GET['act'] == 'edit_upak'){
	$inf = $db->get(TABLE_UPAK, $_GET['ID']);
	?>
	<div align="left">
		<form id="fuser" action="" enctype="multipart/form-data">

			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="news_td" style="padding-right:20px;">ID</td>
					<td class="news_td"><input id="fid" class="fbinp" readonly="readonly" style="width:50px" type="text" name="f_id" value="<?php  echo $inf['id']?>"/></td>
				</tr>
				<tr>
					<td class="news_td" >Название кратко</td>
					<td class="news_td"><input id="fnm" class="fbinp" readonly="readonly" style="width:150px" type="text" name="k_name" value="<?php  echo $inf['name']?>"/>
					</td><input type="hidden" name="k_id" value="<?=$inf['id']?>"/>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;">Описание</td>
					<td class="news_td">
						<textarea readonly="readonly" class="fbinp" name="k_descr"><?php  echo $inf['descr']?></textarea></td>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;"></td>
					<td class="news_td"><?php echo button('Редактировать',"SaveUpak(this);")?></td>
				</tr>

			</table>
		</form>
	</div>
	<?
	exit;
}
elseif($_GET['act'] == 'save_new_tper'){
	$ret=FALSE;
	if(count($_POST)>0){
		if(!empty($_POST['k_name'])){
			$data = array('name' => $_POST['k_name'],'id'=>$_POST['k_id']);
			$dt=DateTime::createFromFormat('d.m.Y', $_POST['k_dt']);
			$data['date']=$dt->getTimestamp();
			$ret=$db->insert(TABLE_TPER, $data);
		}
	}
	if($ret>0) echo '1';
	else echo '0';

}

elseif($_GET['act'] == 'new_tper'){
	$inf = $db->fetch_array($db->query("select max(id)+1 as id from ".TABLE_TPER));

	?>
	<div align="left">
		<form id="fuser" action="" enctype="multipart/form-data">

			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="news_td" style="padding-right:20px;">ID</td>
					<td class="news_td"><input id="fid" class="fbinp" style="width:50px" type="text" name="k_id" value="<?php  echo $inf['id']?>"/></td>
				</tr>
				<tr>
					<td class="news_td" >Название</td>
					<td class="news_td"><input id="fnm" class="fbinp" style="width:400px" type="text" name="k_name" value=""/>
					</td>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;">Дата в формате dd.mm.yyyy</td>
					<td class="news_td">
						<input id="fdt" class="fbinp" style="width:400px" type="text" name="k_dt" value="<?=date('d.m.Y')?>"/></td>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;"></td>
					<td class="news_td"><?php echo button('Cохранить',"SaveNewTper(this);")?></td>
				</tr>

			</table>
		</form>
	</div>
	<?
	exit;
}
elseif($_GET['act'] == 'edit_tper'){
	$inf = $db->get(TABLE_TPER, $_GET['ID']);
	?>
	<div align="left">
		<form id="fuser" action="" enctype="multipart/form-data">

			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="news_td" style="padding-right:20px;">ID</td>
					<td class="news_td"><input id="fid" class="fbinp" readonly="readonly" style="width:50px" type="text" name="f_id" value="<?php  echo $inf['id']?>"/></td>
				</tr>
				<tr>
					<td class="news_td" >Название</td>
					<td class="news_td"><input id="fnm" class="fbinp" readonly="readonly" style="width:400px" type="text" name="k_name" value="<?php  echo $inf['name']?>"/>
					</td><input type="hidden" name="k_id" value="<?=$inf['id']?>"/>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;">Дата ожидаемых отгрузок</td>
					<td class="news_td">
						<input id="fdt" class="fbinp" readonly="readonly" style="width:150px" type="text" name="k_dt" value="<?php  echo date('d.m.Y',$inf['date'])?>" /></td>
				</tr>
				<tr>
					<td class="news_td" style="padding-right:20px;"></td>
					<td class="news_td"><?php echo button('Редактировать',"SaveTper(this);")?></td>
				</tr>

			</table>
		</form>
	</div>
	<?
	exit;
}

elseif($_GET['act'] == 'update_cat_html'){

	$_GET['cat'] = (isset($_GET['cat'])) ? $_GET['cat'] : '-1';
	$content =  ajax_edit_cat_form($_GET['id'], $_GET['cat']);

	if(($_GET['cat'])){
		$btns = array(
			'Закрыть'=>array('id'=>2,'href'=>'swap_pan()'),
			'Категория'=>array('id'=>1,'href'=>'gotourl(\'include.php?place=ishop#update_cat_html('.$_GET['id'].')\')'),
			'Характеристики'=>array('id'=>3,'href'=>'gotourl(\'include.php?place=ishop#update_cat_mod_html('.$_GET['id'].')\')')
		);

		$module['path'] = 'Редактирование раздела '.$_GET['id'];
	}
	else{
		$btns = array(
			'Закрыть'=>array('id'=>2,'href'=>'swap_pan()'),
			'Категория'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=ishop&action=category&cID=-1\'')
		);

		$module['path'] = 'Создание нового раздела';
	}



	$buff = get_module($btns, 1, $content, $module['path']);

}
elseif($_GET['act'] == 'update_prd_html'){
	$content =  ajax_product_edit($_GET['id'], $_GET['cat']);

	$btns = array(
		'Товар'=>array('id'=>1,'href'=>'javascript:void(0)')
	);

	$module['path'] = 'Товар';

	$buff = get_module($btns, 1, $content, $module['path']);

}
elseif($_GET['act'] == 'update_cat_mod_html'){

	$btns = array(
		'Категория'=>array('id'=>2,'href'=>'gotourl(\'include.php?place=ishop#update_cat_html('.$_GET['id'].')\')'),
		'Характеристики'=>array('id'=>1,'href'=>'gotourl(\'include.php?place=ishop#update_cat_mod_html('.$_GET['id'].')\')')
	);

	$_GET['cat'] = (isset($_GET['cat'])) ? $_GET['cat'] : '-1';
	$content =  ajax_edit_cat_mod_form($_GET['id'], $_GET['cat']);
	$module['path'] = 'Редактирование раздела';

	$buff = get_module($btns, 1, $content, $module['path']);

}
elseif($_GET['act'] == 'part_on'){
	if($_GET['type'] == 'cat'){
		$row = $db->fetch_array($db->query("SELECT enabled,onprom FROM ".TABLE_CATEGORIES." where `id` = '".$_GET['id']."'"));
		$db->query("UPDATE ".TABLE_CATEGORIES." SET enabled = '".intval(!$row['enabled'] || $row['onprom'])."' where `id` = '".$_GET['id']."'");
		$row = $db->fetch_array($db->query("SELECT enabled FROM ".TABLE_CATEGORIES." where `id` = '".$_GET['id']."'"));
		$text = ($row['enabled']==1)?'Включен':'Выключен';
	}
	elseif($_GET['type'] == 'prd'){
		$row = $db->fetch_array($db->query("SELECT enabled FROM ".TABLE_PRODUCTS." where `id` = '".$_GET['id']."'"));
		$db->exec("UPDATE ".TABLE_PRODUCTS." SET enabled = '".intval(!$row['enabled'])."' where `id` = '".$_GET['id']."'");
		$text = (!$row['enabled']==1)?'Включен':'Выключен';
	}
	$buff = $text;
}
elseif($_GET['act'] == 'part_on_acl'){
	if($_GET['type'] == 'cat'){
	}
	elseif($_GET['type'] == 'prd'){
		$row = $db->fetch_array($db->query("SELECT saletype FROM ".TABLE_PRODUCTS." where `id` = '".$_GET['id']."'"));
		$db->exec("UPDATE ".TABLE_PRODUCTS." SET saletype = '".intval(!$row['saletype'])."' where `id` = '".$_GET['id']."'");
		$row = $db->fetch_array($db->query("SELECT saletype FROM ".TABLE_PRODUCTS." where `id` = '".$_GET['id']."'"));
		$text = ($row['saletype']==1)?'isacl':'acloff';
	}
	$buff = $text;
}
elseif($_GET['act'] == 'user_on'){
	$row=$db->get(TABLE_USERS,$_GET['id']);
	$db->update(TABLE_USERS, array('id'=>$_GET['id']), array('block'=>intval(!$row['block'])));
	$row=$db->get(TABLE_USERS,$_GET['id']);
	$buff='err';
	if(count($row)>0) $buff=($row['block']==1)?'locked':'active';
}
elseif($_GET['act'] == 'part_on1'){
	if($_GET['type'] == 'cat'){
		$row = $db->fetch_array($db->query("SELECT visible,onprom FROM ".TABLE_CATEGORIES." where `id` = '".$_GET['id']."'"));
		$db->query("UPDATE ".TABLE_CATEGORIES." SET visible = '".intval(!$row['visible']  || $row['onprom'])."' where `id` = '".$_GET['id']."'");
		$row = $db->fetch_array($db->query("SELECT visible FROM ".TABLE_CATEGORIES." where `id` = '".$_GET['id']."'"));
		$text = ($row['visible']==1)?'Видимый':'Скрытый';
	}
	elseif($_GET['type'] == 'prd'){
		$row = $db->fetch_array($db->query("SELECT visible FROM ".TABLE_PRODUCTS." where `id` = '".$_GET['id']."'"));
		$db->exec("UPDATE ".TABLE_PRODUCTS." SET visible = '".intval(!$row['visible'])."' where `id` = '".$_GET['id']."'");
		$text = (!$row['visible']==1)?'Видимый':'Скрытый';
	}
	$buff = $text;
}
elseif($_GET['act'] == 'view_order'){
	$ord_inf = $db->get_rows("SELECT * FROM ".TABLE_ORDERS." WHERE id=".quote_smart($_GET['id'])."");
	if (count($ord_inf)==0) {
		$buff = 'Заказ удален.';
	} else {
		$prd_or = $db->get_rows("SELECT * FROM ".TABLE_ORDERS_PRD." WHERE order_id = '".$_GET['id']."' order by kodstr");
		$buff = show_order($ord_inf,$prd_or);
	}

}
elseif($_GET['act'] == 'view_prdbyord'){
	$q = $db->get_rows("SELECT distinct o.*, p.count+p.isdel-p.otgr as cnt FROM ".TABLE_ORDERS_PRD." p right join ".TABLE_ORDERS." o on o.id=p.order_id WHERE p.prd_id=".quote_smart($_GET['id'])." and p.count+p.isdel-p.otgr>0");
	$buff = show_ordersprd($q);
}
elseif($_GET['act'] == 'view_statd'){
	$dt= date_timestamp_get(date_create_from_format('d-m-y H:i:s',$_GET['dt']." 0:00:00"));
	$buff = get_stattovday($dt);
}
elseif($_GET['act'] == 'view_statdt'){
	$dt1=0;
	if(isset($_GET['dt1'])){
		$dt1=$_GET['dt1'];
	}
	else{
		$dt1=$_GET['dt']+24*60*60;
	}
	$query = "select o.*, p.count as cnt from chudo_ishop_order o left join chudo_ishop_order_det d on o.id=d.order_id  where d.prd_id=".quote_smart($_GET['prd'])." and o.data>=".quote_smart($_GET['dt'])." and o.data<".quote_smart($dt1)." order by o.id desc";
	$q = $db->get_rows($query);
	$buff = show_ordersprd($q);
}
elseif($_GET['act'] == 'view_userorders'){
	$query = "SELECT * FROM ".TABLE_ORDERS." where user_id=".quote_smart($_GET['userid'])." ORDER BY datan DESC";
	$q = $db->get_rows($query);
	$buff = show_orders($q);

}
elseif($_GET['act'] == 'view_usermorders'){
	$ml=$db->get(TABLE_ORDERS,$_GET['id']);
	$query = "SELECT * FROM ".TABLE_ORDERS." where email=".quote_smart($ml['email'])." ORDER BY datan DESC";
	$q = $db->get_rows($query);
	$buff = "Email: {$ml['email']}<br>".show_orders($q);

}
elseif($_GET['act'] == 'recalc_tcnt'){
	$bbb=array();
	$rcnt=0;
	$res=g_a_s(11,1);//array();
	array_unshift($bbb,array(11));
	//	$db->exec('update '.TABLE_CATEGORIES.' set cnt=0');
	for($i=count($bbb);$i>0;$i=$i-1){
		foreach($bbb[$i-1] as $cid){
			$q="SELECT count(id) as cnt FROM ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id WHERE cat_id = '".$cid."' && enabled=1 && visible=1 && ((p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)>0 and p.saletype=1) or p.saletype=0)";
			$rr=$db->query_first($q);
			$rr1=$db->query_first('select sum(cnt) as sm from '.TABLE_CATEGORIES.' where enabled=1 and visible=1 and parent_id='.$cid);
			$ocn=$rr['cnt']+$rr1['sm'];
			if ($ocn>=0){
				$db->exec('update '.TABLE_CATEGORIES.' set cnt='.$ocn.' where id='.$cid);
				$rcnt++;
			}	 
		}
	}
	$buff='Обновлено '.$rcnt.' групп.';
}
elseif($_GET['act'] == 'tmp_send'){
	$res=$db->get_rows("select * from chudo_temp_send where sent=0 order by id LIMIT 250");
	$txt='<p>Уважаемые покупатели интернет-магазина «Чудо-клумба»!</p>
<p>Настоящим сообщением я, Сергеева Анастасия Юрьевна, уведомляю Вас, что мой интернет-магазин, в погашение моих обязательств, переходит в управление другой компании - ООО «Клумба».</p>
<p>Далее интернет-магазин будет работать и развиваться с другой, профессиональной,  командой. Стратегия компании не изменится, все ключевые специалисты остаются работать в ней. Заказы будут приниматься и выполняться. В ближайшее время на сайте будут опубликованы новые реквизиты компании.</p> 
<p>Новые владельцы интернет-магазина пошли мне навстречу и согласовали возврат невыплаченных долгов клиентам ИП Сергеева А.Ю. или в денежном эквиваленте, или растениями. Выплаты денег будут осуществляться с мая 2016 г., постепенно, по мере развития компании, сначала по 10% в месяц, далее больше, до выплаты всего долга. Растениями погашение будет осуществляться, начиная с апреля 2016г., в размере до 20% от суммы долга в месяц.</p> 
<p>В связи с утратой части электронных сообщений магазина, пришлите, пожалуйста, Ваши заявления на возврат денежных средств на электронный адрес отдела расчетов компании <a href="mailto:fb@chudoclumba.ru">fb@chudoclumba.ru</a></p> 
<p>Все вопросы по взаиморасчетам с ИП Сергеева А.Ю. также присылайте на вышеуказанный адрес. </p><br/>
<p>С уважением, Анастасия Сергеева.</p>';
	$cnt=0;
	foreach($res as $val){
		if (strpos($val['mail'],'@')>0){
			sendmail_a($val['mail'],'Информация',$txt,'?utm_source=email_notification&utm_medium=email&utm_campaign=dolg');
			$cnt+=1;
			$db->update('chudo_temp_send',$val['id'],array('sent'=>1));
		}
	}
	$buff='Отправлено '.$cnt.' писем.';
	
}
elseif($_GET['act'] == 'reset_views'){
	$ocn=$db->exec('update '.TABLE_PRODUCTS.' set oldviews=oldviews+views,views=0');
	$buff='Обновлено '.$ocn.' товаров.';
}
elseif($_GET['act'] == 'check_chpu'){
	$ocn=$db->get_rows('select id,title,vlink from '.TABLE_PRODUCTS.'');
	$res=array();
	$cnt=0;
	foreach($ocn as $val){
		$tmp=str_replace(array("`","'","’"," "),"",$val['vlink']);
		$tmp1=str_replace('\\',"/",$val['title']);
		if ($tmp!=$val['vlink'] || $tmp1!=$val['title']){
			$ocn=$db->exec('update '.TABLE_PRODUCTS." set vlink='".$tmp."', title='".$tmp1."' where id='".$val['id']."'");
			if ($ocn===false) $res[]=$val['id']; else $cnt=$cnt+$ocn;
		}
	}
	$buff='Обновлено '.$cnt."\n";
	if (count($res)>0) {
		$buff.='Ошибки:';
		$buff.=print_r($res,TRUE);
	} 
	
}
elseif($_GET['act'] == 'qgp'){
?>
<form id="fmgp" action="" enctype="multipart/form-data">
<div id="gpmes"></div><div>
ГП: <input id="igp" class="fbinp" style="width:40px" type="text" name="igp"/><br/><br/>
<?php echo button('Установить',"SaveGp(this);")?></div><br/>
</form><br/>
<?	
}
elseif($_GET['act'] == 'save_gp'){
	$cats = array_del_empty(explode('_',$_GET['cats']));
	$buff=ajax_setrecgp($cats,$_GET['gp']);
}
elseif($_GET['act'] == 'setaclose'){
	$cats = array_del_empty(explode('_',$_GET['cats']));
	$buff=ajax_setaclose($_GET['id'],1);
}
else $buff="Error. Неизвестное действие.";

function g_a_s($cat_id,$crb=0){
	global $db,$bbb;
	$q=array();
	if(!is_array($cat_id)){
		$cat_id = array($cat_id);
	}
	if(count($cat_id) > 0){
		sort($cat_id,SORT_NUMERIC);
		$rows = $db->get_rows("SELECT id FROM ".TABLE_CATEGORIES." WHERE parent_id IN (".implode(',',$cat_id ).")");
		if(count($rows) > 0){
			$qas1=array();
			foreach($rows as $row){
				$qas1[] = $row['id'];
			}
			if($crb==1) $bbb[]=$qas1;
			$q=array_merge($q,$qas1,g_a_s($qas1,$crb));
		}
	}
	return $q;
}

echo $buff;
$_SESSION['sql_log']=$db->write_dump();
?>