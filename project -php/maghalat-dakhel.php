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

// =====================================================
// گرفتن ID مقاله
// =====================================================

$article_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


// اگر ID معتبر نبود
if ($article_id <= 0) {

    header("Location: index.php");
    exit;

}


// =====================================================
// اطلاعات مقاله
// =====================================================

$sql_article = "
    SELECT
        id,
        title,
        author,
        category,
        image,
        content,
        created_at
    FROM maghale_dakhel
    WHERE id = $article_id
    LIMIT 1
";


$result_article = mysqli_query(
    $conn,
    $sql_article
);


// بررسی خطای Query
if (!$result_article) {

    die(
        "خطا در دریافت مقاله: "
        . mysqli_error($conn)
    );

}


// گرفتن مقاله
$article = mysqli_fetch_assoc(
    $result_article
);


// اگر مقاله پیدا نشد
if (!$article) {

    header("Location: index.php");
    exit;

}


// =====================================================
// اطلاعات آماده برای نمایش
// =====================================================

$title = htmlspecialchars(
    $article['title'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);


$author = htmlspecialchars(
    $article['author'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);


$category = htmlspecialchars(
    $article['category'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);


$image = !empty($article['image'])
    ? basename($article['image'])
    : '';


$content = $article['content'] ?? '';


$created_at = !empty($article['created_at'])
    ? date(
        'Y/m/d',
        strtotime($article['created_at'])
    )
    : '';

?>


<?php

include "header.php";

?>


<!-- =====================================================
     SIDE BUTTONS
===================================================== -->

<!--
<div class="navar-blue"></div>

<div class="mag-did-side-buttons">

    <button class="mag-did-side-btn mag-did-side-cart">
        🛒
    </button>

    <button class="mag-did-side-btn mag-did-side-message">
        ✉
    </button>

</div>
-->


<!-- =====================================================
     ARTICLE
===================================================== -->

<main class="mag-did-main">

    <article class="mag-did-article">


        <!-- =================================================
             TITLE
        ================================================== -->

        <h1 class="mag-did-title overflow-visible">

            <?php echo $title; ?>

        </h1>


        <!-- =================================================
             META
        ================================================== -->

        <div class="mag-did-meta overflow-visible">


            <!-- نویسنده -->

            <div class="mag-did-meta-item overflow-visible">

                <span>♟</span>

                <span class="overflow-visible">

                    نویسنده:

                    <?php echo $author; ?>

                </span>

            </div>


            <!-- تاریخ -->

            <div class="mag-did-meta-item overflow-visible">

                <span>▣</span>

                <span class="overflow-visible">

                    <?php echo $created_at; ?>

                </span>

            </div>


            <!-- دسته بندی -->

            <div class="mag-did-meta-item overflow-visible">

                <span>▦</span>

                <span class="overflow-visible">

                    <?php echo $category; ?>

                </span>

            </div>


        </div>


        <!-- =================================================
             ARROW
        ================================================== -->

        <div class="mag-did-down-arrow overflow-visible">

            ↓

        </div>


        <!-- =================================================
             MAIN IMAGE
        ================================================== -->

        <?php

        if (!empty($image)) {

            ?>

            <div class="mag-did-image-box">

                <img
                        src="uploads/<?php
                        echo rawurlencode($image);
                        ?>"
                        width="500"
                        alt="<?php echo $title; ?>"
                >

            </div>

            <?php

        }

        ?>


        <!-- =================================================
             DECORATION
        ================================================== -->

        <div class="mag-did-decoration mag-did-decoration-top"></div>


        <!-- =================================================
             ARTICLE CONTENT
        ================================================== -->

        <div
                class="mag-did-text"
                style="
                font-size:clamp(13px, 1.5vw, 17px);
            "
        >

            <?php

            /*
             * محتوای CKEditor مستقیماً به صورت HTML
             * در content ذخیره شده است.
             *
             * بنابراین strip_tags نمی‌کنیم،
             * چون در غیر این صورت فرمت‌هایی مثل:
             *
             * <h2>
             * <strong>
             * <img>
             * <ul>
             * <table>
             *
             * از بین می‌روند.
             */

            echo $content;

            ?>

        </div>


        <!-- =================================================
             ARTICLE BOTTOM
        ================================================== -->

        <div class="mag-did-article-bottom">


            <div class="mag-did-social">

                <span>in</span>

                <span>f</span>

                <span>𝕏</span>

            </div>


            <div class="mag-did-article-info">


                <span>

                    نویسنده:

                    <?php echo $author; ?>

                </span>


                <span>

                    <?php echo $created_at; ?>

                </span>


            </div>


        </div>


        <!-- =================================================
             COMMENTS
        ================================================== -->

        <section class="mag-did-comments">


            <h2 class="mag-did-comments-title">

                نظرات

            </h2>


            <!-- =================================================
                 COMMENT FORM
            ================================================== -->

            <div class="mag-did-comment-form">


                <div class="mag-did-form-row">


                    <input
                            type="text"
                            placeholder="نام شما"
                    >


                    <input
                            type="email"
                            placeholder="ایمیل شما"
                    >


                </div>


                <textarea
                        placeholder="دیدگاه شما"
                ></textarea>


                <button
                        class="mag-did-comment-submit"
                        onclick="magDidSendComment()"
                >

                    ثبت نظر

                </button>


            </div>


            <!-- =================================================
                 نمونه نظر
            ================================================== -->

            <div class="mag-did-comment">


                <div class="mag-did-comment-head">

                    <strong>

                        کاربر

                    </strong>

                    <span>

                        1401/10/02

                    </span>

                </div>


                <p>

                    مطلب کاربردی و خوبی بود.

                </p>


            </div>


            <!-- =================================================
                 نمونه نظر دوم
            ================================================== -->

            <div class="mag-did-comment">


                <div class="mag-did-comment-head">

                    <strong>

                        علی

                    </strong>

                    <span>

                        1401/10/02

                    </span>

                </div>


                <p>

                    ممنون بابت توضیحات کامل.

                </p>


            </div>


        </section>


        <!-- =================================================
             RELATED POSTS
        ================================================== -->

        <!--

        <section class="mag-did-related">

            <h2 class="mag-did-related-title">

                دیگر مطالب مرتبط

            </h2>

        </section>

        -->


    </article>

</main>

<script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

<?php

include "footer.php";

?>
