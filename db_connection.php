<?php
session_start(); // Start the session

$host = "127.0.0.1";
$user = "user";
$passwd = "password";
$database = "fr";

$conn = new mysqli($host, $user, $passwd, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$error = '';

