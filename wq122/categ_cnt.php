<?php
define('SQLLOG', 0);
//$tm=microtime(true);
define('ROOT_DIR', '/home/c/cu82628/public_html/');
define('INC_DIR', ROOT_DIR.'includes/');
include INC_DIR.'dbconf.php';
include INC_DIR.'dbtables.php';
require INC_DIR.'dbconnect.php';
global $db;
$bbb=array();

	$bbb=array();
	$rcnt=0;
	$res=g_a_s(11,1);
	array_unshift($bbb,array(11));
	for($i=count($bbb);$i>0;$i=$i-1){
		foreach($bbb[$i-1] as $cid){
			$q="SELECT count(id) as cnt FROM ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id WHERE cat_id = '".$cid."' && enabled=1 && visible=1 && ((p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)>0 and p.saletype=1) or p.saletype=0)";
			$rr=$db->query_first($q);
			$rr1=$db->query_first('select sum(cnt) as sm from '.TABLE_CATEGORIES.' where enabled=1 and visible=1 and parent_id='.$cid);
			$ocn=$rr['cnt']+$rr1['sm'];
			if ($ocn>=0){
				$db->exec('update '.TABLE_CATEGORIES.' set cnt='.$ocn.' where id='.$cid);
				$rcnt++;
			}	 
		}
	}
file_put_contents ( ROOT_DIR.'data/System/pcron.txt', print_r(date('d/m/y H:i').' '.$rcnt."\n",true) , FILE_APPEND  );


function g_a_s($cat_id,$crb=0){
	global $db,$bbb;
	$q=array();
	if(!is_array($cat_id))
	{
		$cat_id = array($cat_id);
	}
	if(count($cat_id) > 0)
	{
		sort($cat_id,SORT_NUMERIC);
		$rows = $db->get_rows("SELECT id FROM ".TABLE_CATEGORIES." WHERE parent_id IN (".implode(',',$cat_id ).")");
		if(count($rows) > 0)
		{
			$qas1=array();
			foreach($rows as $row)
			{
				$qas1[] = $row['id'];
			}
			if ($crb==1) $bbb[]=$qas1;
			$q=array_merge($q,$qas1,g_a_s($qas1,$crb));
		}
	}
	return $q;
}
