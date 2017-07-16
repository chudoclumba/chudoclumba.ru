<?
ob_start();
//print_r($_GET);
if(!empty($_GET['sort']))
{
	$_SESSION['usort']=$_GET['sort'];
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
		$sstr=" where name LIKE '%".str_replace ("'","_",$schstr)."%'";
	}

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$pages = 100;
	$limit = "";
	$sort=' order by u.id desc ';
//	print_r($_SESSION);
	if (isset($_SESSION['usort']) && $_SESSION['usort']==1) $sort=' order by u.id desc ';
	if (isset($_SESSION['usort']) && $_SESSION['usort']==2) $sort=' order by u.name ';
//	if (isset($_SESSION['usort']) && $_SESSION['usort']==3) $sort=' order by sm desc ';
	if($page != 0) $limit =  " LIMIT ".(($page-1)*$pages).",".$pages;
	$query ='SELECT * FROM '.TABLE_SUSUS.' u '.$sstr.$sort.$limit;
	$pdtlist2 = $db->query("SELECT * FROM ".TABLE_SUSUS.' u'.$sstr);
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
	<table class="main_no_height" id="myTable">
	 <tr>
	  <th class="news_header lft"  onclick="gotourl('include.php?place=susers&sort=1')">#</th>
	  <th class="news_header lft" >Дата</th>
	  <th class="news_header lft" onclick="gotourl('include.php?place=susers&sort=2')">Имя</th>
	  <th class="news_header lft">Права</th>
  	<th class="news_header">Функции</th>
	 </tr>

	<?php
//	$query = "SELECT * FROM ".TABLE_USERS." ORDER BY id DESC";
	$q = $db->get_rows($query);
	foreach ($q as $row ) {
	?>
	 <tr>
	  <td class="news_td lft cli" onclick="view_usersus(this);"><?php echo $row['id']?></td>
	  <td class="news_td lft"><?php echo ($row['dt']>0 ? date('d-m-y', $row['data']) : '&nbsp;') ?></td>
	  <td class="news_td lft"><?php  echo $row['name']?></td>
	  <td class="news_td lft"><?php echo $row['role']?></td>
		<td class="news_td cen">
		  <a  href="include.php?place=<?php echo $global_place.'&dID='.$row['id']?>"><img alt="Удалить" title="Удалить" width="18" height="19" src="<?=TEMPLATE_URL?>images/delete.png" /></a>
		  </td>
		 </tr>
<?php	}	?>
	</table>
	<?php echo button1('Создать',"GetNewUsersus(this);",'','add')?>
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
	  <td class="news_td" style="padding-right:20px;">Дата регистрации</td>
	  <td class="news_td"><?echo ($inf['dt']>0 ? date('d-m-y, G:i', $inf['dt']) : '&nbsp;')?></td>
	  <td class="news_td" style="padding-right:20px;"></td>
	  <td class="news_td"></td>
	 </tr>
	 <tr>
	  <td class="news_td" colspan="4">Имя <input id="fum" class="fbinp" readonly="readonly" style="width:150px" type="text" name="k_name" value="<?php  echo $inf['name']?>"/>
	  Права <input class="fbinp" readonly="readonly" type="text" style="width:150px" name="k_role" value="<?php  echo $inf['role']?>"/></td><input type="hidden" name="k_id" value="<?=$_GET['vID']?>"/>
	 </tr>
	  <tr>
	  <td class="news_td" style="padding-right:20px;">Хеш пароля</td>
	  <td class="news_td"><?php  echo $inf['pass']?></td>
	  <td class="news_td" style="padding-right:20px;"></td>
	  <td class="news_td"></td>
	 </tr>
	 <tr>
	  <td class="news_td" style="padding-right:20px;"></td>
	  <td class="news_td" colspan="2"></td>
	  <td class="news_td"><?php echo button1('Редактировать',"SaveUsersus(this);",'','edit')?></td>
	 </tr>

	</table>
	</form>
	</div>
	<?}
	elseif (!empty($_GET['new']))
	{?>
<div align="left"><form id="fuser" action="" enctype="multipart/form-data">

	<table cellspacing="0" cellpadding="0" width="100%">
	 <tr>
	  <td class="news_td" colspan="4">Имя <input id="fum" class="fbinp" style="width:150px" type="text" name="k_name" value=""/>
	  Пароль <input class="fbinp" type="text" style="width:150px" name="k_pass" value=""/></td>
	 </tr>
	  <tr>
	  <td class="news_td" style="padding-right:20px;">Права</td>
	  <td class="news_td"><input class="fbinp" type="text" style="width:50px" name="k_role" value="2"/></td>
	  <td class="news_td" style="padding-right:20px;"></td>
	  <td class="news_td"></td>
	 </tr>
	 <tr>
	  <td class="news_td" style="padding-right:20px;"></td>
	  <td class="news_td" colspan="2"></td>
	  <td class="news_td"><?php echo button1('Cохранить',"SaveNewUsersus(this);",'','save')?></td>
	 </tr>

	</table>
	
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