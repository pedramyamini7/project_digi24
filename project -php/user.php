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

?>

<?php include "header.php"; ?>


<link rel="stylesheet" href="css/bootstrap-5.2.0-dist/css/bootstrap.rtl.min.css">
<link rel="stylesheet" href="css/style.css">

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

        /*background: #f5f8fc;*/

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
       SIDEBAR
    ===================================================== */

    .d24-sidebar {

        width: 270px;

        min-height: 100vh;

        padding: 28px 17px;

        position: relative;

        overflow: hidden;

        background: #ffffff;

        border-left: 1px solid #e5edf5;

        box-shadow:
                -8px 0 30px rgba(35, 75, 110, .035);

    }


    /* دایره تزئینی */

    .d24-sidebar::before {

        content: "";

        position: absolute;

        width: 300px;

        height: 300px;

        border-radius: 50%;

        border: 1px solid rgba(55, 125, 220, .06);

        top: -190px;

        left: -130px;

    }


    /* دایره پایین */

    .d24-sidebar::after {

        content: "";

        position: absolute;

        width: 250px;

        height: 250px;

        border-radius: 50%;

        border: 1px solid rgba(0, 170, 190, .05);

        bottom: -160px;

        right: -130px;

    }


    /* =====================================================
       LOGO
    ===================================================== */

    .d24-logo {

        position: relative;

        z-index: 2;

        height: 55px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-bottom: 1px solid #edf2f7;

        margin-bottom: 28px;
    }


    .d24-logo-name {

        color: #3876dd;

        font-size: 22px;

        font-weight: 950;

        letter-spacing: 2px;
    }


    /* =====================================================
       PROFILE
    ===================================================== */

    .d24-profile {

        position: relative;

        z-index: 2;

        text-align: center;

        padding-bottom: 28px;

        border-bottom: 1px solid #edf2f7;

        margin-bottom: 25px;
    }


    /* =====================================================
       AVATAR
    ===================================================== */

    .d24-avatar {

        width: 82px;

        height: 82px;

        margin: auto;

        border-radius: 25px;

        display: flex;

        align-items: center;

        justify-content: center;

        overflow: hidden;

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

        font-size: 27px;

        font-weight: 950;
    }


    .d24-avatar img {

        width: 100%;

        height: 100%;

        display: block;

        object-fit: cover;
    }


    /* =====================================================
       PROFILE NAME
    ===================================================== */

    .d24-profile-name {

        margin-top: 14px;

        color: #243c52;

        font-size: 14px;

        font-weight: 900;
    }


    /* =====================================================
       USERNAME
    ===================================================== */

    .d24-profile-user {

        margin-top: 7px;

        color: #91a0ad;

        font-size: 10px;
    }


    /* =====================================================
       MENU
    ===================================================== */

    .d24-menu {

        position: relative;

        z-index: 2;

        display: flex;

        flex-direction: column;

        gap: 7px;
    }


    .d24-menu-title {

        color: #9baab7;

        font-size: 10px;

        font-weight: 800;

        padding: 0 14px 10px;
    }


    /* =====================================================
       MENU LINK
    ===================================================== */

    .d24-menu a {

        min-height: 54px;

        display: flex;

        align-items: center;

        gap: 12px;

        padding: 0 12px;

        border-radius: 15px;

        color: #64798b;

        text-decoration: none;

        font-size: 11px;

        font-weight: 800;

        transition: .25s ease;

        position: relative;
    }


    /* hover */

    .d24-menu a:hover {

        color: #3876dd;

        background: #f3f7fc;

        transform: translateX(-3px);
    }


    /* active */

    .d24-menu a.active {

        color: #3978df;

        background:

                linear-gradient(
                        90deg,
                        #edf5ff,
                        #f5f9ff
                );

        box-shadow:
                0 7px 20px rgba(55, 120, 220, .07);
    }


    /* خط کنار آیتم فعال */

    .d24-menu a.active::before {

        content: "";

        position: absolute;

        right: 0;

        top: 13px;

        width: 3px;

        height: 28px;

        border-radius: 10px;

        background:

                linear-gradient(
                        #3978df,
                        #00a8b0
                );
    }


    /* =====================================================
       MENU ICON
    ===================================================== */

    .d24-menu-icon {

        width: 38px;

        height: 38px;

        flex-shrink: 0;

        border-radius: 12px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: #f3f7fb;

        font-size: 17px;

        transition: .25s ease;
    }


    /* hover icon */

    .d24-menu a:hover .d24-menu-icon {

        background: #eaf3ff;
    }


    /* active icon */

    .d24-menu a.active .d24-menu-icon {

        background:

                linear-gradient(
                        135deg,
                        #e4f0ff,
                        #e4f8f7
                );
    }


    /* =====================================================
       LOGOUT
    ===================================================== */

    .d24-logout {

        position: absolute;

        z-index: 2;

        bottom: 25px;

        left: 17px;

        right: 17px;
    }


    .d24-logout a {

        height: 50px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 9px;

        color: #8494a1;

        text-decoration: none;

        border-radius: 14px;

        background: #f7f9fb;

        border: 1px solid #edf1f5;

        font-size: 10px;

        font-weight: 900;

        transition: .25s ease;
    }


    .d24-logout a:hover {

        color: #e25555;

        background: #fff5f5;

        border-color: #f6dddd;

        transform: translateY(-2px);
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
       WELCOME
    ===================================================== */

    .d24-welcome {

        position: relative;

        overflow: hidden;

        min-height: 190px;

        padding: 32px 35px;

        border-radius: 24px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        background:

                linear-gradient(
                        120deg,
                        #3e73df 0%,
                        #4b83e8 48%,
                        #53a8c4 100%
                );

        color: white;

        box-shadow:
                0 20px 40px rgba(50, 115, 215, .16);

        margin-bottom: 23px;
    }


    /* دایره */

    .d24-welcome::before {

        content: "";

        position: absolute;

        width: 350px;

        height: 350px;

        border-radius: 50%;

        border:
                1px solid rgba(255,255,255,.13);

        left: -140px;

        top: -215px;
    }


    /* دایره دوم */

    .d24-welcome::after {

        content: "";

        position: absolute;

        width: 290px;

        height: 290px;

        border-radius: 50%;

        background:
                rgba(255,255,255,.06);

        right: -130px;

        bottom: -175px;
    }


    /* =====================================================
       WELCOME CONTENT
    ===================================================== */

    .d24-welcome-content {

        position: relative;

        z-index: 2;
    }


    .d24-welcome-content h2 {

        font-size: 22px;

        font-weight: 950;

        margin-bottom: 10px;
    }


    .d24-welcome-content p {

        color: #e7f4ff;

        font-size: 11px;

        line-height: 2;
    }


    /* =====================================================
       WELCOME BUTTON
    ===================================================== */

    .d24-welcome-button {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        margin-top: 17px;

        padding: 10px 18px;

        border-radius: 11px;

        background:
                rgba(255,255,255,.13);

        border:
                1px solid rgba(255,255,255,.20);

        color: white;

        text-decoration: none;

        font-size: 10px;

        font-weight: 900;

        transition: .25s ease;
    }


    .d24-welcome-button:hover {

        background: white;

        color: #3978df;
    }


    /* =====================================================
       WELCOME SYMBOL
    ===================================================== */

    .d24-welcome-symbol {

        position: relative;

        z-index: 2;

        width: 95px;

        height: 95px;

        border-radius: 29px;

        display: flex;

        align-items: center;

        justify-content: center;

        background:
                rgba(255,255,255,.11);

        border:
                1px solid rgba(255,255,255,.20);

        font-size: 40px;

        box-shadow:
                0 15px 30px rgba(0,0,0,.07);
    }


    /* =====================================================
       STATS
    ===================================================== */

    .d24-stats {

        display: grid;

        grid-template-columns:
        repeat(3, 1fr);

        gap: 17px;

        margin-bottom: 28px;
    }


    .d24-stat {

        position: relative;

        overflow: hidden;

        min-height: 110px;

        padding: 22px;

        display: flex;

        align-items: center;

        gap: 15px;

        background: #ffffff;

        border: 1px solid #e1eaf2;

        border-radius: 18px;

        box-shadow:
                0 8px 25px rgba(30, 75, 110, .035);

        transition: .25s ease;
    }


    .d24-stat:hover {

        transform: translateY(-4px);

        box-shadow:
                0 15px 32px rgba(30, 100, 160, .07);
    }


    /* =====================================================
       STAT ICON
    ===================================================== */

    .d24-stat-icon {

        width: 54px;

        height: 54px;

        flex-shrink: 0;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 16px;

        background:

                linear-gradient(
                        135deg,
                        #edf5ff,
                        #e8f8f7
                );

        color: #3978df;

        font-size: 20px;
    }


    /* =====================================================
       STAT TEXT
    ===================================================== */

    .d24-stat h3 {

        color: #243c52;

        font-size: 20px;

        font-weight: 950;
    }


    .d24-stat p {

        margin-top: 5px;

        color: #8797a5;

        font-size: 10px;
    }


    /* =====================================================
       SECTION HEADER
    ===================================================== */

    .d24-section-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        margin-bottom: 15px;
    }


    .d24-section-title {

        color: #294258;

        font-size: 15px;

        font-weight: 950;
    }


    .d24-section-line {

        width: 42px;

        height: 3px;

        border-radius: 20px;

        background:

                linear-gradient(
                        90deg,
                        #3978df,
                        #00aaa8
                );
    }


    /* =====================================================
       CARDS
    ===================================================== */

    .d24-cards {

        display: grid;

        grid-template-columns:
        repeat(2, 1fr);

        gap: 17px;
    }


    .d24-card {

        position: relative;

        min-height: 120px;

        padding: 23px;

        display: flex;

        align-items: center;

        gap: 17px;

        background:
                rgba(255,255,255,.96);

        border:
                1px solid #e2eaf1;

        border-radius: 18px;

        text-decoration: none;

        box-shadow:
                0 8px 25px rgba(30,80,120,.035);

        transition: .28s ease;

        overflow: hidden;
    }


    /* خط کناری */

    .d24-card::before {

        content: "";

        position: absolute;

        width: 4px;

        height: 0;

        right: 0;

        top: 50%;

        background:

                linear-gradient(
                        #3978df,
                        #00a8a3
                );

        border-radius: 10px;

        transition: .28s ease;
    }


    .d24-card:hover {

        transform: translateY(-4px);

        border-color: #cfe1f4;

        box-shadow:
                0 18px 35px rgba(20,105,180,.075);
    }


    .d24-card:hover::before {

        height: 65%;

        top: 17%;
    }


    /* =====================================================
       CARD ICON
    ===================================================== */

    .d24-card-icon {

        width: 60px;

        height: 60px;

        flex-shrink: 0;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 18px;

        background:

                linear-gradient(
                        135deg,
                        #edf5ff,
                        #e4f8f7
                );

        color: #3978df;

        font-size: 23px;
    }


    /* =====================================================
       CARD TITLE
    ===================================================== */

    .d24-card-title {

        color: #263e54;

        font-size: 13px;

        font-weight: 950;

        margin-bottom: 7px;
    }


    /* =====================================================
       CARD TEXT
    ===================================================== */

    .d24-card-text {

        color: #8796a3;

        font-size: 10px;

        line-height: 1.9;
    }


    /* =====================================================
       CARD ARROW
    ===================================================== */

    .d24-card-arrow {

        margin-right: auto;

        width: 33px;

        height: 33px;

        border-radius: 10px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: #f3f7fb;

        color: #8da0af;

        font-size: 15px;

        transition: .25s;
    }


    .d24-card:hover .d24-card-arrow {

        background: #eaf3ff;

        color: #3978df;

        transform: translateX(-4px);
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 1050px) {

        .d24-sidebar {

            width: 245px;
        }

        .d24-content {

            padding: 35px 28px;
        }

    }


    @media (max-width: 800px) {

        .d24-panel {

            display: block;
        }


        .d24-sidebar {

            width: 100%;

            min-height: auto;

            padding: 17px;

            border-left: none;

            border-bottom:
                    1px solid #e5edf5;

            border-radius:
                    0 0 22px 22px;
        }


        .d24-logo {

            height: 48px;

            margin-bottom: 15px;

            border-bottom: 1px solid #edf2f7;
        }


        .d24-profile {

            display: none;
        }


        .d24-menu {

            flex-direction: row;

            overflow-x: auto;

            padding-bottom: 3px;

            scrollbar-width: none;
        }


        .d24-menu::-webkit-scrollbar {

            display: none;
        }


        .d24-menu-title {

            display: none;
        }


        .d24-menu a {

            min-width: 120px;

            justify-content: center;

            font-size: 10px;
        }


        .d24-menu a.active::before {

            display: none;
        }


        .d24-logout {

            display: none;
        }


        .d24-content {

            padding: 27px 18px;
        }


        .d24-stats {

            grid-template-columns: 1fr;
        }


        .d24-cards {

            grid-template-columns: 1fr;
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

            font-size: 22px;
        }


        .d24-header-description {

            font-size: 10px;
        }


        .d24-header-badge {

            display: none;
        }


        .d24-welcome {

            min-height: 170px;

            padding: 23px;

            border-radius: 21px;
        }


        .d24-welcome-content h2 {

            font-size: 18px;
        }


        .d24-welcome-content p {

            font-size: 10px;
        }


        .d24-welcome-symbol {

            width: 65px;

            height: 65px;

            border-radius: 19px;

            font-size: 27px;
        }


        .d24-stat {

            padding: 18px;
        }


        .d24-card {

            min-height: 112px;

            padding: 18px;
        }


        .d24-card-icon {

            width: 51px;

            height: 51px;

            border-radius: 15px;

            font-size: 19px;
        }


        .d24-card-title {

            font-size: 12px;
        }

    }

</style>


<div class="d24-panel">


<?php
include "user_kenar.php";
?>

    <!-- =====================================================
         CONTENT
    ===================================================== -->

    <main class="d24-content">


        <!-- HEADER -->

        <header class="d24-header">


            <div>

                <h1 class="d24-header-title overflow-visible">


                    <span>

                        <?php

                        echo htmlspecialchars(
                            $first_name . " " . $last_name
                        );

                        ?>

                    </span>


                </h1>


                <p class="d24-header-description">

                    به پنل کاربری DIGI24 خوش آمدید.
                    حساب کاربری خود را از اینجا مدیریت کنید.

                </p>

            </div>


            <div class="d24-header-badge">

                ✦ پنل کاربری DIGI24

            </div>


        </header>


        <!-- =================================================
             WELCOME
        ================================================== -->

        <section class="d24-welcome">


            <div class="d24-welcome-content">


                <h2>

                    خوش اومدی

                    <?php

                    echo htmlspecialchars($first_name);

                    ?>

                    🌊

                </h2>


                <p>

                    همه چیز برای مدیریت حساب،
                    سفارش‌ها و علاقه‌مندی‌های شما آماده است.

                </p>


                <a
                        href="profile.php"
                        class="d24-welcome-button"
                >

                    مشاهده پروفایل ←

                </a>


            </div>


            <div class="d24-welcome-symbol">

                ✦

            </div>


        </section>


        <!-- =================================================
             STATS
        ================================================== -->

        <div class="d24-stats">


            <div class="d24-stat">


                <div class="d24-stat-icon">
                    👤
                </div>


                <div>

                    <h3>
                        ۱
                    </h3>

                    <p>
                        حساب کاربری
                    </p>

                </div>


            </div>


            <div class="d24-stat">


                <div class="d24-stat-icon">
                    📦
                </div>


                <div>

                    <h3>
                        ۰
                    </h3>

                    <p>
                        سفارش ثبت شده
                    </p>

                </div>


            </div>


            <div class="d24-stat">


                <div class="d24-stat-icon">
                    ❤️
                </div>


                <div>

                    <h3>
                        ۰
                    </h3>

                    <p>
                        محصولات مورد علاقه
                    </p>

                </div>


            </div>


        </div>


        <!-- =================================================
             QUICK ACCESS
        ================================================== -->

        <div class="d24-section-header">


            <h2 class="d24-section-title">

                دسترسی سریع

            </h2>


            <div class="d24-section-line"></div>


        </div>


        <div class="d24-cards">


            <!-- PROFILE -->

            <a
                    href="profile.php"
                    class="d24-card"
            >


                <div class="d24-card-icon">
                    👤
                </div>


                <div>

                    <h3 class="d24-card-title">
                        پروفایل
                    </h3>

                    <p class="d24-card-text">
                        مشاهده و مدیریت اطلاعات حساب کاربری
                    </p>

                </div>


                <span class="d24-card-arrow">
                    ←
                </span>


            </a>


            <!-- ORDERS -->

            <a
                    href="cart.php"
                    class="d24-card"
            >


                <div class="d24-card-icon">
                    📦
                </div>


                <div>

                    <h3 class="d24-card-title">
                        سفارش‌های من
                    </h3>

                    <p class="d24-card-text">
                        مشاهده سفارش‌ها و وضعیت آن‌ها
                    </p>

                </div>


                <span class="d24-card-arrow">
                    ←
                </span>


            </a>


            <!-- FAVORITES -->

            <a
                    href="favorites.php"
                    class="d24-card"
            >


                <div class="d24-card-icon">
                    ❤️
                </div>


                <div>

                    <h3 class="d24-card-title">
                        علاقه‌مندی‌ها
                    </h3>

                    <p class="d24-card-text">
                        محصولات مورد علاقه خود را مشاهده کنید
                    </p>

                </div>


                <span class="d24-card-arrow">
                    ←
                </span>


            </a>


            <!-- SETTINGS -->



        </div>


    </main>


</div>

<script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
