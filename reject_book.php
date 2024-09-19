<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'موفقیت!',
                text: 'درخواست با موفقیت رد شد.',
                icon: 'success',
                confirmButtonText: 'باشه'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'requests.php';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'خطا!',
                text: 'خطایی در رد کردن درخواست رخ داد: " . $conn->error . "',
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
