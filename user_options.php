<?php
        function display_user_options($user_level, $new_requests_count, $new_lecture_requests_count) {
            echo '<li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> داشبورد</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="edit_profile.php"><i class="fas fa-user-edit"></i> پروفایل</a></li>';
            // Display different options based on user level
            switch ($user_level) {
                case 'مشتری':
                    echo '<li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> مشاهده سفارش‌ها</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> سبد خرید</a></li>';
                    break;
                case 'نویسنده':
                    echo '<li class="nav-item"><a class="nav-link" href="my_books.php"><i class="fas fa-book"></i> مشاهده کتاب‌های من</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> مشاهده سفارش‌ها</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> سبد خرید</a></li>';
                    break;
                case 'پابلیشر':
                    echo '<li class="nav-item"><a class="nav-link" href="requests.php"><i class="fas fa-file-alt"></i> مشاهده درخواست‌ها';
                    if ($new_requests_count > 0) {
                        echo " <span class='badge'>$new_requests_count</span>";
                    }
                    echo '</a></li>';
                    break;
                case 'استاد':
                    echo '<li class="nav-item"><a class="nav-link" href="my_books.php"><i class="fas fa-book"></i> مشاهده کتاب‌ها/جزوه‌های من</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="requests.php"><i class="fas fa-book"></i> درخواست‌های جزوات';
                    if ($new_lecture_requests_count > 0) {
                        echo " <span class='badge'>$new_lecture_requests_count</span>";
                    }
                    echo '</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> مشاهده سفارش‌ها</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> سبد خرید</a></li>';
                    break;
                case 'دانشجو':
                    echo '<li class="nav-item"><a class="nav-link" href="my_books.php"><i class="fas fa-book"></i> جزوات من </a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> مشاهده سفارش‌ها</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> سبد خرید</a></li>';
                    break;
                default:
                    // Handle any other cases or errors
                    break;
            }
            echo '<li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> خروج</a></li>';
        }
?>
