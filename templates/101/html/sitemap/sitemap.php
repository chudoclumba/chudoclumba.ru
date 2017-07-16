<ul class="sitemap">
<?
foreach($popup_menu as $menu_id)
{
	$display = (in_array($menu_id,$parents))?'block':'block';
	$menu_link = SITE_URL.'site/'.$menu_id.'';
?>
 <li><a href="<?php echo $menu_link?>"><?php echo StripSlashes($menu_ru[$menu_id])?></a></li>
<?	
	if ($submenu =="on")
	{
		$d = $this->db->query("SELECT count(id) FROM ".TABLE_SITEMENU." WHERE pid =".$menu_id.";");
		$c_rows = $this->db->fetch_array($d);
		if ($c_rows['0'] > 0)
		{
			$level=2;
			$this->map_menu($level,$display,$menu_id);
		}
	}
	$k++;
}
 
?>
</ul>