<?php

$btns = array(
	'Список новостей'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=news\''),
	'Подписчики'=>array('id'=>1,'href'=>'')

);

if (!empty($_POST['copy_news']))
{
	foreach($_POST['box'] as $id => $val) 
	{
		$res = $db->get(TABLE_NEWS,$id);
		unset($res['id']);
		$db->insert(TABLE_NEWS,$res);
	}
	header("Location:include.php?place=".$global_place);
}

if(!empty($_POST['send_news']))
{
	foreach($_POST['box'] as $id => $val) 
	{
		$news = $db->get(TABLE_NEWS,$id);
		$users = $db->get_rows("SELECT email FROM ".TABLE_PODPISKA."");
		foreach($users as $user)
		{
			$content = '<div style="padding:3px 0px 3px 0px; font-weight:bold; color:#666;">'.date('d.m.Y', $news['cdate']).'</div><div style="padding:3px 0px; color:#000; font-weight:bold;">'.$news['t'].'</div><div style="padding:3px 0px;">'.str_replace('data/','https://www.chudoclumba.ru/data/',$news['txt']).'</div>';
			$a=sendmail_a($user['email'], 'Рассылка новостей', $content);
			$b=mail('info@chudoclumba.ru','Test','Test');
			$a=($b==false);
			print_r(array($a,$b,true,$user['email'],$content));
		}
	}
	die;
//	header("Location:include.php?place=".$global_place);
}

if(isSet($_GET['rID']))
{
	$db->delete(TABLE_NEWS,array('id' => $_GET['rID']));
	header("Location:include.php?place=".$global_place);
}

if (!empty($_POST['delete_news']) && $_POST['delete_news'] == 1)
{
	foreach ($_POST['box'] as $id => $v)
		$db->delete(TABLE_NEWS,array('id' => $id));

	header("Location:include.php?place=".$global_place);
}
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
		$sstr=" where email LIKE '%".str_replace ("'","_",$schstr)."%'";
	}
	if ($schstr>" ")
	{ $ee='&sch='.$schstr;}
	else
	{ $ee='';}

ob_start();

	$q = $db->query("SELECT * FROM ".TABLE_PODPISKA.$sstr);
	$pg_count = $db->num_rows($q);
	$db->free_result($q);

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';

?>
<div class="news_pan">
  <?php if($_SESSION['auth']['role']==1) echo button1('Создать хэш', "CreateMD();",'id="bq"','sendm')?>
 </div>
 <div class="adv_news_pan">
 </div>
	<div class="clear">
	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place.'&action=users'?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>
	</div>
<form name="forma" style="margin:3px;" id="frmparts" action="include.php?place=<?php echo $global_place?>" method="post">
<input type="hidden" id="send_news" name="send_news" value="0" />
<input type="hidden" id="copy_news" name="copy_news" value="0" />
<input type="hidden" id="delete_news" name="delete_news" value="0" />
<script>
	$(document).ready(function () {
    	$('input').iCheck({checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'});	
		$("#markp").on('ifToggled',function(){if ($(this).prop('checked')){$("input[name^='box']").iCheck('check');} else {$("input[name^='box']").iCheck('uncheck');}});
	});
</script>
<?
echo get_pages(	array ('class' => 'prd_pages_top','count_pages' => $pages, 'curr_page'=> $page, 'link' => 'include.php?place=news&action=users'.$ee.'&page=','info'=>$mes)).'<div class="clear"></div>' ?>
 <table cellspacing="0">
  <tr>
   <th style="width:25px;" class="news_header">
    <input  type="checkbox" id="markp">
   </th>
  <th  style="width:75px;" class="news_header">Дата</th>
  <th class="news_header">E-mail</th>
  <th style="width:65px; text-align:Center;" class="news_header center">Функции</th>
 </tr>
<?php
$i=0;
$rowscnt = 0;



$q = $db->get_rows("SELECT * FROM ".TABLE_PODPISKA.$sstr." order by date DESC, id DESC".$limit);
foreach($q as $row) 
{
?>
 <tr id="rn_<?=$row['id']?>">
  <td class="news_td" style="width:30px;">
   <input  type="checkbox" name="box[<?php echo $row['id']?>]" id="boxes<?php echo $rowscnt?>"/>
  </td>
  <td class="news_td"><?php echo htmlspecialchars(date("d-m-Y",$row['date']),ENT_COMPAT | ENT_XHTML,'utf-8')?></td>
  <td class="news_td"><?php echo htmlspecialchars($row['email'],ENT_COMPAT | ENT_XHTML,'utf-8')?></td>
  <td style="white-space:nowrap; text-align:center; " class="news_td">
  <a  onclick="Deleteus('<?echo $row['id']?>')"><img alt="Удалить" title="Удалить подписчика" width="18" height="19" src="<?=TEMPLATE_URL?>images/delete.png" /></a>
  </td>
 </tr>
<?php
	$rowscnt++;
}
?>
 </table>
</form>
<?


echo get_pages(	array ('class' => 'prd_pages_bottom','count_pages' => $pages, 'curr_page'=> $page, 'link' => 'include.php?place=news&action=users'.$ee.'&page=','info'=>$mes))?>

<?php
$module['html'] .= ob_get_contents();
$module['path']='Подписчики новостей';
ob_end_clean();