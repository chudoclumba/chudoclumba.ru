<?php
if (!empty($_POST['add_new']))
{
	$n_name = 'param_'.ru_to_en_lc($_POST['name']);

	$params = array(
		'param_name' => $_POST['name'],
		'param_descr' => $n_name,
		'param_type' => $_POST['type']
	);
	 
	$db->insert(TABLE_PRD_P,$params);
	
	$q2 = "ALTER TABLE ".TABLE_PRODUCTS." ADD ".$n_name." ".$_POST['type']."";
	$db->exec($q2);
	
	header("Location:include.php?place=ishop&action=params");
}

if (!empty($_GET['delID']))
{
$parametr = $db->get(TABLE_PRD_P,$_GET['delID']);
$db->exec("DELETE FROM ".TABLE_PRD_P." WHERE id = '".$_GET['delID']."' LIMIT 1");
$db->exec("ALTER TABLE `".TABLE_PRODUCTS."` DROP `".$parametr['param_descr']."`;");
 header("Location:include.php?place=ishop&action=params");
}


$query = $db->get_rows("SELECT * FROM ".TABLE_PRD_P." ORDER BY id ASC");
ob_start();
?>
<style>
.opt_r {
 margin:0px 0px 0px 37px;
}

.rb_opt {
 border-collapse:collapse;
}

.opt_r * {
 font-size:12px;
 font-family:Tahoma;
}

.p1_w {
 width:200px;
}

.p2_w {
 width:300px;
}
</style>
<div style="padding:10px 0px 10px 37px;"><a href="include.php?place=ishop&action=gr_params">Группы параметров</a></div>
<div class="opt_r" align="left">
<table class="rb_opt">
 <tr>
  <td class="p1_w header_td">Параметр</td>
  <td class="p2_w header_td">Тип</td>
  <td class="header_td">Включен</td>
  <td class="header_td">Функции</td>
 </tr>
<?php
foreach($query as $row)
{
?>
 <tr>
  <td class="tree_td"><?php echo $row['param_name']?> ( <?php echo $row['param_descr']?> )</td>
  <td class="tree_td"><?php echo $row['param_type']?></td>
  <td class="tree_td"><?php echo $row['param_enable']?></td>
  <td class="tree_td"><a href="include.php?place=ishop&amp;action=params&amp;delID=<?php echo $row['id']?>">Удалить</a></td>
 </tr>
<?php
}
?>
</table>
</div>
<div align="left" style="padding:20px 0px 0px 40px;">
<form action="" method="post">
 <input name="name" type="text" />
 <select name="type">
  <option value="decimal(15,2)">цена</option>
  <option value="int(11)">целое число</option>
  <option value="text">текст</option>
 </select>
 <input name="add_new" value="Добавить" type="submit" />
</form>
</div>
<?php
$module['html'] = ob_get_contents();
ob_end_clean();
?>