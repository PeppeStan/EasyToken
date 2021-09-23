<?php
//storing the variables for database connection
$config = [
    'db_engine' => 'mysql',
    'db_host' => 'localhost', //Your Host Name
    'db_name' => 'yourname', // Your Database Name
    'db_user' => 'root',  // Your Db User - For external use better not use root
    'db_password' => '', // Db User Password
];

$db_config = $config['db_engine'] . ":host=".$config['db_host'] . ";dbname=" . $config['db_name'];

try {
    $pdo = new PDO($db_config, $config['db_user'], $config['db_password'], [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
        
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    exit("Impossible connection to the database: " . $e->getMessage());
}