<?php
// session را در ابتدای اسکریپت شروع می‌کنیم
session_start();

// اگر کاربر از قبل لاگین کرده بود، او را به صفحه محصولات هدایت می‌کنیم
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../products/index.php");
    exit;
}

// فایل اتصال به دیتابیس را فراخوانی می‌کنیم
require_once "../config/database.php";

// متغیرهایی برای نگهداری پیام‌های خطا
$email = $password = "";
$email_err = $password_err = "";

// بررسی می‌کنیم که آیا فرم ارسال شده است یا نه
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["email"]))) {
        $email_err = "لطفا ایمیل خود را وارد کنید.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "لطفا رمز عبور خود را وارد کنید.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        // ستون role را نیز انتخاب می‌کنیم
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // role را نیز bind می‌کنیم
                    mysqli_stmt_bind_result($stmt, $id, $name, $email, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role; // ذخیره نقش کاربر در session

                            header("location: ../products/index.php");
                            exit;
                        } else {
                            $password_err = "رمز عبور وارد شده صحیح نیست.";
                        }
                    }
                } else {
                    $email_err = "کاربری با این ایمیل یافت نشد.";
                }
            } else {
                echo "مشکلی پیش آمد. لطفا دوباره تلاش کنید.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ورود</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>ورود</h2>
        <p>لطفا اطلاعات خود را برای ورود وارد کنید.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email_input">ایمیل</label>
                <input type="email" id="email_input" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password_input">رمز عبور</label>
                <input type="password" id="password_input" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="ورود">
            </div>
            <p>حساب کاربری ندارید؟ <a href="register.php">همین حالا ثبت نام کنید</a>.</p>
        </form>
    </div>
</body>
</html>
