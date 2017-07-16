<?php
require_once '../includes/PHPMailer/class.phpmailer.php';
$Mailer = new PHPMailer();
  $Mailer->SMTPDebug = 1;
  $Mailer->CharSet = 'UTF-8';
  $Mailer->IsSMTP();
  $Mailer->Host = 'smtp.yandex.ru';
  $Mailer->SMTPSecure = 'tls';
  $Mailer->Port = 587;
  $Mailer->SMTPAuth = true;
  $Mailer->Username = 'zakaz@chudoclumba.ru';
  $Mailer->Password = 'Bkf774ast';

  $Mailer->SetFrom('zakaz@chudoclumba.ru', 'Владимир');
  $Mailer->AddAddress('victor.kozachek@yandex.ru', 'Victor');
  $Mailer->Subject = 'Проверка отправки почты';
  $Mailer->Body = 'Тестовое сообщение.';
  $Mailer->AltBody = 'Тестовое сообщение.';

  if($Mailer->Send()) echo 'true'; else echo 'false';
