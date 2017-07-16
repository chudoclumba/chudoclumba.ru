<div class="ppt"><a href="<?=SITE_URL?>ishop/additions">Последние поступления</a></div>
<table width="100%" class="tbl_cat_and_pr" cellspacing="10">
<?
$k = 0;
foreach($rows as $id=>$row) 
{ 
if ($k==0) echo '<tr>';?>
<td class="prd_outer_short" style="width:33%;">
<table class="col prd_cell_inner" align="center" style="margin:0px auto;">
<tr><td class="prd_ttl p0"><a href="<?=SITE_URL?>ishop/product/<?=$row['id']?>"><?=$row['title']?></a></td></tr>
<tr><td class="prd_bgs p0"><div style="width:<?=$this->image_width?>px;" class="prd_image_fix"><?
	if($sets['mod_new']) echo ((!empty($row['new'])) ? ' <div class="new_m"></div>' : '');
	if($sets['mod_hit']) echo ((!empty($row['hit'])) ? ' <div class="hit_m"></div>' : '');
	if($sets['mod_spec']) echo ((!empty($row['spec'])) ? ' <div class="skidka_m"></div>' : '');
?>	  
<a href="<?=SITE_URL?>ishop/product/<?=$row['id']?>"><? 
if(file_exists($row['foto'])) {?>
<img alt="<?=$row['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&amp;x=<?=$this->image_width?>&y=<?=$this->image_height?>" />
	   <? } else { ?>
<img alt="" width="134" height="134" src="s.gif" /><? } ?>	  
</a></div></td></tr>
<tr><td class="prd_dinf p0">
<? if(!empty($row['param_starayatsena']) && $row['param_starayatsena'] > 0 && $sets['mod_old_price']) { ?>
<div class="ppd_list_item">Старая цена: <span class="lt"><?=$this->s_price($row['param_starayatsena'], 0)?></span></div>
<? } ?>
<? if($sets['mod_show_price']) { ?><div class="ppd_list_item">Цена: <?=$this->s_price($row['tsena'], $row['skidka'])?></div><? } ?>
</td></tr></table></td>
<?
	$k++;

	if($k == 3) 
	{
		echo '</tr>';
		$k = 0;
	}
 } ?>
</table>