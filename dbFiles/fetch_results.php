<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json; charset=UTF-8');

require 'db.php';

// Set the connection character set to UTF-8
mysqli_set_charset($conn, "utf8");
$dateRange = isset($_GET['dateRange']) ? urldecode($_GET['dateRange']) : '';
$dateRange = explode('*', $dateRange);
$startDate = isset($dateRange[0]) ? $dateRange[0] : '';
$endDate = isset($dateRange[1]) ? $dateRange[1] : '';

// Validate date inputs
if (!DateTime::createFromFormat('Y-m-d', $startDate) || !DateTime::createFromFormat('Y-m-d', $endDate)) {
    die(json_encode(array('error' => 'Invalid date format')));
}

$getLeadquery = "";

if (!empty($_SESSION["filterQuery"])) {
    $getLeadquery = $_SESSION["filterQuery"];
} else {
    $startDate = mysqli_real_escape_string($conn, $startDate);
    $endDate = mysqli_real_escape_string($conn, $endDate);

    $getLeadquery = "SELECT * FROM `results` WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND `print_flag` = 0 ORDER BY `student_name`, `period`, `print_flag` ASC";
    
   // $getLeadquery = "SELECT * FROM `results` WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND `print_flag` = 0 ORDER BY `application_id`, `print_flag` ASC";
}
// echo $chklmrow["job_title_designation"];
// echo $getLeadquery;
$getLeadresult = $conn->query($getLeadquery);

if (!$getLeadresult) {
    die(json_encode(array('error' => 'Error in fetching lead sources: ' . mysqli_error($conn))));
}

// Collect leads data
$leadData = array();
$leadArrayID = array();

while ($getLeadrows = $getLeadresult->fetch_assoc()) {
    $leadArrayID[] = $getLeadrows['id'];
    $leadData['result'][] = $getLeadrows; // Append each row to $leadData array
}

echo json_encode($leadData, JSON_UNESCAPED_UNICODE);
$_SESSION["arrayID"] = $leadArrayID;
