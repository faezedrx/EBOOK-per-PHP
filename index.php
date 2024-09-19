<?php
    include 'db.php';
    session_start();
    $user_level = null;
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sql = "SELECT user_level FROM users WHERE username='" . secureInput($username) . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_level = $row['user_level'];
        }
    }
    $result2 = $conn->query("SELECT id, book_cover_picture, title, genre, author, price, total_pages FROM books WHERE is_approved = 1");
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خانه</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaHpyNeuaal5h/pyQ4g/6hVSrtPQ5hzZ3AlM13" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

    <style>
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }

        .card:nth-child(1) { animation-delay: 0s; }
        .card:nth-child(2) { animation-delay: 0.1s; }
        .card:nth-child(3) { animation-delay: 0.2s; }
        .card:nth-child(4) { animation-delay: 0.3s; }
        .card:nth-child(5) { animation-delay: 0.4s; }
        .card:nth-child(6) { animation-delay: 0.5s; }

        .btn-animate {
            transition: transform 0.2s;
        }

        .btn-animate:hover {
            transform: scale(1.1);
        }

        .card img {
            transition: transform 0.5s;
        }

        .card img:hover {
            transform: scale(1.05);
        }

        .bg-brown {
            background-color: #8B4513; /* رنگ قهوه‌ای */
        }
        .bg-brow {
            background-color: #d2a660; 
        }
    </style>
</head>
<?php
    include 'header.php';
?>
<body class="min-h-screen flex flex-col items-center">
    <div class="w-full max-w-6xl p-6">
        <!-- <h1 class="text-3xl font-bold mb-6 text-center text-green-500">لیست کتاب‌ها</h1> -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($row = $result2->fetch_assoc()): ?>
                <div class="card bg-white p-6 rounded-lg shadow-lg" >
                    <?php if (!empty($row['book_cover_picture'])): ?>
                        <img class="w-full h-64 object-cover mb-4 rounded" src="uploads/<?php echo htmlspecialchars($row['book_cover_picture']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <?php else: ?>
                        <!-- <img class="w-full h-64 object-cover mb-4 rounded" src="default_cover.jpg" alt="Default Cover"> -->
                    <?php endif; ?>
                    <div class="card-content" style="text-align: right;">
                        <h2 class="text-xl font-bold mb-2" style="text-align: center;"><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p class="mb-2"><strong>ژانر:</strong> <?php echo htmlspecialchars($row['genre']); ?></p>
                        <p class="mb-2"><strong>نویسنده:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                        <p class="mb-4"><strong>تعداد صفحات:</strong> <?php echo htmlspecialchars($row['total_pages']); ?></p>
                        <p class="mb-2"><strong>قیمت:</strong> <?php echo htmlspecialchars($row['price']); ?> تومان</p>
                    </div>
                    <a class="inline-block py-2 px-4 bg-brown hover:bg-yellow-700 text-white font-bold rounded-md shadow-lg transition duration-300 mb-2 btn-animate" href="view_book.php?id=<?php echo $row['id']; ?>">مشاهده</a>
                    <?php if ((isset($_SESSION['username'])) && $user_level != 'پابلیشر' ): ?>
                        <a class="inline-block py-2 px-4 bg-brow hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 btn-animate" href="add_to_cart.php?id=<?php echo $row['id']; ?>">اضافه به سبد خرید</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php
    include 'login_register.php';
?>
</body>
</html>

<?php
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    if ($message == 'success') {
        echo "<script>
        Swal.fire({
            title: 'کتاب با موفقیت به سبد خرید اضافه شد!',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'رفتن به سبد خرید',
            cancelButtonText: 'ادامه خرید',
            customClass: {
                popup: 'animated tada'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'cart.php';
            }
        });
        </script>";
    } elseif ($message == 'error') {
        $error = isset($_GET['error']) ? $_GET['error'] : 'خطایی رخ داد.';
        echo "<script>
        Swal.fire({
            title: 'خطا',
            text: '$error',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'رفتن به سبد خرید',
            cancelButtonText: 'ادامه خرید',
            customClass: {
                popup: 'animated shake'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'cart.php';
            }
        });
        </script>";
    }
}
?>

<script>
    document.getElementById('open-popup').addEventListener('click', () => {
        document.getElementById('popup-modal').style.display = 'flex';
    });

    document.getElementById('popup-modal').addEventListener('click', (event) => {
        if (event.target === document.getElementById('popup-modal')) {
            document.getElementById('popup-modal').style.display = 'none';
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('showLogin')) {
        document.getElementById('popup-modal').style.display = 'flex';
    }
</script>

<script>
    document.getElementById('forgot-password-link').addEventListener('click', function() {
        document.getElementById('login-form').classList.add('hidden');
        document.getElementById('register-form').classList.add('hidden');
        document.getElementById('forgot-password-form').classList.remove('hidden');
        document.getElementById('form-title').innerText = 'فراموشی رمز عبور';
    });

    document.getElementById('toggle-button').addEventListener('click', function() {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const forgotPasswordForm = document.getElementById('forgot-password-form');
        if (loginForm.classList.contains('hidden')) {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            forgotPasswordForm.classList.add('hidden');
            document.getElementById('form-title').innerText = 'ورود';
            this.innerText = 'ثبت نام کنید';
        } else {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            forgotPasswordForm.classList.add('hidden');
            document.getElementById('form-title').innerText = 'ثبت نام';
            this.innerText = 'ورود';
        }
    });
</script>

    <?php
    // آزادسازی منابع و بستن اتصال
    $result2->free();
    $conn->close();
    ?>
</body>

</html>
