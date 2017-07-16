<?
class LinksRow
{
	private $links = array();
	
	function __construct($data) {
		$this->links = $data;
	}
	
	function __ToString()
	{
		$cnt = '';
		foreach($this->links as $id=>$val)
		{
			$cnt .= '<a class="links_row lnk'.($id+1).'" href="'.$val.'"></a>';
		}
		return $cnt;
	}
}
