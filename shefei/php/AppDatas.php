<?php

require_once 'AppFunc.php';
require_once 'Medoo.php';

// Using Medoo namespace
use Medoo\Medoo;

$app = array();

$app['db_database_name'] = 'db_shefeidy_ht';

$app['db_database'] = new Medoo(array(
	// required
	'database_type' => 'mysql',
	'database_name' => $app['db_database_name'],
	'server' => 'localhost',
	'username' => 'root',
	'password' => 'root',
 
	// [optional]
	'charset' => 'utf8',
	//'port' => 3306,
 
	// [optional] Table prefix
	//'prefix' => 'shefei_',
 
	// [optional] Enable logging (Logging is disabled by default for better performance)
	'logging' => true,
 
	// [optional] MySQL socket (shouldn't be used with server and port)
	//'socket' => '/tmp/mysql.sock',
 
	// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	/*'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	],
 
	// [optional] Medoo will execute those commands after connected to the database for initialization
	'command' => [
		'SET SQL_MODE=ANSI_QUOTES'
	]*/
));

$app['total_table_size'] = 0;
$app['table_show_count'] = 20;
$app['hint'] = array();

// function
$app['hint']['hint'] = '_Hint_';
$app['hint']['back_up'] = '_Back_Up_';
$app['hint']['goto'] = '_Goto_';
$app['hint']['reload'] = '_Reload_';
$app['hint']['isset'] = '_IsSet_';
$app['hint']['array_valid'] = '_Array_Valid_';
$app['hint']['array_find'] = '_Array_Find_';


?>