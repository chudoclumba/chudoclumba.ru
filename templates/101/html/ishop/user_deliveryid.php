<div class="prdcard">
<p class="ppr_name">Идентификаторы отправлений:</p>
<?
foreach($info as $key => $val){
	$ss=$val['value'];
	if (CheckSPI($ss)) $ss='<a href="'.SITE_URL.'service/track/'.$val['value'].'">'.$val['value'].'</a>';
	$str= '<p>'.date('d-m-Y',$val['date']).'&nbsp;<a href="'.SITE_URL.'service/orders/'.$val['orderid'].'">Заказ № '.$val['orderid'].'</a> Идентификатор: '.$ss.'&nbsp;';
	if (!empty($val['comment'])){
		$str.='<br/>&nbsp;'.str_replace("\n",'<br/>&nbsp;',$val['comment']);
	}
	$str.='</p>';
	echo $str;
}
?>
</div>
