<?
$st=SITE_URL;
//if (count($garray)>0 && stripos($st,'test.chudo-k')===false)
{
$pm=array('/[\n\r\t]/');
$pr=array(' ');
$sss=number_format($summacn,2,'.','');
$st="'ecommerce:addTransaction', {'id': '{$last_id}','affiliation': 'Чудо клумба','revenue': '{$sss}'}";
global $gcounter;
$gcounter.="ga('require', 'ecommerce');";
$gcounter.="ga($st);";
foreach($garray as $id=>$val) {
	$res=$this->db->get(TABLE_PRODUCTS,$val['prd_id']);
	$grres=$this->db->get(TABLE_CATEGORIES,$res['cat_id']);
	$nm=$val['name'];
	$nm = preg_replace($pm, $pr, $nm);
	$nm=str_ireplace(',',' ',$nm);
	$nm=str_ireplace("'",' ',$nm);
	$nm=str_ireplace('"',' ',$nm);
	$ty=$grres['title'];
	$ty = preg_replace($pm, $pr, $ty);
	$ty=str_ireplace(',',' ',$ty);
	$ty=str_ireplace("'",' ',$ty);
	$ty=str_ireplace('"',' ',$ty);
	$sum=$val['summa'] - ($val['summa'] *$val['skidka'] )/100;
	$st="'ecommerce:addItem', {'id': '$last_id','name': '$nm','sku': '{$val['prd_id']}','category': '$ty','price': '$sum','quantity': '{$val['count']}'}";
	$gcounter.="ga($st);";
}
	$gcounter.="ga('ecommerce:send');";
}
