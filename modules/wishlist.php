<?php
if(!class_exists('Site')) die(include '../404.html');
class Wish extends Site
{
	public $on = false;
	private $wish_info = NULL;
	public $wish_cnt = 0;
	public $wish_sum = 0;
	public $knadd='<a class="link-wishlist" id="%id%" onclick="Add_to_wish(this);return false;"><span>В Wishlist%cnt%</span></a>';
	public $knmove='<a class="btn-wishlist" id="aw_%id%" onclick="Move_to_wish(\'%id%\');return false;" title="Перенести в Wishlist">%cnt%</a>';
	private $wish_exist = false;
	public $wish_locked = true;
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
		$this->on=(isset($this->sets['wish']) && $this->sets['wish']==1);
		if (isset($_SESSION['user']) && $_SESSION['user']>0){
			$res=$this->db->get(TABLE_WISH, array('userid'=>trim($_SESSION['user'])));
			$this->wish_exist=(count($res)>0);
			if ($this->wish_exist) {
				$this->get_wish_info($_SESSION['user']);
				$this->wish_locked=!($res[0]['scooc']>0);
			}
		}
	}
	private function __clone() {}
	public function regin(){
		echo $this->view('reg/wishin',array('prd'=>$_GET['id']));
		exit;		
	}
	public function removeprd(){
		$prd_id=(isset($_GET['id']))?(int)$_GET['id']:0;
		if ($prd_id==0) die('alert("Нет товара");');
		if (!isset($_SESSION['user'])){
			die("RegWish('".$prd_id."');");
		}
		$res=$this->db->delete(TABLE_WISH_DET,array('userid'=>trim($_SESSION['user']),'prdid'=>$prd_id));
		$this->db->update(TABLE_WISH, array('userid'=>trim($_SESSION['user'])),array('date'=>time(),'isset'=>0));
		$this->get_wish_info($_SESSION['user']);
		if (!($res===false)) die(json_encode(array('res'=>1,'sum'=>number_format($this->wish_sum,2,'.',''))));
		die(json_encode(array('res'=>0)));
	}
	public function setcnt(){
		$prd_id=(isset($_GET['id']))?(int)$_GET['id']:0;
		$prd_cnt=(isset($_GET['params'][0]))?(int)$_GET['params'][0]:0;
		if ($prd_id==0) die('alert("Нет товара");');
		if (!isset($_SESSION['user'])){
			die("RegWish('".$prd_id."');");
		}
		$res=$this->db->update(TABLE_WISH_DET,array('userid'=>trim($_SESSION['user']),'prdid'=>$prd_id),array('cnt'=>$prd_cnt));
		$this->db->update(TABLE_WISH, array('userid'=>trim($_SESSION['user'])),array('date'=>time(),'isset'=>0));
		$this->get_wish_info($_SESSION['user']);
		$sum=($this->wish_info[$prd_id]['tsena'] - (0.01 * $this->wish_info[$prd_id]['skidka'] * $this->wish_info[$prd_id]['tsena']))*$this->wish_info[$prd_id]['cnt'];
		if (!($res===false)) die(json_encode(array('res'=>1,'sum'=>number_format($this->wish_sum,2,'.',''),'psum'=>number_format($sum,2,'.',''),'cnt'=>$prd_cnt)));
		die(json_encode(array('res'=>0)));
	}
	private function add_prd($user,$prd,$cnt){
		$result = $this->db->get(TABLE_WISH_DET, array('userid'=>$user,'prdid'=>$prd));
		if(count($result) > 0) $qr=$this->db->update(TABLE_WISH_DET, array('userid'=>$user,'prdid'=>$prd),array('cnt'=>$result[0]['cnt']+$cnt));
		else {
			$res=$this->db->get_rows('select max(no)+1 as no from '.TABLE_WISH_DET." where userid='".$_SESSION['user']."'");
			if (!($res[0]['no']>0)) $res[0]['no'] =1;
			$qr=$this->db->insert(TABLE_WISH_DET, array('userid'=>trim($_SESSION['user']),'prdid'=>$prd,'cnt'=>$cnt,'no'=>$res[0]['no']));
		}
		if ($this->wish_exist)	$this->db->update(TABLE_WISH, array('userid'=>trim($_SESSION['user'])),array('date'=>time(),'isset'=>0));
		else $this->db->insert(TABLE_WISH, array('userid'=>trim($_SESSION['user']),'datec'=>time(),'date'=>time()));
	}
	public function addprd()
	{ 	
		$prd_id=(isset($_GET['id']))?(int)preg_replace('/(.*?)_/i','',$_GET['id']):0;
		if ($prd_id==0) die('alert("Нет товара");');
		if (!isset($_SESSION['user']))
			die(json_encode(array('res'=>3)));
		$res=$this->add_prd(trim($_SESSION['user']),$prd_id,1);
		$this->get_wish_info($_SESSION['user']);
		if (!($res===false)) die(json_encode(
			array('res'=>1,
			'cnt'=>$this->ShowCnt($prd_id),
			'msg'=>'<div class="msg_cart" id="sid"><img src="'.TEMP_FOLDER.'images/ico_wishm.png" alt="" height="60" width="60" /><em>Wishlist:</em>Товар добавлен в Wishlist.</div>')));
		else die(json_encode(			
			array('res'=>2,
			'msg'=>'<div class="msg_cart" id="sid"><img src="'.TEMP_FOLDER.'images/ico_wishm.png" alt="" height="60" width="60" /><em>Wishlist:</em>Не удалось добавить в Wishlist.</div>')));

	}
	public function movefcart(){
		$prd_id=(isset($_GET['id']))?(int)$_GET['id']:0;
		$prd_cnt=(isset($_GET['params'][0]))?(int)$_GET['params'][0]:1;
		if ($prd_id==0) die(json_encode(array('res'=>0)));
		if (!isset($_SESSION['user'])){
			die(json_encode(array('res'=>0)));
		}
		$res=$this->add_prd(trim($_SESSION['user']),$prd_id,$prd_cnt);
		if (!($res===false)) {
			$resc=Cart::gI()->removeprd();
			die($resc);
		}
		die(json_encode(array('res'=>0)));
		
		
	}
 	
	public function ShowCnt($id){
		if (!$this->wish_exist) return '';
		$res='';
		if (isset($this->wish_info[$id])) $res=' (Уже '.$this->wish_info[$id]['cnt'].')';
		return $res;
	}
	public function set_locked(){
		$unlock=(isset($_GET['id']))?(int)$_GET['id']:0;
		if (!$this->wish_exist) die(json_encode(array('res'=>0)));
		$res=$this->db->update(TABLE_WISH,array('userid'=>$_SESSION['user']),array('scooc'=>$unlock));
		$res=$this->db->get(TABLE_WISH,array('userid'=>$_SESSION['user']));
		if (count($res)>0) die(json_encode(array('res'=>1,'state'=>$res[0]['scooc'])));
		die(json_encode(array('res'=>0)));
	}

	public function get_wish_info($id)
	{
		$sum=0;
		$cnt=0;
		$prd = NULL;
		$q = "select p.*,w.cnt,w.no from ".TABLE_PRODUCTS." p, ".TABLE_WISH_DET." w where w.prdid=p.id and w.userid='".$id."' order by w.no";
		$res=$this->db->get_rows($q);
		foreach($res as $row){
			$prd[$row['id']]['id'] = $row['id'];
			$prd[$row['id']]['no'] = $row['no'];
			$prd[$row['id']]['title'] = $row['title'];
			$prd[$row['id']]['tsena'] = $row['tsena'];
			$prd[$row['id']]['foto'] = $row['foto'];
			$prd[$row['id']]['vlink'] = $row['vlink'];
			$prd[$row['id']]['cat_id'] = $row['cat_id'];
			$prd[$row['id']]['enabled'] = $row['enabled'];
			$prd[$row['id']]['cnt'] = $row['cnt'];
			$prd[$row['id']]['param_kodtovara'] = $row['param_kodtovara'];
			$prd[$row['id']]['param_kolichestvo'] = $row['param_kolichestvo'];
			$prd[$row['id']]['param_starayatsena'] = $row['param_starayatsena'];
			$prd[$row['id']]['skidka'] = $row['skidka'];
			$prd[$row['id']]['price'] =$row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']);
			$prd[$row['id']]['param_srokpostavki'] = $row['param_srokpostavki'];
			$sum+=($row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']))*$row['cnt'];
			$cnt+=$row['cnt'];
		}
		$this->wish_cnt=$cnt;
		$this->wish_sum=$sum;
		$this->wish_info = $prd;
		return $this->wish_info;
	}

	function test(){
		print_r($_GET);
		die('');
	}
	function log_in(){
		if(!empty($_POST['w_login']) && !empty($_POST['w_pass'])){
			$usrs = $this->db->get(TABLE_USERS, array('login'=>$_POST['w_login'], 'block'=>0));
			if(count($usrs) > 0){
				if ($_POST['w_pass']==$usrs[0]['pass']){
					$_SESSION['user'] = $usrs['0']['id'];
					if(!empty($_SESSION['user'])){
						if(!empty($_POST['vrem'])) setcookie('ishopb',encrypt($_SESSION['user']),time()+60*60*24* Site::gI()->sets['login_time'], '/');
						User::gI()->logon();
						Cart::gI()->ResetCart();
						$_SESSION['use_user']=1;
						Cart::gI()->detect_cart();
						$cart=Cart::gI(true);
						$res=array('res'=>1,'user'=>iconv('cp1251','utf8',User::gI()->form()),'cart'=>iconv('cp1251','utf8',$cart->cart()));
						echo json_encode($res);
						exit;
					}
				} else {
					$res=array('res'=>3,'msg'=>iconv('cp1251','utf8','Введен неверный пароль.<br>Если Вы забыли пароль, воспользуйтесь <a href="user/restore">Восстановлением пароля</a>'));
					echo json_encode($res);
					exit;
				}
			} else {
				$res=array('res'=>2,'msg'=>iconv('cp1251','utf8','Пользователь с таким e-mail не найден.<br>Введите правильный e-mail или <a href="user/registr">Зарегистрируйтесь</a>'));
				echo json_encode($res);
				exit;
			}
		}
		$res=json_encode(array('res'=>0));
		die($res);
	}
	function okform(){
		echo User::gI()->form();
		exit;
	}
	function show(){
		$prd = $this->wish_info;
		$summa = $this->wish_sum;
		$_SESSION['umenu']=5;
		return  array (
					'html' => $this->view('ishop/wish_list', array('prd'=>$prd, 'summa'=>$summa)),
					'meta_title' => 'Cписок желаний',
					'meta_keys' => '',
					'meta_desc' => '',
					'path' => 'Мой Cписок желаний'
				);
	}
	function clear(){
		if (isset($_SESSION['user']) && $_SESSION['user']>0){
			$res=$this->db->exec('delete from '.TABLE_WISH_DET." where userid='".$_SESSION['user']."'");
			if (!($res===false)) echo 'Список желаний пуст.';
		}
		die();
	}
	function show_byemail(){
		$mail=(isset($_GET['id']))?trim($_GET['id']):'';
		$resul=array ('meta_title' => 'Cписок желаний',
					'meta_keys' => '',
					'meta_desc' => '',
					'path' => 'Cписок желаний пользователя '.$mail);
		if (empty($mail)) {$resul['html']='Не введен e-mail!';return $resul;}
		$usr=$this->db->get(TABLE_USERS,array('login'=>$mail));
		if (count($usr)==0) {$resul['html']='Cписок желаний пользователя не найден!';return $resul;}
		$id=$usr[0]['id'];
		$lc=$this->db->get(TABLE_WISH,array('userid'=>$id));
		if (count($lc)==0) {$resul['html']='У данного пользователя нет списка желаний.';return $resul;} 
		if (!($lc[0]['scooc']>0)) {$resul['html']='Пользователь запретил просмотр списка желаний.';return $resul;} 
		if (empty($cont)){
			$sum=0;
			$cnt=0;
			$prd = NULL;
			$q = "select p.*,w.cnt,w.no from ".TABLE_PRODUCTS." p, ".TABLE_WISH_DET." w where w.prdid=p.id and w.userid='".$id."' order by w.no";
			$res=$this->db->get_rows($q);
			foreach($res as $row){
				$prd[$row['id']]['id'] = $row['id'];
				$prd[$row['id']]['no'] = $row['no'];
				$prd[$row['id']]['title'] = $row['title'];
				$prd[$row['id']]['tsena'] = $row['tsena'];
				$prd[$row['id']]['foto'] = $row['foto'];
				$prd[$row['id']]['vlink'] = $row['vlink'];
				$prd[$row['id']]['cat_id'] = $row['cat_id'];
				$prd[$row['id']]['enabled'] = $row['enabled'];
				$prd[$row['id']]['cnt'] = $row['cnt'];
				$prd[$row['id']]['param_kodtovara'] = $row['param_kodtovara'];
				$prd[$row['id']]['param_starayatsena'] = $row['param_starayatsena'];
				$prd[$row['id']]['param_kolichestvo'] = $row['param_kolichestvo'];
				$prd[$row['id']]['skidka'] = $row['skidka'];
				$prd[$row['id']]['price'] =$row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']);
				$prd[$row['id']]['param_srokpostavki'] = $row['param_srokpostavki'];
				$sum+=($row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']))*$row['cnt'];
				$cnt+=$row['cnt'];
			}
			$resul['html']=$this->view('ishop/wish_list', array('prd'=>$prd, 'summa'=>$sum,'lock'=>true));
		}
		return  $resul;
	}

}

$wish = Wish::gI();
//print_r($_GET);

if ($_GET['module'] == 'wishlist')
{
	if(isset($_GET['type']) && method_exists($wish, $_GET['type']))
	{
		$content = $wish->{$_GET['type']}();
	}
	else
	{
//		print_r($_GET);
        error_log("Call 404 from wishlist.php: 279");
		$wish->_404();
	}

}
?>