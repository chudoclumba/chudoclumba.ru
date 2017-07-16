<?php
$q = $db->get(TABLE_SETTINGS,'sus_login_time');
if (count($q) > 0)
{
	$cook_health=$q['value'];
	$cook_health=$cook_health*60*60*24;
} 
else 
{
$cook_health = (60*60*24);
}
if (isSet($_GET['logout'])) 
{
	setcookie('cms_user','',time() - 3600,'/wq121/');
	unset($_SESSION['auth']);
	header("Location: ".SA_URL);
	exit;
}

//include "inc/pass.php";

$cook = !empty($_COOKIE['cms_user']) ? $_COOKIE['cms_user'] : null;
if(empty($_SESSION['auth']['role']) && !empty($cook))
{
	$db_role=null;
	$q = $db->get_rows("select name,role from chudo_susus");
	foreach($q as $rid=>$val)
	{
		if (crypt($val['name'], $cook) == $cook)
		{
			$db_role=(int)$val['role'];
			$_SESSION['auth']['user']=$val['name'];
			$_SESSION['auth']['role']=$db_role;
			setcookie('cms_user',crypt($val['name']),time()+$cook_health,'/wq121/'); 
		}
		
	}
	if ($db_role===1)
	{
		$_SESSION['auth']['type'] = 'admin';
	} 
	elseif ($db_role>1) 
	{
		$_SESSION['auth']['type'] = 'user';
	}
	else
	{
		setcookie('cms_user','',time() - 3600,'/wq121/');
		unset($_SESSION['auth']);
		header("Location: ".SA_URL."");
		exit;
	}
}

if (isset($_POST['passwd']) && isset($_POST['loginus']))
{
	$form_username = trim($_POST['loginus']);
	$form_password = trim($_POST['passwd']);
	$q = $db->query("select id,pass,role from ".TABLE_SUSUS." where `name` = ".quote_smart($form_username));
	if ($db->num_rows($q) > 0)
	{
		list($db_id,$db_pass,$db_role)=$db->fetch_array($q);
	}
	if(isset($db_role) && (crypt($form_password, $db_pass) == $db_pass))
	{
		$_SESSION['auth']['user']=$form_username;
		$_SESSION['auth']['role']=(int)$db_role;
		if(isset($_POST['remember'])) setcookie('cms_user',crypt($form_username),time()+$cook_health,'/wq121/'); 
		if ((int)$db_role==1)
		{
			$_SESSION['auth']['type'] = 'admin';
		} 
		else if ((int)$db_role>1) 
		{
			$_SESSION['auth']['type'] = 'user';
		}
	}
	else
	{
		$mail_content = ' 
		<html> 
			<body> 
				<p>Попытка входа в систему управления сайтом '.$sitename.'.</p>
				<p>IP: '.$_SERVER['REMOTE_ADDR'].'</p>
				<p>Дата: '.date('H:i:s d.m.Y').'</p>
				<p>Логин: '.$_POST['loginus'].'</p>
				<p>Пароль: '.$_POST['passwd'].'</p> 
			</body> 
		</html>'; 
		send_mail(
			"admin@".SITE_NAME, 
			'"Система Управления"',
			$tex_email2,
			$tex_email2,
			$ebox['email_su'],
			$ebox['email_su'],
			$_SERVER['HTTP_HOST']." - ".'неверные пароли',
			$mail_content
		);
	}	
}
if(empty($_SESSION['auth']['role']))
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Чудо-Клумба</title>
<base href="<?php echo SA_URL; ?>">
<style type="text/css">
html, body {height:80%;width: 100%}
body {margin:0;padding:0;text-align: center;}
#rem{position:absolute;bottom:5px;left:3px}
#box{background:url(<?php echo TEMPLATE_URL; ?>images/logomob.png) no-repeat 0px 0px #fff;position: relative;width:230px;height:90px;font-family:Tahoma;font-size:13px;padding: 10px 20px 5px 2px;line-height: 30px;text-align: right;margin: 10px 0 0 -120px;background-color:#ffffff;border:1px #AEACAC solid;-moz-border-radius:  10px;-webkit-border-radius:10px;-khtml-border-radius:10px;border-radius:10px;box-shadow: 10px 10px 5px rgba(0, 0, 0, 0.400);left:50%;margin-left: -115px}
input {background:#ffe7d5;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;border:1px solid #E19436;font-family:Tahoma;font-size:12px;font-weight:400;padding: 2px 5px }
input:focus{background:#fff}
#submit {position:absolute;bottom:5px;right:20px;}
#submit input {background:url(<?php echo TEMPLATE_URL; ?>images/z1btn.jpg);border:0px;width:132px;height:21px;font-family:Tahoma;font-size:12px;color:#ffffff;}</style>
</head>
<body><div id="box"><form action="./" name="login" method="post" autocomplete="off">
Логин:<input type="text" name="loginus" size="20" maxlength="20"><br>
Пароль:<input type="password" name="passwd" size="20" maxlength="20">
<div id="rem"><input type="checkbox" name="remember"> Запомнить</div><div id="submit"><input type="submit" value="Войти в систему" name="Password_Go" class="btn_submit"></div>
</form></div></body></html>
<?php
	exit;
}