<?php

$btns = get_cat_btns(5);
$act = $prelink;
if($_SESSION['auth']['type'] != 'admin'){
	exit;
}
if(!empty($_POST['param']))
{
	foreach($_POST['param'] as $id=>$value)
	{
		if(!empty($_POST['save_'.$id]))
		{
			$db->exec("update ".TABLE_SETTINGS." set value = '".$value."' where id = '".$id."'");
			header("Location:".$_SERVER['HTTP_REFERER']."");
			exit;
		}
	}
}

$module['html'] = '<br>';

$module['html'] .= '
<form  action="" method="post">
<table  cellpadding=0 cellspacing=0 border=0 class="settings">
<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="width:124px;">&nbsp;</td>
</tr>';

//$module['html'] .= GetOnOff('Валюта', 'mod_currency');
//$module['html'] .= GetOnOff('Привязка к курсу ЦБ', 'mod_cbr');
$module['html'] .= GetOnOff('Хит продаж', 'mod_hit');
$module['html'] .= GetOnOff('Анимация Хит продаж', 'mod_hit_an');
$module['html'] .= GetOnOff('Новинки', 'mod_new');
$module['html'] .= GetOnOff('Спецпредложение', 'mod_spec');
$module['html'] .= GetOnOff('Оформление заказа с регистрацией', 'mod_order_reg');
$module['html'] .= GetOnOff('Наличие товаров на складе', 'nal_sklad');
$module['html'] .= GetOnOff('Сравнение товаров', 'mod_prd_vs');
//$module['html'] .= GetOnOff('Сравнение товаров с кнопкой', 'mod_prd_vs_new');
$module['html'] .= GetOnOff('Старая цена', 'mod_old_price');
$module['html'] .= GetOnOff('Скидка', 'mod_skidka');
$module['html'] .= GetOnOff('Сортировка', 'mod_sort');
$module['html'] .= GetOnOff('Характеристики по группам', 'mod_chars');
$module['html'] .= GetOnOff('Комментарии пользователей к товарам', 'mod_comments');
//$module['html'] .= GetOnOff('Палитра', 'ishop_palitra');
$module['html'] .= GetOnOff('Скидка на товары', 'mod_prd_skidka');
$module['html'] .= GetOnOff('Доп. фотографии', 'mod_prd_foto');
$module['html'] .= GetOnOff('Отображать описание группы над товарами', 'mod_text_cat');
//$module['html'] .= GetOnOff('Список товаров с описанием', 'mod_prd_dsc');
//$module['html'] .= GetOnOff('Корзина  без перезагрузки', 'ajax_cart');
$module['html'] .= GetOnOff('Добавление товаров в корзину без перезагрузки', 'ajax_add_cart');
$module['html'] .= GetOnOff('Рекомендуемые товары', 'mod_rec_prds');
//$module['html'] .= GetOnOff('Рекомендуемые товары с фотками', 'mod_recprd_f');
//$module['html'] .= GetOnOff('Прайс-лист', 'mod_price');
$module['html'] .= GetOnOff('.CSV Импорт/Экспорт', 'mod_import_cvs');
$module['html'] .= GetOnOff('Групповое изменение цен', 'mod_change_p');
$module['html'] .= GetOnOff('Рейтинг', 'mod_rating');
//$module['html'] .= GetOnOff('Выбор размера', 'select_razmer');
//$module['html'] .= GetOnOff('Статистика по покупателям', 'mod_cust_stat');
//$module['html'] .= GetOnOff('Статистика по заказам', 'mod_order_stat');
//$module['html'] .= GetOnOff('Плавное увеличение фото', 'mod_p_size');
//$module['html'] .= GetOnOff('Фильтр', 'mod_filter_top');
//$module['html'] .= GetOnOff('Поиск в текущей группе', 'mod_search');
//$module['html'] .= GetOnOff('Дисконтные карты', 'mod_cards');
//$module['html'] .= GetOnOff('Производитель', 'mod_proizv');
//$module['html'] .= GetOnOff('Оптовые цены', 'mod_opt_price');
//$module['html'] .= GetOnOff('Отображать цену', 'mod_show_price');
//$module['html'] .= GetOnOff('Цена текстом', 'mod_change_price');
//$module['html'] .= GetOnOff('Отображать "Количество"', 'show_kolvo');
//$module['html'] .= GetOnOff('Отображать "Добавить в корзину"', 'mod_show_cart');
//$module['html'] .= GetOnOff('Отображать "Подробнее"', 'prd_podr');
//$module['html'] .= GetOnOff('Доставка', 'mod_dostavka');
//$module['html'] .= GetOnOff('Расширенный поиск', 'mod_adv_search');

//echo GetForm('','Доставка','Стоимость доставки','<input type="text" value="'.$sets['ishop_dostavka'].'" name="dostavka">','<input type="submit" name="save_dostavka" class="button1" value="Сохранить">');


//echo GetOpen('Текст','','','<a class="trig_on" href="'.$prelink.'&amp;action=letters">Изменить</a>');

//echo GetOpen('Настройки','','','<a class="trig_on" href="'.$prelink.'&amp;action=options">Открыть</a>');

//$module['html'] .= GetOpen('Параметры товара','','','<a class="trig_on" href="'.$prelink.'&amp;action=params">Открыть</a>');

//$module['html'] .= GetOpen('Характеристики товара','','','<a class="trig_on" href="'.$prelink.'&amp;action=charsets">Открыть</a>');

//$module['html'] .= GetOpen('Производители','','','<a class="trig_on" href="'.$prelink.'&amp;action=sellers">Открыть</a>');
//$module['html'] .= GetOpen('Возрастные категории','','','<a class="trig_on" href="'.$prelink.'&amp;action=vozrast">Открыть</a>');
//$module['html'] .= GetOpen('Весовые категории','','','<a class="trig_on" href="'.$prelink.'&amp;action=ves">Открыть</a>');

$module['html'] .= '
</table>
</form>
';
