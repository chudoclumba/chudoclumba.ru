<?
session_start();
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

include_once $_SERVER['DOCUMENT_ROOT']."/includes/kanfih.php";

include_once "../../vars.php";
include_once INC_DIR."site.class.php";
include_once SA_DIR."inc/susfunction.php";
include_once $_SERVER['DOCUMENT_ROOT']."/includes/dbconnect.php";
include_once $_SERVER['DOCUMENT_ROOT']."/includes/functions.php";

$site = Site::gI();
$sets = $site->GetSettings();
$ebox = $site->GetEditBoxes();


if (isset($_GET['value'])) $value = decode_str($_GET['value']);

//$value = iconv("UCS-2BE", "windows-1251", $_GET['value']);

$buff = '';

if($_GET['act'] == 'delete')
{
		$q = "DELETE FROM ".TABLE_PODPISKA." WHERE id='".$_GET['id']."'";
		$buff = $db->exec($q);
}
elseif ($_GET['act'] == 'create_md5')
{
	$res=$db->get_rows('select * from '.TABLE_PODPISKA);
	$cnt=0;
	foreach($res as $val){
		if (!empty($val['email']))		{
			$ii=$db->update(TABLE_PODPISKA,$val['id'],array('mkey'=>md5($val['email'])));
			if ($ii==TRUE) 	$cnt++;
		}
	}
	$buff = 'Обновлено '.$cnt.' записей';
	
}
elseif ($_GET['act'] == 'send_news')
{
	if (!isset($_POST['box']) || count($_POST['box'])<1) die('Нет сообщений для отправки');
	foreach($_POST['box'] as $id => $val){
		$news = $db->get(TABLE_NEWS,$id);
		$users = $db->get_rows("SELECT email,mkey FROM ".TABLE_PODPISKA);
		$cnt=0;
		foreach($users as $user)
		{
			$content = '<div style="padding:3px 0px 3px 0px; font-weight:bold; color:#666;">'.date('d.m.Y', $news['cdate']).'</div><div style="padding:3px 0px; color:#000; font-weight:bold;">'.$news['t'].'</div><div style="padding:3px 0px;">'.str_replace('data/','http://www.chudoclumba.ru/data/',$news['txt']).'</div>';
			$content.='<div style="padding:3px 0px; color:#000; font-weight:bold;">Для отказа от получения новостей воспользуйтесь данной ссылкой <a href="'.SITE_URL.'podpiska/unsubscribe/'.$user['mkey'].'">'.SITE_URL.'podpiska/unsubscribe/'.$user['mkey'].'</a></div>';
			$content=str_replace('href="ishop/','href="'.SITE_URL.'ishop/',$content);
			$res=sendmail_a($user['email'], $news['t'], $content);
			if ($res===true) $cnt++;
		}

	}
	$buff=$content;
	$buff.='<br/><br/>Успешно разослано '.$cnt.' подписчикам.';
}
elseif ($_GET['act'] == 'send_newstest')
{
	if (!isset($_POST['box']) || count($_POST['box'])<1) die('Нет сообщений для отправки');
	foreach($_POST['box'] as $id => $val){
		$news = $db->get(TABLE_NEWS,$id);
		$users = array();
		$users[]=array('email'=>'info@chudoclumba.ru','mkey'=>md5('info@chudoclumba.ru'));
		$users[]=array('email'=>'info@chudoclumba.ru','mkey'=>md5('info@chudoclumba.ru'));
		$users[]=array('email'=>'chudoclumba@yandex.ru','mkey'=>md5('chudoclumba@yandex.ru'));
		$cnt=0;
		foreach($users as $user)
		{
			$content = '<div style="padding:3px 0px 3px 0px; font-weight:bold; color:#666;">'.date('d.m.Y', $news['cdate']).'</div><div style="padding:3px 0px; color:#000; font-weight:bold;">'.$news['t'].'</div><div style="padding:3px 0px;">'.$news['txt'].'</div>';
			$content.='<div style="padding:3px 0px; color:#000; font-weight:bold;">Для отказа от получения новостей воспользуйтесь данной ссылкой <a href="'.SITE_URL.'podpiska/unsubscribe/'.$user['mkey'].'">'.SITE_URL.'podpiska/unsubscribe/'.$user['mkey'].'</a></div>';
			$content=str_replace('href="ishop/','href="'.SITE_URL.'ishop/',$content);
			$res=sendmail_a($user['email'], $news['t'], $content);
			if ($res===true) $cnt++;
		}

	}
	$buff=$content;
	$buff.='<br/><br/>Успешно разослано '.$cnt.' подписчикам.';
}
elseif ($_GET['act'] == 'delete_news')
{
	$q = "DELETE FROM ".TABLE_NEWS." WHERE id=".quote_smart(trim($_GET['id']));
	$buff = '0';
	if($db->exec($q) > 0) $buff = '1';
}
echo $buff;
?>