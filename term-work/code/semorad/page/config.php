<?php
define ('DB_HOST', 'db');
define ('DB_USER', 'root');
define ('DB_PASSWORD', 'docker');
define ('DB_NAME', 'db_dev');

define('BASE_URL' ,
    "http://" . $_SERVER['SERVER_NAME'] . "/semorad");

define('CURRENT_URL',
    $_SERVER["SCRIPT_NAME"] . '?' . $_SERVER["QUERY_STRING"]);


