<? if ($_SESSION['user']>0 ) {  ?>
<p style="color: #a80000;font-size: 14px;font-weight: bold;">Внимание, платежи на сумму менее 300 руб. не принимаются системой Assist!</p>
<p>Если Вы хотите произвести частичную доплату, измените сумму.</p>	
<form action="service/payment" method="post" id="frmFeedback" enctype="multipart/form-data" accept-charset="Windows-1251">
Сумма платежа:&nbsp;<input type="text" class="fbinp" name="summa" id="summa" value="<?=number_format($summacn,2,'.','')?>"/>
<input type="hidden" name="summacn" value="<?=number_format($summacn,2,'.','')?>"/>
<input type="hidden" name="orderid" value="<?=$last_id?>"/>	
<input type="hidden" name="vid" value="<?=$vid?>"/>	
<input type="hidden" name="userid" value="<?=$_SESSION['user']?>"/>
<br><br><button class="albutton alorange" name="send" type="submit" value="Оплатить"><span><span><span class="ok">Оплатить</span></span></span></button>
</form>
<script type="text/javascript">jQuery().ready(function(){jQuery("#frmFeedback").validate({rules : {summa:{required : true, min:300.01}},
messages : {summa : {required : "<span class=\"frm_err\">Заполните поле Cумма</span>", min : "<span class=\"frm_err\">Введите число больше 300!</span>"}}});
jQuery("send").click(function(){jQuery("#frmFeedback").validate();});});</script>

<?} ?>