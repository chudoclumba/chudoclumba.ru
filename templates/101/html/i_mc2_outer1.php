<?
$tmp=preg_replace('/\(\s?(ñêîðî|ÑÊÎÐÎ|Ñêîðî).*?\)/i','',$path);
//$tmp=iconv('utf-8','cp1251',$tmp);
?>
<div class="path_p2 p0"><?=$tmp?></div>

<div class="outer_content top" id="content">
<?if($_GET['module'] == 'site' && $_GET['id'] == '1')
{
	echo sitemenu_get_html(23);
	global $ishop,$sets;
	echo News::gI()->menu(3);
	if ($sets['mod_new'] )echo $ishop->specpredloshenie(9, 9);
}
echo $content;
?>
</div>
