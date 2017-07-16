<?
	$btns = array(
		'Формы'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=forms\'')
	);
	
	
	ob_start();

if(empty($_GET['id']))
{	

	if(!empty($_POST['send']))
	{
		$db->insert(TABLE_FORMS, array('name'=>$_POST['name'], 'parent_id'=>$_POST['parent_id']));
	}

	if(!empty($_GET['rid']))
	{
		$db->delete(TABLE_FORMS, array('id'=>$_GET['rid']));
	}
	
	$module['html'] .= '';
	
	?>
<form id="frmparts" style="margin:3px;" action="" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Добавление формы</legend>
<table>
 <tr>
  <td>Название</td>
  <td><input name="name" type="text" value="" /></td>
 </tr>
 <tr>
  <td>Раздел</td>
  <td><input name="parent_id" type="text" value="" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><input name="send" type="submit" value="Добавить" /></td>
 </tr>
</table></fieldset><br>
<table class="main_no_height" id="myTable">
 <tr>
  <th style="padding-left:10px; width:200px;" class="news_header">Название</th>
  <th style="padding-left:10px; width:200px;" class="news_header">Раздел</th>
  <th style="padding-left:10px;" class="news_header" style="width:60px; text-align:center;">Функции</th>
 </tr>
<?

$rows = $db->get_rows("SELECT * FROM ".TABLE_FORMS." ORDER BY id DESC");

foreach($rows as $id=>$val)
{
?> 
 <tr>
  <td style="padding-left:10px;" class="news_td"><a href="include.php?place=forms&id=<?=$val['id']?>"><?=$val['name']?></a></td>
  <td style="padding-left:10px;" class="news_td"><?=$val['parent_id']?></td>
  <td style="padding-left:10px;" class="news_td"><a href="include.php?place=forms&rid=<?=$val['id']?>">Удалить</a></td>
 </tr>
<?
}
?>
</table> 
</form>
	<?
	
	
}
else
{
	if(!empty($_POST['save']))
	{
		foreach($_POST['name'] as $id=>$val)
		{
			$db->update(TABLE_FEED, array('id'=>$id), array(
				'name' => $_POST['name'][$id],
				'type' => $_POST['type'][$id],
				'options' => $_POST['options'][$id],
				'parent_id' => $_POST['parent_id'][$id],
				'sort' => $_POST['sort'][$id],
				'enabled' => $_POST['enabled'][$id],
				'required' => $_POST['required'][$id]
			));
		}
		
		$site->redirect(SA_URL."include.php?place=forms&id=3");
	}

?>
<form id="frmparts" style="margin:3px;" action="" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Добавление формы</legend>
<table>
 <tr>
  <td>Название</td>
  <td><input name="name" type="text" value="" /></td>
 </tr>
 <tr>
  <td>Раздел</td>
  <td><input name="parent_id" type="text" value="" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><input name="send" type="submit" value="Добавить" /></td>
 </tr>
</table></fieldset><br>
<table class="main_no_height" id="myTable">
 <tr>
  <th style="padding-left:10px; width:200px;" class="news_header">name</th>
  <th style="padding-left:10px; width:200px;" class="news_header">type</th>
  <th style="padding-left:10px; width:200px;" class="news_header">options</th>
  <th style="padding-left:10px; width:200px;" class="news_header">parent_id</th>
  <th style="padding-left:10px; width:200px;" class="news_header">sort</th>
  <th style="padding-left:10px; width:200px;" class="news_header">enabled</th>
  <th style="padding-left:10px; width:200px;" class="news_header">required</th>
  <th style="padding-left:10px;" class="news_header" style="width:60px; text-align:center;">Функции</th>
 </tr>
<?

$rows = $db->get_rows("SELECT * FROM ".TABLE_FEED." ORDER BY sort ASC, id ASC");

foreach($rows as $id=>$val)
{
?> 
 <tr>
  <td style="padding-left:10px;" class="news_td"><input name="name[<?=$val['id']?>]" type="text" value="<?=htmlspecialchars($val['name'],ENT_COMPAT | ENT_XHTML,'cp1251')?>" /></td>
  <td style="padding-left:10px;" class="news_td"><input name="type[<?=$val['id']?>]" type="text" value="<?=htmlspecialchars($val['type'],ENT_COMPAT | ENT_XHTML,'cp1251')?>" /></td>
  <td style="padding-left:10px;" class="news_td"><input name="options[<?=$val['id']?>]" type="text" value="<?=htmlspecialchars($val['options'],ENT_COMPAT | ENT_XHTML,'cp1251')?>" /></td>
  <td style="padding-left:10px;" class="news_td"><input name="parent_id[<?=$val['id']?>]" style="width:30px;" type="text" value="<?=htmlspecialchars($val['parent_id'],ENT_COMPAT | ENT_XHTML,'cp1251')?>" /></td>
  <td style="padding-left:10px;" class="news_td"><input name="sort[<?=$val['id']?>]" style="width:30px;" type="text" value="<?=htmlspecialchars($val['sort'],ENT_COMPAT | ENT_XHTML,'cp1251')?>" /></td>
  <td style="padding-left:10px;" class="news_td"><input name="enabled[<?=$val['id']?>]" style="width:30px;" type="text" value="<?=htmlspecialchars($val['enabled'],ENT_COMPAT | ENT_XHTML,'cp1251')?>" /></td>
  <td style="padding-left:10px;" class="news_td"><input name="required[<?=$val['id']?>]" style="width:30px;" type="text" value="<?=htmlspecialchars($val['required'],ENT_COMPAT | ENT_XHTML,'cp1251')?>" /></td>
  <td style="padding-left:10px;" class="news_td"><a href="include.php?place=forms&rid=<?=$val['id']?>">Удалить</a></td>
 </tr>
<?
}
?>
</table> 
<input type="submit" name="save" value="Сохранить" />
</form>
<?
}
	
	$module['html'] .= ob_get_contents();
	ob_end_clean();
?>