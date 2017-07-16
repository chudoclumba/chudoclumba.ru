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

function gettreeoptions($cid,$cpid,$lvl,$pid, $cats = array(), $block = 1)
{
	global $db, $sets;
	$rtn = "";
	if($sets['allow_subcats'] == 1 || $block == 0)
	{
		$str = strrpt('- ',$lvl);
		
		$site_ar = array();

		$rows = $db->get_rows("SELECT id, title, pid FROM ".TABLE_SITEMENU." WHERE deleted = 0 ORDER BY position ASC, id ASC");

		
		foreach($rows as $id=>$value)
		{
			$site_ar[$value['pid']][$value['id']] = $value['title'];
		}
		
		$rtn .= gettreeoptionsd($site_ar, $pid, $lvl, $cpid, $cats, $cid);
	}
	return $rtn;
}

function gettreeoptionsd(&$site_ar, $pid, $lvl, $cpid, $cats = array(), $cid)
{
	global $sets;
	$rtn = '';
	$str = strrpt('- ',$lvl);
	$lvl++;
	if(!empty($site_ar[$pid]) && count($site_ar[$pid]) > 0)
	{
		foreach ($site_ar[$pid] as $id=>$value)
		{
			if(!in_array($id, $cats))
			{
				if ($cpid == $id) $sel = "selected"; else $sel = "";
				if($cid != $id)
				{
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

function get_main_page($cat_id, $place)
{	
	global $sets, $db;
	
	$module['html'] = '';
	
	$path_string = '<a class="path_link" href="include.php?place='.$place.'">Корень</a>';
	if(!empty($cat_id))
	{
		$f = $cat_id;
		$path_a = Array();
		
		$path_a[] = $cat_id;
		
		while($f != 0)
		{
			$q = $db->query("select pid from ".TABLE_SITEMENU." where `id` = ".quote_smart($f)." && deleted = 0");
			$b_rows = $db->fetch_array($q);
			$path_a[] = $b_rows['pid'];
			$f = $b_rows['pid'];
		}
		

		$q = $db->query("select id, title from ".TABLE_SITEMENU." where `id` IN (".implode(", ", $path_a).") && deleted = 0");

		
		while($row = $db->fetch_array($q))
		{
			$path_string .= ' » <a class="path_link" href="include.php?place='.$place.'&pID='.$row['id'].'">'.$row['title'].'</a>';
		}
	}
	else
	{
		$cat_id = 0;
	}
	
	$tdo = $db->get_rows("SELECT * FROM ".TABLE_TDO);
	
	ob_start();

	?>
	<div class="news_pan">
	<?php
	if($sets['count_pages'] == '0')
	{
		echo button('Копировать', "javascript: if (confirm('При копировании категории скопируются и все подкатегории. Копировать?')) { gotourl('include.php?place=sitemenu#Coping(' + $('#curent_cat').val() + ')'); }");
		echo button('Переместить', "javascript: if (confirm('При перемещени категории переместятся и все подкатегории. Переместить?')) { gotourl('include.php?place=sitemenu#Moving(' + $('#curent_cat').val() + ')'); }");
	}

	 echo button('Удалить', "javascript: if (confirm('Вы действительно хотите удалить выделенные категории/товары?')) { Deleting();}")?>
	</div>
	<div class="adv_news_pan">
	 <?php if(($sets['count_pages'] != '0' && count($cntrs) < $sets['count_pages']) || $sets['count_pages'] == '0') echo button('Новый раздел', "javascript:gotourl('include.php?place=sitemenu#add_this_page(' + $('#curent_cat').val() + ')')")?>
	</div>
	<div class="clear"></div>
	<div class="search_pan">
	   <form method="post" action="include.php?place=sitemenu">
		Поиск: &nbsp;<input type="text" value="" name="search_string">
	   </form>
	</div>
	<div class="curr_dir_pan">
	 <select onchange="gotourl('include.php?place=dostopl#open_sub_cats(' + this.value + ')')" id="curent_cat" name="incatsc" style="width: 150px">
	 <?php foreach($tdo as $id=>$tdoval) {?>
	<option value="<?=$tdoval['id']?>"><?=$tdoval['name']?></option>
	 <?}?>
	 </select>
	</div>
	<form id="frmparts" action="include.php?place=<?php echo $place?>" method="post" style="margin:3px;" enctype="multipart/form-data">
	<script type="text/javascript">
$(document).ready(function () {
	$("#markp").click(function(){
		$("input[name^='box']").attr({'checked' : $(this).attr('checked') });
	});
});
</script>
	 <table class="main_no_height" id="myTable">
	 <thead>
	  <tr>
	   <th class="news_header" style="width:30px;text-align: center;"><input type="checkbox" class="check" id="markp"></th>
	   <th class="news_header" style="width:38px;text-align: center;">№</th>
	   <th class="news_header" style="text-align: left; padding-left:10px;">дерево разделов</th>
	   <th class="news_header" style="width:140px;text-align: center;">тип раздела</th>
 	   <th class="news_header" style="width:65px;text-align: center;">ссылка</th>
	   <th class="news_header" style="width:75px;text-align: center;">функции</th>
	 </tr>
	</thead> 
	<tbody> 
	<?php //echo partstree_user_preloading(0,$cat_id,0)?>
	</tbody>
	</table>
	<script type="text/javascript">
	$(document).ready(function() 
    { 
        $("#myTable").tablesorter({headers: {0: {sorter: false}, 3: {sorter: false}, 5: {sorter: false}}});
    }
); 
   </script>
	</form>
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

function sub_menu($id, $lvl)
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


function ajax_edit_form($id, $pid=-1, $passwrd=0)
{
	global $db, $sets;
	
	$cntrs = $db->get_rows("SELECT id FROM ".TABLE_SITEMENU." WHERE deleted = 0");
	
	if((($sets['count_pages'] != '0' && count($cntrs) < $sets['count_pages']) || $sets['count_pages'] == '0') || $id != -1)
	{
		$module['html'] = '';
		
		$site = Site::gI();
		$sets = $site->GetSettings();
		$ebox = $site->GetEditBoxes();

		if ($id >= 0)
		{
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
		$enabled = (!empty($part['enabled'])) ? $part['enabled'] : '';
		$print = (!empty($part['print'])) ? $part['print'] : '';
		$comm = (!empty($part['comm'])) ? $part['comm'] : '';
		$visible = (!empty($part['visible'])) ? $part['visible'] : '';
		$plink = (!empty($part['link'])) ? $part['link'] : '';
		
		if($pid > -1)
		{
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
	<form onsubmit="scroll_top()" name="forma" target="ajax_frame" action="include.php?place=sitemenu&amp;action=sub&amp;eID=<?php echo $id?>" method="post" enctype="multipart/form-data">
	 <table width="100%" cellpadding=0 cellspacing=0 border=0>
	  <tr valign="top">
	  <td class="text" style="padding-top: 10px; padding-left: 10px;">
		Название раздела: <br>
		<input id="title" style="width:500px;" type="text" value="<?php echo htmlspecialchars($title,ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="title"><br><br>
		Title страницы:<br>
		<input id="pagetitle" style="width:500px;" type="text" value="<?php echo htmlspecialchars($pagetitle,ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="pagetitle"><br>
		<a href="javascript:apply_in_Sub(<?=$id?>)">Применить ко всем подразделам</a>
		<br><br>
		URL страницы:<br>
		<input id="vlink" style="width:500px;" type="text" value="<?php echo htmlspecialchars($vlink,ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="vlink"><br><br>
		МЕТА - Описание(meta_description):<br>
		<input id="metadesc" style="width:500px;" type="text" value="<?php echo htmlspecialchars($metadesc,ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="metadesc"><br><br>
		МЕТА - ключевые слова(meta_keywords):<br>
		<input id="metakey" style="width:500px;" type="text" value="<?php echo htmlspecialchars($metakey,ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="metakey"><br><br>
		<!--Пароль:<br>
		<input id="pass" style="width:500px;" type="text" value="<?php echo htmlspecialchars($pass,ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="pass"><br><br>-->
		Отключен:&nbsp;<input id="enabled" type="checkbox" style="border: none" value="<?php echo $enabled?>" name="enabled" <?php echo ($enabled ? '' : 'checked')?>><br><br>
		<?if(!empty($sets['mod_hide_text'])) { ?>
		Требует регистрации:&nbsp;<input id="reg" type="checkbox" style="border: none" value="1" name="reg" <?php echo ($reg ? 'checked' : '')?>><br><br>
		<?}?>
		<?if(!empty($sets['allow_print'])) { ?>
		Печать:&nbsp;<input id="print" type="checkbox" style="border: none" value="1" name="print" <?php echo ($print ? 'checked' : '')?>><br><br>
		<? } ?>
		<?if(!empty($sets['allow_comments'])) { ?>
		Комментарии:&nbsp;<input id="comm" type="checkbox" style="border: none" value="1" name="comm" <?php echo ($comm ? 'checked' : '')?>><br><br>
		<? } ?>
		Сделать главной:&nbsp;<input id="main_id" type="checkbox" style="border: none" value="<?php echo $id?>" name="main_id" <?php echo ($id==$ebox['id_main'] ? 'checked' : '')?>><br><br>
		Ссылка:<br>
		<input id="link" style="width:500px;" type="text" value="<?php echo htmlspecialchars($plink,ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="link"><br><br>
		Расположение:<br>
		<select id="pid" name="pid">
		<option value="0">Корень</option>
		<?php echo gettreeoptions($id,(isSet($part['pid']) ? $part['pid'] : -1),0,0)?>
		</select>
		<select id="visible" style="width:150px" name="visible">
		 <option value="0" <?php echo (($visible == '0') ? 'selected' : '')?>>Видимый раздел</option>
		 <option value="1" <?php echo (($visible == '1') ? 'selected' : '')?>>Скрытый раздел</option>
		</select>
		<br><br>
		Редактирование раздела:
	   </td>
	  </tr>
	  <tr valign="top">
	   <td style="padding:10px">
		<textarea name="html" rows="15" cols="80" id="editor1" style="width: 80%"><?php echo htmlspecialchars(HTML::del_mso_code($html),ENT_COMPAT | ENT_XHTML,'cp1251')?></textarea><br>
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
	  <tr valign="top">
	   <td style="padding:10px">
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
	<?
		
		$rows = $db->get_rows("SELECT l as login, id FROM ".TABLE_MNG."");
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
		}
	  ?>
	  <tr valign="bottom">
	   <td align="left" style="padding:3px 0px 0px 11px">
	   <?php if($id >= 0) {?>
		<?php echo button('Сохранить изменения','paste_frame(); forma.submit(); scroll_top();')?>
		<?php //echo button('Применить','save_html()')?>
		<?php } else { ?>
		<?php echo button('Создать раздел','paste_frame(); forma.submit(); scroll_top();')?>
		<?php } ?>
		<?php echo button('Отменить','gotourl('."'include.php?place=sitemenu#open_sub_cats(' + $('#pid').val() + ')'".');')?>
	   </td>
	  </tr>
	 </table>
	</form>
	<?php
		/*}*/
		$module['html'] .= ob_get_contents();
		
		@ob_end_clean();
	}
	return $module['html'];
}

function ajax_view_form($id)
{
	global $db;
	$site = new Site($db);
	$ebox = $site->GetEditBoxes();
	$link = ($id == $ebox['id_main']) ? '/' : SITE_URL.'site/'.$id;
	$r = '<'.'if'.'rame'.' src="'.$link.'" style="height:100%; width:100%;"></'.'if'.'rame'.'>';
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

		$q = $db->query("select `id` from ".TABLE_SITEMENU." where `pid` = '".$id."'");
		while ($res = $db->fetch_array($q))
		{
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
		$rows = $db->get_rows("select * from ".TABLE_SITEMENU." where pid = '".$pid."' && deleted = 0 order by position ASC, id ASC");
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
	 	 <td class="news_td" style="text-align: left;">
<table class="cat_dir_t">
		 <tr>
		  <td><a style="margin-right:3px;" href="javascript:gotourl('include.php?place=sitemenu#open_sub_cats(<?=$rows2['0']['pid']?>)')"><img SRC="<?=TEMPLATE_URL?>/images/folder_icon4.jpg" alt="" border="0"></a></td>
		  <td><a class="cat_ttl" href="javascript:gotourl('include.php?place=sitemenu#open_sub_cats(<?=$rows2['0']['pid']?>)')">...</a></td>
		 </tr>
		</table>
		 </td>
		 <td class="news_td parts_vi">&nbsp;</td>
		 <td class="news_td" style="text-align:center;"><?php echo 'site/'.$pid?></td>
		 <td class="news_td" style="text-align:center;">
		  <a title="Редактировать" onclick="gotourl('include.php?place=sitemenu#update_html(<?php echo $pid?>)')" href="include.php?place=sitemenu#update_html(<?php echo $pid?>)"><?php echo edit_img()?></a>&nbsp;&nbsp;<a title="Удалить" href="javascript:del_this_page(<?php echo $pid?>);"><?php echo del_img()?></a>
		 </td>
		</tr>
<?
}

	if(count($rows) > 0) 
	{
		foreach ($rows as $k=>$res)
		{
			$q2 = $db->query("select `id` from ".TABLE_SITEMENU." where pid = '".$res['id']."' && deleted = 0");
			//============

			$dir_icon = ($sets['allow_subcats'] == 1) ? '<a class="dir_icon" style="margin-right:3px;" href="javascript:gotourl('."'include.php?place=sitemenu#open_sub_cats(".$res['id'].")'".')"></a>' : '';
			
			$dir = '<table class="cat_dir_t">
			 <tr>
			  <td>'.$dir_icon.'</td>
			  <td><a title="Редактировать раздел \''.htmlspecialchars($res['title'],ENT_COMPAT | ENT_XHTML,'cp1251').'\'" class="cat_ttl" href="javascript:gotourl('."'include.php?place=sitemenu#update_html(".$res['id'].")'".')">'.htmlspecialchars(substr2($res['title'],45),ENT_COMPAT | ENT_XHTML,'cp1251').'</a></td>
			 </tr>
			</table>';

			//==========
	  
			$visible = ($res['visible']=='1')?'<a id="partv_'.$res['id'].'" href="javascript:part_show(\''.$res['id'].'\')" class="part_off">Скрытый</a>':'<a id="partv_'.$res['id'].'" href="javascript:part_show(\''.$res['id'].'\')" class="part_on">Видимый</a>';
			$enable = ($res['enabled']==1)?'<a id="parte_'.$res['id'].'"  href="javascript:part_on(\''.$res['id'].'\')" class="part_on">Включен</a>':'<a id="parte_'.$res['id'].'"  href="javascript:part_on(\''.$res['id'].'\')" class="part_off">Выключен</a>';

			
			?>
			<tr id="cat_row_<?php echo $res['id']?>">
			 <td class="news_td" style="text-align:center;"><input type="checkbox" class="check" name="box[<?php echo $res['id']?>]"></td>
			 <td class="news_td" onclick="ad_f(<?php echo $res['id']?>, this)" style="text-align:center; cursor:pointer;"><?php echo $res['position']?></td>
			 <td class="news_td" style="text-align: left;"><span style="display:none"><?=htmlspecialchars(substr2($res['title'],4),ENT_COMPAT | ENT_XHTML,'cp1251')?></span><?php echo $dir?></td>
			 <td class="news_td parts_vi"><?php echo $visible?>/<?php echo $enable?></td>
			 <td class="news_td" style="text-align:center;"><span style="display:none;"><?php echo $res['id']?></span><?php echo 'site/'.$res['id']?></td>
			 <td class="news_td" style="text-align:center;">
			  <a title="Редактировать" onclick="gotourl('include.php?place=sitemenu#update_html(<?php echo $res['id']?>)')" href="include.php?place=sitemenu#update_html(<?php echo $res['id']?>)"><?php echo edit_img()?></a>&nbsp;&nbsp;<a title="Удалить" href="javascript:del_this_page(<?php echo $res['id']?>);"><?php echo del_img()?></a>
			 </td>
			</tr>
	<?
		}
	}
	
	$rtn .= ob_get_contents();
	ob_end_clean();
	return $rtn;
}

function MakePath($cid)
{
	global $db;
	$str = $cid;

	if ($cid == '0' || !isSet($cid)) return $str;

	$q = $db->query("select `pid` from ".TABLE_SITEMENU." where `id` = '".$cid."' && deleted = 0");
	list($pid) = $db->fetch_array($q);
	
	$str = MakePath($pid)."_".$str;
	return $str;
}