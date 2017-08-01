<?php
class Pay extends Site
{
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
		global $gmess;
		$gmess.='/Pay';
	
	}

	private function __clone() {}
	public function fail(){
		$ord="";
		$bill='';
		if(isset($_GET['orderNumber']) && !empty($_GET['orderNumber'])) $ord=$_GET['orderNumber'];
		if(isset($_GET['invoiceId']) && !empty($_GET['invoiceId'])) $bill=$_GET['invoiceId'];
		$html='<p>Cтатус платежа определить не удалось. Для выяснения причины свяжитесь с менеджерами.</p>';
		if(!empty($ord)){
			$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$ord),array('billnumber'=>$bill,'declined'=>1,'uret'=>1));
			$html='<p>Ваш платеж отклонен!</p>';
		}
		return array (
			'html' =>$html ,
			'meta_title' => 'Оплата заказа '.$ord,
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Оплата заказа '.$ord);
	}
	public function ok(){
		$ord="";
		$bill='';
		if(isset($_GET['orderNumber']) && !empty($_GET['orderNumber'])) $ord=$_GET['orderNumber'];
		if(isset($_GET['invoiceId']) && !empty($_GET['invoiceId'])) $bill=$_GET['invoiceId'];
		$sum=$_GET['orderSumAmount'];
		$html='<p>Cтатус платежа определить не удалось. Для выяснения причины свяжитесь с менеджерами.</p>';
		if(!empty($ord)){
			$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$ord),array('billnumber'=>$bill,'declined'=>0,'uret'=>1));
			$html="<p>Ваш платеж на сумму {$sum} подтвержден. Информация о данном платеже появится в Личном кабинете через некоторое время.</p>";
		}
		return array (
			'html' =>$html ,
			'meta_title' => 'Оплата заказа '.$ord,
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Оплата заказа '.$ord);
	}
	public function tok(){
		$ord="";
		$bill='';
		if(isset($_GET['OrderId']) && !empty($_GET['OrderId'])) $ord=$_GET['OrderId'];
		if(isset($_GET['PaymentId']) && !empty($_GET['PaymentId'])) $bill=$_GET['PaymentId'];
		$sum=$_GET['Amount']/100;
		$html='<p>Cтатус платежа определить не удалось. Для выяснения причины свяжитесь с менеджерами.</p>';
		if(!empty($ord)){
			$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$ord),array('billnumber'=>$bill,'declined'=>0,'uret'=>1));
			$html="<p>Ваш платеж на сумму {$sum}руб. подтвержден. Информация о данном платеже появится в Личном кабинете через некоторое время.</p>";
		}
		return array (
			'html' =>$html ,
			'meta_title' => 'Оплата заказа '.$ord,
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Оплата заказа '.$ord);
	}
	public function tfail(){
		$ord="";
		$bill='';
		if(isset($_GET['OrderId']) && !empty($_GET['OrderId'])) $ord=$_GET['OrderId'];
		if(isset($_GET['PaymentId']) && !empty($_GET['PaymentId'])) $bill=$_GET['PaymentId'];
		$html='<p>Cтатус платежа определить не удалось. Для выяснения причины свяжитесь с менеджерами.</p>';
		if(!empty($ord)){
			$ms=$_GET['Message'].' '.$_GET['Details'];
			$ms=iconv('utf-8','cp1251',$ms);
			$html="<p>Ваш платеж отклонен!</p><p>Сообщение банка: {$ms}</p>";
			$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$ord),array('billnumber'=>$bill,'declined'=>1,'uret'=>1,'meantypename'=>$ms));
		}
		return array (
			'html' =>$html ,
			'meta_title' => 'Оплата заказа '.$ord,
			'meta_keys' => '',
			'meta_desc' => '',
			'path' => 'Оплата заказа '.$ord);
	}
	public function chk(){
		$settings = new Settings();
		$yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("checkOrder", $settings);
		$res=$yaMoneyCommonHttpProtocol->processRequest($_REQUEST);
		exit;
	}
	public function avi(){
		$settings = new Settings();
		$yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("paymentAviso", $settings);
		$res=$yaMoneyCommonHttpProtocol->processRequest($_REQUEST);
		if (!($res===true))	exit;
/* успешное подтверждение платежа
[2016-04-19 10:06:15] Request: Array
(
    [orderNumber] => 47422
    [orderSumAmount] => 2727.87
    [cdd_exp_date] => 1217
    [shopArticleId] => 286375
    [paymentPayerCode] => 4100322062290
    [paymentDatetime] => 2016-04-19T10:06:15.537+03:00
    [cdd_rrn] => 
    [external_id] => deposit
    [paymentType] => AC
    [requestDatetime] => 2016-04-19T10:06:15.752+03:00
    [depositNumber] => 8coCu8es2D9fsaRkIATDwhKTDnwZ.001f.201604
    [cps_user_country_code] => PL
    [orderCreatedDatetime] => 2016-04-19T10:06:15.129+03:00
    [sk] => ue4555874f452c01f84df3790b8028eeb
    [action] => paymentAviso
    [shopId] => 52961
    [Middlename] => Валерьевич
    [scid] => 534387
    [shopSumBankPaycash] => 1003
    [shopSumCurrencyPaycash] => 10643
    [rebillingOn] => false
    [orderSumBankPaycash] => 1003
    [cps_region_id] => 10765
    [orderSumCurrencyPaycash] => 10643
    [merchant_order_id] => 47422_190416100546_00000_52961
    [Lastname] => Aлексей
    [cdd_pan_mask] => 444444|4448
    [customerNumber] => 1024
    [OrderId] => 47422
    [Firstname] => Серегин
    [yandexPaymentId] => 2570036730138
    [invoiceId] => 2000000753753
    [shopSumAmount] => 2632.39
    [md5] => 7C121423CD79884EE95734E252FA9BDF
    [PHPSESSID] => fcfb61a916ee06664ea59ff1119b5e7d
)
*/	
	$res=$_REQUEST;
	$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$res['orderNumber']),array('billnumber'=>$res['invoiceId'],'approved'=>1,'declined'=>0,'orderamount'=>$res['orderSumAmount'],'shopsumamount'=>$res['shopSumAmount'],'meantypename'=>$res['paymentType'].' '.$res['yandexPaymentId']));
	exit;
	}
	public function tnot(){
		if (isset($this->sets['tinkoff']) && $this->sets['tinkoff']==1) {
			require_once "includes/Debug.php";
			require_once "includes/TinkoffMerchantAPI.php";
		} else die('Service shutdown...');
		$api=new TinkoffMerchantAPI();
		if (!isset($_POST['Token'])) return;
		$res=$api->genToken($_POST);
		if ($_POST['Token']!=$res) 	{
			file_put_contents ( 'data/System/pdata.txt', "Pay Post ".print_r($_POST,true)." get ".print_r($_GET,true).print_r(array('tnot'=>'token error')) , FILE_APPEND  );
			die('ERR');
		}
		if ($_POST['TerminalKey']!=$api->__get('TerminalKey')) {
			file_put_contents ( 'data/System/pdata.txt',"Stat=Autorized key error qy ".print_r($qy,true), FILE_APPEND  );
			return;
			}
		if (trim($_POST['Status'])=='AUTHORIZED') {
			
			$qy=$this->db->get(TABLE_PAYMENTS,array('OrderNumber'=>$_POST['OrderId']));
			file_put_contents ( 'data/System/pdata.txt',"Stat=Autorized qy ".print_r($qy,true), FILE_APPEND  );
			if (count($qy)>0){
				if ($qy[0]['orderamount']==($_POST['Amount']/100)) {
						file_put_contents ( 'data/System/pdata.txt',"Stat=Autorized say ok ".print_r(array(),true), FILE_APPEND  );
						die("OK");
				}
			} 
			file_put_contents ( 'data/System/pdata.txt',"Stat=Autorized say error ".print_r(array(),true), FILE_APPEND  );
			die("ERROR");
		}
		if (trim($_POST['Status'])=='CONFIRMED') {
			$res=$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$_POST['OrderId']),array('billnumber'=>$_POST['PaymentId'],'approved'=>1,'declined'=>0,'orderamount'=>$_POST['Amount']/100,'shopsumamount'=>$_POST['Amount']/100,'meantypename'=>'PAN'.$_POST['Pan']));
//			file_put_contents ( 'data/System/pdata.txt',"Stat=confirmed qy ".print_r(array($res),true), FILE_APPEND  );
			die("OK");
		}
		if (trim($_POST['Status'])=='REJECTED') {
			$res=$this->db->update(TABLE_PAYMENTS,array('ordernumber'=>$_POST['OrderId']),array('billnumber'=>$_POST['PaymentId'],'approved'=>0,'declined'=>1,'orderamount'=>$_POST['Amount']/100,'shopsumamount'=>$_POST['Amount']/100));
//			file_put_contents ( 'data/System/pdata.txt',"Stat=REJECTED qy ".print_r(array($res),true), FILE_APPEND  );
			die("OK");
		}
		die("OK");
		
	}
	public function tresend(){
		if (isset($this->sets['tinkoff']) && $this->sets['tinkoff']==1) {
			require_once "includes/TinkoffMerchantAPI.php";
			require_once "includes/Debug.php";
		} else die('Service shutdown...');
		$api=new TinkoffMerchantAPI();
		$res=$api->resend();
		print_r($res);
		die('');
		
		
	}


}


$pay = Pay::gI();
//$gmess.=print_r($_GET,true);

if ($_GET['module'] == 'pay')
{
	file_put_contents ( 'data/System/pdata.txt', "Pay Post ".print_r($_POST,true)." get ".print_r($_GET,true) , FILE_APPEND  );
	if(isset($_GET['type']) && method_exists($pay, $_GET['type'])&& ($pay->sets['yak']==1 ||  $pay->sets['tinkoff']==1))
	{
		$content = $pay->{$_GET['type']}();
	}
	else
	{
        error_log("Call 404 from payments.php: 220");
		$pay->_404();
	}

}
?>