<?php
if ($_GET['pma']=='adm') {
	$nm='admin';
	$ps="ast08268bkf";
} else 
if ($_GET['pma']=='inf') {
	$nm='info';
	$ps="ast08268bkf";
} else 
if ($_GET['pma']=='zak') {
	$nm='zakaz';
	$ps="ast08268bkf";
} else 
if ($_GET['pma']=='bnk') {
	$nm='bank';
	$ps="ast08268bkf";
} else 
if ($_GET['pma']=='imp') {
	$nm='import';
	$ps="ast08268bkf";
} else 
if ($_GET['pma']=='rek') {
	$nm='reklama';
	$ps="ast08268bkf";
} else 
if ($_GET['pma']=='lnd') {
	$nm='landshaft';
	$ps="ast08268bkf";
} else 
if ($_GET['pma']=='prt') {
	$nm='partner';
	$ps="ast08268bkf";
} else die();
?>
<form name="ddd" method="post" action="https://passport.yandex.ru/for/chudoclumba.ru?mode=auth"> 
<input type="hidden" name="login" value="<?=$nm?>" tabindex="1"/>
<input type="hidden" name="retpath" value="http://mail.yandex.ru/for/chudoclumba.ru">
<input type="hidden" name="passwd" value="<?=$ps?>" maxlength="100" tabindex="2"/> <br>
<input type="hidden" name="twoweeks" id="a" value="no" tabindex="4"/>
</form>
<script type="text/javascript">
setTimeout('document.forms.ddd.submit()', 10)
</script>
