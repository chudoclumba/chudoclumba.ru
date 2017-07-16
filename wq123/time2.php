<?php
$iteration='1000';
$drb="drop table test_table";
$crb="CREATE TABLE `test_table` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `num` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1";


function db_connect()
{
   $result = @mysql_pconnect("localhost", "chudocl_02", "08268");
   if (!$result)
      return false;
   if (!@mysql_select_db("chudocl_02"))
      return false;
   return $result;
}

function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = getmicrotime();

for ($i=0; $i < $iteration*1000; $i++) {

}

$time_end = getmicrotime();
$time = $time_end - $time_start;

echo "Считал овец до $iteration*1000: $time<br>";
$time_start = getmicrotime();
$handle=fopen('text.txt',"w");
for ($i=0; $i < $iteration*100; $i++) {

fwrite($handle,$i);

}
fclose($handle);
$time_end = getmicrotime();
$time = $time_end - $time_start;

echo "Писал файлы*100: $time<br>";

if (db_connect())
{
 	mysql_query($drb);
   	if (!mysql_query($crb)) echo "не создается<br>";
	$time_start = getmicrotime();
	for($i=0; $i<$iteration; $i++)
	{
		$var=rand(100000,999999);
		$ibq="insert into test_table values (NULL,".$var.")";
		mysql_query($ibq);
	}
	$time_end = getmicrotime();
	$time = $time_end - $time_start;

	echo "Ковырял базу $time<br>";
}else echo "problem mysql<br>";
?>