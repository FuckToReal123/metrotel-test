<?php

return [
    'dsn' => 'mysql:host=mariadb;dbname=' . getenv('MYSQL_DATABASE'),
    'username' => getenv('MYSQL_USER'),
    'passwd' => getenv('MYSQL_PASSWORD'),
    'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
];
