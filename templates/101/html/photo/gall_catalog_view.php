<?php

$fotki = array();
foreach($data as $data_id=>$data_val)
{
	$photo_data = get_elements($data_val['photo'],'articul');
	foreach($photo_data as $photo_id=>$photo_info)
	{
		$val = get_elements($photo_info,'param');
		$fotki[] = array($val, $data_val['title'], $data_val['id']);
	}
}	

$r_id = explode('_', $_GET['id']);
$page = (!empty($r_id['1']) && $r_id['1'] > 0) ? $r_id['1'] : 1;

$next = (($page + 1) <= count($fotki)) ? ($page+1) : 1;
$prev = (($page - 1) < 1) ? (count($fotki)) : ($page-1);

if($page < 3)
{
	$start = 0;
}
elseif($page > count($fotki)-3)
{
	$start = count($fotki)-5;
}
else
{
	$start = $page-3;
}

$end = $start + 4;

?>
<div class="photo_bg"><a href="photo/<?=$r_id['0']?>_<?=$next?>"><img alt="" src="thumb.php?id=<?=$fotki[($page-1)]['0']['0']?>&x=500&y=500" /></a></div>
<table class="col cat_small_photo w100">
 <tr>
  <td class="p0 center"><a href="photo/<?=$r_id['0']?>_<?=$prev?>"><img src="<?=TEMP_FOLDER?>/images/ab.gif" /></a></td>
  <td class="p0">
<table align="center" class="col w100">
<tr>
<?php

foreach($fotki as $id=>$val)
{
	if($id >= $start && $id <= $end)
	{
?>
<td class="photo_cat_cell photo_cat_cell_m"><a href="photo/<?=$val['2']?>_<?=($id+1)?>">
<img class="<?=($id == ($page-1)) ? 'active' : ''?>" alt="<?=$val['1']?>" src="thumb.php?id=<?=$val['0']['0']?>&x=120&y=120" /></a></td>
<?php
	}
}

?>
</tr>
</table>
</td>
  <td class="p0 center"><a href="photo/<?=$r_id['0']?>_<?=$next?>"><img src="<?=TEMP_FOLDER?>/images/af.gif" /></a></td>
</tr>
</table>