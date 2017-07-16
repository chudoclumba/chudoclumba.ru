<form method="post" action="user/auth" id="frmpreg" enctype="multipart/form-data" autocomplete="off">
<div class="form-fields">
<p><span style="font-size: 14px; font-weight: 700; color:#ff0707">Вы не авторизованы.</span></p>
<p>Для оформления заказа желательно пройти авторизацию, или <a href="user/registr">зарегистрироватся</a>.</p>
<p>Также можно оформить заказ без авторизации, но при этом Вы не сможете отслеживать состояние своего заказа в Личном кабинете, оплачивать заказы банковской картой, не будут действовать накопительные скидки.
</p>
<p><label>E-mail:</label><? if (isset($_POST['login'])) $vv=$_POST['login']; else $vv='';?>
<input class="fbinp" type="text" value="<?=$vv?>" name="login" readonly onfocus="this.removeAttribute('readonly')" onchange="$('.send').val('Авторизоваться и оформить')"/></p>
<p><label>Пароль:</label><input class="fbinp" type="password" value="" name="pass" /></p>
<input type="hidden" name="w_oreg" value="Jk"/>
</div>
<div class="form-action">
    <input class="floatleft send" type="submit" value="Оформить без авторизации">
</div>
</form>
