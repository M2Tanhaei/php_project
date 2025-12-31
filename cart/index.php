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

// واکشی آیتم‌های سبد خرید کاربر به همراه اطلاعات محصول
$sql = "SELECT p.id, p.name, p.price, p.image, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";

$cart_items = [];
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity'];
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="container">
    <h2>سبد خرید شما</h2>
    <?php if (empty($cart_items)): ?>
        <p>سبد خرید شما خالی است.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>محصول</th>
                    <th>نام</th>
                    <th>قیمت</th>
                    <th>تعداد</th>
                    <th>مجموع</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50"></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo number_format($item['price']); ?> تومان</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price'] * $item['quantity']); ?> تومان</td>
                        <td>
                            <form action="remove_from_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-total">
            <h3>جمع کل: <?php echo number_format($total_price); ?> تومان</h3>
            <a href="checkout.php" class="btn btn-primary">نهایی کردن خرید</a>
        </div>
    <?php endif; ?>
</div>

<?php
mysqli_close($link);
require_once "../templates/footer.php";
?>
