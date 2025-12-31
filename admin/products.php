<?php
require_once "templates/header.php";
require_once "../config/database.php";

$message = "";

// پردازش فرم افزودن محصول
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    if (!empty($name) && !empty($description) && !empty($price) && !empty($image)) {
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssds", $name, $description, $price, $image);
            if (mysqli_stmt_execute($stmt)) {
                $message = "محصول با موفقیت اضافه شد.";
            } else {
                $message = "خطا در افزودن محصول.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $message = "لطفا تمام فیلدها را پر کنید.";
    }
}

// پردازش درخواست حذف محصول
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $sql = "DELETE FROM products WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        if (mysqli_stmt_execute($stmt)) {
            $message = "محصول با موفقیت حذف شد.";
        } else {
            $message = "خطا در حذف محصول.";
        }
        mysqli_stmt_close($stmt);
    }
}

// واکشی لیست محصولات
$products = [];
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($link, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<h3>افزودن محصول جدید</h3>
<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>
<form action="products.php" method="post" class="wrapper" style="width: 100%; max-width: 600px; margin-bottom: 30px;">
    <input type="hidden" name="add_product">
    <div class="form-group">
        <label for="name_input">نام محصول</label>
        <input type="text" id="name_input" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="desc_input">توضیحات</label>
        <textarea id="desc_input" name="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="price_input">قیمت (تومان)</label>
        <input type="number" id="price_input" step="0.01" name="price" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="image_input">نام فایل تصویر (مثال: 5.jpg)</label>
        <input type="text" id="image_input" name="image" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">افزودن</button>
</form>

<hr>

<h3>لیست محصولات</h3>
<table class="cart-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>تصویر</th>
            <th>نام</th>
            <th>قیمت</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" width="50"></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo number_format($product['price']); ?> تومان</td>
                <td>
                    <form action="products.php" method="post" onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟');">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm">حذف</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
mysqli_close($link);
require_once "templates/footer.php";
?>
