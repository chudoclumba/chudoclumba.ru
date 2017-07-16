<div class="col-md-12 pb30">
<div class="area-title bdr">
<h2>Мой личный счет</h2>
</div>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered text-center">
<thead>
<tr class="c-head">
<th>Дата</th>
<th>Документ</th>
<th style="min-width: 60px">Приход</th>
<th style="min-width: 60px">Списание</th>
<th style="min-width: 60px">Баланс</th>
<th>К заказу</th>
</tr>
</thead>
<tbody>

<?
//$tu_on ='<img alt="Состав" title="Состав заказа" width="18" height="19" src="'.TEMP_FOLDER.'images/sord.png" />';
$sumin = 0;
$sumout = 0;
$bal=0;
foreach($info as $id=>$val) {
$bal=$bal+$val['sumin']-$val['sumout'];
		$r = $this->db->count(TABLE_ORDERS,$val['order_id']);
		if ($r>0)$q='<a href="service/orders/'.$val['order_id'].'" title="Состав заказа">'.$val['order_id'].'</a>';
		else $q=$val['order_id'];
?>
 <tr>
  <td class=""><?=date('d.m.y', $val['tdate'])?></td>
  <td class=""><?=$val['docname']?></td>
  <td class="t_price"><?=(($val['sumin']!=0)?$this->s_price($val['sumin'],0):'&nbsp;')?></td>
  <td class="t_price"><?=(($val['sumout']!=0)?$this->s_price($val['sumout'],0):'&nbsp;')?></td>
  <td class="t_price"><?=$this->s_price($bal,0)?></td>
  <td class=""><?=$q?></td>
 </tr>
<?
$sumin=$sumin+$val['sumin'];
$sumout=$sumout+$val['sumout'];

}?>
</tbody>
<tfoot>
 <tr>
  <td class="cart_all" colspan="2">Всего:</td>
  <td class="cart_all_p"><?=$this->s_price($sumin,0)?></td>
  <td class="cart_all_p"><?=$this->s_price($sumout,0)?></td>
  <td class="cart_all" colspan="2"></td>
 </tr>
</tfoot>
</table></div>
<div class="price-box brd-b">
Остаток на Личном счете: <span class="new-price"><?=$this->s_price($sumin-$sumout,0)?></span>
</div>
</div>
<? if (isset($arows) && count($arows)>0){?>
<div class="area-title bdr mt20">
<h2>Электронные платежи</h2>
</div>
<p>После поступления указанных платежей на наш расчетный счет (от 3 до 7 дней) они появятся в Личном счете. </p>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered text-center">
<thead>
 <tr>
  <th style="width: 50px;">Дата</th>
  <th style="width: 150px;">Счет</th>
  <th style="width: 100px;">Сума</th>
  <th>К заказу</th>
 </tr>
 </thead>
 <tbody>
<?
$sumin = 0;
foreach($arows as $id=>$val) {
$sumin=$sumin+$val['orderamount'];

?>
 <tr>
  <td><?=date('d.m.y', $val['date'])?></td>
  <td><?=$val['billnumber']?></td>
  <td class="t_price"><?=$this->s_price($val['orderamount'],0)?></td>
  <td><?=$val['orderid']?></td>
 </tr>
 <?}?>
 </tbody>
 <tfoot>
 <tr>
  <td class="cart_all " colspan="2">Итого:</td>
  <td class="cart_all_p "><?=$this->s_price($sumin,0)?></td><td></td>
 </tr></tfoot>
 </table></div></div>
<?}?>
</div>