<?php
$host = '127.0.0.1';
$user = 'root';
$password = '';
$db = 'test_samson';

$connect = new mysqli($host, $user, $password, $db);
if($connect->connect_error)
{
    die("Ошибка: " . $connect->connect_error);
}