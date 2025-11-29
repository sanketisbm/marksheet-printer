<?php
session_start();
if (!isset($_SESSION['employee_name']) && !isset($_SESSION['session_id'])) {
    echo "<script type='text/javascript'> document.location = 'login'; </script>";
    exit();
}
?>