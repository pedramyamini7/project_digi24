<?php


require "config/config.php";


/* =====================================================
   اطلاعات کاربر
===================================================== */

$user_id = $_SESSION['id'] ?? null;

$first_name = '';
$last_name = '';
$username = '';
$profile_image = '';

if ($user_id) {

    $header_user_stmt = mysqli_prepare(
        $conn,
        "SELECT first_name, last_name, username, profile_image
         FROM login_register2
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($header_user_stmt, "i", $user_id);
    mysqli_stmt_execute($header_user_stmt);

    $header_user_result = mysqli_stmt_get_result($header_user_stmt);

    $header_user = mysqli_fetch_assoc($header_user_result);

    // statement را می‌بندیم تا کوئری‌های بعدی روی $conn
    // (چه در همین فایل، چه در فایلی که این هدر را include می‌کند)
    // دچار تداخل نشوند
    mysqli_stmt_close($header_user_stmt);

    if ($header_user) {
        $first_name    = $header_user['first_name'] ?? '';
        $last_name     = $header_user['last_name'] ?? '';
        $username      = $header_user['username'] ?? '';
        $profile_image = $header_user['profile_image'] ?? '';
    }
}

?>
<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DIGI24</title>
    <link rel="stylesheet" href="css/bootstrap-5.2.0-dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .userax11{
            background-color: transparent !important;
            transition: 0.2s;
        }
        .userax11.show {
            background-color: rgba(0, 129, 255, 0.2) !important;
            border-color: rgba(0, 129, 255, 0.2) !important;
        }
    </style>


</head>
<body dir="rtl" class="overflow-visible">
<br>
<!--navbar-->
<div class="row d-flex justify-content-center  overflow-visible" style="position: relative; z-index: 1000;">




    <div class="row d-flex justify-content-center overflow-visible">
        <!--logo-->
        <div class="col-2 d-flex align-items-center">
            <a href="index.php"><img src="image/logo1.png" alt="" class="img img-fluid"></a>
        </div>
        <!--/logo-->

        <!--search-->
        <div class="col-5">
            <div class="mb-3">
                <input type="text" class="form-control inp-search text-dark" id="formGroupExampleInput"  placeholder="جستجو">
            </div>
        </div>
        <!--/search-->

        <div class="col-3">
        </div>

        <!--user & سبد خرید-->
        <div class="col-2 r d-flex justify-content-center align-items-center gap-2 overflow-visible">



            <div class="btn-group" style="position: relative; overflow: visible;">

                <button
                        class="userax11"
                        type="button"

                    <?php if ($user_id) { ?>

                        data-bs-toggle="dropdown"
                        aria-expanded="false"

                    <?php } else { ?>

                        onclick="window.location.href='login-register/login.php';"

                    <?php } ?>
                >

                    <img
                            src="image/user.svg"
                            alt=""
                            class="ax-nav"
                    >

                </button>


                <?php if ($user_id) { ?>

                    <ul class="dropdown-menu" style="width: 14vw;">

                        <!-- پروفایل -->

                        <li>

                            <a class="dropdown-item" href="user.php">
                                <?php
                                echo htmlspecialchars(
                                    $first_name . " " . $last_name
                                );
                                ?>
                                <img src="image/icon/angle-left(3).svg" style=" width: 25px; position: absolute; left: 0; top: 7%;" alt="">

                            </a>

                        </li>



                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                            <li>

                                <div class="dropdown-divider" style=" width: 80%; margin: auto;background-color: rgba(161,161,161,0.2); "></div>

                            </li>

                            <!-- پنل مدیریت -->

                            <li>

                                <a class="dropdown-item" href="admin/index.php">

                                    <img src="image/icon/user-square.svg" style=" width: 25px; top: 7%; " alt="">
                                    پنل مدیریت
                                </a>

                            </li>

                        <?php } ?>


                        <li>

                            <div class="dropdown-divider" style=" width: 80%; margin: auto;background-color: rgba(161,161,161,0.2); "></div>

                        </li>
                        <!-- سبد خرید -->

                        <li>

                            <a
                                    class="dropdown-item"
                                    href="cart.php"
                            >

                                <img
                                        src="image/icon/shopping-bag.svg"
                                        style="
                            width: 25px;
                            top: 7%;
                        "
                                        alt=""
                                >

                                سبد خرید

                            </a>

                        </li>


                        <li>

                            <div class="dropdown-divider" style=" width: 80%; margin: auto;background-color: rgba(161,161,161,0.2); "></div>

                        </li>


                        <!-- علاقه مندی ها -->

                        <li>

                            <a
                                    class="dropdown-item"
                                    href="favorite.php"
                            >

                                <img
                                        src="image/icon/heart-sign.svg"
                                        style="
                            width: 25px;
                            top: 7%;
                        "
                                        alt=""
                                >

                                علاقه مندی ها

                            </a>

                        </li>

                        <li>

                            <div class="dropdown-divider" style=" width: 80%; margin: auto;background-color: rgba(161,161,161,0.2); "></div>

                        </li>



                        <!-- خروج -->

                        <li>

                            <a
                                    class="dropdown-item"
                                    href="login-register/logout.php"
                            >

                                <img
                                        src="image/icon/signout.svg"
                                        style="
                            width: 25px;
                            top: 7%;
                        "
                                        alt=""
                                >

                                خروج از حساب

                            </a>

                        </li>

                    </ul>

                <?php } ?>

            </div>

<!-- --------------------------------------------->
            <hr class="hr-saf">

            <!--            ------------------>
            <a href="cart.php">
                <img src="image/shopping-cart-alt.svg" alt="" class="ax-nav">
            </a>
        </div>
        <!--user & سبد خرید-->
        <script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>




    </div>
    <hr>
    <div class="row d-flex justify-content-center overflow-visible">

        <nav class="navbar navbar-expand-lg bg-body-tertiary overflow-visible">
            <div class="container-fluid">
                <!-- حالت بازشونده-->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <nav class="navbar bg-body-tertiary fixed-top">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                                <div class="offcanvas-header ">
                                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">DIGI24</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>

                                <div class="offcanvas-body ">
                                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3 gap-1">
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav"  href="index.php">صفحه اصلی</a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="daste-mahsool.php">دوربین عکاسی</a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="daste-mahsool-film.php">فیلم برداری </a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="daste-mahsool-lenz.php"> لنز دوربین عکاسی </a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="daste-mahsool-var.php"> دوربین های ورزشی </a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="daste-mahsool-noor.php"> نورپردازی </a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="daste-mahsool-jan.php"> لوازم جانبی </a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="maghalat.php"> وبلاگ </a>
                                        </li>
                                        <li class="nav-item d-flex justify-content-center">
                                            <a class="nav-link t-nav" href="ertebat-ba-ma.php"> تماس با ما </a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </nav>
                </button>
                <!--/ حالت بازشونده-->

            </div>
        </nav>

    </div>
</div>
<!--/navbar-->