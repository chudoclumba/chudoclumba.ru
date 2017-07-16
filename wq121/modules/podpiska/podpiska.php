<?
class Podpiska extends Site
{
	function __construct(MySQL $db)
	{
		$this->db = Site::gI()->db;
	}

	function delete($id)
	{
		$this->db->delete(TABLE_PODPISKA, array('id'=>$id));
	}

	function users()
	{
		ob_start();
		
		if(!empty($_GET['dId']))
		{
			$this->delete($_GET['dId']);
		}
		
		$rows = $this->db->get_rows("SELECT * FROM ".TABLE_PODPISKA." ORDER BY id ASC");
?>
		<form name="forma" style="margin:3px;" id="frmparts" action="include.php?place=podpiska" method="post">
		<table class="main_no_height">
		 <tr>
		  <th class="news_header" style="width:75px;">#</th>
		  <th class="news_header" >Email</th>
		  <th class="news_header" >Дата добавления</th>
		  <th class="news_header" style="width:160px;">Функции</th>
		 </tr>
<?php
		foreach($rows as $row)
		{
?>		
			 <tr>
			  <td class="news_td"><?php echo $row['id']?></td>
			  <td class="news_td"><?php echo $row['email']?></td>
			  <td class="news_td"><?php echo date('H:i:s d.m.Y', $row['date'])?></td>
			  <td class="news_td"><a href="include.php?place=podpiska&dId=<?php echo $row['id']?>">Удалить</a> <!--<a href="#">Отправить сообщение</a>--></td>
			 </tr>
<?php
		}
?>		
		</table></form>
<?php
		$cnt = ob_get_contents();
		ob_end_clean();
		return $cnt;
	}
}

$podpiska = new Podpiska($db);

$module['html'] = $podpiska->users();
$module['path'] = 'Подписка на новости';
