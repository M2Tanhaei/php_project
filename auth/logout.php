<?php
// session را شروع می‌کنیم
session_start();

// تمام متغیرهای session را unset می‌کنیم
$_SESSION = array();

// session را از بین می‌بریم
session_destroy();

// کاربر را به صفحه اصلی هدایت می‌کنیم
header("location: ../index.php");
exit;
?>
