<?php
require 'db.php';

if (!isset($_POST['id']) || !isset($_FILES['image'])) {
    echo "Invalid request";
    exit;
}

$id = intval($_POST['id']);

$targetDir = "../uploaded_images/";
if (!is_dir($targetDir)) mkdir($targetDir);

$filename = time() . "_" . basename($_FILES["image"]["name"]);
$targetFile = $targetDir . $filename;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    $sql = "UPDATE document_requests SET uploaded_image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $filename, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "db_error";
    }
} else {
    echo "upload_error";
}
?>
