<?php
require 'dbFiles/db.php';

$ids = $_POST['ids'];
$update_query = "UPDATE `results` SET `print_flag`='1' WHERE `id` IN ($ids)";
$update_result = mysqli_query($conn, $update_query);

if ($update_result) {
    echo json_encode(['success' => true, 'message' => 'PDF generated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'No content received or invalid request']);
}
