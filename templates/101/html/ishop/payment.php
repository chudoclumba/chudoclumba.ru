<p style="color:red;font-weight: bold;font-size:16px;">��������, �� ������ �������� �� ����������� ������ �������� "�����", "��������" !</p>
<?php
$inf=unserialize(User::gI()->user['info']);
if ($vid>0){?>
<p>������ � ��������� ������� ��� ��� ����������� ����� ����� ������ � ��������� �������: <?=$ordernumber?></p>
<p>����� ������ � ������ �������� ������� �������: <?=$orderid?></p>
<?}?>
<p>����������, ��������� ���� �������� ������. ����� ������� �� ������ �������� �� ������ �������������� �� �������� ��������� ������� <a href="http://www.assist.ru">
 <img src="http://www.assist.ru/images/assist_logo.gif" width="155" height="49" border="0" alt="�������
 ����������� ��������">
 </a></p>
<FORM ACTION=" https://payments223.paysecure.ru/pay/order.cfm" METHOD="POST" accept-charset="UTF-8">
<INPUT TYPE="HIDDEN" NAME="Merchant_ID" VALUE="643835">
<INPUT TYPE="HIDDEN" NAME="OrderNumber" VALUE="<?=$ordernumber?>">
<INPUT TYPE="HIDDEN" NAME="OrderAmount" VALUE="<?=$orderamount?>">
<table>
<tr><td align="right">����� �</td><td align="left"><?=$ordernumber?></td></tr>
<tr><td align="right">����� �������</td><td align="left"><?=$orderamount?></td></tr>
<tr><td align="left" colspan="2">����������:</td></tr>
<tr><td align="right">�������:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="Firstname" VALUE="<?=$inf[9]?>"></td></tr>
<tr><td align="right">���:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="Lastname" VALUE="<?=$inf[8]?>"></td></tr>
<tr><td align="right">��������:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="Middlename" VALUE="<?=$inf[11]?>"></td></tr>
<tr><td align="right">E-mail:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="Email" VALUE="<?=User::gI()->user['login']?>"></td></tr>
</table>
<INPUT TYPE="HIDDEN" NAME="TestMode" VALUE="0">
<INPUT TYPE="HIDDEN" NAME="URL_RETURN_OK" VALUE="<?=SITE_URL.'service/payok'?>">
<INPUT TYPE="HIDDEN" NAME="URL_RETURN_NO" VALUE="<?=SITE_URL.'service/payno'?>">
<INPUT TYPE="HIDDEN" NAME="URL_RETURN" VALUE="<?=SITE_URL?>">
<INPUT TYPE="SUBMIT" NAME="Submit" VALUE="��������">
</FORM>
