<?php
	
$path = ROOT_DIR.'templates/';
$templates = get_folders($path);

$_SESSION['tmpl'] = (isset($_GET['style'])) ? $_GET['style'] : '0';



$tsemplates =  '
<script>
function update_teml(tid)
{
	location.href="'.SITE_URL.'?style=" + tid.value;
}
</script>
<div style="position:absolute; z-index:1000; top:10px; left:10px;">';
$tsemplates .= html_select(
array(
	'curr' => $_SESSION['tmpl'],
	'onchange' => 'update_teml(this)'

),$templates);
$tsemplates .= '</div>';