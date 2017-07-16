<?php
$module['scripts'] = MODULES_PATH.$global_place.'/sitemenu.js';

$r = (!empty($id)) ? '&pID='.$id : '';

if (!empty($_POST['title']))
{	
	$params = Array(
		'pid' => $_POST['pid'],
		'title' => $_POST['title'],
		'html' => $_POST['html'],
		'visible' => intval(!isSet($_POST['visible'])),
		'vistop' => intval(isSet($_POST['vistop'])),
		'hideleft' => intval(isSet($_POST['hideleft'])),
		'enabled' => intval(isSet($_POST['enabled'])),
		'reg' => intval(!empty($_POST['reg'])),
		'pagetitle' => $_POST['pagetitle'],
		'metadesc' => $_POST['metadesc'],
		'metakey' => $_POST['metakey'],
		'deleted' => 0,
		'link' => $_POST['link']
	); 
	
	$params['vlink'] = $_POST['vlink'];
	if(!empty($sets['allow_print'])) $params['print'] = intval(isSet($_POST['print']));
	if(!empty($sets['allow_comments'])) $params['comm'] = intval(isSet($_POST['comm']));
 
	if ($_GET['eID'] >= 0)
	{
		$page_id = $_GET['eID'];
		$db->update(TABLE_SITEMENU,$_GET['eID'],$params);
		if(!empty($_POST['main_id']))
		{
			$db->update(TABLE_EDITBOXES, array('id'=>'id_main'), array('text'=>$_POST['main_id']));
		}
	}    
	else 
	{
		$db->insert(TABLE_SITEMENU,$params);
		$page_id = $db->insert_id();
		
		if(!empty($_POST['main_id']))
		{
			$db->update(TABLE_EDITBOXES, array('id'=>'id_main'), array('text'=>$db->insert_id()));
		}
	}
	
	$db->delete(TABLE_U_P, array('page_id' => $page_id));
	if(!empty($_POST['user']) && count($_POST['user']) > 0)
	{
		foreach($_POST['user'] as $id=>$val)
		{
			if(!empty($_POST['user'][$id])) {
				$db->insert(TABLE_U_P, array('page_id' => $page_id, 'user_id' => $id));
			}
		}
	}
	
	
?>
<script>window.parent.return_g();</script>
<?php
	//header("Location:404.php");
	exit;
}

include MODULES_PATH.$global_place."/functions.php";

// redirects //
/*
$module['map'] = '<a onclick="show_hide_panel2(\'site_map\');" class="lm2 p0">Карта сайта</a>
<div class="lm_t p0">
<div style="display:none">
 <img alt="" src="'.TEMPLATE_URL.'images/sp_1.jpg" />
 <img alt="" src="'.TEMPLATE_URL.'images/sp2.jpg" />
 <img alt="" src="'.TEMPLATE_URL.'images/sp_3.jpg" />
</div>
<div class="site_map" id="site_map" '.((!empty($_COOKIE['hide_map'])) ? 'style="display:none"' : '').'>';

$module['map'] .= ''.get_sm_line(array('id'=>0, 'pid'=>-1, 'visible'=>-1,'title'=>'Корень','lvl'=>0, 'is_folder'=>1),0);
$module['map'] .= '<div id="hidden_cat0" class="hc" style="display: block;">'.sub_menu(0, 0).'</div></div>';
$module['map'] .= '</div>';
*/
$btns = array(
	'Сайт'=>array('id'=>1,'href'=>'')
);

$module['html'] .= get_sub_cats_page(7, 1);;