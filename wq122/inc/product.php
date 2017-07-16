<?
echo 'dfgdfgdfg';
die();
$cat = $db->get(TABLE_PRODUCTS, $_GET['id']);
$rows=array();
if ($cat['srcid']>0) {
	$rows=$db->get_rows('select * from chudo_ishop_products where srcid='.$cat['srcid'].' and visible=1 order by tsena');
	foreach($rows as $id => $row){
		if ($row['id']==$row['srcid']) $cat=$row;
	}
}
$q=$db->get_rows("select * from chudo_upak");
$upak=array();
foreach($q as  $r){
	$upak[$r['id']]=array('name'=>$r['name'],'descr'=>$r['descr']);
}
?>
<!--start-->
<div class="prdcard">
<div style="padding:6px 0 6px 5px; font-size:14px; color: #fb5a04"><?=$cat['param_naimenovanielatinskoe']?></div>
<table class="w100 col">
<tr><td class="p0 prd_outer_short pl10">
<table class="col">
<tr><td class="prd_bgs p0"><div class="prd_image_fix" style="width:97px; height:150px;" >
<? if($sets['mod_new'] && !empty($cat['new'])) { ?><div class="new_m"></div><? } 
   if($sets['mod_hit'] && !empty($cat['hit'])) { ?><div class="hit_m"></div><? } 
   if($sets['mod_spec'] && !empty($cat['spec'])) { ?><div class="skidka_m"></div><? } 
   $p_size = ($sets['mod_p_size']) ? 'class="highslide" onclick="return hs.expand(this)"' : ''; 
   if(file_exists($cat['foto'])) {?>
<a id="flink" <?=$p_size?> href="<?=SITE_URL?><?=$cat['foto']?>">
<img alt="<?=$cat['title']?>" src="s.gif" width="150px" height="97px" style="background: url(www.chudoclumba.ru\thumb.php?id=<?=$cat['foto']?>&amp;x=97&y=150) center no-repeat" />
</a>
<? } else { ?>Изображение временно отсутствует.<? } ?>
</div>
</td>
<td class="text top p0" style="<?
echo ((!empty($cat['new'])) || (!empty($cat['hit'])) || (!empty($cat['spec']))) ?
			'padding-left: '.$this->product_pw.'px;' : 'padding-left: 40px;';
?>">
<h2><?=htmlspecialchars($cat['title'],ENT_COMPAT | ENT_XHTML,'UTF-8')?></h2>
<?
		if(!empty($cat['param_brend']) && !empty($sets['mod_proizv'])) {
			$inf = $db->get_rows("SELECT title FROM ".TABLE_CATEGORIES." WHERE id = ".quote_smart($cat['param_brend'])."");
			?><div class="ppr_d_t">Производитель: <?=$inf['0']['title']?></div><?
		} 
echo (!empty($cat['param_proizvoditel'])) ? '<div class="ppr_d_t"><b>Производитель:</b> '.$cat['param_proizvoditel'].'</div>
' : '';
echo (!empty($cat['param_visota'])) ? '<div class="ppr_d_t"><b>Высота, см:</b> '.$cat['param_visota'].'</div>
' : '';
echo (!empty($cat['param_shirina'])) ? '<div class="ppr_d_t"><b>Ширина, см:</b> '.$cat['param_shirina'].'</div>
' : '';
echo (!empty($cat['param_aromat'])) ? '<div class="ppr_d_t"><b>Аромат:</b> '.$cat['param_aromat'].'</div>
' : '';
echo (!empty($cat['param_tsvetenie'])) ? '<div class="ppr_d_t"><b>Цветение:</b> '.$cat['param_tsvetenie'].'</div>
' : '';
echo (!empty($cat['param_razmertsvetka'])) ? '<div class="ppr_d_t"><b>Размер цветка, см:</b> '.$cat['param_razmertsvetka'].'</div>
' : '';
echo (!empty($cat['param_tiptsvetka'])) ? '<div class="ppr_d_t"><b>Тип цветка:</b> '.$cat['param_tiptsvetka'].'</div>
' : '';
echo (!empty($cat['param_periodtsveteniya'])) ? '<div class="ppr_d_t"><b>Период цветения:</b> '.$cat['param_periodtsveteniya'].'</div>
' : '';
echo (!empty($cat['param_okraskatsvetka'])) ? '<div class="ppr_d_t"><b>Окраска цветка:</b> '.$cat['param_okraskatsvetka'].'</div>
' : '';
echo (!empty($cat['param_okraskalistvi'])) ? '<div class="ppr_d_t"><b>Окраска листвы:</b> '.$cat['param_okraskalistvi'].'</div>
' : '';
if(!empty($cat['param_morozostoykost'])){ 
	$ss=(strlen(strpbrk($cat['param_morozostoykost'], '0123456789'))>0) ? '&nbsp;&nbsp;&nbsp;<a href="site/28">Зоны морозостойкости</a>' : '';
?><div class="ppr_d_t"><b>Морозостойкость:</b> <?=$cat['param_morozostoykost'].$ss?></div>
<?}
		if(!empty($cat['param_muchnistayarosa']) || !empty($cat['param_chernayapyatnistost'])) { 
		echo '<div class="ppr_d_t"><b>Устойчивость листвы к заболеваниям:</b>';
		echo (!empty($cat['param_muchnistayarosa'])) ? '<br><b>мучнистая роса:</b> '.$cat['param_muchnistayarosa'] : '';
		echo (!empty($cat['param_chernayapyatnistost'])) ? '<br><b>черная пятнистость:</b> '.$cat['param_chernayapyatnistost'] : '';
		echo '</div>
		';
		}
		echo (!empty($cat['param_tippochvi'])) ? '<div class="ppr_d_t"><b>Тип почвы:</b> '.$cat['param_tippochvi'].'</div>
' : '';
		echo (!empty($cat['param_svetovoyrezhim'])) ? '<div class="ppr_d_t"><b>Световой режим:</b> '.$cat['param_svetovoyrezhim'].'</div>
' : '';
?>
</td></tr></table></td></tr>
<?
echo '<tr><td><br><table class="ppr_mt">';
if (!count($rows)>0) $rows[0]=$cat;
//print_r($rows);
echo '<tr><th class="nbl"><div class="ppd_list_item pr10">Код товара</div></th><th><div class="ppd_list_item pr10 pl10">Cтандарт<br>поставки</div></th><th><div class="ppd_list_item pr10">Срок поставки</div></th><th><div class="ppd_list_item pr10 tcen">Цена</div></th><th></th></tr>
';
foreach($rows as $id => $row){
	$ss='';
	if ($_SERVER['REMOTE_ADDR']=='62.140.235.202') $ss=' title="Редактировать в СУС" class="link_sus nbl" onclick="window.open'."('/wq121/include.php?place=ishop#update_prd_html(".$row['id'].")','',''); return false;".'"'; 
	else  $ss=' class="nbl"';
	echo '<tr><td'.$ss.'><div class="ppr_d_t pr10 pl10">'.$row['param_kodtovara'].'</div></td>';
	$pos=substr(trim($row['param_kodtovara']),17,2);
	if (substr($pos,0,1)=='0') @$pos=substr($pos,1,1);
	echo '<td align="center"><div class="ppr_d_t lf pr10 pl10" data-title="'.$upak[$pos]['descr'].'"><span style="font-weight:700">'.$upak[$pos]['name'].'</span>';
	echo (!empty($row['param_kolichestvo']) && $row['isupak']==1) ? ', Кол-во в упак.: '.$row['param_kolichestvo'] : '';
	echo '</div></td>';
	echo '<td><div class="ppr_d_t pr10 pl10">'.$row['param_srokpostavki'].'</div></td>';
	echo '<td'.(($row['skidka_day']>0) ? ' title="Cкидка дня!"' : '').'><div class="ppr_d_t pr10tr pl10">';
	if(!empty($row['param_starayatsena']) && $row['param_starayatsena'] > 0 && $sets['mod_old_price'] && !($row['skidka_day']>0)) {
		echo '<span class="lt">'.$row['param_starayatsena'].'</span>&nbsp';
	} 
	if($row['skidka_day']>0){ 
		echo '<span class="lt">'.$row['tsena'].'</span>&nbsp;';
	}
	echo '<span style="font-weight:bold;font-size:14px">'.($row['tsena']-$row['tsena']*$row['skidka']/100).'</span> руб.';
	echo '</div></td>';

	echo '</td></tr>
	';

}
echo '</table></td></tr>
';


 if(!empty($cat['param_polnoeopisanie'])) { ?>
<tr><td><div class="o_hd">Описание:</div><div class="ppr_descr"><?=$cat['param_polnoeopisanie']?></div></td></tr>
<? } 
?></table></div>
<!--end of product-->