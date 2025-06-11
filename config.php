<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'login_register_db';

$connection = new mysqli($servername, $username, $password, $databaseName);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>