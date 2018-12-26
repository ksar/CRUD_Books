<?php
/**
 * Created by PhpStorm.
 * User: Ksar
 * Date: 19.12.2018
 * Time: 17:11
 */

/*
function myAutoloader($className) {

    $suffixes = array ( '.php','.config.php','.model.php' );
    $folders = array ( '/class/', '/config/', '/model/' );
    $class = strtolower($className);

    foreach ($suffixes AS $suffix) {
        foreach ($folders AS $folder) {
            $file = getcwd().$folder.$class.$suffix;
            if (file_exists($file) === true) {
                include_once ($file);
                break(2);
            }
        }
    }

    return false;
}
*/
require_once ('class/functions.php');
spl_autoload_register('Functions::myAutoloader');

$database = new Database();
$db_connection = $database->getConnection();
$author = new Author($db_connection);
$book = new Book($db_connection);

$_get = $_GET;
$_post = $_POST;
$out = Functions::mainRouting( $_get, $_post, $book, $author);

print <<< EOH
<html>
<head>
<link rel="stylesheet"  href="css/style.css" type="text/css" media="all">
</head>
<body>
EOH;

print('<hr><a href="?">MAIN PAGE</a><hr>');
print ( $out );
print('</body></html>');
