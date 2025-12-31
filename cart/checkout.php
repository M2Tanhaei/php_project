<?php
require_once "../templates/header.php";
require_once "../config/database.php";

// اگر کاربر لاگین نکرده بود، او را به صفحه ورود هدایت کن
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION["id"];
$total_price = 0;
$cart_items = [];

// شروع یک تراکنش (Transaction) برای اطمینان از یکپارچگی داده‌ها
mysqli_begin_transaction($link);

try {
    // 1. واکشی آیتم‌های سبد خرید
    $sql_cart = "SELECT p.id, p.price, c.quantity
                 FROM cart c
                 JOIN products p ON c.product_id = p.id
                 WHERE c.user_id = ?";

    if ($stmt_cart = mysqli_prepare($link, $sql_cart)) {
        mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
        mysqli_stmt_execute($stmt_cart);
        $result = mysqli_stmt_get_result($stmt_cart);
        while ($row = mysqli_fetch_assoc($result)) {
            $cart_items[] = $row;
            $total_price += $row['price'] * $row['quantity'];
        }
        mysqli_stmt_close($stmt_cart);
    }

    // اگر سبد خرید خالی بود، عملیات را متوقف کن
    if (empty($cart_items)) {
        throw new Exception("سبد خرید شما خالی است.");
    }

    // 2. ایجاد یک رکورد جدید در جدول `orders`
    $sql_order = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
    if ($stmt_order = mysqli_prepare($link, $sql_order)) {
        mysqli_stmt_bind_param($stmt_order, "id", $user_id, $total_price);
        mysqli_stmt_execute($stmt_order);
        $order_id = mysqli_insert_id($link); // گرفتن ID سفارش جدید
        mysqli_stmt_close($stmt_order);
    } else {
        throw new Exception("خطا در ایجاد سفارش.");
    }

    // 3. انتقال آیتم‌ها به `order_items`
    $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    if ($stmt_items = mysqli_prepare($link, $sql_items)) {
        foreach ($cart_items as $item) {
            mysqli_stmt_bind_param($stmt_items, "iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            mysqli_stmt_execute($stmt_items);
        }
        mysqli_stmt_close($stmt_items);
    } else {
        throw new Exception("خطا در ثبت اقلام سفارش.");
    }

    // 4. خالی کردن سبد خرید کاربر
    $sql_clear = "DELETE FROM cart WHERE user_id = ?";
    if ($stmt_clear = mysqli_prepare($link, $sql_clear)) {
        mysqli_stmt_bind_param($stmt_clear, "i", $user_id);
        mysqli_stmt_execute($stmt_clear);
        mysqli_stmt_close($stmt_clear);
    }

    // اگر همه چیز موفقیت‌آمیز بود، تراکنش را commit کن
    mysqli_commit($link);
    $checkout_message = "خرید شما با موفقیت ثبت شد!";

} catch (Exception $e) {
    // اگر خطایی رخ داد، تراکنش را rollback کن
    mysqli_rollback($link);
    $checkout_message = "خطا: " . $e->getMessage();
}
?>

<div class="container wrapper" style="text-align: center;">
    <h2>وضعیت خرید</h2>
    <p><?php echo $checkout_message; ?></p>
    <a href="../products/index.php" class="btn btn-primary">بازگشت به فروشگاه</a>
</div>

<?php
mysqli_close($link);
require_once "../templates/footer.php";
?>
