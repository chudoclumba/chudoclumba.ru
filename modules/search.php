<?php
if(!class_exists('Site')) die(include '../404.html');

class Search extends Site
{
	private $count_in_page = 20; //

	function __construct()
	{
		$this->db = Site::gI()->db;
		$this->sets = Site::gI()->sets;
	}
	
	static function form($type = 0) {
		$str = (!empty($_SESSION['bsearch_str'])) ? htmlspecialchars($_SESSION['bsearch_str'],ENT_COMPAT | ENT_XHTML,'cp1251') : '';
		$search = Site::gI()->view('search/search_form', array('type' => $type, 'str' => $str));
		return $search;
	}
	
	public function get($str)
	{
		if (!isset($_SESSION['seach_ref'])) $_SESSION['seach_ref']=$_SERVER['HTTP_REFERER'];
		$_SESSION['bsearch_str'] = trim($str);
		$vq = 0;

			$words = explode(' ', $str);
			$query_a1 = array();
			$query_a2 = array();
			$query_a3 = array();
			$query_a4 = array();
			$query_a5 = array();
			$query_a6 = array();
			$query_a7 = array();
			$query_a8 = array();
			
			foreach($words as $id=>$val)
			{
				$val = trim($val);
				if(!(empty($val) || strlen ($val) < 3 || $val==""))
				{
					$vq++;
					$query_a1[] = "html LIKE ".quote_smart("%".($val)."%")."";
					$query_a2[] = "title LIKE ".quote_smart("%".($val)."%")."";
					$query_a3[] = "text LIKE ".quote_smart("%".($val)."%")."";
					$query_a4[] = "opisanie LIKE ".quote_smart("%".($val)."%")."";
					$query_a5[] = "t LIKE ".quote_smart("%".($val)."%")."";
					$query_a6[] = "txt LIKE ".quote_smart("%".($val)."%")."";
					$query_a7[] = "param_polnoeopisanie LIKE ".quote_smart("%".($val)."%")."";
					$query_a8[] = "param_kodtovara LIKE ".quote_smart("%".($val)."%")."";
				}
			}
		
		if(empty($str) || strlen ($str) < 3 || $str=="" || $vq == 0)
		{
			$src = 'Запрос должен состоять как минимум из 3х символов';
		}
		else
		{
		
			require INC_DIR.'stemmer.class.php';
			$stemmer = new Lingua_Stem_Ru();
			$str = $stemmer->stem_word($str);

			$results1 = array();$results2 = array();$results3 = array();$results4 = array();
			
			$results1 = $this->db->get_rows("SELECT * FROM ".TABLE_SITEMENU." 
			WHERE ((".implode(' && ', $query_a1).") || (".implode(' && ', $query_a2).")) && deleted = 0 && enabled = 1 && visible=1 GROUP BY id");
			
			if(class_exists('Ishop'))
			{
				$results2 = $this->db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." 
				WHERE ((".implode(' && ', $query_a3).") || (".implode(' && ', $query_a2).")) && enabled = 1 && visible=1 GROUP BY id");
				
				if($this->db->check_column('param_polnoeopisanie', TABLE_PRODUCTS))
				{
					$results3 = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." 
					WHERE ((".implode(' && ', $query_a4).") || (".implode(' && ', $query_a7).") 
					|| (".implode(' && ', $query_a2).") || (".implode(' && ', $query_a8).")) && enabled = 1 && visible=1 GROUP BY id");
				}
				else
				{
					$results3 = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." 
					WHERE ((".implode(' && ', $query_a4).") || (".implode(' && ', $query_a2).")) && enabled = 1 && visible=1 GROUP BY id");
				}
			}
			
			if(class_exists('News'))
			{
				$results4 = $this->db->get_rows("SELECT * FROM ".TABLE_NEWS." 
				WHERE (".implode(' && ', $query_a5).") || (".implode(' && ', $query_a6).") GROUP BY id");
			}
			
			
			$rows = count($results1) + count($results2) + count($results4) + count($results3);

			if($rows == 0)
			{
				$src = 'Не найдено ни одного документа, соответствующего запросу '.$str;
			}
			else
			{
			
				$cnt = 0;
				
				$src = $str.'
				<div class="content_search">';

				if ($rows > 0) 
				{
				
					$sr_array = array(
						'site' => array('results1', 'site', 'title', 'html'),
						'news' => array('results4', 'news', 't', 'txt'),
						'ishop' => array('results2', 'ishop', 'title', 'text'),
						'ishop2' => array('results3', 'ishop/product', 'title', 'param_polnoeopisanie')
						
					);
				
					$_SESSION['search'] = $str;
					
					foreach($sr_array as $id=>$value)
					{
						if(count(${$value['0']}) > 0)
						{
							foreach(${$value['0']} as $row)
							{
								$cnt++;
								if($cnt > ($_GET['id']-1)*$this->count_in_page && $cnt <= $_GET['id']*$this->count_in_page || $_GET['id'] == 0)
								{
									$src .= $this->view('search/search', array('row'=>$row, 'value' => $value));
								}	
							}
						}
					}
				} 

				
				$src .= '</div>';
				
				$pages = ceil($cnt/$this->count_in_page);
				
				$src .= get_pages(
					array (
						'class' => 'prd_pages_bottom',
						'count_pages' => $pages, 
						'curr_page'=> $_GET['id'], 
						'link' => 'search/'
					)
				);

			}
		}


		$content = array (
			'html' => $src,
			'meta_title' => 'Поиск',
			'meta_keys' => 'Поиск',
			'meta_desc' => 'Поиск',
			'left_menu' => FALSE,
			'path' => 'Поиск'
		);
		return $content;
	}
	
	public function script()
	{
		$content = '';
		if(!empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == SITE_URL.'search/')
		{
			//echo $_SESSION['search'].'<br>';
			$content .= $this->view('search_script');
		}
		return $content;
	}
}


$search = new Search();

if ($_GET['module'] == 'search')
{
	if (isset($_POST['bsearch_str']) && !empty($_POST['bsearch_str'])) {
		$content = $search->get($_POST['bsearch_str']);
	} else {
		if (isset($_SESSION['bsearch_str'])) $content = $search->get($_SESSION['bsearch_str']);
	}
	
}
