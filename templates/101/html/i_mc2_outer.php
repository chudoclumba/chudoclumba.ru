<table class="w100 col cw">
<tr><td class="path_p2 p0"><div class="path_pabs"><?=$path?></div></td></tr>
<tr><td class="outer_content top">
<?=($_SERVER['REQUEST_URI'] == '/' && (class_exists('Filter'))) ? Filter::gI()->form() : ''?>
<?if($_GET['module'] == 'site' && $_GET['id'] == '1')
{
	echo sitemenu_get_html(23);
	global $ishop;
	echo '<div class="ppt ppt5"><a href="'.SITE_URL.'news/page/1">Наши новости</a></div>';
	echo News::gI()->menu(3);
	echo $ishop->specpredloshenie(9, 9);
}
echo $content;
?>
</td></tr></table>
