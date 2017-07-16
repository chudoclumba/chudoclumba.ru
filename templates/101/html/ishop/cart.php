<div class="shopping-cart expand"><a href="ishop/cart"><span>Корзина</span>&nbsp;<span id="crt_inf">(<?=Cart::gI()->cart_cnt?>)</span></a>
<div class="restrain small-cart-content"><?
 if (isset($_SESSION['cartid']) && isset($_SESSION['user']) && $_SESSION['user']>0 && Cart::gI()->get_cart_user($_SESSION['cartid'])==$_SESSION['user']) $tc='<p>Персональная корзина</p>'; else $tc='';
echo $tc;
if (Cart::gI()->cart_cnt>0){
?><p class="total pr10">Товаров: <span class="amount"><?= Cart::gi()->cart_cnt?></span></p>
<p class="total">На сумму: <span class="amount"><?= $this->s_price_c(Cart::gi()->cart_sum,0)?></span></p>
<p class="buttons"><a href="ishop/cart" class="button">Перейти в корзину</a></p><?
} else { 
?><p class="total pr10">Ваша корзина еще пуста <span class="amount"></span></p><p class="total"><span class="amount"></span></p><?
}?></div></div>

