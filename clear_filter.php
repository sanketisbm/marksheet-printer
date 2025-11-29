<?php
session_start();
unset($_SESSION['filterQuery']);
unset($_SESSION['filterQueryPage']);
echo "Filter cleared";
?>