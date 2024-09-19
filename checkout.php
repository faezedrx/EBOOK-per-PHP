<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_register.html");
    exit;
}

$username = $_SESSION['username'];
$user_id = null;
$sql = "SELECT id FROM users WHERE username='" . secureInput($username) . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
}

// انتقال کتاب‌ها از سبد خرید به جدول orders
$sql_insert_orders = "INSERT INTO orders (user_id, book_id , chapter_id)
                    SELECT user_id, book_id , chapter_id FROM carts WHERE user_id = ?";
$stmt_insert_orders = $conn->prepare($sql_insert_orders);
$stmt_insert_orders->bind_param("i", $user_id);

// پاک کردن سبد خرید
$sql_delete_cart = "DELETE FROM carts WHERE user_id = ?";
$stmt_delete_cart = $conn->prepare($sql_delete_cart);
$stmt_delete_cart->bind_param("i", $user_id);

// اجرای دستورات و بررسی موفقیت
$success = true;
$conn->autocommit(false); // شروع تراکنش

if (!$stmt_insert_orders->execute()) {
    $success = false;
}

if (!$stmt_delete_cart->execute()) {
    $success = false;
}

if ($success) {
    $conn->commit(); // تایید تراکنش
    // echo "خرید با موفقیت انجام شد.";
} else {
    $conn->rollback(); // لغو تراکنش در صورت خطا
    // echo "خطا در انجام خرید.";
}

$stmt_insert_orders->close();
$stmt_delete_cart->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // نمایش پیغام با SweetAlert
        Swal.fire({
            icon: 'success',
            title: 'پرداخت موفق',
            text: 'خرید با موفقیت انجام شد.',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = 'orders.php'; // انتقال به صفحه سفارشات
        });
    });
</script>
</body>
</html>
