<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dateRange = isset($_POST['dateRange']) ? urldecode($_POST['dateRange']) : '';
    $dateRange = explode('*', $dateRange);
    $startDate = isset($dateRange[0]) ? $dateRange[0] : '';
    $endDate = isset($dateRange[1]) ? $dateRange[1] : '';

    // Validate date inputs
    if (!DateTime::createFromFormat('Y-m-d', $startDate) || !DateTime::createFromFormat('Y-m-d', $endDate)) {
        die(json_encode(array('error' => 'Invalid date format')));
    }

    $sql = "SELECT * FROM `results` WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'  ";

    // Append filters dynamically
    for ($i = 0; $i < 20; $i++) {
        $titleKey = 'filterTitle' . ($i === 0 ? '' : $i);
        $searchKey = 'filterSearch' . ($i === 0 ? '' : $i);
        $valueKey = 'filterValue' . ($i === 0 ? '' : $i);

        if (!empty($_POST[$titleKey]) && !empty($_POST[$searchKey]) && !empty($_POST[$valueKey])) {
            $title = mysqli_real_escape_string($conn, $_POST[$titleKey]);
            $search = mysqli_real_escape_string($conn, $_POST[$searchKey]);
            $value = mysqli_real_escape_string($conn, $_POST[$valueKey]);

            if ($search == 'LIKE') {
                $sql .= " AND `$title` $search '%$value%'";
            } else {
                $sql .= " AND `$title` $search '$value'";
            }
        }
    }

    $sql .= " ORDER BY `student_name`, `period`, `print_flag` ASC";

    $_SESSION["filterQuery"] = $sql;
    // $_SESSION["filterQueryPage"] = $condition;
    echo "<script type='text/javascript'> document.location = 'results'; </script>";
}
