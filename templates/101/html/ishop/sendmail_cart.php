<? 
$sss=0;
foreach($prdout as $key => $zak){
	$st='';
	$per=$this->db->get(TABLE_TPER,$zak['tp']);
	$prd=$zak['tov'];
	$st.="<p style=\"margin:8px 0 0 0;\"><b>";
	$st.=($zak['new'])?'Новый заказ ':'Дополнение к заказу ';
	$st.="№ $key на сумму ".number_format($zak['sum'],2,'.','');
	$st.=($zak['new'])?'':" (Полная сумма заказа ".number_format($zak['totsum'],2,'.','').")";
	$st.='</b>';
	if ($zak['ha']!='*') {
		$st.='<br>&nbsp;Для просмотра статуса заказа используйте ссылку: <a href="'.SITE_URL.'service/vieword/'.$key.'_'.$zak['ha'].'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderinfo">'.SITE_URL.'service/vieword/'.$key.'_'.$zak['ha'].'</a>';
	}
	$st.="</p>";
	
	$st.="<p style=\"margin:5px 0 3px 0;\">Условия поставки: {$per['name']}<br/>Ориентировочная дата начала отгрузок: ".date('d.m.Y',$per['date'])."</p>";
	echo $st;
	$sss+=$zak['sum'];
  	
  
  

?><table style="border-collapse:collapse;width: 100%"><tr><th style="border-width:1px;border-style:solid;border-color:#95C12B;width:100px">Фото</th><th style="border-width:1px;border-style:solid;border-color:#95C12B">Наименование</th><th style="border-width:1px;border-style:solid;border-color:#95C12B;width: 80px">Цена</th><th style="border-width:1px;border-style:solid;border-color:#95C12B;width: 80px">Кол-во</th><th style="border-width:1px;border-style:solid;border-color:#95C12B;width: 80px">Сумма</th></tr>
<? 
foreach($prd as $val){
	$link=SITE_URL.'ishop/product/'.$val['prdid'].'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderinfo';
	if(!empty($val['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$val['vlink'].'?utm_source=email_transaction&utm_medium=email&utm_campaign=orderinfo';
	}
	
?>
 <tr><td style="border-width:1px;border-style:solid;border-color: #95C12B"><a href="<?=$link?>">
   <img border="0" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=100&y=100&crop" style="display:block" /></a></td>  
  <td style="border-width:1px;border-style:solid;border-color:#95C12B;padding:0 5px"><?=$val['param_kodtovara']?><br><a style="color:#1C509A;" href="<?=$link?>"><?=$val['name']?></a></td>
  <td style="border-width:1px;border-style:solid;border-color:#95C12B;text-align: right;padding-right: 5px"><?=number_format($val['price'],2)?></td><td style="border-width:1px;border-style:solid;border-color:#95C12B;text-align: center;"><?=$val['cnt']?></td>
  <td style="border-width:1px;border-style:solid;border-color:#95C12B;text-align: right;color:#e51a4b;padding-right: 5px"><?=number_format($val['price']*$val['cnt'],2)?></td></tr>
<? }
$st='';
if ($sale>0){ 
$st=' с учетом скидки';
?>

<tr><td  style="text-align: right;" colspan="4">Скидка:</td><td style="border-width:1px;border-style:solid;border-color:#95C12B;text-align: right;padding-right:5px"><?=$sale?>%</td></tr><?}?>
 <tr><td style="text-align: right;font-weight: bold;" colspan="4">Сумма<?=$st?>:</td>
 <td style="border-width:1px;border-style:solid;border-color:#95C12B;text-align: right;font-weight:bold;padding-right:5px;color:#e51a4b"><?=number_format($zak['sum'],2)?></td></tr>
</table>
<?}
if (count($prdout)>1){?>
<p style="font-weight:bold">Итого сумма дополнений (заказов) за текущий сеанс: <span style="color:#e51a4b"><?=number_format($sss,2)?></span></p>
<?}