<?php
if ($user_level === 'نویسنده' || $user_level === 'دانشجو' || $user_level === 'استاد') {
    $sql_books = "SELECT id, title, genre, summary, author, price, pdf_path, publisher_comments, is_approved FROM books WHERE author IN (SELECT username FROM users WHERE user_level='$user_level' AND username='$username')";
    $result_books = $conn->query($sql_books);

    if ($result_books->num_rows > 0) : ?>
        <table class="table-auto w-full border-collapse border border-gray-400 mb-6">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">عنوان</th>
                    <th class="px-4 py-2">ژانر</th>
                    <th class="px-4 py-2">خلاصه</th>
                    <th class="px-4 py-2">قیمت</th>
                    <th class="px-4 py-2">فایل</th>
                    <th class="px-4 py-2">مشاهده</th>
                    <th class="px-4 py-2">توضیحات پابلیشر</th>
                    <th class="px-4 py-2">ویرایش</th>
                    <th class="px-4 py-2">وضعیت</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row_books = $result_books->fetch_assoc()): ?>
                <tr>
                    <td class='border px-4 py-2'><?php echo htmlspecialchars($row_books['title'])?></td>
                    <td class='border px-4 py-2'><?php echo htmlspecialchars($row_books['genre'])?></td>
                    <td class='border px-4 py-2'><?php echo htmlspecialchars($row_books['summary'])?></td>
                    <td class='border px-4 py-2'><?php echo htmlspecialchars($row_books['price'])?></td>
                    <td class='border px-4 py-2'><a class="inline-block py-2 px-4 bg-red-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300" href="<?php echo htmlspecialchars($row_books['pdf_path']) ?>" download>دانلود</a></td>
                    <td class='border px-4 py-2'><a class="inline-block py-2 px-4 bg-brown hover:bg-yellow-700 text-white font-bold rounded-md shadow-lg transition duration-300 mb-2" href="view_book.php?id=<?php echo $row_books['id']; ?>">مشاهده</a></td>
                    <td class='border px-4 py-2'><p class="bg-yellow-500"><?php echo htmlspecialchars($row_books['publisher_comments'])?></p></td>
                    <td class='border px-4 py-2'>
                    <?php if ($row_books['is_approved'] === '0') :?><button type="button" class="inline-block py-2 px-4 bg-brow hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 cursor-pointer" onclick="openEditPopup(<?php echo $row_books['id']; ?>)">ویرایش کتاب</button><?php else:?><p class='border shadow-lg px-4 py-2'> </p><?php endif ?>
                    </td>
                    <td class='border px-4 py-2'><?php if ($row_books['is_approved'] === '0') :?><p class='border bg-blue-500 px-4 py-2'>در حال بررسی</p><?php else:?><p class='border  bg-green-500 shadow-lg px-4 py-2'> منتشر شده.</p><?php endif ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($user_level === 'نویسنده') : ?>
        <p class='text-gray-700'>شما هیچ کتابی ثبت نکرده‌اید.</p>
    <?php elseif ($user_level === 'دانشجو') : ?>
        <p class='text-gray-700'>شما هیچ جزوه‌ای ثبت نکرده‌اید.</p>
    <?php elseif ($user_level === 'استاد') : ?>
        <p class='text-gray-700'>شما هیچ کتاب یا جزوه‌ای ثبت نکرده‌اید.</p>
    <?php endif; ?>
<?php } ?>
<script>
    function openEditPopup(bookId) {
        fetch('getBookDetails.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'book_id=' + bookId
        })
        .then(response => response.text())
        .then(data => {
            document.body.insertAdjacentHTML('beforeend', data);
            document.getElementById('editBookModal').classList.remove('hidden');
        });
    }

    function closeEditPopup() {
        document.getElementById('editBookModal').remove();
    }
</script>
