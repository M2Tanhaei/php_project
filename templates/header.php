<?php
// session را در ابتدای هر صفحه شروع می‌کنیم
session_start();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فروشگاه سیستم‌های صوتی</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header class="page-header">
        <h1><a href="../products/index.php" style="text-decoration: none; color: inherit;">فروشگاه سیستم‌های صوتی</a></h1>
        <div>
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <span>خوش آمدید، <?php echo htmlspecialchars($_SESSION["name"]); ?>!</span>
                <a href="../cart/index.php" class="btn">سبد خرید</a>

                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === 'admin'): ?>
                    <a href="../admin/index.php" class="btn">پنل ادمین</a>
                <?php endif; ?>

                <a href="../auth/logout.php" class="btn btn-danger">خروج</a>
            <?php else: ?>
                <a href="../auth/login.php" class="btn">ورود</a>
                <a href="../auth/register.php" class="btn">ثبت نام</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
