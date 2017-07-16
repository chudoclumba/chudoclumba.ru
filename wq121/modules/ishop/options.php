<?php

$t = 0;
foreach ($image_types as $a => $b)
	if (isSet($_POST['save_'.$a]))
	{
		$db->exec("update ".TABLE_SETTINGS." set `value` = '".$_POST['image_scale_'.$a]."' where `id` = 'image_scale_".$a."'");
		$db->exec("update ".TABLE_SETTINGS." set `value` = '".$_POST['image_width_'.$a]."' where `id` = 'image_width_".$a."'");
		$db->exec("update ".TABLE_SETTINGS." set `value` = '".$_POST['image_height_'.$a]."' where `id` = 'image_height_".$a."'");
		$t = 1;
	}

if ($t) header("Location: ".$prelink."&action=options");

?>
<div style="text-align: <?php echo CONTENT_ALIGN?>">
<form action="<?php echo $prelink?>&action=options" method="post">
<table width="50%" cellpadding=0 cellspacing=0 border=0 class="settings">
<tr><td height="10px" class="text">Размеры изображений</td>

<tr>
<td class="header_td">Тип изображения</td>
<td class="header_td">Масштаб</td>
<td class="header_td">Размер в пикселях</td>
<td class="header_td">Действие</td>

<?php
$sts = array();


$q = $db->get_rows("select * from ".TABLE_SETTINGS." where `part` = '".$global_place."'");
foreach ($q as $res)	$sts[$res['id']] = $res['value'];

foreach ($image_types as $a => $b)
{
?>

<tr>
	<td class="text"><?php echo $b?></td>
	<td class="text">
<input type="radio" style="border: none" name="image_scale_<?php echo $a?>" value="n" <?php echo (($sts['image_scale_'.$a] == 'n')? 'checked' : '')?>>&nbsp;Оптимально<br>
<input type="radio" style="border: none" name="image_scale_<?php echo $a?>" value="w" <?php echo (($sts['image_scale_'.$a] == 'w')? 'checked' : '')?>>&nbsp;По ширине<br>
<input type="radio" style="border: none" name="image_scale_<?php echo $a?>" value="h" <?php echo (($sts['image_scale_'.$a] == 'h')? 'checked' : '')?>>&nbsp;По высоте
</td>
	<td class="text">
<input type="text" name="image_width_<?php echo $a?>" value="<?php echo $sts['image_width_'.$a]?>"><br>
<input type="text" name="image_height_<?php echo $a?>" value="<?php echo $sts['image_height_'.$a]?>">
</td>

	<td class="text val"><input type="submit" name="save_<?php echo $a?>" class="button1" value="Сохранить"></td>
<tr><td colspan=4 class="tdsep"></td>

<?php
}
?>

</table>
</form>
</div>