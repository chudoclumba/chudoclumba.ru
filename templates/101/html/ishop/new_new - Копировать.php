<div class="our-product-area best-offere featur-padd mt25">
<!-- area title start -->
<div class="area-title"><h2>НОВЫЕ ПОСТУПЛЕНИЯ</h2></div>
<!-- our-product area start -->
<div class="row">
                                    <!-- Tab panes -->
<div role="tabpanel" class="tab-pane fade in active" id="home2">
                                            <!--==============================watches=============================-->
<div class="row row-margin2">
<div class="feature-carousel-tab">
<?

foreach($rows as $id=>$row) 
{ 
	$link=SITE_URL.'ishop/product/'.$row['id'];
	if(!empty($row['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$row['vlink'];
	}
	$ct='';
	if (isset($_SESSION[CART][$row['id']]['count']) && $_SESSION[CART][$row['id']]['count']>0)  $ct= '(Уже '.$_SESSION[CART][$row['id']]['count'].')';

?>

	<!-- single-product start -->
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 col-padd">
		<div class="single-product">
<? if ($row['param_starayatsena']>0 || (!empty($row['spec'])) ||  $row['skidka']>0 ){?>
			<div class="product-label">
				<div class="sale"></div>
			</div>
	
<?} else {?>
			<div class="product-label">
				<div class="new"></div>
			</div>
<?}?>		
			<div class="product-img">
				<a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL.$row['foto']?>">
<? if(file_exists($row['foto'])) {?>
<img alt="<?=$row['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=250&y=250&crop" />
	   <? } else { ?>
<img alt="" width="250" height="250" src="s.gif" /><? } ?>
				</a>
			</div>
			<div class="product-content">
				<div style="display: block; height: 70px; max-height: 80px">
					<h2 class="product-name"><a href="<?=$link?>"><?=$row['title']?></a></h2>
				</div>
				<div class="price-box">
					<span class="new-price"><?=$this->s_price($row['tsena'], $row['skidka'])?></span>
<? if ($row['param_starayatsena']>0){?>
					<span class="old-price"><del><?=$this->s_price($row['param_starayatsena'], 0)?></del></span>
<?}?>					
				</div>
			</div>
			<div class="product-content2">
				<h2 class="product-name"><a href="<?=$link?>"><?=$row['title']?></a></h2>
				<div class="price-box">
					<span class="new-price"><?=$this->s_price($row['tsena'], $row['skidka'])?></span>
<? if ($row['param_starayatsena']>0){?>
					<span class="old-price"><del><?=$this->s_price($row['param_starayatsena'], 0)?></del></span>
<?}?>					
				</div>
				<div class="button-container">
					<a title="Добавить в корзину" id="nwac_<?=$row['id']?>" onclick="Add_to_cart(this);return false;" class="button cart_button">
						<span>В корзину</span><span class="cart_btinfo"><?=$ct?></span>
					</a>
				</div>
			</div>
			<ul class="add-to-links">
				<li>
					<div class="wishlist">
						<a title="Добавить к Wishlist" id="nwaw_<?=$row['id']?>" onclick="Add_to_wish(this);return false;" data-toggle="tooltip"><i class="fa fa-heart"></i></a>
					</div>
				</li>
				<li>
					<div class="view-products">
						<a title="Подробнее..." href="<?=$link?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-arrows-alt"></i></a></div>
				</li>
			</ul>
		</div>
	</div>
	<!-- single-product end -->
<?}?>

	</div>
	<!--feature-carousel-tab-->
	</div>
	</div>
	<!-- product section end -->
	</div>
	</div>


