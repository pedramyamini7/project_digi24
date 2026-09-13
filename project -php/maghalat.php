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


// ==================================================
// PAGINATION
// ==================================================

$per_page = 9;


// شماره صفحه
$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;


// جلوگیری از شماره صفحه نامعتبر
if ($page < 1) {
    $page = 1;
}


// ==================================================
// تعداد کل مقالات
// ==================================================

$count_sql = "
    SELECT COUNT(*)
    FROM maghale_dakhel
";

$count_result = mysqli_query(
    $conn,
    $count_sql
);


if (!$count_result) {

    die(
        "خطا در شمارش مقالات: "
        . mysqli_error($conn)
    );

}


$total = mysqli_fetch_row($count_result)[0];


// ==================================================
// تعداد صفحات
// ==================================================

$total_pages = ($total > 0)
    ? ceil($total / $per_page)
    : 0;


// اگر شماره صفحه بیشتر از آخرین صفحه بود
if ($total_pages > 0 && $page > $total_pages) {

    $page = $total_pages;

}


// ==================================================
// محاسبه شروع نمایش
// ==================================================

$start = ($page - 1) * $per_page;


// ==================================================
// دریافت مقالات
// ==================================================

$sql = "
    SELECT
        id,
        title,
        image,
        content
    FROM maghale_dakhel
    ORDER BY id DESC
    LIMIT $start, $per_page
";


$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    die(
        "خطا در دریافت مقالات: "
        . mysqli_error($conn)
    );

}

?>


<?php

include "header.php";

?>


<!-- ==================================================
     MAIN
================================================== -->

<main class="xq7_main">


    <!-- ==================================================
         BACKGROUND
    ================================================== -->

    <div class="xq7_background_circle xq7_circle_top"></div>


    <section class="xq7_articles">


        <!-- ==================================================
             TITLE
        ================================================== -->

        <div class="xq7_title_box">


            <h1 class="overflow-visible">

                مجله <span>Digi24</span>

            </h1>


            <p
                    style="
                    font-size:clamp(13px, 1.5vw, 20px);
                "
            >

                جدیدترین مقالات و مطالب آموزشی
                دنیای دیجیتال را در مجله Digi24
                دنبال کنید.

            </p>


        </div>



        <!-- ==================================================
             ARTICLES GRID
        ================================================== -->

        <div class="xq7_grid">


            <?php

            if (mysqli_num_rows($result) > 0) {


                while (
                $row = mysqli_fetch_assoc($result)
                ) {

                    ?>


                    <!-- ==================================================
                         ARTICLE CARD
                    ================================================== -->

                    <article class="xq7_card">


                        <!-- ==================================================
                             IMAGE
                        ================================================== -->

                        <?php

                        if (!empty($row['image'])) {

                            $image_name = basename(
                                $row['image']
                            );

                            $image_url =
                                "uploads/"
                                . rawurlencode($image_name);

                            ?>

                            <img
                                    src="<?php echo htmlspecialchars(
                                        $image_url,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                    alt="<?php echo htmlspecialchars(
                                        $row['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                            >

                            <?php

                        } else {

                            ?>

                            <div
                                    style="
                                    width:100%;
                                    min-height:200px;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                "
                            >

                                بدون تصویر

                            </div>

                            <?php

                        }

                        ?>


                        <!-- ==================================================
                             TITLE
                        ================================================== -->

                        <h2
                                class="overflow-visible"
                                style="
                                text-align:right;
                            "
                        >

                            <?php

                            $article_title = $row['title'] ?? '';


                            if (
                                mb_strlen(
                                    $article_title,
                                    'UTF-8'
                                ) > 40
                            ) {

                                echo htmlspecialchars(
                                    mb_substr(
                                        $article_title,
                                        0,
                                        40,
                                        'UTF-8'
                                    ) . '...',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                            } else {

                                echo htmlspecialchars(
                                    $article_title,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                            }

                            ?>

                        </h2>



                        <!-- ==================================================
                             DESCRIPTION
                        ================================================== -->

                        <p
                                style="
                                font-size:clamp(13px, 1.5vw, 14px);
                                text-align:right;
                            "
                        >

                            <?php

                            /*
                             * محتوای CKEditor شامل HTML است.
                             * برای نمایش خلاصه مقاله، ابتدا تگ‌های
                             * HTML را حذف می‌کنیم.
                             */

                            $article_content =
                                strip_tags(
                                    $row['content'] ?? ''
                                );


                            // تبدیل فاصله‌های اضافی
                            $article_content =
                                trim(
                                    preg_replace(
                                        '/\s+/u',
                                        ' ',
                                        $article_content
                                    )
                                );


                            if (
                                mb_strlen(
                                    $article_content,
                                    'UTF-8'
                                ) > 100
                            ) {

                                echo htmlspecialchars(
                                    mb_substr(
                                        $article_content,
                                        0,
                                        100,
                                        'UTF-8'
                                    ) . '...',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                            } else {

                                echo htmlspecialchars(
                                    $article_content,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                            }

                            ?>

                        </p>



                        <!-- ==================================================
                             CONTINUE
                        ================================================== -->

                        <a
                                href="maghalat-dakhel.php?id=<?php echo (int) $row['id']; ?>"
                        >

                            ادامه مطلب >>

                        </a>


                    </article>


                    <?php

                }


            } else {

                ?>


                <!-- ==================================================
                     NO ARTICLE
                ================================================== -->

                <p>

                    مقاله‌ای برای نمایش وجود ندارد.

                </p>


                <?php

            }

            ?>


        </div>



        <!-- ==================================================
             PAGINATION
        ================================================== -->

        <?php

        if ($total_pages > 1) {

            ?>


            <div class="pagination">


                <!-- ==================================================
                     PREVIOUS
                ================================================== -->

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


                // ==================================================
                // اگر تعداد صفحات 7 یا کمتر باشد
                // ==================================================

                if ($total_pages <= 7) {


                    for (
                        $i = 1;
                        $i <= $total_pages;
                        $i++
                    ) {

                        ?>

                        <a
                                href="?page=<?php echo $i; ?>"
                                class="<?php
                                echo (
                                    $i == $page
                                )
                                    ? 'active'
                                    : '';
                                ?>"
                        >

                            <?php echo $i; ?>

                        </a>

                        <?php

                    }


                } else {


                    // ==================================================
                    // صفحه اول
                    // ==================================================

                    ?>

                    <a
                            href="?page=1"
                            class="<?php
                            echo (
                                $page == 1
                            )
                                ? 'active'
                                : '';
                            ?>"
                    >

                        1

                    </a>


                    <?php


                    // ==================================================
                    // سه نقطه سمت چپ
                    // ==================================================

                    if ($page > 4) {

                        ?>

                        <span>

                            ...

                        </span>

                        <?php

                    }


                    // ==================================================
                    // صفحات اطراف صفحه فعلی
                    // ==================================================

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
                                class="<?php
                                echo (
                                    $i == $page
                                )
                                    ? 'active'
                                    : '';
                                ?>"
                        >

                            <?php echo $i; ?>

                        </a>

                        <?php

                    }


                    // ==================================================
                    // سه نقطه سمت راست
                    // ==================================================

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


                    // ==================================================
                    // آخرین صفحه
                    // ==================================================

                    ?>

                    <a
                            href="?page=<?php echo $total_pages; ?>"
                            class="<?php
                            echo (
                                $page == $total_pages
                            )
                                ? 'active'
                                : '';
                            ?>"
                    >

                        <?php echo $total_pages; ?>

                    </a>

                    <?php

                }

                ?>



                <!-- ==================================================
                     NEXT
                ================================================== -->

                <?php

                if ($page < $total_pages) {

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


</main>



<!-- ==================================================
     FOOTER
================================================== -->
<script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

<?php

include "footer.php";

?>
