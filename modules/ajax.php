<?php
if ($_GET['module'] == 'ajax')
{
	
	if(!empty($_GET['ttype']))
	{
		if(!empty($_GET['long']) && $_GET['long']>0) $long=$_GET['long']; else $long=0;
		$wh='and dt>'.(time()-60*60*24*$long).' and trm<900';
		if ($_GET['ttype']==1)
		{
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=2 ".$wh." ORDER BY dt");
			$rw=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=3 ".$wh." ORDER BY dt");
			$rw1=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw1[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT min(trm) as min,max(trm) as max,avg(trm) as avg FROM ".TABLE_TRM." where (id=2 or id=3) ".$wh." ORDER BY dt");
			$stat=array('min'=>(float)$ni[0]['min'],'max'=>(float)$ni[0]['max'],'avg'=>(float)round($ni[0]['avg'],2));
			echo json_encode(array($rw,$rw1,$stat));
		}
		if ($_GET['ttype']==2)
		{
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=5 ".$wh." ORDER BY dt");
			$rw=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=4 ".$wh." ORDER BY dt");
			$rw1=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw1[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT min(trm) as min,max(trm) as max,avg(trm) as avg FROM ".TABLE_TRM." where (id=4 or id=5) ".$wh." ORDER BY dt");
			$stat=array('min'=>(float)$ni[0]['min'],'max'=>(float)$ni[0]['max'],'avg'=>(float)round($ni[0]['avg'],2));
			echo json_encode(array($rw,$rw1,$stat));
		}
		if ($_GET['ttype']==3)
		{
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=6 ".$wh." ORDER BY dt");
			$rw=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=7 ".$wh." ORDER BY dt");
			$rw1=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw1[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT min(trm) as min,max(trm) as max,avg(trm) as avg FROM ".TABLE_TRM." where (id=6 or id=7) ".$wh." ORDER BY dt");
			$stat=array('min'=>(float)$ni[0]['min'],'max'=>(float)$ni[0]['max'],'avg'=>(float)round($ni[0]['avg'],2));
			echo json_encode(array($rw,$rw1,$stat));
		}
		if ($_GET['ttype']==4)
		{
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=1 ".$wh." ORDER BY dt");
			$rw=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT min(trm) as min,max(trm) as max,avg(trm) as avg FROM ".TABLE_TRM." where id=1 ".$wh." ORDER BY dt");
			$stat=array('min'=>(float)$ni[0]['min'],'max'=>(float)$ni[0]['max'],'avg'=>(float)round($ni[0]['avg'],2));
			echo json_encode(array($rw,$stat));
		}
		if ($_GET['ttype']==5)
		{
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=8 ".$wh." ORDER BY dt");
			$rw=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT * FROM ".TABLE_TRM." where id=9 ".$wh." ORDER BY dt");
			$rw1=array();
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['trm']))
				{
					$rw1[]=array(date('n-j-y G:i',$rval['dt']),(FLOAT)$rval['trm']);
				}
			}
			$ni = $db->get_rows("SELECT min(trm) as min,max(trm) as max,avg(trm) as avg FROM ".TABLE_TRM." where (id=8) ".$wh." ORDER BY dt");
			$stat=array('min'=>(float)$ni[0]['min'],'max'=>(float)$ni[0]['max'],'avg'=>(float)round($ni[0]['avg'],2));
			echo json_encode(array($rw,$rw1,$stat));
		}
		if ($_GET['ttype']=='addcmp'){
			$_SESSION['vs_prds'][] = $_GET['id'];
			$_SESSION['vs_prds']=array_unique($_SESSION['vs_prds']);
			echo 'Добавлено в сравнение';
		}
		if ($_GET['ttype']=='rmcmp'){
			$arr=array();
			foreach($_SESSION['vs_prds'] as $key => $value){
				if ($value!=$_GET['id']) $arr[]=$value;
			}
			$_SESSION['vs_prds']=$arr;
			if (count($_SESSION['vs_prds'])>0) echo '1';
			else echo '0';
		}
		exit;

	}
	if(!empty($_GET['atype']))
	{
		$value = decode_str($_GET['title']);
		$value1 = decode_str($_GET['vid']);
		$sflt=' visible=1 ';
		if ($_GET['sall']==0) $sflt.='and enabled=1 ';
		echo '<option value="0">Любой</option>';
		$sfind=' WHERE ';
		if($_GET['atype'] == 'param_vid')
		{
			if (!$value==0) $sfind.="param_vid = ".quote_smart($value)." and";
			$ni = $db->get_rows("SELECT param_gruppa FROM ".TABLE_PRODUCTS.$sfind.$sflt." GROUP BY param_gruppa ORDER BY param_gruppa");
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['param_gruppa']))
				{
					echo '<option value="'.$rval['param_gruppa'].'">'.$rval['param_gruppa'].'</option>';
				}
			}
		}
		if($_GET['atype'] == 'param_gruppa')
		{
			if (!$value==0) $sfind.="param_gruppa = ".quote_smart($value)." and";
			if (!$value1==0) $sfind.=" param_vid = ".quote_smart($value1)." and";
			$ni = $db->get_rows("SELECT param_sort FROM ".TABLE_PRODUCTS.$sfind.$sflt."  GROUP BY param_sort ORDER BY param_sort");
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['param_sort']))
				{
					echo '<option value="'.$rval['param_sort'].'">'.$rval['param_sort'].'</option>';
				}
			}
		}
		if($_GET['atype'] == 'param_vid1')
		{
			if (!$value==0) $sfind.="param_vid = ".quote_smart($value)." and";
			$ni = $db->get_rows("SELECT param_sort FROM ".TABLE_PRODUCTS.$sfind.$sflt." GROUP BY param_sort ORDER BY param_sort");
			foreach($ni as $rid=>$rval)
			{
				if(!empty($rval['param_sort']))
				{
					echo '<option value="'.$rval['param_sort'].'">'.$rval['param_sort'].'</option>';
				}
			}
		}
		exit;
	}
	if(!empty($_GET['ctype']))
	{
		if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_GET['ctype'])
		{
			$_SESSION['carttr']='ok';
			echo '1';
		}
		else
		{
			if (isset($_SESSION['cartid']))	$db->update(TABLE_CART, array('id'=>trim($_SESSION['cartid'])),array('ip'=>ip(),'isset'=>2));
			echo '0';
		}
		unset($_SESSION['captcha_keystring']);
		exit;
	}
	if ($_GET['type']=='filter'){
		$wh='';
		foreach($_POST as $id => $val){
			if ($id=='catid') continue;
			if (empty($wh)) $wh='where '; else $wh.=' and ';
			$arr=array();
			foreach($val as $key => $value){
				$arr[]=iconv("UTF-8","Windows-1251",$value);
			}
			
			if (count($arr)>1){
				$wh.=$id." in ('".implode("','",array_values($arr))."')";
			} else{
				$wh.=$id."='".$arr[0]."'";	
			}
		}
		if (empty($wh)) $wh='where '; else $wh.=' and ';
		$wh.='cat_id='.$_POST['catid'].' and visible=1 and enabled=1';
		$res=$db->get_rows('select count(id) as cnt from chudo_ishop_products '.$wh);
		echo $res[0]['cnt'];
	}
	if ($_GET['type']=='terms'){
		$html='Не найдено...';
		$res = $db->get(TABLE_SITEMENU,array('vlink'=>'terms', 'deleted'=>0, 'reg'=>0));
		if (count($res)>0) 	$html = HTML::del_mso_code($res[0]['html']);
		echo $html;
		exit;		

	}
	if ($_GET['type']=='clrdbg'){
		unset($_SESSION['deb_msg']);
		exit;		

	}
	if ($_GET['type']=='newhit'){
		$sk = $db->get_rows("SELECT id,(enabled and visible) as enb,cat_id FROM ".TABLE_PRODUCTS." WHERE visible=1 and enabled = 1 and views>0 order by views desc  LIMIT 90");
		if (count($sk)<40) 	{
			$sk1= $db->get_rows("SELECT id,(enabled and visible) as enb,cat_id FROM ".TABLE_PRODUCTS." WHERE visible=1 and enabled = 1 and oldviews>0 order by oldviews desc  LIMIT 90");
			if (count($sk1>0)) $sk=array_merge($sk,$sk1);
		}
		$hit=array();
		foreach($sk as  $val){
			if ($val['enb'] && Site::gI()->get_prdstate($val['cat_id'])) $hit[]=$val['id']; 
		}
		$_SESSION['hitblock']=$hit[array_rand($hit,1)];
		$sk=$db->get(TABLE_PRODUCTS,$_SESSION['hitblock']);
		echo $site->view('ishop/hitblock', array('row' => $sk));
		exit;
	}
	if ($_GET['type']=='mainsearch'){
		$ret='';
		$sech=iconv('utf-8','cp1251',(isset($_GET['term']))?$_GET['term']:'');
		$sech1=str_replace ("'","_",$sech);
		if (strlen($sech)<3) exit;
		$sk1= $db->get_rows("SELECT title FROM ".TABLE_CATEGORIES." WHERE visible=1 and enabled = 1 and title like '%".$sech1."%' limit 5");
		$sk2= $db->get_rows("SELECT title FROM ".TABLE_PRODUCTS." WHERE visible=1 and enabled = 1 and title like '%".$sech1."%' limit 10");
		$sk1=array_merge($sk1,$sk2);
		foreach($sk1 as $value){
//			$vv=explode(" ",$value['title']);
//			foreach($vv as $val){
				if (!(mb_stripos($value['title'],$sech,0,'cp1251')===false)) $ret.=((strlen($ret)>0)?',':'').'"'.$value['title'].'"';
//			}
		}
		$ret='['.$ret.']';
		echo $ret;
		exit;
	}
	if ($_GET['type']=='clearsearch'){
		unset($_SESSION['bsearch_str']);
		if (isset($_SESSION['seach_ref'])) {
			echo $_SESSION['seach_ref'];
			unset($_SESSION['seach_ref']);
		} else echo "/";
		exit;
	}
	
	exit;
}
?>