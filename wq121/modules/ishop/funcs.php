<?php
function view_log($id=0){
	global $db,$sets,$global_place;
	$schstr="";
	$sstr='';
	if(!empty($_POST['search_string'])){
		$schstr=$_POST['search_string'];
	}
	else{
		if(!empty($_GET['sch']) && $_GET['sch']>" "){
			$schstr=$_GET['sch'];
		}
	}
	if($schstr>" "){
		$sstr=" where `orderid` LIKE '%".str_replace ("'","_",$schstr)."%'";
	}
    $where='';
	$pdtlist2 = $db->get_rows("SELECT count(id) as cnt FROM ".TABLE_ORDERS_LOG.$sstr);
	$pg_count = $pdtlist2[0]['cnt'];
	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';

	$query='select l.*,p.title from '.TABLE_ORDERS_LOG.' l left join '.TABLE_PRODUCTS.' p on p.id=l.prdid '.$sstr.' order by data desc '.$limit;

//	$query = "SELECT * FROM ".TABLE_ORDERS." ".$sstr." ORDER BY datan DESC ".$limit;
	$q = $db->get_rows($query);
	if($schstr>" "){
		$ee='&sch='.$schstr;
	}
	else{
		$ee='';
	}
	ob_start();
	?>

		<div class="search_pan">
			<form method="post" action="<?php echo 'include.php?place='.$global_place.'&action=log'.((!empty($_GET['userid'])) ? '&userid='.$_GET['userid'] : '')?>">
				Поиск:
				<span style="color:red"><?php
					if( !empty($schstr)){
						echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8');
					} ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
			</form>
		</div>

	<?php
	$plist=get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.'&action=log'.$ee.'&page=','info'=>$mes)).'<div class="clear"></div>';
	echo (empty($plist))?'<div style="height:20px"></div>':$plist;
	echo show_log($q);
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.'&action=log'.$ee.'&page=','info'=>$mes));

	$ret=ob_get_contents();
	ob_end_clean();		
	return $ret;
}




function downloadFile($filename, $mimetype='application/octet-stream') {
	if (!file_exists($filename)) die('Файл не найден');
	$from=$to=0; $cr=NULL;
	if (isset($_SERVER['HTTP_RANGE'])) {
		$range=substr($_SERVER['HTTP_RANGE'], strpos($_SERVER['HTTP_RANGE'], '=')+1);
		$from=strtok($range, '-');
		$to=strtok('/'); if ($to>0) $to++;
		if ($to) $to-=$from;
		header('HTTP/1.1 206 Partial Content');
		$cr='Content-Range: bytes ' . $from . '-' . (($to)?($to . '/' . $to+1):filesize($filename));
	} else	header('HTTP/1.1 200 Ok');
	$etag=md5($filename);
	$etag=substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
	header('ETag: "' . $etag . '"');
	header('Accept-Ranges: bytes');
	header('Content-Length: ' . (filesize($filename)-$to+$from));
	if ($cr) header($cr);
	header('Connection: close');
	header('Content-Type: ' . $mimetype);
	header('Last-Modified: ' . gmdate('r', filemtime($filename)));
	$f=fopen($filename, 'r');
	header('Content-Disposition: attachment; filename="CHCatalog.csv";');
	if ($from) fseek($f, $from, SEEK_SET);
	if (!isset($to) or empty($to)) {
		$size=filesize($filename)-$from;
	} else {
		$size=$to;
	}
	$downloaded=0;
	while(!feof($f) and !connection_status() and ($downloaded<$size)) {
		echo fread($f, 512000);
		$downloaded+=512000;
		flush();
	}
	fclose($f);
}
function ajax_copy_window($cats, $prds, $cat_id)
{
	$btn =  button('Копировать','copy_to(\''.implode(',',$cats).'\', \''.implode(',',$prds).'\', $(\'#kolvo\').val())');
	$btn2 =  button('Отмена','gotourl(\'include.php?place=ishop#open_sub_cats('.$cat_id.', 1)\')');
	$c = '
	<div style="padding:20px;">
	Текущий путь: <span id="pexp">'.get_catnm($cat_id,0).'</span>
	<div>Выберите раздел в который необходимо копировать разделы:</div>
	<div style="padding:5px 0px">
	 <input id="pid" type="hidden" value="'.$cat_id.'">
	 '.get_newtreesubcat(0,1,'disable_controls,nocnt','rt2').'
	 <span style="margin:0px 10px 0px 10px">Количество копий</span><input style="width:50px;" value="1" type="text" id="kolvo" />
	</div>
	<br/><b>Внимание, все ЧПУ ссылки будут очищены!</b><br/>
	<div style="padding:5px 0px">
	'.$btn.''.$btn2.'
	</div>
	</div>';
	return $c;
}
function ajax_move_window($cats, $prds,$cat_id)
{
	$btn =  button('Переместить','move_to(\''.implode(',',$cats).'\', \''.implode(',',$prds).'\')');
	$btn2 =  button('Отмена','gotourl(\'include.php?place=ishop#open_sub_cats('.$cat_id.', 1)\')');
	$c = '
	<div style="padding:20px;">
	Текущий путь: <span id="pexp">'.get_catnm($cat_id,0).'</span>
	<div>Выберите раздел в который необходимо переместить:</div>
	<div style="padding:5px 0px">
	 <input id="pid" type="hidden" value="'.$cat_id.'">
	 '.get_newtreesubcat(0,1,'disable_controls,nocnt','rt2').'
	</div>
	<br/><b>Внимание, все ЧПУ ссылки будут очищены!</b><br/>
	<div style="padding:5px 0px">
	'.$btn.''.$btn2.'
	</div>
	</div>';
	return $c;
}
function ajax_makegr($prds)
{
	global $db;
	$c = '
	<div style="padding:20px;">';
	foreach($prds as $val){
		$row=$db->get_rows('select * from '.TABLE_PRODUCTS.' where id='.quote_smart($val));
		$c.='<input name="gr" type="radio" value="'.$row[0]['id'].'">'.$row[0]['title'].'</input><br>';
	}
	$btn =  button('Выполнить','makegr_ok(\''.implode(',',$prds).'\',$("[name = gr]:checked").val(),'.$row[0]['cat_id'].')');
	$btn2 =  button('Отмена','history.back[1];');
	$c.='<br><br>Выберите основной товар для показа.</div>
	<div style="padding:5px 0px">
	'.$btn.$btn2.'
	</div>
	</div>';
	return $c;
}
function ajax_setrecpar($cats){
	global $db;
	if (count($cats)>0)	{
		foreach($cats as $key => $value){
			$db->update(TABLE_CATEGORIES,$value,array('recpar'=>1));
		}
		return '1';
	}
	return	'0';
}
function ajax_setaclose($cats,$gp){
	function upd_ac($cat_id,$gp,$level=0){
		global $db;
		$db->update(TABLE_PRODUCTS,array('cat_id'=>$cat_id),array('saletype'=>$gp));
		$rows = $db->get_rows("SELECT id FROM ".TABLE_CATEGORIES." WHERE parent_id = ".$cat_id."");
		foreach($rows as $id=>$val){
			upd_ac($val['id'],$gp,$level);
		}
		return 1;
	}

	global $db;
	upd_ac($cats,$gp);
	return '1';
}

function ajax_setrecgp($cats,$gp){
	function upd_gp($cat_id,$gp,$level=0){
		global $db;
		$db->update(TABLE_PRODUCTS,array('cat_id'=>$cat_id),array('tper'=>$gp));
		$rows = $db->get_rows("SELECT id FROM ".TABLE_CATEGORIES." WHERE parent_id = ".$cat_id."");
		foreach($rows as $id=>$val){
			upd_gp($val['id'],$gp,$level);
		}
		return 1;
	}

	global $db;
	if (count($cats)>0)	{
		foreach($cats as $value){
			upd_gp($value,$gp);
		}
		return '1';
	}
	return	'0';
}
function get_scatcnt($catid)
{
	global $db;
	$q = $db->count(TABLE_CATEGORIES,['parent_id'=>$catid]);
	return $q;
}
function get_prdcnt($catid)
{
	global $db;
	$q = $db->count(TABLE_PRODUCTS,['cat_id'=>$catid]);
	return $q;
}
function get_prdencnt($catid)
{
	global $db;
	$q = $db->count(TABLE_PRODUCTS,['enabled'=>1,'visible'=>1,'cat_id'=>$catid]);
	return $q;
}
function get_module_link($name, $value, $prelink)
{
	return  GetOpen(''.$name.'','','','<a class="trig_on" href="'.$prelink.'&amp;action='.$value.'">Открыть</a>');
}
function get_cat_btns($bn=1)
{
	$id=11;
	if (isset($_SESSION['katalogid'])) $id=$_SESSION['katalogid'];
	if (isset($_SESSION['katalogid']) && isset($_SESSION['katalogpg'])) $id=''.$_SESSION['katalogid'].','.$_SESSION['katalogpg'];
	$btns = array(
			'Каталог'=>array('href'=>'gotourl(\'include.php?place=ishop#open_sub_cats('.$id.')\')'),
			'Дерево'=>array('href'=>'gotourl(\'include.php?place=ishop#open_treesub_cats('.$id.')\')'),
			'Дополнительно'=>array('href'=>'gotourl(\'include.php?place=ishop&action=settings\')'));
	if($_SESSION['auth']['type'] == 'admin'){
		$btns['Товары в заказах']=array('href'=>'gotourl(\'include.php?place=ishop#open_prdinord(1)\')');
		$btns['Модули']= array('href'=>'gotourl(\'include.php?place=ishop&action=modules\')');
	}	
	$cnt=0;
	foreach($btns as $key => $value){
		$cnt++;
		unset($btns[$key]['id']);
		if ($bn==$cnt){
			$btns[$key]['id']=1;
		}
	}
	return $btns;
	
}
function get_adv_photo($prd_id)
{
	global $db;
	ob_start();
?>
<div>
<?php
	$fots = $db->get_rows("SELECT * FROM ".TABLE_PRD_FOTO." WHERE product_id = '".$prd_id."' ORDER BY sort ASC, id ASC");
	foreach($fots as $foto)
	{
?>
		  <div id="adv_foto_<?php echo $foto['id']?>" style="margin:5px; text-align:center; float:left; padding:3px;">
		   <input id="sort_adv_v_<?php echo $foto['id']?>" onchange="upd_adv_sort(<?php echo $foto['id']?>)" style="width:30px;" value="<?php echo $foto['sort']?>" name="sel_foto[<?php echo $foto['id']?>]" type="text" /><br>
		   <img style="margin:3px;" alt="" src="<?php echo SITE_URL?>thumb.php?id=<?php echo $foto['filename']?>&x=100" onclick="$('#url_abs_nohost_adv').val('<?=$foto['filename']?>');$('#adv_foto_main').attr('src','<?php echo SITE_URL?>thumb.php?id=' + $('#url_abs_nohost_adv').val() + '&x=100&y=100');return hs.expand(this, { src: '<?=SITE_URL.$foto['filename']?>'} )" /><br>
		   <span id="del_l_v_<?php echo $foto['id']?>"><a  href="javascript:;" onclick="del_adv_foto(<?php echo $foto['id']?>)">Удалить</a></span>
		  </div>
<?php
}
?>
	 </div>
	 <div style="clear:both; height:10px;"></div>
<?php
	$ftsc = ob_get_contents();
	ob_end_clean();
	return $ftsc;
}
function ajax_edit_cat_form($cat_id, $pid=-1)                  // ---------------------- Редактирование группы
{
	global $db, $prelink, $sets, $ebox;
	$cat = $db->get(TABLE_CATEGORIES,$cat_id);
	$nm=get_catnm($cat['parent_id'],0);
	if($pid > -1){
		$cat['parent_id'] = $pid;
	}
	$cat['title'] = (!empty($cat['title'])) ? $cat['title'] : '';
	$cat['metatitle'] = (!empty($cat['metatitle'])) ? $cat['metatitle'] : '';
	$cat['metadesc'] = (!empty($cat['metadesc'])) ? $cat['metadesc'] : '';
	$cat['metakeys'] = (!empty($cat['metakeys'])) ? $cat['metakeys'] : '';
	$cat['text'] = (!empty($cat['text'])) ? $cat['text'] : '';
	$cat['foto'] = (!empty($cat['foto'])) ? $cat['foto'] : '';
	$cat['enabled'] = (!empty($cat['enabled'])) ? $cat['enabled'] : '';
	ob_start();
	?>
	<div class="clear"></div>
	<form id="cat_frm" action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="cid" id="cid" value="<?=$cat_id?>"></input>
	<div id="accordion" style="font-weight: 600">
	<h3><span id="tid"><?=($cat_id>0)?'ID: '.$cat_id.' ':''?></span>Заголовки, ссылки, фото</h3>
	<div>
		Название категории: (все от начала строки до двойного пробела будет отображаться жирным шрифтом)<br>
		<input class="ti_inside" style="width: 70%" type="text" value="<?php echo htmlspecialchars($cat['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="title"><br><br>
		Title страницы:<br>
		<input id="pagetitle" class="ti_inside" style="width: 70%" type="text" value="<?php echo htmlspecialchars($cat['metatitle'],ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="metatitle"><br>
		<a href="javascript:apply_in_Sub(<?=$cat_id?>)">Применить ко всем подразделам</a>
		<br><br>
		МЕТА - Описание(meta_description):<br>
		<input class="ti_inside" style="width: 70%" type="text" value="<?php echo htmlspecialchars($cat['metadesc'],ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="metadesc"><br><br>
		МЕТА - ключевые слова(meta_keywords):<br>
		<input class="ti_inside" style="width: 70%" type="text" value="<?php echo htmlspecialchars($cat['metakeys'],ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="metakeys"><br><br>
		Включен:&nbsp;<input id="enabled" <?=($cat['onprom'] ?'disabled ':'')?>type="checkbox" style="border: none" value="1" name="enabled" <?php echo ($cat['enabled'] ? 'checked="checked"' : '')?>>&nbsp;
		Видим:&nbsp;<input id="visible" <?=($cat['onprom'] ?'disabled ':'')?>type="checkbox" style="border: none" value="1" name="visible" <?php echo ($cat['visible'] ? 'checked="checked"' : '')?>>
		<?
		if($sets['mod_rec_prds']){
			?>
			Наследовать рекоменд. товары:&nbsp;<input id="recpar" type="checkbox" style="border: none" value="1" name="recpar" <?php echo ($cat['recpar'] ? 'checked="checked"' : '')?>>
			<?
		}?>
		На продвижении:&nbsp;<input id="onprom" type="checkbox" style="border: none" value="1" name="onprom" <?php echo ($cat['onprom'] ? 'checked="checked"' : '')?>>Фраза:<input class="ti_inside" style="width: 30%" type="text" value="<?php echo htmlspecialchars($cat['promokey'],ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="promokey" id="promokey">
		<br><br>
		<input type="hidden" name="pid" id="pid" value="<?=$cat['parent_id']?>"></input>
		<a onclick="readtree(this,$('#pid').val(),0);return false;">Путь:</a>
		<span id="pexp"><?=$nm?></span>
		<?
		if($sets['cpucat']<2){// всегда включен ?>
		<br><br>ЧПУ Ссылка:&nbsp;&nbsp;<?echo button1('Создать текущую','change_urlt('.$cat_id.')','','def').button1('Создать во вложенных','change_urls('.$cat_id.')','id="btn55" '.(($cat_id>0)?'':'disabled'),'parent').button1('Создать в товарах','change_urlsprd('.$cat_id.')','id="btn56" '.(($cat_id>0)?'':'disabled'),'parent')?>
		<br><input class="ti_inside" style="width: 70%" type="text" value="<?php echo htmlspecialchars($cat['vlink'],ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="vlink" id="vlink"><br>	<?}?>
		Фото:<br>
		<table>
			<tr>
				<td><img id="foto_main" alt="" src="<?php echo SITE_URL?>thumb.php?id=<?php echo $cat['foto']?>&x=100&y=100" /></td>
				<td><input onchange="document.getElementById('foto_main').src = '<?php echo SITE_URL?>thumb.php?id=' + document.getElementById('url_abs_nohost').value + '&x=100&y=100'" name="foto" value="<?php echo $cat['foto']?>" id="url_abs_nohost" style="width:400px" />
					<a style="margin:0px 10px" href="javascript:;" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?php echo SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Выбрать файл</a></td></tr></table>
	</div>

	<H3>Описание категории:</H3>
	<div>
		<textarea id="editor1" name="cat_desc" style="height:500px; width:80%"><?php echo htmlspecialchars($cat['text'],ENT_COMPAT | ENT_XHTML,'utf-8')?></textarea>
		<script>tinyMCEInit('editor1')</script>
	</div>
<?	if($sets['mod_rec_prds']){
		$prs_all2 = $db->get_rows("SELECT p.id, p.cat_id, p.title FROM ".TABLE_PRODUCTS." p WHERE p.enabled = 1 && p.id IN (SELECT prdid FROM ".TABLE_GRREC." WHERE grid = ".$cat_id.")");
		$rec_prds_ids = array();
		foreach($prs_all2 as $id=>$value){
			$rec_prds_ids[] = $value['id'];
		}
	?>
	<h3>Рекомендуемые товары:</h3>
<div id="tab4">
		<table class="col">
		<tr><td class="p0 top"><div style="background-color:#fff;width:330px;height:400px; overflow-x:hidden;overflow-y:scroll;border:solid 1px"><?=get_newtreesubcat(11,1,'disable_controls,nocnt','rt1')?>
		</div></td>
		<td  style="width:300px;" class="p0 top"><div class="recom_prdds"></div></td>
		<td style="width:300px;"  class="p0 top">
		<select name="recom_products_all" multiple="multiple"  style="width:300px;height:400px">
<?foreach($prs_all2 as $id2=>$value2){
			echo '<option value="'.$value2['id'].'">'.$value2['title'].'</option>';
		}?>	
		</select>	
		</td>
		<tr>
		<td><input type="hidden" name="r_prds" value="<?=implode(',',$rec_prds_ids)?>" /></td>
		<td style="text-align:center;"><input onclick="add_rec()" type="button" value="Добавить" /></td>
		<td style="text-align:center;"><input onclick="del_rec()" type="button" value="Удалить" /></td>
		</tr>
				</tr>
		</table>
</div>
				
<?}?>				

						<?php
						if(!empty($_GET['parent'])):?>
						<input type="hidden" value="<?php echo $_GET['parent']?>" name="parent">
						<?php
						else:?>
						<input type="hidden" value="<?php echo $cat['parent_id']?>" name="parent">
						<?php endif;?>
						</div>
						<?php echo button1((($cat_id >= 0)? 'Сохранить изменения' : 'Создать категорию'),'save_cat('.$cat_id.');','','save').
						button1('Закрыть','swap_pan()','','cancel')?>

	</form><script>$( "#accordion" ).accordion();$(document).ready(function () {$("#onprom").click(function(){$("#visible").prop('disabled' , $(this).prop('checked'));$("#enabled").prop('disabled' , $(this).prop('checked'));});});</script>
	
	<?php
	$module['html'] = ob_get_contents();
	ob_end_clean();
	return $module['html'];
}

function ajax_product_edit($prd_id, $cat_id){				//**----------------------     Редактирование товара
	global $db, $sets, $ebox;
	$module['html'] = '';
	include INC_DIR.'params_func.php';
	$cat = $db->get(TABLE_PRODUCTS,$prd_id);
	$inf='';
	if (count($cat)>0){
		$q="SELECT p.sklad,p.zakazpost,p.reserv,ifnull(tm1.tot,0) as ztot,ifnull(cr.ctot,0) as ctot from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by d.prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$sets['cart_res_time'].")  group by prdid) as cr on cr.prdid=p.id WHERE p.id={$cat['id']}";
		$prdi=$db->get_rows($q);
		$inf=$prdi[0]['ztot']+$prdi[0]['reserv'];
		$inf="На складе: {$prdi[0]['sklad']}; Заказано поставщикам {$prdi[0]['zakazpost']}; Резерв в заказах 1C {$prdi[0]['reserv']}; Резерв в заказах cайт {$prdi[0]['ztot']}; Резерв в корзинах {$prdi[0]['ctot']}<br>";
	}
	if($prd_id != -1) $cat_id = $cat['cat_id'];
	$catinf = $db->get(TABLE_CATEGORIES,$cat_id);
	$gr_p = get_act_partr_r($cat_id);
	$parametrs = $db->get_rows("SELECT * FROM ".TABLE_PRD_P."  where sort>1 ORDER BY sort ASC, id ASC");
	if(count($_POST) > 0){
		$params = Array (
			'title' => $_POST['title'],
			'metatitle' => $_POST['metatitle'],
			'metadesc' => $_POST['metadesc'],
			'metakeys' => $_POST['metakeys'],
			'cat_id' => $cat_id
		);
		if($sets['mod_skidka']) $params['skidka'] = $_POST['skidka'];
		if($sets['mod_new']) $params['new'] = (!empty($_POST['new']) && $_POST['new'] == 1);
		if($sets['mod_hit']) $params['hit'] = (!empty($_POST['hit']) && $_POST['hit'] == 1);
		if($sets['mod_spec'])$params['spec'] = (!empty($_POST['spec']) && $_POST['spec'] == 1);
		$params['enabled'] = 0;
		$params['visible'] = (!empty($_POST['visible']) && $_POST['visible'] == 1);
		foreach($parametrs as $prm){
			if($prm['param_descr'] != 'foto'){
				if(in_array($prm['id'],$gr_p)){
					$params[$prm['param_descr']] = $_POST[$prm['param_descr']];
				}
			}
		}
		if(!empty($_FILES['foto']['name'])){
			$params['foto'] = $_FILES['foto']['name'];
			$new_dir = ISHOP_IMAGES.$_FILES['foto']['name'];
			$filename = $_FILES['foto']['name'];
			if(copy($_FILES['foto']['tmp_name'],$new_dir)){
				$typef=$_FILES["foto"]["type"];
			}
		}
		if($prd_id == -1){
			$db->insert(TABLE_PRODUCTS, $params);
		}
		else{
			$db->update(TABLE_PRODUCTS,$_GET['pID'],$params);
		}
		if($sets['mod_chars']){
			if(!empty($_POST['chars'])){
				foreach($_POST['chars'] as $id=>$value){
					$db->delete(TABLE_CHARS_VALUES, array('char_id'=>$id, 'prd_id'=> $_GET['pID']));
					$db->insert(TABLE_CHARS_VALUES, array('char_id'=>$id, 'prd_id'=> $_GET['pID'], 'value'=>$value));
				}
			}
		}
		if(isset($_POST['recom_products'])){
			foreach($_POST['recom_products'] as $cat_id => $prd_id){
				foreach($prd_id as $s_id){
					$cnt  = $db->get_rows("select count(*) from `cms_ishop_recom` where `r_product`='".intval($s_id)."' AND `product`='".intval($prd_id)."'");
					$cnt2 = $db->get_rows("select count(*) from `".TABLE_PRODUCTS."` where `id`='".intval($s_id)."'");
					if((!$cnt[0]['count(*)']) && ($cnt2[0]['count(*)']))
					$db->insert('cms_ishop_recom', array('r_product'=>$s_id, 'product'=>$_GET['pID']));
				}
			}
		}
		header("Location:".$catprelink.$act);
	}
	$module['html'] .= '<script>setup();</script>';
	$module['html'] .= '
	<form  name="forma" target="ajax_frame" action="include.php?place=ishop&amp;action=product&amp;pID='.$prd_id.'&parent='.$cat_id.'" method="post" enctype="multipart/form-data">
	<input value="1" name="edit_product" type="hidden" />
	';
	$new = (!empty($cat['new'])) ? 'checked="checked"' : '';
	$hit = (!empty($cat['hit'])) ? 'checked="checked"' : '';
	$spec = (!empty($cat['spec'])) ? 'checked="checked"' : '';
	$isupak = (!empty($cat['isupak'])) ? 'checked="checked"' : '';
	$skidka_day = (!empty($cat['skidka_day'])) ? 'checked="checked"' : '';
	$enable = (!empty($cat['enabled'])) ? 'checked="checked"' : '';
	$visible = (!empty($cat['visible'])) ? 'checked="checked"' : '';
	$saletype = (!empty($cat['saletype'])) ? 'checked="checked"' : '';
	$nm=get_catnm($catinf['parent_id'],0).'->'.$catinf['title'].'('.$cat_id.')';
	$module['html'] .= '
	<div id="first"><input value="'.$cat['id'].'" id="prd_id" type="hidden" />
	Текущий ID: '.$cat['id'].'&nbsp;&nbsp;<a onclick="readtree(this,$(\'#pid\').val());return false;">Путь: </a><span id="pexp">'.$nm.'</span>
	<table width="100%">
	<tr valign="middle">
	<td class="prd_te">
	Название товара:</td><td>
	<input class="ti_inside w500" type="text" value="'.htmlspecialchars($cat['title'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="title"></td></tr>
	<tr valign="middle">
	<td class="prd_te">
	Название латинское:</td><td>
	<input class="ti_inside w500" type="text" value="'.htmlspecialchars($cat['param_naimenovanielatinskoe'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="param_naimenovanielatinskoe"></td></tr>
	<tr valign="middle">
	<td class="prd_te">
	Title страницы:</td><td>
	<input class="ti_inside w500" type="text" value="'.htmlspecialchars($cat['metatitle'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="metatitle"></td></tr>
	<tr valign="middle">
	<td class="prd_te">
	МЕТА - Описание(meta_description):</td><td>
	<input class="ti_inside w500" type="text" value="'.htmlspecialchars($cat['metadesc'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="metadesc"></td></tr>
	<tr valign="middle">
	<td class="prd_te">
	МЕТА - ключевые слова(meta_keywords):</td><td>
	<input class="ti_inside w500" type="text" value="'.htmlspecialchars($cat['metakeys'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="metakeys"></td></tr>
	<tr valign="middle">
	<td class="prd_te">
	ЧПУ ссылка:</td><td>
	<input class="ti_inside w500" type="text" value="'.$cat['vlink'].'" name="vlink"></td></tr>
';
	$src='';
	if($cat['id']==$cat['srcid']){
		$src='Это отображаемый товар.';
	}
	else{
		if($cat['srcid']>0){
			$srcd = $db->get(TABLE_PRODUCTS,$cat['srcid']);
			if(count($srcd)>0) $src='Будет отображаться как '.$srcd['param_kodtovara'].' '.$srcd['title'];
		}
	}
	$module['html'] .= '<tr valign="middle">
	<td class="prd_te">
	<input type="hidden" name="pid" id="pid" value="'.$cat_id.'"></input>
	Отображаемый товар:</td><td>
	<input class="ti_inside" style="width:60px;" type="text" value="'.$cat['srcid'].'" name="srcid"> '.$src.'</td></tr>
	</table>
	';
	$module['html'] .= $inf;
	$module['html'] .= '<img align="left" style="margin:0px 20px 5px 5px" id="foto_main" alt="" src="'.SITE_URL.'thumb.php?id='.htmlspecialchars($cat['foto'],ENT_COMPAT | ENT_XHTML,'utf-8').'&x=100&y=100" onclick="return hs.expand(this, { src: \''.SITE_URL.'\'+$(\'#url_abs_nohost\').val() } )"/>';
	if($sets['mod_new']) $module['html'] .= '<span style="line-height: 2.1;font-weight:600">Новинка <input type="checkbox" '.$new.' value="1" name="new">';
	if($sets['mod_hit']) $module['html'] .= 'Хит продаж <input type="checkbox" '.$hit.' value="1" name="hit">';
	if($sets['mod_spec']){
		$module['html'] .= 'Спецпредложение <input type="checkbox" '.$spec.' value="1" name="spec">';
		$module['html'] .= 'Скидка дня <input type="checkbox" '.$skidka_day.' value="1" name="skidka_day">';
	}
	$module['html'] .= 'Открыт <input type="checkbox" '.$enable.' value="1" name="enabled">';
	$module['html'] .= 'Видим <input type="checkbox" '.$visible.' value="1" name="visible">';
	$module['html'] .= 'Автозакрытие <input type="checkbox" '.$saletype.' value="1" name="saletype">';
	$module['html'] .= '<br>';
	$module['html'] .=
	'Цена: <input class="ti_inside" style="width:60px;" type="text" value="'.htmlspecialchars($cat['tsena'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="tsena">
	Старая цена: <input class="ti_inside" style="width:60px;" type="text" value="'.htmlspecialchars($cat['param_starayatsena'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="param_starayatsena">';
	if($sets['mod_skidka'])	$module['html'] .= 'Скидка: <input class="ti_inside" style="width:60px;"  type="text" value="'.htmlspecialchars($cat['skidka'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="skidka">';
	$module['html'] .= '<br><labe>Это упаковка <input type="checkbox" '.$isupak.' value="1" name="isupak"></label> Колличество в упаковке: <input class="ti_inside" style="width:40px;" type="text" value="'.htmlspecialchars($cat['param_kolichestvo'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="param_kolichestvo">&nbsp;Группа заказов: <input class="ti_inside" style="width:40px;" type="text" value="'.htmlspecialchars($cat['tper'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="tper">';
	$module['html'] .= '<br>';
	$module['html'] .= 'Код товара:<input class="ti_inside" style="width:180px;" type="text" value="'.htmlspecialchars($cat['param_kodtovara'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="param_kodtovara">
	Размер цветка:<input class="ti_inside" style="width:60px;" type="text" value="'.htmlspecialchars($cat['param_razmertsvetka'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="param_razmertsvetka">
	Высота:<input class="ti_inside" style="width:60px;" type="text" value="'.htmlspecialchars($cat['param_visota'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="param_visota">
	Ширина:<input class="ti_inside" style="width:60px;" type="text" value="'.htmlspecialchars($cat['param_shirina'],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="param_shirina">
	';
	$module['html'] .= '';
	$module['html'] .='<br style="clear: both;"/>Фото: </span><input type="text" class="ti_inside" onchange="$(\'#foto_main\').attr(\'src\',\''.SITE_URL.'thumb.php?id=\' + $(\'#url_abs_nohost\').val() + \'&x=100&y=100\');" name="foto" value="'.$cat['foto'].'" id="url_abs_nohost" style="width:400px" /><a style="margin:0px 10px" href="javascript:;" onclick="mcImageManager.browse({fields : \'url_abs_nohost\', relative_urls : true, document_base_url : \''.SITE_URL.'\',remove_script_host : false,use_url_path : true,url:$(\'#url_abs_nohost\').val()});">Выбрать файл</a>';
	if($_SESSION['auth']['type'] == 'admin') $module['html'] .= '<br><a href="include.php?place=ishop&action=gr_params&editID=5">Параметры</a><br>';

	
	$module['html'] .= '<table width="100%">';
	foreach($parametrs as $row){
		if(in_array($row['id'],$gr_p) && $row['param_descr'] != 'hitprodazh' && $row['param_descr'] != 'utochnittsenu'){
			if($row['param_name'] == 'Фото'):
			$module['html'] .='<tr valign="middle"><td colspan="2">'. $row['param_name'].':<br>
			<table>
			<tr>
			<td><img id="foto_main" alt="" src="'.SITE_URL.'thumb.php?id='.htmlspecialchars($cat[$row['param_descr']],ENT_COMPAT | ENT_XHTML,'utf-8').'&x=100&y=100" onclick="return hs.expand(this, { src: \''.SITE_URL.'\'+$(\'#url_abs_nohost\').val() } )"/></td>
			<td><input onchange="$(\'#foto_main\').attr(\'src\',\''.SITE_URL.'thumb.php?id=\' + $(\'#url_abs_nohost\').val() + \'&x=100&y=100\');" name="'.$row['param_descr'].'" value="'.$cat[$row['param_descr']].'" id="url_abs_nohost" style="width:400px" /><a style="margin:0px 10px" href="javascript:;" onclick="mcImageManager.browse({fields : \'url_abs_nohost\', relative_urls : true, document_base_url : \''.SITE_URL.'\'});">[Выбрать файл]</a></td>
			</tr>
			</table>
			</td></tr>';
			elseif($row['param_name'] == 'Описание'):
			$module['html'] .= '<tr valign="middle"><td colspan="2">'.$row['param_name'].':<br>
			<textarea class="mceEditor" style="width: 70%" id="editor1" name="'.$row['param_descr'].'">'.htmlspecialchars($cat[$row['param_descr']],ENT_COMPAT | ENT_XHTML,'utf-8').'</textarea>
			<script>tinyMCEInit(\'editor1\')</script>
			</td></tr>
			';
			elseif($row['param_name'] == 'Полное описание'):
			$module['html'] .= '<tr valign="middle"><td colspan="2">'.$row['param_name'].':<br>
			<textarea class="mceEditor" style="height:90px; width: 70%" id="editor2" name="'.$row['param_descr'].'">'.htmlspecialchars($cat[$row['param_descr']],ENT_COMPAT | ENT_XHTML,'utf-8').'</textarea>
			<script>tinyMCEInit(\'editor2\')</script>
			</td><tr>
			';
			elseif($row['param_name'] == 'Бренд'):
			$module['html'] .= 'Производитель:<br>
			<select id="curent_cat" name="'.$row['param_descr'].'" style="width: 150px">
			<option value="0">не задан</option>
			'.getcategoriesoptions($cat[$row['param_descr']],$cat[$row['param_descr']],0,$ebox['id_proizv']).'
			</select>
			<br><br>
			';
			elseif($row['param_name'] == 'Материал' && !empty($sets['ishop_palitra'])):
			include 'filter_razmer_tsena.php';
			else:
			if ($row['param_type']=='date') 
			{
				$module['html'] .='<tr valign="middle"><td class="prd_te">'. $row['param_name'].':</td><td>
				<input class="ti_inside w500" style="max-width:300px" type="text" value="'.date('j.m.Y',$cat[$row['param_descr']]).'" name="'.$row['param_descr'].'"></td></tr>';
			}else
			{
				$module['html'] .='<tr valign="middle"><td class="prd_te">'. $row['param_name'].':</td><td>
				<input class="ti_inside w500" style="max-width:300px" type="text" value="'.htmlspecialchars($cat[$row['param_descr']],ENT_COMPAT | ENT_XHTML,'utf-8').'" name="'.$row['param_descr'].'"></td></tr>';
			}
			endif;
		}
	}
	$module['html'] .= '</table></div>';
	if($sets['mod_rec_prds']){
		$module['html'] .= '<div id="tab4">
		<table>
		<tr>
		<td style="vertical-align:top;" class="params_name"><p class="inf_title">Рекомендуемые товары:</p>
		<table class="col">
		<tr><td class="p0 top"><div style="width:330px;height:227px;overflow-x:hidden;overflow-y:scroll;border:solid 1px">';
		$module['html'] .=get_newtreesubcat(11,1,'disable_controls,nocnt','rt1');
		$module['html'] .= '</div></td>
		<td class="p0 top" style="width:300px"><div class="recom_prdds"></div>';
		$prs_all2 = $db->get_rows("SELECT p.id, p.cat_id, p.title FROM ".TABLE_PRODUCTS." p WHERE p.enabled = 1 && p.id IN (SELECT r_product FROM ".TABLE_RECOM." WHERE product = ".$prd_id.")");
		$rec_prds_ids = array();
		foreach($prs_all2 as $id=>$value){
			$rec_prds_ids[] = $value['id'];
		}
		$module['html'] .= '
		</td>
		<td class="p0 top" style="width:300px">';
		$module['html'] .= '<select name="recom_products_all" multiple="multiple"  style="width:300px" size="15">';
		foreach($prs_all2 as $id2=>$value2){
			$module['html'] .= '<option value="'.$value2['id'].'">'.$value2['title'].'</option>';
		}
		$module['html'] .= '
		</select>';
		$module['html'] .= '</td>
		</tr>
		<tr>
		<td>&nbsp;<input type="hidden" name="r_prds" value="'.implode(',',$rec_prds_ids).'" /></td>
		<td style="text-align:center;">'.button('Добавить',"add_rec()").'</td>
		<td style="text-align:center;">'.button('Удалить',"del_rec()").'</td>
		</tr>
		</table>';
		$module['html'] .= '
		</td></tr></table></div>';
	}
	if($sets['mod_chars']){
		$chars = $db->get(TABLE_CHARS, array('cat_id' => $cat_id), array('sort' => 'asc'));
		if(count($chars) > 0){
			$chars_values = $db->get(TABLE_CHARS_VALUES, array('prd_id' => $prd_id));
			$chars_vals = array();
			foreach($chars_values as $id=>$value){
				$chars_vals[$value['char_id']] = $value['value'];
			}
			unset($chars_values);
			$module['html'] .= '<div><table><tr>
			<td><p class="inf_title">Xар-ки:</p>
			<table>';
			foreach($chars as $char){
				$value = (isset($chars_vals[$char['char_id']])) ? $chars_vals[$char['char_id']] : '';
				$module['html'] .= '
				<tr>
				<td style="padding-left:7px;" class="params_name">'.htmlspecialchars($char['name'],ENT_COMPAT | ENT_XHTML,'utf-8').'</td>
				<td><input name="chars['.$char['char_id'].']" value="'.htmlspecialchars($value,ENT_COMPAT | ENT_XHTML,'utf-8').'" /></td>
				</tr>';
			}
			$module['html'] .= '
			</table>
			</td>
			</tr></table></div>
			';
		}
	}
	if($sets['mod_prd_foto']){
		include SA_DIR.'/modules/ishop/advphoto.php';
	}
	if($sets['mod_comments']){
		global $mes;
		$msgs = $db->get_rows("SELECT * FROM ".TABLE_COMMENTS." WHERE cat_id=".intval($prd_id)." && module = 2 ORDER BY id DESC");
		if(count($msgs) > 0){
			$module['html'] .= '<div id="third"><table><tr><td><p class="inf_title">Комментарии:</p>';
			foreach($msgs as $msg){
				$di = explode(' ', $msg['date']);
				$di2 = explode('-', $di['0']);
				$date = intval($di2['2']).' ';
				$date .= $mes[intval($di2['1'])-1].' ';
				$date .= $di2['0'].' г.';
				$module['html'] .= '<div id="prd_comm_'.$msg['id'].
				'" style="padding:5px 20px; border:1px solid #ccc;" class="o_box"><div class="img idel" title="Удалить" onclick="del_comments('.
				$msg['id'].')"/><div style="padding:2px 0px; class="o_name"><span style="font-weight:bold;">'.htmlspecialchars($msg['name'],ENT_COMPAT | ENT_XHTML,'utf-8').'</span> ['.
				$msg['ip'].'], '. $date.' <input type="checkbox" class="checkbox" id="dc_'.
				$msg['id'].'" '.(($msg['enabled'] == 1) ? 'checked="checked"' : '').'"/><label onclick="onof_comm(\''.$msg['id'].'\', this);" for="checkbox">'.(($msg['enabled'] == 1) ? 'Включено' : 'Выключено').'</label></div><div class="o_msg">'.nl2br(htmlspecialchars($msg['msg'],ENT_COMPAT | ENT_XHTML,'utf-8')).'</div></div>';
			}
			$module['html'] .= '</td></tr></table></div>';
		}
	}
	$module['html'] .= '<div align="left" style="padding:20px 10px 10px 40px">';
	if(!empty($cid)){
		$module['html'] .= '<input type="hidden" value="'.$cat_id.'" name="parent">';
	}
	$module['html'] .= button1((($prd_id >= 0)? 'Сохранить изменения' : 'Создать товар'),'paste_frame(); forma.submit();','','save').
	button1('Отменить',"history.back(1)",'','cancel').'</div></form>';
	$module['html'].=print_r($_SERVER,true);
	return $module['html'];
}
function cat_list($id=0, $level=0, $prd_id)
{
	global $db;
	global $recom;
	global $products;
	global $sel_pr_list;
	$result = '';
	$all_categories  = $db->get(TABLE_CATEGORIES, array('parent_id'=>$id), array('sort' => 'asc'));
	foreach($all_categories as $item)
	{
		$result .= "<option value='{$item['id']}'>".str_repeat("&nbsp;",$level).$item['title']."</option>";
		$result .= cat_list($item['id'], $level++, $prd_id);
		$all_products = $db->get(TABLE_PRODUCTS,   array('cat_id'=>$item['id']), array('sort' => 'asc'));
		$products .= '<div id="sel_'.$item['id'].'" style="display:none;float:right;padding-left:20px;">
						<select name="recom_products['.$item['id'].'][]" multiple style="width:200px" size="10">
					';
		foreach ($all_products as $item1)
		{
			$p_sel = $db->count(TABLE_RECOM, array('product' => $prd_id, 'r_product' => $item1['id']));
			$products .= '<option value="'.$item1['id'].'" '.($p_sel?"SELECTED":"").'>'.$item1['title'].'</option>';
			$sel_pr_list = $item1['cat_id'];
		}
		$products .= '</select></div>';
	}
	return $result;
}
function get_all_prds($cat_id)
{
	global $db;
	$cat_line = (is_array($cat_id)) ? implode(',', $cat_id) : $cat_id;
	$prds = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE cat_id IN(".$cat_line.")");
	$new_c = array();
	foreach($prds as $prd)
	{
		$new_c[] = $prd['id'];
	}
	return $new_c;
}
function get_all_sub_cats($cat_id)
{
	$r = get_sub_cats($cat_id);
	$b = $r;
	while(count($r)!=0)
	{
		$r = get_sub_cats($r);
		$b = array_merge($b, $r);
	}
	return $b;
}
function get_sub_cats($cat_id)
{
	global $db;
	$cat_line = (is_array($cat_id)) ? implode(',',$cat_id) : $cat_id;
	$new_c = array();
	if (!empty($cat_line)){
		$cats = $db->get_rows("SELECT id FROM ".TABLE_CATEGORIES." WHERE parent_id IN(".$cat_line.")");	
		foreach($cats as $cat)	$new_c[] = $cat['id'];
	}
	return $new_c;
}

function CopyCategoryFull($id,$whr)
{
	global $db;
	$res = $db->get(TABLE_CATEGORIES,$id);
	// copying category
	$chars = $db->get(TABLE_CHARS, array('cat_id' => $id));
	unset($res['id']);
	unset($res['vlink']);
	$res['parent_id'] = $whr;
	$db->insert(TABLE_CATEGORIES,$res);
	$last_id = $db->insert_id();
	foreach($chars as $id=>$value)
	{
		$value['cat_id'] = $last_id;
		unset($value['char_id']);
		$db->insert(TABLE_CHARS, $value);
	}
	if($id != $whr)
	{
		$last_id = $db->insert_id();
		// copying child categories...
		$q = $db->get_rows("select `id` from ".TABLE_CATEGORIES." where `parent_id` = '".$id."'");
		foreach ($q as $res2)
			CopyCategoryFull($res2['id'],$last_id);
		// copying products
		$q = $db->get_rows("select `id` from ".TABLE_PRODUCTS." where `cat_id` = '".$id."'");
		foreach ($q as $res)
			CopyProduct($res['id'],$last_id);
	}
}
function CopyCategoryOne($id,$whr)
{
	global $db;
	$res = $db->get(TABLE_CATEGORIES,$id);
	unset($res['id']);
	$db->insert(TABLE_CATEGORIES,$res);
}
function CopyCategoryLink($id,$whr)
{
	global $db;
	$res = $db->get(TABLE_CATEGORIES,$id);
// copying link
	if ($res['link'] < 0) $pid = $id; else $pid = $res['link'];
	$db->exec("insert into ".TABLE_CATEGORIES."
				(`title`,`text`,`enabled`,`metatitle`,`metadesc`,`metakeys`,`parent_id`,`sort`,`link`) values (
					'".$res['title']."','','".$res['enabled']."','',
					'','','".$whr."','','".$pid."'
				)");
}
function CopyProduct($id,$whr)
{
	global $db;
	$curr_id = $id;
	$res = $db->get(TABLE_PRODUCTS,$id);
	unset($res['id']);
	unset($res['vlink']);
	$res['cat_id'] = $whr;
	$db->insert(TABLE_PRODUCTS,$res);
	$last_id = $db->insert_id();
	$img_inf = $db->get_rows("SELECT filename, sort FROM ".TABLE_PRD_FOTO." WHERE product_id = ".intval($curr_id)."");
	foreach($img_inf as $id=>$value)
	{
		$value['product_id'] = $last_id;
		$db->insert(TABLE_PRD_FOTO, $value);
	}
	$img_inf = $db->get_rows("SELECT char_id, value FROM ".TABLE_CHARS_VALUES." WHERE prd_id = ".intval($curr_id)."");
	foreach($img_inf as $id=>$value)
	{
		$value['prd_id'] = $last_id;
		$db->insert(TABLE_CHARS_VALUES, $value);
	}
	return $last_id;
}
function DeleteCategory($cid)
{
	global $db;
	$q = $db->get_rows("select `id` from ".TABLE_CATEGORIES." where `parent_id` = '".$cid."'");
	foreach ($q as $res)
	{
		DeleteCategory($res['id']);
		$db->exec("delete from ".TABLE_CATEGORIES." where `id` = '".$res['id']."'");
		$q = $db->get_rows("select `id` from ".TABLE_PRODUCTS." where `cat_id` = '".$res['id']."'");
		foreach ($q as $res2) DeleteProductImages($res2['id']);
		$db->exec("delete from ".TABLE_PRODUCTS." where `cat_id` = '".$res['id']."'");
		DeleteCategoryImages($cid);
	}
}
function getcategoriesoptions($cid,$cpid,$lvl,$pid, $cats = array())
{
	global $db;
	$rtn = "";
	$str = strrpt('- ',$lvl);
	$q = $db->get_rows('select `id`,`title`,`parent_id` from '.TABLE_CATEGORIES.' where `parent_id` = "'.$pid.'" order by `id` asc');
	foreach ($q as $res)
	{
		if ($cpid == $res['id']) $sel = "selected"; else $sel = "";
		if(!in_array($res['id'], $cats))
		{
			$rtn .= '<option value="'.$res['id'].'" '.$sel.'>'.$str.$res['title'].'</option>';
			$rtn .= getcategoriesoptions($cid,$cpid,$lvl+1,$res['id'], $cats);
		}
	}
	return $rtn;
}
function get_catnm($id,$islink=1)
{
	global $db;
    $catpinf = $db->get(TABLE_CATEGORIES,$id);
    if (!(count($catpinf)>0)) return '';
	if ($islink==1) {
		$nm="<a title=\"Открыть\" href=".'"'."javascript:gotourl('include.php?place=ishop#open_sub_cats(".$id.", 1)')".'"'.">".$catpinf['title'].'</a>';
	}	else	{
		$nm=$catpinf['title'].'('.$catpinf['id'].')';
	}
	if ($catpinf['id']>0) $nm=get_catnm($catpinf['parent_id'],$islink).'->'.$nm;
	return $nm;
}
function get_treesubcat($cat,$lvl=1)
{
	global $db;
	$ret=Get_StreeSubCat($cat,$lvl=1);
	$module['html'] = '';
	$prelink = 'include.php?place=ishop';
	$turn_on ='<img alt="1" title="Выключить" width="18" height="19" src="'.TEMPLATE_URL.'images/plus.png" />';// "Включен";
	$turn_off ='<img alt="0" title="Включить" width="18" height="19" src="'.TEMPLATE_URL.'images/minus.png" />';// "Выключен";
	$turn_on1 ='<img alt="1" title="Закрыть" width="18" height="19" src="'.TEMPLATE_URL.'images/Y1.png" />';// "Видимый";
	$turn_off1 ='<img alt="0" title="Открыть" width="18" height="19" src="'.TEMPLATE_URL.'images/Y0.png" />';// "Скрытый";
	$act = $prelink."&action=catalog";
	$max=0;
	foreach($ret as $id=>$val)
	{
		$tt=array_values($val);
		if ($tt[0]>$max)	$max=$tt[0];
	}
	ob_start();
	$renable=array();
?>
	<form id="frmparts" style="margin:3px;" action="<?php echo htmlspecialchars($act,ENT_COMPAT | ENT_XHTML,'utf-8')?>" method="post" enctype="multipart/form-data">
	<script type="text/javascript">
	$(document).ready(function () {
	$("#markp").click(function(){
	$("input[name^='box']").prop('checked' , $(this).prop('checked'));
	});
	});
	</script>
	<table class="main_no_height" id="myTable">
	 <tr>
	  <th class="news_header" style="width:30px;">
	   <input style="height: 12px;" type="checkbox" class="check" id="markp"></th>
	  <th class="news_header" style="width:38px; text-align:center;">№</th>
	  <th class="news_header" style="width:60px; text-align:center;"></th>
	  <?php for ($i = 1; $i <= $max; $i++) {$renable[]=0;} ?>
	  <th class="news_header" colspan="<?=$max?>"></th>
	  <th style="width:60px; text-align:center;" class="news_header">Функции</th>
	 </tr>
<?	
	$oldlevel=0;
	foreach($ret as $id=>$val)
	{
		$tid=array_keys($val);
		$tlev=array_values($val);
		$catlist = $db->query("select * from ".TABLE_CATEGORIES." where id = ".$tid[0]."");
		while ($res = $db->fetch_array($catlist))
		{
			$link_id = $res['id'];
			$link_sort = $res['sort'];
			$link_en = $res['enabled'];
			$link_en1 = $res['visible'];
			$cpath = !empty($_GET['cPath'])? $_GET['cPath'].'_' : '';
			$cpath .= $res['id'];
			$status = '
				<a id="cparte_'.$link_id.'" href="javascript:part_on('.$link_id.', \'cat\')">'.(($link_en) ? $turn_on : $turn_off).'</a>
				<a id="cparte1_'.$link_id.'" href="javascript:part_on1('.$link_id.', \'cat\')">'.(($link_en1) ? $turn_on1 : $turn_off1).'</a>';
			$gr2_name = (!empty($res['gr_id']))?$gr_name[$res['gr_id']]:'---';
			if (!($oldlevel==$tlev[0])) 
			{
				$oldlevel=$tlev[0];
				if ($tlev[0]==1) 
				{
					$renable[0]=$link_en;
				}
				else
				{
					$tmp=1;
					for  ($i = 1; $i < $tlev[0]; $i++) {$tmp=$tmp*$renable[$i-1]*$link_en;}
					$renable[$tlev[0]-1]=$tmp;
				}
			}
			else
			{
				if ($tlev[0]==1) 
				{
					$renable[0]=$link_en;
				}
				else
				{
					$tmp=1;
					for  ($i = 1; $i < $tlev[0]; $i++) {$tmp=$tmp*$renable[$i-1]*$link_en;}
					$renable[$tlev[0]-1]=$tmp;
				}
			}
?>
	 <tr id="cat_row_<?php echo $link_id?>">
	  <td class="news_td"><input type="checkbox" class="check" name="boxcat[<?php echo $link_id?>]" id="boxes<?php echo $rowscnt?>"></td>
	  <td class="news_td"><input onchange="set_pos(<?php echo $link_id?>,this, 'cat')" type="text" class="part_sort" value="<?php echo htmlspecialchars($link_sort,ENT_COMPAT | ENT_XHTML,'utf-8')?>"></td>
	 <td class="news_td" style="text-align:center; width:60px"><?php echo $status?></td>
	  <?php for ($i = 1; $i < $tlev[0]; $i++) {?>
	  <td style="width:40px; text-align:right; padding-right: 2px " class="news_td">&nbsp;</td><?}?>
	  <td class="news_td" <? echo ($tlev[0]==$max) ? '1' : 'colspan="'.($max-$tlev[0]+1).'"' ?>>
	   <table class="cat_dir_t">
		<tr>
		 <td><a class="<? echo ($renable[$tlev[0]-1]==1) ? 'dir_icon' : 'dir_iconlock' ?>" href="javascript:gotourl('include.php?place=ishop#open_sub_cats(<?php echo $res['id']?>, 1)')"></a></td>
		 <td style="text-align:left;width:40px;"><a style="text-decoration: none;" href="https://chudoclumba.ru/ishop/<?php echo $res['id']?>" target="_blank"><?php echo $res['id']?></a></td>
		 <td><a class="cat_ttl" href="javascript:gotourl('include.php?place=ishop#update_cat_html(<?php echo $res['id']?>)')"><?php echo htmlspecialchars($res['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></a></td>
		 <td style="width:40px;"></td>
		 <td style="text-align:left;"><?php echo get_scatcnt($res['id']).' групп, '.get_prdcnt($res['id']).' товаров(<span style="color:#009000;">'.get_prdencnt($res['id']).'</span>)'?></td>
		</tr>
	   </table>
	  </td> 
	  <td class="news_td" style="text-align:center;">
	   <a href="javascript:gotourl('include.php?place=ishop#update_cat_html(<?php echo $res['id']?>)')"><?php echo edit_img()?></a>
<?php if ($res['id']==11)
{ echo('&nbsp;');}
else{
?>
   <a href="javascript:delete_r(<?php echo $res['id']?>, 'cat')"><?php echo del_img()?></a>
<? }
?>
	  </td>
	 </tr>
<?php 
		}
	}?>
	</table>
	</form>
<?php
	$module['html'] .= ob_get_contents();
	ob_end_clean();
	return $module['html'];
//	return $max;
}
function get_open_prdinord($page=1){
	global $db, $sets, $catprelink;
	$ret='';
	$page = ($page > -1) ? $page : 1;
	$pdtlist2 = $db->query("select * from d_prdinord");
	$pg_count = $db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';

	$pdtlist = $db->get_rows("select p.*,i.cnt,i.sum from d_prdinord i left join chudo_ishop_products p on i.id=p.id order by i.cnt desc ".$limit);
	ob_start();
?>
	<div class="search_pan">
	   <form method="post" action="<?php echo htmlspecialchars($act,ENT_COMPAT | ENT_XHTML,'utf-8')?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr."% ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="" name="search_string">
	   </form></div>
	<?
//	print_r(array($page,$cnt_p,$type));
	//if($type == 'all')
	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_prdinord(","end_link" =>")')",'info'=>$mes));
?>
<table class="main_no_height" id="myTable"><tr><th class="news_header" style="width:80px">В Заказах</th><th class="news_header" style="width:80px">На сумму</th>
<th style="width:52px; text-align:center;" class="news_header">Статус</th><th class="news_header" colspan="2" style="min-width:400px;">Товар</th>
<th style="width:60px; text-align:right; padding-right: 20px " class="news_header">Цена</th><th style="min-width:100px; text-align:left;" class="news_header">Период</th><th style="width:40px; text-align:left;" class="news_header">ID</th></tr>
<?	foreach($pdtlist as $res){
		$scl=(($res['id']==$res['srcid'])?' tcolr':'');
		$ssrc=array();
		$tsrc='';
		if ($res['srcid']>0 && $res['id']!=$res['srcid']){
			$ssrc = $db->get(TABLE_PRODUCTS,$res['srcid']);
			$tsrc=' (<a class="cat_ttl tcolr"  href="javascript:gotourl(\'include.php?place=ishop#update_prd_html('.$res['srcid'].')\')">'.htmlspecialchars($ssrc['title'],ENT_COMPAT | ENT_XHTML,'utf-8').'</a>)';
		}

?>
<tr id="prd_row_<?php echo $res['id'];?>"><td class="rgt"><?=$res['cnt']?></td>
<td class="rgt"><?=number_format($res['sum'],2,'.','')?></td>
<td><div class="enb<?=(($res['enabled']==1)?'':' edis')?>"></div><div class="vsb<?=(($res['visible']==1)?'':' eunv')?>"</div></td>
<td colspan="2"><a class="cat_ttl <?=$scl?>"  href="" onclick="mtdc(this);return false;"><span style="color:blue"><?php echo htmlspecialchars($res['param_kodtovara'],ENT_COMPAT | ENT_XHTML,'utf-8') ?></span>&nbsp;<?php echo htmlspecialchars($res['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></a><?=$tsrc?></td>
<td class="rgt"><?php echo $res['tsena']?></td><td><?php echo $res['param_srokpostavki']?></td><td><a style="text-decoration: none;" href="../ishop/product/<?php echo $res['id']?>" target="_blank"><?php echo $res['id']?></a></td></tr>
<?	}?>
	</table>
<?	
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_prdinord(","end_link" =>")')",'info'=>$mes));
	$ret.= ob_get_contents();
	ob_end_clean();
	return $ret;
}
function get_sub_cats_page($cat_id, $page = 1, $type = 'all',$srt=0) //-------------------  Функция вывода всех товаров
{
	global $db, $sets, $catprelink;
	$srtt=array('0'=>array('visible desc,enabled desc,sort,title','Как на сайте'),'1'=>array('id','Id'),'2'=>array('title','Наименвание'),'3'=>array('param_kodtovara','Код товара'),'4'=>array('tsena','Цена'));
	if ($type<>'all') $srtt[0]=array('sort,title','Как на сайте');
	$path = array();
	$parc = 0;
	$module['html'] = '';
	$prelink = 'include.php?place=ishop';
	$params = '';
	$page = ($page > -1) ? $page : 1;
	$lin = $sets['sus_lines'];
	$schstr='';
	$sstr='';
	$sstr1='';
	$gss="\'\'";
	if (!empty($_POST['search_string'])) $schstr=$_POST['search_string'];
	if (!empty($_GET['find'])) $schstr=String_RusCharsDeCode($_GET['find']);

	if ($schstr>" "){
		$gss="\'".String_RusCharsEnCode(str_replace ("'","_",$schstr))."\'";
		$sstr=" and (param_kodtovara LIKE '%".str_replace ("'","_",$schstr)."%' or title LIKE '%".str_replace ("'","_",$schstr)."%' or id='".str_replace ("'","",$schstr)."')";
		$sstr1=" and (title LIKE '%".str_replace ("'","_",$schstr)."%' or id='".str_replace ("'","",$schstr)."')";
	}

	if($type == 'spec')
	{
		$pdtlist2 = $db->query("select id from ".TABLE_PRODUCTS." where `spec` = 1".$sstr);
		$pg_count = $db->num_rows($pdtlist2);
		$db->free_result($pdtlist2);
		$query = "select * from ".TABLE_PRODUCTS." where `spec` = 1".$sstr." order by `sort` asc, `id` asc ";
	}
	elseif($type == 'hit')
	{
		$pdtlist2 = $db->query("select id from ".TABLE_PRODUCTS." where `hit` = 1".$sstr);
		$pg_count = $db->num_rows($pdtlist2);
		$db->free_result($pdtlist2);
		$query ="select * from ".TABLE_PRODUCTS." where `hit` = 1".$sstr." order by `sort` asc, `id` asc ";
	}
	elseif($type == 'new')
	{
		$pdtlist2 = $db->query("select id from ".TABLE_PRODUCTS." where `new` = 1".$sstr);
		$pg_count = $db->num_rows($pdtlist2);
		$db->free_result($pdtlist2);
		$query ="select * from ".TABLE_PRODUCTS." where `new` = 1 ".$sstr." order by ".$srtt[$srt][0].' ';
	}
	else
	{
		if (!empty($sstr)){
			$pdtlist2 = $db->query("select id from ".TABLE_PRODUCTS." where 1=1".$sstr);
			$catlist = $db->get_rows("select * from ".TABLE_CATEGORIES." where 1=1".$sstr1." order by visible desc, enabled desc,sort asc, title asc");
			$query ="select * from ".TABLE_PRODUCTS." where 1=1".$sstr." order by ".$srtt[$srt][0].' ';
		} else {
			$pdtlist2 = $db->query("select id from ".TABLE_PRODUCTS." where `cat_id` = '".$cat_id."'");
			$catlist = $db->get_rows("select * from ".TABLE_CATEGORIES." where `parent_id` = '".$cat_id."' order by visible desc, enabled desc,sort asc, title asc");
			$query ="select * from ".TABLE_PRODUCTS." where `cat_id` = '".$cat_id."' order by ".$srtt[$srt][0].' ';
		}
		$pg_count = $db->num_rows($pdtlist2);
		$db->free_result($pdtlist2);
	}
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';
	$query.=$limit;
	$pdtlist= $db->get_rows($query);
	
	$catinf = $db->get(TABLE_CATEGORIES,$cat_id);
	$act = $prelink."&action=catalog";
	if (isSet($_GET['cPath'])) $act .= "&cPath=".$_GET['cPath'];
	if (isSet($cat_id)) $act .= "&cID=".$cat_id;
	ob_start();
	?>
	<div class="news_pan">
	 <?php if ($type == 'spec') echo button1('Убрать из спецпредл.', "javascript: if (confirm('Вы действительно хотите убрать из спецпредложения выделенные товары?')) { SpecDeleting(); }",'id="btnsp" disabled','cancel');
	if ($type == 'new') echo button1('Убрать из новинок', "javascript: if (confirm('Вы действительно хотите убрать из новинок выделенные товары?')) { NewDeleting(); }",'id="btnnw" disabled','cancel');
	if ($type == 'hit') echo button1('Убрать из хитов', "javascript: if (confirm('Вы действительно хотите убрать из хитов выделенные товары?')) { HitDeleting(); }",'id="btnnw" disabled','cancel');
	if(isset($catlist) && count($catlist)>0) echo button1('Установить ГП', "SetGp( this );",'id="btngp" disabled','add');
	if(isset($catlist) && $sets['mod_rec_prds'] && count($catlist)>0) echo button1('Насл. рекомендуемые', "javascript: if (confirm('Будет установлено наследование рекомендуемых товаров. Продолжить?')){CopingRec( get_selects('boxcat') );}",'id="btnpar" disabled','parent');
	if(count($pdtlist)>0) echo button1('Объединить показ', "javascript: if (confirm('Объединение товаров. Продолжить?')){MakeGr( get_selects('boxprd') );}",'id="btncon" disabled','join');
	if ($type == 'all') echo button1('Копировать', "javascript: gotourl('include.php?place=ishop#Coping(\'' + get_selects('boxcat') + '\', \'' + get_selects('boxprd') + '\', ".$cat_id.")');",'id="btncop" disabled','copy');
	if ($type == 'all')echo button1('Переместить', "javascript: gotourl('include.php?place=ishop#Moving(".$cat_id.")'); ",'id="btnmv" disabled','move');
	echo button1('Удалить', "javascript: if (confirm('Вы действительно хотите удалить выделенные категории/товары?')) { Deleting(); }",'id="btndel" disabled','delete')?>
	</div>
	<div class="adv_news_pan">
	 <? if ($type == 'all') echo button1('Новая категория', "gotourl('include.php?place=ishop#add_this_page(".$cat_id.")')",'','add');
	 	if ($type == 'all') echo button1('Новый товар', "gotourl('include.php?place=ishop#add_prd_html(".$cat_id.")')",'','add')?>
	</div>
	<div class="clear"></div>
	<div class="search_pan">
	   <form method="post" action="<?php echo htmlspecialchars($act,ENT_COMPAT | ENT_XHTML,'utf-8')?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?=( !empty($schstr))?$schstr:''?>" name="search_string">
	   </form>
	</div>
	<div class="curr_dir_pan">
	 <a onclick="readtree(this,<?=$cat_id?>,11);return false;" id="chp" href="#">Сменить путь</a>
	 &nbsp;Сортировать 
	 <select onchange="gotourl('include.php?place=ishop#open_sub_cats(<?=$cat_id?>, 1,\'<?=$type?>\',\''+this.value+'\')')" id="curent_sort" style="width: 350px">
	 <?
foreach($srtt as $key => $val){
	echo '<option value="'.$key.(($key==$srt)?'" selected ':'"').'>'.$val[1].'</option>';
}
?>
	 </select>
	</div>
	<form id="frmparts" style="margin:3px;" action="<?php echo htmlspecialchars($act,ENT_COMPAT | ENT_XHTML,'utf-8')?>" method="post" enctype="multipart/form-data">
	<?php if(!empty($_POST['upload_price']) ){
		include 'price.php';
	}else{?>
	<script type="text/javascript">
	$(document).ready(function () {
	$("#markp").on('ifToggled',function(){
		if ($(this).prop('checked')){
			$("input[name^='box']").iCheck('check');
		} else {
			$("input[name^='box']").iCheck('uncheck');
			
		}
//		btn_state();
	});
	$("input[name^='box']").on('ifToggled',function(){btn_state();});
    $('input').iCheck({
	    checkboxClass: 'icheckbox_flat-green',
	    radioClass: 'iradio_flat-green'});	

	});
	</script>
	<?
	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_sub_cats(".$cat_id.",","end_link" => ",\'".$type."\',".$srt.",".$gss.")')",'info'=>$mes));
?>
<table class="main_no_height" id="myTable" onclick="mtcl(arguments[0])"><tr><th class="news_header" style="width:20px"><input style="height: 12px;" type="checkbox" class="check" id="markp"></th>
<th class="news_header" style="width:26px; text-align:center;">№</th><th style="width:65px; text-align:center;" class="news_header">Статус</th><th class="news_header" colspan="2" style="min-width:400px;">Категория/Товар</th>
<th class="news_header cen">Склад</th><th class="news_header cen">Пос-к</th>
<th class="news_header cen">Цена</th><th style="width:10px;" class="news_header">ГП</th><th style="min-width:100px; text-align:left;" class="news_header">Период</th><th style="width:40px; text-align:left;" class="news_header">ID</th><th class="news_header">Функции</th></tr>
		 <? if(count($catinf)>0 && $catinf['id'] != 0)
		 {
		 	$link_id = $catinf['parent_id']
?>
<tr id="cat_row_<?php echo $cat_id?>" bgcolor="#f1ebb6"><td colspan="3"></td><td colspan="7"><a class="dir_icon" href="javascript:gotourl('include.php?place=ishop#open_sub_cats(<?php echo $link_id ?>, 1)')" style="float:left;"></a><a class="cat_ttl" href="javascript:gotourl('include.php?place=ishop#open_sub_cats(<?php echo $link_id?>, 1)')">Выше...</a>&nbsp;<? echo get_catnm($catinf['parent_id']).'->'.$catinf['title']?></td>
<td><a style="text-decoration: none;" href="../ishop/<?php echo $cat_id?>" target="_blank"><?php echo $cat_id?></a></td>
<td><div class="img iop"></div></td></tr>
		 <? } /*		   <a href="javascript:delete_r(<?php echo $cat_id?>, 'cat')"><?php echo del_img()?></a>
*/?>
	<?php
		$rowscnt = 0;
		$q_p = $db->get_rows("SELECT * FROM ".TABLE_PRD_GR);
		foreach($q_p as $row)$gr_name[$row['gr_id']] = $row['gr_name'];
		if($type == 'all')
		{
			foreach ($catlist as $res )
			{
				$link_id = $res['id'];
				$link_sort = $res['sort'];
				$cpath = !empty($_GET['cPath'])? $_GET['cPath'].'_' : '';
				$cpath .= $res['id'];
				$gr2_name = (!empty($res['gr_id']))?$gr_name[$res['gr_id']]:'---';
?>
<tr id="cat_row_<?php echo $link_id?>"><td><?if ($link_id!=11){?>
<input type="checkbox" class="check" name="boxcat[<?php echo $link_id?>]"><?}?></td>
<td><input type="text" class="part_sort" value="<?php echo htmlspecialchars($link_sort,ENT_COMPAT | ENT_XHTML,'utf-8')?>"></td>
<td><div class="enb<?=(($res['enabled']==1)?'':' edis')?>"></div><div class="vsb<?=(($res['visible']==1)?'':' eunv')?>"</div></td><td colspan="3"><div class="dir_icon<?=($res['onprom']?' TrPr':'')?>"><?php echo htmlspecialchars($res['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></div></td>
<td><?php echo get_cattext($res['id'])?></td><td colspan="3"><?php echo get_scatcnt($res['id']).' групп, '.get_prdcnt($res['id']).' товаров(<span style="color:#009000;">'.get_prdencnt($res['id']).'</span>)'?></td>
<td style="text-align:left;" class="news_td"><a style="text-decoration: none;" href="../ishop/<?php echo $res['id']?>" target="_blank"><?php echo $res['id']?></a></td>
<td><div class="img iop"></div><div class="img<?=(($link_id!=11)?' idel':'')?>"></div><div class="img iclc"></div></td></tr>
<?php $rowscnt++;
			}
		}
		$rowscnt = 0;
		// выборка товаров
		foreach ($pdtlist as $res)
		{
			$link_id = $res['id'];
			$link_sort = $res['sort'];
			$res['hitprodazh'] = (!empty($res['hitprodazh'])) ? $res['hitprodazh'] : '';
			$ssrc=array();
			$tsrc='';
			if ($res['srcid']>0 && $res['id']!=$res['srcid']){
				$ssrc = $db->get(TABLE_PRODUCTS,$res['srcid']);
				$tsrc=' (<a class="cat_ttl tcolr src2" id="'.$res['srcid'].'">'.htmlspecialchars($ssrc['title'],ENT_COMPAT | ENT_XHTML,'utf-8').'</a>)';
			}
			$scl=(($res['id']==$res['srcid'])?' tcolr':'');
?>
<tr id="prd_row_<?php echo $link_id?>"><td><input type="checkbox" class="check" name="boxprd[<?php echo $link_id?>]"></td>
<td><input type="text" class="part_sort" value="<?php echo $link_sort?>"></td>
<td><div class="enb<?=(($res['enabled']==1)?'':' edis')?>"></div><div class="vsb<?=(($res['visible']==1)?'':' eunv')?>"></div><div class="acl<?=(($res['saletype']==1)?'':' acldis')?>"></div></td>
<td colspan="2"><span class="cat_ttl" style="color:blue"><?php echo htmlspecialchars($res['param_kodtovara'],ENT_COMPAT | ENT_XHTML,'utf-8') ?></span>&nbsp;<a class="cat_ttl <?=$scl?>"  href="" onclick="return false;"><?php echo htmlspecialchars($res['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></a><?=$tsrc?></td>
<td class="cen"><?php echo $res['sklad']?></td><td class="cen"><?php echo $res['zakazpost']?></td>
<td class="rgt"><?php echo $res['tsena']?></td><td><?php echo $res['tper']?></td><td><?php echo $res['param_srokpostavki']?></td><td><a style="text-decoration: none;" href="../ishop/product/<?php echo $res['id']?>" target="_blank"><?php echo $res['id']?></a></td><td><div class="img iop"></div><div class="img idel"></div></td></tr>
<?php 		$rowscnt++;
		}
?>
</table>
<script>
$('#myTable .part_sort').bind('change',function(){set_pos(this);});
</script>
<?
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_sub_cats(".$cat_id.",","end_link" => ",\'".$type."\',".$srt.",".$gss.")')",'info'=>$mes));
	}?>
</form>
	<?php
	$module['html'] .= ob_get_contents();
	ob_end_clean();
	return $module['html'];
}
function get_tper($id,$page){
	global $db, $sets, $catprelink;
	$path = array();
	$parc = 0;
	$module['html'] = '';
	$prelink = 'include.php?place=ishop';
	$params = '';
	$sch = 0;
	$page = ($page > -1) ? $page : 1;
	$schstr='';
	$sstr='';
	$gss="\'\'";
	if (!empty($_POST['search_string'])) $schstr=$_POST['search_string'];
	if (!empty($_GET['find'])) $schstr=String_RusCharsDeCode($_GET['find']);
	if ($schstr>" "){
		$gss="\'".String_RusCharsEnCode(str_replace ("'","_",$schstr))."\'";
		$sstr=" where name LIKE '%".str_replace ("'","_",$schstr)."%' or id LIKE '%".str_replace ("'","_",$schstr)."%'";
	}
	$pdtlist2 = $db->query("select id from ".TABLE_TPER.$sstr);
	$pg_count = $db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';
	$pdtlist = $db->get_rows("select * from ".TABLE_TPER.$sstr." order by id asc ".$limit);
	$act = $prelink."&action=tper";
	ob_start();
	?>
	<div class="adv_news_pan">
	 <?php echo button1('Новая группа', "NewTper(this);",'','add')?>
	</div>
	<div class="search_pan">
	   <form method="post" action="<?php echo htmlspecialchars($act,ENT_COMPAT | ENT_XHTML,'utf-8')?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?=$schstr?>" name="search_string">
	   </form>
	</div>
	<?
//
	//if($type == 'all')
	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_tper(0,","end_link" =>','.$gss.")')",'info'=>$mes)).'<div class="clear"></div>';
?>
	<table class="main_no_height" id="myTable"><tr>
		  <th class="news_header cen" style="width:38px;">ID</th>
		  <th class="news_header">Название</th>
		  <th class="news_header">Дата</th>
		  <th class="news_header cen" style="width:60px;">Функции</th>
		 </tr>
<?
	foreach ($pdtlist as $row){?>
		<tr id="ru_<?=$row['id']?>">
			<td class="news_td cen cli" onclick="view_tper(this);"><?=$row['id']?></td>
			<td class="news_td"><?=$row['name']?></td>
			<td class="news_td"><?=date('d.m.Y', $row['date'])?></td>
		  <td class="news_td" style="text-align:center;"></td>
		</tr><?
	} ?>
	 </table>
	<?php
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_tper(0,","end_link" =>','.$gss.")')",'info'=>$mes));
	$module['html'] .= ob_get_contents();
	ob_end_clean();
	return $module['html'];
}
function get_upak($id,$page){
	global $db, $sets, $catprelink;
	$path = array();
	$parc = 0;
	$module['html'] = '';
	$prelink = 'include.php?place=ishop';
	$params = '';
	$sch = 0;
	$page = ($page > -1) ? $page : 1;
	$schstr='';
	$sstr='';
	$gss="\'\'";
	if (!empty($_POST['search_string'])) $schstr=$_POST['search_string'];
	if (!empty($_GET['find'])) $schstr=String_RusCharsDeCode($_GET['find']);

	if ($schstr>" "){
		$gss="\'".String_RusCharsEnCode(str_replace ("'","_",$schstr))."\'";
		$sstr=" where name LIKE '%".str_replace ("'","_",$schstr)."%' or descr LIKE '%".str_replace ("'","_",$schstr)."%' or id LIKE '%".str_replace ("'","_",$schstr)."%'";
	}
	$pdtlist2 = $db->query("select id from ".TABLE_UPAK.$sstr);
	$pg_count = $db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';
	$pdtlist = $db->get_rows("select * from ".TABLE_UPAK.$sstr." order by name asc ".$limit);
	$act = $prelink."&action=upak";
	ob_start();
	?>
	<div class="adv_news_pan">
	 <?php echo button1('Новый cтандарт', "NewUpak(this);",'','add')?>
	</div>
	<div class="search_pan">
	   <form method="post" action="<?php echo htmlspecialchars($act,ENT_COMPAT | ENT_XHTML,'utf-8')?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?=$schstr?>" name="search_string">
	   </form>
	</div>
	<?
//
	//if($type == 'all')
	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_upak(0,","end_link" =>','.$gss.")')",'info'=>$mes)).'<div class="clear"></div>';
?>
	<table class="main_no_height" id="myTable"><tr>
		  <th class="news_header cen" style="width:38px;">ID</th>
		  <th style="width:80px;" class="news_header">Название</th>
		  <th class="news_header">Описание</th>
		  <th class="news_header cen" style="width:60px;">Функции</th>
		 </tr>
<?
	foreach ($pdtlist as $row){?>
		<tr id="ru_<?=$row['id']?>">
			<td class="news_td cen cli" onclick="view_upak(this);"><?=$row['id']?></td>
			<td class="news_td"><?=$row['name']?></td>
			<td class="news_td"><?=$row['descr']?></td>
		  <td class="news_td" style="text-align:center;"><div class="img idel" title="Удалить" onclick="deleteupak('<?=$row['id']?>')"></div></td>
		</tr><?
	} ?>
	 </table>
	<?php
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => "javascript:gotourl('include.php?place=ishop#open_upak(0,","end_link" =>','.$gss.")')",'info'=>$mes));
	$module['html'] .= ob_get_contents();
	ob_end_clean();
	return $module['html'];
}
function get_cattext($id)
{
	global $db;
    $res = $db->get(TABLE_CATEGORIES,$id);
	$catpinf = $res['text'];
	$nm='';
	if (!empty($catpinf) && strlen($catpinf)>2)
	{
		$nm="<a title=\"Посмотреть описание\" class=\"text_icon highslide-active-anchor\" onclick=\"view_cattext(this,'".$id."');\"></a>";
	}
	return $nm;
}
function get_materials()
{
	global $db;
	$materials = $db->get_rows("SELECT id, name FROM ".TABLE_MATERIAL."");
	$cnt = '<table>';
	foreach($materials as $id=>$material)
	{
		$cnt .= '<tr><td class="material_e"><input onchange="update_mat('.$material['id'].', this)" value="'.htmlspecialchars($material['name'],ENT_COMPAT | ENT_XHTML,'utf-8').'" /></td>
		<td class="material_e"><a href="javascript:edit_mat('.$material['id'].')">'.edit_img().'</a></td>
		<td class="material_e"><a href="javascript:del_mat('.$material['id'].')">'.del_img().'</a></td>
		</tr>';
	}
	$cnt .= '<tr><td class="material_e"><input type="text" id="new_mat" /></td>
	 <td class="material_e" id="add_mat_btn"><a href="javascript:add_material($(\'#new_mat\').val())">'.add_img().'</a></td>
	 <td>&nbsp;</td>
	</tr>';
	$cnt .= '</table>';
	return $cnt;
}
function edit_materials($id)
{
	global $db;
	$colors = $db->get_rows("SELECT id, color FROM ".TABLE_COLOR." WHERE id_mat=".$id."");
	$cnt = '<input type="hidden" id="edit_id" value="'.$id.'" />
		 <textarea id="editor1" name="cat_desc" style="display:none; height:500px; width:80%"></textarea>
		<script>tinyMCEInit(\'editor1\')</script>
	<table>';
	foreach($colors as $id=>$color)
	{
		$cnt .= '<tr><td class="material_e">
			<table>
			<tr>
			 <td><img id="foto_main" alt="" src="'.SITE_URL.'thumb.php?id='.$color['color'].'&x=30&y=30" /></td>
			 <td><input onchange="update_color('.$color['id'].', this);" name="colormm" value="'.$color['color'].'" id="url_abs_nohost" style="width:200px" /><a style="margin:0px 10px" href="javascript:;" onclick="mcImageManager.browse({fields : \'url_abs_nohost\', relative_urls : true, document_base_url : \''.SITE_URL.'\'});">[Выбрать файл]</a></td>
			</tr>
			</table>
</td>
		<td class="material_e"><a href="javascript:del_color('.$color['id'].')">'.del_img().'</a></td>
		</tr>';
	}
	$cnt .= '<tr><td class="material_e">
			<table>
			<tr>
			 <td><img id="foto_mainn" alt="" src="'.SITE_URL.'thumb.php?id=&x=30&y=30" /></td>
			 <td><input onchange="document.getElementById(\'foto_mainn\').src = \''.SITE_URL.'thumb.php?id=\' + document.getElementById(\'new_color\').value + \'&x=30&y=30\'" name="colormm" value="" id="new_color" style="width:200px" /><a style="margin:0px 10px" href="javascript:;" onclick="mcImageManager.browse({fields : \'new_color\', relative_urls : true, document_base_url : \''.SITE_URL.'\'});">[Выбрать файл]</a></td>
			</tr>
			</table>
	</td>
	 <td class="material_e" id="add_mat_btn"><a href="javascript:add_color($(\'#new_color\').val())">'.add_img().'</a></td>
	</tr>';
	$cnt .= '<tr><td tyle="padding-top:10px;"><a href="javascript:back_mat()">Назад к списку</a></td>
	 <td>&nbsp;</td>
	</tr>';
	$cnt .= '</table>';
	return $cnt;
}
function ajax_edit_cat_mod_form($cat_id)
{
	global $db;
	ob_start();
	?>
	<form action="" method="post">
	   <table class="table_g">
		<tr>
		 <td style="padding:0px 3px; font-size:11px;">Хар-ка</td>
		 <td style="padding:0px 3px;"><input id="charname" name="charname" /></td>
		 <td style="padding:0px 3px;"><?php echo button('Добавить','insert_char($(\'#charname\').val(), '.$cat_id.')')?></td>
		</tr>
	</table>
	</form>
	<p class="inf_title">Xар-ки товаров:</p>
	<form name="forma" action="" method="post" style="margin:3px;">
	   <table class="main_no_height">
		<tr>
		 <th class="news_header" style="width:30px;">#</th>
		 <th class="news_header">Хар-ка</th>
		 <th style="width:100px; text-align:center;" class="news_header">Удалить</th>
		</tr>
	<?php
		$chars = $db->get(TABLE_CHARS, array('cat_id' => $cat_id), array('sort' => 'asc'));
		foreach($chars as $char)
		{
	?>
			<tr>
			 <td class="news_td"><input style="width:70px;" name="sort_chars[<?php echo $char['char_id']?>]" value="<?php echo $char['sort']?>" /></td>
			 <td class="news_td"><input style="width:100%;" name="title_chars[<?php echo $char['char_id']?>]" value="<?php echo $char['name']?>" /></td>
			 <td class="news_td" style="text-align:center;"><input name="del_chars[<?php echo $char['char_id']?>]" type="checkbox" value="1" /></td>
			</tr>
	<?php	}?>
	   </table>
	   <p><?php echo button('Сохранить изменения','forma.submit()')?></p>
	</form>
	<?php
	$module['html'] = ob_get_contents();
	ob_end_clean();
	return $module['html'];
}