<?php
$i=0;
$us = $db->get_rows('select id,info from '.TABLE_USERS.' where info>" "');
foreach($us as $val){
	$val1=array();
		if (!empty($val['info'])) {
			$inf = unserialize(iconv('utf-8','cp1251',$val['info']));
		foreach($inf as  $key=> $v){
			$val1[$key]=iconv('windows-1251','utf-8',$v);
		}
		$inf=serialize($val1);
		$res=$db->update(TABLE_USERS,$val['id'],array('info'=>$inf));
		$i+=$res;
		

	}
}
echo 'Конвертировано '.$i.' записей';

