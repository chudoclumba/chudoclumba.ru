<?php
class Site{
	public $db;
	public $url='';
	public $ajax=false;
	public $sets;
	public $ebox;
	public $top_cat='Нет';
	public $path_del = '&nbsp;»&nbsp;';
	private $menu;
	private $module;
	private $parents;
	private $show_submenu = 1;
	public $sitemap_ttl = '';
	private static $catstate;
	private $pre;
	private $fix = '';
	private $hidden_path = array();
	private static $instance;

	public static function gI(){
		if(self::$instance === null){
			self::$instance = new self;
		}
		return self::$instance;
	}
	private function __construct(){
		$this->setVar('db', MySQL::gI());
	}
	private function __clone(){
	}
	public function setVar($varName, $value){
		$this->{$varName} = $value;
	}

	public function getVar($varName){
		return $this->{$varName};
	}

	function txt_url(){
		$link_p = array(explode('/',  $_GET['q']));
		$link_p = $link_p['0'];
		$cnts = count($link_p);
		$rows = "SELECT t".$cnts.".id FROM ".TABLE_SITEMENU." as t1";
		for($i=1;$i<$cnts;$i++){
			$rows .= ", ".TABLE_SITEMENU." as t".($i+1)."";
		}
		$rows .= " WHERE t".$cnts.".title=".quote_smart($link_p[$cnts-1])."";
		for($i=($cnts-1); $i>0; $i--){
			$rows .= "&& t".$i.".title = ".quote_smart($link_p[$i])." && t".$i.".id = t".($i+1).".pid";
		}
		$rows .= " LIMIT 1";
		$rows = $this->db->get_rows($rows);
		$_GET['module'] = 'site';
		$_GET['id'] = $rows['0']['id'];
	}

	function get_menu($ido, $menuRows){
		$q_active_rows = $menuRows;
		$active_rows = array();
		foreach($q_active_rows as $id=>$value){
			$active_rows[$value['parent_id']][$value['id']]['title'] = $value['title'];
			$active_rows[$value['parent_id']][$value['id']]['id'] = $value['id'];
			$active_rows[$value['parent_id']][$value['id']]['type'] = $this->module;
			$active_rows[$value['parent_id']][$value['id']]['link'] = $value['link'];
			$active_rows[$value['parent_id']][$value['id']]['vlink'] = (!empty($value['vlink'])) ? $value['vlink'] : '';
			$active_rows[$value['parent_id']][$value['id']]['visible'] = !$value['visible'];
			$active_rows[$value['parent_id']][$value['id']]['vistop'] = (isset($value['vistop']) && $value['vistop']);
			$active_rows[$value['parent_id']][$value['id']]['cnt'] = ((isset($value['cnt']))?$value['cnt']:0);
		}
		$this->setVar('menu', $active_rows);
		$parents = array();
		$parents[] = $ido;
		if($ido){
			$id = $ido;
			while(true){
				$parent_id = $this->get_parent_id($id);
				if($parent_id === false){
					break;
				}
				else{
					$parents[] = $parent_id;
					$id = $parent_id;
				}
			}
		}

		$this->setVar('parents', $parents);
	}

	function get_path($cat_id, $type = ''){
		$path = $this->get_path_ids($cat_id);
		$path_string = "";
		$i=0;
		foreach($path as $id){
				$a1 = '';
				$a2 = '';
					$a1 = '<li>';
					$a2 = '</li>';
				if($id != $_GET['id']){
					$a1 = '<li class="active"><a href="'.$this->link($id, $type).'">';
					$a2 = '</a></li>';
				}
				$path_string .= $a1.stripslashes($this->get_title($id)).$a2;
			$i++;
		}
		return $path_string;
	}

	function get_pathnm($id){
		if ($id==0) return '';
		$catpinf = $this->db->get(TABLE_CATEGORIES,$id);
		if(!empty($catpinf) && count($catpinf)>0){
			if($catpinf['visible']==1 && $catpinf['enabled']==1){
				$nm='<a title="Открыть" href="'.$this->link($catpinf['id'],'').'">'.stripslashes($catpinf['title']).'</a>';
			}
			else{
				$nm=stripslashes($catpinf['title']);
			}
			if ($catpinf['parent_id']==11) $this->top_cat=$catpinf['title'];
			if($catpinf['id']>0){
				$tmp=$this->get_pathnm($catpinf['parent_id']);
				if(!empty($tmp)){
					$nm=$tmp.$this->path_del.$nm;
				}
				else{
					$nm=$nm;
				}
			}
		}
		else $nm='';
		return $nm;
	}
	function format_price($value,$val=0)
	{
		if	($val==1) 
		{
			return ''.number_format($value,2,'.','').''.$this->cpt[$_SESSION['currency']];
		}
		else
		{
			return ''.number_format($value,2,'.','').' &#8381;';
		}
	}
	function s_price($value, $skidka = 0)
	{
		$value = $this->skidka($value,$skidka);
		return ''.$this->format_price($value).'';
	}
	function s_price_c($value,$val=0)
	{
		return ''.$this->format_price($value,$val).'';
	}
	function skidka($value, $skidka = 0)
	{
		if($this->sets['mod_skidka'])
		{
			return $value = $value - (0.01 * $skidka * $value);
		}
		return $value;
	}

	function get_prdstate($id){
		if(isset(self::$catstate['id']) && self::$catstate['id']==$id){
			return self::$catstate['enabled'];
		}
		else{
			self::$catstate['id']=$id;
			self::$catstate['enabled']=$this->gprs($id);
		}
		return self::$catstate['enabled'];
	}

	private function gprs($id){
		if ($id==0) return true;
		$catpinf = $this->db->get(TABLE_CATEGORIES,$id);
		if(!empty($catpinf) && count($catpinf)>0){
			$nm=($catpinf['enabled'] && $catpinf['visible']);
			if($catpinf['id']>0){
				$nm=($nm && $this->gprs($catpinf['parent_id']));
			}
		}
		else $nm=true;
		return $nm;
	}

	function link($id, $type = ''){
		$pref = (!empty($type)) ? $type : $this->pre;
		$menu_link = SITE_URL.$pref.$id.$this->fix;
		if(DEF_ID == $id && $this->getVar('module') == DEF_MODULE){
			$menu_link = SITE_URL;
		}
		return $menu_link;
	}

	function get_path_ids($cid){
		$path = array();
		$id = $this->get_parent_id($cid);
		if($id != 0) $path = $this->get_path_ids($id);
		if(!in_array($cid, $this->hidden_path))	$path[] = $cid;
		return $path;
	}

	function get_title($k){
		$menu = $this->menu;
		if(count($menu) > 0){
			foreach($this->menu as $id=>$value){
				foreach($value as $id2=>$value2){
					if($id2 == $k) return $value2['title'];
				}
			}
		}
		return false;
	}

	function get_parent_id($k){
		$menu = $this->getVar('menu');
		if(count($menu) > 0){
			foreach($menu as $id=>$value){
				foreach($value as $id2=>$value2){
					if($id2 == $k) return $id;
				}
			}
		}
		return false;
	}

	function GetSettings(){
		$sets = array();
		$q = $this->db->get_rows("select id, value from ".TABLE_SETTINGS);
		foreach($q as $res) $sets[$res['id']] = $res['value'];
		$this->sets = $sets;
		return $sets;
	}


	function get_top_id(){
		return $this->top_id;
	}

	function top_menu($id=0,$lev=0){
		$str='';
		$rows=$this->db->get_rows("select id,pid,enabled,title,vlink from ".TABLE_SITEMENU." where vistop>0 and deleted=0 and pid='".$id."' order by position");
		if (count($rows)>0){
			$sc='';
			if ($lev==0) $sc='class="menu"';
			if ($lev==1) $sc='class="sub-menu"';
			$str.='<ul '.$sc.'>';
			foreach($rows as $id => $val){
				$class=(!(stripos(Site::gI()->url,$val['vlink'])===false))?' class="act"':'';
				$str.='<li><a'.$class.' href="'.$val['vlink'].'">'.$val['title'].'</a>';
				$cnt=$this->db->get_rows("select count(id) as cnt from ".TABLE_SITEMENU." where vistop>0 and deleted=0 and pid='".$val['id']."'");
				if ($cnt[0]['cnt']>0) {
					$lev++;
					$str.=$this->top_menu($val['id'],$lev);
				}
				$str.='</li>';
			}
			$str.='</ul>';
		}
		return $str;

	}
	function top_menu1($cat_id=0, $options = array()){
		
		$parent_rows = $this->getVar('menu');
		$parent_rows = $parent_rows[$cat_id];
		echo '<pre>';
		print_r($parent_rows);
		echo '</pre>';
		$i=0;
		$curr_id = (!empty($this->id)) ? $this->id : '';
		$top_menu = '';
		$top_menu .= '
		<table class="tbl_tm" align="center">
		<tr>';
		$y = 0;
		$cl_right = '';
		foreach($parent_rows as $id=>$row){
			if($row['visible'] == 0 && $row['title']){
				$class = (($_GET['module'] == $this->module && $_GET['id'] == $id) || (!empty($row['link']) && $_SERVER['REQUEST_URI'] == '/'.$row['link'])) ? "selectr" : "";
				$link = $this->link($id);
				$link = (!empty($row['link'])) ? $row['link'] : $link;
				$classg = ($id == $curr_id || $id == $this->get_top_id() || $_SERVER['REQUEST_URI'] == '/'.$row['link']) ? "selectrg" : "";
				$classgs = ($id == $curr_id || $id == $this->get_top_id() || $_SERVER['REQUEST_URI'] == '/'.$row['link']) ? "selectrgs" : "";
				if($i == 0){
					if($options['left'] == '1') $top_menu .= '<td class="tm_left '.$classgs.' p0"><img alt="" src="s.gif" /></td>';
				}
				else{
					if($options['razd'] == '1') $top_menu .= '<td class="nav_razd p0 '.$classg.'"><img alt="" src="s.gif" /></td>';
				}
				if($id == $curr_id || $id == $this->get_top_id() || $_SERVER['REQUEST_URI'] == $row['link']){
					$y++;
				}
				else{
					$y = 0;
				}
				if($y > 0){
					$cl_right = 'slslsl';
				}
				else{
					$cl_right = '';
				}
				$class_t = ($i==0) ? 'nav_first' : 'nav_second';
				$top_menu .= '
				<td onclick="location.href=\''.$link.'\'" class="'.$class_t.' '.$class.' p0">
				<table class="w100 h100 col vnt_1">
				<tr>';
				//$top_menu .= '<td class="p0 vnt_1"><img alt="" width="1" src="s.gif" /></td>';
				$top_menu .= '<td class="p0 vnt_2">
				<a href="'.$link.'">'.$row['title'].'</a>
				</td>';
				// $top_menu .= '<td class="p0 vnt_3"><img alt="" width="1"  src="s.gif" /></td>';
				$top_menu .= '</tr>
				</table>
				</td>';
				$i++;
			}
		}
		if($options['right'] == '1') $top_menu .= '<td class="tm_right '.$cl_right.' p0"><img alt="" src="s.gif" /></td>';
		$top_menu .= '</tr></table>';
		return $top_menu;
	}
	function left_menuui($id = 0, $level = 0, $pref = '',$tlev = 0){
		@$rows=$this->menu[$id];
		$k=0;
		$flli=false;
		$level++;
		$btn_next_o = 0;
		if($level > 1) $level=2;
		$left_menu =  '<ul class="">';//'<ul class="mnlev'.$tlev.'">';
		$tlev++;
		foreach($rows as $id=>$sel){
			if($sel['visible'] == 0 && $sel['title']){
				if($id==$this->parents[0])	$class   = 'lmact';
				else $class   = '';
				$link = $this->link($id);
				if(!empty($sel['vlink']) && ($this->sets['cpu']==1 && $this->module == 'site')){
					$link = $sel['vlink'];
				}
				if(!empty($sel['vlink']) && ($this->sets['cpucat']==1 && $this->module != 'site')){
					$link = $sel['vlink'];
				}
				if(!empty($sel['link'])){
					$link = $sel['link'];
				}
				$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
				$kv      = '';
				$q1=array();
				$q1['cnt']=0;
				if($this->module == 'ishop'){
					$kv = '';
					global $ishop;
					if ($id>0) $kv = '<span style="font-size:10px">('.$sel['cnt'].')</span>';
					
				}
				$skoro = (!(stripos(mb_strtoupper($sel['title']),'СКОРО') === false) or ($sel['cnt'] > 0) or !($this->module == 'ishop') or !($this->sets['hide_empty_cat']));
				if(($level != 0) && $skoro){
					$sel['title'] = trim(preg_replace('{^(.*?)\s\s(.*)}i','<span class="mmnb">$1</span> $2',$sel['title']));
					$lic=($class>'')?' class="'.$class.'"':'';
					$sdi='';$sdz='';
	///				$sdi=($class>'')?'<div class="lm_mark"></div>':'';
//					$sdz=($sel['cnt']>0)?'<div class="lm_cnt">('.$sel['cnt'].')</div>':'';
					$flli=true;
					$left_menu .= '<li><div '.$lic.' onclick="location.href=\''.$link.'\'">'.$sdi.'<a href="'.$link.'">'.$sel['title'].'</a>'.$sdz.'</div>';
				}
				if($cd > 0 && (in_array($id, $this->parents) || in_array($id, $this->open_pages))){
					if($level == 0) $this->top_id = $id;
					$left_menu .= $this->left_menuui($id, $level, $pref,$tlev);
				}
				if ($flli) {
					$left_menu .='</li>';
					$flli=false;
				}
			}
			$k++;
		}
		$left_menu .= '</ul>';
		
		return $left_menu;
	}

	function left_menu($id = 0, $level = 0, $pref = '',$tlev = 0){
		@$rows=$this->menu[$id];
		echo '<pre>';
		print_r($this->menu);
		echo '</pre>parents<br><pre>';
		print_r($this->parents);
		echo '</pre>';
		exit;
//		exit;
		$nr=count($rows);
		$k=0;
		$level++;
		$btn_next_o = 0;
		if($level > 1) $level=2;
		$left_menu = '';
		if($nr > 0){
			$tlev++;
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					if((in_array($id,$this->parents) && $_GET['module'] == $this->module) || in_array($id, $this->open_pages) || ($_GET['module'] == 'news' && $id == 4 && $sel['title'] == 'Новости' )){
						$display = 'block';
						$class   = 'sm'.$pref.'_'.$level;
					}
					else{
						$display = $this->show_submenu ? 'block' : 'none';
						$class   = 'nsm'.$pref.'_'.$level;
					}
					$link = $this->link($id);
					if(!empty($sel['vlink']) && ($this->sets['cpu']==1 && $this->module == 'site')){
						$link = $sel['vlink'];
					}
					if(!empty($sel['vlink']) && ($this->sets['cpucat']==1 && $this->module != 'site')){
						$link = $sel['vlink'];
					}
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					if($cd > 0 && $class == 'sm'.$pref.'_'.$level && $level == 1){
						$class_c = ' sm'.$pref.'_open';
					}
					else{
						$class_c = '';
					}
					$class_o = '';
					if($btn_next_o == 1){
						$class_o    = ' d'.$pref.'_n_o';
						$btn_next_o = 0;
					}
					$class_p = ' m_center';
					//if($k == 0 && $this->module == 'site') $class_p = ' m_first';
					if($k == $nr - 1) $class_p = ' m_last_'.$level;
					$kv      = '';
					$q1=array();
					$q1['cnt']=0;
					$katcl = '';
					if($this->module == 'ishop'){
						$kv = '(0)';
						global $ishop;
						if ($id==11){
							$katcl = ' ccat';
						} 
						if ($id>0) $q1 = $this->db->get(TABLE_CATEGORIES,$id);
						$kv = ' <span style="font-size:10px">('.$q1['cnt'].')</span>';
					}
					$skoro = (!(stripos(mb_strtoupper($sel['title']),'СКОРО') === false) or ($q1['cnt'] > 0) or !($this->module == 'ishop') or !($this->sets['hide_empty_cat']));
					$sclass = '';
					if(($level != 0) && $skoro){
						if($id == 11 && !($_GET['module'] == 'ishop') ){
							$sclass = 'n';
							$class_c= ' n'.trim($class_c);
						}
						$sel['title'] = trim(preg_replace('{^(.*?)\s\s(.*)}i','<span class="mmnb">$1</span> $2',$sel['title']));
						$cl = ($level < 2 && $pref == '_btn') ? ' onclick="location.href=\''.$link.'\'"' : '';
						$left_menu .= '<div'.$cl.' class="'.$sclass.$class.$class_c.$class_o.$class_p.$katcl.'">';
						if($level < 2 && $pref == '_btn') $left_menu .= '<table class="btn1 col"><tr><td class="p0">';
						$left_menu .= '<a href="'.$link.'">'.$sel['title'].''.$kv.'</a>';
						if($level < 2 && $pref == '_btn') $left_menu .= '</td></tr></table>';
						$left_menu .= '</div>';
					}
					if($cd > 0 && $class == 'sm'.$pref.'_'.$level && $level == 1){
						$btn_next_o = 1;
					}
					$gclass = '';
					if($sclass != 'n') $gclass = '_act';
					if($cd > 0 && $class == 'sm'.$pref.'_'.$level){
						if($level == 0) $this->top_id = $id;
						if($level != 0 && $level < 2) $left_menu .= '<div class="sub'.$pref.'_menud'.$gclass.' '.$class_p.'_sbm"><div class="sub'.$pref.'_menu'.$gclass.'"><table class="col"><tr><td class="p0 top">';
						if($level != 0 && $level > 1) $left_menu .= '<div class="sub'.$pref.'_menu2">';
						$left_menu .= $this->left_menu($id, $level, $pref,$tlev);
						if($level != 0 && $level > 1) $left_menu .= '</div>';
						if($level != 0 && $level < 2) $left_menu .= '</td></tr></table></div></div><div class="b1rs2"></div>';
					}
					if($level != 0 && $level < 2) $left_menu .= '<div class="mr_rr"></div>';
				}
				$k++;
			}
		}
		return $left_menu;
	}

	function left_menu_d($id = 0, $level = 0, $pref = '', $limit = 0){
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$k = 0;
		$level++;
		$btn_next_o = 0;
		if($level > 1) $level = 2;
		$left_menu = '';
		if($nr > 0){
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title'] && $k < $limit){
					if((in_array($id,$this->parents) && $_GET['module'] == $this->module)){
						$display='block';
						$class='sm'.$pref.'_'.$level;
					}
					else{
						$display = $this->show_submenu ? 'block' : 'none';
						$class='nsm'.$pref.'_'.$level;
					}
					$link = $this->link($id);
					if(!empty($sel['vlink']) && EDIT_URL_PAGES){
						$link = $sel['vlink'];
					}
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					if($cd > 0 && $class == 'sm'.$pref.'_'.$level && $level == 1){
						$class_c = ' sm'.$pref.'_open';
					}
					else{
						$class_c = '';
					}
					$rz = '';
					if($level > 1){
						//$rz = ' - ';
					}
					$class_o = '';
					if($btn_next_o == 1){
						$class_o = ' d'.$pref.'_n_o';
						$btn_next_o = 0;
					}
					$class_p = ' m_center';
					//if($k == 0 && $this->module == 'site') $class_p = ' m_first';
					//if($k == $nr - 2 && $this->module == 'ishop') $class_p = ' m_last';
					if($level != 0) $left_menu .= '<div class="'.$class.$class_c.$class_o.$class_p.'"><a href="'.$link.'">'.$rz.$sel['title'].'</a></div>';
					$k++;
				}
			}
		}
		return $left_menu;
	}

	function links($id = 0, $level = 0, $pref = ''){
		$left_menu = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$left_menu = '';
		if($nr > 0){
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
						if (strpos($link,'/')===0) $link=substr($link,1);
						if (strpos($link,'http')===false) $link=SITE_URL.$link;
						
					}
					if ($this->sets['cpu'] && !empty($sel['vlink']) && $this->module=='site'){
						$link=SITE_URL.$sel['vlink'];
					}
					if ($this->sets['cpucat'] && !empty($sel['vlink']) && $this->module=='ishop'){
						$link=SITE_URL.$sel['vlink'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					$left_menu .= '<url>';
					$left_menu .= '<loc>'.$link.'</loc>';
					//$left_menu .= '<lastmod>2005-01-01</lastmod>';
					//$left_menu .= '<changefreq>monthly</changefreq>';
					//$left_menu .= '<priority>0.8</priority>';
					$left_menu .= '</url>'."\n";
					if($this->module == 'ishop'){
						$prds = $this->products($id, 'sort ASC', '');
						foreach($prds as $prd_id=>$prd_val){
							$left_menu .= '<url>';
							$link=SITE_URL.'ishop/product/'.$prd_val['id'];
							if ($this->sets['cpucat'] && !empty($prd_val['vlink']) && $this->module=='ishop'){
								$link=SITE_URL.$prd_val['vlink'];
							}
							$left_menu .= '<loc>'.$link.'</loc>';
							//$left_menu .= '<lastmod>2005-01-01</lastmod>';
							//$left_menu .= '<changefreq>monthly</changefreq>';
							//$left_menu .= '<priority>0.8</priority>';
							$left_menu .= '</url>'."\n";
						}
					}
					if($cd > 0){
						$left_menu .= $this->links2($id, $level, $pref);
					}
				}
			}
		}
		return $left_menu;
	}

	function links2($id = 0, $level = 0, $pref = ''){
		$left_menu = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$left_menu = '';
		if($nr > 0){
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
						if (strpos($link,'/')===0) $link=substr($link,1);
						if (strpos($link,'http')===false) $link=SITE_URL.$link;
					}
					if ($this->sets['cpu'] && !empty($sel['vlink']) && $this->module=='site'){
						$link=SITE_URL.$sel['vlink'];
					}
					if ($this->sets['cpucat'] && !empty($sel['vlink']) && $this->module=='ishop'){
						$link=SITE_URL.$sel['vlink'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					$left_menu .= '<url>';
					$left_menu .= '<loc>'.$link.'</loc>';
					//$left_menu .= '<lastmod>2005-01-01</lastmod>';
					//$left_menu .= '<changefreq>monthly</changefreq>';
					//$left_menu .= '<priority>0.8</priority>';
					$left_menu .= '</url>'."\n";
					if($this->module == 'ishop'){
						$prds = $this->products($id, 'sort ASC', '');
						foreach($prds as $prd_id=>$prd_val){
							$left_menu .= '<url>';
							$link=SITE_URL.'ishop/product/'.$prd_val['id'];
							if ($this->sets['cpucat'] && !empty($prd_val['vlink']) && $this->module=='ishop'){
								$link=SITE_URL.$prd_val['vlink'];
							}
							$left_menu .= '<loc>'.$link.'</loc>';
							//$left_menu .= '<lastmod>2005-01-01</lastmod>';
							//$left_menu .= '<changefreq>monthly</changefreq>';
							//$left_menu .= '<priority>0.8</priority>';
							$left_menu .= '</url>'."\n";
						}
					}
					if($cd > 0){
						$left_menu .= $this->links2($id, $level, $pref);
					}
				}
			}
		}
		return $left_menu;
	}


	function sitemap($id = 0, $level = 0, $pref = ''){
		$left_menu = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$left_menu = '';
		if($nr > 0){
			$left_menu .= '<h3>'.$this->sitemap_ttl.'</h3><ul>';
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					if ($this->sets['cpu'] && !empty($sel['vlink']) && $this->module=='site'){
						$link=SITE_URL.$sel['vlink'];
					}
					if ($this->sets['cpucat'] && !empty($sel['vlink']) && $this->module=='ishop'){
						$link=SITE_URL.$sel['vlink'];
					}

					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					$left_menu .= '<li><a href="'.$link.'">'.$sel['title'].'</a></li>';
					if($this->module == 'ishop'){
						$prds = $this->products($id, 'sort ASC', '');
						$left_menu .= '<ul>';
						foreach($prds as $prd_id=>$prd_val){
							$link=SITE_URL.'ishop/product/'.$prd_val['id'];
							if ($this->sets['cpucat'] && !empty($prd_val['vlink']) && $this->module=='ishop'){
								$link=SITE_URL.$prd_val['vlink'];
							}
							$left_menu .= '<li><a href="'.$link.'">'.$prd_val['title'].'</a></li>';
						}
						$left_menu .= '</ul>';
					}

					if($cd > 0){
						$left_menu .= $this->sitemap2($id, $level, $pref);
					}
				}
			}
			$left_menu .= '</ul>';
		}
		return $left_menu;
	}
	function sitemapyml($id = 0, $level = 0, $pref = ''){
		$ret = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$ret = '';
		if($nr > 0){
			$ret .= '<categories>
';
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					$ret .= '<category id="'.$sel['id'].'">'.$sel['title'].'</category>
';
					if($cd > 0){
						$ret .= $this->sitemap2yml($id, $level, $pref);
					}
				}
			}
			$ret .= '</categories>
';
		}
		return $ret;
	}
	function sitemap2yml($id = 0, $level = 0, $pref = ''){
		$ret = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$ret = '';
		$oldid=$id;
		if($nr > 0){
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					if ($this->sets['cpu'] && !empty($sel['vlink']) && $this->module=='site'){
						$link=SITE_URL.$sel['vlink'];
					}
					if ($this->sets['cpucat'] && !empty($sel['vlink']) && $this->module=='ishop'){
						$link=SITE_URL.$sel['vlink'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					$ret .= '<category id="'.$sel['id'].'" parentId="'.$oldid.'">'.htmlspecialchars(trim($sel['title']),ENT_QUOTES | ENT_XHTML,'UTF-8').'</category>
';
					if($cd > 0){
						$ret .= $this->sitemap2yml($id, $level, $pref);
					}
				}
			}
		}
		return $ret;
	}
	function sitemapymlprd($id = 0, $level = 0, $pref = ''){
		$ret = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$ret = '';
		if($nr > 0){
			$ret .= '<offers>
';
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					if($this->module == 'ishop'){
						$prds = $this->products($id, 'sort ASC', '');
						foreach($prds as $prd_id=>$prd_val){
							$link=SITE_URL.'ishop/product/'.$prd_val['id'];
							if ($this->sets['cpucat'] && !empty($prd_val['vlink']) && $this->module=='ishop'){
								$link=SITE_URL.$prd_val['vlink'];
							}
							$ret .= '<offer id="'.$prd_val['id'].'" bid="0">
<url>'.$link.'</url>
<price>'.$prd_val['tsena'].'</price>
<currencyId>RUR</currencyId>
<categoryId>'.$id.'</categoryId>
<picture>'.SITE_URL.'thumb.php?id='.trim($prd_val['foto'],'/').'&amp;x=170&amp;y=170&amp;crop</picture>
<pickup>true</pickup>
<delivery>true</delivery>
<name>'.htmlspecialchars($prd_val['title'],ENT_QUOTES | ENT_XHTML,'UTF-8').'</name>
<vendor>'.htmlspecialchars($prd_val['param_proizvoditel'],ENT_QUOTES | ENT_XHTML,'UTF-8').'</vendor>
<description>'.$prd_val['param_polnoeopisanie'].'</description>
</offer>
';
						}
					}
					if($cd > 0){
						$ret .= $this->sitemap2ymlprd($id, $level, $pref);
					}
				}
			}
			$ret .= '</offers>
';
		}
		return $ret;
	}
	function sitemap2ymlprd($id = 0, $level = 0, $pref = ''){
		$ret = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$ret = '';
		$oldid=$id;
		if($nr > 0){
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					if ($this->sets['cpu'] && !empty($sel['vlink']) && $this->module=='site'){
						$link=SITE_URL.$sel['vlink'];
					}
					if ($this->sets['cpucat'] && !empty($sel['vlink']) && $this->module=='ishop'){
						$link=SITE_URL.$sel['vlink'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					if($this->module == 'ishop'){
						$prds = $this->products($id, 'sort ASC', '');
						foreach($prds as $prd_id=>$prd_val){
							$link=SITE_URL.'ishop/product/'.$prd_val['id'];
							if ($this->sets['cpucat'] && !empty($prd_val['vlink']) && $this->module=='ishop'){
								$link=SITE_URL.$prd_val['vlink'];
							}
							$ret .= '<offer id="'.$prd_val['id'].'" bid="0">
<url>'.$link.'</url>
<price>'.$prd_val['tsena'].'</price>
<currencyId>RUR</currencyId>
<categoryId>'.$id.'</categoryId>
<picture>'.SITE_URL.'thumb.php?id='.trim($prd_val['foto'],'/').'&amp;x=170&amp;y=170&amp;crop</picture>
<pickup>true</pickup>
<delivery>true</delivery>
<name>'.htmlspecialchars($prd_val['title'],ENT_QUOTES | ENT_XHTML,'UTF-8').'</name>
<vendor>'.htmlspecialchars($prd_val['param_proizvoditel'],ENT_QUOTES | ENT_XHTML,'UTF-8').'</vendor>
<description>'.htmlspecialchars($prd_val['param_polnoeopisanie'],ENT_QUOTES | ENT_XHTML,'UTF-8').'</description>
</offer>
';
						}
					}
					if($cd > 0){
						$ret .= $this->sitemap2ymlprd($id, $level, $pref);
					}
				}
			}
		}
		return $ret;
	}

	function sitemap2($id = 0, $level = 0, $pref = ''){
		$left_menu = '';
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$left_menu = '';
		if($nr > 0){
			$left_menu .= '<ul>';
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					if ($this->sets['cpu'] && !empty($sel['vlink']) && $this->module=='site'){
						$link=SITE_URL.$sel['vlink'];
					}
					if ($this->sets['cpucat'] && !empty($sel['vlink']) && $this->module=='ishop'){
						$link=SITE_URL.$sel['vlink'];
					}
					
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					$left_menu .= '<li><a href="'.$link.'">'.$sel['title'].'</a></li>';
					if($this->module == 'ishop'){
						$prds = $this->products($id, 'sort ASC', '');
						$left_menu .= '<ul>';
						foreach($prds as $prd_id=>$prd_val){
							$link=SITE_URL.'ishop/product/'.$prd_val['id'];
							if ($this->sets['cpucat'] && !empty($prd_val['vlink']) && $this->module=='ishop'){
								$link=SITE_URL.$prd_val['vlink'];
							}

							$left_menu .= '<li><a href="'.$link.'">'.$prd_val['title'].'</a></li>';
						}
						$left_menu .= '</ul>';
					}
					if($cd > 0){
						$left_menu .= $this->sitemap2($id, $level, $pref);
					}
				}
			}
			$left_menu .= '</ul>';
		}
		return $left_menu;
	}

	function left_menu_icon($id = 0, $level = 0, $pref = ''){
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$left_menu = '';
		if($nr > 0){
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					if((in_array($id,$this->parents) && $_GET['module'] == $this->module)){
						$display='block';
						$class='sm'.$pref.'_'.$level;
					}
					else{
						$display = $this->show_submenu ? 'block' : 'none';
						$class='nsm'.$pref.'_'.$level;
					}
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					if($cd > 0 && $class == 'sm'.$pref.'_'.$level && $level == 1){
						$class_c = ' sm'.$pref.'_open';
					}
					else{
						$class_c = '';
					}
					if($level != 0) $left_menu .= '<div id="knopka'.$id.'" class="'.$class.$class_c.'"><a href="'.$link.'"><img alt="" src="s.gif" width="256" height="58" /></a></div>';
					if($cd > 0 && $class == 'sm'.$pref.'_'.$level){
						if($level == 0) $this->top_id = $id;
						if($level != 0 && $level < 2) $left_menu .= '<div class="ml_rz"></div><div class="sub'.$pref.'_menud"><div class="sub'.$pref.'_menu">';
						if($level != 0 && $level > 1) $left_menu .= '<div class="ml_rz"></div><div class="sub'.$pref.'_menu2">';
						$left_menu .= $this->left_menu($id, $level, $pref);
						if($level != 0 && $level > 1) $left_menu .= '</div>';
						if($level != 0 && $level < 2) $left_menu .= '</div></div>';
					}
				}
			}
		}
		return $left_menu;
	}

	function left_menu_js($id = 0, $level = 0, $pref = '', $display='block'){
		@$rows = $this->menu[$id];
		$nr = count($rows);
		$level++;
		if($level > 1) $level = 2;
		$sc = 'menu';
		if($level > 1){
			$sc = 'submenu'.$level;
		}
		$left_menu = '<ul id="submenu'.$id.'" style="display:'.$display.'" class="'.$sc.'">';
		if($nr > 0){
			foreach($rows as $id=>$sel){
				if($sel['visible'] == 0 && $sel['title']){
					if((in_array($id,$this->parents) && $_GET['module'] == $this->module) && $level < 2){
						$display='block';
						$class='sm'.$pref.'_'.$level;
					}
					else{
						$display = $this->show_submenu ? 'block' : 'none';
						$class='nsm'.$pref.'_'.$level;
					}
					$link = $this->link($id);
					if(!empty($sel['link'])){
						$link = $sel['link'];
					}
					$cd = (!empty($this->menu[$id])) ? $this->cnt_htd($id) : 0;
					if($cd > 0){
						$class_c = ' sm'.$pref.'_open_'.$level.'';
					}
					else{
						$class_c = '';
					}
					if($level != 0) $left_menu .= '<li onclick="location.href=\''.$link.'\'" onmouseover="show_id('.$id.')" onmouseout="hide_id('.$id.')" id="menu'.$id.'" class="'.$class.$class_c.'"><a href="'.$link.'">'.$sel['title'].'</a>';
					if($cd > 0){
						$left_menu .= $this->left_menu_js($id, $level, $pref, 'none');
					}
					$left_menu .= '</li>';
				}
			}
		}
		$left_menu .= '</ul>';
		return $left_menu;
	}

	function cnt_htd($cat_id){
		$t = 0;
		$parent_rows = $this->menu[$cat_id];
		foreach($parent_rows as $id=>$row){
			if($row['visible'] == 0 && $row['title']){
				$t++;
			}
		}
		return $t;
	}

	public function view($file, $p = array()){
		$filename = TEMP_FOLDER.'html/'.$file.'.php';
		if(file_exists($filename)){
			extract($p, EXTR_OVERWRITE);
			ob_start();
			include $filename;
			$cnt = ob_get_contents();
			ob_end_clean();
			return $cnt;
		}
		else{
			return 'Файл '.$filename.' не найден';
		}
	}

	function GetEditBoxes($keys = array()){
		$ret = array();
		if(count($keys) > 0){
			$q = $this->db->get_rows("select id, text from ".TABLE_EDITBOXES. " WHERE id IN ('".implode("', '", $keys)."')");
		}
		else{
			$q = $this->db->get_rows("select id, text from ".TABLE_EDITBOXES. "");
		}
		foreach($q as $res){
			$ret[$res['id']] = $res['text'];
		}
		$this->ebox = $ret;
		return $ret;
	}

	function _404($txt = 'Страница не найдена'){
	header("HTTP/1.1 301 Moved Permanently");
	header("Location:http://".$_SERVER['HTTP_HOST']);
		exit;
	}

	function _403($txt = 'Страница не найдена'){
	header("HTTP/1.1 301 Moved Permanently");
	header("Location:http://".$_SERVER['HTTP_HOST']);
		exit;
	}

	function redirect($url){
		if(strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0){
			header("Location:".$url);
		}
		else{
			header("Location:".SITE_URL.$url);
		}
		exit;
	}
}
?>