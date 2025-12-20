<?php
session_start();
header('Content-Type: application/json');

require 'db.php';

if (!isset($_POST['id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id = intval($_POST['id']);
$student = trim($_POST['student_name_hindi'] ?? '');
$father  = trim($_POST['father_name_hindi'] ?? '');

if ($student === '' || $father === '') {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE document_requests 
    SET student_name_hindi = ?, father_name_hindi = ?
    WHERE id = ?
");

$stmt->bind_param("ssi", $student, $father, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
