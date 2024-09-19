<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<div id="popup-modal" class="flex" dir="rtl">
    <div id="popup-content" class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
        <div id="form-container">
            <h2 id="form-title" class="text-2xl font-bold mb-6 text-center text-green-500">ورود</h2>
            <form id="login-form" action="process_login_register.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">نام کاربری:</label>
                    <div class="relative">
                        <input type="text" id="login-username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-user absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">رمز عبور:</label>
                    <div class="relative">
                        <input type="password" id="login-password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-lock absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <button type="submit" class="w-full py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300">ورود</button>
            </form>

            <form id="register-form" class="hidden" action="process_login_register.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">نام کاربری:</label>
                    <div class="relative">
                        <input type="text" id="register-username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-user absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">رمز عبور:</label>
                    <button type="button" id="password-help" title="راهنمای رمز عبور">؟</button>
                    <div class="relative">
                        <input type="password" id="register-password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-lock absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">ایمیل:</label>
                    <div class="relative">
                        <input type="email" id="register-email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-envelope absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">سطح کاربری:</label>
                    <div class="relative">
                        <select id="register-role" name="role" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                            <option value="نویسنده">نویسنده</option>
                            <option value="مشتری">مشتری</option>
                            <option value="پابلیشر">پابلیشر</option>
                            <option value="استاد">استاد</option>
                            <option value="دانشجو">دانشجو</option>
                        </select>
                        <i class="fas fa-user-tag absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <button type="submit" class="w-full py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300">ثبت نام</button>
            </form>

            <form id="forgot-password-form" class="hidden" action="process_login_register.php" method="POST">
                <h2 id="forgot-password-title" class="text-2xl font-bold mb-6 text-center text-red-500">فراموشی رمز عبور</h2>
                <div class="mb-4">
                    <label for="forgot-password-email" class="block text-sm font-medium text-gray-700">ایمیل:</label>
                    <div class="relative">
                        <input type="email" id="forgot-password-email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-envelope absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <button type="submit" class="w-full py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300">ارسال</button>
            </form>

            <form id="reset-password-form" class="hidden" action="process_login_register.php" method="POST">
                <h2 id="reset-password-title" class="text-2xl font-bold mb-6 text-center text-red-500">بازنشانی رمز عبور</h2>
                <input type="hidden" name="action" value="reset_password">
                <div class="mb-4">
                    <label for="reset-password-email" class="block text-sm font-medium text-gray-700">ایمیل:</label>
                    <div class="relative">
                        <input type="email" id="reset-password-email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-envelope absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="reset-new-password" class="block text-sm font-medium text-gray-700">رمز جدید:</label>
                    <div class="relative">
                        <input type="password" id="reset-new-password" name="new_password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-green-500 pl-10" required>
                        <i class="fas fa-lock absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                    </div>
                </div>
                <button type="button" id="reset-password-button" class="w-full py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300">بازنشانی رمز عبور</button>
            </form>


            <div class="text-center mt-4">
                <button id="toggle-button" class="text-green-500 hover:underline transition duration-300">ثبت نام کنید</button>
                <br>
                <button id="forgot-password-link" class="text-red-500 hover:underline transition duration-300">فراموشی رمز عبور</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
        document.getElementById('password-help').addEventListener('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'راهنمای رمز عبور',
                html: `
                    <ul style="text-align: right; direction: rtl;">
                        <li>حداقل ۸ کاراکتر داشته باشد.</li>
                        <li>حداقل یک حرف بزرگ داشته باشد.</li>
                        <li>حداقل یک حرف کوچک داشته باشد.</li>
                        <li>حداقل یک عدد داشته باشد.</li>
                        <li>حداقل شامل یک کاراکتر ویژه باشد.</li>
                    </ul>
                `,
                confirmButtonText: 'متوجه شدم'
            });
        });
    </script>
    <script>
        document.getElementById('forgot-password-form').addEventListener('submit', function (event) {
        event.preventDefault();

        var email = document.getElementById('forgot-password-email').value;
        var action = 'check_email';

        var formData = new FormData();
        formData.append('action', action);
        formData.append('email', email);

        fetch('process_login_register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // نمایش فرم بازنشانی رمز عبور
                var forgotPasswordForm = document.getElementById('forgot-password-form');
                var resetPasswordForm = document.getElementById('reset-password-form');
                forgotPasswordForm.classList.add('hidden');
                resetPasswordForm.classList.remove('hidden');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا!',
                    text: data.errors[0],
                    confirmButtonText: 'باشه'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'خطا!',
                text: 'مشکلی پیش آمده است. لطفا دوباره تلاش کنید.',
                confirmButtonText: 'باشه'
            });
        });
    });
    document.getElementById('reset-password-button').addEventListener('click', function(event) {
        event.preventDefault();

        var email = document.getElementById('reset-password-email').value;
        var newPassword = document.getElementById('reset-new-password').value;
        var action = 'reset_password'; // افزودن مقدار action به صورت مستقیم

        var formData = new FormData();
        formData.append('action', action);
        formData.append('email', email);
        formData.append('new_password', newPassword);


        fetch('process_login_register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'رمز عبور با موفقیت بازنشانی شد.',
                    confirmButtonText: 'باشه'
                    
                }).then(() => {
                        window.location.href = 'index.php';
                    });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا!',
                    html: '<ul>' + data.errors.map(error => '<li>' + error + '</li>').join('') + '</ul>',
                    confirmButtonText: 'باشه'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'خطا!',
                text: 'مشکلی پیش آمده است. لطفا دوباره تلاش کنید.',
                confirmButtonText: 'باشه'
            });
        });
    });
</script>


</body>
</html>

