<?php
/*
 * فایل تنظیمات اتصال به دیتابیس
 */

// اطلاعات اتصال به دیتابیس
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'shopuser');
define('DB_PASSWORD', 'shoppass');
define('DB_NAME', 'simple_shop');

// ایجاد اتصال به دیتابیس MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// بررسی اتصال
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// تنظیم کاراکتر ست برای ارتباط با دیتابیس
mysqli_set_charset($link, "utf8mb4");
?>
