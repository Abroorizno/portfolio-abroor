<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'porto';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    echo "Failed to connect to database";
    die;
}
