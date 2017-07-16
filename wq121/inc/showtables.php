<?php






function show_ordersprd($q){
	global $st_zak_arrray;
	if (count($q)==0) {
		return 'Заказов нет.<br>';
	}
	ob_start();
?>	
		<table class="main_no_height" id="zakTable" <?=((MDEVICE==1)?'width=100%':'')?> onclick="ztcl(arguments[0])">
		<tr>
			<th class="news_header cen">Кол-во</th>
			<th class="news_header cen">Обн-н</th>
			<th class="news_header cen">Создан</th>
			<th class="news_header cen">№</th>
			<th class="news_header rgt">Сумма</th>
			<th class="news_header rgt">Оплата</th>
			<th class="news_header rgt">Отгрузка</th>
			<th class="news_header cen">Скидка</th>
			<th class="news_header cen" >ID</th>
			<th class="news_header">ФИО</th>
			<th class="news_header">Статус</th>
			<th class="news_header">Состояние</th>
			<th class="news_header cen">Функции</th></tr>
		<?php	foreach($q as $row){?>
			<tr id="ord_row_<?=$row['id']?>">
				<td class="cen"><?=(!empty($row['cnt'])) ? $row['cnt'] : '&nbsp;'?></td>
				<td title="<?php echo date('G:i', $row['datan'])?>"><?php echo date('j/n', $row['datan'])?></td>
				<td title="<?php echo date('G:i', $row['data'])?>"><?php echo date('j/n/y', $row['data'])?></td>
				<td class="cen cli vo" title="Просмотр заказа"><?=$row['id']?></td>
				<td class="rgt"><?=number_format($row['summa']-$row['summa']*$row['skidka']/100,2,'.','')?></td>
				<td class="rgt"><?=(($row['sumopl']>0)?$row['sumopl']:' ')?></td>
				<td class="rgt"><?=(($row['sumotgr']>0)?$row['sumotgr']:' ')?></td>
				<td class="cen"><?=(!empty($row['skidka'])) ? number_format($row['skidka'],2,'.','') : '&nbsp;'?></td>
				<td class="cen <?=(($row['user_id']>0) ? 'cli vu" title="Просмотр клиента">'.$row['user_id'] : '"> ')?></td>
				<td class="cli" <?=($row['user_id']>0) ? 'onclick="view_userorders(this,'.$row['user_id'].');" title="Просмотр заказов клиента"' : 'onclick="view_usermorders(this,\''.$row['id'].'\');" title="Просмотр заказов по email"'?> > <?=(!empty($row['fio'])) ? htmlspecialchars($row['fio'],ENT_COMPAT | ENT_XHTML,'utf-8') : '&nbsp;'?></td>
				<td><?=(!empty($row['tstatus'])) ? htmlspecialchars($row['tstatus'],ENT_COMPAT | ENT_XHTML,'utf-8') : '&nbsp;'?></td>
				<td><?echo $st_zak_arrray[$row['status']];?></td>
				<td class="lft">
					<div class="img idel"></div><div class="img ild<?=($row['ld']==0) ? " iunld":""?>"></div></td></tr>
			<?}?>
	</table>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;

}



function show_orders($q){
	global $st_zak_arrray;
	if (count($q)==0) {
		return 'Заказов нет.<br>';
	}
	ob_start();
    $ind = rand(0,999);  

?>	
		<table class="main_no_height" id="zakTable<?=$ind?>" <?=((MDEVICE==1)?'width=100%':'')?> onclick="ztcl(arguments[0])">
		<tr>
			<th class="news_header cen">Обн-н</th>
			<th class="news_header cen">Создан</th>
			<th class="news_header cen">№</th>
			<th class="news_header rgt">Сумма</th>
			<th class="news_header rgt">Оплата</th>
			<th class="news_header rgt">Отгрузка</th>
			<th class="news_header cen">Скидка</th>
			<th class="news_header cen" >ID</th>
			<th class="news_header">ФИО</th>
			<th class="news_header">Статус</th>
			<th class="news_header">Состояние</th>
			<th class="news_header cen">Функции</th></tr>
		<?php	foreach($q as $row){?>
			<tr id="ord_row_<?=$row['id']?>">
				<td title="<?php echo date('G:i', $row['datan'])?>"><?php echo date('j/n', $row['datan'])?></td>
				<td title="<?php echo date('G:i', $row['data'])?>"><?php echo date('j/n/y', $row['data'])?></td>
				<td class="cen cli vo" title="Просмотр заказа"><?=$row['id']?></td>
				<td class="rgt"><?=number_format($row['summa']-$row['summa']*$row['skidka']/100,2,'.','')?></td>
				<td class="rgt"><?=(($row['sumopl']>0)?$row['sumopl']:' ')?></td>
				<td class="rgt"><?=(($row['sumotgr']>0)?$row['sumotgr']:' ')?></td>
				<td class="cen"><?=(!empty($row['skidka'])) ? number_format($row['skidka'],2,'.','') : '&nbsp;'?></td>
				<td class="cen <?=(($row['user_id']>0) ? 'cli vu" title="Просмотр клиента">'.$row['user_id'] : '"> ')?></td>
				<td class="cli" <?=($row['user_id']>0) ? 'onclick="view_userorders(this,'.$row['user_id'].');" title="Просмотр заказов клиента"' : 'onclick="view_usermorders(this,\''.$row['id'].'\');" title="Просмотр заказов по email"'?> > <?=(!empty($row['fio'])) ? htmlspecialchars($row['fio'],ENT_COMPAT | ENT_XHTML,'utf-8') : '&nbsp;'?></td>
				<td><?=(!empty($row['tstatus'])) ? htmlspecialchars($row['tstatus'],ENT_COMPAT | ENT_XHTML,'utf-8') : '&nbsp;'?></td>
				<td><?echo $st_zak_arrray[$row['status']];?></td>
				<td class="lft">
					<div class="img idel"></div><div class="img ild<?=($row['ld']==0) ? " iunld":""?>"></div></td></tr>
			<?php
		}	?>
	</table>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;

}
function show_order($ord_inf,$prd_or){
	global $st_zak_arrray,$db;
	if (count($ord_inf)==0) {
		return 'Заказов нет.<br>';
	}
	if($ord_inf['0']['user_id']>0){
		$orders1 = $db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = ".quote_smart($ord_inf['0']['user_id'])." && status != 6 && data <".quote_smart($ord_inf['0']['data']));
		$skidka=0;
		$summa=$orders1['0']['summa'];
		if(!empty($orders1['0']['summa']) && $orders1['0']['summa']>0 ){
			$orders = $db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
			$skidka = $orders['0']['percent'];
		}
		$qs = $db->get_rows("SELECT SUM(sumin) as sumin,SUM(sumout) as sumout FROM ".TABLE_ORDERS_FIN." WHERE uid = ".quote_smart($ord_inf['0']['user_id']));
		$sumin=$qs[0]['sumin'];
		$sumout=$qs[0]['sumout'];
	}

	ob_start();
?>	
<table align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
<tr><td class="news_td" style="padding-left:10px;"><b><?echo date('G:i,   d-m-Y',$ord_inf['0']['datan'])?> (<?echo date('d-m-Y',$ord_inf['0']['data'])?>)</b></td>
<td class="news_td" style="padding-left:10px;">Заказ: <b><?echo $_GET['id']?></b></td>
<td class="news_td" style="padding-left:10px;"><?=(($ord_inf['0']['user_id']>0)?"<a onclick=view_user(this,\"{$ord_inf['0']['user_id']}\")>UserId:</a> ":"UserId: ")?><input type="text" value="<?php echo $ord_inf['0']['user_id'] ?>" onchange="save_order1(<?=quote_smart($_GET['id'])?>, this.value,2)"></td></tr>
<tr><td class="news_td" style="padding-left:10px;"><b><?=htmlspecialchars($ord_inf['0']['tstatus'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td>
<td class="news_td" style="padding-left:10px;"><b><?=$st_zak_arrray[$ord_inf['0']['status']]?></b></td>
<td class="news_td" style="padding-left:10px;"><?=(($ord_inf['0']['user_id']>0)?"Баланс: <b>".number_format($sumin-$sumout, 2, '.', '')."</b>":"")?></td></tr>
		<?php if($ord_inf['0']['user_id']>0){?>
<tr><td class="news_td" style="padding-left:10px;">Сумма предыдущих заказов: <b><?=number_format ($summa, 2, '.', '')?></b></td>
<td class="news_td" style="padding-left:10px;">Рекомендуемая скидка: <b><?=$skidka?></b></td>
<td class="news_td" style="padding-left:10px;">Скидка: <input type="text" value="<?php echo $ord_inf['0']['skidka'] ?>" onchange="save_order1(<?=quote_smart($_GET['id'])?>, this.value,1)"></td></tr>
		<?php }?>

<tr><td class="news_td" style="padding-left:10px;">ФИО: <b><?=htmlspecialchars($ord_inf['0']['fio'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td>
<td class="news_td" style="padding-left:10px;">Тел.: <b><?=htmlspecialchars($ord_inf['0']['tel'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td>
<td class="news_td" style="padding-left:10px;">E-mail: <b><?=htmlspecialchars($ord_inf['0']['email'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td></tr>
<tr><td class="news_td" style="padding-left:10px;" colspan="3">Адрес: <b><?=htmlspecialchars($ord_inf['0']['adr'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td>
</tr>
<tr><td class="news_td" style="padding-left:10px;">Способ доставки: <b><?=htmlspecialchars($ord_inf['0']['dost'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td>
<td class="news_td" style="padding-left:10px;">Способ оплаты: <b><?=htmlspecialchars($ord_inf['0']['opl'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td>
<td class="news_td" style="padding-left:10px;"><?= (($ord_inf['0']['sumopl']>0)?"Оплата: <b>".number_format($ord_inf['0']['sumopl'], 2, '.', '').'</b>':'&nbsp;')?></td></tr>
<? if (isset($ord_inf['0']['comment']) && !empty($ord_inf['0']['comment'])) {?>
<tr><td class="news_td" style="padding-left:10px;" colspan="3">Доп информация: <b><?=htmlspecialchars($ord_inf['0']['comment'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td></tr>
<?}?>
<? if (isset($ord_inf['0']['dopinf']) && !empty($ord_inf['0']['dopinf'])) {?>
<tr><td class="news_td" style="padding-left:10px;" colspan="3">Наш комментарий: <b><?=htmlspecialchars($ord_inf['0']['dopinf'],ENT_COMPAT | ENT_XHTML,'utf-8')?></b></td></tr>
<?}?>
</table>
<table width="98%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
<tr><th class="news_header lft">№</th>
<th class="news_header lft">ID</th>
<th class="news_header lft">Код товара</th>
<th class="news_header lft">Наименование</th>
<th class="news_header cen">Кол-во</th>
<th class="news_header cen">Скидка</th>
<th class="news_header rgt">Цена</th>
<th class="news_header cen">Отмены</th>
<th class="news_header lft">Причина</th>
<th class="news_header cen">Отгр.</th>
<th class="news_header cen">Цена</th>
<th class="news_header cen">Из заказа</th></tr>
<?php
		$cn = 0;
		$pr = 0;
		$ot = 0;
		foreach($prd_or as $id=>$row){
			$cn += $row['count'];
			$pr += $row['count'] *($row['summa']-$row['summa']*$row['skidka']/100);
			$ot += $row['otgr'];
			$class='';
			if ($row['otgr']==$row['count']) $class='class="higl"';
			if ($row['isdel']<0) $class='class="rrr"';
			?>
<tr <?=$class?>><td class="news_td" style="padding-left:10px;"><?php echo $row['kodstr']?></td>
<td class="news_td"><?php echo $row['prd_id']?></td>
<td class="news_td cli" onclick="view_prdbyord(this,'<?php echo $row['prd_id']?>');"><?php echo $row['kodtov']?></td>
<td class="news_td"><?php echo $row['name']?></td>
<td class="news_td cen"><?php echo $row['count']?></td>
<td class="news_td cen"><?php echo number_format($row['skidka'], 2, '.', '')?></td>
<td class="news_td rgt"><?php echo $row['summa']?></td>
<td class="news_td cen"><?php echo $row['isdel']?></td>
<td class="news_td"><?php echo($row['prdel']>' ' ? $row['prdel'] : '&nbsp;') ?></td>
<td class="news_td cen"><?php echo $row['otgr']?></td>
<td class="news_td rgt"><?php echo $row['sumotgr']?></td>
<td class="news_td rgt cli" onclick="view_order(this,'<?=$row['old_id']?>')"><?=$row['old_id']?></td></tr>
			<?php
		}	?>
<tr><td class="news_td" style="padding-left:10px;"></td>
<td class="news_td" style="padding-left:10px;"></td>
<td class="news_td" style="padding-left:10px;"></td>
<td class="news_td" style="padding-left:10px;font-weight:bold;">Итого:</td>
<td class="news_td cen" style="font-weight:bold;"><?=$cn?></td>
<td class="news_td" style="padding-left:10px;"></td>
<td class="news_td rgt" style="font-weight:bold;"><?=number_format($pr,2,'.','')?></td>
<td class="news_td" style="padding-left:10px;">&nbsp;</td>
<td class="news_td" style="padding-left:10px;">&nbsp;</td>
<td class="news_td cen" style="font-weight:bold;"><?echo $ot?></td>
<td class="news_td rgt" style="font-weight:bold;"><?=number_format($ord_inf['0']['sumotgr'],2,'.','')?></td>
<td class="news_td"></td></tr>
</table>


<?	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;

}
function show_log($q){
	global $st_log_arrray;
	if (count($q)==0) {
		return 'Заказов нет.<br>';
	}

	ob_start();
?>	
		<table class="main_no_height" id="logTable" <?=((MDEVICE==1)?'width=100%':'')?>>
		<tr>
			<th class="news_header cen">Дата</th>
			<th class="news_header cen">Заказ</th>
			<th class="news_header cen">Строка</th>
			<th class="news_header">Товар</th>
			<th class="news_header rgt">Кол-во</th>
			<th class="news_header rgt">Сумма</th>
			<th class="news_header">Действие</th></tr>
		<?php	foreach($q as $row){
					$cla='';
					if ($row['isins']==0 || $row['isins']>87) $cla='class="rrr"';
					if ($row['isins']==1) $cla='class="higl"';
					?>
			<tr id="ord_row_<?=$row['orderid']?>" <?=$cla?>>
				<td ><?php echo date('j/n G:i', $row['data'])?></td>
				<td class="cen cli" id="vo" title="Просмотр заказа"><?=$row['orderid']?></td>
				<td class="cen"><?=$row['kodstr']?></td>
				<td ><?=htmlspecialchars($row['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></td>
				<td class="rgt"><?=(($row['cnt']>0)?$row['cnt']:' ')?></td>
				<td class="rgt"><?=number_format(($row['price']-$row['price']*$row['sale']/100)*$row['cnt'],2,'.','')?></td>
				<td class="lft"><?=$st_log_arrray[$row['isins']]?></td></tr>
			<?php
		}	?>
	</table>
	<script type="text/javascript">
		$('#logTable #vo').bind('click',function(){view_order(this);});
	</script>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;

}
function show_users($q){
	if (count($q)==0) {
		return 'Пользователей нет.<br>';
	}
	$pl_arr = array(
	'Логин' => 'login',
	'Пароль' => 'pass'
);
	ob_start();

?>	
	<table class="main_no_height" id="UserTable">
	 <tr>
	  <th class="news_header lft" onclick="gotourl('include.php?place=users&sort=1')">ID</th>
	  <th class="news_header lft">Дата</th>
	  <th class="news_header lft" onclick="gotourl('include.php?place=users&sort=2')">Вход</th>
	  <th class="news_header lft">ФИО</th>

	<?php foreach($pl_arr as $id=>$value){?>
		<th class="news_header lft"><?php echo $id?></th>
	<?php }?>
	  <th class="news_header cen">Заказы</th>
	  <th class="news_header rgt" onclick="gotourl('include.php?place=users&sort=3')">Сумма</th>
	<th class="news_header">Функции</th>
	 </tr>

	<?php
//	$query = "SELECT * FROM ".TABLE_USERS." ORDER BY id DESC";
	foreach($q as  $row){
	$inf = unserialize($row['info']);
	?>
	 <tr id="usr_row_<?=$row['id']?>">
	  <td class="cli lft" onclick="view_user(this);"><?php echo $row['id']?></td>
	  <td class="cen"><?php echo ($row['data']>0 ? date('d-m-y', $row['data']) : '&nbsp;') ?></td>
	  <td class="cen"><?php echo ($row['datep']>0 ? date('d-m-y', $row['datep']) : '&nbsp;') ?></td>
	  <td><?=$inf['9'].' '.$inf['8'].' '.$inf['11']?></td>
<?php
	foreach($pl_arr as $id=>$value)
	{
?>	
	  <td><?php echo ((!empty($row[$value])) ? htmlspecialchars($row[$value],ENT_COMPAT | ENT_XHTML,'utf-8') : '&nbsp;')?></td>
<?php
	}
?>	
		<td class="cen"><?php echo ($row['cn']>0 ? $row['cn']:' ')?></td>
		<td class="rgt"><?php echo ($row['sm']>0 ? number_format($row['sm'],2,'.','') : ' ') ?></td>
		<td class="ncen">
<?		if ($row['cn']>0){ ?>
		  <a  href="include.php?place=ishop&action=order&userid=<?php echo $row['id']?>"><img alt="Заказы" title="Заказы" width="18" height="19" src="<?=TEMPLATE_URL?>images/orders.png" /></a>
<?		 } 
		else
		{ ?>
		<a><img alt="" title="" width="18" height="19" src="<?=TEMPLATE_URL?>images/s.gif" /></a>	
<?		} ?>
		  <div class="vsb<?=(($row['block']==0)?'':' eunv')?>"></div>
		  <div class="img idel"></div>
		  </td>
		 </tr>
<?php	}	?>
	</table>
	<script type="text/javascript">
		$('#UserTable .idel').bind('click',function(){delete_user(this);});
		$('#UserTable .vsb').bind('click',function(){on_off_user(this);});
	</script>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;
}
function show_wish($q){
		if (count($q)==0) {
		return 'Wishlist-ов нет.<br>';
	}
	ob_start();
?>	
	<table class="main_no_height" id="WishTable">
	 <tr>
	  <th class="news_header lft">Обн-н</th>
	  <th class="news_header lft">Создан</th>
	  <th class="news_header lft">Открыт</th>
	  <th class="news_header lft">ID</th>
	  <th class="news_header lft">Клиент</th>
	  <th class="news_header lft">Товаров</th>
	  <th class="news_header cen">Функции</th>
	 </tr>

	<?php
	foreach ($q as $row)	{	
		$inf = unserialize($row['info']);
	  	$fio=$inf['9'].' '.$inf['8'].' '.$inf['11'];

	?>
	 <tr id="c_row_<?=$row['userid']?>">
	  <td class="news_td lft"><?php echo ($row['date']>0 ? date('d-m-y G:i', $row['date']) : '&nbsp;') ?></td>
	  <td class="news_td lft"><?php echo ($row['datec']>0 ? date('d-m-y G:i', $row['datec']) : '&nbsp;') ?></td>
	  <td class="news_td lft"><?php  echo $row['scooc']?></td>
	  <td class="highslide news_td lft cli" onclick="view_wish(this);"><?php  echo $row['userid']?></td>
	  <td class="highslide news_td lft cli" onclick="view_user(this,<?=$row['userid']?>);"><?=$fio?></td>
	  <td class="news_td lft" id="c_row_cnt_<?=$row['id']?>"><?php  echo $row['cnt']?></td>
	  <td class="news_td cen">
		  <a  onclick="Clear_wish('<?echo $row['id']?>')"><img alt="Очистить" title="Очистить wishlist" width="18" height="19" src="<?=TEMPLATE_URL?>images/erase.png" /></a>
	  </td>
</tr>
<?php	}	?>
	</table>


<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;
}
	
function show_cart($q){
		if (count($q)==0) {
		return 'Корзин нет.<br>';
	}
	ob_start();
?>	
	<table class="main_no_height" id="CartTable">
	 <tr>
	  <th class="news_header lft">Обн-на</th>
	  <th class="news_header lft">Создана</th>
	  <th class="news_header lft">#</th>
	  <th class="news_header lft" id="posip">IP</th>
	  <th class="news_header lft">P</th>
	  <th class="news_header lft">C</th>
	  <th class="news_header lft">Клиент</th>
	  <th class="news_header lft">Товаров</th>
	  <th class="news_header cen">Функции</th>
	 </tr>

	<?php
	foreach ($q as $row)	{
	?>
	 <tr id="c_row_<?=$row['id']?>">
	  <td class="news_td lft"><?php echo ($row['date']>0 ? date('d-m-y G:i', $row['date']) : '&nbsp;') ?></td>
	  <td class="news_td lft"><?php echo ($row['datec']>0 ? date('d-m-y G:i', $row['datec']) : '&nbsp;') ?></td>
	  <td  class="highslide news_td lft cli" onclick="view_cart(this);"><?php echo $row['id']?></td>
	  <td class="news_td lft"><?php  echo $row['ip']?></td>
	  <td class="news_td lft"><?php  echo $row['isset']?></td>
	  <td class="news_td lft"><?php  echo $row['scooc']?></td>
	  <?php  echo ($row['userid']>0 ? '<td class="highslide news_td lft cli" onclick="view_user(this);">'.$row['userid'].'</td>' :  '<td class="news_td lft">&nbsp;</td>')?>
	  <td class="news_td lft" id="c_row_cnt_<?=$row['id']?>"><?php  echo $row['cnt']?></td>
	  <td class="news_td cen">
		  <a  onclick="Clear_cart('<?echo $row['id']?>')"><img alt="Очистить" title="Очистить корзину" width="18" height="19" src="<?=TEMPLATE_URL?>images/erase.png" /></a>
<?		if ($row['userid']==0){ ?>
		  <a  onclick="Delete_cart('<?echo $row['id']?>')"><img alt="Удалить" title="Удалить корзину" width="18" height="19" src="<?=TEMPLATE_URL?>images/delete.png" /></a>
<?		 } 
		else
		{ ?>
		<a><img alt="" title="" width="18" height="19" src="<?=TEMPLATE_URL?>images/s.gif" /></a>	
<?		} ?>
	  </td>
</tr>
<?php	}	?>
	</table>


<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;
}


function show_payments($q){
		if (count($q)==0) {
		return 'Платежей нет.<br>';
	}
	ob_start();

?>	
	<table class="main_no_height">
	 <tr>
	  <th class="news_header lft" onclick="gotourl('include.php?place=payments&sort=1<?=$act?>')">Дата</th>
	  <th class="news_header lft" onclick="gotourl('include.php?place=payments&sort=2<?=$act?>')">Заказ</th>
	  <th class="news_header lft">Счет №</th>
	  <th class="news_header rgt">Сумма</th>
	  <th class="news_header rgt">Платеж</th>
	  <th class="news_header cen">Статус</th>
	  <th class="news_header lft">Billnumber</th>
	  <th class="news_header lft">Клиент</th>
	  <th class="news_header lft">Тип</th>
	  <th class="news_header lft">1C</th>
	  <th class="news_header"></th>
	 </tr>

	<?php
	foreach ($q as $row)	{//return hs.htmlExpand(this, { 
		//	objectType: 'ajax', width: '650',src: 'modules/cart/ajax.php?act=viewcart&id=<? echo $row['id']', 
		//	headingEval: 'this.a.innerHTML', wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null} )"
		$st='';
		$cl='';
		if ($row['approved']==1) {$st='Завершен';$cl='class="higl"';}
		if ($row['uchet']==1) {$st='в учете';$cl='class="hig"';}
		if ($row['declined']==1) {$st='Отклонен';$cl='class="rrr"';}
	?>
	 <tr id="<?=$row['ordernumber']?>" <?=$cl?>>
	  <td class="news_td lft"><?php echo ($row['date']>0 ? date('d-m-y G:i', $row['date']) : '&nbsp;') ?></td>
	  <td class="highslide news_td lft cli" onclick="view_order(this);"><?php echo ($row['orderid']) ?></td>
	  <td  class="news_td lft"><?php echo $row['ordernumber']?></td>
	  <td class="news_td lft rgt"><?php  echo $row['orderamount']?></td>
	  <td class="news_td lft rgt"><?php  echo $row['shopsumamount']?></td>
	  <td class="news_td cen"><?php  echo $st?></td>
	  <td class="news_td lft"><?php  echo $row['billnumber']?></td>
	  <td class="news_td lft"><?php  echo $row['fio']?></td>
	  <td class="news_td lft"><?php  echo $row['meantypename']?></td>
	  <td class="news_td lft"><?php  echo ($row['uchet']==1) ? 'Да' :'&nbsp;' ?></td>
	  <td class="news_td lft">
	  <?		if ($row['declined']==1 || $row['approved']!=1){ ?>
		  <a  onclick="delete_pay('<?echo $row['ordernumber']?>')"><img alt="Удалить" title="Удалить запись" width="18" height="19" src="<?=TEMPLATE_URL?>images/delete.png" /></a>
<?		 } ?></td>

</tr>
<?php	}	?>
	</table>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;
}
function show_news($q){
		if (count($q)==0) {
		return 'Новостей нет.<br>';
	}
	ob_start();
?>	
 <table class="main_no_height">
  <tr>
   <th style="width:25px;" class="news_header">
    <input style="height: 12px;" type="checkbox" class="check" id="markp">
   </th>
  <th  style="width:75px;" class="news_header">Дата</th>
  <th class="news_header">Заголовок</th>
  <th style="width:75px; text-align:Center;" class="news_header center">Ссылка</th>
  <th  class="news_header center">Функции</th>
 </tr>
<?php
$i=0;
$rowscnt = 0;
foreach($q as $row) {
?>
 <tr id="nws_row_<?=$row['id']?>">
  <td class="news_td" style="width:30px;">
   <input style="height: 12px; font-size:10px;" type="checkbox" class="check" name="box[<?php echo $row['id']?>]" id="boxes<?php echo $rowscnt?>">
  </td>
  <td class="news_td"><?php echo htmlspecialchars(date("d-m-Y",$row['cdate']),ENT_COMPAT | ENT_XHTML,'utf-8')?></td>
  <td class="news_td"><a class="news_title" href="<?php echo htmlspecialchars('include.php?place=news&action=sub&eID='.$row['id'],ENT_COMPAT | ENT_XHTML,'utf-8')?>"><?php echo htmlspecialchars(Substr($row['t'],0,100),ENT_COMPAT | ENT_XHTML,'utf-8')?>...</a></td>
  <td style="white-space:nowrap; text-align:center;" class="news_td">news/<?php echo $row['id']?></td>
  <td class="news_td">
   <div class="img iop" onclick="gotourl('<?php echo htmlspecialchars('include.php?place=news&action=sub&eID='.$row['id'],ENT_COMPAT | ENT_XHTML,'utf-8')?>')"></div>
   <div class="img idel" onclick="delete_news(this)"></div>
  </td>
 </tr>
<?php
	$rowscnt++;
}
?>
</table>
<script>
$(document).ready(function () {
    $("input[name^='box'],#markp").iCheck({checkboxClass: 'icheckbox_flat-green',radioClass: 'iradio_flat-green'});	
	$("#markp").on('ifToggled',function(){
		if ($(this).prop('checked')){
			$("input[name^='box']").iCheck('check');
		} else {
			$("input[name^='box']").iCheck('uncheck');
			
		}
		mbtn_state();
	});
	$("input[name^='box']").on('ifToggled',function(){mbtn_state();});
});
</script>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;
}

function show_prdinw($q,$cl="prd_in_cart(this,'%id%')"){
	if (count($q)==0) {
		return 'Товаров нет.<br>';
	}
	ob_start();
?>	

	<table  class="main_no_height">
	 <tr>
	  <th class="news_header lft">Код</th>
	  <th class="news_header lft">Наименование</th>
	  <th class="news_header lft">Кол-во</th>
	  <th class="news_header lft">Функции</th>
	 </tr>

	<?php
	foreach ($q as $row)	{
		$cl=str_ireplace('%id%',$row['id'],$cl);
	?>
	 <tr  <? echo(($row['enabled'] && $row['visible']) ? '' : 'style="background:#FFFF00"')?>>
	  <td class="news_td lft"><?php echo $row['param_kodtovara']?></td>
	  <td class="news_td lft"><?php echo $row['title']?></td>
	  <td class="news_td lft"><?php echo $row['cnt']?></td>
	  <td class="news_td lft"><a href="" onclick="prd_in_cart(this,'<?=$row['id']?>');return false;">В корзинах</a>&nbsp;<a href="" onclick="prd_in_wish(this,'<?=$row['id']?>');return false;">В wishlist</a></td>

	 </tr>
<?php	}	?>
	</table>
<?	
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;

}




function show_rest($q){
	if (count($q)==0) {
		return 'Файлов для восстановления нет.<br>';
	}
	ob_start();
?>	
		<table class="main_no_height" id="RestTable" <?=((MDEVICE==1)?'width=100%':'')?>>
		<tr>
			<th class="news_header cen">Дата</th>
			<th class="news_header">Имя</th>
			<th class="news_header cen">Функции</th></tr>
		<?php	foreach($q as $fil=>$row){?>
			<tr id="rst_row_<?=str_replace('.','___',str_replace('.sql.gz','',$fil))?>">
				<td><?php echo date('G:i d/m/Y', $row)?></td>
				<td><?=(!empty($fil)) ? htmlspecialchars($fil,ENT_COMPAT | ENT_XHTML,'utf-8') : '&nbsp;'?></td>
				<td class="cen">
					<div class="img irest" title="Восстановить"></div><div class="img idel" title="Удалить файл"></div></td></tr>
			<?php
		}	?>
	</table>
	<script type="text/javascript">
		$('#RestTable .irest').bind('click',function(){restore_file(this);});
		$('#RestTable .idel').bind('click',function(){delete_restfile(this);});
	</script>
<?
	$ret=ob_get_contents();
	ob_end_clean();
	return $ret;

}

?>