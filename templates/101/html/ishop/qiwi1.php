<script type="text/javascript">
	$(function(){
		$("#ptel").mask("+7 (999) 999-9999");
	});
</script>
<div class="prdcard">
<div class="h2" style="text-align: left">Оплата заказа: <?=$id?></div>
<FORM ACTION="service/qiwipay" METHOD="POST" accept-charset="windows-1251" target="_blank">
<img src="<?=TEMP_FOLDER?>images/qiwi-logo.png" width="154" height="57" alt="QIWI Logo" style="float: left" />
<table>
<tr><td align="right">Сумма платежа</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="sum" VALUE="<?=number_format($sum,2,'.','')?>" style="width: 80px"></td></tr>
<tr><td align="right">Моб. Телефон:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="tel" VALUE="<?=$tel?>" style="width: 120px" id="ptel"></td></tr>
</table>
<INPUT TYPE="HIDDEN" NAME="txn_id" VALUE="<?=$id?>">
<INPUT TYPE="SUBMIT" NAME="Submit" VALUE="Оплатить">
</FORM></div>