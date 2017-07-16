<?php

$catprelink = $prelink."&action=catalog";
$act = $prelink;
$saves = array('user_login', 'count_pages', 'time_hosting', 'time_arenda');
$saves_pass = array('user_password');

foreach($saves as $s_id=>$s_val)
{
	if (isSet($_POST['save_'.$s_val.'']))
	{
		$db->exec("update ".TABLE_SETTINGS." set `value` = '".$_POST[$s_val]."' where `id` = '".$s_val."' and `part` = '".$global_place."'");
		header("Location: ".$prelink);
	}
}

foreach($saves_pass as $s_id=>$s_val)
{
	if (isSet($_POST['save_'.$s_val.'']))
	{
		$db->exec("update ".TABLE_SETTINGS." set `value` = '".md5($_POST[$s_val])."' where `id` = '".$s_val."' and `part` = '".$global_place."'");
		header("Location: ".$prelink);
	}
}

$module['html'] .= '
<br>
<form action="include.php?place=options" method="post">
<table width="100%" cellpadding=0 cellspacing=0 border=0 class="settings">
';

$module['html'] .= GetOnOff('Подтверждение из СУ', 'mod_reg_req');
$module['html'] .= GetOpen('Выгрузка пользователей в файл формата .csv','','',button('Выгрузить CSV', "save_users()"));
$module['html'] .= GetOpen('Обновление пользователей из файла формата .csv','','',button('Загрузить CSV', "load_users()"));


$module['html'] .= '
</table>
</form>
	<div id="status" style="padding:10px 40px;">
	</div>
<script>
function save_users()
{
	$.ajax({
		url: \'modules/ishop/ajax.php?act=save_userscsv\',
		cache: false,
		success: function(html){
			$(\'#status\').html(html);
		}
	});
}
function load_users()
{
	$.ajax({
		url: \'modules/ishop/ajax.php?act=load_user_file&load_file=data/users.csv\',
		cache: false,
		success: function(html){
			$(\'#status\').html(html);
		}
	});
}
</script>

	
';