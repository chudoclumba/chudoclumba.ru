<?php

$catprelink = $prelink."&action=catalog";
$act = $prelink;




$sets = array();
$q = $db->get_rows("select * from ".TABLE_SETTINGS."");
foreach ($q as $res) $sets[$res['id']] = $res['value'];

$saves = array('exch_mail','ishop_mail','SMTPHost','SMTPPort','SMTPUser','SMTPPass','SMTPUser1','SMTPPass1','prd_count','pic_height','pic_width','dostavka','cart_time','login_time','sus_login_time','CSSver','JSver','SJSver','SCSSver','Name',"Phone","Phone1",'card_sendmail','cardsm1_time','sus_lines','ofip','cart_res_time');
$places = array('options','ishop','ishop','ishop','ishop','ishop','ishop','ishop','ishop','ishop','ishop','ishop','ishop','ishop','options','ishop','ishop','options','options','options','options','options','options','options','options','options','options');

foreach($saves as $s_id=>$s_val)
{
	if (isSet($_POST['save_'.$s_val.'']))
	{
		$cnt=$db->count(TABLE_SETTINGS,array('id'=>$s_val,'part'=>$places[$s_id]));
		if ($cnt>0){
			$db->exec("update ".TABLE_SETTINGS." set `value` = '".$_POST[$s_val]."' where `id` = '".$s_val."' and `part` = '".$places[$s_id]."'");
		} else {
			$db->exec("INSERT INTO ".TABLE_SETTINGS." (`id`, `value`, `part`) VALUES ('".$s_val."','".$_POST[$s_val]."','".$places[$s_id]."')");
		}
		
		header("Location: ".$prelink);
	}
}


$btns = array('Настройки'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=options\''));
if ($_SESSION['auth']['role']<2) $module['html'] .=button1('Поля',"gotourl('include.php?place=editboxes')",'style="width:103px;"','package').button1('Доступ',"gotourl('include.php?place=susers')",'style="width:103px;"','ulist');
$module['html'] .= '<form action="include.php?place=options" method="post">
<table  cellpadding=0 cellspacing=0 border=0 class="settings">
';

//$module['html'] .= GetForm('','Логин (для входа в СУС)','','<input type="text" value="'.$sets['user_login'].'" name="user_login">','<input type="submit" name="save_user_login" class="button1" value="Сохранить">');
//$module['html'] .= GetForm('','Пароль','Введите новый пароль два раза','<input style="margin:0px 0px 3px 0px" id="pass1" type="password" value="" name="user_password"><br><input id="pass2" type="password" value="" name="user_password2">','<input type="submit" onclick="javascript: if (document.all[\'pass1\'].value != document.all[\'pass2\'].value) { alert(\'Пароли не совпадают!\'); return false; }" name="save_user_password" class="button1" value="Сохранить">');
if($_SESSION['auth']['type'] == 'admin'){
	$module['html'] .= GetOnOff('Сайт временно заблокирован.', 'soft_upd');
}

$module['html'] .= GetForm('','Имя отправителя сообщений','','<input type="text" value="'.$sets['Name'].'" name="Name">','<input type="submit" name="save_Name" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','IP офиса','','<input type="text" value="'.$sets['ofip'].'" name="ofip">','<input type="submit" name="save_ofip" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Телефон','','<input type="text" value="'.$sets['Phone'].'" name="Phone">','<input type="submit" name="save_Phone" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Телефон1','','<input type="text" value="'.$sets['Phone1'].'" name="Phone1">','<input type="submit" name="save_Phone1" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Адрес ящика для заказов','На этот адрес приходят уведомления о заказах','<input type="text" value="'.$sets['ishop_mail'].'" name="ishop_mail">','<input type="submit" name="save_ishop_mail" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','SMTP host','Адрес сервера исходящей почты','<input type="text" value="'.$sets['SMTPHost'].'" name="SMTPHost">','<input type="submit" name="save_SMTPHost" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','SMTP Port','Номер порта исходящей почты','<input type="text" value="'.$sets['SMTPPort'].'" name="SMTPPort">','<input type="submit" name="save_SMTPPort" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','SMTP User','Имя пользователя сервера исходящей почты','<input type="text" value="'.$sets['SMTPUser'].'" name="SMTPUser">','<input type="submit" name="save_SMTPUser" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','SMTP Pass','Пароль сервера исходящей почты','<input type="text" value="'.$sets['SMTPPass'].'" name="SMTPPass">','<input type="submit" name="save_SMTPPass" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','SMTP User1','Имя пользователя 1 сервера исходящей почты','<input type="text" value="'.$sets['SMTPUser1'].'" name="SMTPUser1">','<input type="submit" name="save_SMTPUser1" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','SMTP Pass1','Пароль 1 сервера исходящей почты','<input type="text" value="'.$sets['SMTPPass1'].'" name="SMTPPass1">','<input type="submit" name="save_SMTPPass1" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Товаров на странице (в магазине)','','<input type="text" value="'.$sets['prd_count'].'" name="prd_count">','<input type="submit" name="save_prd_count" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Высота картинки (в магазине)','','<input type="text" value="'.$sets['pic_height'].'" name="pic_height">','<input type="submit" name="save_pic_height" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Ширина картинки (в магазине)','','<input type="text" value="'.$sets['pic_width'].'" name="pic_width">','<input type="submit" name="save_pic_width" class="button1" value="Сохранить">');
if($sets['mod_dostavka']) $module['html'] .= GetForm('','Доставка','','<input type="text" value="'.$sets['dostavka'].'" name="dostavka">','<input type="submit" name="save_dostavka" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Срок жизни корзины','(в днях)','<input type="text" value="'.$sets['cart_time'].'" name="cart_time">','<input type="submit" name="save_cart_time" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Срок резервирования корзины','(в минутах)','<input type="text" value="'.$sets['cart_res_time'].'" name="cart_res_time">','<input type="submit" name="save_cart_res_time" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Срок автовхода в личный кабинет','(в днях)','<input type="text" value="'.$sets['login_time'].'" name="login_time">','<input type="submit" name="save_login_time" class="button1" value="Сохранить">');
$module['html'] .= GetForm('','Срок автовхода в СУС','(в днях)','<input type="text" value="'.$sets['sus_login_time'].'" name="sus_login_time">','<input type="submit" name="save_sus_login_time" class="button1" value="Сохранить">');
$module['html'] .= GetOnOff('Автоматически скрывать пустые группы каталога (без СКОРО)', 'hide_empty_cat');

if($_SESSION['auth']['type'] == 'admin')
{
	$module['html'] .= GetDown('Скачать MySQL базу');
	$module['html'] .= GetUpl('Загрузить MySQL базу');
	$module['html'] .= GetOnOff('Отправка сообщений о забытых корзинах', 'cart_sendmail');
	$module['html'] .= GetForm('','Первое сообщение будет отправлено через','(в часах)','<input type="text" value="'.$sets['cardsm1_time'].'" name="cardsm1_time">','<input type="submit" name="save_cardsm1_time" class="button1" value="Сохранить">');
	$module['html'] .= GetOnOff('Обработка ЧПУ (URL сайта)', 'cpu');
	$module['html'] .= GetOnOff('Обработка ЧПУ (URL каталога)', 'cpucat');
	$module['html'] .= GetOnOff('Форма обратной связи', 'allow_feedback');
	$module['html'] .= GetOnOff('Cписок желаний', 'wish');
	$module['html'] .= GetOnOff('Отображать банер справа', 'show_baner');
//	$module['html'] .= GetOnOff('Печать', 'allow_print');
	$module['html'] .= GetOnOff('YandexKassa', 'yak');
	$module['html'] .= GetOnOff('YandexKassa тестовый режим', 'yaktst');
	$module['html'] .= GetOnOff('Tinkoff эквайринг.', 'tinkoff');
	$module['html'] .= GetOnOff('Разделы только для зарегистрированных пользователей', 'mod_hide_text');
	$module['html'] .= GetOnOff('Комментарии пользователей к разделам сайта', 'allow_comments');
	$module['html'] .= GetOnOff('Отображать лог SQL', 'sql_log');
	$module['html'] .= GetOnOff('Отображать отладочную информацию', 'debug');
	$module['html'] .= GetForm('','Cтрок в таблицах CУС','','<input type="text" value="'.$sets['sus_lines'].'" name="sus_lines">','<input type="submit" name="save_sus_lines" class="button1" value="Сохранить">');

	$module['html'] .= GetForm('','Версия CSS','','<input type="text" value="'.$sets['CSSver'].'" name="CSSver">','<input type="submit" name="save_CSSver" class="button1" value="Сохранить">');
	$module['html'] .= GetForm('','Версия JS','','<input type="text" value="'.$sets['JSver'].'" name="JSver">','<input type="submit" name="save_JSver" class="button1" value="Сохранить">');
	$module['html'] .= GetForm('','Версия СУС CSS','','<input type="text" value="'.$sets['SCSSver'].'" name="SCSSver">','<input type="submit" name="save_SCSSver" class="button1" value="Сохранить">');
	$module['html'] .= GetForm('','Версия СУС JS','','<input type="text" value="'.$sets['SJSver'].'" name="SJSver">','<input type="submit" name="save_SJSver" class="button1" value="Сохранить">');
//	$module['html'] .= GetForm('','Дата окончания хостинга','','<input type="text" value="'.$sets['time_hosting'].'" name="time_hosting">','<input type="submit" name="save_time_hosting" class="button1" value="Сохранить">');
//	$module['html'] .= GetOnOff('Оповещать об окончании хостинга', 'time_host_check');
//	$module['html'] .= GetOnOff('Автоматически отключать хостинг', 'time_ho');
//	$module['html'] .= GetForm('','Дата отключения СУ','','<input type="text" value="'.$sets['time_arenda'].'" name="time_arenda">','<input type="submit" name="save_time_arenda" class="button1" value="Сохранить">');
//	$module['html'] .= GetOnOff('Оповещать об окончании времени аренды СУ', 'time_su_check');
//	$module['html'] .= GetOnOff('Автоматически отключать СУ', 'time_su');
}

$module['html'] .= '
</table>
</form>
';