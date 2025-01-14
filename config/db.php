<?php
$host = 'mysql-litmaaa.alwaysdata.net';
$db = 'litmaaa_cdf';
$user = 'litmaaa_cdf';
$pass = '7Bt3GL35kpyDwT59cY3HHqJb3u7j5eqMc5d54E9Acm723SUjKE';

$dsn = "mysql:host=$host;dbname=$db";


try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
    echo 'Connection failed: <br>';
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>