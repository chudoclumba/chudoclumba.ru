<?php
	$module['path'] = 'Баннеры';
	$module['scripts'] = MODULES_PATH.$global_place.'/banners.js';
	if(!empty($_GET['sort'])) $_SESSION['bsort']=$_GET['sort'];
	$btns = array('Баннеры'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=banners\''));
	$schstr="";
	$sstr='';
	$sstr1='';
	if (!empty($_POST['search_string'])){
		$schstr=$_POST['search_string'];
	}	else	{
		if (!empty($_GET['sch']) && $_GET['sch']>" ") $schstr=$_GET['sch'];
	}
	if ($schstr>" ") {
		$sstr=" where page LIKE '%".str_replace ("'","_",$schstr)."%' or vlink LIKE '%".str_replace ("'","_",$schstr)."%'";
		$sstr1=" where vlink LIKE '%".str_replace ("'","_",$schstr)."%'";
	}
	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$pages = 100;
	$limit = "";
	$sort=' order by page,place ';
	if (isset($_SESSION['bsort']) && $_SESSION['bsort']==1) $sort=' order by id ';
	if (isset($_SESSION['bsort']) && $_SESSION['bsort']==2) $sort=' order by page,place ';
	if($page != 0) $limit =  " LIMIT ".(($page-1)*$pages).",".$pages;
	$query ='SELECT * FROM '.TABLE_BANNERS.' u '.$sstr.$sort.$limit;
	$query1 ='SELECT * FROM '.TABLE_SLIDERS.' u '.$sstr1.' order by ord';
	$query2 ='SELECT * FROM '.TABLE_SLBANNERS.' u '.$sstr1.$sort;
	$pdtlist2 = $db->query("SELECT * FROM ".TABLE_BANNERS.' u'.$sstr);
	$cnt_p = ceil($db->num_rows($pdtlist2)/$pages);
	$db->free_result($pdtlist2);
	if ($schstr>" ") $ee='&sch='.$schstr; else $ee='';
	ob_start();
?>

	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>
	<div class="block">
	<p class="h2">Верхний слайдер на главной странице</p>
	<table class="main_no_height mb25"  onclick="mtban(arguments[0])">
	 <tr>
	  <th class="lft"></th>
	  <th class="lft">Порядок</th>
	  <th class="lft"></th>
	  <th class="lft">Строка1</th>
	  <th class="lft">Строка2</th>
	  <th class="lft">Ссылка</th>
	  <th class="lft">Фото</th>
  	<th >Функции</th>
	 </tr>
<?
	$q = $db->get_rows($query1);
	foreach ($q as $row ) {
	?>
	 <tr id="sld_row_<?=$row['id']?>">
	 
	  <td class="parts_vi"><div class="enb<?=(($row['enabled']==1)?'':' edis')?>"></div></td>
	  <td class="lft"><?php echo $row['ord']?></td>
<td class="lft cli"><a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=770&y=437">
				<img src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=77&y=44" align="middle"/></a></td>	 
	  <td class="lft"><?=$row['str1']?></td>
	  <td class="lft"><?=$row['str2']?></td>
	  <td class="lft"><?=$row['vlink']?></td>
	  <td class="lft"><?=$row['foto']?></td>
		<td  style="text-align:center;">
			 <div class="img iop edit"></div><div class="img idel"></div>
			 </td>
		 </tr>
<?	}	?>
	</table>
	<?=button1('Создать',"GetNewSlider(this);",'','add');?>
</div>	
	<div class="block">
	<p class="h2">Баннеры справа от слайдера</p>
	<table class="main_no_height mb25"  onclick="mtban(arguments[0])">
	 <tr>
	  <th class="lft"></th>
	  <th class="lft"  onclick="gotourl('include.php?place=banners&sort=1')">#</th>
	  <th class="lft"></th>
	  <th class="lft">Ширина</th>
	  <th class="lft">Высота</th>
	  <th class="lft" onclick="gotourl('include.php?place=banners&sort=2')">Страница</th>
	  <th class="lft">Позиция</th>
	  <th class="lft">Ссылка</th>
	  <th class="lft">Фото</th>
  	<th >Функции</th>
	 </tr>

	<?php
	$q = $db->get_rows($query2);
	foreach ($q as $row ) {
	?>
	 <tr id="slb_row_<?=$row['id']?>">
	 
	  <td class="parts_vi"><div class="enb<?=(($row['enabled']==1)?'':' edis')?>"></div></td>
	  <td class="lft cli"><div class="edit"><?php echo $row['id']?></div></td>
<td class="lft cli"><a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&amp;x=<?=$row['width']?>&amp;y=<?=$row['height']?>&amp;crop">
				<img src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&amp;x=<?=number_format($row['width']*40/$row['height'])?>&amp;y=40&amp;crop" align="middle"/></a></td>	 
	  <td class="lft"><?=$row['width']?></td>
	  <td class="lft"><?=$row['height']?></td>
	  <td class="lft"><?=($row['page']>"") ? $row['page'] : '&nbsp;'?></td>
	  <td class="lft"><?=$row['place']?></td>
	  <td class="lft"><?=$row['vlink']?></td>
	  <td class="lft"><?=$row['foto']?></td>
		<td  style="text-align:center;">
			 <div class="img iop edit"></div><div class="img idel"></div>
			 </td>
		 </tr>
<?	}	?>
	</table>
	<?
	echo button1('Создать',"GetNewSLBanner(this);",'','add');
	?></div>
	<div class="block">
	<p class="h2">Баннеры на страницах</p>
<? 	echo get_pages(
		array ('class' => 'prd_pages_bottom',
			'count_pages' => $cnt_p,
			'curr_page'=> $page,
			'link' => 'include.php?place='.$global_place.$ee.'&page=')).'<div class="clear"></div>';
?>
	<table class="main_no_height mb25"  onclick="mtban(arguments[0])">
	 <tr>
	  <th class="lft"></th>
	  <th class="lft"  onclick="gotourl('include.php?place=banners&sort=1')">#</th>
	  <th class="lft"></th>
	  <th class="lft">Ширина</th>
	  <th class="lft">Высота</th>
	  <th class="lft" onclick="gotourl('include.php?place=banners&sort=2')">Страница</th>
	  <th class="lft">Позиция</th>
	  <th class="lft">Ссылка</th>
	  <th class="lft">Фото</th>
  	<th >Функции</th>
	 </tr>

	<?php
	$q = $db->get_rows($query);
	foreach ($q as $row ) {
	?>
	 <tr id="ban_row_<?=$row['id']?>">
	 
	  <td class="parts_vi"><div class="enb<?=(($row['enabled']==1)?'':' edis')?>"></div></td>
	  <td class="lft cli"><div class="edit"><?php echo $row['id']?></div></td>
<td class="lft cli"><a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&amp;x=<?=$row['width']?>&amp;y=<?=$row['height']?>&amp;crop">
				<img src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&amp;x=<?=number_format($row['width']*40/$row['height'])?>&amp;y=40&amp;crop" align="middle"/></a></td>	 
	  <td class="lft"><?=$row['width']?></td>
	  <td class="lft"><?=$row['height']?></td>
	  <td class="lft"><?=($row['page']>"") ? $row['page'] : '&nbsp;'?></td>
	  <td class="lft"><?=$row['place']?></td>
	  <td class="lft"><?=$row['vlink']?></td>
	  <td class="lft"><?=$row['foto']?></td>
		<td  style="text-align:center;">
			 <div class="img iop edit"></div><div class="img idel"></div>
			 </td>
		 </tr>
<?	}	?>
	</table>
	<?
	echo button1('Создать',"GetNewBanner(this);",'','add');
	echo get_pages(
		array ('class' => 'prd_pages_bottom',
			'count_pages' => $cnt_p,
			'curr_page'=> $page,
			'link' => 'include.php?place='.$global_place.$ee.'&page='));
	?></div>
	<?			
	$module['html'] .= ob_get_contents();
	ob_end_clean();
