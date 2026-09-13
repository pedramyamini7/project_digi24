<?php
session_start();

require "../config/config.php";


// =====================================================
// ذخیره مقاله
// =====================================================

if (isset($_POST['save'])) {

    // -----------------------------
    // دریافت و امن‌سازی اطلاعات
    // -----------------------------

    $title = mysqli_real_escape_string(
        $conn,
        $_POST['title'] ?? ''
    );

    $author = mysqli_real_escape_string(
        $conn,
        $_POST['author'] ?? ''
    );

    $category = mysqli_real_escape_string(
        $conn,
        $_POST['category'] ?? ''
    );

    $content = mysqli_real_escape_string(
        $conn,
        $_POST['content'] ?? ''
    );


    // =================================================
    // بررسی عکس اصلی
    // =================================================

    $nameimage1 = '';

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === 0
    ) {

        $originalName = $_FILES['image']['name'];
        $tmpimage1 = $_FILES['image']['tmp_name'];

        // پسوند فایل
        $extension = strtolower(
            pathinfo($originalName, PATHINFO_EXTENSION)
        );

        // پسوندهای مجاز
        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        if (!in_array($extension, $allowedExtensions)) {

            die("فرمت عکس مجاز نیست.");

        }


        // نام یکتا برای عکس
        $nameimage1 =
            uniqid('article_', true)
            . '.'
            . $extension;


        // مسیر ذخیره عکس
        $uploadDir = "../uploads/";


        // اگر پوشه وجود نداشت
        if (!is_dir($uploadDir)) {

            mkdir(
                $uploadDir,
                0777,
                true
            );

        }


        // انتقال عکس
        if (
            !move_uploaded_file(
                $tmpimage1,
                $uploadDir . $nameimage1
            )
        ) {

            die("آپلود عکس اصلی انجام نشد.");

        }

    }


    // =================================================
    // ثبت مقاله در دیتابیس
    // =================================================

    $sql = "INSERT INTO maghale_dakhel
            (
                title,
                author,
                category,
                image,
                content
            )
            VALUES
            (
                '$title',
                '$author',
                '$category',
                '$nameimage1',
                '$content'
            )";


    // اجرای Query
    $result = mysqli_query(
        $conn,
        $sql
    );


    // =================================================
    // نتیجه
    // =================================================

    if ($result) {

        // انتقال به صفحه مقالات
        header("Location: jadval_maghale.php");
        exit;

    } else {

        echo "خطا در ذخیره مقاله: "
            . mysqli_error($conn);

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

    .digi-add-article-page {
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

    .digi-add-top {
        max-width: 1180px;
        margin: 0 auto 25px;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .digi-add-heading {
        position: relative;
        padding-right: 18px;
    }

    .digi-add-heading::before {
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

    .digi-add-heading h1 {
        margin: 0;

        color: #28231f;

        font-size: 31px;
        font-weight: 950;

        letter-spacing: -1.2px;
    }

    .digi-add-heading p {
        margin: 9px 0 0;

        color: #94897d;

        font-size: 12px;
        font-weight: 600;
    }


    /* =====================================================
       MAIN CARD
    ===================================================== */

    .digi-add-card {
        max-width: 1180px;

        margin: auto;

        padding: 0;

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

    .digi-add-card-header {
        position: relative;

        padding: 25px 30px;

        border-bottom: 1px solid #ebe3da;

        background:
                linear-gradient(
                        135deg,
                        #fffdf9,
                        #faf6f0
                );

        overflow: hidden;
    }

    .digi-add-card-header::after {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        left: -45px;
        top: -75px;

        border-radius: 50%;

        background: rgba(0,129,255,.045);
    }

    .digi-card-title {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;

        gap: 13px;
    }

    .digi-card-icon {
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

        font-size: 19px;

        box-shadow:
                0 10px 22px rgba(0,129,255,.22);
    }

    .digi-card-title strong {
        display: block;

        color: #332d27;

        font-size: 14px;
        font-weight: 900;
    }

    .digi-card-title span {
        display: block;

        margin-top: 4px;

        color: #9b9084;

        font-size: 10px;
    }


    /* =====================================================
       FORM BODY
    ===================================================== */

    .digi-add-body {
        padding: 32px;
    }


    /* =====================================================
       INPUT GRID
    ===================================================== */

    .digi-input-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 20px;
    }


    /* =====================================================
       FIELD
    ===================================================== */

    .digi-modern-field {
        position: relative;
    }

    .digi-modern-field.full {
        grid-column: 1 / -1;
    }

    .digi-modern-field label {
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

    .digi-modern-input {
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

        transition:
                border .25s ease,
                box-shadow .25s ease,
                background .25s ease,
                transform .25s ease;
    }

    .digi-modern-input:hover {
        border-color: #d5c9bc;

        background: #fffdf9;
    }

    .digi-modern-input:focus {
        border-color: #0081ff;

        background: #fff;

        transform: translateY(-1px);

        box-shadow:
                0 0 0 4px rgba(0,129,255,.075),
                0 10px 25px rgba(0,129,255,.06);
    }

    .digi-modern-input::placeholder {
        color: #b0a69b;

        font-weight: 500;
    }


    /* =====================================================
       IMAGE UPLOAD
    ===================================================== */

    .digi-upload-area {
        position: relative;

        width: 100%;
        min-height: 135px;

        padding: 18px;

        box-sizing: border-box;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1.5px dashed #dcd2c6;

        border-radius: 18px;

        background:
                linear-gradient(
                        145deg,
                        #fffdf9,
                        #faf6f0
                );

        overflow: hidden;

        transition: .3s ease;
    }

    .digi-upload-area::before {
        content: "";

        position: absolute;

        width: 180px;
        height: 180px;

        top: -105px;
        left: -70px;

        border-radius: 50%;

        background: rgba(0,129,255,.045);
    }

    .digi-upload-area::after {
        content: "";

        position: absolute;

        width: 100px;
        height: 100px;

        right: -45px;
        bottom: -55px;

        border-radius: 50%;

        background: rgba(224,143,48,.06);
    }

    .digi-upload-area:hover {
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


    /* =====================================================
       UPLOAD CONTENT
    ===================================================== */

    .digi-upload-content {
        position: relative;

        z-index: 2;

        display: flex;
        align-items: center;

        gap: 14px;

        width: 100%;
    }


    /* =====================================================
       UPLOAD ICON
    ===================================================== */

    .digi-upload-icon {
        flex-shrink: 0;

        width: 55px;
        height: 55px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 16px;

        background:
                linear-gradient(
                        145deg,
                        #edf6ff,
                        #e4f1ff
                );

        border: 1px solid #d6eaff;

        color: #0081ff;

        font-size: 22px;

        box-shadow:
                0 8px 20px rgba(0,129,255,.08);
    }


    /* =====================================================
       UPLOAD TEXT
    ===================================================== */

    .digi-upload-text {
        flex: 1;

        min-width: 0;
    }

    .digi-upload-text strong {
        display: block;

        color: #342e28;

        font-size: 12px;
        font-weight: 900;
    }

    .digi-upload-text span {
        display: block;

        margin-top: 6px;

        color: #9b9084;

        font-size: 10px;
        font-weight: 600;
    }


    /* =====================================================
       FILE INPUT
    ===================================================== */

    .digi-upload-area input[type="file"] {
        position: relative;

        z-index: 3;

        width: 175px;

        color: #81766b;

        font-family: inherit;

        font-size: 10px;
        font-weight: 700;

        cursor: pointer;
    }

    .digi-upload-area input[type="file"]::file-selector-button {
        padding: 11px 17px;

        margin-left: 7px;

        border: none;

        border-radius: 11px;

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
                0 8px 18px rgba(0,129,255,.18);

        transition: .25s ease;
    }

    .digi-upload-area input[type="file"]::file-selector-button:hover {
        transform: translateY(-2px);

        box-shadow:
                0 11px 23px rgba(0,129,255,.25);
    }


    /* =====================================================
       DIVIDER
    ===================================================== */

    .digi-section-divider {
        display: flex;
        align-items: center;

        gap: 15px;

        margin: 34px 0 24px;
    }

    .digi-section-divider::before,
    .digi-section-divider::after {
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

    .digi-section-divider::after {
        background:
                linear-gradient(
                        to right,
                        transparent,
                        #e7ded4
                );
    }

    .digi-section-divider span {
        display: inline-flex;
        align-items: center;

        gap: 8px;

        color: #73685d;

        font-size: 11px;
        font-weight: 900;

        white-space: nowrap;
    }

    .digi-section-divider span::before {
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

    .digi-editor-card {
        padding: 4px;

        border: 1px solid #e5dcd2;

        border-radius: 17px;

        background: #f8f4ee;

        box-shadow:
                inset 0 1px 0 rgba(255,255,255,.9);
    }

    .digi-editor-card .ck.ck-editor {
        width: 100%;
    }

    .digi-editor-card .ck.ck-toolbar {
        border: 0 !important;

        border-radius: 13px 13px 7px 7px !important;

        background:
                linear-gradient(
                        180deg,
                        #fffdf9,
                        #f7f2eb
                ) !important;

        padding: 8px !important;

        box-shadow:
                0 3px 10px rgba(70,55,40,.035);
    }

    .digi-editor-card .ck.ck-toolbar .ck-button {
        border-radius: 8px !important;

        transition: .2s ease;
    }

    .digi-editor-card .ck.ck-toolbar .ck-button:hover {
        background: #eaf4ff !important;

        color: #0081ff !important;
    }

    .digi-editor-card
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

    .digi-editor-card
    .ck.ck-editor__main
    > .ck-editor__editable:focus {

        box-shadow:
                inset 0 0 0 2px rgba(0,129,255,.12) !important;
    }


    /* =====================================================
       BOTTOM
    ===================================================== */

    .digi-form-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        margin-top: 28px;

        padding-top: 23px;

        border-top: 1px solid #ebe2d8;
    }

    .digi-required-note {
        color: #9a8f83;

        font-size: 10px;
        font-weight: 600;
    }

    .digi-required-note b {
        color: #0081ff;
    }


    /* =====================================================
       SAVE BUTTON
    ===================================================== */

    .digi-save-button {
        position: relative;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 11px;

        min-width: 210px;
        height: 52px;

        padding: 0 25px;

        overflow: hidden;

        border: none;

        border-radius: 14px;

        background:
                linear-gradient(
                        135deg,
                        #0081ff 0%,
                        #0066ff 100%
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

    .digi-save-button::before {
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

    .digi-save-button:hover::before {
        left: 130%;
    }

    .digi-save-button:hover {
        transform: translateY(-3px);

        box-shadow:
                0 18px 35px rgba(0,129,255,.30);
    }

    .digi-save-button:active {
        transform: translateY(-1px);
    }

    .digi-save-icon {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 27px;
        height: 27px;

        border-radius: 8px;

        background: rgba(255,255,255,.17);

        font-size: 14px;
    }

    .digi-save-button {
        direction: rtl;
    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 900px) {

        .digi-add-article-page {
            padding: 25px 15px 50px;
        }

        .digi-add-heading h1 {
            font-size: 25px;
        }

        .digi-add-card {
            border-radius: 19px;
        }

        .digi-add-card-header {
            padding: 21px;
        }

        .digi-add-body {
            padding: 21px;
        }

        .digi-input-grid {
            grid-template-columns: 1fr;
        }

        .digi-modern-field.full {
            grid-column: auto;
        }

        .digi-upload-content {
            flex-wrap: wrap;
        }

        .digi-upload-area input[type="file"] {
            width: 100%;
            max-width: 100%;
            margin-top: 5px;
        }

        .digi-form-bottom {
            align-items: stretch;

            flex-direction: column;
        }

        .digi-save-button {
            width: 100%;
        }

    }


    /* =====================================================
       SMALL MOBILE
    ===================================================== */

    @media (max-width: 500px) {

        .digi-add-heading h1 {
            font-size: 22px;
        }

        .digi-add-heading p {
            font-size: 10px;
        }

        .digi-add-card-header {
            padding: 18px;
        }

        .digi-add-body {
            padding: 16px;
        }

        .digi-card-icon {
            width: 40px;
            height: 40px;
        }

        .digi-upload-area {
            min-height: 150px;
        }

        .digi-upload-content {
            align-items: flex-start;
        }

        .digi-upload-icon {
            width: 48px;
            height: 48px;
        }

        .digi-editor-card
        .ck.ck-editor__main
        > .ck-editor__editable {

            min-height: 300px;

            padding: 16px !important;
        }

    }

</style>


<div class="content-wrapper digi-add-article-page">


    <!-- =====================================================
         TOP
    ===================================================== -->

    <div class="digi-add-top">

        <div class="digi-add-heading">

            <h1>
                اضافه کردن مقاله
            </h1>

            <p>
                مقاله جدید خود را با جزئیات کامل ایجاد کنید
            </p>

        </div>

    </div>


    <!-- =====================================================
         MAIN CARD
    ===================================================== -->

    <div class="digi-add-card">


        <!-- =================================================
             CARD HEADER
        ================================================= -->

        <div class="digi-add-card-header">

            <div class="digi-card-title">

                <div class="digi-card-icon">
                    ✦
                </div>

                <div>

                    <strong>
                        اطلاعات مقاله
                    </strong>

                    <span>
                        اطلاعات اصلی مقاله را وارد کنید
                    </span>

                </div>

            </div>

        </div>


        <!-- =================================================
             BODY
        ================================================= -->

        <div class="digi-add-body">


            <form
                    id="editor-form"
                    action=""
                    method="post"
                    enctype="multipart/form-data"
            >


                <!-- =================================================
                     INPUTS
                ================================================= -->

                <div class="digi-input-grid">


                    <!-- عنوان مقاله -->

                    <div class="digi-modern-field full">

                        <label>

                            <span class="digi-label-dot"></span>

                            عنوان مقاله

                        </label>

                        <input
                                type="text"
                                name="title"
                                class="digi-modern-input"
                                required
                                placeholder="عنوان مقاله را وارد کنید..."
                        >

                    </div>


                    <!-- نویسنده -->

                    <div class="digi-modern-field">

                        <label>

                            <span class="digi-label-dot"></span>

                            نویسنده

                        </label>

                        <input
                                type="text"
                                name="author"
                                class="digi-modern-input"
                                required
                                placeholder="نام نویسنده..."
                        >

                    </div>


                    <!-- دسته بندی -->

                    <div class="digi-modern-field">

                        <label>

                            <span class="digi-label-dot"></span>

                            دسته بندی

                        </label>

                        <input
                                type="text"
                                name="category"
                                class="digi-modern-input"
                                required
                                placeholder="دسته بندی مقاله..."
                        >

                    </div>


                    <!-- عکس اصلی -->

                    <div class="digi-modern-field full">

                        <label>

                            <span class="digi-label-dot"></span>

                            عکس کلی مقاله

                        </label>


                        <div class="digi-upload-area">

                            <div class="digi-upload-content">



                                <input
                                        type="file"
                                        name="image"
                                        accept="image/jpeg,image/png,image/webp"
                                        required
                                >

                                <div class="digi-upload-text">

                                    <strong>
                                        تصویر اصلی مقاله
                                    </strong>

                                    <span>
                                        تصویر شاخص مقاله را انتخاب کنید · JPG / PNG / WEBP
                                    </span>

                                </div>





                            </div>

                        </div>

                    </div>


                </div>


                <!-- =================================================
                     DIVIDER
                ================================================= -->

                <div class="digi-section-divider">

                    <span>
                        محتوای مقاله
                    </span>

                </div>


                <!-- =================================================
                     CKEDITOR
                ================================================= -->

                <div class="digi-editor-card">

                    <textarea
                            name="content"
                            id="content"
                    ></textarea>

                </div>


                <!-- =================================================
                     BOTTOM
                ================================================= -->

                <div class="digi-form-bottom">


                    <div class="digi-required-note">

                        <b>*</b>

                        تکمیل تمام فیلدهای الزامی ضروری است.

                    </div>


                    <button
                            type="submit"
                            name="save"
                            value="1"
                            class="digi-save-button"
                    >

                        <span class="digi-save-icon">
                            ✓
                        </span>

                        ذخیره و ایجاد مقاله

                    </button>


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


                    this.xhr = new XMLHttpRequest();


                    this.xhr.open(
                        'POST',
                        'upload_image_magh.php',
                        true
                    );


                    this.xhr.responseType = 'json';


                    // -----------------------------------------
                    // پاسخ سرور
                    // -----------------------------------------

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


                    // -----------------------------------------
                    // خطای ارتباط
                    // -----------------------------------------

                    this.xhr.onerror = () => {

                        reject(
                            'ارتباط با سرور برقرار نشد.'
                        );

                    };


                    // -----------------------------------------
                    // ارسال
                    // -----------------------------------------

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


            // -----------------------------------------
            // قبل از ارسال فرم
            // -----------------------------------------

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
