<?php
if ($user_level === 'نویسنده' || $user_level === 'دانشجو' || $user_level === 'استاد') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_book'])) {

        // اعتبارسنجی سمت سرور
        $errors = [];
        $title = secureInput($_POST['title']);
        $genre = secureInput($_POST['genre']);
        $summary = secureInput($_POST['summary']);
        $description = secureInput($_POST['description']);
        $price = secureInput($_POST['price']);
        $total_pages = (int)secureInput($_POST['total_pages']);
        
        // بررسی وجود کلیدهای آرایه
        $chapter_titles = isset($_POST['chapter_titles']) ? $_POST['chapter_titles'] : [];
        $chapter_summary = isset($_POST['chapter_summary']) ? $_POST['chapter_summary'] : [];
        $chapter_start_pages = isset($_POST['chapter_start_pages']) ? $_POST['chapter_start_pages'] : [];
        $chapter_end_pages = isset($_POST['chapter_end_pages']) ? $_POST['chapter_end_pages'] : [];
        $chapter_prices = isset($_POST['chapter_prices']) ? $_POST['chapter_prices'] : [];

        if (empty($title) || empty($genre) || empty($summary) || empty($description) || empty($price) || empty($total_pages)) {
            $errors[] = "لطفاً همه فیلدها را پر کنید.";
        }

        // بررسی صفحات فصل‌ها
        for ($i = 0; $i < count($chapter_titles); $i++) {
            $chapter_start_page = (int)secureInput($chapter_start_pages[$i]);
            $chapter_end_page = (int)secureInput($chapter_end_pages[$i]);

            if ($chapter_start_page < 1 || $chapter_start_page > $total_pages || $chapter_end_page < 1 || $chapter_end_page > $total_pages || $chapter_start_page >= $chapter_end_page) {
                $errors[] = "صفحات فصل‌ها باید در محدوده تعداد صفحات کتاب باشند و صفحه پایان باید بزرگتر از صفحه شروع باشد.";
                break;
            }
        }

        // بارگذاری فایل PDF
        $pdf_path = '';
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
            $file_info = pathinfo($_FILES['pdf']['name']);
            if (strtolower($file_info['extension']) !== 'pdf') {
                $errors[] = "فایل باید با فرمت PDF باشد.";
            } else {
                // تغییر نام فایل به title+username.pdf
                $pdf_filename = $title . '_' . $username . '.pdf';
                $pdf_path = 'uploads/' . basename($pdf_filename);
                move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_path);
            }
        } else {
            $errors[] = "لطفاً فایل PDF را بارگذاری کنید.";
        }

        if (empty($errors)) {
            $sql_insert = "INSERT INTO books (title, genre, summary, description, author, price, pdf_path, total_pages) VALUES ('$title', '$genre', '$summary', '$description', '$username', '$price', '$pdf_path', '$total_pages')";

            if ($conn->query($sql_insert) === TRUE) {
                $book_id = $conn->insert_id; // گرفتن ID کتاب ایجاد شده

                // ایجاد فصل‌ها
                for ($i = 0; $i < count($chapter_titles); $i++) {
                    $chapter_title = secureInput($chapter_titles[$i]);
                    $chapter_summary = secureInput($chapter_summary[$i]);
                    $chapter_start_page = secureInput($chapter_start_pages[$i]);
                    $chapter_end_page = secureInput($chapter_end_pages[$i]);
                    $chapter_price = secureInput($chapter_prices[$i]);

                    $ch_pdf_path = '';
                    if (isset($_FILES['ch_pdf']['name'][$i])) {
                        $file_info = pathinfo($_FILES['ch_pdf']['name'][$i]);
                        // if (strtolower($file_info['extension']) !== 'pdf') {
                        //     $errors[] = "فایل باید با فرمت PDF باشد.";
                        // } else {
                            // تغییر نام فایل به chapter_title+username.pdf
                            $ch_pdf_filename = $chapter_title . '_' . $username . '.pdf';
                            $ch_pdf_path = 'uploads/' . basename($ch_pdf_filename);
                            move_uploaded_file($_FILES['ch_pdf']['tmp_name'][$i], $ch_pdf_path);
                    }

                    $sql_insert_chapter = "INSERT INTO chapters (book_id, title, genre, summary, start_page, end_page, price, pdf_path) VALUES ('$book_id', '$chapter_title', '$genre', '$chapter_summary', '$chapter_start_page', '$chapter_end_page', '$chapter_price', '$ch_pdf_path')";
                    $conn->query($sql_insert_chapter);
                }

                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'موفقیت',
                            text: 'کتاب / جزوه جدید با موفقیت ایجاد شد.',
                            confirmButtonText: 'باشه'
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا',
                            text: 'خطا در ایجاد کتاب / جزوه: " . $conn->error . "',
                            confirmButtonText: 'باشه'
                        });
                      </script>";
            }
        } else {
            foreach ($errors as $error) {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا',
                            text: '$error',
                            confirmButtonText: 'باشه'
                        });
                      </script>";
            }
        }
    }
}
?>

<?php if ($user_level === 'نویسنده' || $user_level === 'دانشجو' || $user_level === 'استاد'): ?>
    <button id="createBookBtn" class="bg-green-500 text-white px-4 py-2 rounded mb-4">
        <?php
        if ($user_level === 'نویسنده') {
            echo 'ایجاد کتاب جدید';
        } elseif ($user_level === 'دانشجو') {
            echo 'ایجاد جزوه جدید';
        } elseif ($user_level === 'استاد') {
            echo 'ایجاد کتاب / جزوه جدید';
        }
        ?>
    </button>

    <div id="createBookForm" class="hidden">
        <form id="bookForm" action="my_books.php" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="title" class="block text-gray-700">عنوان کتاب / جزوه:</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
            </div>
            <div class="mb-4">
                <label for="genre" class="block text-gray-700">ژانر کتاب / جزوه:</label>
                <select id="genre" name="genre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                    <?php if ($user_level === 'نویسنده' || $user_level === 'استاد'): ?>
                        <option value="کتاب">کتاب</option>
                    <?php endif; ?>
                    <?php if ($user_level === 'دانشجو' || $user_level === 'استاد'): ?>
                        <option value="جزوه">جزوه</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="summary" class="block text-gray-700">خلاصه کتاب / جزوه:</label>
                <textarea id="summary" name="summary" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">توضیحات:</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-gray-700">قیمت کتاب / جزوه:</label>
                <input type="text" id="price" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
            </div>
            <div class="mb-4">
                <label for="total_pages" class="block text-gray-700">تعداد صفحات:</label>
                <input type="text" id="total_pages" name="total_pages" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
            </div>
            <div class="mb-4">
                <label for="pdf" class="block text-gray-700">بارگذاری PDF:</label>
                <input type="file" id="pdf" name="pdf" class="mt-1 block w-full text-gray-700" accept=".pdf" required>
            </div>
            <div class="mb-4">
                <label for="chapters" class="block text-gray-700">تعداد فصل‌ها:</label>
                <input type="number" id="chapters" name="chapters" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
            </div>
            <div id="chapterDetails" class="mb-4"></div>
            <input type="submit" name="create_book" value="<?php echo ($user_level === 'نویسنده') ? 'ایجاد کتاب' : (($user_level === 'دانشجو') ? 'ایجاد جزوه' : 'ایجاد کتاب / جزوه'); ?>" class="bg-green-500 text-white px-4 py-2 rounded">
        </form>
    </div>
<?php endif; ?>
<script>
    document.getElementById('createBookBtn').addEventListener('click', function() {
        document.getElementById('createBookForm').classList.toggle('hidden');
    });

    // ایجاد فیلدهای فصل‌ها بر اساس تعداد فصل‌ها
    document.getElementById('chapters').addEventListener('input', function() {
        const chapterCount = parseInt(this.value);
        const chapterDetails = document.getElementById('chapterDetails');
        chapterDetails.innerHTML = '';

        for (let i = 0; i < chapterCount; i++) {
            chapterDetails.innerHTML += `
                <div class="mb-4">
                    <label for="chapter_titles_${i}" class="block text-gray-700">عنوان فصل ${i+1}:</label>
                    <input type="text" id="chapter_titles_${i}" name="chapter_titles[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="chapter_summary_${i}" class="block text-gray-700">خلاصه فصل ${i+1}:</label>
                    <input type="text" id="chapter_summary_${i}" name="chapter_summary[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="chapter_start_pages_${i}" class="block text-gray-700">صفحه شروع فصل ${i+1}:</label>
                    <input type="text" id="chapter_start_pages_${i}" name="chapter_start_pages[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="chapter_end_pages_${i}" class="block text-gray-700">صفحه پایان فصل ${i+1}:</label>
                    <input type="text" id="chapter_end_pages_${i}" name="chapter_end_pages[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="chapter_prices_${i}" class="block text-gray-700">قیمت فصل ${i+1}:</label>
                    <input type="text" id="chapter_prices_${i}" name="chapter_prices[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500" required>
                </div>
                <div class="mb-4">
                    <label for="ch_pdf_${i}" class="block text-gray-700">بارگذاری PDF:</label>
                    <input type="file" id="ch_pdf_${i}" name="ch_pdf[]" class="mt-1 block w-full text-gray-700" accept=".pdf" required>
                </div>`;
        }
    });

    // اعتبارسنجی سمت کاربر
    document.getElementById('bookForm').addEventListener('submit', function(event) {
        const title = document.getElementById('title').value.trim();
        const genre = document.getElementById('genre').value;
        const summary = document.getElementById('summary').value.trim();
        const description = document.getElementById('description').value.trim();
        const price = document.getElementById('price').value.trim();
        const total_pages = parseInt(document.getElementById('total_pages').value.trim());
        const pdf = document.getElementById('pdf').files[0];
        const chapters = parseInt(document.getElementById('chapters').value.trim());

        if (!title || !genre || !summary || !description || !price || !total_pages || !pdf || !chapters) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'لطفاً همه فیلدها را پر کنید.',
                confirmButtonText: 'باشه'
            });
            return;
        }

        const pdfExtension = pdf.name.split('.').pop().toLowerCase();
        if (pdfExtension !== 'pdf') {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'فایل باید با فرمت PDF باشد.',
                confirmButtonText: 'باشه'
            });
        }

        // اعتبارسنجی صفحات فصل‌ها سمت کاربر
        for (let i = 0; i < chapters; i++) {
            const startPage = parseInt(document.getElementById(`chapter_start_pages_${i}`).value.trim());
            const endPage = parseInt(document.getElementById(`chapter_end_pages_${i}`).value.trim());

            if (startPage < 1 || startPage > total_pages || endPage < 1 || endPage >= total_pages || startPage >= endPage) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'صفحات فصل‌ها باید در محدوده تعداد صفحات کتاب باشند و صفحه پایان باید بزرگتر از صفحه شروع باشد.',
                    confirmButtonText: 'باشه'
                });
                return;
            }
        }
    });
</script>
