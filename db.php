<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "tugdeals_result_soft";

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
$conn->set_charset("utf8");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}