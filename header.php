<?php
include 'db.php';
$user_level = null;
$username = null;
$user_id = null;
$cart_item_count = 0;

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // دریافت سطح کاربر و شناسه کاربر
    $sql = "SELECT id, user_level FROM users WHERE username='" . secureInput($username) . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_level = $row['user_level'];
        $user_id = $row['id'];
    }

    // دریافت تعداد آیتم‌های سبد خرید
    if ($user_id !== null) {
        $sql_cart = "SELECT COUNT(*) as item_count FROM carts WHERE user_id='" . $user_id . "'";
        $result_cart = $conn->query($sql_cart);
        if ($result_cart->num_rows > 0) {
            $row_cart = $result_cart->fetch_assoc();
            $cart_item_count = $row_cart['item_count'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazir', sans-serif;
            background-color: #f5f5dc; /* رنگ بژ */
        }
        .sticky-header {
            position: -webkit-sticky; /* Safari */
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #d2b48c; /* رنگ قهوه‌ای روشن */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-radius: 0 0 10px 10px;
        }
        .sticky-header:hover {
            background-color: #deb887; /* رنگ بژ */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .navbar-link {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s;
        }
        .navbar-link:hover {
            background-color: #f5f5dc; /* رنگ بژ */
        }
        .badge {
            background-color: green;
            color: white;
            padding: 0.2em 0.6em;
            border-radius: 50%;
            font-size: 0.8em;
            margin-left: -10px;
            margin-top: -10px;
        }
        .material-icons {
            font-size: 24px; /* Adjust this to make icons larger or smaller */
            transition: color 0.3s;
        }
        .material-icons:hover {
            color: #0d9732; /* رنگ سبز برای هاور */
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <header class="sticky-header w-full p-3 flex justify-between items-center shadow-md px-6">
        <div class="flex items-center space-x-4"> <!-- فاصله بین آیتم‌ها در اینجا تنظیم می‌شود -->
            <a href="index.php" class="text-brown-600 text-2xl font-extrabold">Ebook</a>
            <nav class="hidden md:flex space-x-4"> <!-- فاصله بین آیتم‌های ناوبری -->
                <a href="index.php" class="navbar-link text-gray-700 hover:text-brown-600">خانه</a>
                <!-- <a href="about.php" class="navbar-link text-gray-700 hover:text-brown-600">درباره ما</a> -->
                <!-- <a href="contact.php" class="navbar-link text-gray-700 hover:text-brown-600">تماس با ما</a> -->
            </nav>
        </div>
        <div class="flex items-center space-x-12"> <!-- فاصله بین آیتم‌های سمت راست -->
            <?php if (!isset($_SESSION['username'])): ?>
                <button id="open-popup" class="btn bg-green-600 hover:bg-brown-800 text-white font-bold rounded-md shadow-lg">ورود/ ثبت نام</button>
            <?php endif; ?>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php" class="text-gray-700 hover:text-brown-600 flex items-center">
                    <span class="material-icons">logout</span>
                </a>
                
                <?php if ($user_level != 'پابلیشر'): ?>
                    <a href="cart.php" class="text-gray-700 hover:text-brown-600 flex items-center relative">
                        <span class="material-icons">shopping_cart</span>
                        <?php if ($cart_item_count > 0): ?>
                            <span class="badge"><?php echo $cart_item_count; ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
                <a href="dashboard.php" class="text-gray-700 hover:text-brown-600 flex items-center">
                    <span class="material-icons">person</span>
                </a>
                <span class="text-gray-700 flex items-center"> خوش آمدید <?php echo htmlspecialchars($username); ?> </span>
            <?php endif; ?>
        </div>
    </header>
</body>
</html>
