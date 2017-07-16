<?php
date_default_timezone_set('Europe/Moscow');

function si_getExtension4($filename) {
    return substr(strrchr($filename, '.'), 1);
}

function chmod_R($path) 
{
	$day = '01';
	$month = '08';
	$year = '2009';
	$mdate = mktime(0, 0, 0, $month, $day, $year);

	$ch_f = array('php', 'html', 'js');

	$handle = opendir($path);
	while ( false !== ($file = readdir($handle)) ) 
	{
		if ( ($file !== ".") && ($file !== "..") ) 
		{
			if ( is_file($path."/".$file)) 
			{
				if(in_array($file, array('hs_counter.js')))
				{
					echo $path."/".$file;
					exit;
				}
				
				$st = stat($path."/".$file);
				if(in_array(si_getExtension4($file), $ch_f) && $st['mtime'] > $mdate)
				{
					$cn = file_get_contents($path."/".$file);
					
					if(strpos($cn, 'tn00'.'1.cn') !== false || strpos($cn, 'nt0'.'02') !== false || strpos($cn, 'eval(base64_'.'decode(') !== false || strpos($cn, 'shop-'.'weto') !== false || strpos($cn, 'anoth'.'er-life') !== false)
					{
						echo $path."/".$file.' '.date('d-m-Y', $st['mtime']).' '.strpos($cn,'if'.'rame').'<br>';
					}
				}
			}
			else 
			{
				chmod_R($path . "/" . $file);
			}
		}
	}
	closedir($handle);
}

$path = $_SERVER["DOCUMENT_ROOT"];

chmod_R($path);
//echo $path;
?> 
