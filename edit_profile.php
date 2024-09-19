<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش اطلاعات پروفایل</title>
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaHpyNeuaal5h/pyQ4g/6hVSrtPQ5hzZ3AlM13" crossorigin="anonymous">
    <style>
        
    </style>
</head>
<body class="flex">
<div class="sidebar">
    <?php include 'sidebar.php'; ?>
</div>
<a href="index.php" class="text-brown-600 text-2xl font-extrabold top-left"> Ebook </a>
<div class="content">
    <h1 class="text-2xl font-bold mb-4 text-green-500">پروفایل</h1>
    <div class="user-info">
        <!-- فرم ویرایش اطلاعات پروفایل -->
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <?php
            echo "<img src='uploads/$profile_picture' class='rounded-full w-24 h-24 mb-4' alt='Profile Picture'>";
            ?>
            <label for="profile_picture">عکس پروفایل:</label><br>
            <input type="file" id="profile_picture" name="profile_picture"><br><br>
            
            <label for="full_name">نام کامل:</label><br>
            <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>"><br><br>

            <label for="national_code">کد ملی:</label><br>
            <input type="text" id="national_code" name="national_code" value="<?php echo $national_code; ?>"><br><br>

            <label for="birth_date">تاریخ تولد:</label><br>
            <input type="date" id="birth_date" name="birth_date" value="<?php echo $birth_date; ?>"><br><br>

            <input type="submit" name="submit" value="ذخیره تغییرات">
        </form>
    </div>
</div>

<?php
if (isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $national_code = $_POST['national_code'];
    $birth_date = $_POST['birth_date'];
    $username = $_SESSION['username'];
    
    if ($_FILES['profile_picture']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        // if (file_exists($target_file)) {
        //     echo "Sorry, file already exists.";
        //     $uploadOk = 0;
        // }

        // Check file size
        if ($_FILES["profile_picture"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = htmlspecialchars(basename($_FILES["profile_picture"]["name"]));
                $sql = "UPDATE users SET full_name='$full_name', national_code='$national_code', birth_date='$birth_date', profile_picture='$profile_picture' WHERE username='$username'";
                if ($conn->query($sql) === TRUE) {
                    echo "Profile updated successfully.";
                    header("Location: edit_profile.php"); // Redirect back to edit_profile.php after successful update
                    exit();
                } else {
                    echo "Error updating record:" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $sql = "UPDATE users SET full_name='$full_name', national_code='$national_code', birth_date='$birth_date' WHERE username='$username'";
        if ($conn->query($sql) === TRUE) {
            echo "Profile updated successfully.";
            header("Location: edit_profile.php"); // Redirect back to edit_profile.php after successful update
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}
?>
</body>
</html>
