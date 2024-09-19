<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درخواست‌ها</title>
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaHpyNeuaal5h/pyQ4g/6hVSrtPQ5hzZ3AlM13" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="min-h-screen flex flex-col items-center">
<div class="sidebar">
    <?php include 'sidebar.php'; ?>
</div>
<a href="index.php" class="text-brown-600 text-2xl font-extrabold top-left"> Ebook </a>
<div class="w-full max-w-6xl p-12 bg-white shadow-lg rounded-lg mt-12 transform -translate-x-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-green-500">درخواست‌ها</h1>

    <?php
    $current_username = $_SESSION['username'];
    $user_level = $_SESSION['user_level'];

    if ($user_level === 'استاد') {
        $sql_requests = "SELECT books.*, users.id as user_id FROM books 
                         INNER JOIN users ON books.author = users.username
                         WHERE books.is_approved = 0 AND books.genre = 'جزوه' AND books.author != '$current_username'";
    } else {
        $sql_requests = "SELECT books.*, users.id as user_id FROM books 
                         INNER JOIN users ON books.author = users.username 
                         WHERE books.is_approved = 0 AND books.author != '$current_username'";
    }

    $result_requests = $conn->query($sql_requests);

    if ($result_requests->num_rows > 0) {
        echo "<table class='table-auto w-full border-collapse border border-gray-400 mb-6'>";
        echo "<thead>";
        echo "<tr class='bg-gray-200'>";
        echo "<th class='px-4 py-2'>عکس جلد</th>";
        echo "<th class='px-4 py-2'>عنوان</th>";
        echo "<th class='px-4 py-2'>ژانر</th>";
        echo "<th class='px-4 py-2'>نویسنده</th>";
        echo "<th class='px-4 py-2'>خلاصه</th>";
        echo "<th class='px-4 py-2'>توضیحات</th>";
        echo "<th class='px-4 py-2'>قیمت</th>";
        echo "<th class='px-4 py-2'>تعداد صفحات</th>";
        echo "<th class='px-4 py-2'>مشاهده </th>";
        echo "<th class='px-4 py-2'>فایل PDF</th>";
        echo "<th class='px-4 py-2'>توضیحات پابلیشر</th>";
        echo "<th class='px-4 py-2'>عملیات</th>";
        echo "<th class='px-4 py-2'>پروفایل نویسنده</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row_requests = $result_requests->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='border px-4 py-2'><img src='uploads/{$row_requests['book_cover_picture']}' alt='{$row_requests['title']}' class='w-20 h-20 object-cover'></td>";
            echo "<td class='border px-4 py-2'>{$row_requests['title']}</td>";
            echo "<td class='border px-4 py-2'>{$row_requests['genre']}</td>";
            echo "<td class='border px-4 py-2'>{$row_requests['author']}</td>";
            echo "<td class='border px-4 py-2'>{$row_requests['summary']}</td>";
            echo "<td class='border px-4 py-2'>{$row_requests['description']}</td>";
            echo "<td class='border px-4 py-2'>{$row_requests['price']}</td>";
            echo "<td class='border px-4 py-2'>{$row_requests['total_pages']}</td>";
            echo "<td class='border px-4 py-2'><a href='view_book.php?id={$row_requests['id']}' class='text-blue-500' target='_blank'>مشاهده</a></td>";
            echo "<td class='border px-4 py-2'><a href='{$row_requests['pdf_path']}' class='text-blue-500' target='_blank'>دانلود</a></td>";
            echo "<td class='border px-4 py-2'>";
            echo "<form action='update_comments.php' method='POST' class='update-comment-form'>";
            echo "<input type='hidden' name='id' value='{$row_requests['id']}'>";
            echo "<textarea name='publisher_comments' class='border px-2 py-1 w-full'>{$row_requests['publisher_comments']}</textarea>";
            echo "<button type='submit' class='mt-2 text-green-500 save-comment'>ذخیره</button>";
            echo "</form>";
            echo "</td>";
            echo "<td class='border px-4 py-2 flex flex-col items-center'>";
            echo "<a href='#' class='text-green-500 mb-2 approve-book' data-id='{$row_requests['id']}'>تأیید</a>";
            echo "<form action='reject_book.php' method='POST' class='reject-book-form'>";
            echo "<input type='hidden' name='id' value='{$row_requests['id']}'>";
            echo "<button type='submit' class='text-red-500 reject-book'>رد کردن</button>";
            echo "</form>";
            echo "</td>";
            echo "<td class='border px-4 py-2 text-center'>";
            echo "<a href='#' class='text-blue-500 profile-link' data-id='{$row_requests['user_id']}'><i class='fas fa-user'></i> مشاهده پروفایل</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p class='text-gray-700'>در حال حاضر هیچ درخواستی برای نمایش وجود ندارد.</p>";
    }
    ?>
</div>

<!-- پاپ‌آپ -->
<div class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center hidden" id="profile-popup">
    <div class="bg-white p-8 rounded-lg max-w-md">
        <div class="flex justify-end">
            <button class="text-xl font-bold cursor-pointer" id="close-popup">&times;</button>
        </div>
        <div class="flex flex-col items-center" id="profile-content">
            <!-- محتوای پروفایل از Ajax بارگذاری می‌شود -->
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.profile-link').click(function(e) {
        e.preventDefault();
        var userId = $(this).data('id');
        
        $.ajax({
            url: 'profile.php',
            type: 'GET',
            data: { id: userId },
            success: function(response) {
                var data = JSON.parse(response);
                var profileContent = `
                    <img src="${data.profile_picture}" alt="${data.full_name}" class="profile-picture">
                    <h2 class="text-2xl font-bold mt-4">${data.full_name}</h2>
                    <p class="mt-2">نام کاربری: ${data.username}</p>
                    <p>سطح کاربری: ${data.user_level}</p>
                `;
                $('#profile-content').html(profileContent);
                $('#profile-popup').removeClass('hidden');
            }
        });
    });

    $('#close-popup').click(function() {
        $('#profile-popup').addClass('hidden');
    });

    $('.update-comment-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        Swal.fire({
            title: 'آیا مطمئن هستید؟',
            text: "این عملیات قابل برگشت نیست!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'بله، ذخیره کن!',
            cancelButtonText: 'لغو'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('update_comments.php', formData, function(response) {
                    Swal.fire(
                        'ذخیره شد!',
                        'توضیحات با موفقیت ذخیره شد.',
                        'success'
                    );
                });
            }
        });
    });

    $('.approve-book').click(function(e) {
        e.preventDefault();
        var bookId = $(this).data('id');

        Swal.fire({
            title: 'آیا مطمئن هستید؟',
            text: "این کتاب تأیید خواهد شد!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'بله، تأیید کن!',
            cancelButtonText: 'لغو'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'approve_book.php?id=' + bookId;
            }
        });
    });

    $('.reject-book-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        Swal.fire({
            title: 'آیا مطمئن هستید؟',
            text: "این کتاب رد خواهد شد!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'بله، رد کن!',
            cancelButtonText: 'لغو'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('reject_book.php', formData, function(response) {
                    Swal.fire(
                        'رد شد!',
                        'کتاب با موفقیت رد شد.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                });
            }
        });
    });
});
</script>

</body>
</html>
