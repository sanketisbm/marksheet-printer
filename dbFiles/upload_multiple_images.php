<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

function respond($success, $message, $extra = []) {
    echo json_encode(array_merge([
        "success" => $success,
        "message" => $message
    ], $extra));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, "Invalid request method.");
}

if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
    respond(false, "No images selected.");
}

$uploadDir = realpath(__DIR__ . '/../') . '/uploaded_images/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$allowedExt = ['jpg','jpeg','png','webp'];
$maxSize = 5 * 1024 * 1024; // 5MB each

$stored = [];
$skipped = 0;

$count = count($_FILES['images']['name']);

for ($i = 0; $i < $count; $i++) {

    $originalName = $_FILES['images']['name'][$i];
    $tmp          = $_FILES['images']['tmp_name'][$i];
    $err          = $_FILES['images']['error'][$i];
    $size         = $_FILES['images']['size'][$i];

    if ($err !== UPLOAD_ERR_OK) { $skipped++; continue; }
    if ($size > $maxSize) { $skipped++; continue; }

    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) { $skipped++; continue; }

    if (@getimagesize($tmp) === false) { $skipped++; continue; }

    // 🔐 sanitize original filename
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
    $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $baseName);

    $fileName = $safeBase . "." . $ext;
    $destination = $uploadDir . $fileName;

    // 🔁 Prevent overwrite (file.jpg → file_1.jpg → file_2.jpg)
    $counter = 1;
    while (file_exists($destination)) {
        $fileName = $safeBase . "_" . $counter . "." . $ext;
        $destination = $uploadDir . $fileName;
        $counter++;
    }

    if (move_uploaded_file($tmp, $destination)) {
        $stored[] = $fileName;
    } else {
        $skipped++;
    }
}

if (empty($stored)) {
    respond(false, "No valid images uploaded. (Check type/size)");
}

respond(true, "Uploaded: " . count($stored) . " image(s). Skipped: " . $skipped, [
    "files" => $stored
]);
