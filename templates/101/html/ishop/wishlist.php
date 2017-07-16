<div id="w_table">
<? if ($summa>0) {?>
<table class="ppr_mt cart_t">
 <tr >
  <th >Фото</th>
  <th></th>
  <th >Удалить</th>
 </tr>
 

<?
$ps='--';
$zk=0;
 foreach($prd as $id=>$val)
{
	$link=SITE_URL.'ishop/product/'.$id;
	if(!empty($val['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$val['vlink'];
	}

?>
	<!-- single-product start -->
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 col-padd">
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
<img title="<?=$row['title']?>" alt="<?=$row['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=250&y=250&crop" />
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

 <tr id="wrow_prd_<?=$id?>" <?echo($val['active'] ? '' : 'style="background:#ffff00"')?>>
  <td class="" width="100px">
   <? if(file_exists($val['foto'])) {?>
		<a class="ffd" href="<?=SITE_URL?><?=$val['foto']?>">
        <img alt="<?=$val['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=100&y=100&crop" style="display: block" />
		</a>
	   <? } else { ?>Изображение временно отсутствует.<? } ?>
  </td>
  <td class=""><a href="<?=$link?>"><?=$val['param_kodtovara'].'<br>'.$val['title']?></a><br><?=$this->s_price($val['tsena'], $val['skidka'])?>*<input id="<?=$id?>" class="count_prd fbinp" style="width:20px;text-align: center;" name="count[<?=$id?>]" value="<?=$val['cnt']?>" />=<?=$this->s_price($val['tsena']*$val['cnt'], $val['skidka'])?> 	
  </td>
 <?}?>
  <td class="tcen">
	
  </td>
 </tr>
<? 

 ?>
 <tr>
  <td class="cart_all ">Сумма:</td>
  <td class="cart_all_p  summa_c"><?=$this->s_price_c($summa)?></td>
  <td class="">&nbsp;</td>
 </tr>
<?
if(!empty($this->sets['mod_prd_skidka']) && !empty($_SESSION['user']))
{
	$orders1 = $this->db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$_SESSION['user']."' && status != 6");
}
$sale=0;
if (isset($_SESSION['user']) && $_SESSION['user']>0) $sale=User::gI()->user['sale'];
if(!empty($this->sets['mod_cards']) || ((!empty($this->sets['mod_prd_skidka']))   && (!empty($_SESSION['user'])) )  && !empty($orders1['0']['summa'])){

	if(!empty($this->sets['mod_prd_skidka']) && !empty($_SESSION['user']) && !empty($orders1['0']['summa'])) {
		$orders = $this->db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
		if ($orders['0']['percent']>$sale) $sale=$orders['0']['percent'];
	}
}	
if ($sale>0) { ?>
 <tr>
  <td class="cart_all">Скидка (<?=$sale?>%):
  </td>
  <td class="cart_all_p  summa_c"><?=$this->s_price_c(($summa*$sale)/100)?></td>
  <td class="">&nbsp;</td>
 </tr> 
 <tr>
  <td class="cart_all ">Всего:</td>
  	<td class="cart_all_p  summa_c">
<?
  $skidka = (!empty($sale)) ? ($summa*$sale)/100 : 0;
  echo $this->s_price_c($summa  - $skidka)
?>
	</td>
  	<td class="">&nbsp;</td>
 </tr>
<?}?>
</table>
<?} else echo 'Список желаний пуст.';?>
</div><br>
<button class="albutton alorange" type="button" onclick="ClearWish();"><span><span><span class="cancel">Очистить</span></span></span></button>
<script>
    $(".ffd").fancybox({
    	openEffect	: 'elastic',
    	closeEffect	: 'elastic',

    	helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
    });
</script>