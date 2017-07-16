<?if($sets['mod_prd_vs']) { ?><div id='compare_link' class='msg_cmp'><a target="_blank" href='<?=SITE_URL?>ishop/prd_vs'>показать сравнительную таблицу</a>&nbsp;<span class='icon cancel'  OnClick='$( "#compare_link" ).css("display","none");'></span></div>
<? }?>
<!--prdlist-->
<div class="our-product-area best-offere featur-padd">
<div class="row">
<?
$q=$this->db->get_rows("select * from chudo_upak");
$upak=array();
foreach($q as  $r){
	$upak[$r['id']]=array('name'=>$r['name'],'descr'=>$r['descr']);
}
$k = 0;
foreach($prds as $id=>$row)
{
    $addr=array();
    $q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,ifnull(tm1.tot,0)+p.reserv as ztot,ifnull(cr.ctot,0) as ctot,p.* from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") group by prdid) as cr on cr.prdid=p.id WHERE (srcid={$row['id']} and id<>{$row['id']} && enabled=1 && visible=1 && ((p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0)>0 and p.saletype='1') or p.saletype='0')) ORDER BY p.tsena";
	if ($row['id']==$row['srcid']) $addr=$this->db->get_rows($q);//'select * from chudo_ishop_products where srcid='.$row['id'].' and id<>'.$row['id'].' and visible=1 and enabled=1 order by tsena');
	array_unshift($addr,$row);
	$sho=false;
	foreach($addr as $arow){
		if ($this->get_prdstate($arow['cat_id']) && $arow['enabled'] && $arow['visible'] && ($arow['ost']>0 || $arow['saletype']==0)) $sho=true;	
	}
	if (!$sho) continue;
	$vname=$row['title'];
	if (($row['id']==$row['srcid'])){
		$vname=preg_replace("/\s*?\(\s*?[1-5].*?\)\s*?$/",'',$vname);
	}
	$link='ishop/product/'.$row['id'];
	if(!empty($row['vlink']) && $this->sets['cpucat']==1){
		$link=$row['vlink'];
	}
?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-padd">
<div class="single-product">
<div style="display: block; height: 70px; max-height: 80px"><h3><a href="<?=$link?>"><?=$vname?></a></h3></div>
<div class="col-md-6 col-sm-12 col-xs-12 nopadding-left"><div style="position: relative">
<? if ($row['param_starayatsena']>0 || (!empty($row['spec'])) ||  $row['skidka']>0 ){?>
<div class="product-label"><div class="sale"></div></div>
<?}?>		
<? if ((!empty($row['new'])) ){?>
<div class="product-label"><div class="new"></div></div>
<?}?>	
<?
    if(file_exists($row['foto'])) {
?><div class="imgs-area"><a id="flink" class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL.$row['foto']?>"><img title="<?=$row['title']?>" alt="<?=$row['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=$row['foto']?>&x=200&y=200&crop"/></a></div><?
	} else { ?>Изображение временно отсутствует.<?}?>        
</div></div>
<div class="col-md-6 col-sm-12 col-xs-12 nopadding">
<div class="product-details-content"><?
            	foreach($addr as $aid => $arow){
		if ($this->get_prdstate($arow['cat_id']) && $arow['enabled'] && $arow['visible'] && ($arow['ost']>0 || $arow['saletype']==0)) {
			$pos=strtoupper(substr(trim($arow['param_kodtovara']),17,2));
			echo '<div class="ppd_list_item lf" data-title="'.$upak[$pos]['descr'].'">Cтандарт поставки: '.$upak[$pos]['name'].'</div>';
			echo (!empty($arow['param_kolichestvo']) && $arow['isupak']==1) ? '<div class="ppr_d_t">Количество в упаковке: '.$arow['param_kolichestvo'].'</div>' : '';
			echo (!empty($arow['param_kodtovara'])) ? '<div class="ppr_d_t">Код: '.$arow['param_kodtovara'].'</div>' : '';
			echo (!empty($arow['param_srokpostavki'])) ? '<div class="ppr_d_t">Срок поставки: '.$arow['param_srokpostavki'].'</div>' : '';
			echo $arow['opisanie'];
			$st='<div class="price-box">';
			$st.='<span class="new-price">'.$this->s_price($arow['tsena'],($arow['skidka_day']>0)?  $arow['skidka'] : 0).'</span>';
			$st.=((!empty($row['param_starayatsena']) && $row['param_starayatsena'] > 0 && $sets['mod_old_price'] && !($row['skidka_day']>0))) ? '<span class="old-price">&nbsp;<del>'.$this->s_price($row['param_starayatsena'], 0).'</del></span>' : '';
			$st.='</div>';
			if (User::gI()->user_role>0){
				$tm=($arow['saletype']==1)?"&nbsp;<img alt=\"Автозакрытие\" style=\"vertical-align: middle\" title=\"Автозакрытие\" width=\"16\" height=\"16\" src=\"".TEMP_FOLDER."images/silk/calculator_add.png\" />":"";
				$st.="<table class=\"p_info\"><tr><th><img alt=\"Cклад\" align=\"middle\" title=\"На складе\" width=\"16\" height=\"16\" src=\"".TEMP_FOLDER."images/silk/bricks.png\" /><br/>Cклад</th><th><img alt=\"Поставка\" align=\"middle\" title=\"У поставщика\" width=\"16\" height=\"16\" src=\"".TEMP_FOLDER."images/silk/lorry.png\" /><br/>Поставка</th><th><img alt=\"Заказы\" align=\"middle\" title=\"В заказах\" width=\"16\" height=\"16\" src=\"".TEMP_FOLDER."images/silk/package.png\" /><br/>Заказы</th><th><img alt=\"Корзины\" align=\"middle\" title=\"В корзинах\" width=\"16\" height=\"16\" src=\"".TEMP_FOLDER."images/silk/cart_put.png\" /><br/>Корзины</th><th>=</th></tr><tr><td>{$arow['sklad']}</td><td>{$arow['zakazpost']}</td><td>{$arow['ztot']}</td><td>{$arow['ctot']}</td><td>{$arow['ost']}{$tm}</td></tr></table>";
			}

			$st.='';
			echo $st;
			$st='';
			if($sets['mod_prd_vs']){
				$st.='<li> <a class="link-compare" id="cmp_'.$arow['id'].'" href="" onclick="addcmp('.$arow['id'].');return false;">';
				$st.=((isset($_SESSION['vs_prds']) && in_array($arow['id'],$_SESSION['vs_prds'])) ? 'Добавлено в сравнение'  : 'Добавить к сравнению');
				$st.='</a></li>';
			}
		}}
		$ct='';
		if (isset($_SESSION[CART][$arow['id']]['count']) && $_SESSION[CART][$arow['id']]['count']>0)  $ct= '(Уже '.$_SESSION[CART][$arow['id']]['count'].')';
		if (Wish::gI()->on)
				$mt= str_ireplace('%cnt%',Wish::gI()->ShowCnt($row['id']),str_ireplace('%id%',$row['id'],Wish::gI()->knadd));

		?>

</div>
</div><div class="clearfix"></div>

<div id="inc_<?=$arow['id']?>" class="add-to-box-view pb25">
<a class="button v_button mt15" href="<?=$link?>" title="Полное описание"><span>Подробнее</span></a>
<a class="button cart_button mt15" id="plac_<?=$row['id']?>" onclick="Add_to_cart(this);return false;" title="Добавить в корзину"><span>В Корзину</span><span class="cart_btinfo"><?=$ct?></span></a>
<div class="clearfix"></div>
</div>
<div class="add-to-box pt20 pb20 "><ul><?=$mt?><?=$st?></ul></div>
</div>
</div>
<?
/*			$st.='<table class="tcen noprint" style="width: 180px;"><tr><td>';
			$st.='<div class="add_prd v1" id="ac_'.$arow['id'].'" onclick="Add_to_cart(\''.$arow['id'].'\');return false;" title="Добавить в корзину">';
			if (isset($_SESSION[CART][$arow['id']]['count']) && $_SESSION[CART][$arow['id']]['count']>0)  $st.= ''.$_SESSION[CART][$arow['id']]['count'];
			$st.= '</div>Купить</td><td>';
			$st.='</td></tr></table>';
			$st.='<div class="incart" id="inc_'.$arow['id'].'">';
*/
}?>
</div></div>