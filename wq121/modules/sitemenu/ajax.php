<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

include_once "../../../includes/kanfih.php";
include_once "../../vars.php";
include_once SA_DIR."inc/susfunction.php";
include_once INC_DIR."dbconnect.php";
include_once INC_DIR."functions.php";
include_once "functions.php";
include_once INC_DIR."site.class.php";

$site = Site::gI();
$sets = $site->GetSettings();
$ebox = $site->GetEditBoxes();


function updates_title($cats = array(), $title)
{
	global $db;
	$rows = $db->get_rows("SELECT * FROM ".TABLE_SITEMENU." WHERE pid IN (".implode(',', $cats).")");
	$catn = array();
	foreach($rows as $id=>$val)
	{
		$rows = $db->get_rows("UPDATE ".TABLE_SITEMENU." SET  pagetitle = ".quote_smart($title.', '.$val['title'])." WHERE id = ".$val['id']."");
		$catn[] = $val['id'];
	}

	if(count($catn) > 0) updates_title($catn, $title);
}

if (isset($_GET['value'])) $value = decode_str($_GET['value']);

//$value = iconv("UCS-2BE", "windows-1251", $_GET['value']);

$buff = '';

if($_GET['act'] == 'save_title')
{
	$db->exec("UPDATE ".TABLE_SITEMENU." SET title = '".$value."' where `id` = '".$_GET['id']."'");
	$options = gettreeoptions(0,0);

	$value = "
	var text='".$value."';
	var test='<option value=\"0\">Корень</option>".$options."';
	";

	$buff = $value;
}
elseif ($_GET['act'] == 'set_pos')
{
	$db->exec("UPDATE ".TABLE_SITEMENU." SET position = '".$_GET['value']."' where `id` = '".$_GET['id']."'");
	$buff = "1";
}
elseif($_GET['act'] == 'set_params')
{
	$cnt=$db->count(TABLE_SETTINGS,array('id'=>$_GET['param_name']));
	if ($cnt>0){
		$param = $db->get(TABLE_SETTINGS, array('id'=>$_GET['param_name']));
		$param = $param['0'];
		$param['value'] = intval(!$param['value']);
		$db->exec("update ".TABLE_SETTINGS." set `value` = '".$param['value']."' where `id` = '".$param['id']."'");
	} else {
		$db->exec("INSERT INTO ".TABLE_SETTINGS." (`id`, `value`) VALUES ('".$_GET['param_name']."','0')");
	}

	if($_GET['param_name'] == 'mod_change_price')
	{
		if($param['value'] == 1)
		{
			$db->update(TABLE_PRD_P, array('param_descr' => 'tsena'), array('param_type' => 'text'));
			$db->exec("ALTER TABLE ".TABLE_PRODUCTS." CHANGE `tsena` `tsena` TEXT NULL DEFAULT NULL");
		}
		else
		{
			$db->update(TABLE_PRD_P, array('param_descr' => 'tsena'), array('param_type' => 'decimal(15,2)'));
			$db->exec("ALTER TABLE ".TABLE_PRODUCTS." CHANGE `tsena` `tsena` DECIMAL( 15, 2 ) NULL DEFAULT NULL");
		}
	}
	$param = $db->get(TABLE_SETTINGS, array('id'=>$_GET['param_name']));
	$param = $param['0'];
	$buff = $param['value'];
}
elseif($_GET['act'] == 'del_comments')
{
	$db->exec("DELETE FROM ".TABLE_COMMENTS." WHERE id=".$_GET['id']." && module = 1");
}
elseif ($_GET['act'] == 'part_on')
{
	$row = $db->get(TABLE_SITEMENU,$_GET['id']);
	$vl = ($row['enabled'] == 1) ? 0 : 1;
	$db->exec("UPDATE ".TABLE_SITEMENU." SET enabled = '".$vl."' where `id` = '".$_GET['id']."'");
	$text = (!$row['enabled']==1)?'Включен':'Выключен';
	$buff = $text;
}
elseif ($_GET['act'] == 'update_titles')
{
	$title = String_RusCharsDeCode($_GET['title']);
	updates_title(array($_GET['id']),$title);
}
elseif ($_GET['act'] == 'part_show')
{
	$row = $db->get(TABLE_SITEMENU,$_GET['id']);
	$param = ($row['visible']=='0')?'1':'0';
	$db->exec("UPDATE ".TABLE_SITEMENU." SET visible = '".$param."' where `id` = '".$_GET['id']."'");
	$text = ($param=='0')?'Видимый':'Скрытый';
	$buff = $text;
}
elseif ($_GET['act'] == 'update_html')
{

	$_GET['cat'] = (isset($_GET['cat'])) ? $_GET['cat'] : '-1';
	$_GET['passwrd'] = !empty($_GET['passwrd']) ? $_GET['passwrd'] : '';
	$content =  ajax_edit_form($_GET['id'], $_GET['cat'], $_GET['passwrd']);
	$btns = array(
		'Редактирование'=>array('id'=>1,'href'=>"javascript:gotourl('include.php?place=sitemenu#update_html(".$_GET['id'].")')"),
		'Просмотр'=>array('id'=>2,'href'=>"javascript:gotourl('include.php?place=sitemenu#view_this_page(".$_GET['id'].")')")
	);

	$buff = get_module($btns, 1, $content, 'Редактирование раздела');

}
elseif ($_GET['act'] == 'get_cats')
{
	$content =  get_sub_cats_page($_GET['id'], 'sitemenu');
	$btns = array(
		'Сайт'=>array('id'=>1,'href'=>''),
	);
	$buff = get_module($btns, 1, $content, 'Редактирование раздела');

}
elseif ($_GET['act'] == 'view_page')
{

	$content =  ajax_view_form($_GET['id']);
	$btns = array(
		'Редактирование'=>array('id'=>1,'href'=>"javascript:gotourl('include.php?place=sitemenu#update_html(".$_GET['id'].")')"),
		'Просмотр'=>array('id'=>2,'href'=>"javascript:gotourl('include.php?place=sitemenu#view_this_page(".$_GET['id'].")')")
	);
	$buff = get_module($btns, 2, $content, 'Редактирование раздела');
}
elseif($_GET['act'] == 'load_cats')
{
	$buff = sub_menu($_GET['id'], $_GET['lvl']);
}
elseif($_GET['act'] == 'del_page')
{
	$buff = '0';
	if(!in_array($_GET['id'], $id_undel))
	{
		$d_arr = get_all_sub_cats($_GET['id']);
		$d_arr[] = $_GET['id'];

		$q = "UPDATE ".TABLE_SITEMENU." SET deleted = 1 WHERE id IN (".implode(',',$d_arr).")";
		$buff=$db->exec($q);
	}

}
elseif($_GET['act'] == 'delete_cats')
{
	$ids = array_del_empty(explode('_',$_GET['ids']));

	foreach($ids as $id=>$value)
	{
		if(in_array($value, $id_undel))
		{
			unset($ids[$id]);
		}
	}

	$d_arr = get_all_sub_cats(array_del_empty($ids));
	$d_arr = array_merge($d_arr, $ids);
	$buff = '0';
	if(count($d_arr) > 0)	{
		$q = "UPDATE ".TABLE_SITEMENU." SET deleted = 1 WHERE id IN (".implode(',',$d_arr).")";
		$buff=($db->exec($q)>0)?1:0;
	}
}
elseif($_GET['act'] == 'db_insert')
{
	$rows = $db->get(TABLE_SITEMENU, array('id'=>$_GET['id']));
	$cont = $rows['0'];

	$buff = get_sm_line(array('id'=>$cont['id'], 'pid'=>$cont['pid'], 'visible'=>$cont['tp'],'title'=>$cont['title'],'lvl'=>get_cat_lvl($cont['id']), 'is_folder'=>cat_is_folder($cont['id'])));
}
elseif($_GET['act'] == 'move_after')
{
	$rows = $db->get(TABLE_SITEMENU, array('id'=>$_GET['after']));

	if(count($rows) > 0)
	{
		$rows = $db->get_rows("SELECT * FROM ".TABLE_SITEMENU." WHERE pid = ".$rows['0']['pid']." ORDER BY position asc, id asc");

		$f = 0;
		$g = 0;
		foreach($rows as $row)
		{
			$f++;
			$db->update(TABLE_SITEMENU, array('id'=>$row['id']),array('position'=>$f));
			if($row['id'] == $_GET['after'])
			{
				$f++;
				$g=$f;

			}
		}
		$db->update(TABLE_SITEMENU,array('id'=>$_GET['id']), array('position'=>$g,'pid'=>$rows['0']['pid']));

	}
	$buff = sub_menu(0, 0);
}
elseif($_GET['act'] == 'move_in')
{
	$db->update(TABLE_SITEMENU,array('id'=>$_GET['id']),array('pid'=>$_GET['in']));
	$buff = sub_menu(0, 0);
}
elseif($_GET['act'] == 'copy_cats')
{
	$ids = array_del_empty(explode('_',$_GET['ids']));
	$buff = ajax_copy_window($ids, $_GET['id']);
}
elseif($_GET['act'] == 'move_cats')
{
	$ids = array_del_empty(explode('_',$_GET['ids']));
	$buff = ajax_move_window($ids, $_GET['id']);
}
elseif($_GET['act'] == 'copy_to')
{
	$ids = array_del_empty(explode(',',$_GET['ids']));
	for($i=0;$i<$_GET['kolvo'];$i++)
	{
		foreach($ids as $id)
		{
			CopyPartFull($id, $_GET['id']);
		}
	}
	$buff = get_sub_cats_page($_GET['id'], 'sitemenu');
}
elseif($_GET['act'] == 'move_to')
{
	$ids = array_del_empty(explode(',',$_GET['ids']));
	foreach($ids as $id)
	{
		$db->update(TABLE_SITEMENU, array('id'=>$id), array('pid'=>$_GET['id']));
	}
	$buff = get_sub_cats_page($_GET['id'], 'sitemenu');
}
echo $buff;