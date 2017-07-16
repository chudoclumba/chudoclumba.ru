<?

if (isSet($_GET['type']) && isSet($_GET['value']))
{
	$pid = $_GET['pID'];
	if ($_GET['link_is'] >= 0 && $_GET['type'] != 'enabled') $pid = $_GET['link_is'];
	$t =  "or `link` = '".$pid."'";
	if ($_GET['type'] == 'enabled') $t = "";
	$db->exec("update ".TABLE_PRODUCTS." set `".$_GET['type']."` = '".$_GET['value']."' where `id` = '".$pid."' ".$t);
}

header("Location: ".$_SERVER['HTTP_REFERER']);

?>