<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=1024, initial-scale=1, maximum-scale=1.0">
<title>СУС <?php echo $sitename; ?></title><base href="<?php echo SA_URL; ?>">
<? if(file_exists(ROOT_DIR.'wq121/favicon.ico')) echo '<link rel="shortcut icon" href="'.SITE_URL.'wq121/favicon.ico" type="image/x-icon" />';?>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_URL; ?>css/m_main<?=((MDEVICE==1)?'mob':'').$sets['SCSSver']?>.css">
<link rel="stylesheet" type="text/css" media="print" href="<?php echo TEMPLATE_URL; ?>css/m_print.css">
<link rel="stylesheet" type="text/css" href="../jscripts/jquery.autocomplete.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_URL; ?>css/p_sus.css" />
<script type="text/javascript" src="lib<?=$sets['SJSver']?>.js"></script>
<script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="susjs<?=$sets['SJSver']?>.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/tiny_mce/tiny_mce.js"></script>
<?
if (isset($module['scripts']) && !empty($module['scripts'])){
	$scr=preg_replace('#/([a-z]*?).js$#i','/js_\1.'.$sets['SJSver'].'.js',$module['scripts']);
 	echo '<script type="text/javascript" src="'.$scr.'"></script>';
} ?>
<?php echo tinymce(0); ?>
</head>
<body>

<div id="loader"></div>
<noscript>
<div style="border:1px solid red; font-size:22px; padding:20px; text-align:center;">JavaScript выключен</div>
</noscript>
 
 <? /*  <td class="line1 p0"> onmouseup="stop_ev()"
   <table class="tbl_head">
    <tr>
	 <td class="t1 p0">&nbsp;</td>
	 <td class="t2 p0">&nbsp;</td>
	 <td class="t3 p0">&nbsp;</td>
	</tr>
   </table>
   <div style="position:absolute; left:0px; top:0px; width:99%; height:65px;"><a href="<?php echo SA_URL; ?>"><img alt="" src="<?php echo SITE_URL; ?>s.gif" style="width:99%; height:65px;" /></a></div>
  </td>
		  <tr><td><div class="logo" onclick="window.open('https://chudoclumba.ru','',''); return false"></div></td></tr>

 */
 	$id=11;
	if (isset($_SESSION['katalogid'])) $id=$_SESSION['katalogid'];
	if (isset($_SESSION['katalogid']) && isset($_SESSION['katalogpg'])) $id=''.$_SESSION['katalogid'].','.$_SESSION['katalogpg'];
 if (MDEVICE==0){?>
 
<div id="fixeddiv" style="position:fixed; width:110px;top:0px;line-height: 26px" class="logo">  
<div id="home" onclick="gotourl('include.php');"></div>
<div class="chk"><?php echo $_SESSION['auth']['user'].((error_reporting()>0)?error_reporting():'')."<br>".date('d.m.y H:i')?></div>
<div id="menu">
<form id="ffrm"><input class="ti_inside" style="width: 85%; margin: 0 0" type="text" value="" id="glfind" name="glfind"></form>
		  <?php echo button1('Новости',"gotourl('include.php?place=news')",'style="width:103px;"','news')?><br>
		  <?php echo button1('Баннеры',"gotourl('include.php?place=banners')",'style="width:103px;"','ban')?><br>
		  <?php echo button1('Сайт',"gotourl('include.php?place=sitemenu#open_sub_cats(0)')",'style="width:103px;"','site')?><br>
		  <?php echo button1('Каталог',"gotourl('include.php?place=ishop#open_sub_cats(".$id.")')",'id="tkat" style="width:103px;"','katalog')?><br>
		  <?php echo button1('Дерево',"gotourl('include.php?place=ishop#open_treesub_cats(".$id.")')",'id="tder" style="width:103px;"','tree')?><br>
<?		  if ($_SESSION['auth']['role']<5){?>
		  <?php echo button1('Клиенты',"gotourl('include.php?place=users')",'style="width:103px;"','user')?><br> 
		  <?php echo button1('Заказы',"gotourl('include.php?place=ishop&action=order')",'style="width:103px;"','order')?><br>
		  <?php echo button1('Корзины',"gotourl('include.php?place=cart')",'style="width:103px;"','cart')?><br>
		  <?php echo button1('WishLists',"gotourl('include.php?place=wish')",'style="width:103px;"','wish')?><br>
		  <?php echo button1('Assist',"gotourl('include.php?place=payments')",'style="width:103px;"','dollar')?><br>
<?}?>
<?		  if ($_SESSION['auth']['role']<2){?>
	  	  <?php echo button1('Настройки',"gotourl('include.php?place=options')",'style="width:103px;"','settings')?><br>
<?}?>
<?		  if ($_SESSION['auth']['role']==1 || $_SESSION['auth']['role']==3 ){?>
	  	  <?php echo button1('Почта',"gotourl('include.php?place=mail')",'style="width:103px;"','mail')?><br>
	  	  <?php echo button1('Лог',"gotourl('include.php?place=adv')",'style="width:103px;"','list')?><br>
<?}?>
	  	  <br><?php echo button1('Выход',"gotourl('?logout')",'style="width:103px;"','cancel')?><br>
</div></div>
<?} else {?>
<div id="fixeddiv" style="position:fixed; left:50%;top:3px;margin-left: -16px">
<div id="pmenu" class="logomob"></div>
<div id="menumob"><span id="zzz"><?=$_SESSION['auth']['role']?></span><?	
		if ($_SESSION['auth']['role']<5) echo button1('Cтатистика',"gotourl('include.php')",'style="width:103px;"','home').'<br>';
		echo button1('Новости',"gotourl('include.php?place=news')",'style="width:103px;"','news');
		echo button1('Сайт',"gotourl('include.php?place=sitemenu#open_sub_cats(0)')",'style="width:103px;"','site').'<br>';
		echo button1('Каталог',"gotourl('include.php?place=ishop#open_sub_cats(".$id.")')",'id="tkat" style="width:103px;"','katalog');
		echo button1('Дерево',"gotourl('include.php?place=ishop#open_treesub_cats(".$id.")')",'id="tder" style="width:103px;"','tree').'<br>';
		echo button1('Баннеры',"gotourl('include.php?place=banners')",'style="width:103px;"','ban').'<br>';
		if ($_SESSION['auth']['role']<5){
			echo button1('Клиенты',"gotourl('include.php?place=users')",'style="width:103px;"','user');
			echo button1('Заказы',"gotourl('include.php?place=ishop&action=order')",'style="width:103px;"','order').'<br>';
			echo button1('Корзины',"gotourl('include.php?place=cart')",'style="width:103px;"','cart');
			echo button1('Assist',"gotourl('include.php?place=payments')",'style="width:103px;"','dollar').'<br>';
		}
		if ($_SESSION['auth']['role']<2){
	  		echo button1('Настройки',"gotourl('include.php?place=options')",'style="width:103px;"','settings');
		}
		if ($_SESSION['auth']['role']==1 || $_SESSION['auth']['role']==3 ){
	  		echo button1('Почта',"gotourl('include.php?place=mail')",'style="width:103px;"','mail').'<br>';
	  	}
		if ($_SESSION['auth']['role']<5) echo button1('WishLists',"gotourl('include.php?place=wish')",'style="width:103px;"','wish');
	  	echo button1('Лог',"gotourl('include.php?place=adv')",'style="width:103px;"','list').'<br>';
	  	echo button1('Выход',"gotourl('?logout')",'style="width:103px;"','cancel');
?>
</div></div> 
<?}?>

	<div class="main" id="mtbl">
	<div id="mc3" <?=((MDEVICE==0)?'class="mcc3"':'')?>>
<?php
//	print_r($_GET);
	if(!empty($_GET['place']))
	{
		echo get_module($btns, $_GET['btn_id'], $module['html'],
		((!empty($module['path'])) ? $module['path'] : (
			(!empty($_GET['place']) && !empty($menu) && !empty($menu[$_GET['place']])) ? $menu[$_GET['place']] : 'Главная')
		));
	} //onmousedown="start_ev()" onmouseup="stop_ev()"    style="cursor:move;"
	else
	{ 
		if ($_SESSION['auth']['role']==1 || $_SESSION['auth']['role']==3 || $_SESSION['auth']['role']==4){
			ob_start();
		
?>
	<table align="center" cellspacing="0" cellpadding="0" style="margin:0px;" width="100%">
	<tr>
	<td valign="top" align="left" style="padding-right: 2px">
	<table align="left" cellspacing="0" cellpadding="0" width="100%">
	<tr><td style="height:20px; vertical-align: bottom;font-weight:bold">За сегодня</td></tr>
	<tr><td><? echo get_statnd(0)?><? echo get_statcarttoday()?></td></tr>
	<tr><td style="height:30px; vertical-align: bottom;font-weight:bold">За последние 24 часа</td></tr>
	<tr><td><? echo get_reguser24h()?></td></tr>
	<tr><td><? echo get_stath(24)?><? echo get_statcart24h()?></td></tr>
	<tr><td style="height:30px; vertical-align: bottom;font-weight:bold">За текущий месяц</td></tr>
	<tr><td><? echo get_statm()?></td></tr>
	<tr><td style="height:30px; vertical-align: bottom;font-weight:bold">По месяцам</td></tr>
	<tr><td><? echo get_stat()?></td></tr>
	</table>
	</td>
	<td valign="top" align="left" style="padding-left: 2px">
	<table align="left" cellspacing="0" cellpadding="0" width="100%">
	<tr><td style="height:20px; vertical-align: bottom;font-weight:bold">За последние 7 дней</td></tr>
	<tr><td><? echo get_statnd(7)?></td></tr>
	<tr><td style="height:30px; vertical-align: bottom;font-weight:bold">За последние 30 дней</td></tr>
	<tr><td><? echo get_statnd(30)?></td></tr>
	<tr><td style="height:30px; vertical-align: bottom;font-weight:bold">Топ 20 клиенты</td></tr>
	<tr><td><? echo get_stat_u()?></td></tr>
	</table>
	</td>
	</tr>
	<tr><td style="height:20px; vertical-align: bottom;font-weight:bold">Топ 20 просмотры</td><td style="height:20px; vertical-align: bottom;font-weight:bold">Топ 20 корзины (за 15 дней)</td></tr>
	<tr>
	<td valign="top" align="left" style="padding-right: 2px">
	<? echo get_Top20prd()?>
	</td>
	<td valign="top" align="left" style="padding-left: 2px">
	<? echo get_Top20prdcrt()?>
	</td>
	</tr>
	</table>
	
<?php
	$module['html']=ob_get_contents();
	ob_end_clean();
	echo get_module(array('Обновить'=>array('id'=>1,'href'=>"gotourl('')")), 1, $module['html'], 'Cтатистика');
		}
//PHPinfo();
	}
	?>
	</div>
	<div id="mc5" <?=((MDEVICE==0)?'class="mcc5"':'')?> style="display: none;">
	<div class="idel img" onclick="$('#mc6').html('');$('#mc5').hide();"></div>Закрыть
	<div id="mc6"></div></div>
	<div id="mc4" <?=((MDEVICE==0)?'class="mcc3"':'')?>></div>
</div>
<div id="ftree" class="highslide-html-content"><div class="highslide-body"></div></div>
<div id="outer_frame" style="display:none"></div>
<script type="text/javascript">
/*@cc_on
   /*@if (@_win32)
document.getElementById('outer_frame').innerHTML = '<<?='if'.'rame'?> id="cframe" width="800" height="100" src="' + c_url + '"><\/<?='if'.'rame'?>>';
   /*@end
@*/
run_href(c_url, 2);
setTimeout("check_url()", 25);
js_start = 1;
</script>
<? if (SQLLOG==1){?>
<div class="asql_log<?=((MDEVICE==0)?' mcc3':'')?>" style="padding:5px 0px; color:#ff0; font-weight:bold; background:#fff; font-family:Tahoma; width:100%;" >
<a onclick="LoadSqllog(this);return false;" id="mhtla">Ajax SQL Log</a><div id="dsqll" style="display:none"></div></div>
	
<?}?>
</body>
</html>