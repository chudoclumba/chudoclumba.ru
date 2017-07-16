<?php
if(!class_exists('Site')) die(include '../404.html');

class Podpiska extends Site
{
	function __construct(MySQL $db)
	{
		$this->db = Site::gI()->db;
	}

	static function form()
	{
		$frm = Sitemenu::gI()->view('podpiska/podpiska');		
		return $frm;
	}
	
	function in($email)
	{
		$rows = $this->db->get_rows("SELECT id FROM ".TABLE_PODPISKA." WHERE email=".quote_smart($email)." LIMIT 1");
		return count($rows);
	}
	
	function add($email)
	{
		if (valid_email($email)) 
		{
			if (!$this->in($email)) 
			{
				$this->db->insert(TABLE_PODPISKA, array(
					'email' => $email,
					'mkey'=>md5($email),
					'date' => time()
				));
				
				$this->alert('Ваш E-mail был добавлен в базу');
			}
			else
			{
				$this->alert('Такой E-mail уже есть в базе');
			}
		}
		else
		{
			$this->alert('E-mail введен неверно');
		}
	}
	
	function alert($msg)
	{
		echo $msg;
		exit;
	}
	function rm($mkey)
	{
			$content = array (
				'meta_title' => 'Отказ от подписки',
				'meta_keys' => '',
				'meta_desc' => '',
				'path' =>'Отказ от подписки',
				'html'=>'Неудачно. Возможно, Вы уже отказались от подписки.'
			);
			$res=array();
			if (!empty($mkey) && $mkey!=-1) $res=$this->db->get_rows('select id,email from '.TABLE_PODPISKA.' where mkey="'.$mkey.'"');
			if (count($res)>0 && !empty($res[0]['email']))
			{
				$this->db->delete(TABLE_PODPISKA,array('id'=>$res[0]['id']));
				$content['html'] ='Подписка на новости для '.$res[0]['email'].' оменена.';
			}
			return $content;
	}

}

$podpiska = new Podpiska($db);

if ($_GET['module'] == 'podpiska')
{
	if(isset($_POST['add_email']))
	{
		$podpiska->add($_POST['email']);
		$site->redirect($_SERVER['HTTP_REFERER']);
	}
	if ($_GET['type'] == 'unsubscribe') $content=$podpiska->rm($_GET['id']);
}