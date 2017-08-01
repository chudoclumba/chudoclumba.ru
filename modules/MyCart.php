<?php
if(!class_exists('Site')) die(include '../404.html');
define('NEW_CART', 'ishop');
define('CART', 'ishop_cart');
define('COOKIE_CART', 'cart');
define('RES_CART', 'cartr');
define('CTIME',Site::gI()->sets['cart_time']);


class Cart extends Site
{
	public $cart_cnt = 0;
	public $cart_sum = 0;
    public $cart_sum_for_sale = 0;
	public $double_cart = 0;
	private static $instance;
	private $cart_info = NULL;
	private $cdeb = false;
	private $isnew=FALSE;
	public static function gI() {
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	private function __clone() {}
	private function __construct($fl=false) {
		$this->cdeb=false;
		if ($this->cdeb) print_r([0]);
		$this->db = Site::gI()->db;
		$this->sets = Site::gI()->sets;
		$this->ebox = Site::gI()->ebox;
		$this->detect_cart();
	}

	public function detect_cart() {
	 	global $content;
		if (isset($_SESSION['flaglogout']) && $_SESSION['flaglogout']==1)
		{
			$this->ResetCart();
		}
		if (isset($_SESSION['use_user']) && $_SESSION['use_user']==1 && isset($_SESSION['user']))
		{
			// если только пользовательская корзина, и пользователь, загружаем пользовательскую и выходим.
			if ($this->cdeb) print_r([1]);
			if (!$this->load_cart_user_id($_SESSION['user']))  
			{
				$this->clear_cart($_SESSION['cartid']);
				return;
			}
			$this->load_cart($_SESSION['cartid']);
			$cart_info = NULL;
			$this->get_cart_info();
			return;
		}
		if (!$this->isnew && isset($_COOKIE[NEW_CART]) && !isset($_SESSION['union']) && !isset($_SESSION['use_user']))
		{
			// начальная загрузка и есть карзина по COOKIE получаем id и ставим флаг
			if ($this->cdeb) print_r([2]);
			$_SESSION['cartid']=$_COOKIE[NEW_CART];
			$this->isnew=TRUE;
		}
		if (!empty($_SESSION['cartid']) && $this->is_cart_empty($_SESSION['cartid']))
		{
			// если есть id и корзина пуста - сбрасываем корзину
			if ($this->cdeb) print_r([3]);
			$this->clear_cart($_SESSION['cartid']);
			return;
		}
		if (!$this->isnew)
		{
			// если начальная загрузка И НЕТ корзины по COOKIE 
			if (!empty($_SESSION['user']) && $_SESSION['user']>0)
			{	
				// и есть пользователь - проверяем пользователя
				if (!$this->load_cart_user_id($_SESSION['user'])) 
				{
                    // TODO check empty cart
					// если корзина не существует - выходим
					//if (isset($_SESSION['cartid'])) $this->clear_cart($_SESSION['cartid']); // TODO check empty cart
					//return;
				}
				$this->load_cart($_SESSION['cartid']); // загружаем пользовательскую и выходим
				
			}
			$this->cart_info=NULL;
			$this->get_cart_info();
			return;
		}
		// далее только если есть не пустая корзина по COOKIE 
		$userofcart=$this->get_cart_user($_SESSION['cartid']); // получаем пользователя корзины
		if (!empty($_SESSION['user']) && $_SESSION['user']>0)
		{
			// если пользователь
			if ($userofcart>0 && $userofcart==$_SESSION['user'])
			{
				// пользователь корзины = пользователю, загружаем корзину и выходим
				$this->load_cart($_SESSION['cartid']);
				$this->cart_info=NULL;
				$this->get_cart_info();
				return;
			} 
			if ($userofcart>0)
			{
				// cтранно, но это корзина другого пользователя и такого быть не должно удаляем и выходим.
				$this->clear_cart($_SESSION['cartid']);
				return;
			}
			// это ничейная
			$result = $this->db->get(TABLE_CART, array('userid'=>$_SESSION['user'])); //ищем пользовательскую
			if (count($result) > 0) 
			{
				//да, есть
				if ($this->is_cart_empty($result[0]['id']))
				{
					// пустая - захват ничейной и выход.
					$this->db->exec("update ".TABLE_CART_DET." set cartid='".$result[0]['id']."' where cartid='".trim($_SESSION['cartid'])."'");
					$this->db->exec("delete from ".TABLE_CART." where id='".trim($_SESSION['cartid'])."'");
					$_SESSION['cartid']=$result[0]['id'];
					$this->load_cart($_SESSION['cartid']);
					$unsc=setcookie(NEW_CART,'',time()-3600,'/');
					$this->cart_info=NULL;
					$this->get_cart_info();
					return;
				}
				else
				{	
					if (isset($_GET['type']) && $_GET['type']=='a_login') {
						$this->double_cart=1;
						return;
					}
					if ($this->cdeb) print_r([99]);
					// не пустая - запрос на слияние
					$this->load_cart($_SESSION['cartid']);
					$this->get_cart_info();
					$kcart=$this->cart_info;
                    error_log("Clear CARD from MC:133");
                   // echo "Clear CARD from MC:133";
					unset($_SESSION[CART]);  //TODO empty cart
					if ($this->load_cart_user_id($_SESSION['user'])) $this->load_cart($_SESSION['cartid']);
					$this->cart_info=NULL;
					$this->get_cart_info();
					$pcart=$this->cart_info;
					unset($_SESSION['cartid']);
                    error_log("Clear CARD from MC:141");
              //      echo "Clear CARD from MC:141";
					unset($_SESSION[CART]); //TODO empty cart
					$this->cart_info=NULL;
					$this->cart_cnt=0;
					$this->cart_sum=0;
					$content = array (
						'html' => $this->view('ishop/swcart',array('kcart'=>$kcart,'pcart'=>$pcart)),
						'meta_title' => '',
						'meta_keys' =>  '',
						'meta_desc' => '',
						'left_menu' => false,
						'path' => 'Предупреждение.');
					$_GET['module'] = 'cart';
					$_GET['type']='union';
					return $content;
				}
			}
			else
			{
				// у пользователя нет корзины, захват ничейной, выход
				$this->db->exec("update ".TABLE_CART." set userid='".trim($_SESSION['user'])."' where id='".trim($_SESSION['cartid'])."'");
				$this->load_cart($_SESSION['cartid']);
				$this->cart_info=NULL;
				$this->get_cart_info();
				return;
			}
		}
		else
		{
			// есть корзина по COOCIES и нет пользователя
			if ($userofcart==0)
			{
				// ничейная - загружаем и выходим
				$this->load_cart($_SESSION['cartid']);
				$this->cart_info=NULL;
				$this->get_cart_info();
				return;
			}
			if ($userofcart>0)
			{
				// чужая - сброс COOCIES, id и выход
				$unsc=setcookie(NEW_CART,'',time()-3600,'/');
				unset($_SESSION['cartid']);
				return;
			}
		}
	}


	public function ResetCart(){
		if ($this->cdeb) print_r([99]);
        error_log("Clear CARD from MC:193");
      //  echo "Clear CARD from MC:193";
			unset($_SESSION[CART]); //TODO empty cart
			unset($_SESSION['cartid']);
			unset($_SESSION['use_user']);
			$this->cart_info=NULL;
			$this->cart_cnt=0;
			$this->cart_sum=0;
			unset($_SESSION['flaglogout']);
	}

	public function get_cart_user($cartid)  // определяет пользователя корзины
	{
		$result = $this->db->get(TABLE_CART, array('id'=>trim($cartid)));
		if (!(count($result) > 0)) return FALSE;
		return $result[0]['userid'];
	}

	private function load_cart_user_id($userid)  // получает ид корзины пользователя, ложь если не существует
	{
		$result = $this->db->get(TABLE_CART, array('userid'=>trim($userid)));
		if (!(count($result) > 0)) return FALSE;
		$_SESSION['cartid']=$result[0]['id'];
		return TRUE;
	}
	private function is_cart_empty($cartid)	// истина, если корзина пуста
	{
		$result = $this->db->get(TABLE_CART_DET, array('cartid'=>trim($cartid)));
		return !(count($result) > 0);
	}
	function cart($type = 0)
	{
		if ($this->double_cart==1) return 'double_cart';
		return $this->view('ishop/cart');
	}
	private function getnewid()	
	{
		do
		{
			$arr = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');  
	    	$id = "";
	    	for($i = 0; $i < 10; $i++)  
		    {  
		      $index = rand(0, count($arr) - 1);  
		      $id .= $arr[$index];  
		    }
			$result = $this->db->count(TABLE_CART,$id);
		}
		while ($result>0);
		$us=(isset($_SESSION['user']) && $_SESSION['user']>0)?$_SESSION['user']:0;
		$this->db->insert(TABLE_CART, array('id'=>$id, 'date'=> time(),'datec'=> time(),'ip'=> ip(), 'userid'=>$us));
		if ($us==0) setcookie(NEW_CART,$id,time()+60*60*24*CTIME,'/');
		return $id;
	}

	private function write_cart($cartid)
	{
		if ( !($cartid>'  ') ) return false;
//		print_r(array('write_cart'=>$cartid));
		$this->db->exec("delete from ".TABLE_CART_DET." where cartid='".$cartid."'");
		foreach($_SESSION[CART] as $id=>$param)
		{
			if ($param['count']>0) $this->db->insert(TABLE_CART_DET, array('cartid'=>$cartid, 'prdid'=> $id, 'cnt'=>$param['count']));
		}
	}

	private function load_cart($cartid)
	{
		if (!isset($_SESSION[CART]))
		{
			$rows = $this->db->get_rows("SELECT * FROM ".TABLE_CART_DET." where cartid='".trim($cartid)."'");
			foreach($rows as $rid=>$val)
				$_SESSION[CART][$val['prdid']]['count']=$val['cnt'];
		}
		return (isset($_SESSION[CART]));
	}

	public function addprd($prd_id,$tv=0)
	{ 	
		if (!isset($_SESSION['cartid']) )
			$_SESSION['cartid']=$this->getnewid();
		if (!isset($_SESSION[CART][$prd_id]['count']))
			$_SESSION[CART][$prd_id]['count'] = 0;
		$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,p.saletype from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") or oc.id='{$_SESSION['cartid']}' group by prdid) as cr on cr.prdid=p.id WHERE p.id={$prd_id}";
		$prdi=$this->db->get_rows($q);
		if ($prdi[0]['saletype']==1 && $prdi[0]['ost']<=0) return FALSE;
		$_SESSION[CART][$prd_id]['count'] += (!empty($_POST['kolvo'])) ? $_POST['kolvo'] : 1;
		$result = $this->db->get(TABLE_CART_DET, array('cartid'=>trim($_SESSION['cartid']),'prdid'=>$prd_id));
		if(count($result) > 0) $this->db->update(TABLE_CART_DET, array('cartid'=>trim($_SESSION['cartid']),'prdid'=>$prd_id),array('cnt'=>$_SESSION[CART][$prd_id]['count']));
		else
			$this->db->insert(TABLE_CART_DET, array('cartid'=>trim($_SESSION['cartid']),'prdid'=>$prd_id,'cnt'=>$_SESSION[CART][$prd_id]['count']));
		$this->db->update(TABLE_CART, array('id'=>trim($_SESSION['cartid'])),array('date'=>time(),'ip'=>ip(),'isset'=>0));
		$this->cart_info=NULL;
		$this->get_cart_info();
		return TRUE;
	}

	public function upd_cart_prd($cartid,$id,$val)
	{	
		$_SESSION[CART][$id]['count'] = $val;
		if ($val==0){
			$res=$this->db->exec("delete from ".TABLE_CART_DET." where cartid='".trim($cartid)."' and prdid=".$id);
            error_log("Clear CARD from MC:295");
        //    echo "Clear CARD from MC:295";
			unset($_SESSION[CART][$id]); //TODO empty cart
			return $res;
		}
		$result = $this->db->get(TABLE_CART_DET, array('cartid'=>trim($cartid),'prdid'=>$id));
		if(count($result) > 0) $res=$this->db->update(TABLE_CART_DET, array('cartid'=>trim($cartid),'prdid'=>$id),array('cnt'=>$val));
		else $res=$this->db->insert(TABLE_CART_DET, array('cartid'=>trim($cartid),'prdid'=>$id,'cnt'=>$val));
		return $res;
	}

	public function update_cart()
	{	
		if (!empty($_POST['count']))
			foreach($_POST['count'] as $id=>$val)
				if ($_SESSION[CART][$id]['count']!=$val) $this->upd_cart_prd($_SESSION['cartid'],$id,$val);
		if (!empty($_POST['del']))
			foreach($_POST['del'] as $id=>$val)
				$this->upd_cart_prd($_SESSION['cartid'],$id,0);
		$this->db->update(TABLE_CART, array('id'=>trim($_SESSION['cartid'])),array('date'=>time(),'ip'=>ip(),'isset'=>0));
		$this->cart_info=NULL;
		$this->get_cart_info();
	}

	public function setcnt(){
		$prd_cnt=(isset($_GET['params'][0]))?(int)$_GET['params'][0]:1;
		$prd_id=(isset($_GET['id']))?(int)$_GET['id']:0;
		if ($prd_id==0) die(json_encode(array('res'=>0)));
		if (!isset($_SESSION['cartid'])){
			die(json_encode(array('res'=>2)));
		}
		$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,p.saletype from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") and not oc.id='{$_SESSION['cartid']}' group by prdid) as cr on cr.prdid=p.id WHERE p.id={$prd_id}";
		$prdi=$this->db->get_rows($q);
		if ($prdi[0]['saletype']==1)
			if ($prd_cnt>$prdi[0]['ost']) $prd_cnt=($prdi[0]['ost']>0)?$prdi[0]['ost']:0;
		$res=$this->upd_cart_prd($_SESSION['cartid'],$prd_id,$prd_cnt);
		$this->db->update(TABLE_CART, array('id'=>trim($_SESSION['cartid'])),array('date'=>time(),'ip'=>ip(),'isset'=>0));
		$this->cart_info=NULL;
		$this->get_cart_info();
		$sale=0;
		if (isset($_SESSION['user']) && $_SESSION['user']>0){
			$orders1 = $this->db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$_SESSION['user']."' && status != 6");
			if(!empty($this->sets['mod_prd_skidka']) && !empty($orders1['0']['summa'])){
				$orders = $this->db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
			}
			if ($_SESSION['user']>0) $sale=User::gI()->user['sale'];
			if ($orders['0']['percent']>$sale) $sale=$orders['0']['percent'];
		}
		$skidka = (!empty($sale)) ? ($this->cart_sum_for_sale*$sale)/100 : 0;
		$psum=$this->cart_info[$prd_id]['cnt']*$this->cart_info[$prd_id]['price'];
		if (!($res===false)) die(json_encode(array('res'=>1,'cnt'=>$prd_cnt,'psum'=>number_format($psum,2,'.','').' &#8381;','sum'=>number_format($this->cart_sum,2,'.','').' &#8381;','sale'=>number_format($skidka,2,'.','').' &#8381;','asum'=>number_format($this->cart_sum-$skidka,2,'.','').' &#8381;','cart'=>$this->cart())));
		
		die(json_encode(array('res'=>0)));
	}

	public function removeprd(){  // TODO изменить скидку заказа
		$prd_id=(isset($_GET['id']))?(int)$_GET['id']:0;
		if ($prd_id==0) die(json_encode(array('res'=>0)));
		if (!isset($_SESSION['cartid'])){
			die(json_encode(array('res'=>2)));
		}
		$res=$this->upd_cart_prd($_SESSION['cartid'],$prd_id,0);
		$this->db->update(TABLE_CART, array('id'=>trim($_SESSION['cartid'])),array('date'=>time(),'ip'=>ip(),'isset'=>0));
		$this->cart_info=NULL;
		$this->get_cart_info();
		$sale=0;
		if (isset($_SESSION['user']) && $_SESSION['user']>0){
			$orders1 = $this->db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$_SESSION['user']."' && status != 6");
			if(!empty($this->sets['mod_prd_skidka']) && !empty($orders1['0']['summa'])){
				$orders = $this->db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
			}
			if ($_SESSION['user']>0) $sale=User::gI()->user['sale'];
			if ($orders['0']['percent']>$sale) $sale=$orders['0']['percent'];
		}
		$skidka = (!empty($sale)) ? ($this->cart_sum_for_sale*$sale)/100 : 0;
		if (!($res===false)) die(json_encode(array('res'=>1,'sum'=>number_format($this->cart_sum,2,'.','').' &#8381;','sale'=>number_format($skidka,2,'.','').' &#8381;','asum'=>number_format($this->cart_sum-$skidka,2,'.','').' &#8381;','cart'=>$this->cart())));
		die(json_encode(array('res'=>0)));
	}

	public function clear_cart()
	{
		$unsc=false;
        error_log("Clear CARD from MC:377");
       // echo "Clear CARD from MC:377";
		unset($_SESSION[CART]); //TODO empty cart
		if (isset($_SESSION['cartid']) && trim($_SESSION['cartid'])>' ')
			if (isset($_SESSION['user']) && $_SESSION['user']>0){
				$this->db->exec("delete from ".TABLE_CART_DET." where cartid='".trim($_SESSION['cartid'])."'");
			}else	
			{
				$this->db->exec("delete from ".TABLE_CART_DET." where cartid='".trim($_SESSION['cartid'])."'");
				$this->db->exec("delete from ".TABLE_CART." where id='".trim($_SESSION['cartid'])."'");
				$unsc=setcookie(NEW_CART,'',time()-3600,'/');
				unset($_SESSION['cartid']);
			}
		$this->cart_info=NULL;
		$this->cart_sum=0;
		$this->cart_cnt=0;
		return $unsc;
	}

	public function get_cart_info() //TODO Скидки
	{
		if($this->cart_info == null)
		{
			$sum=0;
			$sum_for_sale = 0;
			$cnt=0;
			$prd = NULL;
			if(array_key_exists(CART, $_SESSION) && count($_SESSION[CART]) > 0)
			{
				$prd = array();
				foreach($_SESSION[CART] as $id=>$param)
				{
					$prd_inf = explode('_', $id);
					$g[] = $prd_inf['0'];
					$cnt+=$param['count'];
				}
				$g = array_unique($g);
				$pr_line = "'".implode("', '", $g)."'";
				$q = "select p.*,t.name as pername,t.date as dt from ".TABLE_PRODUCTS." p left join ".TABLE_TPER." t on p.tper=t.id where p.id in (".$pr_line.") order by tper,title";
				$q_r = $this->db->get_rows($q);
				foreach($q_r as $row)
				{
					$prd[$row['id']]['prdid'] = $row['id'];
					$prd[$row['id']]['name'] = $row['title'];
					$prd[$row['id']]['tsena'] = $row['tsena'];
					$prd[$row['id']]['foto'] = $row['foto'];
					$prd[$row['id']]['tper'] = $row['tper'];
					$prd[$row['id']]['vlink'] = $row['vlink'];
					$prd[$row['id']]['cat_id'] = $row['cat_id'];
					$prd[$row['id']]['active'] = ($row['enabled'] && $row['visible']);
					$prd[$row['id']]['cnt'] = $_SESSION[CART][$row['id']]['count'];
					$prd[$row['id']]['param_kodtovara'] = $row['param_kodtovara'];
					$prd[$row['id']]['param_kolichestvo'] = $row['param_kolichestvo'];
					$prd[$row['id']]['skidka'] = $row['skidka'];
					$prd[$row['id']]['price'] =$row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']);
					$prd[$row['id']]['param_srokpostavki'] = $row['pername'];
					$prd[$row['id']]['dt'] = $row['dt'];
					if ($row['enabled'] && $row['visible']) {
					    if(!Site::gI()->isProductForSale($row['id']))
                        {
                            $sum_for_sale += ($row['tsena'])*$_SESSION[CART][$row['id']]['count'];
                        }
					    $sum+=($row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']))*$_SESSION[CART][$row['id']]['count'];
                    }
				}
			}
			$this->cart_cnt=$cnt;
			$this->cart_sum=$sum;
			$this->cart_sum_for_sale = $sum_for_sale;
			$this->cart_info = $prd;
			return $prd;
		}
		return $this->cart_info;
	}
	public function get_cart_ord() //TODO Скидки
	{
		$periods=array();
		$sum=0;
		$cnt=0;
		$prd = NULL;
		if(array_key_exists(CART, $_SESSION) && count($_SESSION[CART]) > 0)
		{
			$prd = array();
			foreach($_SESSION[CART] as $id=>$param)
			{
				$prd_inf = explode('_', $id);
				$g[] = $prd_inf['0'];
			}
			$g = array_unique($g);
			$pr_line = "'".implode("', '", $g)."'";
			$q_r = $this->db->get_rows("select p.*, c.title as catname from ".TABLE_PRODUCTS." p left join ".TABLE_CATEGORIES." c on c.id=p.cat_id where p.id in (".$pr_line.") and p.visible=1 and p.enabled=1 order by p.tper,p.title");
			$per="";
			foreach($q_r as $row)
			{
				$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,p.saletype from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") and not oc.id='{$_SESSION['cartid']}' group by prdid) as cr on cr.prdid=p.id WHERE p.id={$row['id']}";
				$prdi=$this->db->get_rows($q);
				$tcnt=$_SESSION[CART][$row['id']]['count'];
				if ($prdi[0]['saletype']==1)
					if ($tcnt>$prdi[0]['ost']) $tcnt=($prdi[0]['ost']>0)?$prdi[0]['ost']:0;
				if ($tcnt==0) continue;
				$prd[]=array('prdid'=> $row['id'],
				'name'=> $row['title'],
				'tper'=> $row['tper'],
				'foto'=> $row['foto'],
				'vlink'=> $row['vlink'],
				'cat_id'=> $row['cat_id'],
				'catname'=> $row['catname'],
				'cnt'=> $tcnt,
				'param_kodtovara'=> $row['param_kodtovara'],
				'param_kolichestvo'=> $row['param_kolichestvo'],
				'price'=>$this->skidka($row['tsena'], $row['skidka']),
				'param_srokpostavki'=> $row['param_srokpostavki']);
				$sum+=$this->skidka($row['tsena'], $row['skidka'])*$tcnt;
				$cnt+=$tcnt;
			}
		}
		return array('tov'=>$prd,'summa'=>$sum,'count'=>$cnt);
	}
	function test(){
		print_r($this->cart_info);
		die(" кол-во {$this->cart_cnt}");
	}

}
if (isset($_POST['union']) && $_POST['union']=='use_user')
{
	$_SESSION['use_user']=1;
	unset($_POST['use_user']);
}
global $db;
if (isset($_POST['union']) && $_POST['union']=='union')
{
	$result = $db->get(TABLE_CART, array('userid'=>$_SESSION['user']));
	$pcartid=$result[0]['id'];
	$kcartid=$_COOKIE[NEW_CART];
	$kcart=$db->get_rows("SELECT * FROM ".TABLE_CART_DET." where cartid='".trim($kcartid)."'");
	foreach($kcart as $rid=>$val)
	{
		$result = $db->get(TABLE_CART_DET, array('cartid'=>trim($pcartid),'prdid'=>$val['prdid']));
		if(count($result) > 0) $db->update(TABLE_CART_DET, array('cartid'=>trim($pcartid),'prdid'=>$val['prdid']),
			array('cnt'=>$val['cnt']+$result[0]['cnt']));
		else
			$db->insert(TABLE_CART_DET, array('cartid'=>trim($pcartid),'prdid'=>$val['prdid'],'cnt'=>$val['cnt']));
	}
	$db->exec("delete ".TABLE_CART.", ".TABLE_CART_DET." from ".TABLE_CART.", ".TABLE_CART_DET." where ".TABLE_CART.".id='".trim($kcartid)."' and ".TABLE_CART_DET.".cartid='".trim($kcartid)."'");
	$isset=setcookie(NEW_CART,'',time()-3600,'/');
	$_SESSION['union']=1;
	unset($_POST['union']);
	unset($_SESSION['cartid']);
	unset($_SESSION['ishop_cart']);
}



$cart=Cart::gI();

if ($_GET['module'] == 'cart')
{
	if(isset($_GET['type']) && method_exists($cart, $_GET['type']))
	{
		$content = $cart->{$_GET['type']}();
	}
	else
	{
		if (empty($content)) {
            error_log("Call 404 from MyCart.php: 515");
		    $cart->_404();
        }
	}

}
 
?>