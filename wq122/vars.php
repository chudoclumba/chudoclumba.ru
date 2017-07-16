<?php
$dirp = dirname(__FILE__);
if (strpos($dirp, '\\') !==false)
{
	$dirp = str_replace("\\", "/", $dirp);
}

$dirc = explode('/',$dirp);

define('SA_DIR',ROOT_DIR.$dirc[count($dirc)-1].'/');
define('SA_URL',SITE_URL.$dirc[count($dirc)-1].'/');

define('TEMPLATE_DIR', SA_DIR."templates/orange/");
define('TEMP_FOLDER', SA_DIR."templates/orange/");
define('TEMPLATE_URL', SA_URL."templates/orange/");