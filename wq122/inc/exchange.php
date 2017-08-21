<?php
function reset_sendord()
{
	global $db;
	if (!isset($_POST['id_arr'])) return 'Error. No ID.';
	$res=trim($_POST['id_arr'],';');
	$res=explode(";",$res);
	if (count($res)>0) {
		$str="";
		foreach($res as $val){
			if (!empty($str)) $str.=',';
			$str.='"'.$val.'"';
		}
		$res=$db->exec('update '.TABLE_SENDORD.' set recflag=1 where ord_id in ('.$str.')');
		if ($res===flase) return 'Error. Update query error!';
	}
	return "PostOk\n";
}
function send_ordrep1()
{
	global $db,$sets;
	if (!isset($_POST['id_mail'])) return 'Error. No ID.';
	$res=explode(";",$_POST['id_mail']);
	$ord=$res[0];
	$mail=$res[1];
	$fio=$res[2];
	//$res=explode("\n",$_POST['tov']);
	//if (count($res)==0) return 'Error. No Order det.';
//	if (!($ord>0) || !is_numeric ($ord)) return "PostOk\n";
 	$rows = $db->get_rows("SELECT * FROM ".TABLE_ORDERS." where  id=".$db->escape_string($ord));
 	if (!(count($rows)>0)) return "Error Заказ не найден\n";
 //	$rows = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." where  id=".$ord);
 	$body="<p>Здравствуйте, {$fio}!</p><p>Ваш заказ № {$ord} на Весну 2016 года в интернет-магазине «Чудо-клумба» отменен в связи с невозможностью поставки заказанных Вами растений.</p><p>Сейчас на нашем сайте представлены товары, уже находящиеся на складе или поступающие в ближайшее время. Следите за нашими новостями, каталоги будут постоянно обновляться!</p>
 <p>Мы будем рады, если Вас заинтересует что-либо из предложенного ассортимента, и Вы оформите  новый заказ. Мы отправим его Вам в кратчайшие сроки. </p><p>Приносим свои извинения за доставленные неудобства!</p>
 <p>С уважением,
Интернет-магазин «Чудо-клумба»
</p>";
//$mail='info@chudoclumba.ru';
	$res=sendmail_a($mail,'Ваш заказ.',$body,'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderconfirm');
	
	if ($res===true)return "PostOk\n"; else return "PostOk\n notsend";
		
	
}
function send_ordrep()
{
	global $db,$sets;
	if (!isset($_POST['id_mail'])) return 'Error. No ID.';
	$res=explode(";",$_POST['id_mail']);
	$adress='';
	if (isset($_REQUEST['adress'])) $adress=$_REQUEST['adress'];
	$ord=$res[0];
	$mail=$res[1];
	$fio=$res[2];
	$res=explode("\n",$_POST['tov']);
	if (count($res)==0) return 'Error. No Order det.';
//	if (!($ord>0) || !is_numeric ($ord)) return "PostOk\n";
 	$rows = $db->get_rows("SELECT * FROM ".TABLE_ORDERS." where  id=".$db->escape_string($ord));
 	if (!(count($rows)>0)) return "Error Заказ не найден\n";
 //	$rows = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." where  id=".$ord);
  	$body="<p>Здравствуйте!</p><p>Вы делали заказ в нашем интернет магазине «Чудо-клумба» № {$ord}.</p>
 	<p>На настоящий момент Ваш заказ собран и готов к отправлению в следующей комплектации:</p>";
 	ob_start();
 ?>
<table style="border-collapse:collapse;border-width:1px;border-style:solid;border-color:#a2f7aa"><tr><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Фото</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Наименование</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Цена</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Кол-во</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Стоимость</th></tr>
<? 
	$sum=0;
 	foreach($res as $value){
 		$rows=explode(";",$value);
 		if ($rows[1]>0){
			
	 	$row = $db->get(TABLE_PRODUCTS,$rows[1]);
 		
		$link=SITE_URL.'ishop/product/'.$id;
		if(!empty($row['vlink'])) {
			$link=SITE_URL.$row['vlink'];
		}
		$cen=str_ireplace(",",".",$rows[5]);
		$cnt=str_ireplace(",",".",$rows[4]);
		$sum=$sum+$cen*$cnt;
?>
 <tr><td style="border-width:1px;border-style:solid;border-color: #a2f7aa"><a href="<?=$link.'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderconfirm'?>">
   <img border="0" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=100&y=100&crop" style="display:block" /></a></td>  
  <td style="border-width:1px;border-style:solid;border-color:#a2f7aa"><?=$row['param_kodtovara']?><br><a style="color:#1C509A;" href="<?=$link.'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderconfirm'?>"><?=$row['title']?></a></td>
  <td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;padding: 0 5px;color:#E51A4B"><?=number_format($cen,2,'.','')?></td><td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: center;"><?=$cnt?></td>
  <td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;padding: 0 5px;color:#E51A4B"><?=number_format($cen*$cnt,2,'.','')?></td></tr>
<? }} ?>
<tr><td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;" colspan="4">Сумма:</td><td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;padding: 0 5px;color:#E51A4B"><?=number_format($sum,2,'.','')?></td></tr>
</table> 		

<?
	$body.=ob_get_contents();
	ob_end_clean();
	$keylink=SITE_URL."service/receive/{$ord}/".md5("receive".$ord).'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderconfirm';
	$keylink1=SITE_URL."service/receive/{$ord}/".md5("decline".$ord).'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderdecline';
 	$body.="<p><b>Для доставки заказа</b> в текущем составе по адресу: {$adress}, <b>нажмите на кнопку:</b></p>
	
	<a href=\"{$keylink}\"><img src=\"".SITE_URL."templates/101/images/mail_key_ok.jpg\" alt=\"Подтвердить заказ!\" width=\"213\" height=\"41\" /></a>
	
	<p>Если какие-то растения из Вашего заказа не включены в данный перечень, это означает, что они еще не поступили на наш склад. Оставшиеся растения будут отправлены следующей посылкой.</p>
	<p>Если Вы хотите дождаться полной комплектации заказа, не нажимайте на кнопку.</p>
	<p>По мере комплектации Вашего заказа Вам будут приходить новые уведомления.</p>";

//$mail='info@chudoclumba.ru';
	$res=sendmail_a($mail,'Ваш заказ.',$body,'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderconfirm');

	if ($res===true)return "PostOk\n"; else return "PostOk\n notsend";

		
	
}

function del_deliveryid(){
	if (!isset($_GET['deliv_id']) or empty($_GET['deliv_id'])){
		return 'Error deliv_id.';
	}
	global $db;
	$db->delete("chudo_ishop_postid",['id'=>$_GET['deliv_id']]);
	return 'Ok deliv_id';
}
function set_deliveryid(){
	global $db;
	if (!isset($_GET['deliv_id']) ){
		return 'Error d_id.';
	}
	if (!isset($_POST['delid']) or empty($_POST['delid'])){
		return 'Error id.';
	}
	if (!isset($_POST['deliddta']) or empty($_POST['deliddta'])){
		return 'Error data.';
	}
	$inp=explode(';',$_POST['deliddta']);
	if (count($inp)!==3) return "Error cnt.";
	$ins_data = array('date'=>strtotime($inp[1]),'value'=>$_POST['delid'],'orderid'=>$inp[2]);
	if (isset($_POST['comment'])){
		$ins_data['comment']=$_POST['comment'];
	}
	if ($_GET['deliv_id']==0) {
		if ($db->insert('chudo_ishop_postid', $ins_data)){
			$last_id = $db->insert_id();
			return 'Ok deliv_id;'.$last_id.';'.$last_id*2;
		}
	} else {
		if ($db->update('chudo_ishop_postid',$_GET['deliv_id'], $ins_data)){
			return 'Ok deliv_id;'.$_GET['deliv_id'].';'.$_GET['deliv_id']*2;
		}
	}
//	$db->query("delete from chudo_ishop_postid where orderid=".quote_smart($inp[2])." and value=".quote_smart($_POST['delid']));
	return 'Error.';
}
function reset_orderreg()
{
	global $db;
	$id=$_GET['id'];
	if (!isset($id)) return 'Error';
	$res=$db->exec("update ".TABLE_ORDERS_REG." set isins=4 where isins>0 and id=".$db->escape_string($id));
	$res1=$db->exec("delete from ".TABLE_ORDERS_REG." where isins=0 and id=".$db->escape_string($id));
	if ($res===false || $res===false ) return 'Error';
	else return 'StateOk';
}
function get_updates($id)
{
	global $db;
	$str="";
	if (isset($id) && !empty($id)) $q='select r.*,p.param_kodtovara as kodtov from '.TABLE_ORDERS_REG.' r left join '.TABLE_PRODUCTS.' p on p.id=r.prdid where r.orderid='.$db->escape_string($id).' order by r.isins'; else $q='select r.*,p.param_kodtovara as kodtov from '.TABLE_ORDERS_REG.' r left join '.TABLE_PRODUCTS.' p on p.id=r.prdid order by r.orderid,r.isins';
	$rows = $db->get_rows($q);
	if (count($rows)==0) return "Empty";
	foreach($rows as $row){
		if ($row['isins']==1){ // дозаказ
			$str.="upl;{$row['id']};{$row['orderid']};{$row['kodstr']};{$row['kodtov']};{$row['cnt']};{$row['price']};{$row['sale']}\n";
		} elseif ($row['isins']<3) {  // восстановление/удаление, но не новый заказ
			$cnt=$row['cnt'];
			if ($row['isins']==0) $cnt=0-$cnt;
			$str.="upd;{$row['id']};{$row['orderid']};{$row['kodstr']};{$cnt}\n";
		}
	}
	if (empty($str)) $str="Empty";
	return $str;
}
function get_prdstate($id)
{
	global $db;
    $catpinf = $db->get(TABLE_CATEGORIES,$id);
	if (!empty($catpinf) && count($catpinf)>0) {
		$nm=($catpinf['enabled'] && $catpinf['visible']);
	} else $nm=true;
	if ($catpinf['id']>0) 
	{
		$nm=($nm && get_prdstate($catpinf['parent_id']));
	}
	return $nm;
}

function conv_str($str)
{
	$str1=str_replace('"""','"',$str);
	$str1=str_replace('""','"',$str1);
	$str1=str_replace('"','""',$str1);
	return $str1;
}
if(!function_exists('quote_smart'))
{
	function quote_smart($value)
	{
		// если magic_quotes_gpc включена - используем stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		// Если переменная - число, то экранировать её не нужно
		// если нет - то окружем её кавычками, и экранируем
		$value = "'" . PDO::quote($value) . "'";
		return $value;
	}
}

function get_prdv($id)
{
global $db,$sets;
$cat = $db->get(TABLE_PRODUCTS, $id);
$rows=array();
if ($cat['srcid']>0) {
	$rows=$db->get_rows('select * from chudo_ishop_products where srcid='.$cat['srcid'].' and visible=1 order by tsena');
	foreach($rows as $id => $row){
		if ($row['id']==$row['srcid']) $cat=$row;
	}
}
$q=$db->get_rows("select * from chudo_upak");
$upak=array();
foreach($q as $r){
	$upak[$r['id']]=array('name'=>$r['name'],'descr'=>$r['descr']);	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Карта</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
 window.onerror = myOnError;
function myOnError(msg, url, lno) {return true};
</script><!--start-->
<style type="text/css">
html,body{height:100%;width:100%}
html,body,.p0{font-family:Tahoma;font-size:12px;margin:0;padding:0}
.tit{width: 30%;text-align: right;font-weight: 700}
img{border:0}
body, td { color: #320404;font-family: "Tahoma";font-size: 12px;}
td.text{font-size:11px;font-weight:700}
.prd_image_fix{margin:0;position:relative;text-align:center; }
.prd_image_fix img{margin:0px 0 0 0px}
.prdcard{-moz-border-radius:5px;-moz-box-shadow:5px 5px rgba(0,0,0,0.4);-webkit-border-radius:5px;-webkit-box-shadow:5px 5px rgba(0,0,0,0.4);border:1px solid #FFAD33;border-radius:5px;box-shadow:5px 5px 5px rgba(0,0,0,0.4);
    margin: 3px;padding: 10px; background-color: #fffefe;font-size:11px;}
.col{border-spacing:0;border-collapse: collapse;}
.prd_outer_short .hit_m,.prd_outer_short .skidka_m,.prd_outer_short .new_m{height:60px;position:absolute;right:-25px;width:60px}
td.prd_outer_short{padding-bottom:10px;text-align:left;vertical-align:top}
.pl10{padding-left:10px}
.prd_bgs{padding:0}
.ppr_mt{border: solid 1px #c49c84}
.ppr_mt td{border-left: dotted 1px #c49c84;border-bottom: solid 1px #c49c84}
.ppr_mt th{border-left:dotted 1px #c49c84; border-bottom: solid 1px #c49c84}
th.nbl, td.nbl {border-left: none}
div.ppd_list_item { font-weight: 700;}
.pr10 {padding-right: 10px;}
.ppr_d_t{margin:5px 0}

</style>
</head>
<body>
<div class="prdcard">
<div style="padding:6px 0 6px 5px; font-size:14px; color: #fb5a04"><?=$cat['param_naimenovanielatinskoe']?></div>
<table class="w100 col">
<tr><td class="p0 prd_outer_short pl10">
<table class="col">
<tr><td class="prd_bgs p0"><div class="prd_image_fix" style="width:97px; height:150px;" >
<? if(!empty($cat['new'])) { ?><div class="new_m"></div><? } 
   if(!empty($cat['hit'])) { ?><div class="hit_m"></div><? } 
   if(!empty($cat['spec'])) { ?><div class="skidka_m"></div><? } 
   $p_size = ($sets['mod_p_size']) ? 'class="highslide" onclick="return hs.expand(this)"' : ''; 
   if(file_exists('../'.$cat['foto'])) {?>
<a id="flink" <?=$p_size?> href="<?=SITE_URL?><?=$cat['foto']?>">
<img alt="<?=$cat['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$cat['foto']?>&amp;x=150&y=97"  />
</a>
<? } else { ?>Изображение временно отсутствует.<? } ?>
</div>
</td>
<td class="text top p0" style="padding-left: 40px;">
<h2><?=htmlspecialchars($cat['title'],ENT_COMPAT | ENT_XHTML,'UTF-8')?></h2>
<?
		if(!empty($cat['param_brend']) && !empty($sets['mod_proizv'])) {
			$inf = $db->get_rows("SELECT title FROM ".TABLE_CATEGORIES." WHERE id = ".quote_smart($cat['param_brend'])."");
			?><div class="ppr_d_t">Производитель: <?=$inf['0']['title']?></div><?
		} 
echo (!empty($cat['param_proizvoditel'])) ? '<div class="ppr_d_t"><b>Производитель:</b> '.$cat['param_proizvoditel'].'</div>
' : '';
echo (!empty($cat['param_visota'])) ? '<div class="ppr_d_t"><b>Высота, см:</b> '.$cat['param_visota'].'</div>
' : '';
echo (!empty($cat['param_shirina'])) ? '<div class="ppr_d_t"><b>Ширина, см:</b> '.$cat['param_shirina'].'</div>
' : '';
echo (!empty($cat['param_aromat'])) ? '<div class="ppr_d_t"><b>Аромат:</b> '.$cat['param_aromat'].'</div>
' : '';
echo (!empty($cat['param_tsvetenie'])) ? '<div class="ppr_d_t"><b>Цветение:</b> '.$cat['param_tsvetenie'].'</div>
' : '';
echo (!empty($cat['param_razmertsvetka'])) ? '<div class="ppr_d_t"><b>Размер цветка, см:</b> '.$cat['param_razmertsvetka'].'</div>
' : '';
echo (!empty($cat['param_tiptsvetka'])) ? '<div class="ppr_d_t"><b>Тип цветка:</b> '.$cat['param_tiptsvetka'].'</div>
' : '';
echo (!empty($cat['param_periodtsveteniya'])) ? '<div class="ppr_d_t"><b>Период цветения:</b> '.$cat['param_periodtsveteniya'].'</div>
' : '';
echo (!empty($cat['param_okraskatsvetka'])) ? '<div class="ppr_d_t"><b>Окраска цветка:</b> '.$cat['param_okraskatsvetka'].'</div>
' : '';
echo (!empty($cat['param_okraskalistvi'])) ? '<div class="ppr_d_t"><b>Окраска листвы:</b> '.$cat['param_okraskalistvi'].'</div>
' : '';
if(!empty($cat['param_morozostoykost'])){ 
	$ss=(strlen(strpbrk($cat['param_morozostoykost'], '0123456789'))>0) ? '&nbsp;&nbsp;&nbsp;<a href="site/28">Зоны морозостойкости</a>' : '';
?><div class="ppr_d_t"><b>Морозостойкость:</b> <?=$cat['param_morozostoykost'].$ss?></div>
<?}
		if(!empty($cat['param_muchnistayarosa']) || !empty($cat['param_chernayapyatnistost'])) { 
		echo '<div class="ppr_d_t"><b>Устойчивость листвы к заболеваниям:</b>';
		echo (!empty($cat['param_muchnistayarosa'])) ? '<br><b>мучнистая роса:</b> '.$cat['param_muchnistayarosa'] : '';
		echo (!empty($cat['param_chernayapyatnistost'])) ? '<br><b>черная пятнистость:</b> '.$cat['param_chernayapyatnistost'] : '';
		echo '</div>
		';
		}
		echo (!empty($cat['param_tippochvi'])) ? '<div class="ppr_d_t"><b>Тип почвы:</b> '.$cat['param_tippochvi'].'</div>
' : '';
		echo (!empty($cat['param_svetovoyrezhim'])) ? '<div class="ppr_d_t"><b>Световой режим:</b> '.$cat['param_svetovoyrezhim'].'</div>
' : '';
?>
</td></tr></table></td></tr>
<?
echo '<tr><td><br><table class="ppr_mt col">';
if (!count($rows)>0) $rows[0]=$cat;
//print_r($rows);
echo '<tr><th class="nbl"><div class="ppd_list_item pr10">Код товара</div></th><th><div class="ppd_list_item pr10 pl10">Cтандарт<br>поставки</div></th><th><div class="ppd_list_item pr10">Срок поставки</div></th><th><div class="ppd_list_item pr10 tcen">Цена</div></th><th></th></tr>
';
foreach($rows as $id => $row){
	$ss='';
	if ($_SERVER['REMOTE_ADDR']==$sets['ofip']) $ss=' title="Редактировать в СУС" class="link_sus nbl" onclick="window.open'."('/wq121/include.php?place=ishop#update_prd_html(".$row['id'].")','',''); return false;".'"'; 
	else  $ss=' class="nbl"';
	echo '<tr><td'.$ss.'><div class="ppr_d_t pr10 pl10">'.$row['param_kodtovara'].'</div></td>';
	$pos=substr(trim($row['param_kodtovara']),17,2);
	if (substr($pos,0,1)=='0') @$pos=substr($pos,1,1);
	echo '<td align="center"><div class="ppr_d_t lf pr10 pl10" data-title="'.$upak[$pos]['descr'].'"><span style="font-weight:700">'.$upak[$pos]['name'].'</span>';
	echo (!empty($row['param_kolichestvo']) && $row['isupak']==1) ? ', Кол-во в упак.: '.$row['param_kolichestvo'] : '';
	echo '</div></td>';
	echo '<td><div class="ppr_d_t pr10 pl10">'.$row['param_srokpostavki'].'</div></td>';
	echo '<td'.(($row['skidka_day']>0) ? ' title="Cкидка дня!"' : '').'><div class="ppr_d_t pr10tr pl10">';
	if(!empty($row['param_starayatsena']) && $row['param_starayatsena'] > 0 && $sets['mod_old_price'] && !($row['skidka_day']>0)) {
		echo '<span class="lt">'.$row['param_starayatsena'].'</span>&nbsp';
	} 
	if($row['skidka_day']>0){ 
		echo '<span class="lt">'.$row['tsena'].'</span>&nbsp;';
	}
	echo '<span style="font-weight:bold;font-size:14px">'.($row['tsena']-$row['tsena']*$row['skidka']/100).'</span> руб.';
	echo '</div></td>';
	if($row['enabled']==1 && get_prdstate($row['cat_id'])){
		echo '<td  class="pl10 pr10"><div class="add_prd" id="'.$row['id'].'">Продается';
		echo '</div>';

	} else {
	    echo '<td><div class="ppd_list_item" style="color:red; font-size:11px;">Прием заказов завершен</div>';
	}
	echo '</td></tr>
	';

}
echo '</table></td></tr>
';


 if(!empty($cat['param_polnoeopisanie'])) { ?>
<tr><td><div class="o_hd">Описание:</div><div class="ppr_descr"><?=$cat['param_polnoeopisanie']?></div></td></tr>
<? } 
?></table></div>
<!--end of product-->
</body></html>

<?

}


function write_trm($dt,$par)
{
	global $db;
	$xpar = explode(";",$par);
	$errcnt=0;
	foreach ($xpar as $id=>$lpar)
	{
		$mpar=explode("=",$lpar);
		$id=$mpar[0];
		$trm=$mpar[1];
		$trm=round($trm,2);
		$tdt=strtotime($dt);
		if ($id>0 && !($tdt==FALSE))
		{
			$ins_data = array('dt'=>$tdt,'id'=>$id,'trm'=>$trm);
			$rows = $db->get_rows("SELECT * from ".TABLE_TRM." where id='".$id."' and dt=".strtotime($dt));
			if (!count($rows)>0) $db->insert(TABLE_TRM, $ins_data);
			else $errcnt++;
		}
		else
		{
			$errcnt++;
		}
	}
	if ($errcnt==0) return 'Ok'; else return 'Err'.$errcnt;
}
function close_callback($prd)
{
	global $db;
	$rows = $db->get_rows("SELECT ".TABLE_CALLBACK.".* from ".TABLE_CALLBACK." where id='".$prd."'");
	if (!count($rows)>0) return "Error. callback not found.;ID:".$prd.";";
	$db->exec("UPDATE ".TABLE_CALLBACK." SET end = 1, dataend=".time()."  where id = '".$prd."'");
	$rows = $db->get_rows("SELECT ".TABLE_CALLBACK.".* from ".TABLE_CALLBACK." where id='".$prd."'");
	return 'ID:'.($rows[0]['end']).':'.$prd;
	
}

function load_sendord()
{
	global $db;
 	$rows = $db->get_rows("SELECT * FROM ".TABLE_SENDORD." where  recflag=0");
 	if (!(count($rows)>0)) return "Empty\n";
	$lines='';
	foreach ($rows as $id=>$row)
	{
		$lines.=$row['ord_id'].':'.$row['oper'].';';
	}
	return($lines);
}

function load_callback($ord)
{
	global $db;
 	$rows = $db->get_rows("SELECT * FROM ".TABLE_CALLBACK." where  id>'".$ord."' and end=0");
 	if (!(count($rows)>0)) return "Empty\n";
	$lines='';
	foreach ($rows as $id=>$row)
	{
		$lines.=$row['id'].';'.date('Y-m-d H:i:s',$row['data']).';'.$row['tel'].';'.str_replace(';','',$row['name']).';'.str_replace(';','',$row['comment']).';'.$row['userid'].';*re*';
	}
	return($lines);
}
function upd_ord($ord)
{
	global $db;
//	file_put_contents('11.txt', print_r($_REQUEST,TRUE),FILE_APPEND);
	$xord=!empty($ord);
	if ($xord){
	 	$rows = $db->count(TABLE_ORDERS,$ord);
	 	if ($rows>0){
	 		$flfull=false;
			$stt=array();
			$uarr=array();
			if (isset($_POST['tstatus'])){
				$stt=explode(';',$_POST['tstatus']);
				if (isset($stt[0])) $uarr["tstatus"]=$stt[0];
				if (isset($stt[1])) $uarr["opl"]=str_replace(',','.',$stt[1]);
				if (isset($stt[2])) $uarr["dost"]=$stt[2];
				if (isset($stt[3])) $uarr["adr"]=$stt[3];
				if (isset($stt[4])) $flfull=true;
			}
			if (isset($_GET['sum'])) $uarr["summa"]=str_replace(',','.',$_GET['sum']);
			if (isset($_GET['otgr'])) $uarr["sumotgr"]=str_replace(',','.',$_GET['otgr']);
			if (isset($_GET['sk'])) $uarr["skidka"]=str_replace(',','.',$_GET['sk']);
			if (isset($_GET['sumopl'])) $uarr["sumopl"]=str_replace(',','.',$_GET['sumopl']);
			if (isset($_POST['ordinf'])) $uarr["dopinf"]=$_POST['ordinf'];
			if (isset($_GET['state'])) $uarr["status"]=$_GET['state'];
			$res=$db->update(TABLE_ORDERS,$ord,$uarr);
		} else $xord=false;
	}
	if (isset($_POST['ord_det'])){
		if ($xord) 
			$res=$db->delete(TABLE_ORDERS_PRD,array('order_id'=>$ord));
		$lines = explode("\n",$_POST['ord_det']);
	    $okcnt=0;
	    $errcnt=0;
		foreach ($lines as $id=>$line)
		{
			$line=str_replace(',','.',$line);
			if (!($line>' ')) continue;
			$xline = explode(";",$line);
			$nm=trim($xline[3],'"');
			$prdel=trim($xline[7],'"');
			$ins_data = array('order_id'=>$ord,'kodstr'=>$xline[0],'prd_id'=>$xline[1],'kodtov'=>$xline[2],'name'=>$nm,
								'count'=>$xline[4],'summa'=>$xline[5],'isdel'=>$xline[6],'prdel'=>$prdel,'sumdel'=>$xline[8],
								'otgr'=>$xline[9],'sumotgr'=>$xline[10],'skidka'=>$xline[12],'old_id'=>$xline[11]);
			if ($xord) 
				$db->insert(TABLE_ORDERS_PRD, $ins_data);
			$ins_data = array("sklad"=>$xline[13],"zakazpost"=>$xline[14],"reserv"=>$xline[15]);
			$db->update(TABLE_PRODUCTS,$xline[1],$ins_data);
			if ($flfull==true) $db->exec('delete from '.TABLE_ORDERS_REG." where orderid=".$db->escape_string($ord)." and isins>=3 and kodstr=".$db->escape_string($xline[0])); 
		}
		if ($flfull==true) $db->exec('delete from '.TABLE_ORDERS_REG." where orderid=".$db->escape_string($ord));
	}
	
	return "PostOk\n";
		
	
}
function cat_iopen($cat,$ren=1)
{
	global $db;
	$tren=0;
	$rows = $db->get_rows("SELECT ".TABLE_CATEGORIES.".* from ".TABLE_CATEGORIES." where id=".$cat);
    $tren=$ren*$rows[0]['enabled']*$rows[0]['visible'];
	if ($rows[0]['parent_id']!=11 && $rows[0]['parent_id']>0) $tren=$tren*cat_iopen($rows[0]['parent_id'],$tren);
	return $tren;
}
function get_prdid($prd)
{
	global $db;
	$rows = $db->get_rows("SELECT ".TABLE_PRODUCTS.".id from ".TABLE_PRODUCTS.' where param_kodtovara="'.$prd.'"');
	if (count($rows)>1) return "Error. Some goods are found;ID:".$prd.";".count($rows);
	if (count($rows)==0) return "Error. Товар не найден;ID:".$prd.";".count($rows);
	return 'ID:'.$rows[0]['id'];
	
}

function get_idstate($prd)
{
	global $db;
	$rows = $db->get_rows("SELECT ".TABLE_PRODUCTS.".* from ".TABLE_PRODUCTS." where id=".$prd);
	if (!count($rows)>0) return "Error. PRODUCT not found.;ID:".$prd.";";
	$tren=cat_iopen($rows[0]['cat_id'])*$rows[0]['visible']*$rows[0]['enabled'];
	return 'ID:'.$tren.':'.$prd;
	
}
function get_allstate()
{
	global $db;
	$out='';
	$rows = $db->get_rows("SELECT id,visible, enabled, cat_id, param_kodtovara from ".TABLE_PRODUCTS." order by id");
	foreach($rows as $id => $row){
		$tren=cat_iopen($row['cat_id'])*$row['visible']*$row['enabled'];
		$out.='ID;'.$row['id'].';"'.$row['param_kodtovara'].'";'.$tren.";\n";
	}
	return $out;
}
function get_allfoto()
{
	global $db;
	$out='';
	$rows = $db->get_rows("SELECT id,foto from ".TABLE_PRODUCTS." order by id");
	foreach($rows as $id => $row){
		$out.='ID;'.$row['id'].';"'.$row['foto'].'";'.";\n";
	}
	return $out;
}

function get_newnom()
{
	if (!isset($_POST['newnom'])) return 'Error. Post is empty.';
	$ee=trim($_POST['newnom']);
	global $db;
	$rows = $db->get_rows("SELECT id,visible, enabled, cat_id, title, param_sort, tsena,param_polnoeopisanie, param_kodtovara from ".TABLE_PRODUCTS." where id in ".$ee." order by param_kodtovara");
	$file="";
	foreach($rows as $id => $val)
	{
		$tren=cat_iopen($val['cat_id'])*$val['visible']*$val['enabled'];
		$line='"'.conv_str($val['param_kodtovara']).'";';
		$line.=$val['id'].';';
		$line.=$tren.';';
		$line.='"'.conv_str($val['title']).'";';
		$line.='"'.conv_str($val['param_sort']).'";';
		$line.='"'.conv_str($val['param_polnoeopisanie']).'";';
		$line.=$val['tsena'].';';
		$file.=$line."\n";
	}
	return $file;
}

function get_udata($prd)
{
	global $db;
	$rows = $db->get_rows('SELECT login, pass FROM '.TABLE_USERS.' where id='.$prd);
	if (($rows[0]['login']>' ') and ($rows[0]['pass']>' '))
	{
?>

<form name="ddd" action="https://chudoclumba.ru" method="post">
<input type="text" value="<?echo $rows[0]['login']?>" name="login" />
<input type="password" value="<?echo $rows[0]['pass']?>" name="pass" />
<input  type="submit" value="jk"/>
<script type="text/javascript">
//setTimeout('document.forms.ddd.submit()', 50)
setTimeout('window.location = "https://chudoclumba.ru/service/zwgxtretqq?id=1024&ha=<?=md5(1024)?>"',5000);
</script>
</form>
<?
		return ;//$rows[0]['login'].';'.$rows[0]['pass']."\n";
	}
	else
	{
		return 'Error. Udata incorrect';
	}
	
}
function acl_prd($prd,$state)
{
	global $db;
	$row = $db->get(TABLE_PRODUCTS,$prd);
	if (empty($row)) return "Error. PRODUCT not found.;ID:".$prd.";";
	if ($row['saletype'] == $state) return 'ID:'.($row['saletype']).':'.$prd;
	$db->update(TABLE_PRODUCTS,$prd,array("saletype"=>intval($state)));
	$row = $db->get(TABLE_PRODUCTS,$prd);
	return 'ID:'.($row['saletype']).':'.$prd;
}
function Open_prd($prd)
{
	global $db;
	$rows = $db->get_rows("SELECT ".TABLE_PRODUCTS.".* from ".TABLE_PRODUCTS." where id=".$prd);
	if (!count($rows)>0) return "Error. PRODUCT not found.;ID:".$prd.";";
	$tren=cat_iopen($rows[0]['cat_id']);
	if ($tren!=1) return "Error. GROUP not enabled.;ID:".$prd.";";
	$db->exec("UPDATE ".TABLE_PRODUCTS." SET enabled = 1, visible = 1  where id = ".$prd);
	$rows = $db->get_rows("SELECT ".TABLE_PRODUCTS.".* from ".TABLE_PRODUCTS." where id=".$prd);
	return 'ID:'.($rows[0]['enabled']*$rows[0]['visible']).':'.$prd;
}
function Close_prd($prd)
{
	global $db;
	$rows = $db->get_rows("SELECT ".TABLE_PRODUCTS.".* from ".TABLE_PRODUCTS." where id=".$prd);
	if (!count($rows)>0) return "Error. PRODUCT not found.;ID:".$prd.";";
	$db->exec("UPDATE ".TABLE_PRODUCTS." SET enabled = 0 where id = ".$prd);
	$rows = $db->get_rows("SELECT ".TABLE_PRODUCTS.".* from ".TABLE_PRODUCTS." where id=".$prd);
	return 'ID:'.($rows[0]['enabled']*$rows[0]['visible']).':'.$prd;
}

function isProductForSale($productId)
{
    global $db;
    $bRes = false;
    $q="SELECT param_starayatsena from chudo_ishop_products where id=".$productId;
    $result = $db->get_rows($q);
    if(!empty($result))
    {
        $sprice = $result[0]["param_starayatsena"];
        if($sprice > 0)
            $bRes = true;
    }
    return $bRes;
}

function getzak()
{
    global $db;
 	$rows = $db->get_rows("SELECT * FROM ".TABLE_ORDERS." where  ld=0 order by data LIMIT 1");
 	if (!(count($rows)>0)) return "No new orders";
	$res=$db->get_rows("select sum((summa-summa*skidka/100)*count) as summas,sum(summa*count) as summa from ".TABLE_ORDERS_PRD." where order_id=".$db->escape_string($rows[0]['id']));
	$per=$db->get(TABLE_TPER,$rows[0]['per']);
 	$id=$rows[0]['id'];
  	$tcont1 ='Заказ;'.$rows[0]['id'].';Дата;'.date("d.m.Y",$rows[0]['data']).";Группа;{$rows[0]['per']};".date('Ymd',$per['date'])."\n";
  	$tcont1 .='UserId;'.$rows[0]['user_id']."\n";
  	$tcont1 .='Фамилия Имя Отчество;'.$rows[0]['fio']."\n";
  	$tcont1 .='Телефон;'.$rows[0]['tel']."\n";
  	$tcont1 .='E-mail;'.$rows[0]['email']."\n";
  	$tcont1 .='Адрес доставки;'.$rows[0]['adr']."\n";
  	$tcont1 .='Дополнительная информация;'.$rows[0]['comment']."\n";
  	$tcont1 .='Способ доставки;'.$rows[0]['dost']."\n";
  	$tcont1 .='Способ оплаты;'.$rows[0]['opl']."\n";
  	$tcont1 .='SMS;'.$rows[0]['sms']."\n";
    $pr=$rows[0]['summa'];
    $sk=$rows[0]['skidka'];
	$rows = $db->get_rows("SELECT d.prd_id,d.name,d.summa,d.count,d.todel,d.skidka, p.title, p.param_kodtovara FROM ".TABLE_ORDERS_PRD." d,".TABLE_PRODUCTS." p WHERE d.order_id = ".quote_smart($id)." && p.id = d.prd_id order by d.kodstr");
	$order_sum = 0;
	$skidka_sum = 0;
	foreach($rows as $id=>$row)
	{
	    $skidka_prd = 0;
	    $summa_prd = 0;
	    if(!isProductForSale($row['prd_id']))
        {
            $skidka_prd = $row['skidka'];
            $summa_prd = $row['summa'];
            $skd = (($summa_prd * $row['count']*$skidka_prd) / 100);
            $skidka_sum += $skd;
            $order_sum += ($summa_prd * $row['count']) - $skd;
        }
        else
        {
            $skidka_prd = 0;
            $summa_prd = $row['summa'];
            $order_sum += $summa_prd * $row['count'];
        }

		$tcont1.="ID;{$row['prd_id']};{$row['param_kodtovara']};{$row['name']};";
		$tcont1.="{$row['summa']};{$row['count']};{$row['todel']};{$skidka_prd};\n";
	}
 	$tcont1 .='Сумма;'.number_format($order_sum, 2, ',', '')."\n";
 	$tcont1 .='Скидка;'.number_format($skidka_sum, 2, ',', '')."\n";
 	return $tcont1;

}
function setzakstate($id,$state)
{
    global $db;
    $res=$db->update(TABLE_ORDERS,$id,array('ld'=>$state));
    $sres="StateOk";
    if ($res===false) $sres="Ошибка установки статуса заказа.";
    return $sres;
}
function set_orderstate($id,$state)
{
    global $db;
    $res=$db->update(TABLE_ORDERS,$id,array('status'=>(int)$state));
    $sres="StateOk";
    if ($res===false) $sres="Ошибка установки статуса заказа.";
    return $sres;
}

function setzakopl()
{
    global $db;
	$lines = explode("\n",$_POST['popl']);
    $okcnt=0;
    $errcnt=0;
	foreach ($lines as $id=>$line)
	{
		$line=str_replace(',','.',$line);
		if (!($line>' ')) continue;
		$xline = explode(";",$line);
		$zakid=$xline[0];
		if (!($zakid>0) || !is_numeric ($zakid)) 
		{
//			$errcnt++;
//			echo('Ошибка в строке: '.$line."\n");
			continue;
		}
		$zakopl=$xline[1];
		$zakotgr=$xline[2];
		$zakst=$xline[3];
		if (!($zakopl>0)) $zakopl=0;
		if (!($zakotgr>0)) $zakotgr=0;
		$res=$db->exec('update '.TABLE_ORDERS.' set sumopl='.$zakopl.', sumotgr='.$zakotgr.', tstatus="'.$zakst.'" where id='.$zakid);
		if ($res===false)
		{
			echo('Ошибка при обновлении '.$zakid.'<br>');
			$errcnt++;
		}
		else
		{
			$okcnt++;
		}
	}
	if($errcnt>0) echo('Успешно загружено: '.$okcnt.' строк, строк с ошибками: '.$errcnt );
		else
		echo('PostOk');
	
}
function setfin()
{
    global $db;
	$lines = explode("\n",$_POST['popl']);
    $okcnt=0;
    $errcnt=0;
	$tuid=0;
	foreach ($lines as $id=>$line)
	{
		$line=str_replace(',','.',$line);
		if (!($line>' ')) continue;
		$xline = explode(";",$line);
		if ($tuid<>$xline[1])
		{
			$res=$db->exec('delete from '.TABLE_ORDERS_FIN.' where uid= '.$xline[1]);
			$tuid=$xline[1];

		}
		$uuid=$xline[0];
			$ins_data = array('uuid'=>$uuid,'uid'=>$xline[1],'docname'=>$xline[2],'tdate'=> strtotime($xline[3]),'sumin'=>$xline[4],
							'sumout'=>$xline[5],'order_id'=>$xline[6]);
			$res=$db->insert(TABLE_ORDERS_FIN, $ins_data);
			if (!($res>0))	{$errcnt=$errcnt+1;}
	}
	if ($errcnt>0)	
	{
		echo("Err: ".$errcnt."\n");
	}
	else
	{
		echo("PostOk\n");
	}

}
function reset_fin_err()
{
    global $db;
    if (!isset($_GET['uuid'])) return 'Err. No UUID.';
    $res=$db->get(TABLE_ORDERS_FIN,array("uuid"=>$_GET['uuid']));
    if (count($res)>0 && $res[0]['uid']>0){
		$db->delete(TABLE_ORDERS_FIN,array("uid"=>$res[0]['uid']));
	}
	return "Ok.";

}

function setAssist()
{
    global $db;
	if (isset($_POST['Sum']) && !empty($_POST['Sum']) && isset($_POST['Id']) && !empty($_POST['Id']))
	{
		$sum = (float)str_replace(',','.',trim($_POST['Sum']));
		$id=trim($_POST['Id']);
 		$rows = $db->get_rows("SELECT * FROM ".TABLE_PAYMENTS.' where  ordernumber="'.$id.'"');
	 	if (!(count($rows)>0)) return "Error. ID not found";
	 	$ssum=$rows[0]['orderamount'];
		if (!($sum==$ssum)) return "Error. Sum not eq.";
		$db->exec('update '.TABLE_PAYMENTS.' set uchet=1 where ordernumber= "'. $id.'"');	
		return "PostOk\n";
	}
	return "Error. PostErr";
}

function delfin($uuid)
{
    global $db;
	$res=$db->exec('delete from '.TABLE_ORDERS_FIN.' where uuid= "'. $uuid.'"');
	echo("DeleteOk");
	
}


function array_del_empty($myarr)
{
	foreach ($myarr as $key => $value)
	{
		if (is_null($value) || $value=="")
		{
			unset($myarr[$key]);
		}
	}
	return $myarr;
}

function set_sklad()
{
    global $db;
    $in=$_POST['sklad'];
    $md=crc32($in);
    if ($md!=$_GET['md5']) echo("Error CRC".$md."\n".$_GET['md5']);
	$lines = explode("%",$_POST['sklad']);
    $okcnt=0;
    $errcnt=0;
	$res=$db->exec("update ".TABLE_PRODUCTS." set sklad=0,zakazpost=0,reserv=0");
	foreach ($lines as $id=>$line)
	{
		$line=str_replace(',','.',$line);
		if (!($line>' ')) continue;
		$xline = explode(";",$line);
		$id=$xline[0];
		if (!($id>0) || !is_numeric ($id)) continue;
		$ost=$xline[1];
		$zak=$xline[2];
		$res=$db->update(TABLE_PRODUCTS,$id,array("sklad"=>$ost,"zakazpost"=>$zak,"reserv"=>$xline[3]));
		if ($res===false)
		{
			echo('Ошибка при обновлении '.$id.'<br>');
			$errcnt++;
		}
		else
		{
			$okcnt++;
		}
	}
	if($errcnt>0) echo('Успешно загружено: '.$okcnt.' строк, строк с ошибками: '.$errcnt );
		else
		echo('PostOk');
}

function set_zsclose()
{
    global $db;
    $in=$_POST['data'];
    $md=crc32($in);
    if ($md!=$_GET['md5']) echo("Error CRC".$md."\n".$_GET['md5']);
	$lines = explode("%",$in);
    $okcnt=0;
    $errcnt=0;
	foreach ($lines as $id=>$line)
	{
		$line=str_replace(',','.',$line);
		if (!($line>' ')) continue;
		$xline = explode(";",$line);
		$id=$xline[0];
		if (!($id>0) || !is_numeric ($id)) continue;
		$st=$xline[1];
		$res=$db->update(TABLE_ORDERS,$id,array("status"=>$st));
		if ($res===false)
		{
			echo('Ошибка при обновлении '.$id.'<br>');
			$errcnt++;
		}
		else
		{
			$okcnt++;
		}
	}
	if($errcnt>0) echo('Успешно загружено: '.$okcnt.' строк, строк с ошибками: '.$errcnt );
		else
		echo('PostOk');
}