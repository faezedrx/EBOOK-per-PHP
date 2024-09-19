<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کتاب‌های / جزوات من</title>
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaHpyNeuaal5h/pyQ4g/6hVSrtPQ5hzZ3AlM13" crossorigin="anonymous">
    <style>
        .bg-brown {
            background-color: #8B4513; /* رنگ قهوه‌ای */
        }
        .bg-brow {
            background-color: #d2a660; 
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center">
<div class="sidebar">
    <?php include 'sidebar.php'; ?>
</div>
<a href="index.php" class="text-brown-600 text-2xl font-extrabold top-left"> Ebook </a>
<div class="w-full max-w-4xl p-6 bg-white shadow-lg rounded-lg mt-8">
    <h1 class="text-2xl font-bold mb-4 text-green-500 text-center">
        <?php
        if ($user_level === 'نویسنده') {
            echo 'کتاب‌های من';
        } elseif ($user_level === 'دانشجو') {
            echo 'جزوات من';
        } elseif ($user_level === 'استاد') {
            echo 'کتاب / جزوات من';
        }
        ?>
    </h1>
    <?php include 'createBookForm.php'; ?>
    <!-- <button id="editBookBtn" class="py-2 px-4 bg-brow hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300">ویرایش کتاب</button> -->

    <!-- درج فایل فرم ویرایش به صورت پاپ‌آپ -->
    <!-- <?php include 'editBookForm.php'; ?> -->
    <?php include 'view_my_book.php'; ?>
</div>

<script>
    // نمایش و مخفی کردن پاپ‌آپ فرم ویرایش کتاب
    document.getElementById('editBookBtn').addEventListener('click', function() {
        document.getElementById('editBookModal').classList.toggle('hidden');
    });
    document.getElementById('cancelEditBtn').addEventListener('click', function() {
        document.getElementById('editBookModal').classList.add('hidden');
    });
</script>
    
</body>
</html>