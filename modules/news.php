<?php
if(!class_exists('Site')) die(include '../404.html');

class News extends Site
{

	public $news_descr = 'подробнее >>';
	public $nowrap_descr = 1;
	public $news_photo = 1;
	

	private static $instance;

	public static function gI() {
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {
		$this->db = Site::gI()->db;
	}

	private function __clone() {}

	function show_photo()
	{
		return $this->news_photo;
	}		
	function menu($count = 2, $show_title = 0, $width = 196)
	{
		$news = $this->db->get_rows("select * from ".TABLE_NEWS." order by `cdate` DESC, id DESC LIMIT ".$count."");

		$news_menu = '';
		if (count($news) > 0)
		{
			$news_menu=$this->view('news/news_main', array('news' => $news,'width' => $width));
			$news_menu .= '<div id="news_all"><a href="news/page/1">Архив новостей</a></div>';
		}
		return $news_menu;
	}
	
	function news_descr($value)
	{
		$this->news_descr = $value;
	}
	
	function get_news($id = 0)
	{
		$art = $this->db->get_rows("SELECT * FROM ".TABLE_NEWS." WHERE id=".intval($id)."");
		if(count($art) > 0)
		{
			$art =$art['0'];
			$content = array (
				'meta_title' => htmlspecialchars($art['t'],ENT_COMPAT | ENT_XHTML,'UTF-8'),
				'meta_keys' => htmlspecialchars($art['t'],ENT_COMPAT | ENT_XHTML,'UTF-8'),
				'meta_desc' => htmlspecialchars($art['t'],ENT_COMPAT | ENT_XHTML,'UTF-8'),
				'path' =>'<a href="javascript:history.back(1)">Новости</a> » '.htmlspecialchars($art['t'],ENT_COMPAT | ENT_XHTML,'UTF-8').'&nbsp;'
			);
//			if ($_SERVER['REMOTE_ADDR']=='79.120.75.222') print_r($page);
			$content['html'] = $this->view('news/news', array('art' => $art));

		}
		else
		{
            error_log("Call 404 from news.php: 67");
			echo $this->_404();
		}
		return $content;
	}
	
	function news_list($page = 1)
	{
		ob_start();

		define('NEWS_PAGES', 10);
		
		$arts_cnt = $this->db->get_rows("SELECT count(id) as cnt FROM ".TABLE_NEWS."");
		$arts_cnt = $arts_cnt['0']['cnt'];
		$c_pages = ceil($arts_cnt/NEWS_PAGES);
		
		if(!is_numeric($page) || $page < 0 || $page > $c_pages)
		{
            error_log("Call 404 from news.php: 85");
			echo $this->_404();
		}
		else
		{
			$content = array (
				'meta_title' => 'Новости',
				'meta_keys' => 'Новости',
				'meta_desc' => 'Новости',
				'path' => 'Новости',
				'left_menu'=>FALSE
			);	
			
			
			$limit = "";
			if($page != 0) $limit = " LIMIT ".(($page-1)*NEWS_PAGES).",".NEWS_PAGES."";
			
			$arts = $this->db->get_rows("SELECT * FROM ".TABLE_NEWS." ORDER BY cdate DESC, id DESC".$limit."");
			
			$content['html'] = $this->view('news/news_list', array('arts' => $arts, 'c_pages'=>$c_pages, 'page'=>$page));
		}
	
		return $content;
	}
	
	function fsitemap()
	{
		$news = '';
		$news_list = $this->db->get_rows("select id,t from ".TABLE_NEWS." order by `cdate` DESC, id DESC");
		
		if(count($news_list) > 0)
		{
			$news .= '<h3>Новости</h3><ul>';
			foreach($news_list as $id=>$val)
			{
				$news .= '<li><a href="news/'.$val['id'].'">'.$val['t'].'</a></li>';
			}
			$news .= '</ul>';
		}
		
		return $news;
	}
	
	function flinks()
	{
		$news = '';
		$news_list = $this->db->get_rows("select id,t from ".TABLE_NEWS." order by `cdate` DESC, id DESC");
		
		if(count($news_list) > 0)
		{
			foreach($news_list as $id=>$val)
			{
				$news .= '<url>';
				$news .= '<loc>'.SITE_URL.'news/'.$val['id'].'</loc>';
				//$news .= '<lastmod>2005-01-01</lastmod>';
				//$news .= '<changefreq>monthly</changefreq>';
				//$news .= '<priority>0.8</priority>';
				$news .= '</url>'."\n";
			}
		}
		
		return $news;
	}
}
if (!empty($_GET['module']) && $_GET['module'] == 'news')
{
	if(!empty($_GET['type']) && $_GET['type'] == 'all')
	{
		$id=(isset($_GET['id']))?$_GET['id']:1;
		$content = News::gI()->news_list($id);
	
	}
	else
	{
		$content = News::gI()->get_news($_GET['id']);
	}
}