<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db.php';
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `sw_users` WHERE `employee_code` = '$username' AND `pan_card_no` = '$password'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error in query: " . mysqli_error($conn));
    }
    $count = mysqli_num_rows($result);
    if ($count === 1) {
        $rows = mysqli_fetch_assoc($result);

        $_SESSION['session_id'] = $rows["id"];
        $_SESSION['employee_name'] = $rows["employee_name"];
        $_SESSION['employee_code'] = $rows["employee_code"];
        echo "<script type='text/javascript'> document.location = '../results'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
        echo "<script type='text/javascript'> document.location = '../login'; </script>";
    }
}
