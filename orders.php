<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_register.php");
    exit();
}

$username = $_SESSION['username'];
$sql_get_user_id = "SELECT id FROM users WHERE username='$username'";
$result = $conn->query($sql_get_user_id);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
    $_SESSION['user_id'] = $user_id;
} else {
    echo "کاربر یافت نشد.";
    exit();
}

// دریافت لیست سفارشات
$sql_orders = "
SELECT 'book' AS type, books.id, books.title, books.author, books.price, books.pdf_path, books.book_cover_picture, NULL AS book_id
FROM orders
INNER JOIN books ON orders.book_id = books.id
WHERE orders.user_id='$user_id'
UNION
SELECT 'chapter' AS type, chapters.id, chapters.title, books.author, chapters.price, chapters.pdf_path, books.book_cover_picture, chapters.book_id AS book_id
FROM orders
INNER JOIN chapters ON orders.chapter_id = chapters.id
INNER JOIN books ON chapters.book_id = books.id
WHERE orders.user_id='$user_id'
";

$result_orders = $conn->query($sql_orders);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سفارش‌های من</title>
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <link rel="stylesheet" type="text/css" href="styles/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
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
<?php
    if ($result_orders->num_rows > 0):?>
<div class="w-full max-w-4xl p-6 rounded-lg mt-10">
    <h1 class="text-3xl font-bold mb-6 text-center text-green-500">سفارش‌های من</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <?php while ($row_orders = $result_orders->fetch_assoc()):?>
                <div class="card bg-white p-6 rounded-lg shadow-lg" style="text-align: center;">
                    <?php if (!empty($row_orders['book_cover_picture'])): ?>
                        <img class="w-full h-64 object-cover mb-4 rounded" src="uploads/<?php echo htmlspecialchars($row_orders['book_cover_picture']); ?>" alt="<?php echo htmlspecialchars($row_orders['title']); ?>">
                    <?php endif; ?>
                    <div class="card-content">
                        <h2 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($row_orders['title']); ?></h2>
                        <p class="mb-2"><strong>نویسنده:</strong> <?php echo htmlspecialchars($row_orders['author']); ?></p>
                        <p class="mb-2"><strong>قیمت:</strong> <?php echo htmlspecialchars($row_orders['price']); ?> تومان</p>
                    </div>
                    <?php if ($row_orders['type'] == 'book'): ?>
                        <a class="inline-block py-2 px-4 bg-brown hover:bg-yellow-700 text-white font-bold rounded-md shadow-lg transition duration-300 mb-2" href="view_book.php?id=<?php echo $row_orders['id']; ?>">مشاهده</a>
                    <?php elseif (!empty($row_orders['book_id'])): ?>
                        <a class="inline-block py-2 px-4 bg-brown hover:bg-yellow-700 text-white font-bold rounded-md shadow-lg transition duration-300 mb-2" href="view_book.php?id=<?php echo $row_orders['book_id']; ?>">مشاهده</a>
                    <?php endif; ?>
                    <a class="inline-block py-2 px-4 bg-red-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300" href="<?php echo htmlspecialchars($row_orders['pdf_path']) ?>" download>دانلود</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500">سفارشی ثبت نکرده اید</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php
$conn->close();
?>
