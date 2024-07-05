<?php

// Ahhoz, hogy az apache szerver tudja kezelni az Authorization: Barer token headert, ez a két sor kell a .htaccess file-ba!
// RewriteCond %{HTTP:Authorization} ^(.*)
// RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]

require('./jwt_utils.php');
require('./cors.php');
require('./config.php');

$data = null;
<<<<<<< HEAD
<<<<<<< HEAD
$development = false;
=======
$development = true;
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
=======
$development = true;
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1

$pdo = new PDO('mysql:host=mysql.rackhost.hu;dbname='.$db['mysqlDb'], $db['mysqlUser'], $db['mysqlPass'], array(PDO::MYSQL_ATTR_FOUND_ROWS => true));

if ($development) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

$resource = strtok($_SERVER['QUERY_STRING'], '='); // lecsapja a query = előtti részét
//require('auth.php');

if ($resource == 'users') {
    require('users.php');
}

if ($resource == 'user') {
    require('user.php');
}

if ($resource == 'posts') {
    require('posts.php');
}

if ($resource == 'stats') {
    require('stats.php');
}

if ($resource == 'chat') {
    require('chat.php');
}
  
exit(json_encode($data));