<?
	$link='ishop/product/'.$row['id'];
	if(!empty($row['vlink']) && $this->sets['cpucat']==1){
		$link=$row['vlink'];
	}
?>
<table>
<tr><td class="sk_ttl"><a href="<?=$link?>"><?=$row['title']?></a></td></tr>
<tr><td><div class="prd_image_fix" style="width:156px; height: 156px"><a href="<?=$link?>"><img alt="<?=$row['title']?>" src="thumb.php?id=<?=$row['foto']?>&x=150&y=150&crop" /></a></div></td></tr>
<tr><td class="sk_pr">Цена: <?=$this->s_price($row['tsena'])?></td></tr>
</table>
<!--
<div class="h2" onclick="UpdateHit();">Хит продаж</div>
<div class="sk_ttl" style="vertical-align: middle  ; height: 28px; overflow: hidden;  display: table-cell ; font-size: 13px; line-height: 14px;width: 100%"><a href="<?=$link?>"><?=$row['title']?></a></div>
<div class="prd_image_fix" style="width:156px; height: 156px"><a href="<?=$link?>"><img alt="<?=$row['title']?>" src="thumb.php?id=<?=$row['foto']?>&x=150&y=150&crop" /></a></div>
<div class="sk_pr">Цена: <?=$this->s_price($row['tsena'])?></div> -->
