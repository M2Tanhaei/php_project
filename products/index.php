<?php
// فایل هدر را فراخوانی می‌کنیم که session_start() را نیز انجام می‌دهد.
require_once "../templates/header.php";
// فایل اتصال به دیتابیس
require_once "../config/database.php";

// تمام محصولات را از دیتابیس انتخاب می‌کنیم
$sql = "SELECT * FROM products";
$result = mysqli_query($link, $sql);
?>

<div class="products-container">
    <?php
    // بررسی می‌کنیم که آیا محصولی برای نمایش وجود دارد یا نه
    if (mysqli_num_rows($result) > 0) {
        // با استفاده از حلقه، هر محصول را نمایش می‌دهیم
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product-card'>";
            echo "<img src='../assets/images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<div class='price'>" . number_format($row['price']) . " تومان</div>";
            // دکمه خرید که کاربر را به صفحه سفارش هدایت می‌کند
            echo "<a href='order.php?product_id=" . $row['id'] . "' class='btn btn-primary'>خرید</a>";
            echo "</div>";
        }
    } else {
        echo "<p>هیچ محصولی برای نمایش وجود ندارد.</p>";
    }
    ?>
</div>

<?php
// اتصال به دیتابیس را می‌بندیم
mysqli_close($link);
// فایل فوتر را فراخوانی می‌کنیم
require_once "../templates/footer.php";
?>
