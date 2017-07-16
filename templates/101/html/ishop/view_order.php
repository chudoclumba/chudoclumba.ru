<?
$flupd=false;
$tmpfl=false;
foreach($info as $id=>$val) {
	if ($val['otgr']==0 and (($val['todel']==0 and $val['isdel']==0) or ($val['todel']>0 or ($val['isdel']<0 and ($val['enabled']==1 && $this->get_prdstate($val['cat_id'])))))) $tmpfl=true;
	
}
//$flupd=($flupd and $tmpfl);
$trclass='';
?>
<div class="col-md-12 pb30">
<div class="area-title bdr">
<h2>Заказ №<?=$minfo[0]['id']?></h2>
</div>
<table class="col">
	<tr>
	  <td class="ord_ir">Статус заказа:</td>
	  <td class="ord_il"><?=$minfo[0]['tstatus']?><br/></td>
	</tr>
	<tr>
	  <td class="ord_ir">Фамилия Имя Отчество:</td>
	  <td class="ord_il"><?=$minfo[0]['fio']?></td>
	</tr>
	<tr>
	  <td class="ord_ir">E-mail:</td>
	  <td class="ord_il"><?=$minfo[0]['email']?></td>
	</tr>
	<tr>
	  <td class="ord_ir">Телефон:</td>
	  <td class="ord_il"><?=$minfo[0]['tel']?></td>
	</tr>
	<tr>
	  <td class="ord_ir">Адрес доставки:</td>
	  <td class="ord_il"><?=$minfo[0]['adr']?></td>
	</tr>
	<tr>
	  <td class="ord_ir">Способ доставки:</td>
	  <td class="ord_il"><?=$minfo[0]['dost']?></td>
	</tr>
	<tr>
	  <td class="ord_ir">Способ оплаты:</td>
	  <td class="ord_il"><?=$minfo[0]['opl']?></td>
	</tr>
	<tr>
	  <td class="ord_ir">Доп. информация:</td>
	  <td class="ord_il"><?=$minfo[0]['comment']?></td>
	</tr>
	<tr>
	  <td class="ord_ir">Поступило оплат:</td>
	  <td class="ord_il" style="color: red"><?=$minfo[0]['sumopl']?></td>
	</tr>
</table>
<div style="clear: both"></div><br/>
<? if ($flupd){?>
<p style="">Для удаления товаров из заказа воспользуйтесь кнопкой <img alt="Отменить" align="middle" title="Отменить товар" width="18" height="19" src="<?=TEMP_FOLDER?>images/silk/decline.png" /> в таблице товаров.</p>
<?}?>

<?

$summa_all = 0;
$summa_allout = 0;
$summa_allden = 0;
$i=0;
$ord=array();
$ordout=array();
$ordden=array();
foreach($info as $val) {
	if (($val['todel']>0 and $val['todel']!=999) or $val['isdel']<0) $ordden[]=$val;
	if ($val['otgr']==$val['count']) $ordout[]=$val;
	if ($val['otgr']>0 && $val['otgr']<$val['count']) {
		$cnt=$val['count'];
		$val['count']=$val['otgr'];
		$ordout[]=$val;
		$val['count']=$cnt-$val['otgr']+$val['isdel'];
		if ($val['count']>0) $ord[]=$val;
	}
	if ($val['count']+$val['isdel']-$val['otgr']>0) $ord[]=$val;
}
$cnt=6;
if ($flupd) $cnt=7;
if (count($ord)>0){
?>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr class="c-head"><th colspan="<?=$cnt?>">Заказано</th></tr>
 <tr class="c-head">
  <th class="tcen">№</th>
  <th class="tcen">Наименование</th>
  <th class="tcen" >Кол-во</th>
  <th class="tcen" >Цена</th>
  <th class="tcen" >Скидка</th>
  <th class="tcen">Стоимость</th>
  <? if ($flupd) echo '<th class="tcen" >Отменить</th>';?>
  </tr></thead>
  <tbody>
	
<?	
	foreach($ord as $val) {
		$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,p.saletype from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].")  group by prdid) as cr on cr.prdid=p.id WHERE p.id={$val['prd_id']}";
		$prdi=$this->db->get_rows($q);
		$link=SITE_URL.'ishop/product/'.$val['prd_id'];
		if(!empty($val['vlink']) && $this->sets['cpucat']==1) $link=SITE_URL.$val['vlink'];
		$btndel='';
		if ($flupd) {
			if (($val['todel']==0 or $val['todel']==999) and $val['isdel']==0) {
				$btndel ='<a href="service/deleterow/'.$minfo[0]['id'].'_'.$val['kodstr'].'"><img alt="Отменить" align="middle" title="Отменить товар" width="18" height="19" src="'.TEMP_FOLDER.'images/silk/decline.png" /></a>';
			}  else {
				$btndel='';
			}
		}
		$trclass='';
		if (!empty($val['prdel'])) $ss=$val['prdel']; else $ss=numprint($this->skidka($val['summa']*$val['count'],$val['skidka']),2,'');
?>

 <tr <?echo $trclass?>>
<td style="padding:2px 0px 2px 5px;">  			
			   <? if(file_exists($val['foto'])) {?>
			 <a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
				<img id="big_f" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&amp;x=50&amp;y=50&amp;crop" align="middle"/><?=$val['kodstr']?>
			 </a>
			   <? } else { ?>
				<?=$val['kodstr']?>
			   <? } ?>
</td>
<?php if ($val['prd_id']>0){ ?>
  <td class=""><a style="text-decoration: none;" href="<?=$link?>"><?=$val['kodtov'].'&nbsp;'.$val['name']?></a></td>
 <? } else { ?>
  <td class=""><?=$val['kodtov'].'&nbsp;'.$val['name']?></td>
 	
 <? } ?>
<td class="tcen"><?=numprint($val['count']+$val['isdel'],0,'')?></td>
<td class="t_price"><?=numprint($val['summa'],2,'')?></td>
<td class="tcen"><?=numprint($val['skidka'],0,'').(($val['skidka']>0)?'%':'')?></td>
<td class="t_price"><?=$ss?></td>
<? if ($flupd) echo '<td class="tcen">'.$btndel.'</td>';?>
 </tr><?
		$summa_all += $this->skidka($val['summa'],$val['skidka'])*$val['count'];
	
 	}
$cnt1=5-(!$flupd); 	
?>
</tbody>
<tfoot><tr><td class="cart_all " colspan="5" style="border-left:none;">Сумма заказано:</td>
 <td class="cart_all_p " ><?=$this->s_price($summa_all,0)?></td>
  <? if ($flupd) echo '<td class="cart_all "></td>';?>
 </tr></tfoot></table></div></div>
<? 	
}
if (count($ordout)>0){?>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
 <tr class="c-head"><th colspan="6">Отгружено</th></tr><tr class="c-head">
  <th class="tcen">№</th>
  <th class="tcen">Наименование</th>
  <th class="tcen" >Кол-во</th>
  <th class="tcen" >Цена</th>
  <th class="tcen" >Скидка</th>
  <th class="tcen">Стоимость</th>
  </tr></thead><tbody>	<?
	foreach($ordout as $val) {
		$link=SITE_URL.'ishop/product/'.$val['prd_id'];
		if(!empty($val['vlink']) && $this->sets['cpucat']==1) $link=SITE_URL.$val['vlink'];
		$btndel='';
		$trclass='class="otgr"';
?>
<tr <?echo $trclass?>>
<td style="padding:2px 0px 2px 5px;">  			
			   <? if(file_exists($val['foto'])) {?>
			 <a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
				<img id="big_f" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&amp;x=50&amp;y=50&amp;crop" align="middle"/>
				<?=$val['kodstr']?>
			 </a>
			   <? } else { ?>
				<?=$val['kodstr']?>
			   <? } ?>
</td>
<?if ($val['prd_id']>0){ ?>
  <td ><a style="text-decoration: none;" href="<?=$link?>"><?=$val['kodtov'].'&nbsp;'.$val['name']?></a></td>
 <? } else { ?>
  <td ><?=$val['kodtov'].'&nbsp;'.$val['name']?></td>
 <? } ?>
  <td class="tcen"><?=numprint($val['otgr'],0,'')?></td>
<td class="t_price"><?=numprint($val['summa'],2,'')?></td>
  <td class="tcen"><?=numprint($val['skidka'],0,'').(($val['skidka']>0)?'%':'')?></td>
 <td class="t_price"><?=numprint($val['sumotgr']*$val['otgr'],2,'')?></td>
   
  
 </tr>
<?
		$summa_allout += $val['sumotgr']*$val['otgr'];
	}
	$cnt1=9+$flupd; 	
	?>
	</tbody><tfoot><tr><td class="cart_all " colspan="5">Сумма отгружено:</td>
  <td class="cart_all_p " ><?=$this->s_price($summa_allout,0)?></td>
	 </tr></tfoot></table></div></div><? 	
}
if (count($ordden)>0){
	$cnt=7+(int)$flupd;
	?>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
 <tr class="c-head"><th colspan="7">Отменено</th></tr>
 <tr class="c-head">
  <th class="tcen">№</th>
  <th class="tcen">Наименование</th>
  <th class="tcen" >Отмененo</th>
  <th class="tcen">Причина</th>
  <th class="tcen" >Цена</th>
  <th class="tcen" >Скидка</th>
  <th class="tcen">Стоимость</th>
  </tr></thead><tbody>
  <?
	$trclass='class="otmen"';
 	foreach($ordden as $val) {
		$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,p.saletype from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].")  group by prdid) as cr on cr.prdid=p.id WHERE p.id={$val['prd_id']}";
		$prdi=$this->db->get_rows($q);
		$link=SITE_URL.'ishop/product/'.$val['prd_id'];
		if(!empty($val['vlink']) && $this->sets['cpucat']==1) $link=SITE_URL.$val['vlink'];
		$cnt1=5;
?><tr <?echo $trclass?>>
<td style="padding:2px 0px 2px 5px;">  			
 			
			   <? if(file_exists($val['foto'])) {?>
			 <a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
				<img id="big_f" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&amp;x=50&amp;y=50&amp;crop" align="middle"/><?=$val['kodstr']?> 
			 </a>
			   <? } else { ?>
				<?=$val['kodstr']?> 
			   <? } ?>
</td>
<?php if ($val['prd_id']>0){ ?>
<td><a style="text-decoration: none;" href="<?=$link?>"><?=$val['name']?></a></td>
 <? } else { ?>
<td colspan="<?=$cnt1?>"><?=$val['name']?></td>
<? } ?>
<td class="tcen"><?=numprint(0-$val['isdel'],0,'')?></td>
<td class=""><?=$val['prdel']?></td>
<td class="t_price"><?=numprint($val['sumdel'],2,'')?></td>
<td class="tcen"><?=numprint($val['skidka'],0,'').(($val['skidka']>0)?'%':'')?></td>
<td class="t_price"><?=numprint($this->skidka($val['sumdel']*$val['count'],$val['skidka']),2,'')?></td>
</tr><?

	}
?></tbody></table></div></div>
<?
}
?>
</div>

<?php
if ($minfo[0]['dopinf']>' ')
{ ?>
<div class="col-md-12 pb30">
<div class="area-title bdr">
<h2>Дополнительная информация:</h2>
</div>
<p><?=str_replace("\n",'<br/>',$minfo[0]['dopinf'])?></p>
</div>
<?php //<img src="<?echo TEMP_FOLDER/images/link.png" alt="" style="margin:-45px -10px 0;" />
}
$ids=$this->db->get_rows("SELECT * FROM chudo_ishop_postid WHERE orderid=".quote_smart($minfo[0]['id'])." order by date");
if (count($ids)>0){?>
<div class="col-md-12 pb30">
<div class="area-title bdr">
<h2>Идентификаторы отправлений:</h2>
</div>
<?
foreach($ids as $key => $val){
	if (CheckSPI($val['value']))echo '<p><a href="'.SITE_URL.'service/track/'.$val['value'].'">'.$val['value'].'</a></p>';
	 else	echo '<p>'.$val['value'].'</p>';
}
?>
</div>
<?}?>
