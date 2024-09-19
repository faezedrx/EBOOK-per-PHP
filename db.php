<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebook_final";




try {
    // اتصال به سرور
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // بررسی وجود دیتابیس
    $db_check_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
    $result = $conn->query($db_check_query);

    if ($result->num_rows == 0) {
        // اگر دیتابیس وجود ندارد، ایجادش کن
        $create_db_query = "CREATE DATABASE $dbname CHARACTER SET utf8 COLLATE utf8_general_ci";
        if ($conn->query($create_db_query) === FALSE) {
            throw new Exception("Error creating database: " . $conn->error);
        }
    }

    // اتصال به دیتابیس
    $conn->select_db($dbname);
    

    // ایجاد جدول users
    $table_users = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(50) UNIQUE ,
        user_level ENUM('نویسنده', 'مشتری', 'پابلیشر','استاد','دانشجو') NOT NULL,
        profile_picture VARCHAR(255) DEFAULT 'default.png',
        full_name VARCHAR(50) NOT NULL,
        national_code VARCHAR(10) NOT NULL,
        birth_date DATE
    )";

    if ($conn->query($table_users) === FALSE) {
        throw new Exception("Error creating table users: " . $conn->error);
    }

    // ایجاد جدول books
    $table_books = "CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_cover_picture VARCHAR(255) DEFAULT 'default_cover.jpg',
        title VARCHAR(255) NOT NULL,
        genre ENUM('جزوه','کتاب') NOT NULL,
        summary TEXT NOT NULL,
        description TEXT,
        author VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        pdf_path VARCHAR(255) NOT NULL,
        total_pages INT NOT NULL,
        is_approved BOOLEAN DEFAULT FALSE,
        publisher_comments TEXT
    )";

    if ($conn->query($table_books) === FALSE) {
        throw new Exception("Error creating table books: " . $conn->error);
    }

    // ایجاد جدول chapters
    $table_chapters = "CREATE TABLE IF NOT EXISTS chapters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        genre VARCHAR(255) NOT NULL,
        summary TEXT NOT NULL,
        start_page INT NOT NULL,
        end_page INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        pdf_path VARCHAR(255) NOT NULL,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
    )";

    if ($conn->query($table_chapters) === FALSE) {
        throw new Exception("Error creating table chapters: " . $conn->error);
    }

    // ایجاد جدول carts
    $table_carts = "CREATE TABLE IF NOT EXISTS carts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        book_id INT DEFAULT NULL,
        chapter_id INT DEFAULT NULL ,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
        FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($table_carts) === FALSE) {
        throw new Exception("Error creating table carts: " . $conn->error);
    }

    if ($conn->query($table_carts) === FALSE) {
        throw new Exception("Error creating table carts: " . $conn->error);
    }
    // ایجاد جدول orders
    $table_orders = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        book_id INT DEFAULT NULL,
        chapter_id INT DEFAULT NULL ,
        purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
        FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
    )";

    if ($conn->query($table_orders) === FALSE) {
        throw new Exception("Error creating table orders: " . $conn->error);
    }


    if (!function_exists('secureInput')) {
        function secureInput($data) {
            global $conn;
            return mysqli_real_escape_string($conn, $data);
        }
    }

    // echo "Tables created successfully";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        // $conn->close();
    }
}
?>
