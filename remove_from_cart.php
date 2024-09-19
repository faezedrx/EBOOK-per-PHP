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

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // حذف کتاب از سبد خرید
    $sql = "DELETE FROM carts WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $book_id);
    if ($stmt->execute()) {
        // نمایش پیام sweatalert2 برای موفقیت حذف کتاب
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'عملیات موفق',
                    text: 'کتاب با موفقیت از سبد خرید حذف شد.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'cart.php'; // Redirect به صفحه سبد خرید
                });
              </script>";
    } else {
        // در صورت خطا، نمایش پیام sweatalert2 برای خطا
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در حذف کتاب از سبد خرید: " . $stmt->error . "',
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'cart.php'; // Redirect به صفحه سبد خرید
                });
              </script>";
    }

    $stmt->close();
} elseif (isset($_GET['chapter_id'])) {
    $chapter_id = $_GET['chapter_id'];

    // حذف فصل از سبد خرید
    $sql = "DELETE FROM carts WHERE user_id = ? AND chapter_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $chapter_id);
    if ($stmt->execute()) {
        // نمایش پیام sweatalert2 برای موفقیت حذف فصل
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'عملیات موفق',
                    text: 'فصل با موفقیت از سبد خرید حذف شد.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'cart.php'; // Redirect به صفحه سبد خرید
                });
              </script>";
    } else {
        // در صورت خطا، نمایش پیام sweatalert2 برای خطا
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در حذف فصل از سبد خرید: " . $stmt->error . "',
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'cart.php'; // Redirect به صفحه سبد خرید
                });
              </script>";
    }

    $stmt->close();
} else {
    // اگر book_id و chapter_id مشخص نشده است، نمایش پیام خطا
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'کتاب یا فصل مشخص نشده است.',
                showConfirmButton: true
            }).then(function() {
                window.location.href = 'cart.php'; // Redirect به صفحه سبد خرید
            });
          </script>";
}

$conn->close();
?>
