<?php

require "../config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $price = mysqli_real_escape_string($conn, $_POST['price'] ?? '');
    $pricef = mysqli_real_escape_string($conn, $_POST['pricef'] ?? '');
    $content = mysqli_real_escape_string($conn, $_POST['content'] ?? '');

    function uploadImage($inputName)
    {
        if (
            !isset($_FILES[$inputName]) ||
            $_FILES[$inputName]['error'] != UPLOAD_ERR_OK ||
            empty($_FILES[$inputName]['name'])
        ) {
            return '';
        }

        $fileName = basename($_FILES[$inputName]['name']);
        $tmpName = $_FILES[$inputName]['tmp_name'];

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'gif',
            'webp'
        ];

        if (!in_array($extension, $allowedExtensions)) {
            return '';
        }

        $newFileName = uniqid('img_', true) . '.' . $extension;

        $destination = "../up/" . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            return $newFileName;
        }

        return '';
    }


    $image1 = uploadImage('image1');
    $image2 = uploadImage('image2');
    $image3 = uploadImage('image3');
    $image4 = uploadImage('image4');
    $image5 = uploadImage('image5');


    if ($image1 == '') {
        die("آپلود تصویر اول با مشکل مواجه شد.");
    }


    $sql = "INSERT INTO projects_var
            (
                project_id,
                title,
                price,
                price_fake,
                image1,
                image2,
                image3,
                image4,
                image5,
                content
            )
            VALUES
            (
                0,
                '$name',
                '$price',
                '$pricef',
                '$image1',
                '$image2',
                '$image3',
                '$image4',
                '$image5',
                '$content'
            )";


    if (!mysqli_query($conn, $sql)) {
        die("خطا در ذخیره محصول: " . mysqli_error($conn));
    }


    $project_id = mysqli_insert_id($conn);


    $sql_project = "
        UPDATE projects_var
        SET project_id = '$project_id'
        WHERE id = '$project_id'
    ";

    if (!mysqli_query($conn, $sql_project)) {
        die("خطا در تنظیم project_id: " . mysqli_error($conn));
    }


    $specNumber = 1;

    while (isset($_POST['spec_name_' . $specNumber])) {

        $spec_title = trim($_POST['spec_name_' . $specNumber] ?? '');
        $spec_value = trim($_POST['spec_value_' . $specNumber] ?? '');

        if ($spec_title != '' && $spec_value != '') {

            $spec_title = mysqli_real_escape_string($conn, $spec_title);
            $spec_value = mysqli_real_escape_string($conn, $spec_value);

            $sql_spec = "
                INSERT INTO projects_var
                (
                    project_id,
                    spec_title,
                    spec_value
                )
                VALUES
                (
                    '$project_id',
                    '$spec_title',
                    '$spec_value'
                )
            ";

            if (!mysqli_query($conn, $sql_spec)) {
                die("خطا در ذخیره مشخصات: " . mysqli_error($conn));
            }
        }

        $specNumber++;
    }


    $questionNumber = 1;

    while (isset($_POST['question_' . $questionNumber])) {

        $question_title = trim(
            $_POST['question_' . $questionNumber] ?? ''
        );

        $question_answer = trim(
            $_POST['answer_' . $questionNumber] ?? ''
        );

        if ($question_title != '' && $question_answer != '') {

            $question_title = mysqli_real_escape_string(
                $conn,
                $question_title
            );

            $question_answer = mysqli_real_escape_string(
                $conn,
                $question_answer
            );

            $sql_question = "
                INSERT INTO projects_var
                (
                    project_id,
                    question_title,
                    question_answer
                )
                VALUES
                (
                    '$project_id',
                    '$question_title',
                    '$question_answer'
                )
            ";

            if (!mysqli_query($conn, $sql_question)) {
                die("خطا در ذخیره پرسش و پاسخ: " . mysqli_error($conn));
            }
        }

        $questionNumber++;
    }


    for ($i = 1; $i <= 7; $i++) {

        if (isset($_POST['warranty_' . $i])) {

            $warranty = mysqli_real_escape_string(
                $conn,
                $_POST['warranty_' . $i]
            );

            $sql_warranty = "
                INSERT INTO projects_var
                (
                    project_id,
                    warranty
                )
                VALUES
                (
                    '$project_id',
                    '$warranty'
                )
            ";

            if (!mysqli_query($conn, $sql_warranty)) {
                die("خطا در ذخیره گارانتی: " . mysqli_error($conn));
            }
        }
    }


    for ($i = 1; $i <= 5; $i++) {

        if (isset($_POST['shipping_' . $i])) {

            $shipping_time = mysqli_real_escape_string(
                $conn,
                $_POST['shipping_' . $i]
            );

            $sql_shipping = "
                INSERT INTO projects_var
                (
                    project_id,
                    shipping_time
                )
                VALUES
                (
                    '$project_id',
                    '$shipping_time'
                )
            ";

            if (!mysqli_query($conn, $sql_shipping)) {
                die("خطا در ذخیره زمان ارسال: " . mysqli_error($conn));
            }
        }
    }


    header("Location: jadval_camera_var.php");
    exit;
}

?>
session_start();

<?php
include "haeder.php";
?>

<style>

    /* =========================================================
       DIGI24 — MODERN PRODUCT EDITOR
    ========================================================= */

    .digi-add-page {
        direction: rtl;
        min-height: 100vh;
        padding: 34px 38px 100px;
        background:
                radial-gradient(circle at 85% 5%, rgba(0,129,255,.10), transparent 25%),
                radial-gradient(circle at 10% 35%, rgba(188,154,92,.10), transparent 28%),
                #f5f0e7;
        font-family: inherit;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .digi-add-header {
        max-width: 1420px;
        margin: 0 auto 26px;
    }

    .digi-add-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        padding: 25px 28px;
        border: 1px solid rgba(32,43,55,.08);
        border-radius: 22px;
        background: rgba(255,253,249,.88);
        box-shadow: 0 15px 45px rgba(66,52,30,.07);
    }

    .digi-add-heading-main {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .digi-add-heading-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 17px;
        color: #fff;
        font-size: 27px;
        font-weight: 300;
        background: linear-gradient(145deg,#0081ff,#0063e8);
        box-shadow: 0 12px 25px rgba(0,129,255,.25);
    }

    .digi-add-heading h1 {
        margin: 0;
        color: #202832;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .digi-add-heading p {
        margin: 6px 0 0;
        color: #8b8d91;
        font-size: 13px;
    }


    /* =========================================================
       FORM
    ========================================================= */

    #editor-form {
        max-width: 1420px;
        margin: auto;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .digi-form-card {
        position: relative;
        margin-bottom: 18px;
        overflow: hidden;
        border: 1px solid rgba(38,44,50,.075);
        border-radius: 21px;
        background: #fffdf9;
        box-shadow: 0 12px 35px rgba(65,52,33,.065);
        transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
    }

    .digi-form-card:hover {
        transform: translateY(-2px);
        border-color: rgba(0,129,255,.15);
        box-shadow: 0 18px 45px rgba(65,52,33,.09);
    }


    /* =========================================================
       CARD HEADER
    ========================================================= */

    .digi-form-card-head {
        position: relative;
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 19px 23px;
        border-bottom: 1px solid #eee7dc;
        background:
                linear-gradient(
                        90deg,
                        rgba(0,129,255,.025),
                        rgba(255,255,255,0)
                );
    }

    .digi-form-card-head:after {
        content: "";
        position: absolute;
        right: 0;
        top: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(
                180deg,
                #0081ff,
                #65b7ff
        );
    }

    .digi-form-card-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        color: #fff;
        font-size: 17px;
        background: linear-gradient(
                145deg,
                #0081ff,
                #0068ef
        );
        box-shadow: 0 8px 18px rgba(0,129,255,.18);
    }

    .digi-form-card-head h2 {
        margin: 0;
        color: #252c34;
        font-size: 17px;
        font-weight: 800;
    }

    .digi-form-card-head span {
        display: block;
        margin-top: 4px;
        color: #98958f;
        font-size: 12px;
    }


    /* =========================================================
       CARD BODY
    ========================================================= */

    .digi-form-card-body {
        padding: 24px;
    }


    /* =========================================================
       INPUTS
    ========================================================= */

    .digi-input-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1fr;
        gap: 17px;
    }

    .digi-field label,
    .digi-image-field label {
        display: block;
        margin-bottom: 8px;
        color: #3a4148;
        font-size: 13px;
        font-weight: 700;
    }

    .digi-field .form-control,
    .digi-image-field .form-control,
    .spec-item .form-control,
    .question-item .form-control {
        min-height: 47px;
        border: 1px solid #e2dbd0;
        border-radius: 12px;
        background: #faf7f1;
        color: #293039;
        font-size: 14px;
        box-shadow: none;
        transition: .2s ease;
    }

    .digi-field .form-control:focus,
    .digi-image-field .form-control:focus,
    .spec-item .form-control:focus,
    .question-item .form-control:focus {
        border-color: #0081ff;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0,129,255,.08);
    }

    .digi-field .form-control::placeholder,
    .spec-item .form-control::placeholder,
    .question-item .form-control::placeholder {
        color: #aaa59d;
        font-size: 13px;
    }


    /* =========================================================
       IMAGE GRID
    ========================================================= */

    .digi-image-grid {
        display: grid;
        grid-template-columns: repeat(5,1fr);
        gap: 13px;
    }

    .digi-image-field {
        padding: 13px;
        border: 1px dashed #d8d0c4;
        border-radius: 15px;
        background: #faf7f1;
        transition: .2s ease;
    }

    .digi-image-field:hover {
        border-color: #0081ff;
        background: #f8fbff;
    }

    .digi-image-field .form-control {
        min-height: auto;
        padding: 8px;
        border: 1px solid #e1d9cd;
        background: #fffdf9;
        font-size: 12px;
    }

    .digi-required {
        display: inline-block;
        margin-right: 5px;
        padding: 3px 7px;
        border-radius: 20px;
        color: #fff;
        background: #e34d4d;
        font-size: 9px;
        font-weight: 700;
    }


    /* =========================================================
       SPECS
    ========================================================= */

    .spec-item {
        position: relative;
        margin-bottom: 12px;
        padding: 15px;
        border: 1px solid #e9e1d5;
        border-radius: 15px;
        background: #faf7f1;
    }

    .spec-item h4,
    .question-item h4 {
        margin: 0 0 10px;
        color: #565b61;
        font-size: 12px;
        font-weight: 800;
    }

    .spec-item .form-control {
        display: inline-block;
        width: calc(50% - 7px);
        margin: 0 !important;
    }

    #add-spec,
    #add-question {
        margin-top: 5px;
        padding: 10px 17px;
        border: 0;
        border-radius: 11px;
        color: #fff;
        background: #0081ff;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(0,129,255,.18);
        transition: .2s ease;
    }

    #add-spec:hover,
    #add-question:hover {
        background: #006ee0;
        transform: translateY(-1px);
    }


    /* =========================================================
       QUESTIONS
    ========================================================= */

    .question-item {
        margin-bottom: 13px;
        padding: 15px;
        border: 1px solid #e9e1d5;
        border-radius: 15px;
        background: #faf7f1;
    }

    .question-item textarea {
        min-height: 105px !important;
        padding-top: 12px;
    }


    /* =========================================================
       CKEDITOR
    ========================================================= */

    #editor-form .ck.ck-editor {
        width: 100%;
    }

    #editor-form .ck.ck-toolbar {
        border: 1px solid #ded6ca !important;
        border-bottom: 0 !important;
        border-radius: 13px 13px 0 0 !important;
        background: #f7f3ec !important;
    }

    #editor-form .ck.ck-editor__main > .ck-editor__editable {
        min-height: 270px;
        border: 1px solid #ded6ca !important;
        border-radius: 0 0 13px 13px !important;
        background: #fff !important;
        box-shadow: none !important;
        font-size: 14px;
    }

    #editor-form .ck.ck-editor__main > .ck-editor__editable:focus {
        border-color: #0081ff !important;
        box-shadow: 0 0 0 3px rgba(0,129,255,.07) !important;
    }


    /* =========================================================
       WARRANTY + SHIPPING
    ========================================================= */

    .d24p-warranty,
    .d24p-shipping {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 9px;
    }

    .d24p-option-title {
        width: 100%;
        margin-bottom: 3px;
        color: #41474d;
        font-size: 13px;
        font-weight: 800;
    }

    .d24p-warranty label,
    .d24p-shipping label {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 8px 13px;
        border: 1px solid #e1d9ce;
        border-radius: 10px;
        color: #555a60;
        background: #faf7f1;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: .2s ease;
    }

    .d24p-warranty label:hover,
    .d24p-shipping label:hover {
        border-color: #0081ff;
        color: #0074e8;
        background: #f5faff;
    }

    .d24p-warranty input,
    .d24p-shipping input {
        accent-color: #0081ff;
    }


    /* =========================================================
       SAVE
    ========================================================= */

    .digi-save-area {
        position: sticky;
        bottom: 15px;
        z-index: 20;
        display: flex;
        justify-content: flex-end;
        margin-top: 24px;
        padding: 13px;
        border: 1px solid rgba(218,208,194,.8);
        border-radius: 17px;
        background: rgba(255,253,249,.88);
        box-shadow: 0 15px 40px rgba(55,43,28,.12);
        backdrop-filter: blur(12px);
    }

    .digi-save-button {
        min-width: 190px;
        height: 48px;
        padding: 0 27px;
        border: 0;
        border-radius: 12px;
        color: #fff;
        background: linear-gradient(
                135deg,
                #0081ff,
                #0065e7
        );
        font-family: inherit;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(0,129,255,.25);
        transition: .22s ease;
    }

    .digi-save-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(0,129,255,.3);
    }

    .digi-save-button:active {
        transform: translateY(0);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .digi-input-grid {
            grid-template-columns: 1fr 1fr;
        }

        .digi-input-grid .digi-field:first-child {
            grid-column: 1 / -1;
        }

        .digi-image-grid {
            grid-template-columns: repeat(3,1fr);
        }

    }


    @media (max-width: 700px) {

        .digi-add-page {
            padding: 20px 13px 80px;
        }

        .digi-add-heading {
            padding: 18px;
        }

        .digi-add-heading h1 {
            font-size: 20px;
        }

        .digi-form-card-body {
            padding: 17px;
        }

        .digi-input-grid {
            grid-template-columns: 1fr;
        }

        .digi-input-grid .digi-field:first-child {
            grid-column: auto;
        }

        .digi-image-grid {
            grid-template-columns: 1fr 1fr;
        }

        .spec-item .form-control {
            width: 100%;
            margin-bottom: 8px !important;
        }

        .digi-save-area {
            bottom: 8px;
        }

        .digi-save-button {
            width: 100%;
        }

    }


    @media (max-width: 430px) {

        .digi-image-grid {
            grid-template-columns: 1fr;
        }

        .digi-form-card-head {
            padding: 16px;
        }

        .digi-form-card-icon {
            width: 36px;
            height: 36px;
        }

        .digi-form-card-head h2 {
            font-size: 15px;
        }

    }

</style>


<div class="content-wrapper digi-add-page">

    <div class="digi-add-header">

        <div class="digi-add-heading">

            <div class="digi-add-heading-main">

                <div class="digi-add-heading-icon">
                    +
                </div>

                <div>
                    <h1> اضافه کردن محصول(دوربین های ورزشی)</h1>
                    <p>اطلاعات محصول جدید را وارد کنید</p>
                </div>

            </div>

        </div>

    </div>


    <form
            action=""
            method="POST"
            enctype="multipart/form-data"
            id="editor-form"
    >


        <!-- اطلاعات اصلی -->

        <div class="digi-form-card">

            <div class="digi-form-card-head">

                <div class="digi-form-card-icon">✦</div>

                <div>
                    <h2>اطلاعات اصلی محصول</h2>
                    <span>نام و قیمت محصول را وارد کنید</span>
                </div>

            </div>

            <div class="digi-form-card-body">

                <div class="digi-input-grid">

                    <div class="digi-field">

                        <label>نام محصول</label>

                        <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="مثلاً دوربین حرفه‌ای..."
                                required
                        >

                    </div>


                    <div class="digi-field">

                        <label>قیمت</label>

                        <input
                                type="number"
                                name="price"
                                class="form-control"
                                placeholder="قیمت اصلی محصول"
                                required
                        >

                    </div>


                    <div class="digi-field">

                        <label>قیمت غیر واقعی</label>

                        <input
                                type="number"
                                name="pricef"
                                class="form-control"
                                placeholder="قیمت قبل از تخفیف"
                                required
                        >

                    </div>

                </div>

            </div>

        </div>


        <!-- تصاویر -->

        <div class="digi-form-card">

            <div class="digi-form-card-head">

                <div class="digi-form-card-icon">▣</div>

                <div>
                    <h2>تصاویر محصول</h2>
                    <span>تصویر اصلی الزامی و سایر تصاویر اختیاری هستند</span>
                </div>

            </div>


            <div class="digi-form-card-body">

                <div class="digi-image-grid">

                    <div class="digi-image-field">

                        <label>
                            تصویر 1
                            <span class="digi-required">الزامی</span>
                        </label>

                        <input
                                type="file"
                                name="image1"
                                class="form-control"
                                accept="image/*"
                                required
                        >

                    </div>


                    <div class="digi-image-field">

                        <label>تصویر 2</label>

                        <input
                                type="file"
                                name="image2"
                                class="form-control"
                                accept="image/*"
                        >

                    </div>


                    <div class="digi-image-field">

                        <label>تصویر 3</label>

                        <input
                                type="file"
                                name="image3"
                                class="form-control"
                                accept="image/*"
                        >

                    </div>


                    <div class="digi-image-field">

                        <label>تصویر 4</label>

                        <input
                                type="file"
                                name="image4"
                                class="form-control"
                                accept="image/*"
                        >

                    </div>


                    <div class="digi-image-field">

                        <label>تصویر 5</label>

                        <input
                                type="file"
                                name="image5"
                                class="form-control"
                                accept="image/*"
                        >

                    </div>

                </div>

            </div>

        </div>


        <!-- مشخصات -->

        <div class="digi-form-card">

            <div class="digi-form-card-head">

                <div class="digi-form-card-icon">≡</div>

                <div>
                    <h2>مشخصات محصول</h2>
                    <span>ویژگی‌های محصول را به صورت ردیفی وارد کنید</span>
                </div>

            </div>


            <div class="digi-form-card-body">

                <div id="specifications">

                    <div class="spec-item">

                        <h4>ردیف 1</h4>

                        <input
                                type="text"
                                class="form-control mb-2"
                                name="spec_name_1"
                                placeholder="نام مشخصات"
                        >

                        <input
                                type="text"
                                class="form-control"
                                name="spec_value_1"
                                placeholder="مقدار مشخصات"
                        >

                    </div>

                </div>


                <button
                        type="button"
                        id="add-spec"
                        class="btn btn-primary"
                >
                    + افزودن مشخصات
                </button>

            </div>

        </div>


        <!-- محتوا -->

        <div class="digi-form-card">

            <div class="digi-form-card-head">

                <div class="digi-form-card-icon">¶</div>

                <div>
                    <h2>محتوای محصول</h2>
                    <span>توضیحات کامل محصول را بنویسید</span>
                </div>

            </div>


            <div class="digi-form-card-body">

                <textarea
                        name="content"
                        id="content"
                ></textarea>

            </div>

        </div>


        <!-- پرسش و پاسخ -->

        <div class="digi-form-card">

            <div class="digi-form-card-head">

                <div class="digi-form-card-icon">?</div>

                <div>
                    <h2>پرسش و پاسخ</h2>
                    <span>سؤالات متداول محصول را اضافه کنید</span>
                </div>

            </div>


            <div class="digi-form-card-body">

                <div id="questions">

                    <div class="question-item">

                        <h4>ردیف 1</h4>

                        <input
                                type="text"
                                name="question_1"
                                class="form-control mb-2"
                                placeholder="سؤال"
                        >

                        <textarea
                                name="answer_1"
                                class="form-control"
                                placeholder="پاسخ"
                        ></textarea>

                    </div>

                </div>


                <button
                        type="button"
                        id="add-question"
                        class="btn btn-primary"
                >
                    + افزودن پرسش و پاسخ
                </button>

            </div>

        </div>


        <!-- گارانتی -->

        <div class="digi-form-card">

            <div class="digi-form-card-head">

                <div class="digi-form-card-icon">✓</div>

                <div>
                    <h2>گارانتی محصول</h2>
                    <span>مدت گارانتی محصول را انتخاب کنید</span>
                </div>

            </div>


            <div class="digi-form-card-body">

                <div class="d24p-warranty">

                    <span class="d24p-option-title">
                        مدت گارانتی:
                    </span>

                    <label>
                        <input type="checkbox" name="warranty_1" value="no_warranty">
                        بدون گارانتی
                    </label>

                    <label>
                        <input type="checkbox" name="warranty_2" value="6_month">
                        ۶ ماه
                    </label>

                    <label>
                        <input type="checkbox" name="warranty_3" value="12_month">
                        ۱۲ ماه
                    </label>

                    <label>
                        <input type="checkbox" name="warranty_4" value="18_month">
                        ۱۸ ماه
                    </label>

                    <label>
                        <input type="checkbox" name="warranty_5" value="24_month">
                        ۲۴ ماه
                    </label>

                    <label>
                        <input type="checkbox" name="warranty_6" value="36_month">
                        ۳۶ ماه
                    </label>

                    <label>
                        <input type="checkbox" name="warranty_7" value="48_month">
                        ۴۸ ماه
                    </label>

                </div>

            </div>

        </div>


        <!-- ارسال -->

        <div class="digi-form-card">

            <div class="digi-form-card-head">

                <div class="digi-form-card-icon">↗</div>

                <div>
                    <h2>زمان ارسال</h2>
                    <span>زمان آماده‌سازی و ارسال محصول را مشخص کنید</span>
                </div>

            </div>


            <div class="digi-form-card-body">

                <div class="d24p-shipping">

                    <span class="d24p-option-title">
                        زمان ارسال:
                    </span>

                    <label>
                        <input type="checkbox" name="shipping_1" value="today">
                        ارسال امروز
                    </label>

                    <label>
                        <input type="checkbox" name="shipping_2" value="tomorrow">
                        ارسال فردا
                    </label>

                    <label>
                        <input type="checkbox" name="shipping_4" value="2_3_days">
                        ۲ تا ۳ روز کاری
                    </label>

                    <label>
                        <input type="checkbox" name="shipping_5" value="3_5_days">
                        ۳ تا ۵ روز کاری
                    </label>

                </div>

            </div>

        </div>


        <!-- ذخیره -->

        <div class="digi-save-area" style="position: relative; right: 0;">

            <button
                    type="submit"
                    class="digi-save-button"

            >



                ذخیره محصول

            </button>

        </div>

    </form>

</div>


<script>

    let specNumber = 1;

    document
        .getElementById('add-spec')
        .addEventListener('click', function () {

            specNumber++;

            const specifications =
                document.getElementById('specifications');

            const specItem =
                document.createElement('div');

            specItem.className =
                'spec-item mb-3';

            specItem.innerHTML = `

            <h4>
                ردیف ${specNumber}
            </h4>

            <input
                type="text"
                class="form-control mb-2"
                name="spec_name_${specNumber}"
                placeholder="نام مشخصات"
            >

            <input
                type="text"
                class="form-control"
                name="spec_value_${specNumber}"
                placeholder="مقدار مشخصات"
            >

        `;

            specifications.appendChild(specItem);

        });


    let questionNumber = 1;

    document
        .getElementById('add-question')
        .addEventListener('click', function () {

            questionNumber++;

            const questions =
                document.getElementById('questions');

            const questionItem =
                document.createElement('div');

            questionItem.className =
                'question-item mb-3';

            questionItem.innerHTML = `

            <h4>
                ردیف ${questionNumber}
            </h4>

            <input
                type="text"
                name="question_${questionNumber}"
                class="form-control mb-2"
                placeholder="سؤال"
            >

            <textarea
                name="answer_${questionNumber}"
                class="form-control"
                placeholder="پاسخ"
            ></textarea>

        `;

            questions.appendChild(questionItem);

        });

</script>


<script src="../ckeditor5-build-classic/ckeditor.js"></script>

<script>

    let editor;

    class MyUploadAdapter {

        constructor(loader) {
            this.loader = loader;
            this.xhr = null;
        }

        upload() {

            return this.loader.file.then(file => {

                return new Promise((resolve, reject) => {

                    const data = new FormData();

                    data.append('upload', file);

                    this.xhr = new XMLHttpRequest();

                    this.xhr.open(
                        'POST',
                        'upload_image.php',
                        true
                    );

                    this.xhr.responseType = 'json';

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
                                    default: this.xhr.response.url
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

                    this.xhr.onerror = () => {

                        reject(
                            'ارتباط با سرور برقرار نشد.'
                        );
                    };

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


    function MyCustomUploadAdapterPlugin(editor) {

        editor.plugins
            .get('FileRepository')
            .createUploadAdapter = loader => {

            return new MyUploadAdapter(loader);

        };

    }


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

            document
                .querySelector('#editor-form')
                .addEventListener('submit', function () {

                    document.querySelector('#content').value =
                        editor.getData();

                });

        })

        .catch(error => {

            console.error(error);

        });

</script>


<?php
include "footer.php";
?>
