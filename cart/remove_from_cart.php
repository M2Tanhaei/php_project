<?php
session_start();
require_once "../config/database.php";

// اگر کاربر لاگین نکرده بود، او را به صفحه ورود هدایت کن
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../auth/login.php");
    exit;
}

// بررسی کن که آیا product_id ارسال شده است
if (isset($_POST["product_id"]) && !empty($_POST["product_id"])) {
    $user_id = $_SESSION["id"];
    $product_id = $_POST["product_id"];

    // دستور SQL برای حذف آیتم از سبد خرید
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// کاربر را به صفحه سبد خرید بازگردان
header("location: index.php");
exit;
?>
