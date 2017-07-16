<?php

if(intval(date("d",time()))%13 == 0 && file_exists('../../backup/'))
{
	require_once('../includes/pclzip.lib.php');
	if(!file_exists('../../backup/SQL_' . date("d",time()).'.sql.gz'))
	{
		require_once('backup_sql.php');
	}

	$bkp_name = 'FILES_'.date('d',time()).'.zip';
	if(!file_exists('../../backup/'.$bkp_name))
	{
		$archive = new PclZip('../../backup/'.$bkp_name);
		$v_list = $archive->add(ROOT_DIR, PCLZIP_OPT_REMOVE_PATH, 'dev');
	}
}