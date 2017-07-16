<?if(!empty($_SESSION['user'])) {?>
<a href="user/logout">Выход</a>
<?} else {?>
<form method="post" action=""><table>
 <tr>
  <td>Логин</td>
  <td><input class="fbinps" type="text" name="login" /></td>
 </tr>
 <tr>
  <td>Пароль</td>
  <td><input class="fbinps" type="pass" name="pass" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><input class="fbbs" type="submit" name="send" value="Войти" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><a href="user/registr">Регистрация</a><br>
  <a href="user/restore">Забыли пароль?</a>
  </td>
 </tr>
</table>
</form>
<?}?>