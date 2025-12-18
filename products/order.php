<?php
// فایل هدر را فراخوانی می‌کنیم که session_start() را نیز انجام می‌دهد.
require_once "../templates/header.php";
// فایل اتصال به دیتابیس
require_once "../config/database.php";

// بررسی می‌کنیم که آیا کاربر لاگین کرده است یا نه. اگر نه، او را به صفحه ورود هدایت می‌کنیم.
// این بررسی در هدر هم می‌تواند انجام شود، اما برای امنیت بیشتر اینجا هم تکرار می‌کنیم.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../auth/login.php");
    exit;
}

$order_message = "";

// بررسی می‌کنیم که آیا product_id از طریق URL ارسال شده است یا نه
if (isset($_GET["product_id"]) && !empty($_GET["product_id"])) {
    $product_id = intval($_GET["product_id"]); // برای امنیت بیشتر، به عدد صحیح تبدیل می‌کنیم
    $user_id = $_SESSION["id"];

    // یک رکورد جدید در جدول orders ثبت می‌کنیم
    $sql = "INSERT INTO orders (user_id, product_id) VALUES (?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);

        if (mysqli_stmt_execute($stmt)) {
            $order_message = "سفارش شما با موفقیت ثبت شد!";
        } else {
            $order_message = "مشکلی در ثبت سفارش پیش آمد. لطفا دوباره تلاش کنید.";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $order_message = "خطا: محصولی برای سفارش انتخاب نشده است.";
}

mysqli_close($link);
?>

<div class="wrapper" style="text-align: center;">
    <h2>وضعیت سفارش</h2>
    <p><?php echo $order_message; ?></p>
    <a href="index.php" class="btn btn-primary">بازگشت به صفحه محصولات</a>
</div>

<?php
// فایل فوتر را فراخوانی می‌کنیم
require_once "../templates/footer.php";
?>
