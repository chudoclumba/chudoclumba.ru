<?php
if(!class_exists('Site')) die(include '../404.html');

class Sitemenu extends Site
{
	private $id = 0;
	private $content;
	private $cnt = '';
	private $hidden_path = array();
	public $top_id;
	public $open_pages = array();  //Всегда открытые разделы	
	public $disabled = array();

	private static $instance;

	public static function gI() {
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {
		$this->db = Site::gI()->db;
		$this->sets = Site::gI()->sets;
		$this->ebox = Site::gI()->ebox;
		
		$this->setVar('pre', 'site/');
		$this->setVar('fix', '');
		$this->setVar('hidden_path', array(/*написать тут цифру можно через запятую например: 1,2,3*/)); //скрыть разделы в пути
		$this->setVar('sitemap_ttl', 'Разделы');
//		$this->get_menu($_GET['id'], $this->getMenuRows());
	}

	private function __clone() {}

	function getMenuRows()
	{
		$d = '';
		if(!empty(Site::gI()->sets['mod_hide_text']))
		{
			$d = (empty($_SESSION['user'])) ? ' && reg=0' : '';
		
			if(USER_SITEMENU)
			{
				$users_page = array();

				if(!empty($_SESSION['user']))
				{
					$ui = $this->db->get_rows("SELECT page_id FROM ".TABLE_U_P." WHERE user_id = ".quote_smart($_SESSION['user'])."");

					foreach($ui as $uid=>$uval)
					{
						$users_page[] = $uval['page_id'];
					}
					
					$d = ' && reg=0';
					
					if(count($users_page) > 0)
					{
						$d = ' && (reg=0 || id IN ('.implode(',', $users_page).'))';
					}
				}
			}
		}
	
		/*if($_GET['module'] == 'site')
		{
			$i = array();
			$i[] = 0;
			$i[] = $_GET['id'];
			$p = $this->db->get_rows("SELECT pid FROM ".TABLE_SITEMENU." WHERE id=".quote_smart($_GET['id'])."");
			if(!empty($p['0']['pid'])) $i[] = $p['0']['pid'];
			while($p['0']['pid'] != 0)
			{
				$p = $this->db->get_rows("SELECT pid FROM ".TABLE_SITEMENU." WHERE id=".quote_smart($p['0']['pid'])."");
				$i[] = $p['0']['pid'];
			}
			return $this->db->get_rows("SELECT id, pid as parent_id, title, link, !(visible) as visible FROM ".TABLE_SITEMENU." WHERE pid IN (".implode(',', array_unique($i)).") && deleted = 0".$d." ORDER BY position ASC, id ASC");
		}
		else
		{
			return $this->db->get_rows("SELECT id, pid as parent_id, title, link, !(visible) as visible FROM ".TABLE_SITEMENU." WHERE pid = 0 && deleted = 0".$d." ORDER BY position ASC, id ASC");
		}*/
		
		
		$q = "SELECT id, pid as parent_id, title, vlink, link, !(visible) as visible, vistop FROM ".TABLE_SITEMENU." WHERE deleted = 0".$d." ORDER BY position ASC, id ASC";

		return $this->db->get_rows($q);
		
	}
	
	function set_id($id)
	{
		$this->id = $id;
	}
	
	function c_text()
	{
		
		 $texts = explode("\n", $this->ebox['top_text']);
		 
		 ob_start();
		 ?><script>
		var i = 0;
		function update() {
		<?foreach($texts as $id=>$val) {?>
			if(i == <?=$id?>)
			{
				$(".textfl").html('<?
				$h = ($id+1);
				
				if($h > count($texts) - 1) $h = 0;
				echo htmlspecialchars(trim($texts[$h]));
				
				?>');
			}
		<?}?>	
			i++;
			if(i > <?=count($texts)?>)
			{
				i = 0;
			}
			setTimeout('update()', 3000);
		}

		 setTimeout('update()', 3000);
		 </script><div class="textfl"><?=htmlspecialchars(trim($texts['0']),ENT_COMPAT | ENT_XHTML,'UTF-8')?></div>
		 <?
		 
		 $txt = ob_get_contents();
		 ob_end_clean();
		 return $txt;
	}
	
	function get_subcats($id)
	{
		$rows = $this->db->get_rows("SELECT * FROM ".TABLE_SITEMENU." WHERE pid = ".quote_smart($id)."  && deleted = 0 && visible = 0 ORDER BY position ASC, id ASC");
		return $rows;
	}


	
	function top_menu2($cat_id=0, $options = array())
	{
		$parent_rows = $this->getVar('menu');
		$parent_rows = $parent_rows[$cat_id];

		$i=0;

		$top_menu = '';
		
		foreach ($parent_rows as $id=>$row)	
		{ 
			if($row['visible'] == 0 && $row['title'])
			{
				$link = $this->link($id);
				$link = (!empty($row['link'])) ? $row['link'] : $link;
				$top_menu .= '<a href="'.$link.'">'.$row['title'].'</a>';
				
				$i++;
			}
		}
		return $top_menu;
	}

	function get_submenu($id)
	{
		if (isset($this->id))
		{
			if (get_pid($_GET['id'])==$id || $_GET['id']==$id)
			{
				$submenu = '';
				$row = $this->db->count(TABLE_SITEMENU,['pid'=>$id,'deleted'=>0,'visible'=> 0]);
				if ($row > 0)
				{
					$parent_rows = $this->get_subcats($id);
					$submenu .= '<ul class="submenu">';
					foreach ($parent_rows as $row)
					{
						$submenu .= '<li><a href="'.$this->link($row['id']).'">'.$row['title'].'  '.get_pid($row['id']).'</a></li>';
					}
					$submenu .= '</ul>';
				}
				return $submenu;
			}
		}
	}

	function get_pid($id)
	{
		$row = $this->db->get(TABLE_SITEMENU,$id);
		return $row['pid'];
	}

	function get_html($id = null)
	{
		$row['html'] = '';
		if(!empty($id))
			$row = $this->db->get(TABLE_SITEMENU,$id);
		return fix_content($row['html']);
	}

	 function add_feedback($value, $email, $form_data = array(), $pos = ''){
	 	$frm = '';
	 	$fl=false;
	 	if($this->id != $value) return;
 		if(isSet($_POST['posted']) &&
 			(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['ppc'])){
 			$ctm = '<b>'.date('d-m-Y H:i',time()).' IP '.$_SERVER['REMOTE_ADDR'].'</b> Раздел:'.$this->content['title'].'<br>';				 			foreach($form_data as $id=>$val){
 				if($val['1'] == 'checkbox'){
 					$chk = (!empty($_POST['p'.$id])) ? 'да' : 'нет';
 					$ctm .= "".$val['0'].": ".$chk."<br><br>";
 				}
 				if($val['1'] == 'checkboxes'){
 					if(count($_POST['p'.$id]) > 0){
 						$ctm .= "".$val['0'].": ".implode(', ', $_POST['p'.$id])."<br>";
 					}
 					else{
 						$ctm .= "".$val['0'].": <br>";
 					}
 				}	
 				if($val['1'] == 'select_text'){
 					if($_POST['p'.$id] == 'нет'){
 						$ctm .= "".$val['0'].": нет<br>";
 					}
 					else{
 						$ctm .= "".$val['0'].": ".$_POST['p'.$id.'_1']."<br>";
 					}
 				}						
 				elseif($val['1'] == 'passport'){
 					$ctm .= "".$val['0'].": серия: ".$_POST['p'.$id.'_1']." номер: ".$_POST['p'.$id.'_2']." кем выдан: ".$_POST['p'.$id.'_3']." дата выдачи: ".$_POST['p'.$id.'_4']."<br>";
 				}
 				elseif($val['1'] == 'phone'){
 					$ctm .= "".$val['0'].": ".$_POST['p'.$id]."<br>";
 				}	
 				elseif($val['1'] == 'data3'){
 					$ctm .= "".$val['0'].": ".$_POST['p'.$id.'_1']." ".$_POST['p'.$id.'_2']." ".$_POST['p'.$id.'_3']."<br>";
 				}
 				else{
 					if($val['1'] != 'file' && $val['1'] != 'capcha'){
 						$ctm .= "".$val['0'].": ".$_POST['p'.$id]."<br>";
 					}
 				}
 			}
 			$mail_content = ' 
 			<html> 
 			<body> 
 			<p>Письмо с сайта '.SITE_NAME.'.</p> 
 			'.$ctm.'
 			</body> 
 			</html>'; 
 			ob_start();
 			require_once INC_DIR.'PHPMailer/class.phpmailer.php';
 			$mail = new PHPMailer();
 			$mail->AddReplyTo($_POST['pemail'],$_POST['pemail']);
 			$mail->SetFrom($this->ebox['email_su'], 'CHK страница Контакты');
 			$mail->AddAddress($email, 'Отдел заказов');
 			$mail->Subject = 'Письмо с сайта.';
 			$mail->MsgHTML($mail_content);
 			foreach($_FILES as $fileid=>$fileval){
 				$mail->AddAttachment($fileval['tmp_name'], $fileval['name']);
 			}
 			IF ($mail->Send()) $frm = '<div class="sent">Ваше письмо отправлено!</div>';
 			ob_end_clean();
 		} 
 		else{
			$td=array();
			$dt=array();
 			if (isset($_POST['posted'])){
 				unset($_POST['posted']);
 				$fl=true;
 				foreach($_POST as $id => $val){
				 	if (substr($id,0,1)=="p"){
						$td[substr($id,1)]=$val;
					}
				 }
				 $dt['errmsg']="Введен неверный код с картинки!";
			}
 			$frm .= $this->view('feedback/feedback_new', array('txt_data' => $td, 'form_data' => $form_data, 'position'=>$pos,'data'=>$dt));
 		}
 		if ($fl){
			$this->content['html'] = $frm.$this->content['html'];
		} else {
			$this->content['html'] .= $frm;
		}
 		
	 }
	
	function add_faq($value)
	{
		if($this->id == $value)
		{
			if(file_exists(ROOT_DIR.'modules/faq.php'))
			{
				$db = $this->db;
				require_once ROOT_DIR.'modules/faq.php';
				$this->content['html'] .= $cnt;
			}
		}
	}	
	
	function comments()
	{
		if(!empty($_POST['otpravitb']))
		{
			$this->db->insert(TABLE_COMMENTS, array(
				'name' => $_POST['name'],
				'msg' => $_POST['msg'],
				'cat_id' => $_GET['id'],
				'module' => '1',
				'date' => date('Y-m-d H:i:s')
			));
		}
		
		$rows = $this->db->get_rows("SELECT * FROM ".TABLE_COMMENTS." WHERE cat_id = ".quote_smart($_GET['id'])." && module = 1");
		
		return $this->view('comments', array('msgs' => $rows));
	}
	
	function add_print()
	{	
		if($this->content['id'] == $_GET['id'] && !empty($this->content['print']) && (empty($_GET['type']) || $_GET['type'] != 'print'))
		{
			$this->content['html'] .= '<a target="_blank" href="site/print/'.$this->content['id'].'">Распечатать</a>';
		}
		elseif($this->content['id'] == $_GET['id'] && empty($this->content['print']) && (!empty($_GET['type']) && $_GET['type'] == 'print'))
		{
			$this->_404();
		}
	}
	
	function recurs_cats($cats2, $display, $display2)
	{
		if(count($cats2) > 0)
		{
			foreach($cats2 as $c_id=>$c_val)
			{
				if($display == 0 || $c_val['1'] == 0 || $display2 == 0)
				{
					$this->disabled[] = $c_val['0'];
				}	

				if(array_key_exists($c_val['0'], $this->cat_all))
				{
					$this->recurs_cats($this->cat_all[$c_val['0']], $c_val['1'], $display);
				}
			}
		}
	}	
	
	
	function get_content()
	{	
	
		if(!empty($_SESSION['user']) || empty($this->sets['mod_hide_text']))
		{
			$res = $this->db->get(TABLE_SITEMENU,array('id'=>$this->id, 'deleted'=>0));
		}
		else
		{
			$res = $this->db->get(TABLE_SITEMENU,array('id'=>$this->id, 'deleted'=>0, 'reg'=>0));
		}
		
		if(count($res) > 0)
		{
			$res = $res['0'];
			if ($this->sets['cpu']==1 && !(strripos($_SERVER['REQUEST_URI'],'site/'.$this->id)===false) && !empty($res['vlink'])) {
					header("HTTP/1.1 301 Moved Permanently");
					header("Location:http://".$_SERVER['HTTP_HOST'].'/'.$res['vlink']); 
					die();

			}
		}
		
		$enabled = (!empty($res['enabled'])==1)?"1":"0";

		$cnt = '';
		$html = '';

		if (!$enabled || in_array($res['id'], $this->disabled)) 
		{ 
			$html = $this->_404();
		}
		else 
		{
			$html=(!empty($html))?$html:'&nbsp;';
			$html = HTML::del_mso_code($res['html']);
		}
		
		if(count($res) == 0)
		{
			$html = $this->_404();
		}
	
		$content = array (
			'html' => $html,
			'id' => $res['id'],
			'print' => $res['print'],
			'comm' => $res['comm'],
			'title' => (!empty($res['title'])) ? htmlspecialchars($res['title'],ENT_COMPAT | ENT_XHTML,'UTF-8') : '',
			'meta_title' => (!empty($res['pagetitle'])) ? htmlspecialchars($res['pagetitle'],ENT_COMPAT | ENT_XHTML,'UTF-8') : '',
			'meta_keys' => (!empty($res['metakey'])) ? htmlspecialchars($res['metakey'],ENT_COMPAT | ENT_XHTML,'UTF-8') : '',
			'meta_desc' => (!empty($res['metadesc'])) ? htmlspecialchars($res['metadesc'],ENT_COMPAT | ENT_XHTML,'UTF-8') : '',
			'path' => $this->get_path($res['id']),
			'left_menu' => ($res['hideleft']==0) 
		);
		
		$this->content = $content;
	}
	
	function get()
	{
		return $this->content;
	}
}

$sitemenu = Sitemenu::gI();
$sitemenu->setVar('module', 'site');

if(!empty($_GET['module']) && $_GET['module'] == 'site' && (empty($_GET['type']) || $_GET['type'] == 'print'))
{
	$sitemenu->set_id($_GET['id']);
	$sitemenu->get_content();
	
	if(empty($sets['allow_print']) && (!empty($_GET['type']) && $_GET['type'] == 'print')) $sitemenu->_404();
	
	if(!empty($sets['allow_print'])) $sitemenu->add_print();
	
	if(!empty($ebox['id_feedback']))
	{
		$ids = explode(',', $ebox['id_feedback']);
		foreach($ids as  $id)
		{
			$form_data = array(
				'fio'=>array('Фамилия Имя Отчество', 'text', 300, 5),
				'tel'=>array('Телефон', 'phone', 300, 9),
				'email' => array('E-mail', 'email', 300, 1),
				//array('Прикрепить файл', 'file', 300, 1),
				'text'=>array('Ваше сообщение', 'textarea', 350, 20),
				array('Код с картинки', 'capcha', 350, 3)
			);	
		
			if(!empty($sets['allow_feedback'])) $sitemenu->add_feedback($id, $ebox['email'], $form_data, 'top');
		}
		
		//$sitemenu->add_feedback1(4, $ebox['email'],1);
		//$sitemenu->add_feedback1(5, $ebox['email'],2);
	}
	

	$content = $sitemenu->get();
	if(!empty($sets['allow_comments']) && $content['comm'] == 1)
	{
		$content['html'] .= $sitemenu->comments();
	}
}