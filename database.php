
<?php

//$host='maspostwarehouseusers.com';
//$db = 'maspost';
//$username = 'appmasuser';
//$password = 'Myapp11!';

$host='localhost';
$db = 'maspost';
$username = 'appmasuser';
$password = 'Myapp11!';

$dsn= "mysql:host=$host;dbname=$db;charset=utf8";

try{
    // create a PDO connection with the configuration data
    $connection = new PDO($dsn, $username, $password);
}catch (PDOException $e){
    // report error message
    echo $e->getMessage();
}
