<form id="cart_form" action="" method="post">
<div class="orders_btnm">
 <button class="albutton alorange" name="ch_cart" type="submit" value="�������� �������"><span><span><span class="sync">�������� �������</span></span></span></button>
 <button class="albutton alorange" name="order" type="submit" value="�������� �����"><span><span><span class="ok">�������� �����</span></span></span></button>
</div><br/>
<table class="ppr_mt cart_t">
 <tr >
  <th >����</th>
  <th >��� ������</th>
  <th >������������</th>
  <th >����</th>
  <th >����������</th>
  <th >���������</th>
  <th >�������</th>
 </tr>
 

<?
		if (!isset($_SESSION['script'])) $_SESSION['script']='';
		$_SESSION['script'].='<script type="text/javascript">$(window).load(function() {yaCounter16195645.reachGoal(\'Cart_on\')});</script>';

$ps='--';
$zk=0;
 foreach($prd as $id=>$val)
{
	$link=SITE_URL.'ishop/product/'.$id;
	if(!empty($val['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$val['vlink'];
	}
	if ($ps!=$val['param_srokpostavki']){
		$zk++;
		$ps=$val['param_srokpostavki'];
		echo '<tr><th>����� �'.$zk.'</th><th class="tcen" colspan="6">'.$ps.'</th></tr>';
	}

?>

 <tr id="row_prd_<?=$id?>" <?echo($val['active'] ? '' : 'style="background:#ffff00"')?>>
  <td class="" width="100px">
   <? if(file_exists($val['foto'])) {?>
		<a id="flink" class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
        <img alt="<?=$val['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=100&y=100&crop" />
		</a>
	   <? } else { ?>����������� �������� �����������.<? } ?>
  </td>
  <td class=""><a href="<?=$link?>"><?=$val['param_kodtovara']?></a></td>
  <td class=""><a href="<?=$link?>"><?=$val['name']?></a></td>
  <td class="pr_<?=$id?> t_price">
  <?=$this->s_price($val['tsena'], $val['skidka'])?></td>  
  <? if ($val['active']) {?>
  <td class="tcen">
  <input id="<?=$id?>" class="count_prd fbinp" style="width:20px;text-align: center;" name="count[<?=$id?>]" value="<?=$val['cnt']?>" />
  </td>
  <td class="t_price pr_<?=$id?>">
  <input type="hidden" id="price_<?=$id?>" value="<?=$this->skidka($val['tsena'], $val['skidka'])?>" />
  <input type="hidden" id="new_price_<?=$id?>" value="<?=$this->skidka($val['tsena'], $val['skidka'])*$val['cnt']?>" />
  <?=$this->s_price($val['tsena']*$val['cnt'], $val['skidka'])?></td>  
  <?}else{?>
  <td class="" colspan="2">����� ������� ��������</td>	
 <?}?>
  <td class="tcen">
	<input id="<?=$id?>" <?if(!empty($this->sets['ajax_cart'])){?>class="delete_tovar"<?}?> name="del[<?=$id?>]" value="1" type="checkbox" />
  </td>
 </tr>
<? 
}
 ?>
 <tr>
  <td class="cart_all " colspan="5">�����:</td>
  <td class="cart_all_p  summa_c"><?=$this->s_price_c($summa)?></td>
  <td class="">&nbsp;</td>
 </tr>
<?
if(!empty($this->sets['mod_prd_skidka']) && !empty($_SESSION['user']))
{
	$orders1 = $this->db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$_SESSION['user']."' && status != 6");
}
$sale=0;
if (isset($_SESSION['user']) && $_SESSION['user']>0) $sale=User::gI()->user['sale'];
if(!empty($this->sets['mod_cards']) || ((!empty($this->sets['mod_prd_skidka']))   && (!empty($_SESSION['user'])) )  && !empty($orders1['0']['summa'])){

	if(!empty($this->sets['mod_prd_skidka']) && !empty($_SESSION['user']) && !empty($orders1['0']['summa'])) {
		$orders = $this->db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
		if ($orders['0']['percent']>$sale) $sale=$orders['0']['percent'];
	}
}	
if ($sale>0) { ?>
 <tr>
  <td class="cart_all " colspan="5">������ (<?=$sale?>%):
  </td>
  <td class="cart_all_p  summa_c"><?=$this->s_price_c(($summa*$sale)/100)?></td>
  <td class="">&nbsp;</td>
 </tr> 
 <tr>
  <td class="cart_all " colspan="5">�����:</td>
  	<td class="cart_all_p  summa_c">
<?
  $skidka = (!empty($sale)) ? ($summa*$sale)/100 : 0;
  echo $this->s_price_c($summa - ($summa*$this->card)/100 - $skidka)
?>
	</td>
  	<td class="">&nbsp;</td>
 </tr>
<?}?>
</table><br/>
<div class="orders_btnm">
 <button class="albutton alorange" name="ch_cart" type="submit" value="�������� �������"><span><span><span class="sync">�������� �������</span></span></span></button>
 <button class="albutton alorange" name="order" type="submit" value="�������� �����"><span><span><span class="ok">�������� �����</span></span></span></button>
</div>
<div class="pp_d">
* - ��� �������� �������� ������� �� �������, ���������� �������� �� ��������� � �������� �� ������ "�������� �������".<br>
* - ��� ��������� ���������� �������, ���������� ��������� � ������� �������� ������ ����� � �������� �� ������ "�������� �������". 
</div>
</form>
