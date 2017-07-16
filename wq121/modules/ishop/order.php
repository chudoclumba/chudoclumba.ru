<?php
$btns = array(
	'Заказы'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=ishop&action=order\''),
	'Лог'=>array('href'=>'javascript:location.href=\'include.php?place=ishop&action=log\'')
);

ob_start();
if(!empty($_GET['id'])){
	$ord_inf = $db->get_rows("SELECT * FROM ".TABLE_ORDERS." WHERE id=".quote_smart($_GET['id'])."");
	?>
	<div class="clear"></div>
	<table width="50%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
		<tr>
			<td class="news_td" style="padding-left:10px;"><?echo date('G:i   d-m-Y',$ord_inf['0']['data'])?></td>
			<td class="news_td" style="padding-left:10px;"></td>
		</tr>
		<?php
		if($ord_inf['0']['user_id']>0){
			$orders1 = $db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$ord_inf['0']['user_id']."' && status != 6 && id <".quote_smart($_GET['id']));
			$skidka=0;
			if(!empty($orders1['0']['summa']) && $orders1['0']['summa']>0 ){
				$orders = $db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
				$skidka = $orders['0']['percent'];
			}

			?>
			<tr>
				<td class="news_td" style="padding-left:10px;">Сумма предыдущих заказов</td>
				<td class="news_td" style="padding-left:10px;"><?=number_format ($orders1['0']['summa'], 2, '.', '')?></td>
			</tr>
			<tr>
				<td class="news_td" style="padding-left:10px;">Рекомендуемая скидка</td>
				<td class="news_td" style="padding-left:10px;"><?=htmlspecialchars($skidka,ENT_COMPAT | ENT_XHTML,'utf-8')?></td>
			</tr>
			<?php
		}    ?>

		<tr>
			<td class="news_td" style="padding-left:10px;">Фамилия Имя Отчество</td>
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

	<table width="98%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
		<tr>
			<th class="news_header lft">№</th>
			<th class="news_header lft">ID</th>
			<th class="news_header lft">Код товара</th>
			<th class="news_header lft">Наименование</th>
			<th class="news_header cen">Кол-во</th>
			<th class="news_header rgt">Цена</th>
			<th class="news_header cen">Отмены</th>
			<th class="news_header lft">Причина</th>
			<th class="news_header cen">Отгр.</th>
			<th class="news_header rgt">Цена</th>
		</tr>

		<?php
		$prd_or = $db->get_rows("SELECT * FROM ".TABLE_ORDERS_PRD." WHERE order_id = ".$_GET['id']." order by kodstr");
		$cn = 0;
		$pr = 0;
		$ot = 0;
		foreach($prd_or as $id=>$row){
			$cn += $row['count'];
			$pr += $row['count'] * $row['summa'];
			$ot += $row['otgr'];

			?>
			<tr>
				<td class="news_td" style="padding-left:10px;"><?php echo $row['kodstr']?></td>
				<td class="news_td"><?php echo $row['prd_id']?></td>
				<td class="news_td"><?php echo $row['kodtov']?></td>
				<td class="news_td"><?php echo $row['name']?></td>
				<td class="news_td cen"><?php echo $row['count']?></td>
				<td class="news_td rgt"><?php echo $row['summa']?></td>
				<td class="news_td cen"><?php echo $row['isdel']?></td>
				<td class="news_td"><?php echo($row['prdel']>' ' ? $row['prdel'] : '&nbsp;') ?></td>
				<td class="news_td cen"><?php echo $row['otgr']?></td>
				<td class="news_td rgt"><?php echo $row['sumotgr']?></td>

			</tr>
			<?php
		}	?>
		<tr>
			<td class="news_td" style="padding-left:10px;"></td>
			<td class="news_td" style="padding-left:10px;"></td>
			<td class="news_td" style="padding-left:10px;"></td>
			<td class="news_td" style="padding-left:10px;font-weight:bold;">Итого:</td>
			<td class="news_td cen" style="font-weight:bold;"><?=$cn?></td>
			<td class="news_td rgt" style="font-weight:bold;"><?=number_format($pr,2,'.','')?></td>
			<td class="news_td" style="padding-left:10px;">&nbsp;</td>
			<td class="news_td" style="padding-left:10px;">&nbsp;</td>
			<td class="news_td cen" style="font-weight:bold;"><?echo $ot?></td>
			<td class="news_td rgt" style="font-weight:bold;"><?=number_format($ord_inf['0']['sumotgr'],2,'.','')?></td>
		</tr>
	</table>
	<?php
	if($ord_inf['0']['dopinf']>' '){
		?>


		<table width="60%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
			<tr>
				<th class="news_header lft">Доп. Информация из 1С</th></tr>
			<tr>
				<td class="news_td"><?=$ord_inf['0']['dopinf']?></td></tr>
		</table><?
	} ?>



	<div style="padding:10px 0px 10px 20px">
		<a href="javascript:history.go(-1)">Вернуться назад</a></div>
	<script>
		function save_order1(order_id, value, tip)
		{
			$.ajax({
					url: 'modules/ishop/ajax.php?act=save_order1' + '&order_id=' + order_id + '&value=' + value + '&tip=' + tip,
					cache: false,
					success: function(html){
					}
				});
		}

	</script>

	<?
}
else{

	$schstr="";
	$sstr='';
	if(!empty($_POST['search_string'])){
		$schstr=$_POST['search_string'];
	}
	else{
		if(!empty($_GET['sch']) && $_GET['sch']>" "){
			$schstr=$_GET['sch'];
		}
	}
	if ($schstr>" " and ($schstr{0}<'A' or $schstr{0}=="№"))
	{
		$sstr=" where `id` = '".$schstr."' or user_id=".$schstr ;
	}
	elseif($schstr>" "){
		$sstr=" where `fio` LIKE '%".str_replace ("'","_",$schstr)."%'";
	}
	if(!empty($_GET['userid'])){
		if($sstr>" "){
			$sstr.=' and user_id='.$_GET['userid'];
		}
		else{
			$sstr=' where user_id='.$_GET['userid'];
		}
	}


	?>

		<div class="search_pan">
			<form method="post" action="<?php echo 'include.php?place='.$global_place.'&action=order'.((!empty($_GET['userid'])) ? '&userid='.$_GET['userid'] : '')?>">
				Поиск:
				<span style="color:red"><?php
					if( !empty($schstr)){
						echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8');
					} ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
			</form>
		</div>

	<?php
	$pdtlist2 = $db->get_rows("SELECT count(id) as cnt FROM ".TABLE_ORDERS.$sstr);
	$pg_count = $pdtlist2[0]['cnt'];
	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';


	$query = "SELECT * FROM ".TABLE_ORDERS." ".$sstr." ORDER BY datan DESC ".$limit;
	$q = $db->get_rows($query);
	if($schstr>" "){
		$ee='&sch='.$schstr;
	}
	else{
		$ee='';
	}
	$plist=get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.'&action=order'.$ee.'&page=','info'=>$mes)).'<div class="clear"></div>';
	echo (empty($plist))?'<div style="height:20px"></div>':$plist;
	echo show_orders($q);
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.'&action=order'.$ee.'&page=','info'=>$mes));
}

$module['html'] .= ob_get_contents();
ob_end_clean();