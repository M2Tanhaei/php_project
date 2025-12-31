<?php
require_once "templates/header.php";
require_once "../config/database.php";

// واکشی سفارشات به همراه اطلاعات کاربر
$orders = [];
$sql = "SELECT o.id, o.total_amount, o.order_date, u.name AS user_name
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.order_date DESC";

$result = mysqli_query($link, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // برای هر سفارش، اقلام آن را نیز واکشی می‌کنیم
        $order_items = [];
        $sql_items = "SELECT oi.quantity, oi.price, p.name AS product_name
                      FROM order_items oi
                      JOIN products p ON oi.product_id = p.id
                      WHERE oi.order_id = ?";

        if ($stmt_items = mysqli_prepare($link, $sql_items)) {
            mysqli_stmt_bind_param($stmt_items, "i", $row['id']);
            mysqli_stmt_execute($stmt_items);
            $result_items = mysqli_stmt_get_result($stmt_items);
            while($item_row = mysqli_fetch_assoc($result_items)){
                $order_items[] = $item_row;
            }
            mysqli_stmt_close($stmt_items);
        }
        $row['items'] = $order_items;
        $orders[] = $row;
    }
}
?>

<h3>لیست سفارشات</h3>
<table class="cart-table">
    <thead>
        <tr>
            <th>ID سفارش</th>
            <th>نام کاربر</th>
            <th>مبلغ کل</th>
            <th>تاریخ سفارش</th>
            <th>اقلام سفارش</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($orders)): ?>
            <tr>
                <td colspan="5">هیچ سفارشی ثبت نشده است.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                    <td><?php echo number_format($order['total_amount']); ?> تومان</td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td>
                        <ul>
                            <?php foreach ($order['items'] as $item): ?>
                                <li>
                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                    (تعداد: <?php echo $item['quantity']; ?>، قیمت واحد: <?php echo number_format($item['price']); ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php
mysqli_close($link);
require_once "templates/footer.php";
?>
