<?php

$module['html'] .= '
<table class="col">
<tr>
<td class="text top" style="padding-top: 10px; padding-left: 10px;">
<form name="forma" action="include.php?place='.$global_place.'" method="post">';

require SA_DIR.'modules/sitemenu/functions.php';

function getcategoriesoptions($cid,$cpid,$lvl,$pid, $cats = array()){
	global $db;
	$rtn = "";
	$str = strrpt('- ',$lvl);
	$q = $db->get_rows('select `id`,`title`,`parent_id` from '.TABLE_CATEGORIES.' where `parent_id` = "'.$pid.'" order by `id` asc');
	foreach($q as $res){
		if($cpid == $res['id']) $sel = "selected";
		else $sel = "";
		if(!in_array($res['id'], $cats)){
			$rtn .= '<option value="'.$res['id'].'" '.$sel.'>'.$str.$res['title'].'</option>';
			$rtn .= getcategoriesoptions($cid,$cpid,$lvl+1,$res['id'], $cats);
		}
	}
	return $rtn;
}

foreach($edit_boxes as $key=>$val){
	if((!empty($sets['allow_feedback']) && $key == 'id_feedback') || $key != 'id_feedback'){
		if((!empty($sets['mod_proizv']) && $key == 'id_proizv') || $key != 'id_proizv'){
			if(count($_POST) > 0){
				$db->exec("update ".TABLE_EDITBOXES." set text = ".quote_smart($_POST[$key])." where id = ".quote_smart($key)."");
			}
			$q = $db->count(TABLE_EDITBOXES,$key);
			if($q==0){
				$q = $db->exec("insert into ".TABLE_EDITBOXES." (id,text) values (".quote_smart($key).", NULL);");
			}
			$row = $db->get(TABLE_EDITBOXES,$key);
			if($key == 'id_feedback' || $key == 'id_main'){
				ob_start();
				?>
				<select name="<?=htmlspecialchars($key,ENT_COMPAT | ENT_XHTML,'UTF-8')?>" style="width: 150px">
					<?php echo gettreeoptions(-1,$row['text'],0,0, array(), 0)?>
				</select> - <?=$val?><br><br>
				<?
				$module['html'] .=  ob_get_contents();
				ob_end_clean();
			}
			elseif($key == 'spdst' || $key == 'spopl'){
				$module['html'] .=  $val.':<br><textarea style="width:600px; height:70px;" name="'.htmlspecialchars($key,ENT_COMPAT | ENT_XHTML,'UTF-8').'">'.htmlspecialchars($row['text'],ENT_COMPAT | ENT_XHTML,'UTF-8').'</textarea><br><br>';
			}
			elseif($key == 'id_proizv'){
				ob_start();
				?>
				<select name="<?=htmlspecialchars($key,ENT_COMPAT | ENT_XHTML,'UTF-8')?>" style="width: 150px">
					<?php echo getcategoriesoptions(-1,$row['text'],0,0)?>
				</select> - <?=$val?><br><br>
				<?
				$module['html'] .=  ob_get_contents();
				ob_end_clean();
			}
			else{
				$module['html'] .=  $val.':<br><input style="width:600px;" value="'.htmlspecialchars($row['text'],ENT_COMPAT | ENT_XHTML,'UTF-8').'" type="text" name="'.htmlspecialchars($key,ENT_COMPAT | ENT_XHTML,'UTF-8').'"><br><br>';
			}
		}
	}
}

$module['html'] .= button('Сохранить изменения','forma.submit();').'

</form>
</td>
</tr>
</table>';
