<?

/*
  <td class=""><a href="service/orders/<?=$val['id']?>"><? ECHO ($tu_on) ?></a>&nbsp;<? echo ($val['status']<6 && $val['summa']-$val['summa']*$val['skidka']/100-$val['sumopl']>0) ? '<a href="service/kvit/'.$val['id'].'">'.$kv_on.'</a>' : ''?>&nbsp;<? echo ($val['status']<6 && $val['summa']-$val['summa']*$val['skidka']/100-$val['sumopl']>0) ? '<a href="service/pay_a/'.$val['id'].'">'.$kr_on.'</a>' : ''?>&nbsp;<? echo ($val['status']<6 && $val['summa']-$val['summa']*$val['skidka']/100-$val['sumopl']>0) ? '<a href="service/qiwi/'.$val['id'].'">'.$qw_on.'</a>' : ''?></td>
*/
$tu_on ='<img alt="Состав" title="Состав заказа" src="'.TEMP_FOLDER.'images/sord.png" />';
$kv_on ='<img alt="Квитанция" title="Распечатать квитанцию" src="'.TEMP_FOLDER.'images/silk/printer_go.png" />';
$kr_on ='<img alt="Оплатить" title="Оплатить заказ" src="'.TEMP_FOLDER.'images/silk/creditcards.png" />';
$qw_on ='<img alt="Qiwi" title="Оплатить через Qiwi" src="'.TEMP_FOLDER.'images/qiwi.png" />';
?>
<div class="col-md-12 pb30">
<div class="area-title bdr">
<h2>Мои заказы</h2>
</div>
<div class="bdr-b pb10">
<label><input class="showact" <?=(isset($_SESSION['ord_showact']) && $_SESSION['ord_showact']==1)?'checked':''?> type="checkbox" />Только активные заказы</label><a class="button c_button" title="Перейти к cписку неотгруженных товаров" onclick="location.href='<?=SITE_URL?>service/expectedgoods'"><span>Список неотгруженных товаров</span></a>
</div>
<? if (count($info)>0) {?>
<p>На данной странице Вы можете оплатить заказ (Кнопка <? echo $kr_on ?> в таблице заказов)</p>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered text-center">
<thead>
<tr class="c-head">
<th >Дата</th>
<th >№ заказа</th>
<th >Стоимость</th>
<th >Оплачено</th>
<th >Отгружено</th>
<th >Статус</th>
<th >Функции</th>
</tr>
</thead>
<tbody>
<?
$summa_all = 0;
$sumopl_all=0;
$sumotgr_all=0;
foreach($info as $id=>$val) {
	$di='';
	if ($val['dopinf']>' ') $di='<br/> см. Доп. информацию ->';
	$bk=(isset(Site::gI()->sets['yak']) && (Site::gI()->sets['yak']==1 && ((Site::gI()->sets['yaktst']==1 && User::gI()->user_role>0) || Site::gI()->sets['yaktst']==0)) && $val['status']<6 && $val['summa']-$val['summa']*$val['skidka']/100-$val['sumopl']>0) ? '<a href="service/pay_a/'.$val['id'].'">'.$kr_on.'</a>' : '';
	$bk=(isset(Site::gI()->sets['tinkoff']) && (Site::gI()->sets['tinkoff']==1 ) && $val['status']<6 && $val['summa']-$val['summa']*$val['skidka']/100-$val['sumopl']>0) ? '<a href="service/pay_a/'.$val['id'].'/tin">'.$kr_on.'</a>' : '';
	$kvit=($val['status']<6 && $val['summa']-$val['summa']*$val['skidka']/100-$val['sumopl']>0) ? '<a href="service/kvit/'.$val['id'].'">'.$kv_on.'</a>' : '';

?>
 <tr>
  <td><?=date('d.m.y', $val['data'])?></td>
  <td class="tcen"><a href="service/orders/<?=$val['id']?>" title="Состав заказа"><?=$val['id']?></a></td>
  <td class="t_price"><?=(($val['summa']>0) ? $this->s_price($val['summa']-($val['summa']*$val['skidka']/100),0) : '&nbsp;')?></td>
  <td class="t_price"><?=(($val['sumopl']>0) ? $this->s_price($val['sumopl'],0) : '&nbsp;')?></td>
  <td class="t_price"><?=(($val['sumotgr']>0) ? $this->s_price($val['sumotgr'],0) : '&nbsp;')?></td>
  <td class=""><?=$val['tstatus'].$di?></td>
  <td class="oper"><a href="service/orders/<?=$val['id']?>"><?=$tu_on?></a><?=$bk.$kvit?></td>
 </tr>
<?
$summa_all += $val['summa']-$val['summa']*$val['skidka']/100;
$sumopl_all += $val['sumopl'];
$sumotgr_all += $val['sumotgr'];
}?>
</tbody>
<tfoot>
<tr>
<td class="cart_all cart_colr" colspan="2">Всего:</td>
<td class="t_price cart_all_p"><?=$this->s_price($summa_all,0)?></td>
<td class="t_price cart_all_p"><?=$this->s_price($sumopl_all,0)?></td>
<td class="t_price cart_all_p"><?=$this->s_price($sumotgr_all,0)?></td>
<td colspan="2"></td>
</tr></tfoot>
</table></div></div>
<div class="bdr-b pb10">
<label><input class="showact" <?=(isset($_SESSION['ord_showact']) && $_SESSION['ord_showact']==1)?'checked':''?> type="checkbox" />Только активные заказы</label><a class="button c_button" title="Перейти к cписку неотгруженных товаров" onclick="location.href='<?=SITE_URL?>service/expectedgoods'"><span>Список неотгруженных товаров</span></a>
</div>
<?} else {?>
	Нет активных заказов...
<?}?>
</div>
<script type="text/javascript">
	function btn_state(){
	$.ajax({url: 'service/orders/ajax?act=togle',cache: false,
		success: function(html){$('#content').html(html);}
	});
		}
	$(document).ready(function () {
	$('.showact').iCheck({checkboxClass: 'icheckbox_flat-green',radioClass: 'iradio_flat-green'});	
	$(".showact").on('ifToggled',function(){btn_state();});

	});
		
	
</script>