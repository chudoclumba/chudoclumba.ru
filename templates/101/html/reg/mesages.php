<?
if(!empty($_SESSION['ok_send']))
{
	echo '<p><b>Сообщение создано</b></p>';
	unset($_SESSION['ok_send']);
}

?>
<p>Личные сообщения - здесь вы можете написать сообщение любому зарегистрированному участнику сайта</p>
<p><a href="user/send">Написать сообщение</a></p>
<? /*<p><a href="user/search">Поиск в личных сообщениях</a></p>*/?>
<table class="reg_messages col w100">
 <tr>
  <td>От кого</td>
  <td>Кому</td>
  <td>Дата отправки</td>
  <td>Дата прочтения</td>
  <td>Сообщение</td>
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
		echo 'Не прочитано';
	}
  
  ?></td>
  <td><a href="user/send/<?=$value['id_sender']?>">Ответить</a><br><div><?=$value['msg']?></div></td>
 </tr>
<? } ?> 
</table> 