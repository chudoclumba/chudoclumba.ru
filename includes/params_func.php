<?php
function get_act_partr($gr_id)
{
	global $db;
	$q_p = "SELECT gr_pr FROM ".TABLE_PRD_GR." WHERE gr_id = '".$gr_id."'";
	$row = $db->fetch_array($db->query($q_p));
	$p_ar = explode(',',$row['gr_pr']);
	return $p_ar;
}

function get_act_partr_r($p_id)
{
	global $db;
	$q = "SELECT gr_id FROM ".TABLE_CATEGORIES." WHERE id = '".$p_id."'";
	$q_r = $db->fetch_array($db->query($q)); 
	$q_p = "SELECT gr_pr FROM ".TABLE_PRD_GR." WHERE gr_id = '".$q_r['gr_id']."'";
	$row = $db->fetch_array($db->query($q_p));
	$p_ar = explode(',',$row['gr_pr']);
	return $p_ar;
}

function set_act_partr($gr_id, $new_p)
{
	global $db;
	$q_p = "UPDATE ".TABLE_PRD_GR." SET gr_pr = '".$new_p."' WHERE gr_id = '".$gr_id."'";
	$row = $db->fetch_array($db->query($q_p));
	$p_ar = explode(',',$row['gr_pr']);
	return $p_ar;
}


?>