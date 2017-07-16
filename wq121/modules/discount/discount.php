<?
	$btns = array(
		'Скидки'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=discount\'')
	);
	
	if(!empty($_POST['send']))
	{
		$db->insert(TABLE_DISCOUNTS, array('start'=>$_POST['start'], 'end'=>$_POST['end'], 'percent'=>$_POST['percent']));
	}

	if(!empty($_GET['rid']))
	{
		$db->delete(TABLE_DISCOUNTS, array('id'=>$_GET['rid']));
	}
	
	$module['html'] .= '';
	
	ob_start();
	
	?>
<form id="frmparts" style="margin:3px;" action="" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Добавление скидки</legend>
<table>
 <tr>
  <td>От</td>
  <td><input name="start" type="text" value="" /></td>
 </tr>
 <tr>
  <td>До</td>
  <td><input name="end" type="text" value="" /></td>
 </tr>
 <tr>
  <td>Скидка</td>
  <td><input name="percent" type="text" value="" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><input name="send" type="submit" value="Добавить" /></td>
 </tr>
</table></fieldset><br>
<table class="main_no_height" id="myTable">
 <tr>
  <th style="padding-left:10px; width:200px;" class="news_header">От</th>
  <th style="padding-left:10px; width:200px;" class="news_header">До</th>
  <th style="padding-left:10px; width:200px;" class="news_header">Скидка</th>
  <th style="padding-left:10px;" class="news_header" style="width:60px; text-align:center;">Функции</th>
 </tr>
<?

$rows = $db->get_rows("SELECT * FROM ".TABLE_DISCOUNTS." ORDER BY id DESC");

foreach($rows as $id=>$val)
{
?> 
 <tr>
  <td style="padding-left:10px;" class="news_td"><?=$val['start']?></td>
  <td style="padding-left:10px;" class="news_td"><?=$val['end']?></td>
  <td style="padding-left:10px;" class="news_td"><?=$val['percent']?></td>
  <td style="padding-left:10px;" class="news_td"><a href="include.php?place=discount&rid=<?=$val['id']?>">Удалить</a></td>
 </tr>
<?
}
?>
</table> 
</form>
	<?
	
	
	$module['html'] .= ob_get_contents();
	ob_end_clean();
?>