<?
$cnt=count($prdout);
$keys=array_keys($prdout);
$tu_on ='<img alt="Состав" style="vertical-align: middle" title="Состав заказа" width="18" height="19" src="'.TEMP_FOLDER.'images/sord.png" />';
$kv_on ='<img alt="Квитанция" style="vertical-align: middle" title="Распечатать квитанцию" width="18" height="19" src="'.TEMP_FOLDER.'images/silk/printer_go.png" />';
$kr_on ='<img alt="Оплатить" style="vertical-align: middle" title="Оплатить банковской картой" width="18" height="19" src="'.TEMP_FOLDER.'images/silk/creditcards.png" />';
$qw_on ='<img alt="Qiwi" style="vertical-align: middle" title="Оплатить через Qiwi" width="18" height="19" src="'.TEMP_FOLDER.'images/qiwi.png" />';
?>
<div class="prdcard">
<p style="padding:20px; font-weight:bold;">Благодарим Вас за заказ.</p> 
<p>Оформлены следующие заказы:</p>
<?
$st='';
foreach($prdout as $key => $val){
	$st.="<p><b>";
	$st.=($val['new'])?'Новый заказ ':'Дополнение к заказу ';
	$st.="№ $key на сумму ".number_format(Site::gI()->getRealOrderSumm($key),2,'.','');
	$st.=($val['new'])?'':" (Полная сумма заказа ".number_format($val['totsum'],2,'.','').")";
	$st.='</b>';
	if (!isset($_SESSION['user']) && $val['ha']!='*') {
		$st.='<br>&nbsp;Для просмотра статуса заказа используйте ссылку: <a href="'.SITE_URL.'service/vieword/'.$key.'_'.$val['ha'].'">'.SITE_URL.'service/vieword/'.$key.'_'.$val['ha'].'</a>';
	}
	$st.="</p>";
}
/*$st.='<br/><p>';
$st.='<img alt="Оплатить" style="vertical-align: middle" title="Оплатить банковской картой" width="18" height="19" src="'.TEMP_FOLDER.'images/silk/creditcards.png" />&nbsp;<b><u><a href="'.SITE_URL.'service/pay_a/'.$key.'/tin" target="_blank">Оплатить картой on-line</a></b></u>';
$st.="</p>";*/
$st.='<br/><p>';
if ($issend) $st.= "Подробная информация выслана на Ваш email ( $mail ).";
	else $st.= "Уведомление на Ваш email ( $mail ) выслать не удалось. Для уточнения, свяжитесь с нашими менеджерами по телефону.";
$st.="</p>";
	
echo $st;					
 ?>
</div>
