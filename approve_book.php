<?php
include 'db.php';
session_start();
// if (!isset($_SESSION['username']) || $_SESSION['user_level'] !== 'پابلیشر') {
//     header("Location: login_register.htmlphp");
//     exit;
// }

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $sql_approve = "UPDATE books SET is_approved = 1 WHERE id = $book_id"; // تأیید کتاب با استفاده از شناسه آن
    if ($conn->query($sql_approve) === TRUE) {
        header("Location: requests.php"); // انتقال به صفحه درخواست‌ها بعد از تأیید کتاب
        exit;
    } else {
        echo "خطا در تأیید کتاب: " . $conn->error;
    }
}

$conn->close();
?>
