<?php

$dsn = 'mysql:dbname=数据库名字;host=localhost';
$user = '数据库用户名';
$pwd = '数据库密码';
// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)
require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
OAuth2_Autoloader::register();

// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2_Storage_Pdo(array('dsn' => $dsn, 'username' => $user, 'password' => $pwd));

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2_Server($storage);

// Add the "Client Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2_GrantType_ClientCredentials($storage));
?>
