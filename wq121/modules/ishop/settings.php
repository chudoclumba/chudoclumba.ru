<?php


$btns = get_cat_btns(3);

$act = $prelink;

if(!empty($_POST['param']))
{
	foreach($_POST['param'] as $id=>$value)
	{
		if(!empty($_POST['save_'.$id]))
		{
			$db->exec("update ".TABLE_PREFIX."settings set value = '".$value."' where id = '".$id."'");
			header("Location:".$_SERVER['HTTP_REFERER']."");
			exit;
		}
	}
}

$module['html'] = '<br>';

$module['html'] .= '
<form  style="margin:-10px 5px 0px 5px;" action="" method="post">
<table width="100%" cellpadding=0 cellspacing=0 border=0 class="settings">';
if($sets['mod_import_cvs']) $module['html'] .= GetOpen('Загрузка содержимого каталога из файла формата .csv','','',button1('Загрузить CSV', "gotourl('include.php?place=ishop#load_price(0)')",'','upload'));
if($sets['mod_import_cvs']) $module['html'] .= GetOpen('Восстановление каталога из резервной копии','','',button1('Восстановить', "show_restore();",'','undo'));
if($sets['mod_import_cvs']) $module['html'] .= GetOpen('Выгрузка содержимого каталога в файл формата .csv','','',button1('Выгрузить CSV', "gotourl('include.php?place=ishop#save_price()')",'','download'));
if($sets['mod_spec']) $module['html'] .= GetOpen('Спецпредложение','','',button1('Спецпредложение', "gotourl('include.php?place=ishop&action=specpredl')"));
if($sets['mod_new']) $module['html'] .= GetOpen('Новинки','','',button1('Новинки', "gotourl('include.php?place=ishop&action=novinki')"));
if($sets['mod_hit']) $module['html'] .= GetOpen('Хиты продаж','','',button1('Хиты продаж', "gotourl('include.php?place=ishop&action=hits')"));
$module['html'] .= GetOpen('Таблица стандартов поставки','','',button1('Стандарты поставки', "gotourl('include.php?place=ishop#open_upak(0,1)')"));
$module['html'] .= GetOpen('Таблица группировки заказов','','',button1('Группы заказов', "gotourl('include.php?place=ishop#open_tper(0,1)')"));
//$module['html'] .= GetOpen('Загрузка оплат/отгрузок из файла формата .csv','','',button('Загрузить CSV', "gotourl('include.php?place=ishop#load_opl()')"));
//$module['html'] .= GetOpen('Сделать URL страницы из названий','','','<a class="trig_on" href="javascript:change_urls()">Выполнить</a>');
$module['html'] .= GetOpen('Убрать URL страницы из названий','','','<a class="trig_on" href="javascript:back_change_urls()">Выполнить</a>');
$module['html'] .= GetOpen('Пересчитать открытые товары','','',button1('Пересчет', "recalc_tcnt();",'','complay'));
$module['html'] .= GetOpen('Разослать TempSend','','',button1('Разослать', "tmp_send();",'','complay'));
$module['html'] .= GetOpen('Дерево с ЧПУ ссылками','','',button1('Дерево', "vlink_tree()",'','tree'));
$module['html'] .= GetOpen('Сбросить счетчик просмотров','','',button1('Сброс', "reset_views();",'','complay'));
$module['html'] .= GetOpen('Проверка ЧПУ товаров','','',button1('Проверка', "check_chpu();",'','complay'));


if($sets['ishop_palitra']) $module['html'] .= get_module_link('Палитра', 'color', $prelink);
if($sets['mod_change_p']) $module['html'] .= get_module_link('Изменение цен', 'change_price', $prelink);
if($sets['mod_price']) $module['html'] .= get_module_link('Прайс-лист', 'pricelist', $prelink);
if($sets['mod_currency']) $module['html'] .= get_module_link('Валюта', 'currency', $prelink);

if($_SESSION['auth']['type'] == 'admin')
{
	$module['html'] .= GetOpen('Редактировать параметры товаров','','',button1('Параметры', "gotourl('include.php?place=ishop&action=params')"));
	$module['html'] .= GetOpen('Конвертация пользователей','','',button1('Конвертация', "gotourl('include.php?place=ishop&action=convusers')"));

	$module['html'] .= GetOpen('Редактировать группы параметры товаров','','',button1('Группы параметров', "gotourl('include.php?place=ishop&action=gr_params')"));
	
//	$module['html'] .= get_module_link('Параметры', 'params', $prelink);
//	$module['html'] .= get_module_link('Группы параметров', 'gr_params', $prelink);
	$module['html'] .= GetOpen('Выполнить SQL запрос','','',button1('Run SQL', "gotourl('include.php?place=ishop#run_sql()')",'','complay'));
}


$module['html'] .= '
</table>
</form>
';
$module['path']='Дополнительно';
