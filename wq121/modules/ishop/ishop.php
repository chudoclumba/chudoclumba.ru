<?php

if(!empty($_GET['action']) && $_GET['action'] == 'cr_ajax_cats')
{
	$prs_all = $db->get_rows("SELECT id, cat_id, title,enabled FROM ".TABLE_PRODUCTS." WHERE visible = 1 && (srcid=0 or srcid=id) && cat_id = ".quote_smart($_GET['id'])." order by enabled desc,title");
	if (count($prs_all)>0){
		$module['html'] .= '<div style="display:none;" id="cat_s_'.$_GET['id'].'"><select class="all_selects" name="recom_products['.$_GET['id'].'][]" multiple  style="width:300px;height:400px">';
		foreach($prs_all as $id=>$value){
			$slt = ($value['enabled']!=1) ? 'class="edis"' : '';
			$module['html'] .= '<option '.$slt.' value="'.$value['id'].'">'.$value['title'].'</option>';
		}
		$module['html'] .= '</select></div>';	
	}
	if($module['html'] == ''){
		$module['html'] .= '<div style="position:absolute; left:0px; z-index:0; top:0px; display:none;" id="cat_s_'.$_GET['id'].'"><select name="recom_products['.$_GET['id'].'][]" multiple  style="width:300px;height:400px"></select></div>';
	}
	echo $module['html'];
	exit;
}


if(!empty($_GET['action']) && $_GET['action'] == 'price')
{
	include MODULES_PATH.$global_place."/price.php";
	exit;
}
if(count($_POST) > 0)
{
	if (!empty($_POST['edit_product']))					// сохраняем изменения после редактирования товара
	{
		include INC_DIR.'params_func.php';
		$cat = $db->get(TABLE_PRODUCTS,$_GET['pID']);
		$cid = ($_GET['pID'] < 0) ? $_GET['parent'] : $cat['cat_id'];
		$gr_p = get_act_partr_r($cid);
		$parametrs = $db->get_rows("SELECT * FROM ".TABLE_PRD_P." ORDER BY id ASC");
		$params = Array (
			'title' => $_POST['title'],
			'metatitle' => $_POST['metatitle'],
			'metadesc' => $_POST['metadesc'],
			'metakeys' => $_POST['metakeys'],
			'vlink' => $_POST['vlink'],
			'foto' => $_POST['foto'],
			'cat_id' => $_POST['pid'],
			'tper' => $_POST['tper']
		);
		$ins_art = '';
		foreach($_POST['value'] as $id=>$rows)
		{
			if(empty($_POST['del_art'][$id]))
			{
				$ins_art .= '<articul>';
				foreach($rows as $ids=>$row)
				{
					if(empty($_POST['del_column'][$ids]))	$ins_art .= '<param>'.$row.'</param>';
				}
				$ins_art .= '</articul>';
			}
		}
		$_POST['param_material'] = $ins_art;
		if($sets['mod_skidka']) $params['skidka'] = intval($_POST['skidka']);
		if($sets['mod_new']) $params['new'] = intval(!empty($_POST['new']) && $_POST['new'] == 1);
		if($sets['mod_hit']) $params['hit'] = intval(!empty($_POST['hit']) && $_POST['hit'] == 1);
		$params['enabled'] = intval(!empty($_POST['enabled']) && $_POST['enabled'] == 1);
		$params['visible'] = intval(!empty($_POST['visible']) && $_POST['visible'] == 1);
		$params['saletype'] = intval(!empty($_POST['saletype']) && $_POST['saletype'] == 1);
		if($sets['mod_spec']) $params['spec'] = intval(!empty($_POST['spec']) && $_POST['spec'] == 1);
		$params['skidka_day'] = intval(!empty($_POST['skidka_day']) && $_POST['skidka_day'] == 1);
		if($sets['nal_sklad']) $params['count'] = intval($_POST['count']);
		foreach($parametrs as $prm)
		{
			if(in_array($prm['id'],$gr_p))
			{
				if($prm['param_type'] == 'decimal(15,2)' || $prm['param_descr'] == 'param_starayatsena')
				{
					$params[$prm['param_descr']] = $_POST[$prm['param_descr']] + 0.00;
				}
				else
				{
					$params[$prm['param_descr']] = $_POST[$prm['param_descr']];
				}
			}
			if ($prm['param_type'] == 'date') $params[$prm['param_descr']] = strtotime($_POST[$prm['param_descr']]); 
		}
		$params['param_kolichestvo'] = (!empty($_POST['param_kolichestvo'])) ? intval($_POST['param_kolichestvo']) : 0;
		$params['isupak'] = (!empty($_POST['isupak'])) ? intval($_POST['isupak']) : 0;
		$params['srcid'] = (!empty($_POST['srcid'])) ? intval($_POST['srcid']) : 0;
		if ($_GET['pID'] == -1)
		{
			$db->insert(TABLE_PRODUCTS, $params);
			$new_prd_id = $db->insert_id();
			$_GET['pID'] = $new_prd_id;
			$db->update(TABLE_PRD_FOTO, array('product_id'=>-1),array('product_id'=>$new_prd_id));
		}
		else
		{
			$db->update(TABLE_PRODUCTS,$_GET['pID'],$params);
		}
		if($sets['mod_chars'])
		{
			if(!empty($_POST['chars']))
			{
				foreach($_POST['chars'] as $id=>$value)
				{
					$db->delete(TABLE_CHARS_VALUES, array('char_id'=>$id, 'prd_id'=> $_GET['pID']));
					$db->insert(TABLE_CHARS_VALUES, array('char_id'=>$id, 'prd_id'=> $_GET['pID'], 'value'=>$value));
				}
			}
		}
		if($sets['mod_rec_prds']){
			$db->delete(TABLE_RECOM, array('product'=>$_GET['pID']));
			if (!empty($_POST['r_prds'])){
				$r_prdsv = explode(',', $_POST['r_prds']);
				foreach($r_prdsv as $s_id=>$val){
					if ($val>0) $db->insert(TABLE_RECOM, array('r_product'=>$val, 'product'=>$_GET['pID']));
				}
			}
		}
?>
		<script>
		window.parent.return_i('<?=trim($_GET['pID'])?>');
		</script>
<?php
		exit;
	}

	if(!empty($_POST['sort_chars']) && count($_POST['sort_chars']) > 0)
	{
		foreach($_POST['sort_chars'] as $id=>$value)
		{
			if(empty($_POST['del_chars'][$id]))
			{
				$params = array(
					'sort'=> $_POST['sort_chars'][$id],
					'name'=> $_POST['title_chars'][$id]
				);


				$db->update(TABLE_CHARS, array('char_id'=>$id), $params);
			}
			else
			{
				$db->delete(TABLE_CHARS, array('char_id' => $id));
			}

		}
	}


	if (isSet($_GET['edit_cat']) && isSet($_GET['cID']))
	{
		global $sets;
		$last_id = 0;
		$params = Array (
			'title' => $_POST['title'],
			'metatitle' => $_POST['metatitle'],
			'metadesc' => $_POST['metadesc'],
			'metakeys' => $_POST['metakeys'],
			'text' => $_POST['cat_desc'],
			'foto' => $_POST['foto'],
			'vlink' => $_POST['vlink'],
			'parent_id' => $_POST['pid']
		);
		$params['enabled'] = intval(!empty($_POST['enabled']) && $_POST['enabled'] == 1);
		$params['visible'] = intval(!empty($_POST['visible']) && $_POST['visible'] == 1);
		$params['recpar'] = intval(!empty($_POST['recpar']) && $_POST['recpar'] == 1);
		if($_GET['cID'] == -1){
			$params['gr_id'] = '5';
			$db->insert(TABLE_CATEGORIES,$params);
		}
		else{
			$db->update_notrim(TABLE_CATEGORIES,$_GET['cID'],$params);
		}
		if($sets['mod_rec_prds']){
			$db->delete(TABLE_GRREC, array('grid'=>$_GET['cID']));
			if(!empty($_POST['r_prds'])){
				$r_prdsv = explode(',', $_POST['r_prds']);
				foreach($r_prdsv as $s_id=>$val){
					if($val>0) $db->insert(TABLE_GRREC, array('prdid'=>$val, 'grid'=>$_GET['cID']));
				}
			}
		}

		?>
		<script>
			window.parent.return_i();
		</script>
		<?php
		exit;
	}

	if(!empty($_GET['save_cat_mod']))
	{
		foreach($_POST['sort'] as $id=>$value)
		{
			$whr = array('char_id' => $id);

			if(!empty($_POST['del'][$id]))
			{
				$db->delete(TABLE_CHARS, $whr);
			}
			else
			{
				$params = array(
					'name' => $_POST['title'][$id],
					'sort' => $_POST['sort'][$id]
				);

				$db->update(TABLE_CHARS, $whr, $params);
			}
		}
		exit;
	}

	if(!empty($_GET['add_cat_mod']))
	{
		$params = array(
			'name' => $_POST['charname'],
			'cat_id' => $_GET['cID']
		);

		$db->insert(TABLE_CHARS, $params);
		exit;
	}
}
$module['scripts'] .= MODULES_PATH.$global_place.'/ishop.js';
include MODULES_PATH.$global_place."/funcs.php";
$_GET['action'] = (!empty($_GET['action']))?$_GET['action']:'';
$catprelink = $prelink."&action=catalog";
$image_types = array("thumb" => "Иконки", "view" => "Изображение");
$q = $db->get_rows("select * from ".TABLE_SETTINGS." where `part` = '".$global_place."'");
foreach ($q as $res) $sts[$res['id']] = $res['value'];
$s_link = 'include.php?place='.$_GET['place'].'&action='.$_GET['action'].'';
$_GET['cID'] = (!empty($_GET['cID'])) ? $_GET['cID'] : '0';
//print_r($_GET['action']);
switch ($_GET['action'])
{
	case 'catalog' :{
	$btns = get_cat_btns(1);
	$module['html'] = get_sub_cats_page($_GET['cID']); break;
	};
	case 'upak' :{
	$module['html'] = get_upak(0,1); break;
	};
	case 'modules' : include MODULES_PATH.$global_place."/m.php"; break;

	case 'category' :
	{
		$module['html'] = ajax_edit_cat_form($_GET['cID']); break;
	};
	case 'dwnload' : downloadFile($_GET['file']); break;

	case 'product' : $module['html'] = ajax_product_edit($_GET['pID'], -1); break;
	case 'log' : {
		$module['html'] = view_log();
		$module['path'] = 'Лог работы с заказами';
		$btns = array(
			'Заказы'=>array('href'=>'javascript:location.href=\'include.php?place=ishop&action=order\''),
			'Лог'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=ishop&action=log\'')
		);
		break;
		
	}

	case 'comments' : { $module['html'] .= '<div class="modules_path"><nobr>Комментарии</nobr></div><br>'; include MODULES_PATH.$global_place."/comments.php"; } break;

	case 'color' : {

		$module['path'] = 'Палитра';
		include MODULES_PATH.$global_place."/color.php";
	}
	break;

	case 'specpredl' : {

		$module['path'] = 'Спецпредложение';
		$module['html'] = get_sub_cats_page(0,1,'spec');
	}
	break;

	case 'hits' : {

		$module['path'] = 'Хит продаж';
		$module['html'] = get_sub_cats_page(0,1,'hit');
	}
	break;

	case 'novinki' : {

		$module['path'] = 'Новинки';
		$module['html'] = get_sub_cats_page(0,1,'new');
	}
	break;

	case 'settings' : include MODULES_PATH.$global_place."/settings.php"; break;
	case 'convusers' : {

		$module['path'] = 'Палитра';
		include MODULES_PATH.$global_place."/convusers.php";
	}
	break;

	case 'order' : {
				include MODULES_PATH.$global_place."/order.php";
			} break;
	case 'customerstat' : {
				$module['html'] .= '<div class="modules_path"><nobr>Статистика по клиентам</nobr></div><br>';
				include MODULES_PATH.$global_place."/customerstat.php";
			} break;
	case 'params' : {
				include MODULES_PATH.$global_place."/params.php";
			} break;
	case 'gr_params' : {
				include MODULES_PATH.$global_place."/gr_params.php";
			} break;

	case 'currency' : {
				$module['html'] .= '<div class="modules_path"><nobr>Валюта</nobr></div><br>';
				include MODULES_PATH.$global_place."/currency.php";
			} break;

	case 'options' : {
				$module['html'] .= '<div class="modules_path"><nobr>Настройки</nobr></div><br>';
				include MODULES_PATH.$global_place."/options.php";
			} break;
	case 'change_price' : {
				$module['html'] .= '<div class="modules_path"><nobr><a href="'.$s_link.'">Изменение цен</a></nobr></div><br>';
				include MODULES_PATH.$global_place."/change_price.php";
			} break;
	case 'chars' : {
				$module['html'] .= ajax_edit_cat_mod_form($_GET['cID']);
				break;
			}

	default:
	{
		$btns = get_cat_btns(0);
//		$module['html'] = get_sub_cats_page(0);
	};
}

//////////////

?>