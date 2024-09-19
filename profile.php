<?php
include 'db.php';
session_start();

if (isset($_GET['id'])) {
    $author_id = $_GET['id'];
} else {
    echo "شناسه نویسنده مورد نظر یافت نشد.";
    exit;
}

$sql_author = "SELECT * FROM users WHERE id = $author_id";
$result_author = $conn->query($sql_author);

if ($result_author->num_rows > 0) {
    $row_author = $result_author->fetch_assoc();
    $author_full_name = $row_author['full_name'];
    $author_username = $row_author['username'];
    $author_user_level = $row_author['user_level'];
    $author_profile_picture = $row_author['profile_picture'];
} else {
    echo "اطلاعات نویسنده مورد نظر یافت نشد.";
    exit;
}

$response = [
    'full_name' => $author_full_name,
    'username' => $author_username,
    'user_level' => $author_user_level,
    'profile_picture' => $author_profile_picture ? 'uploads/' . $author_profile_picture : 'default.png'
];

echo json_encode($response);
?>
