<?
if (isSet($_POST['posted']))
{
	$ctm = "";
	
	$ctm .= "Время: ".date('d-m-Y H:i',time())."<br><br>";
	$ctm .= "IP: ".$_SERVER['REMOTE_ADDR']."<br>";
	$ctm .= "Фамилия Имя Отчество.: ".$_POST['fio']."<br>";
	$ctm .= "Телефон: ".$_POST['phone']."<br>";
	$ctm .= "Email: ".$_POST['email']."<br>";
	$ctm .= "Сообщение: ".$_POST['msg']."<br><br>";

	$message = ' 
	<html> 
		<body> 
			<p>Письмо из СУ '.$sitename.'.</p> 
			'.$ctm.'
		</body> 
	</html>'; 

	send_mail(
		"feedback@".$sitename_mail, 
		"feedback@".$sitename_mail,
		$tex_email,
		$tex_email,
		$_SERVER['HTTP_HOST']." - ".'письмо из СУ',
		$message
	);	
	
	$frm = '<div class="sent">Ваше письмо отправлено!</div>';
} 
else 
{

$frm = '
<form style="margin-left:10px;" method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">Фамилия Имя Отчество</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" name="fio" value=""></td></tr>
<tr><td class="fb_id">Email</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" name="email" value=""></td></tr>
<tr><td class="fb_id">Телефон</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" name="phone" value=""></td></tr>
<tr><td class="fb_id">Ваше сообщение</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" name="msg"></textarea></td></tr>
<tr><td class="fb_input" align="left"><input class="fbb" type="submit" value="Отправить"></td></tr>
</table>
<input type="hidden" name="posted" value="1">
</form>';
 }

$module['path'] = 'Форма обратной связи';
$module['html'] .= $frm;