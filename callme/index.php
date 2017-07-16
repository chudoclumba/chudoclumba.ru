<?php
session_start();
require_once "../includes/kanfih.php";
require INC_DIR.'dbconnect.php';
require_once "../includes/functions.php";
require_once "../includes/site.class.php";
require_once INC_DIR.'PHPMailer/class.phpmailer.php';
$site      = Site::gI();
$sets      = $site->GetSettings();
$ebox      = $site->GetEditBoxes(array_keys($edit_boxes));

header("Content-Type: text/html; charset=UTF-8"); //charset

//адрес почты для отправки уведомления
$to        = "admin@chudoclumba.ru"; //несколько ящиков могут перечисляться через запятую
$from      = "noreply@".($_SERVER["HTTP_HOST"]); //адрес, от которого придёт уведомление, можно не трогать

// данные для отправки смс

$id        = "";
$key       = "";
$sms_login = "";
$sms_pass  = "";
$frm       = "callme"; // добавьте новую подпись в смс - шлюзе и дождитесь апрува
$num       = ""; // ваш номер в формате без + (f.e. 380501112233 или 79218886622)
$prv       = ""; // на выбор: sms.ru, infosmska.ru, bytehand.com, sms - sending.ru, smsaero.ru

function uc($s)
{
	$s = urlencode($s);
	return $s;
}

function gf($s)
{
	// no shit
	$s = substr((htmlspecialchars($_GET[$s],ENT_COMPAT | ENT_XHTML,'UTF-8')), 0 , 500);
	if(strlen($s) > 1) return $s;
}

function sendSMS($to, $msg)
{
	global $id;
	global $key;
	global $from;
	global $frm;
	global $num;
	global $prv;
	global $sms_login;
	global $sms_pass;

	$u['sms.ru'] = "sms.ru/sms/send?api_id=".uc($key)."&to=".uc($num)."&text=".uc($msg);
	$u['bytehand.com'] = "bytehand.com:3800/send?id=".uc($id)."&key=".uc($key)."&to=".uc($num)."&partner=callme&from=".uc($frm)."&text=".uc($msg);
	$u['sms-sending.ru'] = "lcab.sms-sending.ru/lcabApi/sendSms.php?login=".uc($sms_login)."&password=".uc($sms_pass)."&txt=".uc($msg)."&to=".uc($num);
	$u['infosmska.ru'] = "api.infosmska.ru/interfaces/SendMessages.ashx?login=".uc($sms_login)."&pwd=".uc($sms_pass)."&sender=SMS&phones=".uc($num)."&message=".uc($msg);
	$u['smsaero.ru'] = "gate.smsaero.ru/send/?user=".uc($sms_login)."&password=".md5(uc($sms_pass))."&to=".uc($num)."&text=".uc($msg)."&from=".uc($frm);

	$r = file_get_contents("http://".$u[$prv]);
	//echo " < br > ".$u[$prv]."\n\n".$r;
}

function translit($str)
{
	$tr = array("А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I","Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j","з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y","ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya");
	return strtr($str,$tr);
}
// translit * ProgrammerZ.Ru

//далее можно не трогать

$time     = time(); // время отправки
$interval = $time - $_GET['ctime'];
$phone = gf("cphone");
$ph=str_replace(" ","",$phone);
$ph=str_replace("-","",$ph);
$ph=str_replace("+7","",$ph);
$ph=str_replace("(","",$ph);
$ph=str_replace(")","",$ph);
//$ph       = "9266642484";
if($interval < 60*30)
{
	// если прошло менее получаса, указано в секундах
	$result = "error";
	$cls    = "c_error";
	$time   = "";
	$message= "Сообщение уже было отправлено.";
}
else
{
if ((strlen($_GET['cname'])>2)&&((strlen($_GET['cphone'])>5))&& (strlen($ph) == 10)){

//	if((strlen($_GET['cname']) > 2) && ((strlen($_GET['cphone']) > 5)) && (strlen($ph) = 10))
	
		$ip      = $_SERVER['REMOTE_ADDR']; //что будем отправлять
		$phone   = gf("cphone");
		$name    = gf("cname");
		$comment = gf("ccmnt");
		$url     = gf("url");
		$data= array("data"=>$time,
		"name"=>$name,
		"tel"=>$ph,
		"comment"=>$comment,
		"userid"=>$_SESSION['user']
		);
		$db->insert(TABLE_CALLBACK,$data);
		$title   = "Заказ обратного звонка";
		$mess    = "<b>Телефон</b><br>".$phone."<br><br>
		<b>Имя</b><br>".$name."<br><br>";
		if(strlen($comment) > 2)
		{
			$mess = $mess."<b>Комментарий</b><br>".$comment."<br><br>";
		}
		$mess    = $mess."<b>Отправлено со страницы</b><br>".$url."<br><br><b>ip</b><br>".$ip;
		$headers = "Content-type: text/html; charset=utf-8 \r\n";
		$headers .= "From: CallMe <".$from.">\r\n";
		$msg = "Callme:". translit($name).",".translit($phone)." ";
		$msg .= substr(translit($comment), 0, (160 - strlen($msg)));
		$issend = true;
		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure='ssl';
		$mail->Host = $sets['SMTPHost']; // SMTP server
		$mail->Port = $sets['SMTPPort'];                    // set the SMTP port for the GMAIL server
		$mail->Username = $sets['SMTPUser']; // SMTP account username
		$mail->Password = $sets['SMTPPass'];        // SMTP account password
		$mail->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
		//$mail->AddReplyTo($this->ebox['email'],'Отдел заказов');
		$mail->SetFrom($sets['SMTPUser'], "CallBack");
		$mail->AddAddress($to,"CallBack");
		$mail->Subject = $title;
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		//						$mail->MsgHTML(iconv("UTF - 8", "windows - 1251",$mess));
		$mail->MsgHTML($mess);
		@$issend = $mail->Send();
		//@mail($to, $title, $mess, $headers);
		if($issend){
			$result = "success";
			$cls    = "c_success";
			$message= "Спасибо, Вам обязательно перезвонят с 9 до 18 часов по Московскому времени!"; //сообщение об отправке
			if(($id != "") || ($key != "") || ($sms_login != ""))
			{
				@sendSMS($num, $msg);
			}
		}
		else
		{
			$result = "error";
			$cls    = "c_error";
			$time   = "";
			$message= "Спасибо, Вам обязательно перезвонят с 9 до 18 часов по Московскому времени!";
		}
	}
	else
	{
		$result = "error";
		$cls    = "c_error";
		$time   = "";
		$message= "Ошибка при заполнении полей...";
	}
}
?>{
"result": "<?php echo $result; ?>",
"cls": "<?php echo $cls; ?>",
"time": "<?php echo $time; ?>",
"message": "<?php echo $message; ?>"
}