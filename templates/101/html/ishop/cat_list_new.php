


<div class="shop-product-area">
<div class="row row-margin2">
<?
foreach($cats as $id=>$row) { 
if(!empty($row['vlink']) && ($this->sets['cpucat']==1)){
	$link = $row['vlink'];
} else $link=$type.'/'.$row['id'];
?>


	<!-- single-product start -->
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 col-padd">
		<div class="single-product">
			<div style="display: block; height: 70px; max-height: 80px">
				<h2 class="product-name"><a href="<?=$link?>"><?=$row['title']?></a></h2>
				</div>
			<div class="product-img">
				<a href="<?=$link?>">
<? if(file_exists($row['foto'])) {?>
<img title="<?=$row['title']?>" alt="<?=$row['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=250&y=250&crop" />
	   <? } else { ?>
<img alt="" width="250" height="250" src="s.gif" /><? } ?>
				</a>
			</div>
		</div>
	</div>
	<!-- single-product end -->
<?}?>

	</div>
	<!-- product section end -->
</div>

