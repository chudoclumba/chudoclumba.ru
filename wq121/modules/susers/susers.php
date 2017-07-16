<?php
if(!empty($_GET['action']) && $_GET['action'] == 'saveuser')
{
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
        if (!empty($_POST['k_name']))
        {
			$data = array('name' => $_POST['k_name'],'role' => $_POST['k_role']);
			$ret=$db->update(TABLE_SUSUS, array('id' => $_POST['k_id']), $data);
		}
	}
	if (!($ret===false)) echo '1'; else echo '0';
	exit;
}  
elseif (!empty($_GET['action']) && $_GET['action'] == 'savenewusersus')
{
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
        if (!empty($_POST['k_name']) && !empty($_POST['k_pass']))
        {
			$data = array('name' => $_POST['k_name'],'role' => $_POST['k_role'],
							'pass'=>crypt($_POST['k_pass']));
		}
		$ret=$db->insert(TABLE_SUSUS,$data);
	}
	if ($ret>0) echo '1'; else echo '0';
	exit;
		
}

$module['path'] = 'Пользователи СУС';

	$btns = array(
		'Пользователи СУС'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=susers\'')
	);

	require_once('susers_list.php');
