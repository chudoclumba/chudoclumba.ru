<?php
$dirp = dirname(__FILE__);
if (strpos($dirp, '\\') !==false)
{
	$dirp = str_replace("\\", "/", $dirp);
}
$dirc = explode('/',$dirp);
define('SA_DIR',ROOT_DIR.$dirc[count($dirc)-1].'/');
define('SA_URL',SITE_URL.$dirc[count($dirc)-1].'/');
define('TEMPLATE_DIR', SA_DIR."template/");
define('TEMP_FOLDER', SA_DIR."template/");
define('TEMPLATE_URL', SA_URL."template/");
$st_zak_arrray = array(
						0 => 'Неизвеcтен',
						1 => 'АвтоРезерв',
						2 => 'В работе',
						3 => 'Принят',
						4 => 'Закрыт',
						6 => 'Аннулирован'

					);
$st_log_arrray = array(
	0 => 'Удаление из загр.',
	1 => 'Добавление в старый',
	88 => 'Удаление из добавленного',
	100 => 'Удаление из нового',
	99 => 'Удаление из загр с ошибкой регистратора',
	3 => 'Добавление в новый'
	);
