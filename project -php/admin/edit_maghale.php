<?php
session_start();

require "../config/config.php";


// =====================================================
// گرفتن ID مقاله
// =====================================================

$article_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


// اگر ID معتبر نبود
if ($article_id <= 0) {

    header("Location: jadval_maghale.php");
    exit;

}


// =====================================================
// دریافت اطلاعات مقاله
// =====================================================

$sql_article = "
    SELECT
        id,
        title,
        author,
        category,
        image,
        content
    FROM maghale_dakhel
    WHERE id = $article_id
    LIMIT 1
";


$result_article = mysqli_query(
    $conn,
    $sql_article
);


if (!$result_article) {

    die(
        "خطا در دریافت مقاله: "
        . mysqli_error($conn)
    );

}


$article = mysqli_fetch_assoc(
    $result_article
);


// اگر مقاله وجود نداشت
if (!$article) {

    header("Location: jadval_maghale.php");
    exit;

}


// =====================================================
// اطلاعات اولیه
// =====================================================

$title = $article['title'] ?? '';

$author = $article['author'] ?? '';

$category = $article['category'] ?? '';

$oldImage = $article['image'] ?? '';

$content = $article['content'] ?? '';


// =====================================================
// ذخیره تغییرات
// =====================================================

if (isset($_POST['save'])) {


    // =================================================
    // دریافت اطلاعات
    // =================================================

    $newTitle = mysqli_real_escape_string(
        $conn,
        $_POST['title'] ?? ''
    );


    $newAuthor = mysqli_real_escape_string(
        $conn,
        $_POST['author'] ?? ''
    );


    $newCategory = mysqli_real_escape_string(
        $conn,
        $_POST['category'] ?? ''
    );


    $newContent = mysqli_real_escape_string(
        $conn,
        $_POST['content'] ?? ''
    );


    // =================================================
    // عکس جدید
    // =================================================

    $newImage = $oldImage;


    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {


        $originalName = $_FILES['image']['name'];

        $tmpImage = $_FILES['image']['tmp_name'];


        // -----------------------------
        // پسوند
        // -----------------------------

        $extension = strtolower(
            pathinfo(
                $originalName,
                PATHINFO_EXTENSION
            )
        );


        // -----------------------------
        // فرمت‌های مجاز
        // -----------------------------

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


        if (
            !in_array(
                $extension,
                $allowedExtensions
            )
        ) {

            die(
            "فرمت عکس مجاز نیست."
            );

        }


        // -----------------------------
        // نام جدید
        // -----------------------------

        $newImage =
            uniqid(
                'article_',
                true
            )
            . '.'
            . $extension;


        // -----------------------------
        // پوشه آپلود
        // -----------------------------

        $uploadDir = "../uploads/";


        if (!is_dir($uploadDir)) {

            mkdir(
                $uploadDir,
                0777,
                true
            );

        }


        // -----------------------------
        // انتقال عکس
        // -----------------------------

        if (
            !move_uploaded_file(
                $tmpImage,
                $uploadDir . $newImage
            )
        ) {

            die(
            "آپلود عکس جدید انجام نشد."
            );

        }


        // =================================================
        // حذف عکس قبلی
        // =================================================

        if (
            !empty($oldImage)
        ) {

            $oldImagePath =
                $uploadDir .
                basename($oldImage);


            if (
                file_exists($oldImagePath)
            ) {

                unlink($oldImagePath);

            }

        }

    }


    // =================================================
    // UPDATE
    // =================================================

    $sql_update = "
        UPDATE maghale_dakhel
        SET
            title = '$newTitle',
            author = '$newAuthor',
            category = '$newCategory',
            image = '$newImage',
            content = '$newContent'
        WHERE id = $article_id
        LIMIT 1
    ";


    $result_update = mysqli_query(
        $conn,
        $sql_update
    );


    // =================================================
    // نتیجه
    // =================================================

    if ($result_update) {

        header(
            "Location: jadval_maghale.php"
        );

        exit;

    } else {

        die(
            "خطا در ویرایش مقاله: "
            . mysqli_error($conn)
        );

    }

}

?>

<?php

include "haeder.php";

?>

<style>

    /* =====================================================
       MAIN
    ===================================================== */

    .digi-edit-article-page {
        min-height: 90vh;
        padding: 38px 30px 75px;

        background:
                radial-gradient(
                        circle at 92% 3%,
                        rgba(0,129,255,.10),
                        transparent 27%
                ),
                radial-gradient(
                        circle at 5% 92%,
                        rgba(224,143,48,.11),
                        transparent 30%
                ),
                #f7f5f1;

        font-family: inherit;
    }


    /* =====================================================
       TOP
    ===================================================== */

    .digi-edit-top {
        max-width: 1180px;
        margin: 0 auto 25px;
    }

    .digi-edit-heading {
        position: relative;
        padding-right: 18px;
    }

    .digi-edit-heading::before {
        content: "";

        position: absolute;

        right: 0;
        top: 4px;

        width: 4px;
        height: 52px;

        border-radius: 10px;

        background:
                linear-gradient(
                        180deg,
                        #0081ff,
                        #0066ff
                );

        box-shadow:
                0 7px 18px rgba(0,129,255,.25);
    }

    .digi-edit-heading h1 {
        margin: 0;

        color: #28231f;

        font-size: 31px;
        font-weight: 950;

        letter-spacing: -1.2px;
    }

    .digi-edit-heading p {
        margin: 9px 0 0;

        color: #94897d;

        font-size: 12px;
        font-weight: 600;
    }


    /* =====================================================
       MAIN CARD
    ===================================================== */

    .digi-edit-card {
        max-width: 1180px;

        margin: auto;

        overflow: hidden;

        border: 1px solid #e8dfd5;

        border-radius: 26px;

        background: #fffdf9;

        box-shadow:
                0 30px 70px rgba(69,54,39,.10),
                0 5px 18px rgba(69,54,39,.045);
    }


    /* =====================================================
       CARD HEADER
    ===================================================== */

    .digi-edit-card-header {
        position: relative;

        padding: 25px 30px;

        overflow: hidden;

        border-bottom: 1px solid #ebe3da;

        background:
                linear-gradient(
                        135deg,
                        #fffdf9,
                        #faf6f0
                );
    }

    .digi-edit-card-header::after {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        left: -45px;
        top: -75px;

        border-radius: 50%;

        background: rgba(0,129,255,.045);
    }

    .digi-edit-card-title {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;

        gap: 13px;
    }

    .digi-edit-card-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background:
                linear-gradient(
                        135deg,
                        #0081ff,
                        #0067ff
                );

        color: white;

        font-size: 18px;

        box-shadow:
                0 10px 22px rgba(0,129,255,.22);
    }

    .digi-edit-card-title strong {
        display: block;

        color: #332d27;

        font-size: 14px;
        font-weight: 900;
    }

    .digi-edit-card-title span {
        display: block;

        margin-top: 4px;

        color: #9b9084;

        font-size: 10px;
    }


    /* =====================================================
       BODY
    ===================================================== */

    .digi-edit-body {
        padding: 32px;
    }


    /* =====================================================
       INPUT GRID
    ===================================================== */

    .digi-edit-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 20px;
    }


    /* =====================================================
       FIELD
    ===================================================== */

    .digi-edit-field {
        position: relative;
    }

    .digi-edit-field.full {
        grid-column: 1 / -1;
    }

    .digi-edit-field label,
    .digi-image-label {
        display: flex;
        align-items: center;

        gap: 8px;

        margin-bottom: 9px;

        color: #4a423a;

        font-size: 11px;
        font-weight: 900;
    }

    .digi-label-dot {
        width: 6px;
        height: 6px;

        flex-shrink: 0;

        border-radius: 50%;

        background: #0081ff;

        box-shadow:
                0 0 0 4px rgba(0,129,255,.08);
    }


    /* =====================================================
       INPUT
    ===================================================== */

    .digi-edit-input {
        width: 100%;
        height: 54px;

        padding: 0 17px;

        box-sizing: border-box;

        border: 1px solid #e4dbd1;

        border-radius: 14px;

        outline: none;

        background:
                linear-gradient(
                        180deg,
                        #fcfaf7,
                        #f9f5ef
                );

        color: #302a24;

        font-family: inherit;

        font-size: 12px;
        font-weight: 700;

        transition: .25s ease;
    }

    .digi-edit-input:hover {
        border-color: #d5c9bc;

        background: #fffdf9;
    }

    .digi-edit-input:focus {
        border-color: #0081ff;

        background: #fff;

        transform: translateY(-1px);

        box-shadow:
                0 0 0 4px rgba(0,129,255,.075),
                0 10px 25px rgba(0,129,255,.06);
    }


    /* =====================================================
       CURRENT IMAGE
    ===================================================== */

    .digi-current-image {
        position: relative;

        display: flex;
        align-items: center;

        gap: 22px;

        min-height: 155px;

        padding: 17px;

        margin-bottom: 20px;

        box-sizing: border-box;

        border: 1px solid #e5dcd2;

        border-radius: 18px;

        background:
                linear-gradient(
                        145deg,
                        #fffdf9,
                        #f8f3ec
                );

        overflow: hidden;

        transition: .3s ease;
    }

    .digi-current-image:hover {
        border-color: #d8c8b8;

        box-shadow:
                0 12px 30px rgba(75,60,45,.07);
    }

    .digi-current-image::before {
        content: "";

        position: absolute;

        width: 180px;
        height: 180px;

        left: -90px;
        bottom: -115px;

        border-radius: 50%;

        background: rgba(0,129,255,.045);
    }


    /* image frame */

    .digi-current-image-frame {
        position: relative;
        z-index: 2;

        flex-shrink: 0;

        width: 190px;
        height: 120px;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 8px;

        box-sizing: border-box;

        border: 1px solid #e0d6cb;

        border-radius: 15px;

        background: #fffdf9;

        box-shadow:
                0 8px 20px rgba(70,55,40,.07);
    }

    .digi-current-image-frame img {
        width: 100%;
        height: 100%;

        object-fit: cover;

        border-radius: 10px;
    }


    /* image info */

    .digi-current-image-info {
        position: relative;
        z-index: 2;
    }

    .digi-current-image-info strong {
        display: block;

        color: #3a332c;

        font-size: 12px;
        font-weight: 900;
    }

    .digi-current-image-info span {
        display: block;

        margin-top: 7px;

        color: #9b9084;

        font-size: 10px;
        line-height: 1.8;
    }


    /* no image */

    .digi-no-current-image {
        display: flex;
        align-items: center;
        justify-content: center;

        min-height: 110px;

        padding: 20px;

        border: 1px dashed #d9d0c6;

        border-radius: 16px;

        background: #faf7f2;

        color: #95897c;

        font-size: 11px;
        font-weight: 700;

        text-align: center;
    }


    /* =====================================================
       NEW IMAGE UPLOAD
    ===================================================== */

    .digi-new-image-box {
        position: relative;

        display: flex;
        align-items: center;

        width: 100%;
        min-height: 105px;

        padding: 15px;

        box-sizing: border-box;

        border: 1.5px dashed #dcd2c6;

        border-radius: 17px;

        background:
                linear-gradient(
                        145deg,
                        #fffdf9,
                        #faf6f0
                );

        overflow: hidden;

        transition: .3s ease;
    }

    .digi-new-image-box::before {
        content: "";

        position: absolute;

        width: 140px;
        height: 140px;

        left: -65px;
        top: -90px;

        border-radius: 50%;

        background: rgba(0,129,255,.045);
    }

    .digi-new-image-box:hover {
        border-color: #0081ff;

        background:
                linear-gradient(
                        145deg,
                        #fffdf9,
                        #f7fbff
                );

        box-shadow:
                0 12px 30px rgba(0,129,255,.07);
    }

    .digi-new-image-inner {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;

        gap: 14px;

        width: 100%;
    }

    .digi-new-image-icon {
        flex-shrink: 0;

        width: 50px;
        height: 50px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #eaf4ff;

        border: 1px solid #d7eaff;

        color: #0081ff;

        font-size: 19px;
    }

    .digi-new-image-text {
        flex: 1;
    }

    .digi-new-image-text strong {
        display: block;

        color: #39322b;

        font-size: 11px;
        font-weight: 900;
    }

    .digi-new-image-text span {
        display: block;

        margin-top: 5px;

        color: #9a8e82;

        font-size: 9px;
    }

    .digi-new-image-box input[type="file"] {
        position: relative;
        z-index: 3;

        width: 175px;

        color: #81766b;

        font-family: inherit;

        font-size: 10px;

        cursor: pointer;
    }

    .digi-new-image-box input[type="file"]::file-selector-button {
        padding: 10px 16px;

        margin-left: 7px;

        border: none;

        border-radius: 10px;

        background:
                linear-gradient(
                        135deg,
                        #0081ff,
                        #0066ff
                );

        color: white;

        font-family: inherit;

        font-size: 10px;
        font-weight: 900;

        cursor: pointer;

        box-shadow:
                0 8px 18px rgba(0,129,255,.17);

        transition: .25s ease;
    }

    .digi-new-image-box input[type="file"]::file-selector-button:hover {
        transform: translateY(-2px);

        box-shadow:
                0 11px 23px rgba(0,129,255,.24);
    }

    .digi-image-help {
        display: block;

        margin-top: 8px;

        color: #a09589;

        font-size: 9px;
        font-weight: 600;
    }


    /* =====================================================
       DIVIDER
    ===================================================== */

    .digi-edit-divider {
        display: flex;
        align-items: center;

        gap: 15px;

        margin: 34px 0 24px;
    }

    .digi-edit-divider::before,
    .digi-edit-divider::after {
        content: "";

        height: 1px;

        flex: 1;

        background:
                linear-gradient(
                        to left,
                        transparent,
                        #e7ded4
                );
    }

    .digi-edit-divider::after {
        background:
                linear-gradient(
                        to right,
                        transparent,
                        #e7ded4
                );
    }

    .digi-edit-divider span {
        display: flex;
        align-items: center;

        gap: 8px;

        color: #73685d;

        font-size: 11px;
        font-weight: 900;

        white-space: nowrap;
    }

    .digi-edit-divider span::before {
        content: "";

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: #0081ff;

        box-shadow:
                0 0 0 5px rgba(0,129,255,.07);
    }


    /* =====================================================
       EDITOR
    ===================================================== */

    .digi-edit-editor {
        padding: 4px;

        border: 1px solid #e5dcd2;

        border-radius: 17px;

        background: #f8f4ee;

        box-shadow:
                inset 0 1px 0 rgba(255,255,255,.9);
    }

    .digi-edit-editor .ck.ck-editor {
        width: 100%;
    }

    .digi-edit-editor .ck.ck-toolbar {
        border: 0 !important;

        border-radius: 13px 13px 7px 7px !important;

        background:
                linear-gradient(
                        180deg,
                        #fffdf9,
                        #f7f2eb
                ) !important;

        padding: 8px !important;
    }

    .digi-edit-editor .ck.ck-toolbar .ck-button {
        border-radius: 8px !important;

        transition: .2s ease;
    }

    .digi-edit-editor .ck.ck-toolbar .ck-button:hover {
        background: #eaf4ff !important;

        color: #0081ff !important;
    }

    .digi-edit-editor
    .ck.ck-editor__main
    > .ck-editor__editable {

        min-height: 400px;

        padding: 22px !important;

        border: 0 !important;

        border-radius: 7px 7px 13px 13px !important;

        background: #fffdf9 !important;

        color: #302a24 !important;

        font-family: inherit !important;

        font-size: 14px;

        line-height: 2.1;

        box-shadow: none !important;
    }

    .digi-edit-editor
    .ck.ck-editor__main
    > .ck-editor__editable:focus {

        box-shadow:
                inset 0 0 0 2px rgba(0,129,255,.12) !important;
    }


    /* =====================================================
       BOTTOM
    ===================================================== */

    .digi-edit-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        margin-top: 28px;

        padding-top: 23px;

        border-top: 1px solid #ebe2d8;
    }

    .digi-edit-actions {
        display: flex;
        align-items: center;

        gap: 10px;
    }


    /* =====================================================
       SAVE
    ===================================================== */

    .digi-edit-save {
        position: relative;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 10px;

        min-width: 205px;
        height: 52px;

        padding: 0 24px;

        overflow: hidden;

        border: none;

        border-radius: 14px;

        background:
                linear-gradient(
                        135deg,
                        #0081ff,
                        #0066ff
                );

        color: white;

        font-family: inherit;

        font-size: 12px;
        font-weight: 900;

        cursor: pointer;

        box-shadow:
                0 13px 28px rgba(0,129,255,.22);

        transition: .3s ease;
    }

    .digi-edit-save::before {
        content: "";

        position: absolute;

        top: 0;
        left: -100%;

        width: 70%;
        height: 100%;

        background:
                linear-gradient(
                        90deg,
                        transparent,
                        rgba(255,255,255,.22),
                        transparent
                );

        transform: skewX(-20deg);

        transition: .6s ease;
    }

    .digi-edit-save:hover::before {
        left: 130%;
    }

    .digi-edit-save:hover {
        transform: translateY(-3px);

        box-shadow:
                0 18px 35px rgba(0,129,255,.30);
    }

    .digi-edit-save:active {
        transform: translateY(-1px);
    }

    .digi-edit-save-icon {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 27px;
        height: 27px;

        border-radius: 8px;

        background: rgba(255,255,255,.17);
    }


    /* =====================================================
       CANCEL
    ===================================================== */

    .digi-edit-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        height: 52px;

        padding: 0 21px;

        border: 1px solid #e1d7cd;

        border-radius: 14px;

        background: #faf7f2;

        color: #756b61 !important;

        text-decoration: none !important;

        font-family: inherit;

        font-size: 11px;
        font-weight: 900;

        transition: .25s ease;
    }

    .digi-edit-cancel:hover {
        border-color: #cfc3b6;

        background: #f3eee7;

        color: #413a33 !important;

        transform: translateY(-2px);
    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 900px) {

        .digi-edit-article-page {
            padding: 25px 15px 50px;
        }

        .digi-edit-heading h1 {
            font-size: 25px;
        }

        .digi-edit-card {
            border-radius: 19px;
        }

        .digi-edit-card-header {
            padding: 21px;
        }

        .digi-edit-body {
            padding: 21px;
        }

        .digi-edit-grid {
            grid-template-columns: 1fr;
        }

        .digi-edit-field.full {
            grid-column: auto;
        }

        .digi-current-image {
            flex-direction: column;
            align-items: stretch;
        }

        .digi-current-image-frame {
            width: 100%;
            height: 210px;
        }

        .digi-new-image-inner {
            flex-wrap: wrap;
        }

        .digi-new-image-box input[type="file"] {
            width: 100%;
            max-width: 100%;
            margin-top: 5px;
        }

        .digi-edit-bottom {
            align-items: stretch;
            flex-direction: column;
        }

        .digi-edit-actions {
            width: 100%;
            flex-direction: column;
        }

        .digi-edit-save,
        .digi-edit-cancel {
            width: 100%;
        }

    }


    /* =====================================================
       SMALL MOBILE
    ===================================================== */

    @media (max-width: 500px) {

        .digi-edit-heading h1 {
            font-size: 22px;
        }

        .digi-edit-heading p {
            font-size: 10px;
        }

        .digi-edit-card-header {
            padding: 18px;
        }

        .digi-edit-body {
            padding: 16px;
        }

        .digi-edit-card-icon {
            width: 40px;
            height: 40px;
        }

        .digi-current-image-frame {
            height: 180px;
        }

        .digi-edit-editor
        .ck.ck-editor__main
        > .ck-editor__editable {

            min-height: 300px;

            padding: 16px !important;
        }

    }

</style>


<div class="content-wrapper digi-edit-article-page">


    <!-- =====================================================
         TOP
    ===================================================== -->

    <div class="digi-edit-top">

        <div class="digi-edit-heading">

            <h1>
                ویرایش مقاله
            </h1>

            <p>
                اطلاعات و محتوای مقاله را ویرایش و به‌روزرسانی کنید
            </p>

        </div>

    </div>


    <!-- =====================================================
         MAIN CARD
    ===================================================== -->

    <div class="digi-edit-card">


        <!-- =================================================
             HEADER
        ================================================= -->

        <div class="digi-edit-card-header">

            <div class="digi-edit-card-title">

                <div class="digi-edit-card-icon">
                    ✎
                </div>

                <div>

                    <strong>
                        ویرایش اطلاعات مقاله
                    </strong>

                    <span>
                        اطلاعات مقاله را بررسی و تغییرات خود را ذخیره کنید
                    </span>

                </div>

            </div>

        </div>


        <!-- =================================================
             BODY
        ================================================= -->

        <div class="digi-edit-body">


            <form
                    id="editor-form"
                    action=""
                    method="post"
                    enctype="multipart/form-data"
            >


                <!-- =================================================
                     INPUTS
                ================================================= -->

                <div class="digi-edit-grid">


                    <!-- عنوان -->

                    <div class="digi-edit-field full">

                        <label>

                            <span class="digi-label-dot"></span>

                            عنوان مقاله

                        </label>

                        <input
                                type="text"
                                name="title"
                                class="digi-edit-input"
                                value="<?php
                                echo htmlspecialchars(
                                    $title,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                                required
                        >

                    </div>


                    <!-- نویسنده -->

                    <div class="digi-edit-field">

                        <label>

                            <span class="digi-label-dot"></span>

                            نویسنده

                        </label>

                        <input
                                type="text"
                                name="author"
                                class="digi-edit-input"
                                value="<?php
                                echo htmlspecialchars(
                                    $author,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                                required
                        >

                    </div>


                    <!-- دسته بندی -->

                    <div class="digi-edit-field">

                        <label>

                            <span class="digi-label-dot"></span>

                            دسته بندی

                        </label>

                        <input
                                type="text"
                                name="category"
                                class="digi-edit-input"
                                value="<?php
                                echo htmlspecialchars(
                                    $category,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                                required
                        >

                    </div>


                    <!-- =================================================
                         CURRENT IMAGE
                    ================================================= -->

                    <div class="digi-edit-field full">

                        <label class="digi-image-label">

                            <span class="digi-label-dot"></span>

                            عکس فعلی مقاله

                        </label>


                        <?php

                        if (!empty($oldImage)) {

                            ?>

                            <div class="digi-current-image">


                                <div class="digi-current-image-frame">

                                    <img
                                            src="../uploads/<?php
                                            echo rawurlencode(
                                                basename($oldImage)
                                            );
                                            ?>"
                                            alt="تصویر فعلی"
                                    >

                                </div>


                                <div class="digi-current-image-info">

                                    <strong>
                                        تصویر فعلی مقاله
                                    </strong>

                                    <span>
                                        این تصویر در حال حاضر به عنوان تصویر اصلی مقاله استفاده می‌شود.
                                    </span>

                                </div>


                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="digi-no-current-image">

                                برای این مقاله تصویر اصلی ثبت نشده است.

                            </div>

                            <?php

                        }

                        ?>

                    </div>


                    <!-- =================================================
                         NEW IMAGE
                    ================================================= -->

                    <div class="digi-edit-field full">

                        <label>

                            <span class="digi-label-dot"></span>

                            انتخاب عکس جدید

                        </label>


                        <div class="digi-new-image-box">

                            <div class="digi-new-image-inner">


                                <input
                                        type="file"
                                        name="image"
                                        accept="image/jpeg,image/png,image/webp"
                                >


                                <div class="digi-new-image-text">

                                    <strong>
                                        جایگزینی تصویر مقاله
                                    </strong>

                                    <span>
                                        اگر تصویر جدید انتخاب نکنید، تصویر فعلی حفظ خواهد شد.
                                    </span>

                                </div>





                            </div>

                        </div>


                        <small class="digi-image-help">

                            فرمت‌های مجاز: JPG ، PNG ، WEBP

                        </small>

                    </div>


                </div>


                <!-- =================================================
                     DIVIDER
                ================================================= -->

                <div class="digi-edit-divider">

                    <span>
                        محتوای مقاله
                    </span>

                </div>


                <!-- =================================================
                     EDITOR
                ================================================= -->

                <div class="digi-edit-editor">

                    <textarea
                            name="content"
                            id="content"
                    ><?php

                        echo htmlspecialchars(
                            $content,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        ?></textarea>

                </div>


                <!-- =================================================
                     BOTTOM
                ================================================= -->

                <div class="digi-edit-bottom">


                    <div class="digi-required-note">

                        <span
                                style="
                                color:#0081ff;
                                font-weight:900;
                            "
                        >
                            ●
                        </span>

                        تغییرات خود را قبل از خروج ذخیره کنید.

                    </div>


                    <div class="digi-edit-actions">


                        <button
                                type="submit"
                                name="save"
                                value="1"
                                class="digi-edit-save"
                        >

                            <span class="digi-edit-save-icon">
                                ✓
                            </span>

                            ذخیره تغییرات

                        </button>


                        <a
                                href="jadval_maghale.php"
                                class="digi-edit-cancel"
                        >

                            انصراف

                        </a>


                    </div>


                </div>


            </form>

        </div>

    </div>

</div>


<!-- =====================================================
     CKEditor
===================================================== -->

<script src="../ckeditor5-build-classic/ckeditor.js"></script>


<script>

    let editor;


    // =====================================================
    // آپلود عکس CKEditor
    // =====================================================

    class MyUploadAdapter {


        constructor(loader) {

            this.loader = loader;

            this.xhr = null;

        }


        upload() {

            return this.loader.file.then(file => {

                return new Promise((resolve, reject) => {


                    const data = new FormData();


                    data.append(
                        'upload',
                        file
                    );


                    this.xhr =
                        new XMLHttpRequest();


                    this.xhr.open(
                        'POST',
                        'upload_image_magh.php',
                        true
                    );


                    this.xhr.responseType =
                        'json';


                    // =================================================
                    // پاسخ سرور
                    // =================================================

                    this.xhr.onload = () => {


                        if (
                            this.xhr.status >= 200 &&
                            this.xhr.status < 300
                        ) {


                            if (
                                this.xhr.response &&
                                this.xhr.response.url
                            ) {


                                resolve({

                                    default:
                                    this.xhr.response.url

                                });


                            } else {


                                reject(
                                    'آدرس تصویر دریافت نشد.'
                                );

                            }


                        } else {


                            reject(
                                'آپلود تصویر انجام نشد.'
                            );

                        }

                    };


                    // =================================================
                    // خطای ارتباط
                    // =================================================

                    this.xhr.onerror = () => {

                        reject(
                            'ارتباط با سرور برقرار نشد.'
                        );

                    };


                    // =================================================
                    // ارسال
                    // =================================================

                    this.xhr.send(data);


                });

            });

        }


        abort() {

            if (this.xhr) {

                this.xhr.abort();

            }

        }

    }


    // =====================================================
    // پلاگین آپلود
    // =====================================================

    function MyCustomUploadAdapterPlugin(editor) {

        editor.plugins
            .get('FileRepository')
            .createUploadAdapter = loader => {

            return new MyUploadAdapter(loader);

        };

    }


    // =====================================================
    // ساخت CKEditor
    // =====================================================

    ClassicEditor
        .create(

            document.querySelector('#content'),

            {

                language: 'fa',

                extraPlugins: [

                    MyCustomUploadAdapterPlugin

                ],

                toolbar: [

                    'heading',

                    '|',

                    'bold',
                    'italic',
                    'underline',
                    'link',

                    '|',

                    'bulletedList',
                    'numberedList',

                    '|',

                    'alignment',
                    'outdent',
                    'indent',

                    '|',

                    'insertTable',
                    'blockQuote',
                    'imageUpload',

                    '|',

                    'undo',
                    'redo'

                ]

            }

        )


        .then(newEditor => {


            editor = newEditor;


            // =================================================
            // قبل از ارسال فرم
            // =================================================

            document
                .querySelector('#editor-form')
                .addEventListener(
                    'submit',
                    function () {


                        document.querySelector(
                            '#content'
                        ).value =
                            editor.getData();


                    }
                );


        })


        .catch(error => {

            console.error(error);

        });

</script>


<?php

include "footer.php";

?>
