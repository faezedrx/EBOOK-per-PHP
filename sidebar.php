<style>
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        background-color: #ff6b6b;
        border-radius: 0.25rem;
        margin-right: 10px;
    }
</style>
<?php
include 'db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT username, user_level, profile_picture, full_name, national_code, birth_date FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    $current_username = $_SESSION['username'];


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_picture = $row['profile_picture'];
        $user_level = $row['user_level'];
        $full_name = $row['full_name'];
        $national_code = $row['national_code'];
        $birth_date = $row['birth_date'];

        echo "<div class='text-center mb-4'>";
        echo "<img src='uploads/$profile_picture' class='rounded-full w-24 h-24 mx-auto' alt='Profile Picture'>";
        echo "<p class='text-sm text-gray-400'>$user_level</p>";
        echo "<h3 class='mt-2'>$full_name</h3>";
        echo "</div>";

        // دریافت تعداد درخواست‌های جدید برای پابلیشر و استاد
        $new_requests_count = 0;
        $new_lecture_requests_count = 0;

        if ($user_level == 'پابلیشر') {
            $sql_new_requests = "SELECT COUNT(*) as new_requests FROM books WHERE is_approved = 0";
            $result_new_requests = $conn->query($sql_new_requests);
            if ($result_new_requests->num_rows > 0) {
                $row_new_requests = $result_new_requests->fetch_assoc();
                $new_requests_count = $row_new_requests['new_requests'];
            }
        }

        if ($user_level == 'استاد') {
            $sql_new_lecture_requests = "SELECT COUNT(*) as new_lecture_requests FROM books WHERE is_approved = 0 AND genre ='جزوه' AND books.author != '$current_username' ";
            $result_new_lecture_requests = $conn->query($sql_new_lecture_requests);
            if ($result_new_lecture_requests->num_rows > 0) {
                $row_new_lecture_requests = $result_new_lecture_requests->fetch_assoc();
                $new_lecture_requests_count = $row_new_lecture_requests['new_lecture_requests'];
            }
        }

        echo "<ul class='nav flex-column'>";
        include 'user_options.php';
        display_user_options($user_level, $new_requests_count, $new_lecture_requests_count);
        echo "</ul>";
    }
} else {
    header("Location: login_register.php");
    exit;
}
?>
