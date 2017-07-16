<?

if (isSet($_GET['type']) && isSet($_GET['value']))
{

 if (!empty($_GET['pID']))
 {
  $pid = $_GET['pID'];
  $q = "update ".TABLE_PRODUCTS." set `".$_GET['type']."` = '".$_GET['value']."' where `id` = '".$pid."' LIMIT 1";
 	$db->exec($q);
 }
 else
 {
  $pid = $_GET['cID'];
  $q = "update ".TABLE_CATEGORIES." set `".$_GET['type']."` = '".$_GET['value']."' where `id` = '".$pid."' LIMIT 1";
 	$db->exec($q); 
 }
}

header("Location:".$_SERVER['HTTP_REFERER'])
?>