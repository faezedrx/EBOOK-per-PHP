<?php
include 'db.php';
session_start();

if (!function_exists('secureInput')) {
    function secureInput($data) {
        global $conn;
        return mysqli_real_escape_string($conn, $data);
    }
}

if (!isset($_SESSION['username'])) {
    header("Location: login_register.php");
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

$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$chapter_id = isset($_GET['chapter_id']) ? (int)$_GET['chapter_id'] : 0;

// بررسی وجود کتاب در صورت لزوم
if ($book_id > 0) {
    $sql = "SELECT * FROM books WHERE id = $book_id";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        die("کتاب مورد نظر وجود ندارد.");
    }
}

// بررسی وجود فصل در صورت لزوم
if ($chapter_id > 0) {
    $sql = "SELECT * FROM chapters WHERE id = $chapter_id";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        die("فصل مورد نظر وجود ندارد.");
    }
}

// چک کردن وجود آیتم در سبد خرید
if ($chapter_id > 0) {
    // چک کردن وجود فصل در سبد خرید
    $sql = "SELECT * FROM carts WHERE user_id = ? AND chapter_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $chapter_id);
} else {
    // چک کردن وجود کتاب در سبد خرید
    $sql = "SELECT * FROM carts WHERE user_id = ? AND book_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $book_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: index.php?message=error&error=آیتم قبلاً به سبد خرید اضافه شده است.");
} else {
    if ($chapter_id > 0) {
        // اضافه کردن فصل به سبد خرید
        $sql = "INSERT INTO carts (user_id, book_id, chapter_id) VALUES (?, null, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $chapter_id);
    } else {
        // اضافه کردن کتاب به سبد خرید
        $sql = "INSERT INTO carts (user_id, book_id, chapter_id) VALUES (?, ?, NULL)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $book_id);
    }

    if ($stmt->execute() === FALSE) {
        die('Error: ' . $stmt->error);
    }
    header("Location: index.php?message=success");
}

$conn->close();
?>
