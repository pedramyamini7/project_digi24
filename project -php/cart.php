<?php

session_start();


// =====================================================
// بررسی ورود کاربر
// =====================================================

if (!isset($_SESSION['id'])) {
    header("Location: login-register/login.php");
    exit;
}


// =====================================================
// اتصال به دیتابیس
// =====================================================

require "config/config.php";


// =====================================================
// گرفتن اطلاعات کاربر
// =====================================================

$user_id = $_SESSION['id'];

$user_sql = "SELECT first_name, last_name, username, profile_image
             FROM login_register2
             WHERE id = ?";

$user_stmt = mysqli_prepare($conn, $user_sql);

mysqli_stmt_bind_param(
    $user_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($user_stmt);

$user_result = mysqli_stmt_get_result($user_stmt);

$user = mysqli_fetch_assoc($user_result);

mysqli_stmt_close($user_stmt);


// =====================================================
// اطلاعات کاربر برای user_kenar.php
// =====================================================

$first_name = $user['first_name'] ?? '';
$last_name = $user['last_name'] ?? '';
$username = $user['username'] ?? '';
$profile_image = $user['profile_image'] ?? '';


// حرف اول اسم برای آواتار
if (!empty($first_name)) {

    $avatar_letter = mb_substr(
        $first_name,
        0,
        1,
        'UTF-8'
    );

} else {

    $avatar_letter = '؟';

}


// =====================================================
// افزودن محصول به سبد
// =====================================================

if (isset($_POST['add'])) {

    $id = $_POST['id'];

    $price = str_replace(
        ',',
        '',
        $_POST['price']
    );


    // ذخیره آدرس صفحه محصول
    if (isset($_POST['return_url'])) {

        $_SESSION['return_url'] = $_POST['return_url'];

    }


    // اگر محصول قبلاً داخل سبد باشد
    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['count']++;

    } else {

        $_SESSION['cart'][$id] = array(

            "title" => $_POST['title'],

            "price" => $price,

            "count" => 1

        );

    }

}


// =====================================================
// افزایش تعداد +
// =====================================================

if (isset($_GET['plus'])) {

    $id = $_GET['plus'];

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['count']++;

    }

}


// =====================================================
// کاهش تعداد -
// =====================================================

if (isset($_GET['minus'])) {

    $id = $_GET['minus'];

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['count']--;

        if ($_SESSION['cart'][$id]['count'] <= 0) {

            unset($_SESSION['cart'][$id]);

        }

    }

}


// =====================================================
// حذف محصول
// =====================================================

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    unset($_SESSION['cart'][$id]);

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

    <title>سبد خرید | DIGI24</title>


    <!-- Bootstrap -->

    <link
            rel="stylesheet"
            href="css/bootstrap-5.2.0-dist/css/bootstrap.rtl.min.css"
    >


    <!-- CSS اصلی -->

    <link
            rel="stylesheet"
            href="css/style.css"
    >


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

            /*min-height: 200vh;*/

        }


        /* =====================================================
           PANEL
        ===================================================== */

        .d24-cart-panel {

            min-height: 100vh;

            display: flex;

            align-items: stretch;

        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .d24-cart-content {

            flex: 1;

            width: 100%;

            padding: 42px 48px;

            max-width: 1450px;

            margin: auto;

        }


        /* =====================================================
           HEADER
        ===================================================== */

        .d24-cart-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 25px;

        }


        .d24-cart-header-title {

            color: #263e55;

            font-size: 28px;

            font-weight: 950;

            line-height: 1.5;

        }


        .d24-cart-header-title span {

            color: #3978df;

        }


        .d24-cart-header-description {

            margin-top: 7px;

            color: #8998a6;

            font-size: 11px;

            line-height: 2;

        }


        .d24-cart-header-badge {

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
           CART BOX
        ===================================================== */

        .d24-cart-box {

            background: #ffffff;

            border: 1px solid #e2eaf1;

            border-radius: 24px;

            padding: 32px;

            box-shadow:
                    0 10px 30px rgba(30, 75, 110, .04);

            margin-bottom: 22px;

        }


        /* =====================================================
           CART TOP
        ===================================================== */

        .d24-cart-top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            padding-bottom: 25px;

            margin-bottom: 25px;

            border-bottom: 1px solid #edf2f7;

        }


        .d24-cart-section-title {

            color: #294258;

            font-size: 15px;

            font-weight: 950;

            margin: 0;

        }


        .d24-cart-section-description {

            color: #91a0ad;

            font-size: 10px;

            margin-top: 7px;

        }


        .d24-cart-count {

            padding: 9px 14px;

            border-radius: 12px;

            background: #edf5ff;

            color: #3978df;

            border: 1px solid #e0ecfb;

            font-size: 10px;

            font-weight: 900;

            white-space: nowrap;

        }


        /* =====================================================
           TABLE WRAPPER
        ===================================================== */

        .d24-cart-table-wrapper {

            width: 100%;

            overflow-x: auto;

        }


        /* =====================================================
           TABLE
        ===================================================== */

        .d24-cart-table {

            width: 100%;

            border-collapse: separate;

            border-spacing: 0;

            min-width: 720px;

        }


        .d24-cart-table th {

            background: #f8fafc;

            color: #738798;

            font-size: 10px;

            font-weight: 900;

            padding: 14px 13px;

            text-align: center;

            border-top: 1px solid #edf2f7;

            border-bottom: 1px solid #edf2f7;

        }


        .d24-cart-table th:first-child {

            border-top-right-radius: 12px;

        }


        .d24-cart-table th:last-child {

            border-top-left-radius: 12px;

        }


        .d24-cart-table td {

            padding: 18px 13px;

            text-align: center;

            color: #526b7d;

            font-size: 11px;

            border-bottom: 1px solid #edf2f7;

            background: #ffffff;

        }


        .d24-cart-table tbody tr {

            transition: .2s ease;

        }


        .d24-cart-table tbody tr:hover td {

            background: #fbfdff;

        }


        .d24-cart-table tbody tr:last-child td {

            border-bottom: none;

        }


        /* =====================================================
           PRODUCT TITLE
        ===================================================== */

        .d24-cart-product-title {

            color: #294258 !important;

            font-weight: 900;

            text-align: right !important;

            line-height: 1.9;

        }


        /* =====================================================
           PRICE
        ===================================================== */

        .d24-cart-price {

            color: #526b7d;

            font-weight: 700;

            white-space: nowrap;

        }


        /* =====================================================
           QUANTITY
        ===================================================== */

        .d24-cart-quantity {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 9px;

        }


        .d24-cart-quantity-button {

            width: 30px;

            height: 30px;

            display: flex;

            align-items: center;

            justify-content: center;

            text-decoration: none;

            border-radius: 9px;

            font-size: 16px;

            font-weight: 900;

            transition: .2s ease;

        }


        .d24-cart-minus {

            background: #f3f6f9;

            color: #71808d;

            border: 1px solid #e6edf2;

        }


        .d24-cart-minus:hover {

            background: #e9eef2;

            color: #52616e;

        }


        .d24-cart-plus {

            background: #edf5ff;

            color: #3978df;

            border: 1px solid #dcecff;

        }


        .d24-cart-plus:hover {

            background: #dfeeff;

            color: #3978df;

        }


        .d24-cart-count-number {

            min-width: 25px;

            color: #294258;

            font-size: 12px;

            font-weight: 900;

        }


        /* =====================================================
           SUM
        ===================================================== */

        .d24-cart-row-total {

            color: #3978df;

            font-weight: 900;

            white-space: nowrap;

        }


        /* =====================================================
           DELETE
        ===================================================== */

        .d24-cart-delete {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 7px 11px;

            text-decoration: none;

            color: #df6262;

            background: #fff3f3;

            border: 1px solid #f9dddd;

            border-radius: 9px;

            font-size: 10px;

            font-weight: 900;

            transition: .2s ease;

        }


        .d24-cart-delete:hover {

            color: #ffffff;

            background: #df6262;

            border-color: #df6262;

        }


        /* =====================================================
           TOTAL
        ===================================================== */

        .d24-cart-total {

            margin-top: 25px;

            padding: 20px 0 0;

            border-top: 1px solid #edf2f7;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

        }


        .d24-cart-total-label {

            color: #738798;

            font-size: 11px;

            font-weight: 800;

        }


        .d24-cart-total-price {

            color: #3978df;

            font-size: 19px;

            font-weight: 950;

        }


        /* =====================================================
           EMPTY CART
        ===================================================== */

        .d24-cart-empty {

            text-align: center;

            padding: 55px 20px 45px;

        }


        .d24-cart-empty-icon {

            width: 70px;

            height: 70px;

            margin: 0 auto 18px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 22px;

            background:
                    linear-gradient(
                            135deg,
                            #edf5ff,
                            #e7f8fa
                    );

            border: 1px solid #dfebf5;

            color: #3978df;

            font-size: 27px;

            box-shadow:
                    0 10px 25px rgba(45, 110, 180, .08);

        }


        .d24-cart-empty h3 {

            font-size: 17px;

            font-weight: 950;

            color: #294258;

            margin-bottom: 8px;

        }


        .d24-cart-empty p {

            font-size: 11px;

            color: #91a0ad;

            margin-bottom: 0;

        }


        /* =====================================================
           CONTINUE BUTTON
        ===================================================== */

        .d24-cart-bottom {

            display: flex;

            justify-content: flex-start;

        }


        .d24-cart-continue {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            text-decoration: none;

            padding: 12px 21px;

            border-radius: 13px;

            background:
                    linear-gradient(
                            135deg,
                            #3978df,
                            #00a8a3
                    );

            color: #ffffff;

            font-size: 11px;

            font-weight: 900;

            box-shadow:
                    0 10px 25px rgba(57, 120, 223, .12);

            transition: .25s ease;

        }


        .d24-cart-continue:hover {

            color: #ffffff;

            transform: translateY(-2px);

            box-shadow:
                    0 14px 30px rgba(57, 120, 223, .18);

        }


        /* =====================================================
           SIDEBAR HEIGHT FIX
        ===================================================== */

        .d24-cart-panel > * {

            align-self: stretch;

        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1050px) {

            .d24-cart-content {

                padding: 35px 28px;

            }

        }


        @media (max-width: 800px) {

            .d24-cart-panel {

                display: block;

            }


            .d24-cart-content {

                padding: 27px 18px;

            }


            .d24-cart-header-title {

                font-size: 23px;

            }


            .d24-cart-top {

                align-items: flex-start;

                flex-direction: column;

            }


            .d24-cart-box {

                padding: 22px;

            }

        }


        @media (max-width: 500px) {

            .d24-cart-content {

                padding: 21px 14px;

            }


            .d24-cart-header {

                margin-bottom: 20px;

            }


            .d24-cart-header-title {

                font-size: 21px;

            }


            .d24-cart-header-description {

                font-size: 10px;

            }


            .d24-cart-header-badge {

                display: none;

            }


            .d24-cart-box {

                padding: 17px;

                border-radius: 20px;

            }


            .d24-cart-total-price {

                font-size: 16px;

            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     HEADER
===================================================== -->

<?php include "header.php"; ?>


<!-- =====================================================
     USER PANEL
===================================================== -->

<div class="d24-cart-panel">


    <!-- =================================================
         SIDEBAR
    ================================================= -->

    <?php include "user_kenar.php"; ?>


    <!-- =================================================
         CONTENT
    ================================================= -->

    <main class="d24-cart-content">


        <!-- =================================================
             HEADER
        ================================================= -->

        <header class="d24-cart-header">

            <div>

                <h1 class="d24-cart-header-title overflow-visible">

                    سبد
                    <span>خرید</span>

                </h1>


                <p class="d24-cart-header-description overflow-visible">

                    محصولات انتخاب شده خود را مشاهده و مدیریت کنید.

                </p>

            </div>


            <div class="d24-cart-header-badge overflow-visible">

                ✦ سبد خرید DIGI24

            </div>

        </header>


        <!-- =================================================
             CART BOX
        ================================================= -->

        <section class="d24-cart-box">


            <!-- CART TOP -->

            <div class="d24-cart-top">

                <div>

                    <h2 class="d24-cart-section-title overflow-visible">

                        محصولات سبد خرید

                    </h2>


                    <p class="d24-cart-section-description overflow-visible">

                        تعداد و محصولات انتخاب‌شده خود را مدیریت کنید.

                    </p>

                </div>


                <?php

                $cart_count = 0;

                if (
                    isset($_SESSION['cart'])
                    &&
                    !empty($_SESSION['cart'])
                ) {

                    foreach ($_SESSION['cart'] as $cart_item) {

                        $cart_count += (int)
                        $cart_item['count'];

                    }

                }

                ?>


                <div class="d24-cart-count">

                    <?php echo $cart_count; ?>

                    محصول

                </div>

            </div>


            <?php

            $total = 0;


            if (
                isset($_SESSION['cart'])
                &&
                !empty($_SESSION['cart'])
            ):

                ?>


                <!-- =================================================
                     TABLE
                ================================================= -->

                <div class="d24-cart-table-wrapper">


                    <table class="d24-cart-table">


                        <thead>

                        <tr>

                            <th>
                                نام محصول
                            </th>

                            <th>
                                قیمت واحد
                            </th>

                            <th>
                                تعداد
                            </th>

                            <th>
                                جمع
                            </th>

                            <th>
                                عملیات
                            </th>

                        </tr>

                        </thead>


                        <tbody>


                        <?php foreach (
                            $_SESSION['cart']
                            as $id => $item
                        ): ?>


                            <?php

                            $price = str_replace(
                                ',',
                                '',
                                $item['price']
                            );


                            $sum =
                                $price *
                                $item['count'];


                            $total += $sum;

                            ?>


                            <tr>


                                <td class="d24-cart-product-title">

                                    <?php

                                    echo htmlspecialchars(
                                        $item['title']
                                    );

                                    ?>

                                </td>


                                <td class="d24-cart-price">

                                    <?php

                                    echo number_format(
                                        $price
                                    );

                                    ?>

                                    تومان

                                </td>


                                <td>

                                    <div
                                            class="d24-cart-quantity"
                                    >

                                        <a
                                                class="d24-cart-quantity-button d24-cart-minus"
                                                href="?minus=<?php echo urlencode($id); ?>"
                                        >

                                            −

                                        </a>


                                        <span
                                                class="d24-cart-count-number"
                                        >

                                            <?php

                                            echo (int)
                                            $item['count'];

                                            ?>

                                        </span>


                                        <a
                                                class="d24-cart-quantity-button d24-cart-plus"
                                                href="?plus=<?php echo urlencode($id); ?>"
                                        >

                                            +

                                        </a>

                                    </div>

                                </td>


                                <td class="d24-cart-row-total">

                                    <?php

                                    echo number_format(
                                        $sum
                                    );

                                    ?>

                                    تومان

                                </td>


                                <td>

                                    <a
                                            class="d24-cart-delete"
                                            href="?delete=<?php echo urlencode($id); ?>"
                                            onclick="return confirm('آیا از حذف این محصول مطمئن هستید؟');"
                                    >

                                        حذف

                                    </a>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     TOTAL
                ================================================= -->

                <div class="d24-cart-total">

                    <span class="d24-cart-total-label">

                        جمع کل خرید:

                    </span>


                    <strong class="d24-cart-total-price">

                        <?php

                        echo number_format(
                            $total
                        );

                        ?>

                        تومان

                    </strong>

                </div>


            <?php else: ?>


                <!-- =================================================
                     EMPTY CART
                ================================================= -->

                <div class="d24-cart-empty">

                    <div class="d24-cart-empty-icon">

                        🛒

                    </div>


                    <h3>

                        سبد خرید شما خالی است

                    </h3>


                    <p>

                        هنوز محصولی به سبد خرید اضافه نکرده‌اید.

                    </p>

                </div>


            <?php endif; ?>


        </section>


        <!-- =================================================
             CONTINUE SHOPPING
        ================================================= -->

        <div class="d24-cart-bottom">

            <a
                    href="<?php echo htmlspecialchars(
                        $_SESSION['return_url'] ?? 'index.php'
                    ); ?>"
                    class="d24-cart-continue"
            >

                ← ادامه خرید

            </a>

        </div>


    </main>

</div>


<script
        src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
