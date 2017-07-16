<!--wishlist-->
<div id="w_table" class="our-product-area best-offere featur-padd">
<div class="area-title"><h2>Wishlist</h2></div>
<? 
if (!isset($lock)) $lock=false;
if ($summa>0) {
if (!$lock){?>
	<div><label><input <?=($this->wish_locked)?'':'checked="checked"'?> type="checkbox" value="on" id="share" />&nbsp;Разрешить просмотр моего списка другим пользователям.</label></div>
<?}
$sum=0;
 foreach($prd as $id=>$row) { 
	$link=SITE_URL.'ishop/product/'.$id;
	if(!empty($row['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$row['vlink'];
	}
	$sum+=$this->skidka($row['tsena']*$row['cnt'], $row['skidka']);
	$cnt='<input class="count_prd fbinp" style="width:20px;text-align: center;" value="'.$row['cnt'].'" '.((!$lock)?'':'disabled="disabled"').'/>';
	$ct='';
	if (isset($_SESSION[CART][$row['id']]['count']) && $_SESSION[CART][$row['id']]['count']>0)  $ct= '(Уже '.$_SESSION[CART][$row['id']]['count'].')';
?>
	<!-- single-product start -->
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 col-padd" id="wl_<?=$id?>">
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
				<h2 class="product-name"><a href="<?=$link?>"><?=$row['title']?></a></h2>
				<div class="price-box">
					<span class="new-price"><?=$this->s_price($row['tsena'], $row['skidka'])?></span>
<? if ($row['param_starayatsena']>0){?>
					<span class="old-price"><del><?=$this->s_price($row['param_starayatsena'], 0)?></del></span>
<?}?>					
				</div>
				<div class="button-container">
<?	if($row['enabled']==1 && $this->get_prdstate($row['cat_id'])){?>				
					<a title="Добавить в корзину" id="nwac_<?=$row['id']?>" onclick="Add_to_cart(this);return false;" class="button cart_button mb10">
						<span>В корзину</span><span class="cart_btinfo"><?=$ct?></span>
					</a>
<?} else {?>
<div class="ppd_list_item" style="color:red; font-size:11px;">Нет в продаже</div>
	
<?};
if (!$lock) {?>		
					<a title="Удалить из Wishlist"  onclick="ClearWishItem('<?=$row['id']?>')" class="button v_button">
						<span>Удалить</span>
					</a>
<?} else if (Wish::gI()->on && User::gI()->is_logged())echo str_ireplace('%cnt%',Wish::gI()->ShowCnt($id),str_ireplace('%id%',$id,Wish::gI()->knadd));?>			

				</div>
			</div>
			<ul class="add-to-links">
				<li>
					<div class="view-products">
						<a title="Подробнее..." href="<?=$link?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-arrows-alt"></i></a></div>
				</li>
			</ul>
		</div>
	</div>
	<!-- single-product end -->

<? } ?>

<div style="clear: both"></div>
<div id="wsum">Итого на сумму <?=$this->s_price($sum,0)?></div><br>
<script>
function WishAddcart(el){
	tel=$(el).parents('.wish_block').attr('id');
	id=tel.substr(3,tel.length-3);
	oval=$('#'+tel).children('.count_prd').val();
	if (!(oval>0)) {$.fancybox('Введите число больше 0!');return false;}
	$.ajax({
			url: 'cart/setcnt/' + id+'/'+oval,
			cache: false,
			success: function(html){
				var er =JSON.parse(html);
				if (parseInt(er.res) == 1) {
					$('#fixeddiv').html(er.cart);
					$(el).html(er.cnt);
					$("body").append('<div class="msg_cart" style="top:'+($('#'+tel).position().top+120)+'px; left:'+($('#'+tel).position().left-50)+'px;" id="flow_cart_'+id+'"><img src="<?=TEMP_FOLDER?>images/ico_cartm.png" alt="" height="60px" width="60px" /><em>Корзина:</em>Товар добавлен в корзину.</div>');
					eval('$("#flow_cart_'+id+'").fadeIn("slow", function() {setTimeout(function() {$("#flow_cart_'+id+'").fadeOut("slow", function(){$("#flow_cart_'+id+'").remove();})}, 1000);});');
				}
			}
		});
	
}
</script>
<? if (!$lock){?>
<button class="albutton alorange" type="button" onclick="ClearWish();"><span><span><span class="cancel">Очистить</span></span></span></button>
<script>
$('#w_table .count_prd').bind('change',function(){SetWishCnt(this);});
$(document).ready(function () {
	$("#share").on('ifToggled',function(){
		var id=($(this).prop('checked'))?1:0;
		$.ajax({
			url: 'wishlist/set_locked/'+id,
			cache: false,
			success: function (html){
				var er =JSON.parse(html);
				if (parseInt(er.res) == 1) {
					$(this).prop('checked',(er.state>0));
				}
			}
		});
	});
});
</script>
<?}
} else echo 'Список желаний пуст.';
?>
<br /><br /><span class="h2">Просмотр списка желаний другого пользователя</span><br /><br />
Введите e-mail пользователя <input id="mail" type="text" class="fbinp" style="width:150px" value="" />&nbsp;<button class="albutton alorange" type="button" onclick="if ($('#mail').val().length>0) location.href='<?=SITE_URL.'wishlist/show_byemail/'?>'+$('#mail').val();"><span><span><span class="forward">Просмотр списка</span></span></span></button>

</div>