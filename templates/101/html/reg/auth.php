<?if(!empty($_SESSION['user'])) {?>
<a href="user/logout">�����</a>
<?} else {?>
<form method="post" action=""><table>
 <tr>
  <td>�����</td>
  <td><input class="fbinps" type="text" name="login" /></td>
 </tr>
 <tr>
  <td>������</td>
  <td><input class="fbinps" type="pass" name="pass" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><input class="fbbs" type="submit" name="send" value="�����" /></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td><a href="user/registr">�����������</a><br>
  <a href="user/restore">������ ������?</a>
  </td>
 </tr>
</table>
</form>
<?}?>