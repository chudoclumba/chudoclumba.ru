<?
if (isset($_SESSION['user']) && $_SESSION['user']>0 )  {  ?>
<div class="prdcard">
<div class="h2" style="text-align: left">Оплата заказа</div>
<p>Вы можете оплатить заказ сейчас, или сделать это позже из личного кабинета.</p>
<br/><p><a href="https://chudoclumba.ru/service/pay_a/<?=$id?>/tin" target="_blank"><img src="https://chudoclumba.ru/templates/101/images/cardpay.png" alt="Оплатить банковской картой" /></a></p>
<?} ?>