<?php

session_start();

require "../config/config.php";


if (isset($_POST['register'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username   = trim($_POST['username']);
    $password   = $_POST['password'];
    $password2  = $_POST['password2'];

    // عکس پروفایل
    $profile_image = NULL;


    // =========================================
    // بررسی یکی بودن رمزها
    // =========================================

    if ($password !== $password2) {

        echo "<script>
                alert('رمز عبور و تکرار رمز عبور یکسان نیستند');
              </script>";

    } else {

        // =========================================
        // بررسی تکراری نبودن نام کاربری
        // =========================================

        $check = "
            SELECT *
            FROM login_register2
            WHERE username = '$username'
        ";

        $result = mysqli_query($conn, $check);


        if (mysqli_num_rows($result) > 0) {

            echo "<script>
                    alert('این نام کاربری قبلاً استفاده شده است');
                  </script>";

        } else {

            // =========================================
            // هش کردن رمز عبور
            // =========================================

            $password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            // =========================================
            // آپلود عکس پروفایل
            // =========================================

            if (
                isset($_FILES['profile_image']) &&
                $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE
            ) {

                if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {

                    // حداکثر حجم: 2MB
                    if ($_FILES['profile_image']['size'] > 2 * 1024 * 1024) {

                        echo "<script>
                                alert('حجم عکس نباید بیشتر از 2 مگابایت باشد');
                              </script>";

                        exit;
                    }


                    // بررسی نوع واقعی فایل
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);

                    $mime_type = finfo_file(
                        $finfo,
                        $_FILES['profile_image']['tmp_name']
                    );

                    finfo_close($finfo);


                    $allowed_types = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/webp' => 'webp'
                    ];


                    if (!isset($allowed_types[$mime_type])) {

                        echo "<script>
                                alert('فرمت عکس مجاز نیست. فقط JPG، PNG و WEBP مجاز هستند');
                              </script>";

                        exit;
                    }


                    // =========================================
                    // ساخت نام اختصاصی برای عکس
                    // =========================================

                    $extension = $allowed_types[$mime_type];

                    $profile_image =
                        'user_' .
                        time() .
                        '_' .
                        bin2hex(random_bytes(5)) .
                        '.' .
                        $extension;


                    // مسیر پوشه
                    $upload_dir = "../uploads/profile/";


                    // اگر پوشه وجود نداشت، ساخته شود
                    if (!is_dir($upload_dir)) {

                        mkdir(
                            $upload_dir,
                            0755,
                            true
                        );
                    }


                    // انتقال عکس
                    $upload_success = move_uploaded_file(
                        $_FILES['profile_image']['tmp_name'],
                        $upload_dir . $profile_image
                    );


                    if (!$upload_success) {

                        echo "<script>
                                alert('آپلود عکس با خطا مواجه شد');
                              </script>";

                        exit;
                    }

                } else {

                    echo "<script>
                            alert('در آپلود عکس مشکلی ایجاد شد');
                          </script>";

                    exit;
                }
            }


            // =========================================
            // ثبت کاربر
            // =========================================

            $sql = "
                INSERT INTO login_register2
                (
                    first_name,
                    last_name,
                    username,
                    password,
                    role,
                    profile_image
                )
                VALUES
                (
                    '$first_name',
                    '$last_name',
                    '$username',
                    '$password',
                    'user',
                    " .
                (
                $profile_image === NULL
                    ? "NULL"
                    : "'" . mysqli_real_escape_string(
                        $conn,
                        $profile_image
                    ) . "'"
                ) .
                "
                )
            ";


            $insert = mysqli_query($conn, $sql);


            if (!$insert) {

                echo "<script>
                        alert('ثبت نام انجام نشد');
                      </script>";

                exit;
            }


            // =========================================
            // گرفتن ID کاربر
            // =========================================

            $id = mysqli_insert_id($conn);


            // =========================================
            // ساخت Session
            // =========================================

            $_SESSION['id']         = $id;
            $_SESSION['username']   = $username;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name']  = $last_name;
            $_SESSION['role']       = 'user';


            // =========================================
            // انتقال به پنل کاربر
            // =========================================

            header("Location: ../user.php");
            exit;
        }
    }
}

?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
    >

    <title>ثبت نام | DIGI24</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            min-height: 100%;
        }


        body {

            font-family:
                    Tahoma,
                    Arial,
                    sans-serif;

            background:

                    radial-gradient(
                            circle at 10% 10%,
                            rgba(36, 180, 178, .11),
                            transparent 28%
                    ),

                    radial-gradient(
                            circle at 90% 90%,
                            rgba(77, 154, 166, .08),
                            transparent 30%
                    ),

                    #f4f7f7;

            color: #29383a;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 35px 18px;
        }


        /* =========================================
           MAIN
        ========================================= */

        .d24-register-wrapper {

            width: 100%;

            max-width: 1040px;

            display: flex;

            justify-content: center;

            align-items: stretch;
        }


        /* =========================================
           CARD
        ========================================= */

        .d24-register-card {

            width: 100%;

            max-width: 880px;

            min-height: 600px;

            display: grid;

            grid-template-columns: .85fr 1.15fr;

            background: #ffffff;

            border: 1px solid #dfe9e9;

            border-radius: 28px;

            overflow: hidden;

            box-shadow:

                    0 25px 70px rgba(41, 86, 91, .11),

                    0 5px 18px rgba(41, 86, 91, .05);
        }


        /* =========================================
           LEFT SIDE
        ========================================= */

        .d24-register-side {

            position: relative;

            display: flex;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            text-align: center;

            padding: 45px 35px;

            background:

                    linear-gradient(
                            145deg,
                            #269d9d,
                            #187b80
                    );

            color: #ffffff;

            overflow: hidden;
        }


        .d24-register-side::before {

            content: "";

            position: absolute;

            width: 280px;

            height: 280px;

            border-radius: 50%;

            border: 1px solid rgba(255,255,255,.10);

            top: -150px;

            right: -130px;
        }


        .d24-register-side::after {

            content: "";

            position: absolute;

            width: 240px;

            height: 240px;

            border-radius: 50%;

            border: 1px solid rgba(255,255,255,.08);

            bottom: -130px;

            left: -110px;
        }


        /* =========================================
           LOGO
        ========================================= */

        .d24-logo {

            position: relative;

            z-index: 2;

            width: 72px;

            height: 72px;

            border-radius: 22px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: rgba(255,255,255,.10);

            border: 1px solid rgba(191,240,237,.65);

            color: #c9f1ed;

            font-size: 17px;

            font-weight: 900;

            letter-spacing: 1px;

            margin-bottom: 22px;

            box-shadow:
                    0 15px 35px rgba(20,75,78,.15);
        }


        /* =========================================
           BRAND
        ========================================= */

        .d24-register-side h1 {

            position: relative;

            z-index: 2;

            margin: 0;

            font-size: 29px;

            font-weight: 950;

            letter-spacing: 2px;
        }


        .d24-register-side .d24-line {

            position: relative;

            z-index: 2;

            width: 42px;

            height: 2px;

            margin: 17px 0;

            background: #b9e7e3;

            border-radius: 10px;
        }


        .d24-register-side p {

            position: relative;

            z-index: 2;

            max-width: 230px;

            margin: 0;

            color: #e3f4f3;

            font-size: 11px;

            line-height: 2.2;
        }


        .d24-side-bottom {

            position: relative;

            z-index: 2;

            margin-top: 35px;

            color: #b9dedd;

            font-size: 9px;
        }


        /* =========================================
           FORM AREA
        ========================================= */

        .d24-register-form-area {

            padding: 45px 55px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            background: #ffffff;
        }


        /* =========================================
           HEADER
        ========================================= */

        .d24-form-header {

            margin-bottom: 24px;
        }


        .d24-form-header h2 {

            margin: 0;

            color: #293b3e;

            font-size: 22px;

            font-weight: 950;
        }


        .d24-form-header h2 span {

            color: #168c90;
        }


        .d24-form-header p {

            margin: 9px 0 0;

            color: #8a999b;

            font-size: 10px;

            line-height: 1.9;
        }


        /* =========================================
           FIELDS
        ========================================= */

        .d24-fields {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 15px 14px;
        }


        .d24-field.full {

            grid-column: 1 / -1;
        }


        /* =========================================
           LABEL
        ========================================= */

        .d24-field label {

            display: block;

            margin-bottom: 8px;

            color: #536568;

            font-size: 10px;

            font-weight: 900;
        }


        .d24-field label::after {

            content: " *";

            color: #1a9b9e;
        }


        /* =========================================
           INPUT
        ========================================= */

        .d24-field input {

            width: 100%;

            height: 47px;

            padding: 0 14px;

            border: 1px solid #dce7e7;

            border-radius: 11px;

            outline: none;

            background: #f7fafa;

            color: #293b3e;

            font-family: inherit;

            font-size: 11px;

            font-weight: 600;

            transition: all .25s ease;
        }


        .d24-field input::placeholder {

            color: #a3afb0;

            font-size: 10px;
        }


        .d24-field input:hover {

            background: #fbfdfd;

            border-color: #a9cdcf;
        }


        .d24-field input:focus {

            background: #ffffff;

            border-color: #2aa5a7;

            box-shadow:

                    0 0 0 3px rgba(36,166,168,.11),

                    0 7px 20px rgba(42,91,95,.06);
        }


        /* =========================================
           PROFILE IMAGE
        ========================================= */

        .d24-profile-upload {

            grid-column: 1 / -1;

            display: flex;

            align-items: center;

            gap: 15px;

            padding: 13px 15px;

            border: 1px solid #dce7e7;

            border-radius: 14px;

            background: #f7fafa;

            transition: .25s ease;
        }


        .d24-profile-upload:hover {

            border-color: #a9cdcf;

            background: #fbfdfd;
        }


        .d24-profile-icon {

            width: 45px;

            height: 45px;

            min-width: 45px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background:

                    linear-gradient(
                            135deg,
                            #249b9d,
                            #187c81
                    );

            color: #ffffff;

            font-size: 18px;

            box-shadow:
                    0 7px 18px rgba(25,121,126,.15);
        }


        .d24-profile-info {

            flex: 1;

            min-width: 0;
        }


        .d24-profile-info strong {

            display: block;

            color: #405255;

            font-size: 10px;

            font-weight: 900;

            margin-bottom: 4px;
        }


        .d24-profile-info span {

            display: block;

            color: #9aa7a8;

            font-size: 9px;
        }


        .d24-profile-file {

            width: auto !important;

            height: auto !important;

            padding: 0 !important;

            border: none !important;

            background: transparent !important;

            box-shadow: none !important;

            font-size: 9px !important;

            color: #178c90 !important;

            cursor: pointer;

        }


        .d24-profile-file::file-selector-button {

            border: none;

            border-radius: 8px;

            padding: 9px 12px;

            margin-left: 6px;

            background: #e0f3f2;

            color: #147b7e;

            font-family: inherit;

            font-size: 9px;

            font-weight: 900;

            cursor: pointer;

            transition: .2s ease;
        }


        .d24-profile-file::file-selector-button:hover {

            background: #cdebea;
        }


        /* =========================================
           BUTTON
        ========================================= */

        .d24-register-button {

            width: 100%;

            height: 52px;

            margin-top: 22px;

            border: none;

            border-radius: 12px;

            background:

                    linear-gradient(
                            135deg,
                            #249b9d,
                            #187c81
                    );

            color: #ffffff;

            font-family: inherit;

            font-size: 11px;

            font-weight: 950;

            cursor: pointer;

            box-shadow:

                    0 10px 24px rgba(25,121,126,.18);

            transition: all .25s ease;
        }


        .d24-register-button:hover {

            transform: translateY(-2px);

            background:

                    linear-gradient(
                            135deg,
                            #2eaaac,
                            #1d858a
                    );

            box-shadow:

                    0 14px 28px rgba(25,121,126,.23);
        }


        .d24-register-button:active {

            transform: translateY(0);
        }


        /* =========================================
           LOGIN
        ========================================= */

        .d24-login {

            margin-top: 18px;

            padding-top: 16px;

            border-top: 1px solid #e4ebeb;

            text-align: center;

            color: #929e9f;

            font-size: 10px;
        }


        .d24-login a {

            color: #178c90;

            text-decoration: none;

            font-weight: 950;

            margin-right: 5px;

            font-size: 10px;

            transition: .2s ease;
        }


        .d24-login a:hover {

            color: #116f73;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 800px) {

            .d24-register-card {

                grid-template-columns: 1fr;

                max-width: 520px;
            }


            .d24-register-side {

                min-height: 230px;

                padding: 35px 25px;
            }


            .d24-register-side p {

                display: none;
            }


            .d24-side-bottom {

                margin-top: 15px;
            }


            .d24-register-form-area {

                padding: 38px 30px;
            }
        }


        @media (max-width: 520px) {

            body {

                padding: 18px 12px;
            }


            .d24-register-card {

                border-radius: 21px;

                min-height: auto;
            }


            .d24-register-side {

                min-height: 190px;

                padding: 28px 20px;
            }


            .d24-logo {

                width: 58px;

                height: 58px;

                border-radius: 17px;

                margin-bottom: 14px;
            }


            .d24-register-side h1 {

                font-size: 23px;
            }


            .d24-register-form-area {

                padding: 30px 20px;
            }


            .d24-fields {

                grid-template-columns: 1fr;

                gap: 15px;
            }


            .d24-field.full {

                grid-column: auto;
            }


            .d24-profile-upload {

                grid-column: auto;

                flex-wrap: wrap;
            }


            .d24-profile-info {

                flex: 1;
            }


            .d24-profile-file {

                width: 100% !important;
            }


            .d24-form-header h2 {

                font-size: 19px;
            }


            .d24-form-header p {

                font-size: 10px;
            }


            .d24-field label {

                font-size: 10px;
            }


            .d24-field input {

                font-size: 11px;
            }


            .d24-register-button {

                font-size: 11px;
            }


            .d24-login {

                font-size: 10px;
            }

        }

    </style>

</head>


<body>


<div class="d24-register-wrapper">


    <div class="d24-register-card">


        <!-- =========================================
             LEFT SIDE
        ========================================== -->

        <div class="d24-register-side">


            <div class="d24-logo">
                D24
            </div>


            <h1>
                DIGI24
            </h1>


            <div class="d24-line"></div>


            <p>
                به DIGI24 خوش آمدید.
                حساب کاربری خود را بسازید
                و تجربه‌ای متفاوت از خرید آنلاین داشته باشید.
            </p>


            <div class="d24-side-bottom">
                تجربه‌ای ساده، سریع و حرفه‌ای
            </div>


        </div>


        <!-- =========================================
             RIGHT SIDE
        ========================================== -->

        <div class="d24-register-form-area">


            <div class="d24-form-header">

                <h2>
                    ایجاد حساب <span>کاربری</span>
                </h2>

                <p>
                    اطلاعات خود را وارد کنید تا حساب شما ساخته شود.
                </p>

            </div>


            <form
                    action=""
                    method="POST"
                    enctype="multipart/form-data"
            >


                <div class="d24-fields">


                    <!-- نام -->

                    <div class="d24-field">

                        <label>
                            نام
                        </label>

                        <input
                                type="text"
                                name="first_name"
                                placeholder="نام خود را وارد کنید"
                                required
                        >

                    </div>


                    <!-- نام خانوادگی -->

                    <div class="d24-field">

                        <label>
                            نام خانوادگی
                        </label>

                        <input
                                type="text"
                                name="last_name"
                                placeholder="نام خانوادگی"
                                required
                        >

                    </div>


                    <!-- نام کاربری -->

                    <div class="d24-field full">

                        <label>
                            نام کاربری
                        </label>

                        <input
                                type="text"
                                name="username"
                                placeholder="یک نام کاربری انتخاب کنید"
                                required
                        >

                    </div>


                    <!-- رمز عبور -->

                    <div class="d24-field">

                        <label>
                            رمز عبور
                        </label>

                        <input
                                type="password"
                                name="password"
                                placeholder="رمز عبور"
                                required
                        >

                    </div>


                    <!-- تکرار رمز عبور -->

                    <div class="d24-field">

                        <label>
                            تکرار رمز عبور
                        </label>

                        <input
                                type="password"
                                name="password2"
                                placeholder="تکرار رمز عبور"
                                required
                        >

                    </div>


                    <!-- =========================================
                         عکس پروفایل
                    ========================================== -->

                    <div class="d24-profile-upload">


                        <div class="d24-profile-icon">
                            👤
                        </div>


                        <div class="d24-profile-info">

                            <strong>
                                عکس پروفایل
                            </strong>

                            <span>
                                اختیاری • JPG، PNG یا WEBP • حداکثر 2MB
                            </span>

                        </div>


                        <input
                                type="file"
                                name="profile_image"
                                class="d24-profile-file"
                                accept="image/jpeg,image/png,image/webp"
                        >


                    </div>


                </div>


                <button
                        type="submit"
                        name="register"
                        class="d24-register-button"
                >
                    ایجاد حساب کاربری
                </button>


            </form>


            <div class="d24-login">

                قبلاً حساب کاربری دارید؟

                <a href="login.php">
                    وارد شوید
                </a>

            </div>


        </div>


    </div>


</div>


</body>

</html>
