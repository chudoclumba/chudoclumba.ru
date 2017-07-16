<form method="post" action="" >
<b>Подписка на новости о распродажах</b> 
<br/>
<? if(!empty($_SESSION['msg'])) { ?>
<p><?=$_SESSION['msg'];?></p>
<?
unset($_SESSION['msg']);
 } ?>

<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tbody>
    <tr><td>Имя:<font color="red"><span class="form-required starrequired">*</span></font></td>
	<td width="100%"><input type="text" size="0" value="" name="name" class="inputtext"/></td></tr>
  
    <tr><td>Город:<font color="red"><span class="form-required starrequired">*</span></font></td>
	<td><input type="text" size="0" value="" name="town" class="inputtext"/></td></tr>
  
    <tr><td>Email:<font color="red"><span class="form-required starrequired">*</span></font></td>
	<td><input type="text" size="0" value="" name="email" class="inputtext"/></td></tr>
  
    <tr><td>Телефон:</td><td><input type="text" size="0" value="" name="phone" class="inputtext"/></td></tr>
  
    <tr><td/><td><input type="submit" value="Отправить" name="podpiska"/><br/></td></tr>
  </tbody>
</table>
</form>