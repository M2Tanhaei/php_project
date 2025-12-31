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

    // بررسی کن که آیا این محصول از قبل در سبد خرید کاربر وجود دارد یا نه
    $sql_check = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";

    if ($stmt_check = mysqli_prepare($link, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt_check);
        $result = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result) > 0) {
            // اگر محصول وجود داشت، تعداد آن را یکی اضافه کن
            $sql_update = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
            if ($stmt_update = mysqli_prepare($link, $sql_update)) {
                mysqli_stmt_bind_param($stmt_update, "ii", $user_id, $product_id);
                mysqli_stmt_execute($stmt_update);
            }
        } else {
            // اگر محصول وجود نداشت، آن را به سبد خرید اضافه کن
            $sql_insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
            if ($stmt_insert = mysqli_prepare($link, $sql_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $product_id);
                mysqli_stmt_execute($stmt_insert);
            }
        }

        // کاربر را به صفحه محصولات بازگردان و یک پیام موفقیت نمایش بده
        $_SESSION['message'] = "محصول با موفقیت به سبد خرید اضافه شد!";
        header("location: ../products/index.php");
        exit;

    }
} else {
    // اگر product_id ارسال نشده بود
    $_SESSION['message'] = "خطا: محصولی انتخاب نشده است.";
    header("location: ../products/index.php");
    exit;
}
?>
