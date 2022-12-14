<?php
$port = "3307";
$charset = 'utf8mb4';

$dbh = "127.0.0.1";
$username = "root";
$password="";
$dbname="pricecollegequora";
 $dsn = "mysql:host=$dbh;dbname=$dbname;charset=$charset;port=$port";
try{
    $pdo = new PDO($dsn, $username, $password);
}
catch(Exception $e){
    echo json_encode(array('status'=>500, 'message'=>'Database connection error:', 'Error'=>$e));
    die();

}

 ?> 
 