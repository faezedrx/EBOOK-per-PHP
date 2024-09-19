<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';
session_start();

$errors = [];

function validate_password($password) {
    if (strlen($password) < 8) {
        return "رمز عبور باید حداقل ۸ کاراکتر باشد.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "رمز عبور باید حداقل شامل یک حرف بزرگ باشد.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "رمز عبور باید حداقل شامل یک حرف کوچک باشد.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "رمز عبور باید حداقل شامل یک عدد باشد.";
    }
    if (!preg_match('/[\W]/', $password)) {
        return "رمز عبور باید حداقل شامل یک کاراکتر ویژه باشد.";
    }
    return "";
}

function validate_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "آدرس ایمیل نامعتبر است.";
    }
    return "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

    if ($action == 'login') {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_level'] = $row['user_level'];
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "رمز عبور اشتباه است!";
            }
        } else {
            $errors[] = "کاربری یافت نشد!";
        }
    } elseif ($action == 'register') {
        $password_error = validate_password($password);
        $email_error = validate_email($email);

        if ($password_error) {
            $errors[] = $password_error;
        }

        if ($email_error) {
            $errors[] = $email_error;
        }

        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, password, email, user_level) VALUES ('$username', '$hashed_password', '$email', '$role')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['user_level'] = $role;
                header("Location: index.php"); 
                exit;
            } else {
                $errors[] = "خطا در ثبت‌نام: " . $conn->error;
            }
        }
    } elseif ($action == 'check_email') {
        $email_error = validate_email($email);

        if ($email_error) {
            $errors[] = $email_error;
        } else {
            $sql = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                $errors[] = "ایمیل یافت نشد!";
            }
        }
    } elseif ($action == 'reset_password') {
        $email_error = validate_email($email);
        $password_error = validate_password($new_password);

        if ($email_error) {
            $errors[] = $email_error;
        }

        if ($password_error) {
            $errors[] = $password_error;
        }

        if (empty($errors)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET password='$hashed_password' WHERE email='$email'";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                $errors[] = "خطا در بازنشانی رمز عبور: " . $conn->error;
            }
        }
    } else {
        $errors[] = "عملیات نامعتبر است!";
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
}
?>
