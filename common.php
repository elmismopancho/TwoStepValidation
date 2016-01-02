<?php

require_once 'medoo.php';
require_once 'util.php';

$db = new medoo([
	'database_type' => 'mysql',
	'database_name' => 'twosteplogin',
	'server' => 'localhost',
	'username' => 'twosteplogin',
	'password' => 'setupassword',
	'charset' => 'utf8'
]);

session_start();