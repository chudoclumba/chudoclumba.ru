<?php
	$btns = array(
		'Каталог'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=ishop\''),
		'Дополнительно'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=ishop&action=settings\''),
		'Статистика по заказам'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=ishop&action=order\'')
	);

	ob_start();

	if(!empty($_GET['id']))
	{
		if (!empty($_GET['dID']))
		{
			$query ="DELETE FROM ".TABLE_ORDERS_PRD." WHERE order_id=".$_GET['id']." && prd_id = ".$_GET['dID'];
			$q = $db->query($query);
			$prd_or = $db->get_rows("SELECT * FROM ".TABLE_ORDERS_PRD." WHERE order_id = ".$_GET['id']);
			$cn = 0;
			$pr = 0;
			foreach($prd_or as $id=>$row)
			{
				$cn += $row['count'];
				$pr += $row['count'] * $row['summa'];
			}
			$db->query("UPDATE ".TABLE_ORDERS." SET summa = '".$pr."' where `id` = '".$_GET['id']."'");


		}
	$ord_inf = $db->get_rows("SELECT * FROM ".TABLE_ORDERS." WHERE id=".quote_smart($_GET['id'])."");
	?>
	<div class="clear"></div>
	<table width="70%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
<?php	if ($ord_inf['0']['user_id']>0)
	{
		$orders1 = $db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$ord_inf['0']['user_id']."' && status != 6 && id <".quote_smart($_GET['id']));
		$skidka=0;
		if(!empty($orders1['0']['summa']) && $orders1['0']['summa']>0 )
		{
			$orders = $db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
			$skidka = $orders['0']['percent'];
		}

?>
		<tr>
	  	<td class="news_td" style="padding-left:10px;">Сумма предыдущих заказов</td>
	  	<td class="news_td" style="padding-left:10px;"><?=number_format ($orders1['0']['summa'], 2, '.', '')?> </td>
		</tr>
		<tr>
	  	<td class="news_td" style="padding-left:10px;">Рекомендуемая скидка</td>
	  	<td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($skidka,ENT_COMPAT | ENT_XHTML,'utf-8')?> </td>
		</tr>
<?php	}    ?>

	 <tr>
	  <td class="news_td" style="padding-left:10px;">Фамилия Имя Отчество </td>
	  <td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($ord_inf['0']['fio'],ENT_COMPAT | ENT_XHTML,'utf-8')?>&nbsp;</td>
		 </tr>
	 <tr>
	  <td class="news_td" style="padding-left:10px;">Телефон</td>
	  <td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($ord_inf['0']['tel'],ENT_COMPAT | ENT_XHTML,'utf-8')?>&nbsp;</td>
	 </tr>
	<tr>
	  <td class="news_td" style="padding-left:10px;">E-mail</td>
	  <td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($ord_inf['0']['email'],ENT_COMPAT | ENT_XHTML,'utf-8')?>&nbsp;</td>
	 </tr>
	<tr>
	  <td class="news_td" style="padding-left:10px;">Адрес</td>
	  <td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($ord_inf['0']['adr'],ENT_COMPAT | ENT_XHTML,'utf-8')?>&nbsp;</td>
	 </tr>
	<tr>
	  <td class="news_td" style="padding-left:10px;">Способ доставки</td>
	  <td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($ord_inf['0']['dost'],ENT_COMPAT | ENT_XHTML,'utf-8')?>&nbsp;</td>
	 </tr>
	<tr>
	  <td class="news_td" style="padding-left:10px;">Способ оплаты</td>
	  <td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($ord_inf['0']['opl'],ENT_COMPAT | ENT_XHTML,'utf-8')?>&nbsp;</td>
	 </tr>
	<tr>
	  <td class="news_td" style="padding-left:10px;">Доп информация</td>
	  <td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($ord_inf['0']['comment'],ENT_COMPAT | ENT_XHTML,'utf-8')?>&nbsp;</td>
	 </tr>
	<tr>
	  <td class="news_td" style="padding-left:10px;">Скидка</td>
	  <td class="news_td" style="padding-left:10px;"><input type="text" value="<?php echo $ord_inf['0']['skidka'] ?>" onchange="save_order1(<?=quote_smart($_GET['id'])?>, this.value,1)"></td>
	 </tr>
	<tr>
	  <td class="news_td" style="padding-left:10px;">UserId</td>
	  <td class="news_td" style="padding-left:10px;"><input type="text" value="<?php echo $ord_inf['0']['user_id'] ?>" onchange="save_order1(<?=quote_smart($_GET['id'])?>, this.value,2)"></td>
	 </tr>

	</table>

	<table width="70%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
	 <tr>
		<th class="news_header" style="padding-left:10px;">Артикул</th>
		<th class="news_header" style="padding-left:10px;">Наименование</th>
		<th class="news_header" style="padding-left:10px;">Количество</th>
		<th class="news_header" style="padding-left:10px;">Цена</th>
		<th class="news_header" style="padding-left:10px;">Функции</th>
	 </tr>

	<?php
	$cnt1=0;
	$prd1_or = $db->get_rows("SELECT ".TABLE_ORDERS_PRD.".* FROM ".TABLE_ORDERS_PRD." WHERE order_id = ".quote_smart($_GET['id']));
	foreach($prd1_or as $id=>$row)
	{
	  $cnt1=$cnt1+1;
	}
	$cnt=0;


	$prd_or = $db->get_rows("SELECT ".TABLE_ORDERS_PRD.".*, ".TABLE_PRODUCTS.".title, ".TABLE_PRODUCTS.".param_kodtovara FROM ".TABLE_ORDERS_PRD.",".TABLE_PRODUCTS." WHERE order_id = ".quote_smart($_GET['id'])." && ".TABLE_PRODUCTS.".id = ".TABLE_ORDERS_PRD.".prd_id");
	$cnt=0;
	foreach($prd_or as $id=>$row)
	{
	  $cnt=$cnt+1;
	}
	$fl=false;
	if (!($cnt==$cnt1))
	{
		$fl=true;
		$prd_or=$prd1_or;
	}
	$cn = 0;
	$pr = 0;

	foreach($prd_or as $id=>$row)
	{
		$cn += $row['count'];
		$pr += $row['count'] * $row['summa'];

	?>
	 <tr>
	  <td class="news_td" style="padding-left:10px;"><?php echo $row['param_kodtovara']?></td>
	  <td class="news_td" style="padding-left:10px;"><?php echo (($fl===true) ? $row['name'] : $row['title'])?></td>
	  <td class="news_td" style="padding-left:10px;"><?php echo $row['count']?></td>
	  <td class="news_td" style="padding-left:10px;"><?php echo $row['summa']?></td>
	  <td class="news_td" style="text-align:center;">
		   <a href="include.php?place=<?php echo $global_place.'&action=order&id='.$row['order_id'].'&dID='.$row['prd_id']?>"><?php echo del_img()?></a>
	  </td>

	 </tr>
<?php	}	?>
	 <tr>
		<td class="news_td" style="padding-left:10px;"><?php echo $row['skidka']?></td>
		<td class="news_td" style="padding-left:10px;font-weight:bold;">Итого:</td>
		<td class="news_td" style="padding-left:10px;"><?=$cn?></td>
		<td class="news_td" style="padding-left:10px;"><?=$pr?></td>
	 </tr>
	</table>
		<div style="padding:10px 0px 10px 20px"><a href="javascript:history.go(-1)">Вернуться назад</a></div>
		<div style="padding:10px 0px 10px 20px"> <?php echo button('Отправить на '.$sets['exch_mail'], "javascript:zak_mail('".$_GET['id']."')") ?></div>
	<script>
	function save_order1(order_id, value, tip)
	{
		$.ajax({url: 'modules/ishop/ajax.php?act=save_order1' + '&order_id=' + order_id + '&value=' + value + '&tip=' + tip,
			cache: false,
			success: function(html){}});
	}

	</script>

<?
	}
	else
	{

		if (!empty($_GET['dID']))
		{
			$query = "DELETE FROM ".TABLE_ORDERS." WHERE id = '".$_GET['dID']."'";
			$query2 = "DELETE FROM ".TABLE_ORDERS_PRD." WHERE order_id = '".$_GET['dID']."'";
			$q = $db->query($query);
			$q2 = $db->query($query2);
		}
	$schstr="";
	if (!empty($_POST['search_string']))
	{
		$schstr=$_POST['search_string'];
	}
	else
	{
		if (!empty($_GET['sch']) && $_GET['sch']>" ")
		{
			$schstr=$_GET['sch'];
		}
	}
	if ($schstr>" " and $schstr{0}<'A' or $schstr{0}=="№")
	{
		if ($schstr{0}=="№" or $schstr{0}=="#")
		{
			$schstr=substr($schstr,1);
			$sstr=" where `id` = '".$schstr."'";
		}
		else
		{
			$sstr=" where `user_id` = '".$schstr."'";
		}
	}
	elseif ($schstr>" ")
	{
		$sstr=" where `fio` LIKE '%".str_replace ("'","_",$schstr)."%'";
	}

?>

	<div class="clear">
	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place.'&action=order'?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>
	</div>

	<?php
	$page=$_GET['page'];
	$page = ($page > -1) ? $page : 1;
	$pages = 100;
	$limit = "";
	if($page != 0) $limit =  " LIMIT ".(($page-1)*$pages).",".$pages;
	$query = "SELECT * FROM ".TABLE_ORDERS." ".$sstr." ORDER BY id DESC ".$limit;
	$q = $db->query($query);
	$pdtlist2 = $db->query("SELECT * FROM ".TABLE_ORDERS.$sstr);
	$cnt_p = ceil($db->num_rows($pdtlist2)/$pages);
	if ($schstr>" ")
	{ $ee='&sch='.$schstr;}
	else
	{ $ee='';}
	echo get_pages(
		array ('class' => 'prd_pages_bottom',
			'count_pages' => $cnt_p,
			'curr_page'=> $page,
			'link' => 'include.php?place='.$global_place.'&action=order'.$ee.'&page='));
?>
	<table width="90%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
	 <tr>
		<th class="news_header" style="width:60px;padding-left:5px">Дата</th>
		<th class="news_header">№</th>
		<th class="news_header"  align="right" style="width:70px;">Сумма</th>
		<th class="news_header" style="padding-left:10px;">Скидка</th>
		<th class="news_header">ID</th>
		<th class="news_header">ФИО</th>
		<th class="news_header">Статус</th>
		<th class="news_header">Функции</th>
	 </tr>

<?php	while ($row = $db->fetch_array($q))	{ ?>
	 <tr>
	   <td class="news_td" style="width:60px;padding-left:5px"><?php echo date('d-m-y', $row['data'])?></td>
	   <td class="news_td"><?=$row['id']?></td>
	   <td class="news_td" align="right"><?=$row['summa']?></td>
	   <td class="news_td" align="center"><?=(!empty($row['skidka'])) ? $row['skidka'] : '&nbsp;'?></td>
	   <td class="news_td"><?=$row['user_id']?></td>
	   <td class="news_td"><?=(!empty($row['fio'])) ? htmlspecialchars($row['fio'],ENT_COMPAT | ENT_XHTML,'utf-8') : '&nbsp;'?></td>
	  <td class="news_td">
	  <?
		$st_arrray = array(
			1 => 'Принят',
			2 => 'Обработан',
			3 => 'Оплачен',
			4 => 'Отправлен',
			5 => 'Выполнен',
			6 => 'Аннулирован'

		);
	  ?>
	  <select onchange="save_order(<?=$row['id']?>, this.value)">
	  <?foreach($st_arrray as $id=>$value) {?>
	   <option value="<?=$id?>" <?if($id == $row['status']) {?>selected="selected"<?}?>>
	     <?php echo $value?>
	   </option>
	   <? } ?>
	  </select>
	</td>
		  <td class="news_td">
		  <a href="include.php?place=<?php echo $global_place.'&action=order&id='.$row['id']?>">Просмотреть</a>
		  <a href="include.php?place=<?php echo $global_place.'&action=order&dID='.$row['id']?>">Удалить</a></td>
		 </tr>
<?php	}	?>
	</table>
<?PHP	echo get_pages(
		array ('class' => 'prd_pages_bottom',
			'count_pages' => $cnt_p,
			'curr_page'=> $page,
			'link' => 'include.php?place='.$global_place.'&action=order'.$ee.'&page='));
?>
	<script>
	function save_order(order_id, value)
	{
		$.ajax({
			url: 'modules/ishop/ajax.php?act=save_order' + '&order_id=' + order_id + '&value=' + value,
			cache: false,
			success: function(html){

			}
		});
	}

	</script>
<?php
	}

$module['html'] .= ob_get_contents();
ob_end_clean();