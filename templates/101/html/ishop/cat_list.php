<!--catlist-->
<? foreach($cats as $id=>$row) { 
if(!empty($row['vlink']) && ($this->sets['cpucat']==1)){
	$link = $row['vlink'];
} else $link=$type.'/'.$row['id'];
?>
<div class="cat_block" style="display:inline-block;  " onclick="window.location.href='<?=SITE_URL?><?=$link?>'">
<a href="<?=$link?>"><? if(file_exists($row['foto'])) {
?><img alt="<?=$row['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=110&y=110&crop"/><?
 } else { ?>
<? } 
?></a><div class="cat_title"><?=$row['title']?></div></div>
<? } ?>
<div style="clear: both"></div>
