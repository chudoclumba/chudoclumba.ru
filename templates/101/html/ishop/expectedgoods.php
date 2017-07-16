<div class="prdcard"><table class="ppr_mt cart_t">
 <tr>
  <th></th>
  <th>Наименование</th>
  <th>Поставка</th>
  <th>Кол-во</th>
  <th>Цена</th>
  <th>Стоимость</th>
  <th>№ Заказа</th>
  </tr>
<? //TODO Переписать форму
$summa_all = 0;
$i=0;
foreach($info as $id=>$val) {
$i++;
	$link=SITE_URL.'ishop/product/'.$val['prd_id'];
	if(!empty($val['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$val['vlink'];
	}

?>
 <tr>
  <td class="">  			
			   <? if(file_exists($val['foto'])) {?>
				 <a id="flink" class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
				<img id="big_f" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&amp;x=30&amp;y=30&amp;crop" />
			   <? } else { ?>
				&nbsp;
			   <? } ?>
			 </a>
</td>
<?php if ($val['prd_id']>0){ ?>
  <td class=""><a style="text-decoration: none;" href="<?=$link?>"><?=$val['kodtov'].'&nbsp;'.$val['name']?></a></td>
 <? } else { ?>
  <td class=""><?=$val['kodtov'].'&nbsp;'.$val['name']?></td>
 	
 <? } ?>
  <td class=""><?=$val['param_srokpostavki']?></td>
  <td class="tcen"><?=$val['count']?></td>
  <td class="t_price"><span class="t_price"><?=number_format($this->skidka($val['summa'],$val['skidka']),2,'.','')?></span></td>
  <td class="t_price"><span class="t_price"><?=number_format($this->skidka($val['summa'],$val['skidka'])*$val['count'],2,'.','')?></span></td>
  <td class="tcen"><?echo '<a href="service/orders/'.$val['order_id'].'" title="Состав заказа">'.$val['order_id'].'</a>'?></td>
  
 </tr>
<?
$summa_all += $this->skidka($val['summa'],$val['skidka'])*$val['count'];
}
 ?>
 <tr>
  <td class="cart_all " colspan="5">Сумма:</td>
  <td class="cart_all_p t_price" ><?=$this->s_price($summa_all,0)?></td>
  <td></td>
 </tr>
</table>
</div>