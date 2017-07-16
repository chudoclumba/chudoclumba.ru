<?php
if(!class_exists('Site')) die(include '../404.html');

class Sitemap extends Site
{
	function __construct()
	{
		$this->db = Site::gI()->db;
	}

	var $parents = array();

	function icon()
	{
		return '<div class="map_icon"><a href="map/view"><img alt="Карта сайта" src="'.TEMP_FOLDER.'images/map.png" /></a></div>';
	}
}



$sitemap = new Sitemap();

if($_GET['module'] == 'map')
{
	
	if($_GET['type'] == 'view')
	{
		$site_map = $sitemenu->sitemap();
		$ishopmap = (class_exists('Ishop')) ? $ishop->sitemap() : '';
		$newsmap = (class_exists('News')) ? News::gI()->fsitemap() : '';
		
		
		$content = array(
			'html' => $site_map.$ishopmap.$newsmap,
			'meta_title' => 'Карта сайта',
			'meta_keys' => 'Карта сайта',
			'meta_desc' => 'Карта сайта',
			'path' => 'Карта сайта'
		);
	}
	elseif($_GET['type'] == 'sitemap')
	{
		header("Content-Type: text/xml");
		?><<??>?xml version="1.0" encoding="UTF-8"?<??>>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?
echo $sitemenu->links();
if(class_exists('Ishop')) echo $ishop->links();
if(class_exists('News')) echo News::gI()->flinks();
?>
</urlset>
<?
		
		//print_r($all_links);
		exit;
	}
	elseif($_GET['type'] == 'yml'){
		ob_start();
?>
<<??>?xml version="1.0" encoding="windows-1251"?<??>>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?=date('Y-m-d H:i')?>">
<shop>
<name>Чудо-Клумба</name>
<company>ИП Сергеева Анастасия Юрьевна</company>
<url>http://www.chudoclumba.ru/</url>
<currencies>
<currency id="RUR" rate="1"/>
</currencies>
<?=(class_exists('Ishop')) ? $ishop->sitemapyml() : ''?>
<?=(class_exists('Ishop')) ? $ishop->sitemapymlprd() : ''?>
</shop>
</yml_catalog>
<?
		$ret=ob_get_contents();
		ob_end_clean();
		header("Content-Type: text/xml");
		echo $ret;
		exit;
	}
	else
	{
		$sitemap->_404();
	}
}

//echo $sitemap->get();
