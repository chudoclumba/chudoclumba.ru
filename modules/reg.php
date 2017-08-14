<?php
if(!class_exists('Site')) die(include '../404.html');


class User extends Site {
	public $msg ='';
	public $user = array();
	public $ufio = '';
	public $user_role=0;
	private $logged = 0;
	private $cntx = 0;
	private static $instance;

	public static function gI(){
		if(self::$instance === null){
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct(){
		$this->db = Site::gI()->db;
		if(!empty($_COOKIE['ishopb']) && empty($_SESSION['user'])){
			$_SESSION['user'] = decrypt($_COOKIE['ishopb']);
			if(!empty($_SESSION['user'])) setcookie('ishopb',encrypt($_SESSION['user']),time()+60*60*24*Site::gI()->sets['login_time'], '/');
		}
		if(!empty($_SESSION['user'])) $this->logon();
	}

	private function read_info($id){
		$usrs = $this->db->get_rows("SELECT u.id, u.login, u.pass, u.datep, u.info, sum(f.sumin)-sum(f.sumout) as balans,u.sale FROM ".TABLE_USERS." u , ".TABLE_ORDERS_FIN." f where f.uid=u.id and  u.id=".$this->db->escape_string($id));
		if (count($usrs)>0) $usrs=$usrs[0]; else $usrs=array();
		return $usrs;
	}
	private function set_dlog(){
		if((int)$this->user['datep']<(time()-60*60*24) && $_SERVER['REMOTE_ADDR']!=$this->sets['ofip']){
			$this->db->update(TABLE_USERS,array('id'=>$_SESSION['user']),array('datep'=>time()));
			$this->user['datep']=time();
			$_SESSION['userinf']['datep']=$this->user['datep'];
		}
	}

	public function logon(){
		global $gmess;
		$gmess.='-logon-';
		if(!isset($_SESSION['userinf'])){
			$this->setInfo($this->read_info($_SESSION['user']));
		}
		else{
			$this->user=$_SESSION['userinf'];
		}
		$this->logged();
		if (empty($inf_dop)) $inf_dop = unserialize($this->user['info']);
		$this->ufio=$inf_dop[9].' '.$inf_dop[8].' '.$inf_dop[11];
		$this->msg ='';
		$this->set_dlog();
		$res=$this->db->get(TABLE_USERADM,$_SESSION['user']);
		if (count($res)>0) $this->user_role=$res['role'];
		unset($_SESSION['tpr']);
	}

	private function __clone(){
	}

	function mesage(){
		if(isset($_SESSION['tpr'])){
			$r=$_SESSION['tpr'];
		}
		else{
			$r=$this->msg;
		}
		return $r;
	}

	function form(){
		if(empty($_SESSION['user'])){
			$r = $this->view('reg/in',array('msg'=>$this->mesage()));
		}
		else{
			//print_r($this->user);
			$r = $this->view('reg/out',array('fio'=>$this->user['inf_dop'][8]));
		}
		return $r;
	}

	function logged(){
		$this->logged = 1;
	}

	function is_logged(){
		return $this->logged;
	}

	function setInfo($info){
		$inf_dop = unserialize($info['info']);
		$info['inf_dop']=$inf_dop;
		$this->user = $info;
		$_SESSION['userinf']=$info;
	}

	function getInfo(){
		return $this->user;
	}

	function auth(){
		if(!empty($_POST['login']) && !empty($_POST['pass'])){
			$usrs = $this->db->get(TABLE_USERS, array('login'=>$_POST['login'], 'pass'=>$_POST['pass'], 'block'=>0));
			if(count($usrs) > 0){
				$_SESSION['user'] = $usrs['0']['id'];
				$this->logon();
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
			$this->redirect($_SERVER['HTTP_REFERER']);
		} else
		{
            IF (isset($_POST['w_oreg']) && $_POST['w_oreg']=="Jk"){
            	$_SESSION['user_vo']=1;
            	unset($_POST['w_oreg']);
            }

		} 
		$this->redirect($_SERVER['HTTP_REFERER']);
		return 1;
	}


	function anketa(){		// ********************************  anketa()  ********************************
		$_SESSION['umenu']=4;
		$err='';
		$data=array();
		$dop_rows = $this->db->get_rows("SELECT id,name,type,300,required FROM ".TABLE_FEED." WHERE parent_id=1 && enabled = 1 ORDER BY sort ASC, id ASC");
		foreach($dop_rows as $key => $val){
			$id=$val['id'];
			unset($val['id']);
			$data[''.$id]=array_values($val);
		}
		$this_url = SITE_URL.'user/registr';
		$inf = $this->getInfo();
		$inf_dop = unserialize($inf['info']);
		$cnt = '';
		if(count($_POST)>0 && $_POST['posted']==1)
		{
			print_r($_POST);
			foreach($dop_rows as $id=>$val){
				if($val['id'] != 3 && $val['id'] != 4 && $val['id'] != 16 && $val['id'] != 10){
					if($val['type'] == 'checkbox')
						$user_inf[$val['id']] = (!empty($_POST['p'.$val['id']])) ? '1' : '0';
					else{
						$user_inf[$val['id']] = $_POST['p'.$val['id']];
						if($val['id']==8 || $val['id']==9 || $val['id']==11) $user_inf[$val['id']] = mb_convert_case($user_inf[$val['id']],MB_CASE_TITLE,"UTF-8");
					}
				}
			}
			if (!isset($_POST['p21'])){
				$this->db->SetErrMsgOn(false);
				$this->db->delete(TABLE_PODPISKA, array('email' => $inf['login']));
				$this->db->SetErrMsgOn();
			}
			if(!empty($_POST['p16'])){
				if (isset($_POST['p21'])){
//					$this->db->SetErrMsgOn(false);
					$this->db->insert(TABLE_PODPISKA, array('email' => $_POST['p16'],'mkey'=>md5($_POST['p16']),'date' => time()));
					$this->db->SetErrMsgOn();
				}
				$data = array('login' => $_POST['p16'],'pass' => $_POST['p4'],'email' => $_POST['p16'],'info' => serialize($user_inf));
			}
			else{
				$data = array('info' => serialize($user_inf));
			}
		print_r($data);
			$this->db->update(TABLE_USERS, $_SESSION['user'], $data);
			$mail_content = '<p>Здравствуйте, '.$_POST['p9'].' '.$_POST['p8'].' '.$_POST['p11'].'!</p>';
			$mail_content .= '<p>Для входа в личный кабинет используйте</p>';
			$mail_content .= '<p>Логин	: '.$_POST['p16'].'<br>';
			$mail_content .= 'Пароль	: '.$_POST['p4'].'</p>';
			sendmail_a($_POST['p16'],'Изменение регистрации на '.SITE_NAME,$mail_content);
			unset($_SESSION['userinf']);
			$this->logon();
			$content = array (
				'html' =>'Данные успешно сохранены. На Ваш e-mail отправлено сообщение с новыми регистрационными данными.',
				'meta_title' => 'Мои данные',
				'meta_keys' => 'Мои данные',
				'meta_desc' => 'Мои данные',
				'path' => 'Мои данные'
			);
			return $content;
		}
		if (!$this->is_logged()) {
            error_log("Call 404 from reg.php: 184");
            $this->_404();
        }
		$v=$this->db->count(TABLE_PODPISKA,array('email'=>$inf['login']));
		$inf_dop[21]=($v==1 || $v==NULL)?1:0;
		$inf_dop[16]=$inf['login'];
		$inf_dop[4]=$inf['pass'];
		$inf_dop[10]=$inf['pass'];
		if(!empty($err)) $inf_dop = $user_inf;
$cnt=$this->view('feedback/feedback_new',array('form_data'=>$data,'txt_data'=>$inf_dop,'header'=>'Изменение личных данных.','btn_name'=>'Сохранить'));		
		$content = array (
			'html' => $cnt,
			'meta_title' => 'Мои данные',
			'meta_keys' => 'Мои данные',
			'meta_desc' => 'Мои данные',
			'path' => 'Мои данные',
			'left_menu'=>false
		);
		return $content;
	}

	private function update($info){ // **************************************** update() ****************************
		$this->db->update(TABLE_USERS, $this->user['id'], $info);
	}

	function registr(){   // ******************************************** Registr() ****************************
		if(!empty($_SESSION['user'])){
			$this->redirect('user/auth');
		}
		$data=array();
		$dop_rows = $this->db->get_rows("SELECT id,name,type,300,required FROM ".TABLE_FEED." WHERE parent_id=1 && enabled = 1 ORDER BY sort ASC, id ASC");
		foreach($dop_rows as $key => $val){
			$id=$val['id'];
			unset($val['id']);
			$data['f'.$id]=array_values($val);
		}
		$data['capcha']=array('Код с картинки','capcha',300,4);
		$tdata=array('f21'=>1);
		$cnt = '';
		$msg='';
		if(count($_POST)>0 && isset($_POST['posted'])){
			if(isset($_SESSION['captcha_keystring']) && isset($_POST['pcapcha']) && $_SESSION['captcha_keystring'] == $_POST['pcapcha']){
				$usrs = $this->db->get(TABLE_USERS, array('login'=>$_POST['pf16']));
				if(count($usrs) > 0)
					$cnt = '<p style="color:red;">Вы уже зарегистрированы.</p><p></p>';
				else {
					foreach($dop_rows as $id=>$val){
						if($val['id'] != 3 && $val['id'] != 4 && $val['id'] != 16){
							if($val['type'] == 'checkbox') 
								$user_inf[$val['id']] = (!empty($_POST['p'.$val['id']])) ? '1' : '0';
							else{
								$user_inf[$val['id']] = $_POST['pf'.$val['id']];
								if($val['id']==8 || $val['id']==9 || $val['id']==11) $user_inf[$val['id']] = mb_convert_case($user_inf[$val['id']],MB_CASE_TITLE,"UTF-8");
							}
						}
					}
					$last_id=0;
					$data = array(
						'data' => time(),
						'login'=>$_POST['pf16'],
						'pass'=>$_POST['pf4'],
						'email'=>$_POST['pf16'],
						'code' => '',
						'block' => (!empty(Site::gI()->sets['mod_reg_req'])) ? 1 : 0,
						'info' => serialize($user_inf)
					);
					unset($_SESSION['captcha_keystring']);
					$this->db->insert(TABLE_USERS, $data);
					$last_id = $this->db->insert_id();
					if (isset($_POST['p21']) && !empty($_POST['p16'])){
						$this->db->SetErrMsgOn(false);
						$this->db->insert(TABLE_PODPISKA, array('email' => $_POST['p16'],'mkey'=>md5($_POST['p16']),'date' => time()));
						$this->db->SetErrMsgOn();
					}
					$cnt = sitemenu_get_html(29);
					if($last_id >0){
						$_SESSION['user']=$last_id;
						$this->logon();
					}
					$mail_content = (sitemenu_get_html(12)).'<p>Логин (e-mail): '.$_POST['p16'].'<br>
					Пароль: '.$_POST['p4'].'</p>';
					sendmail_a($_POST['p16'],$_SERVER['HTTP_HOST']." - ".'Регистрация завершена',$mail_content);
				}
			}
			else
				$msg = 'Неверно введен код!';
			unset($_SESSION['captcha_keystring']);
		} 
		else {
			unset($_SESSION['captcha_keystring']);
			$cnt = $this->view('feedback/feedback_new', array('form_data'=>$data,'txt_data'=>$tdata, 'header'=>'Регистрация покупателя.','btn_name'=>'Зарегистрироваться'));
		}
		if (!empty($msg)) {
			unset($_SESSION['captcha_keystring']);
			$cnt=$this->view('feedback/feedback_new',array('form_data'=>$data,'txt_data'=>$_POST,'header'=>'Регистрация покупателя.','btn_name'=>'Зарегистрироваться','errmsg'=>$msg));
		}
		$content = array (
			'html' => $cnt,
			'meta_title' => 'Регистрация',
			'meta_keys' => 'Регистрация',
			'meta_desc' => 'Регистрация',
			'path' => 'Регистрация',
			'left_menu'=>false
		);
		return $content;
	}

	function logout(){   // *********************************************  Logout()  ********************
		unset($_SESSION['user']);
		unset($_SESSION['userinf']);
		unset($_SESSION['use_user']);
		unset($_SESSION['tpr']);
		unset($_SESSION['union']);
		$_SESSION['flaglogout']=1;
		setcookie('ishopb','',time()-3600,'/');
		$this->redirect(SITE_URL);
	}

	function restore(){		// *****************************  Restore()  *******************************
		$this_url = SITE_URL.'user/restore';
		$form_data = array(
			'email' => array('E-mail', 'email', 0, 1),
			'capcha' => array('Код с картинки', 'capcha', 0, 4));
		$cnt = '';
		$msg='';
		if(count($_POST)>0 && isset($_POST['posted'])){
			if(isset($_SESSION['captcha_keystring']) && isset($_POST['pcapcha']) && $_SESSION['captcha_keystring'] == $_POST['pcapcha']){
				$usrs = $this->db->get(TABLE_USERS, array('login'=>$_POST['pemail']));
				if(count($usrs) > 0){
					$inf = unserialize($usrs[0]['info']);
					$mail_content = '<p>Здравствуйте, '.$inf[9].' '.$inf[8].' '.$inf[11].'!</p>
					<p>Вы воспользовались формой восстановления пароля на сайте '.trim(SITE_URL,'/').'. Для входа используйте</p>
					<p>Логин (E-mail): '.$usrs['0']['login'].'<br/>Пароль: '.$usrs['0']['pass'].'</p>';
					sendmail_a($usrs['0']['email'],'Восстановление пароля '.SITE_NAME,$mail_content,'#UserRestore');
					$cnt = '<p style="color: #95C12B; font-size:20px; font-weight:700;">Пароль отправлен на E-email.</p>';
				}
				else{
					$msg = 'Неверно введен E-email!';
				}
			}
			else{
				$msg = 'Неверно введен код!';
			}
			unset($_SESSION['captcha_keystring']);
		} 
		else {
			unset($_SESSION['captcha_keystring']);
			$cnt=$this->view('feedback/feedback_new',array('form_data'=>$form_data,'header'=>'Восстановление пароля','btn_name'=>'Восстановить'));
		}
		if (!empty($msg)) {
			unset($_SESSION['captcha_keystring']);
			$cnt=$this->view('feedback/feedback_new',array('form_data'=>$form_data,'txt_data'=>$_POST,'header'=>'Восстановление пароля','btn_name'=>'Восстановить','errmsg'=>$msg));
		}
		$content = array (
			'html' => $cnt,
			'meta_title' => 'Восстановление пароля',
			'meta_keys' => 'Восстановление пароля',
			'meta_desc' => 'Восстановление пароля',
			'path' => 'Восстановление пароля',
			'left_menu'=>false
		);
		return $content;
	}
	
	function test(){
		print_r($this->user);
		print_r(array('тест'));
		exit;
	}
	
	function formin(){			// ***********************************   Formin() ******************************
		echo $this->view('reg/auth_new');
		exit;
	}
	
	function a_login($inp=1){	// ***********************************  a_login()  ******************************

		$res=array('res'=>0);
		if(!empty($_POST['login']) && !empty($_POST['pass'])){
			$usrs = $this->db->get(TABLE_USERS, array('login'=>$_POST['login'], 'pass'=>$_POST['pass']));
			if(count($usrs) > 0 && $usrs[0]['block']==0){
				$_SESSION['user'] = $usrs['0']['id'];
				if(!empty($_SESSION['user'])){
					$this->logon();
					if(!empty($_POST['rem'])) setcookie('ishopb',encrypt($_SESSION['user']),time()+60*60*24* Site::gI()->sets['login_time'], '/');
					$this->set_dlog();
					global $modules;
					$file = ROOT_DIR.'modules/'.$modules['cart'].'.php';
					if (file_exists($file))	require_once($file);
					$res=array('res'=>1,'user'=>User::gI()->form(),'cbtn'=>Cart::gI()->cart());

					//$promoEngine = new PromoEngine();
					//$promoEngine->checkPromoForUser($_SESSION['user']);
					$ins = PromoEngine::Instance();

					/*try
                    {
                      //  Logger::Info("Something strange");
                     //   PromoEngine::Instance()->checkPromoForUser($_SESSION['user']);
                    }
                    catch (Exception $exception)
                    {
                        Logger::Info("505 error: ".$exception->getMessage());
                    }*/
					//PromoEngine::Instance()->checkPromoForUser($_SESSION['user']);
				} 
			}
			elseif( isset($usrs[0]) && $usrs[0]['block']>0){
				$res=array('res'=>3,'msg'=>'Ваша анкета на модерации.');
			}
			else{
				$res=array('res'=>3,'msg'=>'Введен неверный пароль.<br>Если Вы забыли пароль, воспользуйтесь <a href="user/restore">Восстановлением пароля</a>');
			}
		}else $res=array('res'=>3,'msg'=>'Ошибка данных');
		echo json_encode($res);
		exit;
	}
}


//=================
if(!empty($_SESSION['user']) && !empty($_SESSION['user_type'])){
	define('USER_ID',$_SESSION['user']);
	define('USER_TYPE',$_SESSION['user_type']);
}
//=================



$reg = User::gI();
unset($_SESSION['umenu']);
if($_GET['module'] == 'user'){
	if(method_exists('User', $_GET['type'])){
		$content = $reg->{
			$_GET['type']
		}();
	}
	else{
        error_log("Call 404 from reg.php: 406");
		$reg->_404();
	}

	if(!empty($_SESSION['user'])){
		if(isset($reg->user['block']) && $reg->user['block']==1){
			$content['html'] = '<p>Ваша анкета заблокирована администрацией.</p>
			<p>Все вопросы вы можете задать по e-mail:<br>'.$ebox['email'].'</p>';

		}
	}
}