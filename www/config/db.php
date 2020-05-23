<?php

return [
    'dsn' => 'mysql:host=mariadb;dbname=' . getenv('MYSQL_DATABASE'),
    'username' => getenv('MYSQL_USER'),
    'passwd' => getenv('MYSQL_PASSWORD')
];
