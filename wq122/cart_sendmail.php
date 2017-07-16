<?php
//	ini_set('error_reporting', E_ALL );
//	error_reporting(E_ALL | E_STRICT);
//	ini_set('display_errors', 1);
$ret='';
define('SITE_URL', '//chudoclumba.ru/');
//$tm=microtime(true);
define('ROOT_DIR', '/home/c/chudocl/public_html/');
define('INC_DIR', ROOT_DIR.'includes/');
include INC_DIR.'dbconf.php';
include INC_DIR.'dbtables.php';
require INC_DIR.'dbconnect.php';
include INC_DIR.'functions.php';
include_once INC_DIR."site.class.php";
$site = Site::gI();
$sets = $site->GetSettings();
if (!isset($sets['cart_sendmail']) || $sets['cart_sendmail']!=1) exit;
if (!isset($sets['cardsm1_time']) || $sets['cardsm1_time']<1) exit;

$ebox = $site->GetEditBoxes();
global $db,$sets;
	function skidka($value, $skidka = 0)
	{
		global $db,$sets;
		if($sets['mod_skidka'])
		{
			return $value = $value - (0.01 * $skidka * $value);
		}
		return $value;
	}
	function fix_price($value)
	{
		return $value;
	}
	function format_price($value,$val=0)
	{
		if	($val==1) 
		{
			return '<span class="t_price">'.number_format($value,2,'.','').'</span> '.'руб.';
		}
		else
		{
			return '<span class="t_price">'.number_format($value,2,'.','').'</span> ';
		}
	}
	function s_price($value, $skidka = 0)
	{
		$value = skidka($value,$skidka);
		$value = fix_price($value);
		return ''.format_price($value).'';
	}
	function s_price_c($value,$val=0)
	{
		$value = fix_price($value);
		return ''.format_price($value,$val).'';
	}

$qy='SELECT id,userid, sum(d.cnt) as cnt FROM '.TABLE_CART.' c left join '.TABLE_CART_DET.
' d on d.cartid=c.id where d.cartid is not null and c.date<(UNIX_TIMESTAMP(now())-'.$sets['cardsm1_time'].'*60*60) and c.date>(UNIX_TIMESTAMP(now())-30*24*60*60) and c.userid>0 and c.isset<1 group by c.id having cnt>2 limit 20';
//$qy='SELECT id,userid, sum(d.cnt) as cnt FROM '.TABLE_CART.' c left join '.TABLE_CART_DET.' d on d.cartid=c.id where d.cartid is not null and  c.date<(UNIX_TIMESTAMP(now())-5*60) and c.userid=1024 and c.isset<1 group by c.id having cnt>2 ';
$res=$db->get_rows($qy);
$ret='';
foreach($res as $cart){
	$qy='SELECT p.*, c.cnt from '.TABLE_CART_DET.' c, '.TABLE_PRODUCTS." p where p.id=c.prdid and c.cartid='".$cart['id']."' order by p.param_srokpostavki,p.title";
	$prds=$db->get_rows($qy);
	$qy='SELECT id,login,info,sale from '.TABLE_USERS.' where id='.$cart['userid'];
	$users=$db->get_rows($qy);
	if (!isset($users[0]['login']) || empty($users[0]['login']) || strpos($users[0]['login'],'chudoclumba')>0) continue;
	$inf = unserialize($users[0]['info']);
	$orders1 = $db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$users[0]['id']."' && status != 6");
	if(!empty($sets['mod_prd_skidka']) && !empty($orders1['0']['summa'])){
		$orders = $db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
	}
	$sale=$users[0]['sale'];
	if ($orders['0']['percent']>$sale) $sale=$orders['0']['percent'];
	$ret='';
	ob_start();
	?>
<p>Здравствуйте, <?=$inf[9].' '.$inf[8].' '.$inf[11]?>!</p>
<p>Некоторое время назад Вы положили в Вашу корзину товары, но не оформили заказ.</p>
<p>Напоминаем, количество товаров ограничено и они будут зарезервированы за Вами только после оформления заказа!</p>
Ваша корзина:
<table  style="border-collapse:collapse;border-width:1px;border-style:solid;border-color:#a2f7aa"><tr><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Фото</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Наименование</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Цена</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Кол-во</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Стоимость</th><th style="border-width:1px;border-style:solid;border-color:#a2f7aa">Срок поставки</th></tr>
<? 
$summa=0;
foreach($prds as $val) {
	$link=SITE_URL.'ishop/product/'.$val['id'];
	if(!empty($val['vlink']) && $sets['cpucat']==1){
		$link=SITE_URL.$val['vlink'].'?utm_source=email_oldcart&utm_medium=email&utm_campaign=забытая_корзина';
	}
	$summa+=($val['tsena'] - (0.01 * $val['skidka'] * $val['tsena']))*$val['cnt'];
?>
<tr><td style="border-width:1px;border-style:solid;border-color: #a2f7aa"><a href="<?=$link?>">
   <img border="0" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=100&y=100&crop" style="display:block" /></a></td>  
  <td style="border-width:1px;border-style:solid;border-color:#a2f7aa"><?=$val['param_kodtovara']?><br><a style="color:#1C509A;" href="<?=$link?>"><?=$val['name']?></a></td>
  <td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;"><?=s_price($val['tsena'],$val['skidka'])?></td><td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: center;"><?=$val['cnt']?></td>
  <td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;"><?=s_price($val['tsena']*$val['cnt'],$val['skidka'])?></td><td style="border-width:1px;border-style:solid;border-color:#a2f7aa"><?=$val['param_srokpostavki']?></td></tr>
<? } ?>
 <tr><td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;" colspan="4">Сумма:</td><td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;"><?=s_price_c($summa)?></td><td style="border-width:1px;border-style:solid;border-color:#a2f7aa" rowspan="3">&nbsp;</td></tr>
<?if(!empty($sets['mod_prd_skidka'])){?>
 <tr><td  style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;" colspan="4">Скидка (<?=$sale?>%):</td><td style="border-width:1px;border-style:solid;border-color:#a2f7aa;text-align: right;"><?=s_price_c(($summa*$sale)/100)?></td></tr>
 <tr><td style="text-align: right;" colspan="4">Всего:</td><td style="border-left-width:1px;border-left-style:solid;border-left-color:#a2f7aa;text-align: right;"><?=s_price_c($summa - ($summa*card)/100 - ($summa*$sale)/100)?></td></tr>
<?}?>
</table><br/>
<a href="<?=SITE_URL?>service/zcart/<?=$cart['id'].'/'.md5($cart['id'].$cart['userid']).'?utm_source=email_oldcart&utm_medium=email&utm_campaign=забытая_корзина'?>"><img src="<?=SITE_URL?>templates/101/images/btn_cart_go.jpg" alt="Оформить заказ..." width="213px" height="41px" /></a>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	sendmail_a($users[0]['login'],'Вы оставили товары в корзине',$ret,'?utm_source=email_oldcart&utm_medium=email&utm_campaign=забытая_корзина');
	$db->update(TABLE_CART,$cart['id'],array('isset'=>3));
}
//echo $ret;

file_put_contents ( ROOT_DIR.'data/System/pcron.txt', print_r(date('d/m/y H:i')."\n",true) , FILE_APPEND  );
