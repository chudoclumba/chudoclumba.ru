<?php
define('ROOT_DIR', '/home/c/chudocl/public_html/');
define('INC_DIR', ROOT_DIR.'includes/');
include INC_DIR.'dbconf.php';
include INC_DIR.'dbtables.php';
require INC_DIR.'dbconnect.php';
global $db;
$sql='delete c.* from  '.TABLE_CART.' c where c.userid=0 and c.date<'.(time()-2*30*24*60*60);
$sql1='delete d.* from '.TABLE_CART_DET.' d, '.TABLE_CART.' c where c.id=d.cartid and c.userid>0 and c.date<'.(time()-6*30*24*60*60);
$sql2='delete d.* FROM '.TABLE_CART_DET.' d LEFT JOIN '.TABLE_CART.' c ON c.id=d.cartid WHERE c.id IS NULL';
$db->exec($sql);
$db->exec($sql1);
$db->exec($sql2);
?>