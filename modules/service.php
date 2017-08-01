<?php
class Serv extends Site
{
	public $epay=false;
	public $needrepay=false;
	public $needrepaywait=false;
	public $newpay=false;
	public $addpay=false;
	public $vid=0;
	public $summacn=0;
	public $perror=false;
	public $ordernumber='';
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
		$this->epay =(((isset($this->sets['yak']) && $this->sets['yak']==1) || (isset($this->sets['tinkoff']) && $this->sets['tinkoff']==1)) && User::gI()->is_logged());
	}

	private function __clone() {}

	public function Get_payinfo($id){
		if (empty($id)) return FALSE;
		$this->ordernumber='';
		$this->needrepay=false;
		$this->needrepaywait=false;
		$this->newpay=false;
		$this->addpay=false;
		$this->vid=0;
		$this->summacn=0;
		$this->perror=false;
		$zak = $this->db->get(TABLE_ORDERS, $id);
		if(empty($id) || !($zak['user_id']==$_SESSION['user']) || empty($_SESSION['user']))
		{
			$this->perror=TRUE;
			return FALSE;
		}
		$ph=$this->db->get_rows("select * from ".TABLE_PAYMENTS." where declined=1 and orderid=".$this->db->escape_string($id));
		if (count($ph)>0)
		{
			$this->summacn=$ph[0]['orderamount'];
			$this->ordernumber=$ph[0]['ordernumber'];
			$this->needrepay=($ph[0]['orderamount']>0);
			if ($this->needrepay==TRUE) {
				return TRUE;
			}
		}
		$ph=$this->db->get_rows("select * from ".TABLE_PAYMENTS." where declined=0 and approved=0 and orderid=".$this->db->escape_string($id));
		if (count($ph)>0)
		{
			$this->needrepay=TRUE;
			$this->needrepaywait=($ph[0]['date']>(time()-2*60));
			$this->summacn=$ph[0]['orderamount'];
			$this->ordernumber=$ph[0]['ordernumber'];
			return TRUE;
		}
		$ph=$this->db->get_rows("select sum(orderamount) as sm, max(vid) as mvid from ".TABLE_PAYMENTS." where approved=1 and orderid=".$this->db->escape_string($id));
		$this->newpay=(!($ph[0]['sm']>0));
		$this->summacn=($zak['summa']-$zak['summa']*$zak['skidka']/100-$zak['sumopl']);
		$this->vid=$ph[0]['mvid']+1;
		if ($this->newpay==TRUE){
			 return TRUE;
		}
		$ph=$this->db->get_rows("select sum(orderamount) as sm from ".TABLE_PAYMENTS." where approved=1 and uchet=0 and orderid=".$this->db->escape_string($id));
		$this->summacn=$zak['summa']-$zak['summa']*$zak['skidka']/100-$zak['sumopl']-$ph[0]['sm'];
		if ($this->summacn>0) $this->addpay=TRUE;
		return TRUE;
	}

	public function payinfo(){
		if(!empty($_POST))
		{
			file_put_contents ( 'data/System/pdata.txt', print_r($_POST,true) , FILE_APPEND  );
		  	$chek=strtoupper(md5(strtoupper(md5('chk08268') . md5($_POST['merchant_id'].$_POST['ordernumber'].$_POST['amount'].$_POST['currency'].$_POST['orderstate']))));
			if ($_POST['checkvalue']===$chek)
			{
				if (strtoupper($_POST['orderstate'])=='APPROVED')
				{
//					$pay=$this->db->get_rows("select * from ".TABLE_PAYMENTS." where ordernumber='".$_POST['ordernumber']."'");
//					$pord=$this->db->get_rows("select * from ".TABLE_ORDERS." where id=".$pay[0]['orderid']);
					$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$_POST['ordernumber']),array('billnumber'=>$_POST['billnumber'],'approved'=>1,'declined'=>0,'orderamount'=>$_POST['amount'],'meantypename'=>$_POST['meantypename']));
//					$this->db->insert(TABLE_ORDERS_FIN,array('uuid'=>$_POST['billnumber'],'uid'=>$pord[0]['user_id'],'docname'=>'Платеж ASSIST','tdate'=> time(),'sumin'=>$_POST['amount'],
//							'sumout'=>'0.00','order_id'=>$pay[0]['orderid']));
				}
				else
				{
//					$pay=$this->db->get_rows("select * from ".TABLE_PAYMENTS." where ordernumber='".$_POST['ordernumber']."'");
//					$pord=$this->db->get_rows("select * from ".TABLE_ORDERS." where id=".$pay[0]['orderid']);
					$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$_POST['ordernumber']),array('billnumber'=>$_POST['billnumber'],'declined'=>1));
				}
				header("Content-type: text/xml");
				$file ='<?xml version="1.0" encoding="UTF-8"?>';
				$file.='<pushpaymentresult firstcode="0" secondcode="0"><order><billnumber>'.$_POST['billnumber'].
					'</billnumber><packetdate>'.$_POST['packetdate'].'</packetdate></order></pushpaymentresult>';
				header("Content-Length: " . strlen($file) .""); 
				echo $file;
				exit;
			}
		}
		else
		{
            error_log("Call 404 from service.php: 110");
			$this->_404();
		}
	}

	public function payok(){
		// http://www.chudoclumba.ru/service/payok?billnumber=5643835205157184&ordernumber=14694&payerdenial=0
		$ord=((isset($_GET['id']) && $_GET['id']!=-1)? $_GET['id'] : '');
		if(isset($_GET['ordernumber']) && !empty($_GET['ordernumber'])) $ord=$_GET['ordernumber'];
		$bill=((isset($_GET['ord']) && !empty($_GET['ord']))? $_GET['ord'] : '');
		if(isset($_GET['billnumber']) && !empty($_GET['billnumber'])) $bill=$_GET['billnumber'];
		$html='<p>Cтатус платежа определить не удалось. Для выяснения причины свяжитесь с менеджерами.</p>';
		if(!empty($ord)){
			$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$ord),array('billnumber'=>$bill,'uret'=>1));
			$html='<p>Ваш платеж подтвержден. Информация о данном платеже появится в Личном кабинете через некоторое время.</p>';
		}
		return array (
			'html' =>$html ,
			'meta_title' => 'Оплата заказа '.$ord,
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Оплата заказа '.$ord);
	}



	public function track()	{
		if (isset($_SESSION['user']) && $_SESSION['user']>0) {
			$pth='<a href="service/deliveryid">Мои посылки</a>->Отслеживание посылки '.$_GET['id'];
			$_SESSION['umenu']=2;
		} else $pth='Отслеживание посылки '.$_GET['id'];
	 	$html='<p>Cтатус платежа определить не удалось. Для выяснения причины свяжитесь с менеджерами.</p>';
	 	require_once(INC_DIR.'russianpost.lib.php');
		try {
		  $client = new RussianPostAPI();
		  $data=$client->GetOperationHistory("".$_GET['id']."");
		} catch(RussianPostException $e) {
  			$html='Ошибка: ' . $e->getMessage() . "\n";
			return array (
						'html' =>$html ,
						'meta_title' => 'Отслеживание посылки '.$_GET['id'],
						'meta_keys' => '',
						'meta_desc' => '',
						'path' => $pth);
		}
		$html= $this->view('ishop/track', array('data'=>$data));

		return array (
					'html' =>$html ,
					'meta_title' => 'Отслеживание посылки '.$_GET['id'],
					'meta_keys' => '',
					'meta_desc' => '',
					'path' => $pth);
	}


	public function payno()	{
	 	$html='<p>Ваш платеж не произошел. Повторный платеж вы можете совершить из списка заказов в Личном кабинете</p>';
		return array (
					'html' =>$html ,
					'meta_title' => 'Оплата заказа '.$_GET['id'],
					'meta_keys' => 'Оплата заказа '.$_GET['id'],
					'meta_desc' => 'Оплата заказа '.$_GET['id'],
					'path' => 'Оплата заказа '.$_GET['id']);
	}


	public function vieword(){

	 	$html='Запрос неверен. Для получения информации о заказе свяжитесь с менеджерами.';
		if(!empty($_GET['id'])){
			$nav_ar = explode('_', $_GET['id']);
			$ords = $this->db->get_rows("SELECT * FROM ".TABLE_ORDERS." WHERE id=".quote_smart($nav_ar[0])." and ha=".quote_smart($nav_ar[1]));
			if (count($ords)>0){
				$rows = $this->db->get_rows("SELECT o.*, p.foto FROM ".TABLE_ORDERS_PRD." o left join ".TABLE_PRODUCTS." p on o.prd_id=p.id WHERE o.order_id = ".quote_smart($nav_ar[0]).' order by kodstr');
				$html = $this->view('ishop/view_order', array('info'=>$rows,'minfo'=>$ords));
			}
		}
		return array (
					'html' =>$html ,
					'meta_title' => 'Состояние заказа',
					'meta_keys' => '',
					'meta_desc' => 'Состояние заказа',
					'path' => 'Состояние заказа',
					'left_menu'=>false);
	}


	public function qiwipay(){
		$tel=str_ireplace(' ','',$_POST['tel']);
		$tel=str_ireplace('(','',$tel);
		$tel=str_ireplace(')','',$tel);
		$tel=str_ireplace('-','',$tel);
	 	$str="?from=9183&summ={$_POST['sum']}&to={$tel}&currency=RUB&txn_id={$_POST['txn_id']}&comm=".rawurlencode("ORDER {$_POST['txn_id']}")."&lifetime=1440&successUrl=".urlencode(SITE_URL."service/payqok")."&failUrl=".urlencode(SITE_URL."service/payno");
	 	$this->redirect('https://w.qiwi.com/order/external/create.action'.$str);
	 	$html=$str;
		return array (
					'html' =>$html ,
					'meta_title' => 'test',
					'meta_keys' => '',
					'meta_desc' => 'test',
					'path' => 'test');
	}


	public function test(){
		print_r($_REQUEST);
		exit();
	}


	public function getpass(){
		$this_url = SITE_URL.'service/getpass';
		$cnt = '';
		$p_arr = array(
			'Номер Вашего заказа:' => 'ordid',
			'E-mail указанный в заказе:'=>'email'
		);
		$cnt .= '<div><form method="post" action="'.$this_url.'"><table>';
		foreach($p_arr as $id=>$value){
			$zval = (!empty($_POST[$value])) ? htmlspecialchars($_POST[$value],ENT_COMPAT | ENT_XHTML,'cp1251') : '';
			$cnt .= '<tr><td>'.$id.'</td><td><input type="text" class="fbinp" style="width:150px;" value="'.$zval.'" name="'.$value.'" /></td></tr>';
		}
		$cnt .= '</table><br><div style="color:#a65254; font-weight:bold; padding:20px 0px 10px 4px">Введите ключ, который вы видите на экране (латинские буквы и цифры):</div>
		Ключ: <input type="text" value="" name="pp" class="fbinp" style="width:150px"/>
		<img style="border:1px solid #0F986A" src="'.SITE_URL.'kcaptcha/?'.session_name().'='.session_id().'#'.time().'" name="send" align="middle"/>
		<div style="padding:4px 0px 0px 4px"><button name="send" type="submit" value="getpass" class="albutton alorange"><span><span><span class="ok">Получить ссылку</span></span></span></button></div></form></div>';
		if(count($_POST)>0){
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['pp']){
				$usrs = $this->db->get(TABLE_ORDERS, array('id'=>$_POST['ordid']));
				if(count($usrs) > 0){
					if($usrs[0]['email']==trim($_POST['email'])){
						$ha=$usrs[0]['ha'];
						if(strlen($usrs[0]['ha'])<4){
							$ha=get_ord_ha();
							$this->db->update(TABLE_ORDERS,$usrs[0]['id'],array('ha'=>$ha));
						}
						$mail_content = '
						<html>
						<body>
						<p>Письмо с сайта '.SITE_NAME.'.</p>
						Cсылка для просмотра статуса заказа: <a href="'.SITE_URL.'service/vieword/'.$usrs[0]['id'].'_'.$ha.'">'.SITE_URL.'service/vieword/'.$usrs[0]['id'].'_'.$ha.'</a>
						</body>
						</html>';
						$res=send_mail(
							"robot@".SITE_NAME,
							SITE_NAME,
							$usrs[0]['email'],
							'',
							'',
							'',
							$_SERVER['HTTP_HOST']." - ".'Ссылка Состояние заказа',
							$mail_content
						);
						if ($res){
							$cnt = 'Ссылка отправлена на E-mail указанный в заказе. Если Вы не получите ответное письмо, свяжитесь с менеджерами.';
						} 
						else{
							$cnt = 'Ошибка при отправке на E-mail указанный в заказе. Пожалуйста, свяжитесь с менеджерами.'.$usrs[0]['email'];
							if (strlen($ha)>4)$cnt.='<br>Cсылка для просмотра статуса заказа: <a href="'.SITE_URL.'service/vieword/'.$usrs[0]['id'].'_'.$ha.'">'.SITE_URL.'service/vieword/'.$usrs[0]['id'].'_'.$ha.'</a>';
						}
					}
					else{
						$cnt.='<br>Неверно введен email! Введите правильный e-mail или свяжитесь с менеджерами.';
					}
				}
				else{
					$cnt.='<br>Неверно введен номер заказа!';
				}
			}
			else{
				$cnt .= '<br>Неверно введен код!';
			}
		}
		unset($_SESSION['captcha_keystring']);
		return array (
			'html' => $cnt,
			'meta_title' => 'Запрос ссылки',
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Запрос ссылки для просмотра статуса заказа.'
		);
	}


	public function zwgxtretqq(){
		if (!isset($_REQUEST['id']) || !isset($_REQUEST['ha'])) {
            error_log("Call 404 from service.php: 297");
		    $this->_404();
        }
		$user = $_REQUEST['id'];
		if (crc32($user)!=$_REQUEST['ha']) {
			die('errmd5');}
		if (isset($_SESSION['user']) && $_SESSION['user']!=$user){
			unset($_SESSION['user']);
			unset($_SESSION['userinf']);
			unset($_SESSION['use_user']);
			unset($_SESSION['tpr']);
			unset($_SESSION['union']);
			unset($_SESSION['ishop_cart']);
			unset($_SESSION['cartid']);
			setcookie('ishopb','',time()-3600,'/');
			$this->redirect(SITE_URL.'service/zwgxtretqq?id='.$user.'&ha='.$_REQUEST['ha']);
		}
		unset($_SESSION['ishop_cart']);
		unset($_SESSION['cartid']);
		$_SESSION['user']=$user;
		$_SESSION['use_user']=1;
		$this->redirect(SITE_URL.'service/fin');
	}


	public function zcart(){
		if (!isset($_GET['id']) || !isset($_GET['params'][0])) {
            error_log("Call 404 from service.php: 324");
		    $this->_404();
        }
		$cart = $this->db->get(TABLE_CART, $_GET['id']);
		$user = $this->db->get(TABLE_USERS, $cart['userid']);
		if (md5($_GET['id'].$user['id'])!=$_GET['params'][0]) {
            error_log("Call 404 from sitemenu.php: 330");
		    $this->_404();
        }
		if (isset($_SESSION['user']) && $_SESSION['user']!=$user['id']){
			unset($_SESSION['user']);
			unset($_SESSION['userinf']);
			unset($_SESSION['use_user']);
			unset($_SESSION['tpr']);
			unset($_SESSION['union']);
			unset($_SESSION['ishop_cart']);
			unset($_SESSION['cartid']);
			setcookie('ishopb','',time()-3600,'/');
			$this->redirect(SITE_URL.'service/zcart/'.$_GET['id'].'/'.$_GET['params'][0]);
		}
		unset($_SESSION['ishop_cart']);
		unset($_SESSION['cartid']);
		$_SESSION['user']=$user['id'];
		$_SESSION['use_user']=1;
		if (!isset($_SESSION['script'])) $_SESSION['script']='';
		$_SESSION['script'].='<script type="text/javascript">$(window).load(function() {yaCounter16195645.reachGoal(\'Cart_sendmail\')});</script>';
		$this->redirect(SITE_URL.'ishop/cart');
	}


	public function showh(){
		$rows = $this->db->get_rows("SELECT  p.*, 1 as active FROM ".TABLE_ORDERS_PRD." o left join ".TABLE_PRODUCTS." p on o.prd_id=p.id WHERE o.order_id = 49307");
		$html = $this->view('ishop/sendmail_cart', array('prd'=>$rows,'$summa'=>1000));
		
		die(mailhead_a($html)); 
		return array (
			'html' => mailhead_a($html),
			'meta_title' => 'Обертка письма',
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Обертка письма'
		);
	}


	public function receive(){
		if (md5("receive".$_GET['id'])==$_GET['params'][0]){
			$cnt = $this->db->get(TABLE_SENDORD,array('ord_id'=>$_GET['id']));
			if (count($cnt)>0) {
				if ($cnt[0][oper]==0) {
					$ret="Заказ уже подтвержден.";
				} else
				{
					$ret="Заказ был отменен. Для внесения изменений свяжитесь с менеджерами.";
				}
			} else {
				$cnt = $this->db->insert(TABLE_SENDORD,array('ord_id'=>$_GET['id'],'data'=>time(),'oper'=>0));
				$ret="<p>Заказ №{$_GET['id']} подтвержден.</p>
				<p>После комплектации заказа с Вами свяжется наш менеджер для согласования условий доставки.</p>";
				
			}
		}  elseif (md5("decline".$_GET['id'])==$_GET['params'][0]){
			$cnt = $this->db->get(TABLE_SENDORD,array('ord_id'=>$_GET['id']));
			if (count($cnt)>0) {
				if ($cnt[0][oper]==1) {
					$ret="Заказ уже отменен.";
				} else
				{
					$ret="Заказ был подтвержден. Для внесения изменений свяжитесь с менеджерами.";
				}
			} else {
				$cnt = $this->db->insert(TABLE_SENDORD,array('ord_id'=>$_GET['id'],'data'=>time(),'oper'=>1));
				$ret="<p>Заказ №{$_GET['id']} отменен информация в личном кабинете будет обновлена после обработки менеджерами.</p>
				";
				
			}
		}  else return;

		return array (
			'html' => $ret,
			'meta_title' => 'Отправка заказа',
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Отправка заказа №'.$_GET['id']
		);
		
	}


	private function get_payform($orderamount,$user,$ordernumber,$orderid,$mes='',$pm=''){
		$p_html="Запрещено";
		if ($this->epay==true) {
			if ($pm=='tin'){
				require_once "includes/TinkoffMerchantAPI.php";
				$ypl= new TinkoffMerchantAPI(array());
			} else {
				$settings = new Settings();
				$ypl = new YaMoneyCommonHttpProtocol("", $settings);
			}
				$p_html=$ypl->getform($orderamount,$user,$ordernumber,$orderid,$mes);
		}
		return $p_html;
	}


	public function savepay(){
		$res=0;
		$err='Error in OrderId,OrderNumber or sum.'.print_r($_POST,true);
		if (isset($_POST['OrderId']) && !empty($_POST['OrderId']) && isset($_POST['OrderNumber']) && !empty($_POST['OrderNumber']) && isset($_POST['sum']) && !empty($_POST['sum'])) {
			$err='Error in OrderId.';
			$qy=$this->db->get(TABLE_ORDERS,$_POST['OrderId']);

			if (count($qy>0)){
				$url='';
				if (isset($_GET['id']) && $_GET['id']=='tin') {
					if (isset($this->sets['tinkoff']) && $this->sets['tinkoff']==1) {
						require_once "includes/TinkoffMerchantAPI.php";						require_once "includes/Debug.php";
					} else die(json_encode(array('result'=>0,'error'=>'Service shutdown')));
					$api=new TinkoffMerchantAPI();
					$ph=$_POST['cps_phone'];
					$ph=str_ireplace(array(' ','-','  ','(',')'),array('','','','',''),$ph);
					$params=array('OrderId'=>$_POST['OrderNumber'],'Amount'=>$_POST['sum']*100,'CustomerKey'=>$_POST['customerNumber'],
					'DATA'=>'Email='.$_POST['cps_email'].'|Phone='.$ph);
					$api->init($params);
					if ($api->error) die(json_encode(array('result'=>0,'error'=>$api->error)));
					$url=$api->paymentUrl;
				}
				$err='cntid>0';
				$qy=$this->db->get(TABLE_PAYMENTS,array('OrderNumber'=>$_POST['OrderNumber']));
				if (count($qy)>0){
					$err='Error write payment.';
					$res=$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$_POST['OrderNumber']),array('date'=>time(),'orderamount'=>$_POST['sum'],'uret'=>0,'approved'=>0,'declined'=>0));
					if (!($res===FALSE)) {
						$res=1;
						$err='';
					}

				} else {
					$err='';
					$vid=0;
					if ($_POST['OrderNumber']!=$_POST['OrderId']){
						$vid=substr($_POST['OrderNumber'],strlen($_POST['OrderId'])+1);
						$err='errsetvid';
					}
					$res=$this->db->insert(TABLE_PAYMENTS,array('orderid'=>$_POST['OrderId'],'ordernumber'=>$_POST['OrderNumber'],'date'=>time(),'orderamount'=>$_POST['sum'],'uret'=>0,'approved'=>0,'declined'=>0,'vid'=>$vid));

				}
			}
		}
		die(json_encode(array('result'=>$res,'error'=>$err,'result_url'=>$url)));
	
	
	
	}


	public function pay_a()	{
		global $gmess;
		$pm='';
		if (isset($_GET['params'][0])) $pm=$_GET['params'][0];
		$serv=Serv::gI();
		$order_sum = Site::gI()->getRealOrderSumm($_GET['id']);
		unset($_SESSION['payment']);
		$flag=$serv->Get_payinfo($_GET['id']);
//				$html=$vvv.'$needrepay'.$serv->needrepay.'$needrepaywait'.$serv->needrepaywait.'$newpay'.$serv->newpay.'$addpay'.$serv->addpay;
		$html='<p>Неизвестная ошибка. Для выяснения причины обратитесь к менеджерам нашего магазина.</p>';
		$flag=!$flag;
		if ($serv->needrepaywait && $serv->needrepay)
		{
			$html =  '<p><b>Ваш платеж в обработке.</b></p><p>Повторная попытка платежа возможна через некоторое время.</p>';
			$flag=TRUE;
		}
		if (!$flag && $serv->needrepay)
		{
			$_SESSION['payment']=$_GET['id'];
//			$this->db->exec('update '.TABLE_PAYMENTS.' set date='.time()." where ordernumber='".$serv->ordernumber."'");
			$html = $this->get_payform($order_sum, User::gI()->user,$serv->ordernumber,$_GET['id'],'Для частичной оплаты измените сумму платежа!',$pm);
			$gmess.='/pay_a_repay';
			$flag=TRUE;
		}
		if (!$flag && $serv->newpay)
		{
			$gmess.='/pay_a_newpay';
			$html = $this->get_payform($order_sum,User::gI()->user,$_GET['id'],$_GET['id'],'Для частичной оплаты измените сумму платежа!',$pm);
			$flag=true;
		}
		if (!$flag && $serv->addpay)
		{
			$ordernumber=$_GET['id'].'-'.$serv->vid;
			$gmess.='/pay_a_addpay';
			$html = $this->get_payform($order_sum,User::gI()->user,$ordernumber,$_GET['id'],'Для частичной оплаты измените сумму платежа!',$pm);
			$flag=true;
//					$_SESSION['payment']=$_GET['id'];
//					$this->db->insert(TABLE_PAYMENTS, array('orderid'=>$_GET['id'],'vid'=>$serv->vid,'ordernumber'=>$ordernumber,
//						'orderamount'=>$serv->summacn,'date'=>time()));
//					$_SESSION['payment']=$_POST['orderid'];
//					$html = $this->view('ishop/payment', array('orderid'=>$_GET['id'],'ordernumber'=>$ordernumber,
//							'orderamount'=>$serv->summacn,'vid'=>$serv->vid));
			$flag=TRUE;
		}
		if (!$flag)	$html='<p>Заказ полностью оплачен!</p>';
		return array (
			'html' => $html,
			'meta_title' => 'Оплата заказа '.$_GET['id'],
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Оплата заказа '.$_GET['id'],
			'left_menu'=>false
		);
	}


	public function kvit() 	{
		if (strtoupper($_GET['id'])=='BLANK') {
			$product = $this->view('ishop/kvit', array('zak'=>'', 'sets'=>$this->sets, 'fio'=>'','adr'=>'','sum'=>0));
			return array (
				'html' => $product,
				'meta_title' => 'Квитанция',
				'meta_keys' => 'Квитанция',
				'meta_desc' => 'Квитанция',
				'path' => 'Квитанция'
			);
		}	
		$zak = $this->db->get(TABLE_ORDERS, $_GET['id']);
		if(!($zak['user_id']==$_SESSION['user']) || empty($_SESSION['user']))
		{
			return array (
				'html' => 'У Вас нет прав чтения данного файла',
				'meta_title' => (!empty($cat['title'])) ? $cat['metatitle'] : '',
				'meta_keys' => (!empty($cat['metakeys'])) ? $cat['metakeys'] : '',
				'meta_desc' => (!empty($cat['metadesc'])) ? $cat['metadesc'] : '',
				'path' => 'Квитанция к заказу '.$_GET['id']
			);
		}
		if (!empty($_POST['pfio']) && !empty($_POST['padr']) && !empty($_POST['psum']) && $_POST['posted']=='1')
		{
			$product = $this->view('ishop/kvit', array('zak'=>$zak, 'sets'=>$this->sets, 'fio'=>$_POST['pfio'],'adr'=>$_POST['padr'],'sum'=>Site::gI()->getRealOrderSumm($_GET['id'])));
			return array (
				'html' => $product,
				'meta_title' => 'Квитанция',
				'meta_keys' => 'Квитанция',
				'meta_desc' => 'Квитанция',
				'path' => 'Квитанция'
			);
		}
		$form_data = array(
			'fio'=> array('Плательщик', 'text', 350, 5),
			'adr' => array('Адрес плательщика', 'textarea', 350, 10),
			'sum' => array('Сумма', 'text', 80, 3)
		);
		$dr = array(
			'fio' => $zak['fio'],
			'adr' => $zak['adr'],
			'sum' => Site::gI()->getRealOrderSumm($_GET['id'])//$zak['summa']-($zak['summa']*$zak['skidka']/100)-$zak['sumopl']
		);
		$product = $this->view('feedback/feedback_new', array('form_data'=>$form_data,'txt_data'=>$dr, 'position' => 'top','btn_name' => 'Продолжить'));
		
		return array (
			'html' => $product,
			'meta_title' => 'Подготовка квитанции',
			'meta_keys' => 'Подготовка квитанции',
			'meta_desc' => 'Подготовка квитанции',
			'path' => 'Подготовка квитанции'
		);
	}


	public function qiwi() {	
		$zak = $this->db->get(TABLE_ORDERS, $_GET['id']);
		if(!($zak['user_id']==$_SESSION['user']) || empty($_SESSION['user']))		{
			return array (
				'html' => 'У Вас нет прав чтения данного файла',
				'meta_title' => (!empty($cat['title'])) ? $cat['metatitle'] : '',
				'meta_keys' => (!empty($cat['metakeys'])) ? $cat['metakeys'] : '',
				'meta_desc' => (!empty($cat['metadesc'])) ? $cat['metadesc'] : '',
				'path' => 'Оплата заказа '.$_GET['id']
			);
		}
		$tel=str_ireplace(' ','',$zak['tel']);
		$tel=str_ireplace('(','',$tel);
		$tel=str_ireplace(')','',$tel);
		$tel=str_ireplace('-','',$tel);

		$dr = array(
			'id' => $zak['id'],
			'sum' => round($zak['summa']-($zak['summa']*$zak['skidka']/100)-$zak['sumopl'],2),
			'tel'=>$tel
		);
		$product = $this->view('ishop/qiwi1', $dr);
		
		return array (
			'html' => $product,
			'meta_title' => 'Оплата через Qiwi',
			'meta_keys' => 'Оплата через Qiwi',
			'meta_desc' => 'Оплата через Qiwi',
			'path' => 'Оплата через Qiwi. Заказ №'.$zak['id']
		);
	}


	public function deliveryid() {   // идентификаторы
		if(!empty($_SESSION['user']))
		{
			$_SESSION['umenu']=2;
			$res = $this->db->get_rows("SELECT d.*,o.user_id FROM `chudo_ishop_postid` d left join chudo_ishop_order o on o.id=d.orderid WHERE o.user_id=".quote_smart($_SESSION['user'])." order by d.orderid desc,d.date desc");
			$html = $this->view('ishop/user_deliveryid', array('info'=>$res));
			return array (
			'html' => $html,
			'meta_title' => 'Мои посылки',
			'meta_keys' => 'Идентификаторы доставок',
			'meta_desc' => 'Идентификаторы доставок',
			'path' => 'Мои посылки');
		}
		else
		{
			$this->redirect(SITE_URL);
		}
	}


	public function orders()    // Мои заказы
	{
		if(!empty($_SESSION['user']))
		{ 
			$_SESSION['umenu']=1;
			$res = $this->db->get_rows("SELECT sum(f.sumin)-sum(f.sumout) as balans FROM ".TABLE_ORDERS_FIN." f where f.uid=".$this->db->escape_string($_SESSION['user']));
			$_SESSION['userinf']['balans']=$res[0]['balans'];
			if(!empty($_GET['id']))
			{
				$id=$this->db->escape_string($_GET['id']);
				$ords = $this->db->get_rows("SELECT * FROM ".TABLE_ORDERS." WHERE id=$id");
				if (count($ords)!=1) return;
				$rows = $this->db->get_rows("SELECT o.*, p.foto,p.cat_id,p.enabled,p.vlink FROM ".TABLE_ORDERS_PRD." o left join ".TABLE_PRODUCTS." p on o.prd_id=p.id WHERE o.order_id = $id order by kodstr");
				$html = $this->view('ishop/user_orders_1', array('info'=>$rows,'minfo'=>$ords));
				return array (
				'html' => $html,
				'meta_title' => 'Состав заказа',
				'meta_keys' => 'Состав заказа',
				'meta_desc' => 'Состав заказа',
				'path' => '<a href="service/orders">Мои заказы</a>->Состав заказа '.$_GET['id'],
				'left_menu'=>FALSE
				);
			}
			else
			{
				if (!isset($_SESSION['ord_showact'])) $_SESSION['ord_showact']=1;
				if (isset($_REQUEST['act']) && $_REQUEST['act']=='togle') {
					$_SESSION['ord_showact']=(($_SESSION['ord_showact']==1)?0:1);
				}
				$wh='';
				if ($_SESSION['ord_showact']==1) $wh=' and status <4 ';
				$rows = $this->db->get_rows("SELECT * FROM ".TABLE_ORDERS." WHERE user_id=".$this->db->escape_string($_SESSION['user']).$wh." ORDER BY data DESC");
				$html = $this->view('ishop/user_orders', array('info'=>$rows));
				return array (
				'html' => $html,
				'meta_title' => 'Заказы',
				'meta_keys' => 'Заказы',
				'meta_desc' => 'Заказы',
				'path' => 'Мои заказы',
				'left_menu'=>FALSE
				);
			}
		}
		else
		{
			$this->redirect(SITE_URL);
		}
	}


	public function fin() 	//личный кабинет - личный счет
	{
		if(!empty($_SESSION['user']))
		{
			$_SESSION['umenu']=3;
			$res = $this->db->get_rows("SELECT sum(f.sumin)-sum(f.sumout) as balans FROM ".TABLE_ORDERS_FIN." f where f.uid=".$this->db->escape_string($_SESSION['user']));
			$_SESSION['userinf']['balans']=$res[0]['balans'];
			$rows = $this->db->get_rows("SELECT * FROM ".TABLE_ORDERS_FIN." WHERE uid=".$this->db->escape_string($_SESSION['user'])." ORDER BY tdate");
			$arows = $this->db->get_rows("SELECT pay.* FROM ".TABLE_PAYMENTS." pay left join ".TABLE_ORDERS." ord on ord.id=pay.orderid WHERE ord.user_id=".$this->db->escape_string($_SESSION['user'])." and pay.approved=1 and pay.uchet=0 ORDER BY pay.date desc");
			$html = $this->view('ishop/user_fin', array('info'=>$rows,'arows'=>$arows));
			return array (
				'html' => $html,
				'meta_title' => 'Личный счет',
				'meta_keys' => 'Личный счет',
				'meta_desc' => 'Личный счет',
				'path' => 'Личный счет',
				'left_menu'=>FALSE
			);
		}
		else
		{
			$this->redirect(SITE_URL);
		}
	}


	public function expectedgoods()	{
		if(!empty($_SESSION['user'])){
			$_SESSION['umenu']=1;
			$rows = $this->db->get_rows("SELECT od.*, p.foto, p.param_srokpostavki,p.vlink FROM ".TABLE_ORDERS_PRD." od JOIN ".TABLE_ORDERS." o ON od.order_id = o.id left join ".TABLE_PRODUCTS." p on od.prd_id=p.id WHERE o.user_id = ".$this->db->escape_string($_SESSION['user'])." and o.status<4 and od.isdel=0 and od.count>od.otgr order by od.kodtov");
			$html = $this->view('ishop/expectedgoods', array('info'=>$rows));
			return array (
				'html' => $html,
				'meta_title' => 'Заказы суммарно',
				'meta_keys' => 'Заказы суммарно',
				'meta_desc' => 'Заказы суммарно',
				'path' => '<a href="service/orders">Заказы</a>->Полный список заказанных товаров'
			);
		}
		else{
			$this->redirect(SITE_URL);
		}
	}


	public function deleterow()    // отмена товара из заказа
	{
		if(!empty($_GET['id']) && $_GET['id'] > ""){
			$navar = explode('_', $_GET['id']);
			if ($navar[0]>' ' and $navar[1]>0){
				$ord=$this->db->get(TABLE_ORDERS,$navar[0]);
				if (count($ord)==0) {
					$this->redirect(SITE_URL);
				}
				$row=$this->db->get(TABLE_ORDERS_PRD,array('order_id'=>$navar[0],'kodstr'=>$navar[1]));
				if ($ord['ld']==1){ // заказ уже загружен
					$reg=$this->db->get(TABLE_ORDERS_REG,array('orderid'=>$navar[0],'kodstr'=>$navar[1]));
					if (count($reg)>0){
						$this->db->delete(TABLE_ORDERS_PRD,array('order_id'=>$navar[0],'kodstr'=>$navar[1]));
						$this->db->delete(TABLE_ORDERS_REG,array('orderid'=>$navar[0],'kodstr'=>$navar[1]));
						$isins=(($reg[0]['isins']==1)?88:99);
						$this->db->insert(TABLE_ORDERS_LOG,array('orderid'=>$navar[0],'kodstr'=>$navar[1],'prdid'=>$row[0]['prd_id'],'cnt'=>$row[0]['count'],'price'=>$row[0]['summa'],'sale'=>$row[0]['skidka'],'data'=>time(),'isins'=>$isins));
						$this->redirect($_SERVER['HTTP_REFERER']);
					}
					$res=$this->db->exec("update ".TABLE_ORDERS_PRD." set todel=count-otgr, isdel=otgr-count, prdel='Ожидается отмена' where order_id=".$this->db->escape_string($navar[0])." and kodstr=".$this->db->escape_string($navar[1]));
					if ($res===1) {
						$row=$this->db->get(TABLE_ORDERS_PRD,array('order_id'=>$navar[0],'kodstr'=>$navar[1]));
						$reg=$this->db->delete(TABLE_ORDERS_REG,array('orderid'=>$navar[0],'kodstr'=>$navar[1]));
						$this->db->insert(TABLE_ORDERS_REG,array('orderid'=>$navar[0],'kodstr'=>$navar[1],'prdid'=>$row[0]['prd_id'],'cnt'=>$row[0]['todel'],'price'=>$row[0]['summa'],'sale'=>$row[0]['skidka']));
						$this->db->insert(TABLE_ORDERS_LOG,array('orderid'=>$navar[0],'kodstr'=>$navar[1],'prdid'=>$row[0]['prd_id'],'cnt'=>$row[0]['todel'],'price'=>$row[0]['summa'],'sale'=>$row[0]['skidka'],'data'=>time(),'isins'=>0));
					} 							
				}else { //заказ не загружен
					$reg=$this->db->delete(TABLE_ORDERS_PRD,array('order_id'=>$navar[0],'kodstr'=>$navar[1]));
					$reg=$this->db->delete(TABLE_ORDERS_REG,array('orderid'=>$navar[0],'kodstr'=>$navar[1]));
					$this->db->insert(TABLE_ORDERS_LOG,array('orderid'=>$navar[0],'kodstr'=>$navar[1],'prdid'=>$row[0]['prd_id'],'cnt'=>$row[0]['count'],'price'=>$row[0]['summa'],'sale'=>$row[0]['skidka'],'data'=>time(),'isins'=>100));
					$cnt=$this->db->count(TABLE_ORDERS_PRD,array('order_id'=>$navar[0]));
					if ($cnt==0) {
						$this->db->delete(TABLE_ORDERS,array('id'=>$navar[0]));
						$this->db->delete(TABLE_ORDERS_PRD,array('order_id'=>$navar[0]));
						$this->db->delete(TABLE_ORDERS_REG,array('orderid'=>$navar[0]));
						$this->redirect('service/orders');
					}
				}

			}
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
		else{
			$this->redirect(SITE_URL);
		}
	}


	public function GetHits($cnt=9){
		$sk = $this->db->get_rows("SELECT id,(enabled and visible) as enb,cat_id FROM ".TABLE_PRODUCTS." WHERE visible=1 and enabled = 1 and views>0 order by views desc  LIMIT 30");
		if (count($sk)<12) 	{
			$sk1= $this->db->get_rows("SELECT id,(enabled and visible) as enb,cat_id FROM ".TABLE_PRODUCTS." WHERE visible=1 and enabled = 1 and oldviews>0 order by oldviews desc  LIMIT 40");
			if (count($sk1>0)) $sk=array_merge($sk,$sk1);
		}
		$hit=array();
		foreach($sk as  $val){
			if ($val['enb'] && Site::gI()->get_prdstate($val['cat_id'])) $hit[]=$val['id']; 
		}
		if (shuffle($hit)){
			$prds="(".implode(",",array_slice($hit,0,$cnt)).")";
			$sk = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE id in $prds");
			return $this->view('ishop/hit_new', array('rows'=>$sk));
		}

	}


	public function newitems($cnts = 6)
	{
		$cnt = '';
		$rows = $this->db->get_rows("SELECT id, title, tsena, skidka, param_starayatsena, spec , foto,vlink FROM ".TABLE_PRODUCTS." WHERE new = 1 && enabled = 1 && visible = 1 ORDER BY id DESC LIMIT ".$cnts);
		if(count($rows) > 0)
		{
			$cnt = $this->view('ishop/new_new', array('rows'=>$rows));
		}
		return $cnt;
	}


	public function send_comment()
	{
		if(!empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['msg'])){
			$this->db->insert(TABLE_COMMENTS, array('name' => $_POST['name'],
					'msg' => $_POST['msg'],
					'module' => 2,
					'enabled' => 0,
					'ip' => ip(),
					'date' => date('Y.m.d H:i:s'),
					'cat_id' => $_GET['id']
				));
			require_once INC_DIR.'PHPMailer/class.phpmailer.php';
			$mail = new PHPMailer();
			$mail->SetFrom($this->ebox['email_su'], "Сайт");
			$mail->AddAddress($this->ebox['email_su'], 'Администратор сайта');
			$mail->Subject = 'Чудо-Клумба. Новый комментарий к товару';
			$inf = $this->db->get(TABLE_PRODUCTS, array('id' => $_GET['id']));
			$tuser='';
			if(User::gI()->is_logged()){
				$tuser=User::gI()->GetInfo();
				$inf_dop = unserialize($tuser['info']);
				$fio=$inf_dop[9].' '.$inf_dop[8].' '.$inf_dop[11];
				$tuser='Пользователь №'.$tuser['id'].' email: '.$tuser['login'].' ФИО: '.$fio;
			}
			$mail->MsgHTML('<p>Новый комментарий к товару <a href="'.SITE_URL.'ishop/product/'.$_GET['id'].'">'.$inf['0']['title'].
			'</a></p><p><a href="http://chudoclumba.ru/wq121/include.php?place=ishop#update_prd_html('.$_GET['id'].
			')">Редактировать в СУС</a></p><p>'.date('Y.m.d H:i:s').' <b>'.$_POST['name'].' ['.ip().']</b></p><p>'.$tuser.
			'<p><p>Комментарий: '.$_POST['msg'].'</p>');
			@$mail->Send();
			die('Отзыв отправлен.');
		}
		die('Ошибка...');
		
		
	}
	
}

$srv = Serv::gI();
//print_r($_GET);

if ($_GET['module'] == 'service')
{
	if(isset($_GET['type']) && method_exists($srv, $_GET['type']))
	{
		$content = $srv->{$_GET['type']}();
	}
	else
	{
//		print_r($_GET);
        error_log("Call 404 from service.php: 867");
		$srv->_404();
	}

}
?>