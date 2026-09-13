<?php

session_start();

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


/* =====================================================
   PAGINATION
===================================================== */

$per_page = 12;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $per_page;


/* =====================================================
   تعداد محصولات اصلی
===================================================== */

$count_sql = "
    SELECT COUNT(*)
    FROM projects_noor
    WHERE id = project_id
";

$count_result = mysqli_query(
    $conn,
    $count_sql
);

if (!$count_result) {
    // اگر کوئری شمارش fail شد، لاگ می‌کنیم و صفر در نظر می‌گیریم
    error_log("Count query failed: " . mysqli_error($conn));
    $total = 0;
} else {
    $total = mysqli_fetch_row($count_result)[0];
}


/* =====================================================
   تعداد صفحات
===================================================== */

$total_pages = ceil($total / $per_page);


/* =====================================================
   محصولات صفحه فعلی
   فقط ردیف اصلی هر محصول
===================================================== */

$sql = "
    SELECT *
    FROM projects_noor
    WHERE id = project_id
    ORDER BY id DESC
    LIMIT $start, $per_page
";

$result = mysqli_query(
    $conn,
    $sql
);

if (!$result) {
    // اگر کوئری محصولات fail شد، لاگ می‌کنیم
    // (در production بهتره صفحه خطا نشون بدید نه die خام)
    error_log("Products query failed: " . mysqli_error($conn));
}

?>

<?php
include "header.php";
?>


    <!-- =====================================================
         MAIN
    ===================================================== -->

    <main class="container das-mah-main">


        <!-- =================================================
             PRODUCTS + SIDEBAR
        ================================================= -->

        <div class="row g-3">


            <!-- ================= SIDEBAR ================= -->

            <div class="col-lg-3 col-12 order-1 order-lg-1">

                <aside class="das-mah-sidebar">


                    <!-- SEARCH FILTER -->

                    <div class="das-mah-filter-box">

                        <h3>
                            جستجو
                        </h3>


                        <div class="das-mah-filter-search">

                            <input
                                    type="text"
                                    class="form-control"
                                    placeholder="محصول مورد نظر خود را جستجو نمایید"
                            >

                            <span>
                            ⌕
                        </span>

                        </div>

                    </div>


                    <!-- BRAND -->

                    <div class="das-mah-filter-box">

                        <h3>
                            برند
                        </h3>


                        <label class="das-mah-check-row">

                        <span>
                            کانن
                        </span>

                            <input
                                    type="checkbox"
                                    checked
                            >

                        </label>

                    </div>


                    <!-- PRICE -->

                    <div class="das-mah-filter-box">

                        <h3>
                            محدوده قیمت
                        </h3>


                        <div class="das-mah-price-line">
                        </div>


                        <div class="das-mah-price-values d-flex justify-content-between">

                        <span>
                            ۳۲,۰۰۰,۰۰۰ تومان
                        </span>

                            <span>
                            ۰ تومان
                        </span>

                        </div>

                    </div>


                    <!-- OTHER BRANDS -->

                    <div class="das-mah-filter-box">

                        <h3>
                            دیگر برندها
                        </h3>


                        <label class="das-mah-check-row">

                        <span>
                            نیکون
                        </span>

                            <input
                                    type="checkbox"
                                    checked
                            >

                        </label>


                        <label class="das-mah-check-row">

                        <span>
                            پاناسونیک
                        </span>

                            <input
                                    type="checkbox"
                                    checked
                            >

                        </label>


                        <label class="das-mah-check-row">

                        <span>
                            سونی
                        </span>

                            <input
                                    type="checkbox"
                                    checked
                            >

                        </label>


                        <label class="das-mah-check-row">

                        <span>
                            فوجی فیلم
                        </span>

                            <input
                                    type="checkbox"
                                    checked
                            >

                        </label>

                    </div>

                </aside>

            </div>


            <!-- ================= PRODUCTS ================= -->

            <div class="col-lg-9 col-12 order-2 order-lg-2">

                <section class="das-mah-products-area">


                    <!-- =================================================
                         SORT
                    ================================================== -->

                    <div class="das-mah-sort-box d-flex align-items-center flex-wrap">

                    <span class="das-mah-sort-title">
                        ترتیب نمایش:
                    </span>


                        <button>
                            ترتیب نمایش
                        </button>


                        <button>
                            پربازدیدترین
                        </button>


                        <button class="das-mah-active-sort">
                            گران ترین
                        </button>


                        <button>
                            ارزان ترین
                        </button>


                        <button>
                            پربحث ترین
                        </button>


                        <button>
                            جدیدترین
                        </button>

                    </div>


                    <!-- =================================================
                         PRODUCT GRID
                    ================================================== -->

                    <div class="row g-0 das-mah-products-grid overflow-visible d-flex">


                        <?php

                        if ($result && mysqli_num_rows($result) > 0) {

                            while (
                            $row = mysqli_fetch_assoc($result)
                            ) {

                                ?>

                                <a
                                        href="products-noor.php?id=<?php echo (int) $row['id']; ?>"
                                        class="das-mah-product overflow-visible"
                                >


                                    <!-- IMAGE -->

                                    <?php

                                    if (!empty($row['image1'])) {

                                        ?>

                                        <img
                                                src="up/<?php echo htmlspecialchars(
                                                    $row['image1']
                                                ); ?>"
                                                alt="<?php echo htmlspecialchars(
                                                    $row['title']
                                                ); ?>"
                                                class="das-mah-product-image"
                                        >

                                        <?php

                                    } else {

                                        ?>

                                        <img
                                                src="https://placehold.co/500x400?text=No+Image"
                                                alt="بدون تصویر"
                                                class="das-mah-product-image"
                                        >

                                        <?php

                                    }

                                    ?>


                                    <!-- TITLE -->

                                    <div class="das-mah-product-title">

                                        <?php

                                        $proTitle = $row['title'];

                                        if (
                                            mb_strlen(
                                                $proTitle,
                                                'UTF-8'
                                            ) > 60
                                        ) {

                                            echo htmlspecialchars(
                                                    mb_substr(
                                                        $proTitle,
                                                        0,
                                                        60,
                                                        'UTF-8'
                                                    )
                                                ) . '...';

                                        } else {

                                            echo htmlspecialchars(
                                                $proTitle
                                            );

                                        }

                                        ?>

                                    </div>


                                    <!-- PRICE -->

                                    <div
                                            class="das-mah-product-price"
                                    >

                                        <?php

                                        echo number_format(
                                            (float) $row['price']
                                        );

                                        ?>

                                        تومان

                                    </div>


                                    <!-- OLD PRICE -->

                                    <?php

                                    if (
                                        !empty($row['price_fake'])
                                    ) {

                                        ?>

                                        <div
                                                class="das-mah-product-old-price"
                                                style="font-size: clamp(5px, 1vw, 12px)"
                                        >

                                            <?php

                                            echo number_format(
                                                (float) $row['price_fake']
                                            );

                                            ?>

                                            تومان

                                        </div>

                                        <?php

                                    }

                                    ?>


                                </a>


                                <?php

                            }

                        } else {

                            ?>

                            <p style="padding: 20px;">
                                محصولی یافت نشد.
                            </p>

                            <?php

                        }

                        ?>


                    </div>


                    <!-- =================================================
                         PAGINATION
                    ================================================== -->

                    <?php

                    if ($total_pages > 1) {

                        ?>

                        <div class="pagination">


                            <!-- PREVIOUS -->

                            <?php

                            if ($page > 1) {

                                ?>

                                <a
                                        href="?page=<?php echo $page - 1; ?>"
                                >
                                    ‹
                                </a>

                                <?php

                            }

                            ?>


                            <?php

                            // =================================================
                            // اگر تعداد صفحات 7 یا کمتر باشد
                            // =================================================

                            if ($total_pages <= 7) {

                                for (
                                    $i = 1;
                                    $i <= $total_pages;
                                    $i++
                                ) {

                                    ?>

                                    <a
                                            href="?page=<?php echo $i; ?>"
                                            class="<?php echo (
                                                $i == $page
                                            ) ? 'active' : ''; ?>"
                                    >
                                        <?php echo $i; ?>
                                    </a>

                                    <?php

                                }

                            } else {

                                // =================================================
                                // صفحه اول
                                // =================================================
                                ?>

                                <a
                                        href="?page=1"
                                        class="<?php echo (
                                            $page == 1
                                        ) ? 'active' : ''; ?>"
                                >
                                    1
                                </a>


                                <?php

                                if ($page > 4) {

                                    ?>

                                    <span>
                                    ...
                                </span>

                                    <?php

                                }


                                // =================================================
                                // صفحات اطراف صفحه فعلی
                                // =================================================

                                $start_page = max(
                                    2,
                                    $page - 1
                                );

                                $end_page = min(
                                    $total_pages - 1,
                                    $page + 1
                                );


                                for (
                                    $i = $start_page;
                                    $i <= $end_page;
                                    $i++
                                ) {

                                    ?>

                                    <a
                                            href="?page=<?php echo $i; ?>"
                                            class="<?php echo (
                                                $i == $page
                                            ) ? 'active' : ''; ?>"
                                    >
                                        <?php echo $i; ?>
                                    </a>

                                    <?php

                                }


                                if (
                                    $page <
                                    $total_pages - 3
                                ) {

                                    ?>

                                    <span>
                                    ...
                                </span>

                                    <?php

                                }


                                // =================================================
                                // آخرین صفحه
                                // =================================================
                                ?>

                                <a
                                        href="?page=<?php echo $total_pages; ?>"
                                        class="<?php echo (
                                            $page == $total_pages
                                        ) ? 'active' : ''; ?>"
                                >
                                    <?php echo $total_pages; ?>
                                </a>

                                <?php

                            }


                            // =================================================
                            // NEXT
                            // =================================================

                            if (
                                $page <
                                $total_pages
                            ) {

                                ?>

                                <a
                                        href="?page=<?php echo $page + 1; ?>"
                                >
                                    ›
                                </a>

                                <?php

                            }

                            ?>


                        </div>

                        <?php

                    }

                    ?>


                </section>

            </div>

        </div>


        <!-- =================================================
             DESCRIPTION
        ================================================= -->

        <section class="das-mah-description">


            <h2
                    style="font-size:clamp(13px, 1.5vw, 17px);"
                    class="overflow-visible"
            >
                دوربین دیجیتال
            </h2>


            <p
                    style="font-size:clamp(11px, 1.5vw, 12px);"
            >
                حتی اگر تا به حال به یک دوربین دیجیتال عکاسی توجه نکرده باشید
                و چیزی از عکاسی ندانید باز هم نام برند کانن را شنیده اید.
                این غول دنیای دیجیتال مدل های مختلفی از دوربین های DSLR
                را در خط تولید خود دارد که طیف گسترده ای از کاربران را پوشش می دهد.
            </p>


            <h3
                    style="font-size:clamp(13px, 1.5vw, 17px);"
                    class="overflow-visible"
            >
                انواع دوربین های کانون
            </h3>


            <p
                    style="font-size:clamp(11px, 1.5vw, 12px);"
            >
                اگر به دنبال خرید یک دوربین حرفه ای برای عکاسی هستید،
                گزینه های مختلفی پیش روی شما قرار دارد.
                دوربین های DSLR و بدون آینه از محبوب ترین انتخاب ها هستند.
            </p>


            <h3
                    style="font-size:clamp(13px, 1.5vw, 17px);"
                    class="overflow-visible"
            >
                قیمت دوربین عکاسی کانون
            </h3>


            <p
                    style="font-size:clamp(11px, 1.5vw, 12px);"
            >
                قیمت دوربین های کانن با توجه به نوع و امکانات محصول متفاوت است.
                مدل های حرفه ای معمولاً قیمت بیشتری دارند و برای کاربران حرفه ای
                طراحی شده اند.
            </p>


            <h3
                    style="font-size:clamp(13px, 1.5vw, 17px);"
                    class="overflow-visible"
            >
                خرید دوربین عکاسی کانون
            </h3>


            <p
                    style="font-size:clamp(11px, 1.5vw, 12px);"
            >
                در صورتی که قصد خرید دوربین عکاسی کانن را دارید،
                بهتر است مشخصات فنی و نیاز خود را بررسی کنید.
                ارسال محصولات به تمام نقاط ایران امکان پذیر است.
            </p>

        </section>


        <!-- =================================================
             FAQ
        ================================================= -->

        <section class="das-mah-faq">


            <div class="das-mah-faq-title">

            <span
                    style="font-size:clamp(13px, 1.5vw, 17px);"
            >
                سوالات متداول
            </span>

                <b>
                    ?
                </b>

            </div>


            <!-- FAQ 1 -->

            <div class="das-mah-faq-item">

                <button>

                <span
                        style="font-size:clamp(11px, 1.5vw, 13px);"
                >
                    نحوه خرید و سفارش دوربین عکاسی در دیجی 24 چگونه است؟
                </span>

                    <span>
                    ⌄
                </span>

                </button>


                <div
                        class="das-mah-faq-answer"
                        style="font-size:clamp(10px, 1.5vw, 12px);"
                >
                    نمیدونم
                </div>

            </div>


            <!-- FAQ 2 -->

            <div class="das-mah-faq-item">

                <button>

                <span
                        style="font-size:clamp(11px, 1.5vw, 13px);"
                >
                    نحوه پرداخت هزینه دوربین های کانون چگونه است؟
                </span>

                    <span>
                    ⌄
                </span>

                </button>


                <div
                        class="das-mah-faq-answer"
                        style="font-size:clamp(10px, 1.5vw, 12px);"
                >
                    نمیدونم
                </div>

            </div>


            <!-- FAQ 3 -->

            <div class="das-mah-faq-item">

                <button>

                <span
                        style="font-size:clamp(11px, 1.5vw, 13px);"
                >
                    آیا دوربین های کانن با گارانتی عرضه می شوند؟
                </span>

                    <span>
                    ⌄
                </span>

                </button>


                <div
                        class="das-mah-faq-answer"
                        style="font-size:clamp(10px, 1.5vw, 12px);"
                >
                    نمیدونم
                </div>

            </div>


            <!-- FAQ 4 -->

            <div class="das-mah-faq-item">

                <button>

                <span
                        style="font-size:clamp(11px, 1.5vw, 13px);"
                >
                    آیا امکان مراجعه و خرید دوربین ها به صورت حضوری هم وجود دارد؟
                </span>

                    <span>
                    ⌄
                </span>

                </button>


                <div
                        class="das-mah-faq-answer"
                        style="font-size:clamp(10px, 1.5vw, 12px);"
                >
                    نمیدونم
                </div>

            </div>


        </section>


    </main>


    <!-- =====================================================
         FAQ JAVASCRIPT
    ===================================================== -->

    <script>

        const dasMahFaqItems =
            document.querySelectorAll(
                ".das-mah-faq-item"
            );


        dasMahFaqItems.forEach(function(item) {

            const dasMahButton =
                item.querySelector("button");


            dasMahButton.addEventListener(
                "click",
                function() {

                    dasMahFaqItems.forEach(
                        function(otherItem) {

                            if (
                                otherItem !== item
                            ) {

                                otherItem.classList.remove(
                                    "das-mah-open"
                                );

                            }

                        }
                    );


                    item.classList.toggle(
                        "das-mah-open"
                    );

                }
            );

        });

    </script>

    <script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

<?php
include "footer.php";
?>