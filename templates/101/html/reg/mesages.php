<?
if(!empty($_SESSION['ok_send']))
{
	echo '<p><b>��������� �������</b></p>';
	unset($_SESSION['ok_send']);
}

?>
<p>������ ��������� - ����� �� ������ �������� ��������� ������ ������������������� ��������� �����</p>
<p><a href="user/send">�������� ���������</a></p>
<? /*<p><a href="user/search">����� � ������ ����������</a></p>*/?>
<table class="reg_messages col w100">
 <tr>
  <td>�� ����</td>
  <td>����</td>
  <td>���� ��������</td>
  <td>���� ���������</td>
  <td>���������</td>
 </tr>
<? foreach($msgs as $id=>$value) { ?> 
 <tr>
  <td><?=$value['sender']?></td>
  <td><?=$value['reader']?></td>
  <td><?=date('d.m.Y H:i', $value['date_send'])?></td>
  <td><?
  
	if(!empty($value['date_read']))
	{
		echo date('d.m.Y H:i', $value['date_read']);
	}
	else
	{
		echo '�� ���������';
	}
  
  ?></td>
  <td><a href="user/send/<?=$value['id_sender']?>">��������</a><br><div><?=$value['msg']?></div></td>
 </tr>
<? } ?> 
</table> 