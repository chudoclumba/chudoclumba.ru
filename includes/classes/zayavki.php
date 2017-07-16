<?
function replace_cyr($path){
$search = array ("'Ё'", "'А'", "'Б'", "'В'", "'Г'", "'Д'", "'Е'", "'Ж'", "'З'", "'И'", "'Й'", "'К'", "'Л'", "'М'", "'Н'", "'О'", "'П'", "'Р'", "'С'", "'Т'", "'У'", "'Ф'", "'Х'", "'Ц'", "'Ч'", "'Ш'", "'Щ'", "'Ъ'", "'Ы'", "'Ь'", "'Э'", "'Ю'", "'Я'", "'а'", "'б'", "'в'", "'г'", "'д'", "'е'", "'ж'", "'з'", "'и'", "'й'", "'к'", "'л'", "'м'", "'н'", "'о'", "'п'", "'р'", "'с'", "'т'", "'у'", "'ф'", "'х'", "'ц'", "'ч'", "'ш'", "'щ'", "'ъ'", "'ы'", "'ь'", "'э'", "'ю'", "'я'", "'ё'", "'0'e");
$replace= array ('&#1025;', '&#1040;', '&#1041;', '&#1042;', '&#1043;', '&#1044;', '&#1045;', '&#1046;', '&#1047;', '&#1048;', '&#1049;', '&#1050;', '&#1051;', '&#1052;', '&#1053;', '&#1054;', '&#1055;', '&#1056;', '&#1057;', '&#1058;', '&#1059;', '&#1060;', '&#1061;', '&#1062;', '&#1063;', '&#1064;', '&#1065;', '&#1066;', '&#1067;', '&#1068;', '&#1069;', '&#1070;', '&#1071;', '&#1072;', '&#1073;', '&#1074;', '&#1075;', '&#1076;', '&#1077;', '&#1078;', '&#1079;', '&#1080;', '&#1081;', '&#1082;', '&#1083;', '&#1084;', '&#1085;', '&#1086;', '&#1087;', '&#1088;', '&#1089;', '&#1090;', '&#1091;', '&#1092;', '&#1093;', '&#1094;', '&#1095;', '&#1096;', '&#1097;', '&#1098;', '&#1099;', '&#1100;', '&#1101;', '&#1102;', '&#1103;', '&#1105;', '0');
return   preg_replace ($search,$replace,$path);
} 


function downloadFile($filename, $mimetype='application/octet-stream', $new_name = 'downloaded.txt') {
	if (!file_exists($filename)) die('Файл не найден');

	$from=$to=0; $cr=NULL;

	if (isset($_SERVER['HTTP_RANGE'])) {
		$range=substr($_SERVER['HTTP_RANGE'], strpos($_SERVER['HTTP_RANGE'], '=')+1);
		$from=strtok($range, '-');
		$to=strtok('/'); if ($to>0) $to++;
		if ($to) $to-=$from;
		header('HTTP/1.1 206 Partial Content');
		$cr='Content-Range: bytes ' . $from . '-' . (($to)?($to . '/' . $to+1):filesize($filename));
	} else	header('HTTP/1.1 200 Ok');

	$etag=md5($filename);
	$etag=substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
	header('ETag: "' . $etag . '"');

	header('Accept-Ranges: bytes');
	header('Content-Length: ' . (filesize($filename)-$to+$from));
	if ($cr) header($cr);

	header('Connection: close');
	header('Content-Type: ' . $mimetype);
	header('Last-Modified: ' . gmdate('r', filemtime($filename)));
	$f=fopen($filename, 'r');
	header('Content-Disposition: attachment; filename="'.$new_name.'";');
	if ($from) fseek($f, $from, SEEK_SET);
	if (!isset($to) or empty($to)) {
		$size=filesize($filename)-$from;
	} else {
		$size=$to;
	}
	$downloaded=0;
	while(!feof($f) and !connection_status() and ($downloaded<$size)) {
		echo fread($f, 512000);
		$downloaded+=512000;
		flush();
	}
	fclose($f);
}


class Zayavki
{
	static function download()
	{
		$content = '';
		if(User::gi()->is_logged())
		{
			$db = Site::gI()->db;
			$user_info = User::gI()->getInfo();

			$rows = $db->get_rows("SELECT * FROM ".TABLE_ZAYAVKI." WHERE user_id = ".quote_smart($user_info['id'])." ORDER BY date DESC LIMIT 3");
			
			$c_z = array();
			$c_z_list = array();
			
			$rows_t = $db->get_rows("SELECT * FROM ".TABLE_ZAYAVKI." WHERE user_id = ".quote_smart($user_info['id'])." && id = ".quote_smart($_GET['id'])."");
			$rows_t_list = $db->get_rows("SELECT * FROM ".TABLE_ZAYAVKI_LIST." WHERE parent_id = ".quote_smart($_GET['id'])."");
			if(count($rows_t) > 0) $c_z = $rows_t['0'];
			if(count($rows_t_list) > 0) $c_z_list = $rows_t_list;


			$data = file_get_contents(ROOT_DIR.'blank2.xls');
			$data = str_replace('ZAKAZCHIK', replace_cyr($c_z['zakazchik']), $data);
			$data = str_replace('KONTAKTNOELICO', replace_cyr($c_z['contlico']), $data);
			$data = str_replace('GRUZOOTPRAVITEL', replace_cyr($c_z['grusootpravitel']), $data);
			$data = str_replace('DATAOTPRAVKI', replace_cyr($c_z['data']), $data);
			$data = str_replace('MESTOPOGRUSKI', replace_cyr($c_z['mesto']), $data);

			$row = '';
			
			foreach($rows_t_list as $id=>$val)
			{
				$row .= '<tr height=3D17 style=3D\'height:12.75pt\'>
				  <td height=3D17 class=3Dxl71 align=3Dright style=3D\'height:12.75pt\'>'.($id+1).'</td>
				  <td class=3Dxl72>'.$val['town'].'</td>
				  <td class=3Dxl72>'.$val['adr'].'</td>
				  <td colspan=3D3 class=3Dxl99 style=3D\'border-right:.5pt solid black\'>'.$val['kolvo'].'</td>
				  <td class=3Dxl72>'.$val['temp'].'</td>
				 </tr>';
			}
			
			$data = str_replace('ROWSNEW', replace_cyr($row), $data);

			$tmpfname = tempnam("cache", "zayavka");
			$handle = fopen($tmpfname, "w");
			fwrite($handle, $data);
			fclose($handle);

			downloadFile($tmpfname, 'application/octet-stream', 'Заявка №'.$_GET['id'].'.xls');

			unlink($tmpfname);
		}
		exit;
	}

	static function get()
	{
		$content = '';
		if(User::gi()->is_logged())
		{
			$db = Site::gI()->db;
			$user_info = User::gI()->getInfo();
		
			if(!empty($_POST['z_add']))
			{
				Zayavki::save();
			}
		

			$rows = $db->get_rows("SELECT * FROM ".TABLE_ZAYAVKI." WHERE user_id = ".quote_smart($user_info['id'])." ORDER BY date DESC LIMIT 3");
			
			$c_z = array();
			$c_z_list = array();
			
			$rows_t = $db->get_rows("SELECT * FROM ".TABLE_ZAYAVKI." WHERE user_id = ".quote_smart($user_info['id'])." && id = ".quote_smart($_GET['id'])."");
			$rows_t_list = $db->get_rows("SELECT * FROM ".TABLE_ZAYAVKI_LIST." WHERE parent_id = ".quote_smart($_GET['id'])."");
			if(count($rows_t) > 0) $c_z = $rows_t['0'];
			if(count($rows_t_list) > 0) $c_z_list = $rows_t_list;

			$content = Site::gI()->view('reg/zayavki', array('zayavki' => $rows, 'inf' => $c_z, 'inf_list' => $c_z_list));
		}
		return $content;
	}
	
	static function save()
	{
		$db = Site::gI()->db;
		$user_info = User::gI()->getInfo();
		
		$db->insert(TABLE_ZAYAVKI, array(
			'user_id' => $user_info['id'],
			'zakazchik' => $_POST['g1'],
			'contlico' => $_POST['g2'],
			'grusootpravitel' => $_POST['g3'],
			'data' => $_POST['g4'],
			'mesto' => $_POST['g5'],
			'text' => $_POST['text'],
			'status' => '',
			'date' => time(),
			'modify_date' => 0
		));
		
		$last_id = $db->insert_id();
		
		foreach($_POST['h'] as $id=>$val)
		{
			if(!empty($val['0']) || !empty($val['1']) || !empty($val['2']) || !empty($val['3']))
			{
				$db->insert(TABLE_ZAYAVKI_LIST, array(
					'town' => $val['0'],
					'adr' => $val['1'],
					'kolvo' => $val['2'],
					'temp' => $val['3'],
					'parent_id' => $last_id
				));
			}
		}

		send_mail(
			"admin@".SITE_NAME, '"Система Управления '.SITE_NAME.'"',
			Site::gI()->ebox['email'], Site::gI()->ebox['email'],
			'',
			'',
			$_SERVER['HTTP_HOST']." - ".' новая заявка №'.$last_id,
			' 
		<html> 
			<body> 
				<p>'.nl2br(htmlspecialchars($_POST['text'])).'</p>
			</body> 
		</html>'
		);
		
		User::gi()->redirect($_SERVER['HTTP_REFERER']);
	}
	
	static function get_arh()
	{
		$content = '';
		if(User::gi()->is_logged())
		{
			$db = Site::gI()->db;
			$user_info = User::gI()->getInfo();
		
			if(!empty($_POST['z_add']))
			{
				Zayavki::save();
			}

			$rows = $db->get_rows("SELECT * FROM ".TABLE_ZAYAVKI." WHERE user_id = ".quote_smart($user_info['id'])." ORDER BY date DESC");
			$content = Site::gI()->view('reg/zayavki', array('zayavki' => $rows, 'status'=>array('0'=>'В обработке', '1'=>'Выполнен', '2'=>'Не выполнен')));
		}
		return $content;
	}	
}
?>