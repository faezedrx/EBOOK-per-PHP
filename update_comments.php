<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $publisher_comments = $_POST['publisher_comments'];

    $sql_update = "UPDATE books SET publisher_comments = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('si', $publisher_comments, $id);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'موفقیت!',
                text: 'توضیحات با موفقیت به‌روزرسانی شد.',
                icon: 'success',
                confirmButtonText: 'باشه'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'requests.php';
                }
            });
        </script>";
        header("Location: requests.php");
    } else {
        echo "<script>
            Swal.fire({
                title: 'خطا!',
                text: 'خطایی در به‌روزرسانی رکورد رخ داد: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'باشه'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'requests.php';
                }
            });
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>
