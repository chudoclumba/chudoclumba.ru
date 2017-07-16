<?

session_start();
include_once "../../../../includes/kanfih.php";
//require_once('backup.php');

require INC_DIR.'dbconnect.php';
require INC_DIR.'functions.php';

$site = Site::gI();

$sets = $site->GetSettings();
$ebox = $site->GetEditBoxes(array_keys($edit_boxes));

define('DEF_ID', $ebox['id_main']);

	if(isset($_GET['id']))
	{
		if($_GET['id'] == '0')
		{
			if(file_exists(ROOT_DIR.'modules/sitemenu.php')) echo '<li class="closed" id="site_0"><a onclick="insert_link(\'/\')" href="#" href2="/"><ins>&nbsp;</ins>Меню сайта</a></li>';
			if(file_exists(ROOT_DIR.'modules/ishop.php')) echo '<li class="closed" id="ishop_0"><a onclick="insert_link(\'ishop/0\')" href="#"  href2="ishop/0"><ins>&nbsp;</ins>Интернет магазин</a></li>';
			if(file_exists(ROOT_DIR.'modules/news.php')) echo '<li class="closed" id="news_0"><a onclick="insert_link(\'news/page/1\')" href="#"  href2="news/page/1"><ins>&nbsp;</ins>Новости</a></li>';
		}
		else
		{
			$inf = explode('_', $_GET['id']);
			$cat_id= $inf['1'];
			
			if($inf['0'] == 'site')
			{
				
				$rows = $db->get_rows("SELECT * FROM ".TABLE_SITEMENU." WHERE pid=".$cat_id." && deleted = 0");
				foreach($rows as $id => $val)
				{
					$g = $db->get_rows("SELECT count(*) as cnt FROM ".TABLE_SITEMENU." WHERE pid = ".$val['id']." && deleted = 0");
					$class = ($g['0']['cnt'] > 0) ?  'class="closed"' : '';
					
					$link = 'site/'.$val['id'];
					
					echo '<li id="site_'.$val['id'].'" '.$class.'><a onclick="insert_link(\''.$link.'\')" href="#"  href2="'.$link.'"><ins>&nbsp;</ins>'.$val['title'].'</a></li>';
				}
			}
			
			if($inf['0'] == 'ishop')
			{
				$rows = $db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." WHERE parent_id=".$cat_id."");
				$rows2 = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE cat_id=".$cat_id."");
				foreach($rows as $id => $val)
				{
					$g = $db->get_rows("SELECT count(*) as cnt FROM ".TABLE_CATEGORIES." WHERE parent_id = ".$val['id']."");
					$g2 = $db->get_rows("SELECT count(*) as cnt FROM ".TABLE_PRODUCTS." WHERE cat_id = ".$val['id']."");
					$class = (($g['0']['cnt'] + $g2['0']['cnt']) > 0) ?  'class="closed"' : '';
					
					$link = 'ishop/'.$val['id'];
					
					echo '<li id="ishop_'.$val['id'].'" '.$class.'><a onclick="insert_link(\''.$link.'\')" href="'.$link.'"><ins>&nbsp;</ins>'.$val['title'].'</a></li>';
				}
				
				foreach($rows2 as $id => $val)
				{
					$link = 'ishop/product/'.$val['id'];
					echo '<li id="product_'.$val['id'].'"><a onclick="insert_link(\''.$link.'\')"  href="#"  href2="ishop/product/'.$val['id'].'"><ins>&nbsp;</ins>'.$val['title'].'</a></li>';
				}
				
			}
			
			if($inf['0'] == 'news')
			{
				$rows = $db->get_rows("SELECT * FROM ".TABLE_NEWS."");
				
				
				foreach($rows as $id => $val)
				{
				
					$link = 'news/'.$val['id'];
					echo '<li id="ishop_'.$val['id'].'"><a onclick="insert_link(\''.$link.'\')" href="#"  href2="news/'.$val['id'].'"><ins>&nbsp;</ins>'.$val['t'].'</a></li>';
				}
			}
		}
		exit;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#razdelmanager.dialog_title}</title>
	<script type="text/javascript" src="<?=SITE_URL?>jscripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/jstree2/jquery.tree.min.js"></script>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
</head>
<body id="razdelmanager"  style="display: none">
<div id="async_html_2">
<ul class="ltr"><li></li></ul>
</div>
<script language="javascript" type="text/javascript">
$("#async_html_2").tree({
	data : {
		async : true,
		opts : {
			url : "dialog.php"
		}
	},
	callback : {
		onselect : function(c,v){
			parent.s($(c).children('a').attr('href2'));
			tinyMCEPopup.close();
			return false;
		}
	}
});

function insert_link(link)
{
	parent.test(link);
	tinyMCEPopup.close();
}
</script>
</body>
</html>