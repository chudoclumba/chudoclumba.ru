<?
 if (isset($_SESSION['user']) && $_SESSION['user']>0 && !(strpos($opl,'Банковская карта')===False))  {  ?>
<div class="prdcard">
<div class="h2" style="text-align: left">Оплата заказа: <?=last_id?></div>
<p>Вы можете оплатить заказ сейчас, или сделать это позже из личного кабинета.</p>
<p>Если Вы хотите произвести частичную оплату, измените сумму.</p>	
<form action="service/payment" method="post" id="frmFeedback" enctype="multipart/form-data" accept-charset="Windows-1251">
Сумма платежа:&nbsp;<input type="text" class="fbinp" name="summa" value="<?=number_format($summacn,2,'.','')?>"/>
<input type="hidden" name="summacn" value="<?=number_format($summacn,2,'.','')?>"/>
<input type="hidden" name="orderid" value="<?=$id?>"/>	
<input type="hidden" name="userid" value="<?=$_SESSION['user']?>"/>
<button class="albutton alorange" name="send" type="submit" value="Оплатить"><span><span><span class="ok">Оплатить</span></span></span></button>
</form></div>	
<?} ?>