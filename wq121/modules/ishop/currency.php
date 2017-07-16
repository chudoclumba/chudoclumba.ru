<?php
	
$cf ='
<style>

.m_currency {
text-align:left;
}

.tbl_currency {
	border-collapse:collapse;
}
table.currency_add td {
font-size:11px;
}

.crcs_title {
font-size:12px;
font-weight:bold;
text-align:left;
padding-top:10px;
}

</style>

';

if(!empty($_POST['add']))
{
	$params = array(
		'name' => $_POST['cname'],
		'ed' => $_POST['ced'],
		'rate' => $_POST['ctax']
	);

	$db->insert(TABLE_CURRENCY, $params);
}

$cf .= '
<p class="crcs_title">Добавление валюты:</p>
<div style="text-align:left;">
<form action="" method="post">
<table class="currency_add">
 <tr>
  <td>Наименование</td>
  <td><input name="cname" type="text" value="" /></td>
 </tr>
 <tr>
  <td>Отношение к рублю</td>
  <td><input name="ctax" type="text" value="" /></td>
 </tr>
 <tr>
  <td>Ед. измерения</td>
  <td><input name="ced" type="text" value="" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><input name="add" class="button2" type="submit" value="Добавить" /></td>
 </tr>
</table>
</form>
</div>
<p class="crcs_title">Редактирование валюты:</p>
<div class="m_currency">
<form action="" method="post">
<table class="tbl_currency">
 <tr>
  <td class="header_td">#</td>
  <td class="header_td">Наименование</td>
  <td class="header_td">Ед. измерения</td>
  <td class="header_td">Отношение к рублю</td>
  <td class="header_td">Удалить</td>
 </tr>';

if(!empty($_POST['save']))
{
	foreach($_POST['cname'] as $id=>$value)
	{
		if(!empty($_POST['del'][$id]))
		{
			$db->delete(TABLE_CURRENCY, array('id'=>$id));
		}
		else
		{
			$params = array (
				'name' => $_POST['cname'][$id],
				'ed' => $_POST['ced'][$id],
				'rate' => $_POST['crate'][$id]
			);
			$db->update(TABLE_CURRENCY, $id, $params);
		}
	}
}

$crcs = $db->get_rows("SELECT * FROM ".TABLE_CURRENCY."");
$f = 1;
foreach($crcs as $crc)
{
	$cf .= '
	<tr>
	 <td class="tree_td">'.$f.'</td>
	 <td class="tree_td"><input value="'.$crc['name'].'" type="text" name="cname['.$crc['id'].']" /></td>
	 <td class="tree_td"><input value="'.$crc['ed'].'" type="text" name="ced['.$crc['id'].']" /></td>
	 <td class="tree_td"><input value="'.$crc['rate'].'" type="text" name="crate['.$crc['id'].']" /></td>
	 <td class="tree_td"><input type="checkbox" name="del['.$crc['id'].']" /></td>
	</tr>
	';
	$f++;
}

 
$cf .= '
</table>
<div style="padding:10px; 0px;"><input class="button2" name="save" type="submit" value="Сохранить" /></div>
</form>
</div>';


$module['html'] .= $cf;
?>