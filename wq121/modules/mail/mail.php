<?php
$btns = array('Почта'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=mail\''));
ob_start();
echo '<div>';
echo button1('Admin', "window.open('modules/mail/pa.php?pma=adm','',''); return false");
echo button1('Info', "window.open('modules/mail/pa.php?pma=inf','',''); return false");
echo button1('Partner', "window.open('modules/mail/pa.php?pma=prt','',''); return false");
echo button1('Zakaz', "window.open('modules/mail/pa.php?pma=zak','',''); return false");
echo button1('Landshaft', "window.open('modules/mail/pa.php?pma=lnd','',''); return false");
echo button1('Import', "window.open('modules/mail/pa.php?pma=imp','',''); return false");
echo button1('Bank', "window.open('modules/mail/pa.php?pma=bnk','',''); return false");
echo button1('Reklama', "window.open('modules/mail/pa.php?pma=rek','',''); return false");
echo button1('Вookkeeper', "window.open('modules/mail/pa.php?pma=buh','',''); return false");
echo '<br></div>';
$module['html'] .= ob_get_contents();
$module['path']="Почтовые ящики";
ob_end_clean();
?>
