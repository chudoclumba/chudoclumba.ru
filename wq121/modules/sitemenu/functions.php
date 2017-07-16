<?php

function get_subcats($cat_id)
{
	global $db;
	$u = 1;
	$arr_all = array($cat_id);
	$idss123 = array($cat_id);
	
	while($u != 0)
	{
		$u = 0;
		$ids6 = array();
		$rowsss2 = $db->get_rows("SELECT id FROM ".TABLE_SITEMENU." WHERE pid IN (".implode(',',$idss123).")");
		foreach($rowsss2 as $id2=>$value2)
		{
			$ids6[] = $value2['id'];
			$u++;
		}
		$arr_all = array_merge($arr_all, $ids6);
		$idss123 = $ids6;
	}
	return $arr_all;
}

function get_cat_lvl($cat_id)
{
	$r = $cat_id;
	$lvl = 0;
	while($r != 0)
	{
		$lvl++;
		$r = get_cat_pid($r);
	}
	return $lvl;
}

	function gettreeoptions($cid,$cpid,$lvl,$pid, $cats = array(), $block = 1){
		global $db, $sets;
		$rtn = "";
		$str = strrpt('- ',$lvl);
		$site_ar = array();
		$rows = $db->get_rows("SELECT id, title, pid FROM ".TABLE_SITEMENU." WHERE deleted = 0 ORDER BY position ASC, id ASC");
		foreach($rows as $id=>$value){
			$site_ar[$value['pid']][$value['id']] = $value['title'];
		}
		$rtn .= gettreeoptionsd($site_ar, $pid, $lvl, $cpid, $cats, $cid);
		return $rtn;
	}

	function gettreeoptionsd(&$site_ar, $pid, $lvl, $cpid, $cats = array(), $cid){
		global $sets;
		$rtn = '';
		$str = strrpt('- ',$lvl);
		$lvl++;
		if(!empty($site_ar[$pid]) && count($site_ar[$pid]) > 0){
			foreach($site_ar[$pid] as $id=>$value){
				if(!in_array($id, $cats)){
					if($cpid == $id) $sel = "selected";
					else $sel = "";
					if($cid != $id){
						$rtn .= '<option value="'.$id.'" '.$sel.'>'.$str.$value.'</option>';
						$rtn .= gettreeoptionsd($site_ar, $id, $lvl, $cpid, $cats, $cid);
					}
				}
			}
		}
		return $rtn;
	}

function get_sub_cats($cat_id)
{
	global $db;
	$cat_line = (is_array($cat_id)) ? implode(',', $cat_id) : $cat_id;
	$cat = $db->get_rows("SELECT * FROM ".TABLE_SITEMENU." WHERE pid IN(".$cat_line.") && deleted = 0");
	$new_c = array();
	foreach($cat as $cat)
	{
		$new_c[] = $cat['id'];
	}
	return $new_c;
}

function location_replace($id, $place)
{
	global $global_place;
	
	$r = (!empty($id)) ? '&pID='.$id : '';
	header("Location:include.php?place=".$global_place.$r);
	exit;
}

function ajax_copy_window($ids, $id=0)
{
	$btn =  button('Копировать','copy_to(\''.implode(',',$ids).'\',\''.$id.'\')');
	$c = '
	<div style="padding:20px;">
	<div>Выберите раздел в который необходимо копировать разделы:</div>
	<div style="padding:5px 0px">
	 <select id="curent_cat" name="incatsc" style="width: 150px">
	  <option value="0">Корень</option>
	 '.gettreeoptions(-1,$id,0,0, $ids).'
	 </select>
	 <span style="margin:0px 10px 0px 10px">Количество копий</span><input style="width:50px;" value="1" type="text" id="kolvo" />
	</div>
	<div style="padding:5px 0px">
	'.$btn.'
	</div>
	</div>';
	return $c;
}

function ajax_move_window($ids, $id=0)
{
	$btn =  button('Переместить','move_to(\''.implode(',',$ids).'\',\''.$id.'\')');
	$c = '
	<div style="padding:20px;">
	<div>Выберите раздел в который необходимо переместить разделы:</div>
	<div style="padding:5px 0px">
	 <select id="curent_cat" name="incatsc" style="width: 150px">
	  <option value="0">Корень</option>
	 '.gettreeoptions(-1,$id,0,0, $ids).'
	 </select>
	 <span style="margin:0px 10px 0px 10px">Количество копий</span><input style="width:50px;" value="1" type="text" id="kolvo" />
	</div>
	<div style="padding:5px 0px">
	'.$btn.'
	</div>
	</div>';
	return $c;
}

function get_sub_cats_page($cat_id, $place)
{	
	global $sets, $db;
	$place='sitemenu';
	$module['html'] = '';

	$path_string = '<a class="path_link" href="" onclick="open_sub_cats(0);return false;">Корень</a>';
	if(!empty($cat_id)){
		$f = $cat_id;
		$path_a = Array();
		$path_a[] = $cat_id;
		while($f != 0){
			$b_rows = $db->get_rows("select pid from ".TABLE_SITEMENU." where `id` = ".quote_smart($f)." && deleted = 0");
			$path_a[] = $b_rows[0]['pid'];
			$f = $b_rows[0]['pid'];
		}
		$q = $db->get_rows("select id, title from ".TABLE_SITEMENU." where `id` IN (".implode(", ", $path_a).") && deleted = 0");
		foreach($q as $row){
			$path_string .= ' » <a class="path_link"  href="" onclick="open_sub_cats('.$row['id'].');return false;">'.$row['title'].'</a>';
		}
	}
	else{
		$cat_id = 0;
	}
	
	ob_start();

	?>
	<div class="news_pan">
	<?php
	echo button1('Копировать', 'javascript: if (confirm(\'При копировании категории скопируются и все подкатегории. Копировать?\')) { gotourl(\'include.php?place=sitemenu#Coping(\' + $("#curent_cat").val() + \')\'); }','id="btncop" disabled','copy');
	echo button1('Переместить', "javascript: if (confirm('При перемещени категории переместятся и все подкатегории. Переместить?')) { gotourl('include.php?place=sitemenu#Moving(' + $('#curent_cat').val() + ')'); }",'id="btnmv" disabled','move');
    echo button1('Удалить', "javascript: if (confirm('Вы действительно хотите удалить выделенные cтраницы?')) { Deleting();}",'id="btndel" disabled','delete');
    ?>
</div>
	<div class="adv_news_pan">
	 <?php echo button1('Новый раздел', "javascript:gotourl('include.php?place=sitemenu#add_this_page(' + $('#curent_cat').val() + ')')",'','add')?>
	</div>
	<div class="clear"></div>
	<div class="search_pan">
	   <form method="post" action="include.php?place=sitemenu">
		Поиск: &nbsp;<input type="text" value="" name="search_string">
	   </form>
	</div><div class="clear"></div>
	<div class="curr_dir_pan">
	 <select onchange="gotourl('include.php?place=sitemenu#open_sub_cats(' + this.value + ')')" id="curent_cat" name="incatsc" style="width: 150px">
	  <option value="0">Корень</option>
	 <?php
	 echo gettreeoptions(-1,$cat_id,0,0);
	 ?>
	 </select>
	<?=$path_string?></div>
	<script type="text/javascript">
	$(document).ready(function () {
	$("#markp").click(function(){
	$("input[name^='box']").prop('checked' , $(this).prop('checked'));
	btn_state();});});
	</script>
	 <table class="main_no_height" id="myTable"  onclick="mtclx(arguments[0])">
	 <thead>
	  <tr>
	   <th class="news_header" style="width:30px;text-align: center;"><input type="checkbox" class="check" id="markp"></th>
	   <th class="news_header {sorter: 'numeric'}" style="width:38px;">№</th>
	   <th class="news_header" style="width:46px;text-align: center;"></th>
	   <th class="news_header" style="text-align: left; padding-left:10px;">дерево разделов</th>
 	   <th class="news_header" style="width:65px;text-align: center;">ссылка</th>
	   <th class="news_header" style="width:75px;text-align: center;">функции</th>
	 </tr>
	</thead> 
	<tbody> 
	<?php echo partstree_user_preloading(0,$cat_id,0)?>
	</tbody>
	</table><br /><br />
	Щелчек по разделу -> вход в раздел, Shift+Щелчек -> редактирование раздела.
	<script type="text/javascript">
	//$(document).ready(function() 
   // { 
   //     $("#myTable").tablesorter({headers:{0:{sorter: false},5:{sorter: false},1:{sorter: 'numeric'}}});
  //  }
//); 
   </script>
	<?php
	$module['html'] .= ob_get_contents();
	ob_end_clean();
	return $module['html'];
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

function get_cat_pid($id)
{
	global $db;
	$cat = $db->get(TABLE_SITEMENU, array('id'=>$id));
	return $cat['0']['pid'];
}

function cat_is_folder($id)
{
	global $db;
	$cat = $db->get(TABLE_SITEMENU, array('pid'=>$id));
	if(count($cat) > 0) return 1;
	return 0;
}

/*function sub_menu($id, $lvl)
{
	global $db;
	$cats = $db->get_rows("SELECT * FROM ".TABLE_SITEMENU." WHERE pid = ".$id." ORDER BY position ASC, id ASC");

	$sm = '';
	
	$lvl++;
	foreach($cats as $cat)
	{
		$title = $cat['title'];
		if(strlen($cat['title']) > 20-$lvl)
		{
			$title = substr($cat['title'],0,20-$lvl).'...';
		}
		
		$cnt = $db->get_rows("SELECT count(id) as cnt from ".TABLE_SITEMENU." WHERE pid = ".$cat['id']."");
		
		$is_folder = ($cnt['0']['cnt'] > 0) ? '1' : '0';
		
		$sm .= get_sm_line(array('id'=>$cat['id'], 'pid'=>$cat['pid'], 'visible'=>$cat['visible'], 'title'=>$title, 'lvl'=>$lvl, 'is_folder'=>$is_folder, 'sort'=>$cat['position']));
	}
	
	return $sm;
}
*/

function ajax_edit_form($id, $pid=-1, $passwrd=0)
{
	global $db, $sets;
	require_once INC_DIR."site.class.php";
	$cntrs = $db->get_rows("SELECT id FROM ".TABLE_SITEMENU." WHERE deleted = 0");
	$module['html'] = '';
	$site = Site::gI();
	$sets = $site->GetSettings();
	$ebox = $site->GetEditBoxes();
	if($id >= 0){
		$part = $db->get(TABLE_SITEMENU,$id);
	}
	else $part = array();
	$html = (!empty($part['html'])) ? $part['html'] : '';
	$title = (!empty($part['title'])) ? $part['title'] : '';
	$pagetitle = (!empty($part['pagetitle'])) ? $part['pagetitle'] : '';
	$vlink = (!empty($part['vlink'])) ? $part['vlink'] : '';
	$metadesc = (!empty($part['metadesc'])) ? $part['metadesc'] : '';
	$metakey = (!empty($part['metakey'])) ? $part['metakey'] : '';
	$pass = (!empty($part['pass'])) ? $part['pass'] : '';
	$reg = (!empty($part['reg'])) ? $part['reg'] : '';
	$enabled = (!empty($part['enabled'])) ? $part['enabled'] : '0';
	$print = (!empty($part['print'])) ? $part['print'] : '';
	$comm = (!empty($part['comm'])) ? $part['comm'] : '';
	$visible = !((!empty($part['visible'])) ? $part['visible'] : '0');
	$vistop = (!empty($part['vistop'])) ? $part['vistop'] : '0';
	$hideleft = (!empty($part['hideleft'])) ? $part['hideleft'] : '0';
	$plink = (!empty($part['link'])) ? $part['link'] : '';
	if($pid > - 1){
		$part['pid'] = $pid;
	}
	/*if (isset($part['pass']) && trim($part['pass']) != '' && $part['pass'] != $passwrd  && @$_SESSION['auth']['type'] != 'admin')
	{
	$module['html'] = '<center>
	<div>Раздел защищен паролем.</div><br>
	<form name="forma" method="post" target="ajax_frame" action="include.php?place=sitemenu&amp;action=sub&amp;eID='.$id.'">
	<div><input type="password" name="passw" value=""></div><br>
	<div><input onclick="paste_frame(); forma.submit(); " type="submit" class="button1" name="go" value="OK"></div>
	</form>
	</center>';
	}
	else
	{*/
	unset($_SESSION['ok_pass']);
	//$module['html'] .= tinymce();
	ob_start();
	?>
	<div>
	<form onsubmit="scroll_top()" name="forma" target="ajax_frame" action="include.php?place=sitemenu&action=sub&eID=<?php echo $id?>" method="post" enctype="multipart/form-data">
		<!--Пароль:<br>
		<input id="pass" style="width:500px;" type="text" value="<?php echo htmlspecialchars($pass,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="pass"><br><br>-->
	 <table width="100%">
	  <tr>
	  <td class="text rgt">Название раздела:</td>
	  <td><input id="title" style="width:700px;" type="text" value="<?php echo htmlspecialchars($title,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="title"></td></tr>
	  <tr><td class="text rgt">Title страницы:</td>
	  <td><input id="pagetitle" style="width:700px;" type="text" value="<?php echo htmlspecialchars($pagetitle,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="pagetitle"><a href="javascript:apply_in_Sub(<?=$id?>)">Применить ко всем подразделам</a></td></tr>
	  <tr><td class="text rgt">URL страницы:</td>
	  <td><input id="vlink" style="width:700px;" type="text" value="<?php echo htmlspecialchars($vlink,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="vlink"></td></tr>
		  
		<tr><td class="text rgt">МЕТА - Описание(meta_description):</td>
		<td><input id="metadesc" style="width:700px;" type="text" value="<?php echo htmlspecialchars($metadesc,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="metadesc"></td></tr>
		<tr><td class="text rgt">МЕТА - ключевые слова(meta_keywords):</td>
		<td><input id="metakey" style="width:700px;" type="text" value="<?php echo htmlspecialchars($metakey,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="metakey"></td></tr>
		<tr><td colspan="2"><label><input id="enabled" type="checkbox" style="border: none" value="<?php echo $enabled?>" name="enabled" <?=($enabled ? 'checked' : '')?>>Включен</label>&nbsp;&nbsp;<label><input id="visible" type="checkbox" style="border: none" value="<?=$visible?>" name="visible" <?=($visible ? 'checked' : '')?>>Левое меню</label>&nbsp;&nbsp;<label><input id="vistop" type="checkbox" style="border: none" value="<?=$vistop?>" name="vistop" <?=($vistop ? 'checked' : '')?>>Верхнее меню</label>&nbsp;&nbsp;<label><input id="hideleft" type="checkbox" style="border: none" value="<?=$hideleft?>" name="hideleft" <?=($hideleft ? 'checked' : '')?>>Скрыть левое меню</label>&nbsp;&nbsp;
		
		<?if(!empty($sets['mod_hide_text'])) { ?>
		<label><input id="reg" type="checkbox" style="border: none" value="1" name="reg" <?php echo ($reg ? 'checked' : '')?>>Требует регистрации</label>&nbsp;&nbsp;
		<?}?>
		<?if(!empty($sets['allow_print'])) { ?>
		<label><input id="print" type="checkbox" style="border: none" value="1" name="print" <?php echo ($print ? 'checked' : '')?>>Печать</label>&nbsp;&nbsp;
		<? } ?>
		<?if(!empty($sets['allow_comments'])) { ?>
		<label><input id="comm" type="checkbox" style="border: none" value="1" name="comm" <?php echo ($comm ? 'checked' : '')?>>Комментарии:</label>&nbsp;&nbsp;
		<? } ?>
		<label><input id="main_id" type="checkbox" style="border: none" value="<?php echo $id?>" name="main_id" <?php echo ($id==$ebox['id_main'] ? 'checked' : '')?>>Сделать главной</label></td></tr>
		<tr><td class="text rgt">Ссылка:</td>
		<td><input id="link" style="width:500px;" type="text" value="<?php echo htmlspecialchars($plink,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="link"></td></tr>
		<tr><td class="text rgt">Расположение:</td>
		<td><select id="pid" name="pid">
		<option value="0">Корень</option>
		<?php echo gettreeoptions($id,(isSet($part['pid']) ? $part['pid'] : -1),0,0)?>
		</select>
		</td></tr>
	  
	 
	  <tr valign="top">
	   <td style="padding:10px" colspan="2">
		Редактирование раздела:
		<textarea name="html" rows="30" cols="80" id="editor1" style="width: 80%"><?php echo htmlspecialchars(HTML::del_mso_code($html),ENT_COMPAT | ENT_XHTML,'utf-8')?></textarea><br>
		<script>tinyMCEInit('editor1');</script>
	   </td>
	  </tr>
	<?
		if($sets['allow_comments'])
		{
			$msgs = $db->get_rows("SELECT * FROM ".TABLE_COMMENTS." WHERE cat_id=".intval($id)." && module = 1 ORDER BY id DESC");
			if(count($msgs) > 0)
			{
	?>  
	  <tr><td colspan="2">
	<?
			if(count($msgs) > 0)
			{
				echo '<p class="inf_title">Комментарии:</p>';

				foreach($msgs as $msg)
				{
					echo '
					<div id="prd_comm_'.$msg['id'].'" style="padding:5px 20px; border:1px solid #ccc;" class="o_box">
					 <div style="padding:2px 0px; font-weight:bold;" class="o_name">'.$msg['name'].' (<a href="javascript:del_comments('.$msg['id'].')">Удалить</a>)</div>
					 <div class="o_msg">'.$msg['msg'].'</div>
					</div>';
				}
			}
			?>
	   </td>
	  </tr>
	 <?
			}
		}
	?> 
	 </table>
	<?
		
	/*	$rows = $db->get_rows("SELECT l as login, id FROM ".TABLE_MNG."");
		$rows2 = $db->get_rows("SELECT user_id FROM ".TABLE_U_P." WHERE page_id = ".quote_smart($id)."");
		
		$cu = array();
		foreach($rows2 as $idt => $valt)
		{
			$cu[] = $valt['user_id'];
		}
		
		if(count($rows) > 0 && !empty($sets['mod_hide_text']) && USER_SITEMENU)
		{
	?> 
	  <tr valign="top">
	   <td style="padding:10px">
	   <p><b>Пользователи которые будут иметь доступ к данному разделу:</b></p>
		<?
			foreach($rows as $id => $val)
			{
				$chk = (in_array($val['id'], $cu)) ? ' checked="checked"' : '';
				?>
				
				<input <?=$chk?> value="1" name="user[<?=$val['id']?>]" type="checkbox" /> - <?=$val['login']?><br>
				
				<?
			}
		?>
	   </td>
	  </tr>
	  <?
		}*/
	  ?>
	   <?php if($id >= 0) {?>
		<?php echo button1('Сохранить изменения','paste_frame(); forma.submit(); scroll_top();','','save')?>
		<?php //echo button('Применить','save_html()')?>
		<?php } else { ?>
		<?php echo button1('Создать раздел','paste_frame(); forma.submit(); scroll_top();','','save')?>
		<?php } ?>
		<?php echo button1('Отменить','gotourl('."'include.php?place=sitemenu#open_sub_cats(' + $('#pid').val() + ')'".');','','cancel')?>
	</form>
		</div>
		<script>	$(document).ready(function () {
    $('input').iCheck({
	    checkboxClass: 'icheckbox_flat-green',
	    radioClass: 'iradio_flat-green'});	
	});
</script>
	<?php
		/*}*/
		$module['html'] .= ob_get_contents();
		
		@ob_end_clean();
	return $module['html'];
}

function ajax_view_form($id)
{
	global $db;
	require_once INC_DIR."site.class.php";
	$site = Site::gI($db);
	$ebox = $site->GetEditBoxes();
	$link = ($id == $ebox['id_main']) ? '/' : SITE_URL.'site/'.$id;
	$r = '<'.'if'.'rame'.' src="'.$link.'" style="height:700px; width:980px;" id="ifrm"></'.'if'.'rame'.'>';
	return $r;
}

function CopyPartFull($id,$whr)
{
	global $db;
	
	$res = $db->get(TABLE_SITEMENU,array('id'=>$id));
	$res = $res['0'];

	unset($res['id']);
	$res['pid'] = $whr;
	
	$db->insert(TABLE_SITEMENU,$res);
		
	if($id != $whr)
	{
		$last_id = $db->insert_id();
		$q = $db->get_rows("select `id` from ".TABLE_SITEMENU." where `pid` = '".$id."'");
		foreach ($q as $res)		{
			CopyPartFull($res['id'],$last_id);
		}
	}
}

function partstree_user_preloading($lvl,$pid,$pii)
{
	global $db, $global_place, $rowscnt, $arrs, $sets;
	$rtn = "";
	$rows = array();
	$str = strrpt('- ',$lvl);
	
	if(!empty($_POST))
	{
		$rows = $db->get_rows("select * from ".TABLE_SITEMENU." where title LIKE ".quote_smart('%'.$_POST['search_string'].'%')." && deleted = 0 order by position ASC, id ASC");
	}
	else
	{
		$rows = $db->get_rows("select * from ".TABLE_SITEMENU." where pid = '".$pid."' && deleted = 0 order by visible asc,enabled asc,position ASC, id ASC");
		$rows2 = $db->get_rows("select * from ".TABLE_SITEMENU." where id = '".$pid."'");
	}
	


	$rowscnt++;
	$i=0;

		ob_start();
	if($pid != 0)	
	{
?>
		<tr id="cat_row_0">
		 <td class="news_td">&nbsp;</td>
		 <td class="news_td">&nbsp;</td>
		 <td class="news_td">&nbsp;</td>
	 	 <td class="news_td" style="text-align: left;">
			  <a class="dir_icon" style="margin-right:3px;" href="javascript:gotourl('include.php?place=sitemenu#open_sub_cats(<?=$rows2['0']['pid']?>)')">Выше..</a>
		 </td>
		 <td class="news_td" style="text-align:center;"><?php echo 'site/'.$pid?></td>
		 <td class="news_td" style="text-align:center;">
		  <div class="img iop" title="Редактировать" onclick="gotourl('include.php?place=sitemenu#update_html(<?php echo $pid?>)')"></div><div class="img idel" title="Удалить" onclick="javascript:del_this_page(<?php echo $pid?>);"></div>
		 </td>
		</tr>
<?
}

	if(count($rows) > 0) 
	{
		foreach ($rows as $k=>$res)
		{
			
			?>
			<tr id="cat_row_<?php echo $res['id']?>">
			 <td class="news_td" style="text-align:center;"><?=(($res['id']!=1)?'<input type="checkbox" class="check" name="box['.$res['id'].']">':'&nbsp;')?></td>
			 <td class="news_td" onclick="ad_f(<?php echo $res['id']?>, this)" style="text-align:center; cursor:pointer;"><?php echo $res['position']?></td>
			 <td class="news_td parts_vi"><div class="enb<?=(($res['enabled']==1)?'':' edis')?>"></div><div class="vsb<?=(($res['visible']==0)?'':' eunv')?>"></div></td>
			 <td class="news_td" style="text-align: left;"><div class="dir_icon"><?php echo htmlspecialchars($res['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></div></td>
			 <td class="news_td" style="text-align:center;"><span style="display:none;"><?php echo $res['id']?></span><?php echo 'site/'.$res['id']?></td>
			 <td class="news_td" style="text-align:center;">
			 <div class="img iop"></div><div class="img<?=(($res['id']!=1)?' idel':'')?>"></div>
			 </td>
			</tr>
	<?
		}
	}
	
	$rtn .= ob_get_contents();
	ob_end_clean();
	return $rtn;
}

