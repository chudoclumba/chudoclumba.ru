<div class="lastinp">
<div class="h2">Популярные товары</div>
<table class="tbl_cat_and_pr w100">
<?
$k = 0;
foreach($rows as $id=>$row) 
{ 
	$link=SITE_URL.'ishop/product/'.$row['id'];
	if(!empty($row['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$row['vlink'];
	}

if ($k==0) echo '<tr>';?>
<td style="width:33%;">
<table class="col prd_cell_inner" style="margin:0px auto;">
<tr><td class="prd_zdiv"><a href="<?=$link?>"><?=$row['title']?></a></td></tr>
<tr><td class="prd_bgs p0"><div style="width:156px; height: 156px" class="prd_image_fix"><?
	if($sets['mod_new']) echo ((!empty($row['new'])) ? ' <div class="new_m"></div>' : '');
	if($sets['mod_hit']) echo ((!empty($row['hit'])) ? ' <div class="hit_m"></div>' : '');
	if($sets['mod_spec']) echo ((!empty($row['spec'])) ? ' <div class="skidka_m"></div>' : '');
?>	  
<a href="<?=$link?>"><? 
if(file_exists($row['foto'])) {?>
<img alt="<?=$row['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=150&y=150&crop" />
	   <? } else { ?>
<img alt="" width="150" height="150" src="s.gif" /><? } ?>	  
</a></div></td></tr>
<tr><td class="prd_dinf tcen">
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
</table></div>