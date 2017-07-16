<?
ob_start();
//print_r($_GET);
if(!empty($_GET['sort']))
{
	$_SESSION['bsort']=$_GET['sort'];
}


if (!empty($_GET['dID'])){
 $q = $db->delete(TABLE_SUSUS,['id'=>$_GET['dID']]);
}

?>

<?php
if('Отправить сообщение' == 'on'){
?>
	<div style="padding:0px 0px 0px 10px"><?php echo button('Отправить сообщение', "javascript:gotourl('include.php?place=users&type=send')")?></div>
<?php
}	
?>	
<?

if(empty($_GET['vID']) && empty($_GET['new'])) 
{
	$schstr="";
	$sstr='';
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
	if ($schstr>" ")
	{
		$sstr=" where page LIKE '%".str_replace ("'","_",$schstr)."%'";
	}

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$pages = 100;
	$limit = "";
	$sort=' order by page,place ';
//	print_r($_SESSION);
	if (isset($_SESSION['bsort']) && $_SESSION['bsort']==1) $sort=' order by id ';
	if (isset($_SESSION['bsort']) && $_SESSION['bsort']==2) $sort=' order by page,place ';
	if($page != 0) $limit =  " LIMIT ".(($page-1)*$pages).",".$pages;
	$query ='SELECT * FROM '.TABLE_BANNERS.' u '.$sstr.$sort.$limit;
	$pdtlist2 = $db->query("SELECT * FROM ".TABLE_BANNERS.' u'.$sstr);
	$cnt_p = ceil($db->num_rows($pdtlist2)/$pages);
	$db->free_result($pdtlist2);
	if ($schstr>" ")
	{ $ee='&sch='.$schstr;}
	else
	{ $ee='';}
?>

	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place.'&action=order'.((!empty($_GET['userid'])) ? '&userid='.$_GET['userid'] : '')?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>
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
	  <th class="lft" onclick="gotourl('include.php?place=banners&sort=2')">Страница</th>
	  <th class="lft">Позиция</th>
	  <th class="lft">Фото</th>
  	<th >Функции</th>
	 </tr>

	<?php
//	$query = "SELECT * FROM ".TABLE_USERS." ORDER BY id DESC";
	$q = $db->get_rows($query);
	foreach ($q as $row ) {
	?>
	 <tr id="ban_row_<?=$row['id']?>">
	 
	  <td class="parts_vi"><div class="enb<?=(($row['enabled']==1)?'':' edis')?>"></div></td>
	  <td class="lft cli"><div class="edit"><?php echo $row['id']?></div></td>
<td class="lft cli"><a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&amp;x=407&amp;y=176&amp;crop">
				<img src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&amp;x=81&amp;y=35&amp;crop" align="middle"/></a></td>	 
	  <td class="lft"><?=($row['page']>"") ? $row['page'] : '&nbsp;'?></td>
	  <td class="lft"><?=$row['place']?></td>
	  <td class="lft"><?=$row['foto']?></td>
		<td  style="text-align:center;">
			 <div class="img iop edit"></div><div class="img idel"></div>
			 </td>
		 </tr>
<?php	}	?>
	</table>
	<?php echo button1('Создать',"GetNewBanner(this);",'','add')?>
<?PHP	echo get_pages(
		array ('class' => 'prd_pages_bottom',
			'count_pages' => $cnt_p,
			'curr_page'=> $page,
			'link' => 'include.php?place='.$global_place.$ee.'&page='));

	
	} elseif (!empty($_GET['vID']))
	{
	
	$inf = $db->get(TABLE_SUSUS, $_GET['vID']);
	?> <div align="left"><form id="fuser" action="" enctype="multipart/form-data">

	<table cellspacing="0" cellpadding="0" width="100%">
	 <tr>
	  <td  style="padding-right:20px;">Дата регистрации</td>
	  <td ><?echo ($inf['dt']>0 ? date('d-m-y, G:i', $inf['dt']) : '&nbsp;')?></td>
	  <td  style="padding-right:20px;"></td>
	  <td ></td>
	 </tr>
	 <tr>
	  <td  colspan="4">Имя <input id="fum" class="fbinp" readonly="readonly" style="width:150px" type="text" name="k_name" value="<?php  echo $inf['name']?>"/>
	  Права <input class="fbinp" readonly="readonly" type="text" style="width:150px" name="k_role" value="<?php  echo $inf['role']?>"/></td><input type="hidden" name="k_id" value="<?=$_GET['vID']?>"/>
	 </tr>
	  <tr>
	  <td  style="padding-right:20px;">Хеш пароля</td>
	  <td ><?php  echo $inf['pass']?></td>
	  <td  style="padding-right:20px;"></td>
	  <td ></td>
	 </tr>
	 <tr>
	  <td  style="padding-right:20px;"></td>
	  <td  colspan="2"></td>
	  <td ><?php echo button1('Редактировать',"SaveUsersus(this);",'','edit')?></td>
	 </tr>

	</table>
	</form>
	</div>
	<?}
	elseif (!empty($_GET['new']))
	{?>
<div align="left"><form id="fban" action="" enctype="multipart/form-data">
<textarea style="display:none" class="mceEditor" style="width: 70%" id="editor1"></textarea>
	 <label>Страница <input class="fbinp" style="width:450px" type="text" name="page" value=""/></label><br/><br/>
	 <label>Позиция <input class="fbinp" style="width:50px" type="text" name="place" value=""/></label><br/><br/>
	 <label>Фото <input type="text" onchange="document.getElementById('foto_main').src = '<?=SITE_URL?>thumb.php?id=' + document.getElementById('url_abs_nohost').value + '&x=409&y=176&crop'" name="foto" value="" id="url_abs_nohost" style="width:400px" /></label><a onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?=SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Выбрать файл</a><br/><br/>
	 <img style="margin:0px 20px 5px 5px;width: 409px;height: 176px" id="foto_main" alt="" src="s.gif" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost').val() } )"/><br/><br/>
	 
	<?=button1('Cохранить',"SaveNewBanner(this);",'','save')?>
	<br/>
	</form>
	</div>		
<?	}

$module['html'] .= ob_get_contents();
ob_end_clean();
if (!empty($_GET['ajax']))
{
	echo $module['html'];
	exit;
}