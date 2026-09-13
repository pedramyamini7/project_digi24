<?php

//session_start();

require "../config/config.php";

/* =====================================================
   اطلاعات کاربر
===================================================== */

$user_id = $_SESSION['id'] ?? null;

$first_name = '';
$last_name = '';
$username = '';
$profile_image = '';

if ($user_id) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT first_name, last_name, username, profile_image
         FROM login_register2
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    // نتیجه کاربر را داخل متغیر جدا می‌ریزیم
    $user_result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($user_result);

    // مهم: statement را می‌بندیم تا کوئری‌های بعدی روی $conn
    // با خطای "commands out of sync" مواجه نشوند
    mysqli_stmt_close($stmt);

    if ($user) {
        $first_name    = $user['first_name'] ?? '';
        $last_name     = $user['last_name'] ?? '';
        $username      = $user['username'] ?? '';
        $profile_image = $user['profile_image'] ?? '';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css.css">


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>پنل مدیریت | داشبورد اول</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Google Font: Source Sans Pro -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="dist/css/bootstrap-rtl.min.css">
    <!-- template rtl version -->
    <link rel="stylesheet" href="dist/css/custom-style.css">

    <link rel="stylesheet" href="style.css">


    <!--    <style>-->
<!--        .tab1{-->
<!--            border:1px;-->
<!--            width:1000px;-->
<!--            margin:0 auto;-->
<!--        }-->
<!---->
<!--    </style>-->
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">


    <!-- کنار -->
    <style>

        /* =====================================================
           COLOR SYSTEM
        ===================================================== */

        :root {
            --digi-blue: #1677ff;
            --digi-blue-dark: #0f5ed7;

            --digi-orange: #ff8a00;
            --digi-orange-light: #fff1df;

            --digi-purple: #7c5cff;
            --digi-purple-light: #eeeaff;

            --digi-green: #16a66a;
            --digi-green-light: #e4f8ef;

            --digi-red: #ef476f;
            --digi-red-light: #ffe8ee;

            --digi-text: #172033;
            --digi-muted: #8b95a7;

            --digi-border: #e9edf3;
        }


        /* =====================================================
           MAIN SIDEBAR
        ===================================================== */

        .main-sidebar {
            direction: rtl;

            background:
                    linear-gradient(
                            180deg,
                            #ffffff 0%,
                            #f8fafc 100%
                    ) !important;

            border-left: 1px solid var(--digi-border);

            box-shadow:
                    -12px 0 35px rgba(25, 35, 55, .06) !important;
        }


        /* =====================================================
           BRAND
        ===================================================== */

        .main-sidebar .brand-link {

            direction: rtl;

            height: 65px;

            display: flex;
            align-items: center;

            padding: 0 16px;

            background: #ffffff;

            border-bottom: 1px solid var(--digi-border) !important;

            color: var(--digi-text) !important;
        }


        .main-sidebar .brand-image {

            width: 38px;
            height: 38px;

            margin-left: 11px;
            margin-right: 0;

            opacity: 1 !important;

            box-shadow:
                    0 5px 15px rgba(22, 119, 255, .15);
        }


        .main-sidebar .brand-text {

            font-size: 15px;

            font-weight: 900 !important;

            color: var(--digi-text);
        }


        /* =====================================================
           SIDEBAR + SCROLL
        ===================================================== */

        .main-sidebar .sidebar {

            direction: rtl !important;

            height: calc(100vh - 65px);

            padding: 0 9px 25px;

            background: transparent !important;

            overflow-y: auto !important;
            overflow-x: hidden !important;

            scrollbar-width: thin;

            scrollbar-color:
                    #cbd5e1
                    transparent;
        }


        /* =====================================================
           SCROLLBAR
        ===================================================== */

        .main-sidebar .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .main-sidebar .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .main-sidebar .sidebar::-webkit-scrollbar-thumb {

            background:
                    linear-gradient(
                            180deg,
                            var(--digi-blue),
                            var(--digi-purple)
                    );

            border-radius: 20px;
        }


        /* =====================================================
           USER PANEL
        ===================================================== */

        .main-sidebar .user-panel {

            position: relative;

            margin:
                    14px 3px 14px !important;

            padding:
                    13px 12px !important;

            border:
                    1px solid var(--digi-border) !important;

            border-radius: 17px;

            background:
                    linear-gradient(
                            135deg,
                            #ffffff,
                            #f7faff
                    );

            box-shadow:
                    0 7px 22px rgba(31, 41, 55, .05);

            overflow: hidden;
        }


        /* نور تزئینی */

        .main-sidebar .user-panel::before {

            content: "";

            position: absolute;

            width: 100px;
            height: 100px;

            left: -45px;
            top: -55px;

            border-radius: 50%;

            background:
                    radial-gradient(
                            circle,
                            rgba(22, 119, 255, .12),
                            transparent 70%
                    );
        }


        /* =====================================================
           USER AVATAR
        ===================================================== */

        .main-sidebar .user-panel .image {

            width: 43px;
            height: 43px;

            margin-left: 11px !important;
            margin-right: 0 !important;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            background:
                    linear-gradient(
                            135deg,
                            var(--digi-blue),
                            var(--digi-purple)
                    );

            box-shadow:
                    0 7px 18px
                    rgba(22, 119, 255, .25);

            position: relative;
        }


        .main-sidebar .user-panel .image::after {

            /*content: "پ";*/

            color: #ffffff;

            font-size: 17px;

            font-weight: 900;
        }


        /* =====================================================
           USER NAME
        ===================================================== */

        .main-sidebar .user-panel .info {

            padding: 0 !important;

            display: flex;
            align-items: center;

            position: relative;
        }


        .main-sidebar .user-panel .info a {

            color: var(--digi-text) !important;

            font-size: 12px;

            font-weight: 900;

            text-decoration: none;

            transition: .2s ease;
        }


        .main-sidebar .user-panel .info a:hover {

            color: var(--digi-blue) !important;
        }


        /* =====================================================
           NAV
        ===================================================== */

        .main-sidebar nav {

            margin-top: 4px !important;
        }


        /* =====================================================
           NAV LIST
        ===================================================== */

        .main-sidebar .nav-sidebar {

            padding: 0;

            margin: 0;
        }


        /* =====================================================
           NAV ITEM
        ===================================================== */

        .main-sidebar .nav-sidebar .nav-item {

            margin-bottom: 5px;
        }


        /* =====================================================
           NAV LINK
        ===================================================== */

        .main-sidebar .nav-sidebar .nav-link {

            position: relative;

            min-height: 47px;

            padding:
                    7px 9px !important;

            display: flex;
            align-items: center;

            border-radius: 14px;

            background: transparent;

            color: #667085 !important;

            font-size: 11px;

            font-weight: 800;

            direction: rtl;

            transition:
                    background .2s ease,
                    color .2s ease,
                    transform .2s ease,
                    box-shadow .2s ease;
        }


        /* =====================================================
           ICON BOX
        ===================================================== */

        .main-sidebar .nav-sidebar .nav-link .nav-icon {

            width: 35px;
            height: 35px;

            margin-left: 10px;
            margin-right: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background: #f1f4f8;

            color: #7d8797;

            font-size: 14px;

            transition:
                    background .2s ease,
                    color .2s ease,
                    transform .2s ease,
                    box-shadow .2s ease;
        }


        /* =====================================================
           HOVER
        ===================================================== */

        .main-sidebar .nav-sidebar .nav-link:hover {

            background:
                    #f4f8ff;

            color:
                    var(--digi-blue) !important;

            transform:
                    translateX(-2px);
        }


        .main-sidebar .nav-sidebar .nav-link:hover .nav-icon {

            background:
                    var(--digi-blue);

            color:
                    #ffffff;

            transform:
                    scale(1.07);

            box-shadow:
                    0 5px 13px
                    rgba(22, 119, 255, .22);
        }


        /* =====================================================
           ACTIVE
        ===================================================== */

        .main-sidebar .nav-sidebar .nav-link.active {

            background:
                    #ffffff !important;

            color:
                    var(--digi-text) !important;

            border:
                    1px solid #e7ebf2;

            box-shadow:
                    0 7px 20px
                    rgba(31, 41, 55, .07);

            transform:
                    translateX(-2px);
        }


        /* =====================================================
           ACTIVE BLUE LINE
        ===================================================== */

        .main-sidebar .nav-sidebar .nav-link.active::before {

            content: "";

            position: absolute;

            right: -1px;

            top: 9px;
            bottom: 9px;

            width: 4px;

            border-radius: 10px;

            background:
                    linear-gradient(
                            180deg,
                            var(--digi-blue),
                            var(--digi-purple)
                    );
        }


        /* =====================================================
           ACTIVE ICON
        ===================================================== */

        .main-sidebar
        .nav-sidebar
        .nav-link.active
        .nav-icon {

            background:
                    linear-gradient(
                            135deg,
                            var(--digi-blue),
                            var(--digi-purple)
                    );

            color:
                    #ffffff;

            box-shadow:
                    0 6px 16px
                    rgba(22, 119, 255, .25);

            transform:
                    scale(1.04);
        }


        /* =====================================================
           ACTIVE HOVER
        ===================================================== */

        .main-sidebar
        .nav-sidebar
        .nav-link.active:hover {

            background:
                    #ffffff !important;

            color:
                    var(--digi-blue) !important;
        }


        /* =====================================================
           DIFFERENT ICON COLORS
           بر اساس جایگاه آیتم
        ===================================================== */


        /* آیتم اول */
        .main-sidebar .nav-sidebar .nav-item:nth-child(1)
        .nav-icon {

            background:
                    #e8f1ff;

            color:
                    #1677ff;
        }


        /* آیتم دوم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(2)
        .nav-icon {

            background:
                    var(--purple-light, #eeeaff);

            color:
                    #7c5cff;
        }


        /* آیتم سوم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(3)
        .nav-icon {

            background:
                    #fff1df;

            color:
                    #ff8a00;
        }


        /* آیتم چهارم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(4)
        .nav-icon {

            background:
                    #e4f8ef;

            color:
                    #16a66a;
        }


        /* آیتم پنجم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(5)
        .nav-icon {

            background:
                    #eeeaff;

            color:
                    #7c5cff;
        }


        /* آیتم ششم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(6)
        .nav-icon {

            background:
                    #fff1df;

            color:
                    #ff8a00;
        }


        /* آیتم هفتم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(7)
        .nav-icon {

            background:
                    #e8f1ff;

            color:
                    #1677ff;
        }


        /* آیتم هشتم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(8)
        .nav-icon {

            background:
                    #e4f8ef;

            color:
                    #16a66a;
        }


        /* آیتم نهم */
        .main-sidebar .nav-sidebar .nav-item:nth-child(9)
        .nav-icon {

            background:
                    #ffe8ee;

            color:
                    #ef476f;
        }


        /* =====================================================
           TREEVIEW ARROW
        ===================================================== */

        .main-sidebar
        .nav-sidebar
        .nav-link
        > .right {

            margin-right: auto;

            margin-left: 0;

            color:
                    #a1a9b6;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 768px) {

            .main-sidebar .brand-link {

                height: 57px;
            }

            .main-sidebar .sidebar {

                height:
                        calc(100vh - 57px);

                padding:
                        0 7px 18px;
            }

            .main-sidebar .nav-sidebar .nav-link {

                min-height: 45px;

                font-size: 10px;
            }

            .main-sidebar
            .nav-sidebar
            .nav-link
            .nav-icon {

                width: 33px;
                height: 33px;

                font-size: 13px;
            }
        }

    </style>


    <!-- =====================================================
         MAIN SIDEBAR
    ===================================================== -->

    <aside class="main-sidebar sidebar-dark-primary elevation-4">


        <!-- =================================================
             BRAND
        ================================================== -->

        <a href="index3.html" class="brand-link">



            <span class="brand-text font-weight-light">
            پنل ادمین
        </span>

        </a>


        <!-- =================================================
             SIDEBAR
        ================================================== -->

        <div class="sidebar">


            <!-- =================================================
                 USER PANEL
            ================================================== -->

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                <div class="image">
                    <i class="nav-icon fas " style="position:relative; right: 20%; "><img src="../image/icon/user-square-ad.svg" alt="" width="25px"></i>

                </div>

                <div class="info">
<strong>
                            <?php echo htmlspecialchars($first_name); ?>
</strong>

                </div>

            </div>


            <!-- =================================================
                 SIDEBAR MENU
            ================================================== -->

            <nav class="mt-2">

                <ul
                        class="nav nav-pills nav-sidebar flex-column"
                        data-widget="treeview"
                        role="menu"
                        data-accordion="false"
                >

                    <?php

                    include "kenar.php";

                    ?>

                </ul>

            </nav>


        </div>

    </aside>
    <!--/ کنار -->

