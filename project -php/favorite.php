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

$user_id = (int) $_SESSION['id'];

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

mysqli_stmt_close($stmt);

if (!$user) {
    die("کاربر پیدا نشد.");
}

$first_name    = $user['first_name'] ?? '';
$last_name     = $user['last_name'] ?? '';
$username      = $user['username'] ?? '';
$profile_image = $user['profile_image'] ?? '';

$avatar_letter = mb_substr($first_name, 0, 1, 'UTF-8');


/* =====================================================
   جدول های مجاز
===================================================== */

$allowed_tables = [
    'projects'      => 'products.php',
    'projects_film' => 'products_film.php',
    'projects_jan'  => 'products_jan.php',
    'projects_lenz' => 'products_lenz.php',
    'projects_noor' => 'products_noor.php',
    'projects_var'  => 'products_var.php'
];


/* =====================================================
   حذف محصول از علاقه مندی
===================================================== */

if (isset($_POST['remove_favorite'])) {

    $favorite_key = trim(
        (string) ($_POST['favorite_key'] ?? '')
    );

    if (
        preg_match(
            '/^(projects|projects_film|projects_jan|projects_lenz|projects_noor|projects_var)_(\d+)$/',
            $favorite_key,
            $matches
        )
    ) {

        if (
            isset($_SESSION['favorites']) &&
            is_array($_SESSION['favorites'])
        ) {
            $_SESSION['favorites'] = array_values(
                array_diff(
                    $_SESSION['favorites'],
                    [$favorite_key]
                )
            );
        }
    }

    header("Location: favorite.php");
    exit;
}


/* =====================================================
   گرفتن علاقه مندی ها از Session

   فرمت جدید:
   projects_5
   projects_film_3
   projects_jan_8
   projects_lenz_2
   projects_noor_10
   projects_var_4
===================================================== */

$favorites = [];

if (
    isset($_SESSION['favorites']) &&
    is_array($_SESSION['favorites'])
) {

    foreach ($_SESSION['favorites'] as $favorite_key) {

        $favorite_key = trim(
            (string) $favorite_key
        );

        if (
            preg_match(
                '/^(projects|projects_film|projects_jan|projects_lenz|projects_noor|projects_var)_(\d+)$/',
                $favorite_key
            )
        ) {
            $favorites[] = $favorite_key;
        }
    }
}


/* =====================================================
   پشتیبانی از Session های قدیمی دسته بندی ها

   اگر بعضی از صفحات هنوز از این Session ها استفاده کنند:
   favorites_film
   favorites_jan
   favorites_lenz
   favorites_noor
   favorites_var

   ID عددی آنها به کلید جدید تبدیل می شود.
===================================================== */

$old_sessions = [
    'favorites_film' => 'projects_film',
    'favorites_jan'  => 'projects_jan',
    'favorites_lenz' => 'projects_lenz',
    'favorites_noor' => 'projects_noor',
    'favorites_var'  => 'projects_var'
];

foreach ($old_sessions as $session_name => $table_name) {

    if (
        !isset($_SESSION[$session_name]) ||
        !is_array($_SESSION[$session_name])
    ) {
        continue;
    }

    foreach ($_SESSION[$session_name] as $old_id) {

        if (is_numeric($old_id)) {

            $old_id = (int) $old_id;

            if ($old_id > 0) {
                $favorites[] = $table_name . '_' . $old_id;
            }
        }
    }
}


/* =====================================================
   پشتیبانی از favorites قدیمی برای جدول projects

   اگر داخل favorites عددی باشد، آن را مربوط به projects
   در نظر می گیریم تا علاقه مندی های قبلی از بین نروند.
===================================================== */

if (
    isset($_SESSION['favorites']) &&
    is_array($_SESSION['favorites'])
) {

    foreach ($_SESSION['favorites'] as $old_value) {

        if (is_numeric($old_value)) {

            $old_id = (int) $old_value;

            if ($old_id > 0) {
                $favorites[] = 'projects_' . $old_id;
            }
        }
    }
}


/* =====================================================
   حذف موارد تکراری
===================================================== */

$favorites = array_values(
    array_unique($favorites)
);


/* =====================================================
   گرفتن محصولات علاقه مندی از 6 جدول
===================================================== */

$favorite_products = [];

foreach ($favorites as $favorite_key) {

    $table_name = '';
    $product_id = 0;


    /* =================================================
       تشخیص جدول و ID
    ================================================= */

    if (preg_match(
        '/^(projects|projects_film|projects_jan|projects_lenz|projects_noor|projects_var)_(\d+)$/',
        $favorite_key,
        $matches
    )) {

        $table_name = $matches[1];
        $product_id = (int) $matches[2];
    }


    /* =================================================
       اعتبارسنجی
    ================================================= */

    if (
        empty($table_name) ||
        $product_id <= 0 ||
        !isset($allowed_tables[$table_name])
    ) {
        continue;
    }


    /* =================================================
       گرفتن محصول
    ================================================= */

    $sql_favorite = "
        SELECT
            id,
            project_id,
            title,
            price,
            price_fake,
            image1
        FROM `$table_name`
        WHERE id = $product_id
          AND project_id = id
        LIMIT 1
    ";

    $result_favorite = mysqli_query(
        $conn,
        $sql_favorite
    );


    if ($result_favorite) {

        $row = mysqli_fetch_assoc(
            $result_favorite
        );

        if ($row) {

            $row['favorite_key'] = $favorite_key;
            $row['table_name'] = $table_name;
            $row['product_page'] = $allowed_tables[$table_name];

            $favorite_products[] = $row;
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

<script
        src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"
></script>


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
       FAVORITES BOX
    ===================================================== */

    .d24-favorites-box {

        background: #ffffff;

        border: 1px solid #e2eaf1;

        border-radius: 24px;

        padding: 28px;

        box-shadow:
                0 10px 30px rgba(30, 75, 110, .04);

        margin-bottom: 22px;
    }


    /* =====================================================
       TOP BAR
    ===================================================== */

    .d24-favorites-top {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding-bottom: 23px;

        margin-bottom: 25px;

        border-bottom: 1px solid #edf2f7;
    }


    .d24-favorites-section-title {

        color: #294258;

        font-size: 16px;

        font-weight: 950;
    }


    .d24-favorites-count {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 38px;

        height: 30px;

        padding: 0 10px;

        border-radius: 10px;

        background: #edf5ff;

        color: #3978df;

        font-size: 11px;

        font-weight: 900;
    }


    /* =====================================================
       PRODUCTS GRID
    ===================================================== */

    .d24-favorites-grid {

        display: grid;

        grid-template-columns:
        repeat(3, minmax(0, 1fr));

        gap: 18px;
    }


    /* =====================================================
       PRODUCT CARD
    ===================================================== */

    .d24-favorite-card {

        position: relative;

        overflow: hidden;

        background: #ffffff;

        border: 1px solid #e3eaf0;

        border-radius: 19px;

        transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;
    }


    .d24-favorite-card:hover {

        transform: translateY(-4px);

        border-color: #cddff3;

        box-shadow:
                0 15px 32px rgba(30, 75, 110, .08);
    }


    /* =====================================================
       PRODUCT IMAGE
    ===================================================== */

    .d24-favorite-image {

        width: 100%;

        height: 220px;

        padding: 15px;

        background:
                linear-gradient(
                        135deg,
                        #f8fbff,
                        #f4f8fc
                );

        border-bottom: 1px solid #edf2f7;
    }


    .d24-favorite-image img {

        width: 100%;

        height: 100%;

        display: block;

        object-fit: contain;
    }


    /* =====================================================
       PRODUCT CONTENT
    ===================================================== */

    .d24-favorite-content {

        padding: 18px;
    }


    .d24-favorite-title {

        color: #294258;

        font-size: 13px;

        font-weight: 900;

        line-height: 1.9;

        min-height: 50px;

        margin-bottom: 12px;

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;

        overflow: hidden;
    }


    /* =====================================================
       PRICE
    ===================================================== */

    .d24-favorite-price {

        color: #3978df;

        font-size: 15px;

        font-weight: 950;

        margin-bottom: 5px;
    }


    .d24-favorite-price span {

        color: #8998a6;

        font-size: 10px;

        font-weight: 700;
    }


    /* =====================================================
       OLD PRICE
    ===================================================== */

    .d24-favorite-old-price {

        color: #a5afb7;

        font-size: 10px;

        text-decoration: line-through;

        margin-bottom: 15px;
    }


    /* =====================================================
       ACTIONS
    ===================================================== */

    .d24-favorite-actions {

        display: flex;

        align-items: stretch;

        gap: 8px;

        margin-top: 14px;
    }


    /* =====================================================
       VIEW PRODUCT
    ===================================================== */

    .d24-favorite-view {

        flex: 1;

        min-height: 42px;

        display: flex;

        align-items: center;

        justify-content: center;

        padding: 0 10px;

        border-radius: 11px;

        background:
                linear-gradient(
                        135deg,
                        #3978df,
                        #00a8a3
                );

        color: #ffffff;

        text-decoration: none;

        font-size: 10px;

        font-weight: 900;

        transition: .25s ease;

        box-shadow:
                0 7px 17px rgba(57, 120, 223, .10);
    }


    .d24-favorite-view:hover {

        color: #ffffff;

        transform: translateY(-2px);

        box-shadow:
                0 10px 23px rgba(57, 120, 223, .17);
    }


    /* =====================================================
       REMOVE BUTTON
    ===================================================== */

    .d24-favorite-remove-form {

        margin: 0;
    }


    .d24-favorite-remove {

        width: 42px;

        height: 42px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 11px;

        border: 1px solid #f0dede;

        background: #fff8f8;

        color: #df6262;

        font-size: 17px;

        cursor: pointer;

        transition: .25s ease;
    }


    .d24-favorite-remove:hover {

        background: #fff0f0;

        border-color: #e8bebe;

        color: #d84949;

        transform: translateY(-2px);
    }


    /* =====================================================
       EMPTY
    ===================================================== */

    .d24-favorites-empty {

        min-height: 320px;

        display: flex;

        align-items: center;

        justify-content: center;

        flex-direction: column;

        text-align: center;

        padding: 35px;
    }


    .d24-favorites-empty-icon {

        width: 76px;

        height: 76px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 18px;

        border-radius: 22px;

        background:
                linear-gradient(
                        135deg,
                        #edf5ff,
                        #e7f8fa
                );

        color: #3978df;

        font-size: 34px;

        box-shadow:
                0 10px 25px rgba(45, 110, 180, .08);
    }


    .d24-favorites-empty-title {

        color: #294258;

        font-size: 17px;

        font-weight: 950;

        margin-bottom: 8px;
    }


    .d24-favorites-empty-text {

        color: #8998a6;

        font-size: 11px;

        line-height: 2;

        max-width: 420px;

        margin-bottom: 20px;
    }


    /* =====================================================
       GO SHOP
    ===================================================== */

    .d24-favorites-shop {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-height: 44px;

        padding: 0 22px;

        border-radius: 12px;

        background:
                linear-gradient(
                        135deg,
                        #3978df,
                        #00a8a3
                );

        color: #ffffff;

        text-decoration: none;

        font-size: 11px;

        font-weight: 900;

        box-shadow:
                0 9px 22px rgba(57, 120, 223, .12);

        transition: .25s ease;
    }


    .d24-favorites-shop:hover {

        color: #ffffff;

        transform: translateY(-2px);

        box-shadow:
                0 13px 28px rgba(57, 120, 223, .18);
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 1150px) {

        .d24-favorites-grid {

            grid-template-columns:
            repeat(2, minmax(0, 1fr));
        }
    }


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


        .d24-favorites-box {

            padding: 20px;
        }


        .d24-favorites-grid {

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

            font-size: 21px;
        }


        .d24-header-description {

            font-size: 10px;
        }


        .d24-header-badge {

            display: none;
        }


        .d24-favorites-box {

            padding: 15px;
        }


        .d24-favorites-top {

            align-items: flex-start;

            flex-direction: column;
        }


        .d24-favorite-image {

            height: 200px;
        }
    }

</style>


<!-- =====================================================
     PANEL
===================================================== -->

<div class="d24-panel">


    <?php include "user_kenar.php"; ?>


    <!-- =================================================
         CONTENT
    ================================================= -->

    <main class="d24-content">


        <!-- =================================================
             HEADER
        ================================================= -->

        <header class="d24-header">

            <div>

                <h1 class="d24-header-title overflow-visible">

                    علاقه‌مندی‌های

                    <span>
                        من
                    </span>

                </h1>


                <p class="d24-header-description">

                    محصولاتی که برای مشاهده و خرید در آینده ذخیره کرده‌اید.

                </p>

            </div>


            <div class="d24-header-badge">

                ♥ علاقه‌مندی‌های DIGI24

            </div>

        </header>


        <!-- =================================================
             FAVORITES BOX
        ================================================= -->

        <section class="d24-favorites-box">


            <?php if (!empty($favorite_products)): ?>


                <!-- TOP -->

                <div class="d24-favorites-top">

                    <div class="d24-favorites-section-title">

                        محصولات مورد علاقه

                    </div>


                    <div class="d24-favorites-count">

                        <?php echo count($favorite_products); ?>

                    </div>

                </div>


                <!-- =================================================
                     PRODUCTS
                ================================================= -->

                <div class="d24-favorites-grid">


                    <?php foreach (
                        $favorite_products
                        as $favorite
                    ): ?>


                        <article class="d24-favorite-card">


                            <!-- IMAGE -->

                            <div class="d24-favorite-image">

                                <?php if (
                                    !empty($favorite['image1'])
                                ): ?>

                                    <img
                                            src="up/<?php
                                            echo htmlspecialchars(
                                                $favorite['image1']
                                            );
                                            ?>"
                                            alt="<?php
                                            echo htmlspecialchars(
                                                $favorite['title']
                                            );
                                            ?>"
                                    >

                                <?php else: ?>

                                    <div
                                            style="
                                            width:100%;
                                            height:100%;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            color:#91a0ad;
                                            font-size:11px;
                                        "
                                    >

                                        بدون تصویر

                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- CONTENT -->

                            <div class="d24-favorite-content">


                                <!-- TITLE -->

                                <div class="d24-favorite-title">

                                    <?php
                                    echo htmlspecialchars(
                                        $favorite['title']
                                    );
                                    ?>

                                </div>


                                <!-- PRICE -->

                                <div class="d24-favorite-price">

                                    <?php
                                    echo number_format(
                                        $favorite['price']
                                    );
                                    ?>

                                    <span>
                                        تومان
                                    </span>

                                </div>


                                <!-- OLD PRICE -->

                                <?php
                                if (
                                    !empty(
                                    $favorite['price_fake']
                                    ) &&
                                    $favorite['price_fake']
                                    > $favorite['price']
                                ):
                                    ?>

                                    <div
                                            class="d24-favorite-old-price"
                                    >

                                        <?php
                                        echo number_format(
                                            $favorite['price_fake']
                                        );
                                        ?>

                                        تومان

                                    </div>

                                <?php else: ?>

                                    <div
                                            style="
                                            height:15px;
                                        "
                                    ></div>

                                <?php endif; ?>


                                <!-- ACTIONS -->

                                <div
                                        class="d24-favorite-actions"
                                >


                                    <!-- VIEW -->

                                    <?php

                                    $product_page = 'products.php';

                                    switch (
                                    $favorite['table_name']
                                    ) {

                                        case 'projects':
                                            $product_page = 'products.php';
                                            break;

                                        case 'projects_film':
                                            $product_page = 'products_film.php';
                                            break;

                                        case 'projects_jan':
                                            $product_page = 'products_jan.php';
                                            break;

                                        case 'projects_lenz':
                                            $product_page = 'products_lenz.php';
                                            break;

                                        case 'projects_noor':
                                            $product_page = 'products_noor.php';
                                            break;

                                        case 'projects_var':
                                            $product_page = 'products_var.php';
                                            break;
                                    }

                                    ?>

                                    <a
                                            href="<?php
                                            echo $product_page;
                                            ?>?id=<?php
                                            echo (int) $favorite['id'];
                                            ?>"
                                            class="d24-favorite-view"
                                    >

                                        مشاهده محصول

                                    </a>


                                    <!-- REMOVE -->

                                    <form
                                            method="post"
                                            class="d24-favorite-remove-form"
                                    >

                                        <input
                                                type="hidden"
                                                name="favorite_key"
                                                value="<?php
                                                echo htmlspecialchars(
                                                    $favorite['favorite_key']
                                                );
                                                ?>"
                                        >


                                        <button
                                                type="submit"
                                                name="remove_favorite"
                                                class="d24-favorite-remove overflow-visible"
                                                title="حذف از علاقه مندی ها"
                                                onclick="
                                                return confirm(
                                                    'آیا می‌خواهید این محصول از علاقه‌مندی‌ها حذف شود؟'
                                                );
                                            "
                                        >

                                            ♥

                                        </button>

                                    </form>


                                </div>


                            </div>


                        </article>


                    <?php endforeach; ?>


                </div>


            <?php else: ?>


                <!-- =================================================
                     EMPTY
                ================================================= -->

                <div class="d24-favorites-empty">


                    <div class="d24-favorites-empty-icon">

                        ♡

                    </div>


                    <div class="d24-favorites-empty-title">

                        هنوز محصولی به علاقه‌مندی‌ها اضافه نکرده‌اید

                    </div>


                    <div class="d24-favorites-empty-text">

                        محصولاتی که دوست دارید را به لیست علاقه‌مندی‌های
                        خود اضافه کنید تا بعداً به راحتی به آن‌ها دسترسی داشته باشید.

                    </div>


                    <a
                            href="index.php"
                            class="d24-favorites-shop"
                    >

                        مشاهده محصولات

                    </a>


                </div>


            <?php endif; ?>


        </section>


    </main>


</div>


<script
        src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
