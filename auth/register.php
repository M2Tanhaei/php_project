<?php
// فایل اتصال به دیتابیس را فراخوانی می‌کنیم
require_once "../config/database.php";

// چون از هدر مشترک استفاده می‌کنیم، باید آن را بعد از منطق اصلی PHP فراخوانی کنیم
// تا در صورت نیاز به header("location: ...") مشکلی پیش نیاید.
// بنابراین، منطق PHP را ابتدا انجام می‌دهیم.

// متغیرهایی برای نگهداری پیام‌های خطا
$name = $email = $password = "";
$name_err = $email_err = $password_err = "";

// بررسی می‌کنیم که آیا فرم ارسال شده است یا نه
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // اعتبارسنجی نام کاربری
    if (empty(trim($_POST["name"]))) {
        $name_err = "لطفا نام خود را وارد کنید.";
    } else {
        $name = trim($_POST["name"]);
    }

    // اعتبارسنجی ایمیل
    if (empty(trim($_POST["email"]))) {
        $email_err = "لطفا ایمیل خود را وارد کنید.";
    } else {
        // بررسی می‌کنیم که آیا ایمیل قبلا ثبت شده است یا نه
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "این ایمیل قبلا ثبت نام شده است.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "مشکلی پیش آمد. لطفا دوباره تلاش کنید.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // اعتبارسنجی رمز عبور
    if (empty(trim($_POST["password"]))) {
        $password_err = "لطفا رمز عبور را وارد کنید.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "رمز عبور باید حداقل ۶ کاراکتر باشد.";
    } else {
        $password = trim($_POST["password"]);
    }

    // اگر خطایی وجود نداشت، کاربر را در دیتابیس ذخیره می‌کنیم
    if (empty($name_err) && empty($email_err) && empty($password_err)) {

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_password);

            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
                exit; // مهم است که بعد از هدر، اجرای اسکریپت متوقف شود
            } else {
                echo "مشکلی پیش آمد. لطفا دوباره تلاش کنید.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // اتصال به دیتابیس را می‌بندیم
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ثبت نام</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>ثبت نام</h2>
        <p>لطفا برای ایجاد حساب کاربری، فرم زیر را پر کنید.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="name_input">نام</label>
                <input type="text" id="name_input" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
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
                <input type="submit" class="btn btn-primary" value="ثبت نام">
            </div>
            <p>قبلا حساب کاربری ساخته‌اید؟ <a href="login.php">وارد شوید</a>.</p>
        </form>
    </div>
</body>
</html>
