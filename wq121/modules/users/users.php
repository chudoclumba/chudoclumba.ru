<?php
if(!empty($_GET['action']) && $_GET['action'] == 'saveuser')
{
	$dop_rows = $db->get_rows("SELECT * FROM ".TABLE_FEED." WHERE parent_id=1 && enabled = 1 ORDER BY sort ASC, id ASC");
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
		foreach($dop_rows as $id=>$val)
		{
			if($val['id'] != 3 && $val['id'] != 4 && $val['id'] != 16 && $val['id'] != 10)
			{
				if($val['type'] == 'checkbox')
				{
					$user_inf[$val['id']] = (!empty($_POST['p'.$val['id']])) ? '1' : '0';
				}
				else
				{
					$user_inf[$val['id']] =$_POST['p'.$val['id']];
					if ($val['id']==8 || $val['id']==9 || $val['id']==11) $user_inf[$val['id']] = mb_convert_case($user_inf[$val['id']],MB_CASE_TITLE,"utf-8");
				}
			}
		}
        if (!empty($_POST['k_mail']))
        {
			$data = array('sale' =>$_POST['k_sale'],'login' => $_POST['k_mail'],'pass' => $_POST['k_pass'],'email' => $_POST['k_mail'],'info' => serialize($user_inf));
		}
		else
		{
			$data = array('sale' =>$_POST['k_sale'],'info' => serialize($user_inf));
		}
		if (!isset($_POST['p21'])){
			$db->SetErrMsgOn(false);
			$db->delete(TABLE_PODPISKA, array('email' => $data['login']));
			$db->SetErrMsgOn();
		}
		if (isset($_POST['p21']) && !empty($data['login'])){
			$db->SetErrMsgOn(false);
			$db->insert(TABLE_PODPISKA, array('email' => $data['login'],'mkey'=>md5($data['login']),'date' => time()));
			$db->SetErrMsgOn();
		}
		
		$ret=$db->update(TABLE_USERS, array('id' => $_POST['k_id']), $data);
	}
	echo ($ret===false)?'0':'1';
	exit;
}  

$module['path'] = 'Клиенты';

if(!empty($_GET['action']) && $_GET['action'] == 'options') 
{
	$btns = array(
		'Клиенты'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=users\''),
		'Настройки'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=users&action=options\'')
	);

	require_once('options.php');
}
else
{
	$btns = array(
		'Клиенты'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=users\''),
		'Настройки'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=users&action=options\'')
	);

	require_once('users_list.php');
}
