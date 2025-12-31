<?php
session_start();

// بررسی کن که آیا کاربر لاگین کرده و نقش ادمین دارد یا نه
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== 'admin') {
    // اگر دسترسی نداشت، او را به صفحه اصلی سایت هدایت کن
    header("location: ../products/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>پنل مدیریت</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* استایل‌های اضافی مخصوص پنل ادمین */
        .admin-nav {
            background-color: #343a40;
            padding: 10px;
            text-align: center;
        }
        .admin-nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
        }
        .container {
            padding: 20px;
        }
    </style>
</head>
<body>
    <header class="page-header">
        <h1>پنل مدیریت</h1>
        <div>
            <span>خوش آمدید، <?php echo htmlspecialchars($_SESSION["name"]); ?>!</span>
            <a href="../auth/logout.php" class="btn btn-danger">خروج</a>
        </div>
    </header>
    <nav class="admin-nav">
        <a href="products.php">مدیریت محصولات</a>
        <a href="orders.php">مدیریت سفارشات</a>
        <a href="../products/index.php">بازگشت به فروشگاه</a>
    </nav>
    <main class="container">
