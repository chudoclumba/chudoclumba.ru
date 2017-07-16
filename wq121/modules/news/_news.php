<?php
//print_r(array($prelink,$_SERVER));
if (isSet($_POST['save']) && isSet($_GET['eID']) || isset($_POST['save_send']))
{
	$time = explode('-', htmlspecialchars($_POST['cdate'],ENT_COMPAT | ENT_XHTML,'utf-8'));
	$params = array (
		'cdate' => mktime(0,0,0,$time[1],$time[0],$time[2]),
		't' => $_POST['title'],
		'txt' => $_POST['html_file']
	);
	
	if(News::gI()->news_photo) $params['photo'] = $_POST['photo'];

	if ($_GET['eID'] > 0)
	{
		$db->update(TABLE_NEWS,$_GET['eID'],$params);
	}
	else 
	{
		$db->insert(TABLE_NEWS,$params);
	}
	
	if(isset($_POST['save_send']))
	{
		$module['html'] .= '<div style="font-weight:bold; font-size:14px; padding:10px;">Отправлено</div>';
		$users = $db->get_rows("SELECT email FROM ".TABLE_PODPISKA." WHERE `active`='1'");
		
		foreach($users as $user)
		{
			$content = '<div style="padding:3px 0px 3px 0px; font-weight:bold; color:#666;">'.$_POST['cdate'].'</div><div style="padding:3px 0px; color:#000; font-weight:bold;">'.$_POST['title'].'</div><div style="padding:3px 0px;">'.$_POST['html_file'].'</div>';
			sendmail_a($user['email'], 'Рассылка новостей', $content);
		}
		
		
	}
	else
	{
		header("Location:include.php?place=".$global_place."");
	}
	
}

$q = $db->query("SELECT * FROM ".TABLE_NEWS." WHERE id = '".$_GET['eID']."'");
$sel = $db->fetch_array($q);








$module['html'] .= '
<table class="w100">
 <tr valign="top">
  <td class="news_content text" >
   <form action="" method="post" enctype="multipart/form-data">
   	Дата: <input id="date_n" type="text" value="'.(($_GET['eID'] > 0)? date('d-m-Y',$sel['cdate']) : date('d-m-Y',time())).'" name="cdate" /><br><br>
   	Заголовок: <input type="text" style="width:80%;" name="title" value="'.htmlspecialchars($sel['t'],ENT_COMPAT | ENT_XHTML,'utf-8').'" /><br><br>';
	if(News::gI()->show_photo())
	{
		$module['html'] .=	'<img align="left" style="margin:0px 20px 5px 5px" id="foto_main" alt="" src="'.SITE_URL.'thumb.php?id='.htmlspecialchars($sel['photo'],ENT_COMPAT | ENT_XHTML,'utf-8').'&x=100&y=100" onclick="return hs.expand(this, { src: \''.SITE_URL.'\'+$(\'#url_abs_nohost\').val() } )"/>';
		$module['html'] .= 'Фото:<br><input type="text" onchange="document.getElementById(\'foto_main\').src = \''.SITE_URL.'thumb.php?id=\' + document.getElementById(\'url_abs_nohost\').value + \'&x=100&y=100\'" name="photo" value="'.$sel['photo'].'" id="url_abs_nohost" style="width:400px" /><br><br><a href="javascript:;" onclick="mcImageManager.browse({fields : \'url_abs_nohost\', relative_urls : true, document_base_url : \''.SITE_URL.'\',use_url_path : true,url:$(\'#url_abs_nohost\').val()});">Выбрать файл</a>
		<div style="clear:both"></div>';
	}
	$module['html'] .= 'Текст новости:<br><textarea class="mceEditor" rows="15" cols="10" name="html_file" style="height:500px; width:90%;" id="editor1" >'.htmlspecialchars($sel['txt'],ENT_COMPAT | ENT_XHTML,'utf-8').'</textarea><br>
	<script>	
tinyMCEInit(\'editor1\');</script>
	'.
button1('Сохранить изменения','','type="submit" name="save" value="Сохранить"','save').button1('Отменить',"history.back(1)",'','cancel').'</form></td></tr></table>';