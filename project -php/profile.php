<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login-register/login.php");
    exit;
}

require "config/config.php";


/* =====================================================
   اطلاعات کاربر
===================================================== */

$user_id = $_SESSION['id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT first_name, last_name, username, profile_image
     FROM login_register2
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("کاربر پیدا نشد.");
}


$first_name    = $user['first_name'] ?? '';
$last_name     = $user['last_name'] ?? '';
$username      = $user['username'] ?? '';
$profile_image = $user['profile_image'] ?? '';


/* حرف اول اسم */

$avatar_letter = mb_substr(
    $first_name,
    0,
    1,
    'UTF-8'
);


/* =====================================================
   ذخیره تغییرات
===================================================== */

$message = '';
$message_type = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $new_first_name = trim($_POST['first_name'] ?? '');
    $new_last_name  = trim($_POST['last_name'] ?? '');


    if ($new_first_name === '' || $new_last_name === '') {

        $message = 'لطفاً نام و نام خانوادگی را وارد کنید.';
        $message_type = 'danger';

    } else {

        $new_profile_image = $profile_image;


        /* =================================================
           آپلود عکس جدید
        ================================================= */

        if (
            isset($_FILES['profile_image']) &&
            $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {

                $message = 'در آپلود عکس مشکلی به وجود آمد.';
                $message_type = 'danger';

            }

            elseif ($_FILES['profile_image']['size'] > 2 * 1024 * 1024) {

                $message = 'حجم عکس نباید بیشتر از 2 مگابایت باشد.';
                $message_type = 'danger';

            }

            else {

                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                $mime = finfo_file(
                    $finfo,
                    $_FILES['profile_image']['tmp_name']
                );

                finfo_close($finfo);


                $allowed_types = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp'
                ];


                if (!isset($allowed_types[$mime])) {

                    $message = 'فرمت عکس باید JPG، PNG یا WEBP باشد.';
                    $message_type = 'danger';

                }

                else {

                    $upload_dir = __DIR__ . '/uploads/profile/';


                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }


                    $extension = $allowed_types[$mime];


                    $new_filename =
                        time() . '_' .
                        bin2hex(random_bytes(8)) .
                        '.' . $extension;


                    $destination = $upload_dir . $new_filename;


                    if (
                        move_uploaded_file(
                            $_FILES['profile_image']['tmp_name'],
                            $destination
                        )
                    ) {

                        $new_profile_image = $new_filename;

                    }

                    else {

                        $message = 'ذخیره عکس با مشکل مواجه شد.';
                        $message_type = 'danger';
                    }
                }
            }
        }


        /* =================================================
           آپدیت دیتابیس
        ================================================= */

        if ($message_type !== 'danger') {

            $update = mysqli_prepare(
                $conn,
                "UPDATE login_register2
                 SET first_name = ?,
                     last_name = ?,
                     profile_image = ?
                 WHERE id = ?"
            );


            mysqli_stmt_bind_param(
                $update,
                "sssi",
                $new_first_name,
                $new_last_name,
                $new_profile_image,
                $user_id
            );


            if (mysqli_stmt_execute($update)) {


                /* حذف عکس قبلی */

                if (
                    $profile_image !== '' &&
                    $new_profile_image !== $profile_image
                ) {

                    $old_file =
                        __DIR__ .
                        '/uploads/profile/' .
                        basename($profile_image);


                    if (is_file($old_file)) {
                        unlink($old_file);
                    }
                }


                /* آپدیت Session */

                $_SESSION['first_name'] = $new_first_name;
                $_SESSION['last_name']  = $new_last_name;


                /* آپدیت اطلاعات صفحه */

                $first_name = $new_first_name;
                $last_name = $new_last_name;
                $profile_image = $new_profile_image;


                $avatar_letter = mb_substr(
                    $first_name,
                    0,
                    1,
                    'UTF-8'
                );


                $message = 'اطلاعات پروفایل با موفقیت ذخیره شد.';
                $message_type = 'success';

            }

            else {

                /* اگر ذخیره دیتابیس شکست خورد، عکس جدید حذف شود */

                if (
                    $new_profile_image !== $profile_image &&
                    $new_profile_image !== ''
                ) {

                    $new_file =
                        __DIR__ .
                        '/uploads/profile/' .
                        basename($new_profile_image);


                    if (is_file($new_file)) {
                        unlink($new_file);
                    }
                }


                $message = 'ذخیره اطلاعات با مشکل مواجه شد.';
                $message_type = 'danger';
            }
        }
    }
}


?>

<?php include "header.php"; ?>

<link
        rel="stylesheet"
        href="css/bootstrap-5.2.0-dist/css/bootstrap.rtl.min.css"
>

<link
        rel="stylesheet"
        href="css/style.css"
>

<script src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

<style>

    /* =====================================================
       RESET
    ===================================================== */

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }


    /* =====================================================
       BODY
    ===================================================== */

    body {

        font-family:
                Tahoma,
                Arial,
                sans-serif;

        color: #20364a;

        min-height: 100vh;
    }


    /* =====================================================
       PANEL
    ===================================================== */

    .d24-panel {

        min-height: calc(100vh - 1px);

        display: flex;
    }


    /* =====================================================
       CONTENT
    ===================================================== */

    .d24-content {

        flex: 1;

        width: 100%;

        padding: 42px 48px;

        max-width: 1450px;

        margin: auto;
    }


    /* =====================================================
       HEADER
    ===================================================== */

    .d24-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        margin-bottom: 25px;
    }


    .d24-header-title {

        color: #263e55;

        font-size: 28px;

        font-weight: 950;

        line-height: 1.5;
    }


    .d24-header-title span {

        color: #3978df;
    }


    .d24-header-description {

        margin-top: 7px;

        color: #8998a6;

        font-size: 11px;

        line-height: 2;
    }


    .d24-header-badge {

        padding: 12px 17px;

        border-radius: 13px;

        background: #ffffff;

        border: 1px solid #e2eaf2;

        color: #738798;

        font-size: 10px;

        font-weight: 800;

        box-shadow:
                0 7px 22px rgba(30, 75, 110, .04);
    }


    /* =====================================================
       PROFILE BOX
    ===================================================== */

    .d24-profile-box {

        background: #ffffff;

        border: 1px solid #e2eaf1;

        border-radius: 24px;

        padding: 32px;

        box-shadow:
                0 10px 30px rgba(30, 75, 110, .04);

        margin-bottom: 22px;
    }


    /* =====================================================
       PROFILE TOP
    ===================================================== */

    .d24-profile-top {

        display: flex;

        align-items: center;

        gap: 25px;

        padding-bottom: 28px;

        margin-bottom: 28px;

        border-bottom: 1px solid #edf2f7;
    }


    .d24-profile-big-avatar {

        width: 100px;

        height: 100px;

        flex-shrink: 0;

        border-radius: 25px;

        overflow: hidden;

        display: flex;

        align-items: center;

        justify-content: center;

        background:
                linear-gradient(
                        135deg,
                        #edf5ff,
                        #e7f8fa
                );

        border: 3px solid #ffffff;

        box-shadow:
                0 10px 30px rgba(45, 110, 180, .13);

        color: #3876dd;

        font-size: 35px;

        font-weight: 950;
    }


    .d24-profile-big-avatar img {

        width: 100%;

        height: 100%;

        display: block;

        object-fit: cover;
    }


    .d24-profile-main-name {

        color: #243c52;

        font-size: 21px;

        font-weight: 900;

        margin-bottom: 7px;
    }


    .d24-profile-main-user {

        color: #91a0ad;

        font-size: 11px;
    }


    /* =====================================================
       SECTION TITLE
    ===================================================== */

    .d24-profile-section-title {

        color: #294258;

        font-size: 15px;

        font-weight: 950;

        margin-bottom: 18px;
    }


    /* =====================================================
       FORM
    ===================================================== */

    .d24-form-row {

        display: grid;

        grid-template-columns:
        repeat(2, 1fr);

        gap: 17px;

        margin-bottom: 17px;
    }


    .d24-form-group {

        margin-bottom: 17px;
    }


    .d24-form-label {

        display: block;

        color: #526b7d;

        font-size: 11px;

        font-weight: 800;

        margin-bottom: 8px;
    }


    .d24-form-input {

        width: 100%;

        height: 48px;

        padding: 0 15px;

        border-radius: 13px;

        border: 1px solid #e0e8ef;

        background: #fafcfe;

        color: #294258;

        font-family: inherit;

        font-size: 12px;

        outline: none;

        transition: .25s ease;
    }


    .d24-form-input:focus {

        border-color: #7faeea;

        background: #ffffff;

        box-shadow:
                0 0 0 4px rgba(57, 120, 223, .06);
    }


    .d24-form-input[readonly] {

        background: #f3f6f9;

        color: #8a9aa7;

        cursor: not-allowed;
    }


    /* =====================================================
       UPLOAD
    ===================================================== */

    .d24-upload-box {

        padding: 18px;

        border-radius: 15px;

        background: #f8fafc;

        border: 1px dashed #d8e2eb;
    }


    .d24-upload-box input {

        width: 100%;

        font-family: inherit;

        font-size: 11px;
    }


    .d24-upload-help {

        color: #91a0ad;

        font-size: 10px;

        margin-top: 8px;
    }


    /* =====================================================
       SAVE BUTTON
    ===================================================== */

    .d24-save-button {

        border: none;

        border-radius: 13px;

        padding: 12px 24px;

        background:
                linear-gradient(
                        135deg,
                        #3978df,
                        #00a8a3
                );

        color: #ffffff;

        font-family: inherit;

        font-size: 11px;

        font-weight: 900;

        cursor: pointer;

        transition: .25s ease;

        box-shadow:
                0 10px 25px rgba(57, 120, 223, .12);
    }


    .d24-save-button:hover {

        transform: translateY(-2px);

        box-shadow:
                0 14px 30px rgba(57, 120, 223, .18);
    }


    /* =====================================================
       ALERT
    ===================================================== */

    .d24-profile-alert {

        padding: 13px 16px;

        border-radius: 13px;

        margin-bottom: 20px;

        font-size: 11px;

        font-weight: 700;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 1050px) {

        .d24-content {

            padding: 35px 28px;
        }
    }


    @media (max-width: 800px) {

        .d24-panel {

            display: block;
        }


        .d24-content {

            padding: 27px 18px;
        }


        .d24-header-title {

            font-size: 23px;
        }


        .d24-form-row {

            grid-template-columns: 1fr;
        }


        .d24-profile-top {

            flex-direction: column;

            text-align: center;
        }
    }


    @media (max-width: 500px) {

        .d24-content {

            padding: 21px 14px;
        }


        .d24-header {

            margin-bottom: 20px;
        }


        .d24-header-title {

            font-size: 21px;
        }


        .d24-header-description {

            font-size: 10px;
        }


        .d24-header-badge {

            display: none;
        }


        .d24-profile-box {

            padding: 20px;
        }

    }

</style>

<div class="d24-panel">

    <?php include "user_kenar.php"; ?>


    <!-- =====================================================
         CONTENT
    ===================================================== -->

    <main class="d24-content">


        <!-- HEADER -->

        <header class="d24-header">

            <div>

                <h1 class="d24-header-title overflow-visible">

                    ویرایش
                    <span>پروفایل</span>

                </h1>


                <p class="d24-header-description">

                    اطلاعات حساب کاربری خود را مشاهده و ویرایش کنید.

                </p>

            </div>


            <div class="d24-header-badge">

                ✦ پروفایل DIGI24

            </div>

        </header>


        <?php if ($message !== ''): ?>

            <div
                    class="d24-profile-alert alert alert-<?php echo $message_type; ?>"
            >

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <!-- PROFILE -->

        <section class="d24-profile-box">


            <!-- PROFILE TOP -->

            <div class="d24-profile-top">


                <div class="d24-profile-big-avatar">

                    <?php if (!empty($profile_image)): ?>

                        <img
                                src="uploads/profile/<?php echo htmlspecialchars($profile_image); ?>"
                                alt="عکس پروفایل"
                        >

                    <?php else: ?>

                        <?php echo htmlspecialchars($avatar_letter); ?>

                    <?php endif; ?>

                </div>


                <div>

                    <div class="d24-profile-main-name">

                        <?php

                        echo htmlspecialchars(
                            $first_name . ' ' . $last_name
                        );

                        ?>

                    </div>


                    <div class="d24-profile-main-user">

                        @<?php echo htmlspecialchars($username); ?>

                    </div>

                </div>


            </div>


            <!-- FORM -->

            <div class="d24-profile-section-title">

                اطلاعات حساب

            </div>


            <form
                    method="POST"
                    enctype="multipart/form-data"
            >


                <div class="d24-form-row">


                    <!-- FIRST NAME -->

                    <div class="d24-form-group">

                        <label class="d24-form-label">

                            نام

                        </label>


                        <input
                                type="text"
                                name="first_name"
                                class="d24-form-input"
                                value="<?php echo htmlspecialchars($first_name); ?>"
                                required
                        >

                    </div>


                    <!-- LAST NAME -->

                    <div class="d24-form-group">

                        <label class="d24-form-label">

                            نام خانوادگی

                        </label>


                        <input
                                type="text"
                                name="last_name"
                                class="d24-form-input"
                                value="<?php echo htmlspecialchars($last_name); ?>"
                                required
                        >

                    </div>


                </div>


                <!-- USERNAME -->

                <div class="d24-form-group">

                    <label class="d24-form-label">

                        نام کاربری

                    </label>


                    <input
                            type="text"
                            class="d24-form-input"
                            value="<?php echo htmlspecialchars($username); ?>"
                            readonly
                    >

                </div>


                <!-- PROFILE IMAGE -->

                <div class="d24-form-group">

                    <label class="d24-form-label">

                        عکس پروفایل

                    </label>


                    <div class="d24-upload-box">


                        <input
                                type="file"
                                name="profile_image"
                                accept="image/jpeg,image/png,image/webp"
                        >


                        <div class="d24-upload-help">

                            فرمت‌های مجاز: JPG ،PNG ،WEBP
                            — حداکثر حجم ۲ مگابایت

                        </div>


                    </div>

                </div>

                <!-- BUTTON -->

                <button
                        type="submit"
                        class="d24-save-button"
                        style="width: 20%;"

                >

                    ذخیره تغییرات

                </button>


            </form>


        </section>


    </main>

</div>
<script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
