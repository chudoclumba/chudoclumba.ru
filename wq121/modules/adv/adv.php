<?php
//	$btns = array('Дополнительно'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=adv\''));

	ob_start();
?>
	<table width="80%" align="center" cellspacing="0" cellpadding="0" style="margin:10px;">
	 <tr>
		<th class="news_header lft">Дата</th>
		<th class="news_header lft">IP</th>
		<th class="news_header lft">Сессия</th>
		<th class="news_header lft">Пользователь</th>
	 </tr>

	<?php
	$ts = $db->get_rows("select * from ".TABLE_ONLINE." order by time desc");
	
	foreach($ts as $t)
	{?>
	 <tr>
	  <td class="news_td"><?php echo date('d:m:Y H:i:s',$t['time'])?></td>
	  <td class="news_td"><?php echo $t['ip']?></td>
	  <td class="news_td"><?php echo $t['sess_id']?></td>
	  <td class="news_td"><?php echo $t['user']?></td>

	 </tr>
<?	}	?>
	</table>

<?php

$module['html'] .= ob_get_contents();
$module['path']='Логи активности пользователей';
ob_end_clean();