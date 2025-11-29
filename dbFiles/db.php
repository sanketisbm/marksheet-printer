<?php
$dbHost = "localhost";
$dbUser = "complianceuser_result_soft_admin";
$dbPass = "waFhE3AII.Nt";
$dbName = "complianceuser_result_soft";

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
$conn->set_charset("utf8");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}