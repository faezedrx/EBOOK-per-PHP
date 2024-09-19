<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد</title>
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font/dist/font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaHpyNeuaal5h/pyQ4g/6hVSrtPQ5hzZ3AlM13" crossorigin="anonymous">
</head>
<body class="flex">
<div class="sidebar">
    <?php include 'sidebar.php'; ?>
</div>
<!-- <img src="uploads/default.png" alt="توضیحات عکس" class="top-left"> -->
<a href="index.php" class="text-brown-600 text-2xl font-extrabold top-left"> Ebook </a>
<div class="content">
    <!-- <h1 class="text-2xl font-bold mb-4 text-green-500">داشبورد</h1> -->
    <br><br><br><br><br>
    <div class="user-info">
        <img src='uploads/<?php echo $profile_picture; ?>' alt='Profile Picture'>
        <div class="user-info-content">
            <h2 class="text-2xl font-bold text-green-500">مشخصات کاربر</h2><br>
            <p class="text-rtl"><strong>نام کاربری:</strong> <?php echo $_SESSION['username']; ?></p>
            <p class="text-rtl"><strong>نام کامل:</strong> <?php echo $full_name; ?></p>
            <p class="text-rtl"><strong>کد ملی:</strong> <?php echo $national_code; ?></p>
            <p class="text-rtl"><strong>تاریخ تولد:</strong> <?php echo $birth_date; ?></p>
            <p class="text-rtl"><strong>سطح کاربری:</strong> <?php echo $user_level; ?></p>
        </div>
        <a class="inline-block py-2 px-4 bg-yellow-500 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition duration-300 btn-animate text-lf" href="edit_profile.php"> ویرایش </a>
    </div>
    
</div>

</body>
</html>
