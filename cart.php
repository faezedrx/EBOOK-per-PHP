<?php
include 'db.php';
session_start();

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

// بازیابی کتاب‌ها و فصل‌های موجود در سبد خرید
$sql = "SELECT DISTINCT b.id AS book_id, b.title AS book_title, b.genre AS book_genre, b.author AS book_author, b.price AS book_price, b.total_pages AS book_total_pages, 
        c.id AS chapter_id, c.title AS chapter_title, c.genre AS chapter_genre, c.price AS chapter_price, c.book_id AS chapter_book_id
        FROM carts ca 
        LEFT JOIN books b ON ca.book_id = b.id 
        LEFT JOIN chapters c ON ca.chapter_id = c.id
        WHERE ca.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result2 = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سبد خرید</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaHpyNeuaal5h/pyQ4g/6hVSrtPQ5hzZ3AlM13" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Vazir', sans-serif;
            background-color: #e8f5e9;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center">
<div class="sidebar">
<?php
include 'sidebar.php';
?>
</div>
<a href="index.php" class="text-brown-600 text-2xl font-extrabold top-left"> Ebook </a>
    <div class="w-full max-w-4xl p-6 bg-white shadow-lg rounded-lg mt-8 ">
        <h1 class="text-3xl font-bold mb-6 text-center text-green-500">سبد خرید</h1>
        <?php if ($result2->num_rows > 0): ?>
            <table class="table-auto w-full border-collapse border border-gray-400 mb-6">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">عنوان</th>
                        <th class="px-4 py-2">ژانر</th>
                        <th class="px-4 py-2">نویسنده</th>
                        <th class="px-4 py-2">قیمت</th>
                        <th class="px-4 py-2">تعداد صفحات</th>
                        <th class="px-4 py-2">نوع آیتم</th>
                        <th class="px-4 py-2">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result2->fetch_assoc()): ?>
                        <?php if ($row['book_id']): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['book_title']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['book_genre']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['book_author']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['book_price']); ?> تومان</td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['book_total_pages']); ?></td>
                                <td class="border px-4 py-2">کتاب</td>
                                <td class="border px-4 py-2">
                                    <a href="#" data-book-id="<?php echo $row['book_id']; ?>" class="text-red-500 hover:text-red-700 delete-book">حذف</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($row['chapter_id']): ?>
                            <?php
                            // بازیابی نام نویسنده فصل از جدول کتاب‌ها
                            $chapter_book_id = $row['chapter_book_id'];
                            $author_sql = "SELECT author FROM books WHERE id = ?";
                            $author_stmt = $conn->prepare($author_sql);
                            $author_stmt->bind_param("i", $chapter_book_id);
                            $author_stmt->execute();
                            $author_result = $author_stmt->get_result();
                            $author_row = $author_result->fetch_assoc();
                            $chapter_author = $author_row['author'];
                            ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['chapter_title']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['chapter_genre']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($chapter_author); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['chapter_price']); ?> تومان</td>
                                <td class="border px-4 py-2">-</td>
                                <td class="border px-4 py-2">فصل</td>
                                <td class="border px-4 py-2">
                                    <a href="#" data-chapter-id="<?php echo $row['chapter_id']; ?>" class="text-red-500 hover:text-red-700 delete-chapter">حذف</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a class="inline-block py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 mt-4" href="checkout.php">پرداخت</a>
        <?php else: ?>
            <p class="text-center text-gray-500">سبد خرید شما خالی است.</p>
        <?php endif; ?>
    </div>
</body>
</html>

    <?php
    //آزادسازی منابع
    $result2->free();
    $conn->close();
    ?>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteBookLinks = document.querySelectorAll('.delete-book');
            deleteBookLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const bookId = this.getAttribute('data-book-id');
                    Swal.fire({
                        icon: 'question',
                        title: 'آیا از حذف کتاب اطمینان دارید؟',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'بله، حذف کن!',
                        cancelButtonText: 'انصراف'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ارسال درخواست حذف به صفحه remove_from_cart.php با استفاده از AJAX
                            const url = `remove_from_cart.php?book_id=${bookId}`;
                            fetch(url, {
                                method: 'GET'
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.text();
                                }
                                throw new Error('Network response was not ok.');
                            })
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'عملیات موفق',
                                    // text: data,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // بازنشانی وضعیت صفحه یا به‌روزرسانی محتوا سبد خرید
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطا',
                                    text: `خطا در حذف کتاب: ${error.message}`
                                });
                            });
                        }
                    });
                });
            });

            const deleteChapterLinks = document.querySelectorAll('.delete-chapter');
            deleteChapterLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const chapterId = this.getAttribute('data-chapter-id');
                    Swal.fire({
                        icon: 'question',
                        title: 'آیا از حذف فصل اطمینان دارید؟',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'بله، حذف کن!',
                        cancelButtonText: 'انصراف'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ارسال درخواست حذف به صفحه remove_from_cart.php با استفاده از AJAX
                            const url = `remove_from_cart.php?chapter_id=${chapterId}`;
                            fetch(url, {
                                method: 'GET'
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.text();
                                }
                                throw new Error('Network response was not ok.');
                            })
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'عملیات موفق',
                                    // text: data,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // بازنشانی وضعیت صفحه یا به‌روزرسانی محتوا سبد خرید
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطا',
                                    text: `خطا در حذف فصل: ${error.message}`
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
