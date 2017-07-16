<?
class CalcDist extends Site
{
	static public function form()
	{
		$db = Site::gI()->db;
		
		$rows = $db->get_rows("SELECT * FROM ".TABLE_DISTS."");
		
		$towns = array();
		
		foreach($rows as $id=>$val)
		{
			$towns['first'][] = $val['town1'];
			$towns['second'][] = $val['town2'];
			$towns['dist'][] = $val['dist'];
		}
		
		return Site::gI()->view('feedback/dist', array('towns' => $towns));
	}
}