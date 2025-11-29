<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json; charset=UTF-8');

require 'db.php';

$id = mysqli_real_escape_string($conn, $_POST['id']);

$getLeadquery = "DELETE FROM `results` WHERE `id` = '$id'";
$getLeadresult = $conn->query($getLeadquery);

if ($getLeadresult) {
    echo json_encode(["status" => "success", "message" => "Request raised successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error inserting Request: " . $conn->error]);
}
