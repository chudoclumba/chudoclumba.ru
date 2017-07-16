<?php
include ROOT_DIR.'includes/mysql.class.php';

$r = ";\n";

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
	$r = ";\r\n";
}
$db = MySQL::gI();
$db->connect(DB_HOST, DB_NAME, DB_USER, DB_PASS);
