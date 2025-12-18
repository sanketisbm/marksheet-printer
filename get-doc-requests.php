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

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(["status" => "ok"]);
    exit;
}

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

if (!is_array($data)) {
    echo json_encode([
        "status" => "error",
        "message" => "Payload must be an array of objects"
    ]);
    exit;
}

// Columns your API supports (REMOVED duplicate passout_session_hindi)
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
    'prefix_eng',
    'prefix_hindi',
    'department_hindi',
    'uploaded_image',
    'print_date',
    'issued_date'
];

$results = [];

foreach ($data as $idx => $record) {

    if (!is_array($record)) {
        $results[] = [
            "index" => $idx,
            "status" => "error",
            "message" => "Each item must be an object"
        ];
        continue;
    }

    // Collect values for response / duplicate checks
    $values = [];
    foreach ($columns as $c) {
        $values[$c] = array_key_exists($c, $record) ? $record[$c] : null;
    }

    $enrollment_no = $values['enrollment_no'];
    $issued_date   = $values['issued_date'];
    $request_type  = $values['request_type'];

    if (!$enrollment_no || !$issued_date || !$request_type) {
        $results[] = [
            "index" => $idx,
            "status" => "error",
            "message" => "Missing required fields: enrollment_no, issued_date, request_type",
            "given" => [
                "enrollment_no" => $enrollment_no,
                "issued_date" => $issued_date,
                "request_type" => $request_type
            ]
        ];
        continue;
    }

    // Duplicate check (prepared)
    $dupSql = "SELECT id FROM document_requests WHERE enrollment_no=? AND issued_date=? AND request_type=? LIMIT 1";
    $dupStmt = mysqli_prepare($conn, $dupSql);
    if (!$dupStmt) {
        $results[] = ["index" => $idx, "status" => "error", "message" => "Prepare failed", "mysql_error" => mysqli_error($conn)];
        continue;
    }
    mysqli_stmt_bind_param($dupStmt, "sss", $enrollment_no, $issued_date, $request_type);
    mysqli_stmt_execute($dupStmt);
    mysqli_stmt_store_result($dupStmt);

    if (mysqli_stmt_num_rows($dupStmt) > 0) {
        mysqli_stmt_close($dupStmt);
        $results[] = [
            "index" => $idx,
            "status" => "duplicate",
            "message" => "Record already exists",
            "existing_record" => [
                "enrollment_no" => $enrollment_no,
                "issued_date" => $issued_date,
                "request_type" => $request_type
            ]
        ];
        continue;
    }
    mysqli_stmt_close($dupStmt);

    // Build INSERT dynamically (prepared)
    $colList = [];
    $phList = [];
    $types = "";
    $binds = [];
    $inserted = [];

    foreach ($columns as $c) {
        if (!array_key_exists($c, $record)) continue;        // only insert what is provided
        $val = $record[$c];

        // keep empty string as valid, skip only null
        if ($val === null) continue;

        $colList[] = "`$c`";
        $phList[] = "?";
        $types .= "s";
        $binds[] = $val;
        $inserted[$c] = $val;
    }

    // Always insert print_flag = 0
    $colList[] = "`print_flag`";
    $phList[] = "?";
    $types .= "s";
    $binds[] = "0";
    $inserted["print_flag"] = "0";

    if (count($colList) === 0) {
        $results[] = [
            "index" => $idx,
            "status" => "error",
            "message" => "No valid fields provided to insert"
        ];
        continue;
    }

    $sql = "INSERT INTO document_requests (" . implode(", ", $colList) . ") VALUES (" . implode(", ", $phList) . ")";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $results[] = [
            "index" => $idx,
            "status" => "error",
            "message" => "Prepare insert failed",
            "mysql_error" => mysqli_error($conn),
            "sql" => $sql
        ];
        continue;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$binds);

    if (mysqli_stmt_execute($stmt)) {
        $results[] = [
            "index" => $idx,
            "status" => "success",
            "message" => "Record inserted successfully",
            "insert_id" => mysqli_insert_id($conn),
            "inserted_data" => $inserted
        ];
    } else {
        $results[] = [
            "index" => $idx,
            "status" => "error",
            "message" => "Insert failed",
            "mysql_error" => mysqli_stmt_error($stmt),
            "sql" => $sql
        ];
    }

    mysqli_stmt_close($stmt);
}

// Final response for ALL records (bulk)
echo json_encode([
    "status" => "done",
    "total" => count($data),
    "results" => $results
]);
