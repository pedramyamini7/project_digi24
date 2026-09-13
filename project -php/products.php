<?php

session_start();

require "config/config.php";


/* =====================================================
   علاقه مندی ها - افزودن / حذف
===================================================== */

if (isset($_POST['add_favorite'])) {

    // کاربر باید وارد شده باشد
    if (!isset($_SESSION['id'])) {

        header("Location: login-register/login.php");
        exit;
    }

    $favorite_id = (int) ($_POST['favorite_id'] ?? 0);

    if ($favorite_id > 0) {

        // اگر Session علاقه مندی وجود نداشت
        if (
            !isset($_SESSION['favorites']) ||
            !is_array($_SESSION['favorites'])
        ) {

            $_SESSION['favorites'] = [];
        }


        /*
         * اگر محصول قبلاً در علاقه مندی ها باشد
         * آن را حذف می کنیم
         */
        if (
            in_array(
                $favorite_id,
                $_SESSION['favorites']
            )
        ) {

            $_SESSION['favorites'] = array_values(
                array_diff(
                    $_SESSION['favorites'],
                    [$favorite_id]
                )
            );

        }

        /*
         * اگر محصول وجود نداشته باشد
         * آن را اضافه می کنیم
         */
        else {

            $_SESSION['favorites'][] = $favorite_id;
        }
    }


    // جلوگیری از ارسال دوباره فرم هنگام Refresh
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}


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

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user_id
    );

    mysqli_stmt_execute($stmt);

    // نتیجه کاربر را داخل متغیر جدا می ریزیم
    $user_result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($user_result);

    // بستن statement
    mysqli_stmt_close($stmt);


    if ($user) {

        $first_name = $user['first_name'] ?? '';
        $last_name = $user['last_name'] ?? '';
        $username = $user['username'] ?? '';
        $profile_image = $user['profile_image'] ?? '';
    }
}


/* =====================================================
   گرفتن ID محصول
===================================================== */

if (
    !isset($_GET['id']) ||
    empty($_GET['id'])
) {

    die("محصول پیدا نشد");
}

$product_id = (int) $_GET['id'];


/* =====================================================
   اطلاعات اصلی محصول
   فقط ردیف اصلی محصول
   id = project_id
===================================================== */

$sql_product = "
    SELECT *
    FROM projects
    WHERE id = $product_id
      AND project_id = $product_id
    LIMIT 1
";

$result_product = mysqli_query(
    $conn,
    $sql_product
);

$product = mysqli_fetch_assoc($result_product);

if (!$product) {

    die("محصول پیدا نشد");
}


/* =====================================================
   گرفتن تمام ردیف های مربوط به محصول
===================================================== */

$sql_project_rows = "
    SELECT *
    FROM projects
    WHERE project_id = $product_id
    ORDER BY id ASC
";

$result_project_rows = mysqli_query(
    $conn,
    $sql_project_rows
);


/* =====================================================
   آرایه ها
===================================================== */

$specs = [];
$questions = [];
$warranties = [];
$shippings = [];


/* =====================================================
   جدا کردن اطلاعات
===================================================== */

while (
$row = mysqli_fetch_assoc($result_project_rows)
) {


    /* -----------------------------
       مشخصات
    ----------------------------- */

    if (
        !empty($row['spec_title']) &&
        !empty($row['spec_value'])
    ) {

        $specs[] = [
            'title' => $row['spec_title'],
            'value' => $row['spec_value']
        ];
    }


    /* -----------------------------
       پرسش و پاسخ
    ----------------------------- */

    if (!empty($row['question_title'])) {

        $questions[] = [
            'question' => $row['question_title'],
            'answer'   => $row['question_answer']
        ];
    }


    /* -----------------------------
       گارانتی
    ----------------------------- */

    if (!empty($row['warranty'])) {

        $warranties[] = $row['warranty'];
    }


    /* -----------------------------
       زمان ارسال
    ----------------------------- */

    if (!empty($row['shipping_time'])) {

        $shippings[] = $row['shipping_time'];
    }
}


/* =====================================================
   جلوگیری از تکراری بودن گارانتی و ارسال
===================================================== */

$warranties = array_unique($warranties);

$shippings = array_unique($shippings);


/* =====================================================
   بررسی علاقه مندی محصول
===================================================== */

$is_favorite = false;

if (
    isset($_SESSION['favorites']) &&
    in_array(
        $product_id,
        $_SESSION['favorites']
    )
) {

    $is_favorite = true;
}

?>


<?php
include "header.php";
?>


<style>

    /* =====================================================
       خرید + علاقه مندی
    ===================================================== */

    .d24p-buy-favorite-row {

        width: 100%;

        display: flex;

        align-items: stretch;

        gap: 12px;

        margin-top: 18px;
    }


    /* =====================================================
       فرم سبد خرید
    ===================================================== */

    .d24p-cart-form {

        flex: 1;

        margin: 0;
    }


    /* =====================================================
       فرم علاقه مندی
    ===================================================== */

    .d24p-favorite-form {

        flex: 1;

        margin: 0;
    }


    /* =====================================================
       دکمه علاقه مندی
    ===================================================== */

    .d24p-add-favorite {

        width: 100%;

        height: 58px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 9px;

        border: 1px solid #dce5ee;

        border-radius: 14px;

        background: #ffffff;

        color: #3978df;

        font-size: 13px;

        font-weight: 800;

        cursor: pointer;

        transition:
                background 0.25s ease,
                border-color 0.25s ease,
                color 0.25s ease,
                transform 0.25s ease,
                box-shadow 0.25s ease;
    }


    /* =====================================================
       آیکون قلب
    ===================================================== */

    .d24p-favorite-icon {

        font-size: 23px;

        line-height: 1;
    }


    /* =====================================================
       حالت Hover
    ===================================================== */

    .d24p-add-favorite:hover {

        background: #f3f8ff;

        border-color: #3978df;

        box-shadow:
                0 8px 20px rgba(57, 120, 223, 0.12);

        transform: translateY(-1px);
    }


    /* =====================================================
       وقتی محصول اضافه شده
    ===================================================== */

    .d24p-add-favorite.d24p-favorite-active {

        background: linear-gradient(
                135deg,
                #3978df,
                #00a8a3
        );

        border-color: transparent;

        color: #ffffff;

        cursor: pointer;

        box-shadow:
                0 8px 20px rgba(57, 120, 223, 0.18);
    }


    /* =====================================================
       Hover حالت فعال
    ===================================================== */

    .d24p-add-favorite.d24p-favorite-active:hover {

        background: linear-gradient(
                135deg,
                #326dcc,
                #00958f
        );

        box-shadow:
                0 10px 24px rgba(57, 120, 223, 0.22);
    }


    /* =====================================================
       دکمه سبد خرید
    ===================================================== */

    .d24p-cart-form .d24p-add-cart {

        width: 100%;

        height: 58px;
    }


    /* =====================================================
       موبایل
    ===================================================== */

    @media (max-width: 700px) {

        .d24p-buy-favorite-row {

            flex-direction: column;

            gap: 10px;
        }

        .d24p-cart-form,
        .d24p-favorite-form {

            width: 100%;
        }
    }

</style>
<style>
    /* =====================================================
   دکمه علاقه مندی
===================================================== */

    .d24p-add-favorite {

        width: 100%;

        height: 58px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 9px;

        border: 1px solid #e05b5b;

        border-radius: 14px;

        background: #ffffff;

        color: #e05b5b;

        font-size: 13px;

        font-weight: 800;

        cursor: pointer;

        transition:
                background 0.25s ease,
                border-color 0.25s ease,
                color 0.25s ease;
    }


    /* =====================================================
       آیکون قلب
    ===================================================== */

    .d24p-favorite-icon {

        font-size: 23px;

        line-height: 1;

        color: #e05b5b;
    }


    /* =====================================================
       Hover حالت عادی
       بدون حرکت
    ===================================================== */

    .d24p-add-favorite:hover {

        background: #fff5f5;

        border-color: #d94d4d;

        color: #d94d4d;

        transform: none;

        box-shadow: none;
    }


    /* =====================================================
       وقتی محصول در علاقه مندی است
    ===================================================== */

    .d24p-add-favorite.d24p-favorite-active {

        background: #e05b5b;

        border-color: #e05b5b;

        color: #ffffff;

        cursor: pointer;

        box-shadow: none;
    }


    /* =====================================================
       Hover حالت فعال
       همچنان قرمز می ماند
    ===================================================== */

    .d24p-add-favorite.d24p-favorite-active:hover {

        background: #d94d4d;

        border-color: #d94d4d;

        color: #ffffff;

        transform: none;

        box-shadow: none;
    }


    /* =====================================================
       آیکون در حالت فعال
    ===================================================== */

    .d24p-add-favorite.d24p-favorite-active
    .d24p-favorite-icon {

        color: #ffffff;
    }
</style>

<!-- =====================================================
     نوار رنگی
===================================================== -->

<div class="d24p-blue-line"></div>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="container-fluid d24p-main">


    <!-- مسیر صفحه -->

    <div class="d24p-breadcrumb">

        خانه

        <span> / </span>

        دوربین عکاسی

        <span> / </span>

        <?php echo htmlspecialchars($product['title']); ?>

    </div>


    <!-- =================================================
         PRODUCT
    ================================================= -->

    <section class="row g-3 d24p-product-section">


        <!-- =================================================
             تصویر محصول
        ================================================= -->

        <div class="col-xl-4 col-lg-3 col-md-12 order-xl-1 order-3">

            <div class="d24p-product-gallery">


                <!-- تصویر اصلی -->

                <div class="d24p-main-image">

                    <img
                            id="d24p-main-product-image"
                            src="up/<?php echo htmlspecialchars($product['image1']); ?>"
                            alt="<?php echo htmlspecialchars($product['title']); ?>"
                    >

                </div>


                <!-- تصاویر کوچک -->

                <div class="d24p-thumbnails">

                    <?php

                    for (
                        $imageNumber = 1;
                        $imageNumber <= 5;
                        $imageNumber++
                    ) {

                        $imageName =
                            $product['image' . $imageNumber];

                        // اگر تصویر خالی بود نمایش نده
                        if (empty($imageName)) {
                            continue;
                        }

                        ?>

                        <button
                                type="button"
                                class="d24p-thumbnail <?php echo ($imageNumber == 1) ? 'active' : ''; ?>"
                                data-image="up/<?php echo htmlspecialchars($imageName); ?>"
                        >

                            <img
                                    src="up/<?php echo htmlspecialchars($imageName); ?>"
                                    alt="<?php echo htmlspecialchars($product['title']); ?>"
                            >

                        </button>

                    <?php } ?>

                </div>

            </div>

        </div>


        <!-- =================================================
             اطلاعات محصول
        ================================================= -->

        <div class="col-xl-6 col-lg-6 col-md-8 order-xl-2 order-2">


            <div class="d24p-product-info">


                <h1 class="d24p-product-title">

                    <?php echo htmlspecialchars($product['title']); ?>

                </h1>


                <div class="d24p-price">

                    قیمت :

                    <strong>

                        <?php
                        echo number_format($product['price']);
                        ?>

                        تومان

                    </strong>

                </div>


                <!-- =================================================
                     گارانتی
                ================================================= -->

                <?php if (!empty($warranties)) { ?>

                    <div class="d24p-option-row">

                        <span class="d24p-option-title">

                            گارانتی :

                        </span>


                        <select
                                name="warranty"
                                class="form-select d24p-select"
                                form="cart-form"
                        >

                            <?php foreach ($warranties as $warranty) { ?>

                                <option
                                        value="<?php echo htmlspecialchars($warranty); ?>"
                                >

                                    <?php

                                    if ($warranty == 'warranty') {

                                        echo 'شرکت الماس دوربین داران';

                                    }

                                    elseif ($warranty == '3_month') {

                                        echo 'گارانتی ۳ ماهه';

                                    }

                                    elseif ($warranty == '6_month') {

                                        echo 'گارانتی ۶ ماهه';

                                    }

                                    elseif ($warranty == '12_month') {

                                        echo 'گارانتی ۱۲ ماهه';

                                    }

                                    elseif ($warranty == '18_month') {

                                        echo 'گارانتی ۱۸ ماهه';

                                    }

                                    elseif ($warranty == '24_month') {

                                        echo 'گارانتی ۲۴ ماهه';

                                    }

                                    elseif ($warranty == '36_month') {

                                        echo 'گارانتی ۳۶ ماهه';

                                    }

                                    elseif ($warranty == '48_month') {

                                        echo 'گارانتی ۴۸ ماهه';

                                    }

                                    elseif ($warranty == 'no_warranty') {

                                        echo 'بدون گارانتی';

                                    }

                                    else {

                                        echo htmlspecialchars($warranty);
                                    }

                                    ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                <?php } ?>


                <!-- =================================================
                     تعداد محصول
                ================================================= -->

                <!--

                <div class="d24p-quantity">

                    <button
                        type="button"
                        class="d24p-qty-btn"
                        id="d24p-plus"
                    >

                        +

                    </button>

                    <span id="d24p-count">

                        1

                    </span>

                    <button
                        type="button"
                        class="d24p-qty-btn"
                        id="d24p-minus"
                    >

                        -

                    </button>

                </div>

                -->


                <!-- =================================================
                     زمان ارسال
                ================================================= -->

                <?php if (!empty($shippings)) { ?>

                    <div class="d24p-option-row">

                        <span class="d24p-option-title">

                            زمان ارسال :

                        </span>


                        <select
                                name="shipping"
                                class="form-select d24p-select"
                                form="cart-form"
                        >

                            <?php foreach ($shippings as $shipping) { ?>

                                <option
                                        value="<?php echo htmlspecialchars($shipping); ?>"
                                >

                                    <?php

                                    if ($shipping == 'today') {

                                        echo 'ارسال امروز';

                                    }

                                    elseif ($shipping == 'tomorrow') {

                                        echo 'ارسال فردا';

                                    }

                                    elseif ($shipping == '2_3_days') {

                                        echo 'ارسال ۲ تا ۳ روز کاری';

                                    }

                                    elseif ($shipping == '3_5_days') {

                                        echo 'ارسال ۳ تا ۵ روز کاری';

                                    }

                                    else {

                                        echo htmlspecialchars($shipping);
                                    }

                                    ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                <?php } ?>


                <!-- =================================================
                     خرید + علاقه مندی
                ================================================= -->

                <div class="d24p-buy-row d24p-buy-favorite-row">


                    <!-- =================================================
                         سبد خرید
                    ================================================= -->

                    <form
                            id="cart-form"
                            action="cart.php"
                            method="post"
                            class="d24p-cart-form"
                    >

                        <input
                                type="hidden"
                                name="id"
                                value="ax_<?php echo (int) $product['id']; ?>"
                        >


                        <input
                                type="hidden"
                                name="title"
                                value="<?php echo htmlspecialchars($product['title']); ?>"
                        >


                        <input
                                type="hidden"
                                name="price"
                                value="<?php echo htmlspecialchars($product['price']); ?>"
                        >


                        <input
                                type="hidden"
                                name="return_url"
                                value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>"
                        >


                        <button
                                type="submit"
                                name="add"
                                class="d24p-add-cart"
                        >

                            <span>

                                <img
                                        src="image/shopping-cart-alt2.svg"
                                        alt=""
                                        style="width:25px;"
                                >

                            </span>

                            افزودن به سبد خرید

                        </button>

                    </form>


                    <!-- =================================================
                         علاقه مندی
                    ================================================= -->

                    <form
                            method="post"
                            class="d24p-favorite-form"
                    >

                        <input
                                type="hidden"
                                name="favorite_id"
                                value="<?php echo (int) $product['id']; ?>"
                        >


                        <button
                                type="submit"
                                name="add_favorite"
                                class="d24p-add-favorite <?php echo $is_favorite ? 'd24p-favorite-active' : ''; ?> "

                        >

                            <span class="d24p-favorite-icon">

                                <?php if ($is_favorite) { ?>

                                    ♥️

                                <?php } else { ?>

                                    ♡

                                <?php } ?>

                            </span>


                            <?php if ($is_favorite) { ?>

                                اضافه شده به علاقه مندی ها

                            <?php } else { ?>

                                افزودن به علاقه مندی ها

                            <?php } ?>

                        </button>

                    </form>


                </div>


            </div>

        </div>


        <!-- =================================================
             پیشنهاد ویژه
        ================================================= -->

        <div class="col-xl-2 col-lg-3 col-md-4 order-xl-3 order-1">

            <div class="d24p-special-box">


                <div class="d24p-special-image">

                    <img
                            src="https://placehold.co/500x300"
                            alt="پکیج پیشنهادی"
                    >

                </div>


                <div class="d24p-special-content">


                    <h4 class="overflow-visible">

                        بسته پیشنهادی

                    </h4>


                    <p>

                        دوربین عکاسی مدل مناسب عکاسی

                    </p>


                    <div class="d24p-special-item">

                        + کیف دوربین

                    </div>


                    <div class="d24p-special-item">

                        + پایه دوربین

                    </div>


                    <strong>

                        33,000,000 تومان

                    </strong>


                </div>

            </div>

        </div>


    </section>


    <!-- =================================================
         FEATURES
    ================================================= -->

    <section class="d24p-features">


        <div class="d24p-feature">

            <span>

                <img
                        src="image/icon/truck.svg"
                        alt=""
                        style="width: 30px;"
                >

            </span>

            <div>

                <strong>

                    ارسال اکسپرس

                </strong>

                <small>

                    ارسال سریع محصول

                </small>

            </div>

        </div>


        <div class="d24p-feature">

            <span>

                <img
                        src="image/icon/check(1).svg"
                        alt=""
                        style="width: 30px;"
                >

            </span>

            <div>

                <strong>

                    ضمانت اصالت بودن کالا

                </strong>

                <small>

                    کالای اصل و معتبر

                </small>

            </div>

        </div>


        <div class="d24p-feature">

            <span>

                <img
                        src="image/icon/location-arrow.svg"
                        alt=""
                        style="width: 30px;"
                >

            </span>

            <div>

                <strong>

                    ارسال به سراسر ایران

                </strong>

                <small>

                    تحویل در تمام شهرها

                </small>

            </div>

        </div>


        <div class="d24p-feature">

            <span>

                <img
                        src="image/icon/enter.svg"
                        alt=""
                        style="width: 30px;"
                >

            </span>

            <div>

                <strong>

                    مرجوعی کالا در صورت نقص فنی

                </strong>

                <small>

                    با شرایط فروشگاه

                </small>

            </div>

        </div>


        <div class="d24p-feature">

            <span>

                <img
                        src="image/icon/gift.svg"
                        alt=""
                        style="width: 30px;"
                >

            </span>

            <div>

                <strong>

                    بسته بندی زیبا

                </strong>

                <small>

                    بسته بندی ایمن

                </small>

            </div>

        </div>


    </section>


    <!-- =================================================
         TABS
    ================================================= -->

    <section class="d24p-details">


        <!-- سربرگ ها -->

        <div class="d24p-tabs overflow-visible">


            <button
                    type="button"
                    class="d24p-tab overflow-visible"
                    data-tab="specifications"
            >

                مشخصات

            </button>


            <button
                    type="button"
                    class="d24p-tab active overflow-visible"
                    data-tab="reviews"
            >

                نقد و بررسی

            </button>


            <button
                    type="button"
                    class="d24p-tab overflow-visible"
                    data-tab="questions"
            >

                پرسش و پاسخ

            </button>


        </div>


        <!-- =================================================
             مشخصات
        ================================================= -->

        <div
                id="specifications"
                class="d24p-tab-content"
        >

            <h2>

                مشخصات محصول

            </h2>


            <div class="d24p-spec-table">


                <?php if (!empty($specs)) { ?>


                    <?php foreach ($specs as $spec) { ?>


                        <div class="d24p-spec-row">

                            <span>

                                <?php

                                echo htmlspecialchars(
                                    $spec['title']
                                );

                                ?>

                            </span>


                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $spec['value']
                                );

                                ?>

                            </strong>

                        </div>


                    <?php } ?>


                <?php } else { ?>


                    <p>

                        مشخصاتی برای این محصول ثبت نشده است.

                    </p>


                <?php } ?>


            </div>


        </div>


        <!-- =================================================
             نقد و بررسی
        ================================================= -->

        <div
                id="reviews"
                class="d24p-tab-content active"
        >


            <?php if (!empty($product['content'])) { ?>


                <div
                        class="d24p-product-content"
                        style="font-size:clamp(12px, 1.5vw, 16px);"
                >

                    <?php

                    /*
                     * محتوای CKEditor به صورت HTML
                     * داخل content ذخیره شده است.
                     *
                     * htmlspecialchars استفاده نمی‌کنیم
                     * تا فرمت و تصاویر CKEditor نمایش داده شوند.
                     */

                    echo $product['content'];

                    ?>

                </div>


            <?php } else { ?>


                <p>

                    نقد و بررسی برای این محصول ثبت نشده است.

                </p>


            <?php } ?>


        </div>


        <!-- =================================================
             پرسش و پاسخ
        ================================================= -->

        <div
                id="questions"
                class="d24p-tab-content"
        >


            <h2>

                پرسش و پاسخ

            </h2>


            <?php if (!empty($questions)) { ?>


                <?php foreach ($questions as $question) { ?>


                    <div class="d24p-question">


                        <div class="d24p-question-title">

                            <?php

                            echo htmlspecialchars(
                                $question['question']
                            );

                            ?>

                        </div>


                        <div class="d24p-answer">

                            <?php

                            echo nl2br(
                                htmlspecialchars(
                                    $question['answer']
                                )
                            );

                            ?>

                        </div>


                    </div>


                <?php } ?>


            <?php } else { ?>


                <p>

                    هنوز پرسش و پاسخی برای این محصول ثبت نشده است.

                </p>


            <?php } ?>


            <div class="d24p-question-form">


                <textarea
                        placeholder="سوال خود را وارد کنید..."
                ></textarea>


                <button type="button">

                    ارسال سوال

                </button>


            </div>


        </div>


    </section>


</main>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

    document.addEventListener(
        "DOMContentLoaded",
        function () {


            /* =====================================================
               QUANTITY
            ===================================================== */

            const plusBtn =
                document.getElementById("d24p-plus");

            const minusBtn =
                document.getElementById("d24p-minus");

            const countElement =
                document.getElementById("d24p-count");


            let count = 1;


            if (plusBtn) {

                plusBtn.addEventListener(
                    "click",
                    function () {

                        count++;

                        countElement.textContent = count;

                    }
                );
            }


            if (minusBtn) {

                minusBtn.addEventListener(
                    "click",
                    function () {

                        if (count > 1) {

                            count--;

                            countElement.textContent = count;

                        }

                    }
                );
            }


            /* =====================================================
               PRODUCT GALLERY
            ===================================================== */

            const thumbnails =
                document.querySelectorAll(
                    ".d24p-thumbnail"
                );


            const mainImage =
                document.getElementById(
                    "d24p-main-product-image"
                );


            thumbnails.forEach(
                function (thumbnail) {

                    thumbnail.addEventListener(
                        "click",
                        function () {


                            const image =
                                thumbnail.getAttribute(
                                    "data-image"
                                );


                            mainImage.src = image;


                            thumbnails.forEach(
                                function (item) {

                                    item.classList.remove(
                                        "active"
                                    );

                                }
                            );


                            thumbnail.classList.add(
                                "active"
                            );

                        }
                    );

                }
            );


            /* =====================================================
               TABS
               مشخصات / نقد و بررسی / پرسش و پاسخ
            ===================================================== */

            const tabs =
                document.querySelectorAll(
                    ".d24p-tab"
                );


            const tabContents =
                document.querySelectorAll(
                    ".d24p-tab-content"
                );


            tabs.forEach(
                function (tab) {

                    tab.addEventListener(
                        "click",
                        function () {


                            const target =
                                tab.getAttribute(
                                    "data-tab"
                                );


                            /* حذف active از سربرگ ها */

                            tabs.forEach(
                                function (item) {

                                    item.classList.remove(
                                        "active"
                                    );

                                }
                            );


                            /* اضافه کردن active */

                            tab.classList.add(
                                "active"
                            );


                            /* مخفی کردن محتوا */

                            tabContents.forEach(
                                function (content) {

                                    content.classList.remove(
                                        "active"
                                    );

                                }
                            );


                            /* نمایش محتوای انتخاب شده */

                            const selectedContent =
                                document.getElementById(
                                    target
                                );


                            if (selectedContent) {

                                selectedContent.classList.add(
                                    "active"
                                );

                            }

                        }
                    );

                }
            );


            /* =====================================================
               QUESTIONS
            ===================================================== */

            const questions =
                document.querySelectorAll(
                    ".d24p-question"
                );


            questions.forEach(
                function (question) {


                    const title =
                        question.querySelector(
                            ".d24p-question-title"
                        );


                    if (title) {

                        title.addEventListener(
                            "click",
                            function () {

                                question.classList.toggle(
                                    "open"
                                );

                            }
                        );

                    }

                }
            );


        }
    );

</script>


<br>
<br>


<script
        src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"
></script>


<?php
include "footer.php";
?>
