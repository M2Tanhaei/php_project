<?php
require_once "../templates/header.php";
require_once "../config/database.php";

$sql = "SELECT * FROM products";
$result = mysqli_query($link, $sql);
?>

<div class="container">
    <?php
    // نمایش پیام موفقیت یا خطا
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        // پاک کردن پیام بعد از نمایش
        unset($_SESSION['message']);
    }
    ?>
    <div class="products-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product-card'>";
                echo "<img src='../assets/images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<div class='price'>" . number_format($row['price']) . " تومان</div>";
                // فرم برای افزودن به سبد خرید
                echo "<form action='../cart/add_to_cart.php' method='post'>";
                echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                echo "<button type='submit' class='btn btn-primary'>افزودن به سبد خرید</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>هیچ محصولی برای نمایش وجود ندارد.</p>";
        }
        ?>
    </div>
</div>

<?php
mysqli_close($link);
require_once "../templates/footer.php";
?>
