<?
$pm=array('/[\n\r\t]/');
$pr=array(' ');
$sss=number_format($summacn,2,'.','');
$st1='<script type="text/javascript">window.dataLayer = window.dataLayer || []; window.dataLayer.push({"ecommerce":{"purchase": {"actionField": {"id": "'.$last_id.'","goal_id": "1464109","revenue": "'.$sss.'"},"products": [';
$st='';
foreach($garray as $id=>$val) {
	if (!empty($st)) $st.=',';
	$res=$this->db->get(TABLE_PRODUCTS,$val['prd_id']);
	$grres=$this->get_catnm($res['cat_id'],2);
	$nm=$val['name'];
	$nm = preg_replace($pm, $pr, $nm);
	$nm=str_ireplace(',',' ',$nm);
	$nm=str_ireplace("'",' ',$nm);
	$nm=str_ireplace('"',' ',$nm);
	$ty=$grres;
	$ty = preg_replace($pm, $pr, $ty);
	$ty=str_ireplace(',',' ',$ty);
	$ty=str_ireplace("'",' ',$ty);
	$ty=str_ireplace('"',' ',$ty);
	$sum=$val['summa'] - ($val['summa'] *$val['skidka'] )/100;
	$sum=number_format($sum,2,'.','');
	$st.="{'name': '$nm','id': '{$val['prd_id']}','category': '$ty','price': '$sum','quantity': '{$val['count']}'}";
}
$st1.=$st;
$st1.=']}}});</script>';

echo $st1;
