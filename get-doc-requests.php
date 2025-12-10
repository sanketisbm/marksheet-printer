<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kolkata');

require 'dbFiles/db.php';

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid JSON input',
        'error' => json_last_error_msg(),
    ]);
    exit;
}


foreach ($data as $record) {
    $columns = [
        'request_type',
        'student_name',
        'enrollment_no',
        'application_id',
        'fathers_husbands_name',
        'program',
        'passing_year',
        'division',
        'specialization',
        'mother_name',
        'professor',
        'professor_desg',
        'professor_dept',
        'doc_no',
        'student_name_hindi',
        'father_name_hindi',
        'branch',
        'program_name_hindi',
        'splz_name_hindi',
        'passout_session_hindi',
        'division_hindi',
        'passout_session_hindi',
        'prefix_eng',
        'prefix_hindi',
        'uploaded_image',
        'print_date',
        'issued_date'
    ];

    $escaped_values = [];
    foreach ($columns as $column) {
        if (isset($record[$column])) {
            $escaped_values[$column] =  $record[$column];
        } else {
            $escaped_values[$column] = null;
        }
    }

    $enrollment_no = $escaped_values['enrollment_no'];
    $issued_date = $escaped_values['issued_date'];
    $request_type = $escaped_values['request_type'];

    $select_query = "SELECT * FROM `document_requests` WHERE `enrollment_no` = '$enrollment_no' AND `issued_date` = '$issued_date' AND `request_type` = '$request_type'";
    $select_result = mysqli_query($conn, $select_query);
    if ($select_result && mysqli_num_rows($select_result) === 0) {
        // Insert new record
        $column_list = [];
        $value_list = [];
        foreach ($columns as $column) {
            if ($escaped_values[$column] !== null) {
                $column_list[] = "`$column`";
                $value_list[] = "'" . mysqli_real_escape_string($conn, $escaped_values[$column]) . "'";
            }
        }
        // Explicitly include print_flag
        $column_list[] = "`print_flag`";
        $value_list[] = "'0'";

        $insert_query = "INSERT INTO `document_requests` (" . implode(", ", $column_list) . ") VALUES (" . implode(", ", $value_list) . ")";
        $insert_result = mysqli_query($conn, $insert_query);
        if ($insert_result) {
            echo json_encode([
                "status" => "success",
                "message" => "Record inserted successfully",
                "inserted_data" => $escaped_values
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Insert failed",
                "mysql_error" => mysqli_error($conn)
            ]);
        }
    } else {
        echo json_encode([
            "status" => "duplicate",
            "message" => "Record already exists",
            "existing_record" => [
                "enrollment_no" => $enrollment_no,
                "issued_date" => $issued_date,
                "request_type" => $request_type,
            ]
        ]);
    }
}
