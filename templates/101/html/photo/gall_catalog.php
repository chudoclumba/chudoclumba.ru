<?php

$fotki = array();
foreach($data as $data_id=>$data_val)
{
	$photo_data = get_elements($data_val['photo'],'articul');
	foreach($photo_data as $photo_id=>$photo_info)
	{
		if($photo_id == 0)
		{
			$val = get_elements($photo_info,'param');
			$fotki[] = array($val, $data_val['title'], $data_val['id']);
		}
	}
}	

?>
<table class="col w100">
<tr>
<?
foreach($fotki as $id=>$val)
{
?>
<td class="photo_cat_cell"><a href="photo/<?=$val['2']?>"><img alt="<?=$val['1']?>" src="thumb.php?id=<?=$val['0']['0']?>&x=190&y=190" /><br><?=$val['1']?></td>
<?php

	if(($id+1)%3 == 0) echo '</tr><tr>';
}

?>
</tr>
</table>