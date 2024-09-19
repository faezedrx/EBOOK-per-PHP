<?php
include 'db.php';
session_start();

$user_level = null;
$username = null;

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT user_level FROM users WHERE username='" . secureInput($username) . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_level = $row['user_level'];
    }
}

// Function to check if the book or chapter is in the user's orders
function isPurchased($conn, $userId, $bookId = null, $chapterId = null) {
    $query = "SELECT id FROM orders WHERE user_id = ? AND ";
    if ($bookId !== null) {
        $query .= "book_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $bookId);
    } elseif ($chapterId !== null) {
        $query .= "chapter_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $chapterId);
    } else {
        return false;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $isPurchased = $result->num_rows > 0;
    $stmt->close();
    return $isPurchased;
}

// Get the user ID
$userId = null;
if ($username !== null) {
    $sql = "SELECT id FROM users WHERE username='" . secureInput($username) . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];
    }
}

$book_id = $_GET['id'];

// Fetch book information
$stmt = $conn->prepare("SELECT book_cover_picture, title, genre, summary, description, author, price, total_pages FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$stmt->bind_result($book_cover_picture, $title, $genre, $summary, $description, $author, $price, $total_pages);
$stmt->fetch();
$stmt->close();

// Fetch chapter information
$stmt = $conn->prepare("SELECT * FROM chapters WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$chapters = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشاهده کتاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaHpyNeuaal5h/pyQ4g/6hVSrtPQ5hzZ3AlM13" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/view_book.css" />

    <style>
        body {
            font-family: 'Vazir', sans-serif;
            background-color: #e8f5e9;
        }
        .card {
            border: 2px solid #d2a679;
            position: relative;
            overflow: hidden;
            padding-top: 16px;
            padding-bottom: 16px;
            background-color: #fff8f0;
        }
        .card::before, .card::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #d2a679, #fff8f0, #d2a679);
        }
        .card::before {
            top: 0;
        }
        .card::after {
            bottom: 0;
        }
        .card-content > *:not(:last-child) {
            border-bottom: 1px solid #d2a679;
            margin-bottom: 8px;
            padding-bottom: 8px;
        }
        table th, table td {
            text-align: right;
        }
        .btn-animate {
            background-color: #a57f47;
        }
        .btn-animate:hover {
            background-color: #4caf50;
        }
        .book-cover {
            width: 200px;
            height: auto;
            margin-left: 20px;
        }
        .book-details {
            flex: 1;
        }
        .book-info {
            display: flex;
            flex-direction: row-reverse;
            align-items: flex-start;
            margin-bottom: 20px;
        }
    </style>
</head>
<?php
    include 'header.php';
?>
<body class="min-h-screen flex flex-col items-center">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg card">
        <div class="card-content">
            <div class="book-info">
                <div class="book-details flex-1" style="text-align:right ;">
                    <h1 class="text-3xl font-bold mb-4" style="text-align: center;"><?php echo htmlspecialchars($title); ?></h1>
                    <p class="mb-2"><strong>ژانر:</strong> <?php echo htmlspecialchars($genre); ?></p>
                    <p class="mb-2"><strong>نویسنده:</strong> <?php echo htmlspecialchars($author); ?></p>
                    <p class="mb-2"><strong>قیمت:</strong> <?php echo htmlspecialchars($price); ?> تومان</p>
                    <p class="mb-2"><strong>تعداد صفحات:</strong> <?php echo htmlspecialchars($total_pages); ?></p>
                    <p class="mb-2"><strong>خلاصه:</strong> <?php echo nl2br(htmlspecialchars($summary)); ?></p>
                    <p class="mb-2"><strong>توضیحات:</strong> <?php echo nl2br(htmlspecialchars($description)); ?></p>
                </div>
                <img src="uploads/<?php echo htmlspecialchars($book_cover_picture); ?>" alt="Book Cover" class="book-cover">
            </div>
        </div>
        

        <h2 class="text-2xl font-bold mt-8 mb-4" style="text-align: right;">فصل‌ها</h2>
        <table class="table-auto w-full border-collapse border border-gray-400 card">
            <thead>
                <tr>
                    <th class="px-4 py-2">عنوان</th>
                    <th class="px-4 py-2">ژانر</th>
                    <th class="px-4 py-2">خلاصه</th>
                    <th class="px-4 py-2">شروع صفحه</th>
                    <th class="px-4 py-2">پایان صفحه</th>
                    <th class="px-4 py-2">قیمت</th>
                    <?php if($user_level!="پابلیشر"): ?><th class="px-4 py-2">خرید</th><?php endif ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($chapter = $chapters->fetch_assoc()): ?>
                    <tr class="bg-white border-t">
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($chapter['title']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($chapter['genre']); ?></td>
                        <td class="border px-4 py-2"><?php echo nl2br(htmlspecialchars($chapter['summary'])); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($chapter['start_page']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($chapter['end_page']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($chapter['price']); ?></td>
                        <td class="border px-4 py-2">
                            <?php if (isPurchased($conn, $userId, null, $chapter['id'])): ?>
                                <a class="inline-block py-2 px-4 bg-red-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 btn-animate mt-4" href="download.php?chapter_id=<?php echo $chapter['id']; ?>">دانلود این فصل</a>
                            <?php else: ?>
                                <?php if($user_level!="پابلیشر"): ?><a class="inline-block py-2 px-4 bg-brow hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 btn-animate mt-4" href="add_to_cart.php?chapter_id=<?php echo $chapter['id']; ?>">خرید این فصل</a><?php endif ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <?php if (isPurchased($conn, $userId, $book_id)): ?>
            <a class="inline-block py-2 px-4 bg-red-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 btn-animate mt-4" href="download.php?book_id=<?php echo $book_id; ?>">دانلود کتاب</a>
        <?php else: ?>
            <?php if($user_level!="پابلیشر"): ?><a class="inline-block py-2 px-4 bg-brow hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 btn-animate mt-4" href="add_to_cart.php?id=<?php echo $book_id; ?>">خرید کتاب</a><?php endif ?>
        <?php endif; ?>
    </div>
    
    <?php
    // Free resources and close the connection
    $chapters->free();
    $conn->close();
    ?>
    
</body>

</html>
