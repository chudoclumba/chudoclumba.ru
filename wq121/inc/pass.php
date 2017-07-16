<?php

$q = $db->query("select `value` from ".TABLE_SETTINGS." where (`id` = 'user_login' or `id` = 'user_password') and `part` = 'options'");
if ($db->num_rows($q) > 0)
{
	list($user_login) = $db->fetch_array($q);
	list($user_pass) = $db->fetch_array($q);
} 
else 
{
	$user_login = '123';
	$user_pass = 'dc0fa7df3d07904a09288bd2d2bb5f40';
}