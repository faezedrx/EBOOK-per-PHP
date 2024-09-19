<?php
$conn = new mysqli("localhost", "root", "","ebook_final");
if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id']; 

    // فرض بر این است که اتصال به پایگاه داده قبلاً برقرار شده است
    $stmt = $conn->prepare("SELECT book_cover_picture, title, genre, summary, description, author, price, total_pages FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($book_cover_picture, $title, $genre, $summary, $description, $author, $price, $total_pages);
    $stmt->fetch();
    $stmt->close();
?>
    <div id="editBookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/4 max-w-xl">
            <h2 class="text-2xl font-bold mb-4">ویرایش کتاب</h2>
            <form id="editBookForm" action="editBook.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <div class="mb-4">
                    <label for="edit_title" class="block text-gray-700">عنوان کتاب:</label>
                    <input type="text" id="edit_title" name="title" value="<?php echo htmlspecialchars($title); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="edit_genre" class="block text-gray-700">ژانر:</label>
                    <input type="text" id="edit_genre" name="genre" value="<?php echo htmlspecialchars($genre); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="edit_summary" class="block text-gray-700">خلاصه:</label>
                    <textarea id="edit_summary" name="summary" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required><?php echo htmlspecialchars($summary); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit_description" class="block text-gray-700">توضیحات:</label>
                    <textarea id="edit_description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit_author" class="block text-gray-700">نویسنده:</label>
                    <input type="text" id="edit_author" name="author" value="<?php echo htmlspecialchars($author); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="edit_price" class="block text-gray-700">قیمت:</label>
                    <input type="text" id="edit_price" name="price" value="<?php echo htmlspecialchars($price); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="edit_total_pages" class="block text-gray-700">تعداد صفحات:</label>
                    <input type="text" id="edit_total_pages" name="total_pages" value="<?php echo htmlspecialchars($total_pages); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="edit_book_cover" class="block text-gray-700">جلد کتاب:</label>
                    <input type="file" id="edit_book_cover" name="book_cover_picture" class="mt-1 block w-full text-gray-700">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditPopup()" class="py-2 px-4 bg-red-500 hover:bg-red-700 text-white font-bold rounded-md shadow-lg transition duration-300">انصراف</button>
                    <button type="submit" class="ml-2 py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
<?php
} else {
    echo "<p>خطا: شناسه کتاب مشخص نشده است.</p>";
}
?>
