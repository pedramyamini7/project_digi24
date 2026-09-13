<?php
session_start();

require "../config/config.php";

if (isset($_POST['save'])) {

    $time1 = $_POST['time1'];

    $nameimage = $_FILES['image']['name'];
    $tmpimage = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmpimage, "../up/" . $nameimage);

    $sql = "INSERT INTO tamas_ba_ma_t(image, text) VALUE ('$nameimage', '$time1')";

    mysqli_query($conn, $sql);

    echo "<script>
            window.location.href = 'tamas_ba_ma.php';
          </script>";
}

?>

<?php
include "haeder.php";
?>


<style>

    /* =====================================================
       صفحه اصلی
    ===================================================== */

    .digi-text-page {
        direction: rtl;
        min-height: 100vh;

        /* فاصله از سایدبار ادمین */
        margin-right: 250px;

        padding: 34px 38px 100px;

        background:
                radial-gradient(
                        circle at top right,
                        rgba(0,129,255,.10),
                        transparent 32%
                ),
                radial-gradient(
                        circle at bottom left,
                        rgba(188,154,92,.10),
                        transparent 30%
                ),
                #f5f0e7;

        box-sizing: border-box;
    }


    /* =====================================================
       عنوان صفحه
    ===================================================== */

    .digi-text-heading {
        width: 100%;
        max-width: 1420px;

        margin: 0 auto 25px;
        padding: 24px 28px;

        background: rgba(255,253,249,.90);

        border: 1px solid rgba(255,255,255,.8);
        border-radius: 22px;

        box-shadow:
                0 10px 30px rgba(0,0,0,.07),
                inset 0 1px 0 rgba(255,255,255,.8);

        box-sizing: border-box;
    }


    .digi-text-heading h1 {
        margin: 0;

        color: #222;

        font-size: 28px;
        font-weight: 800;
    }


    .digi-text-heading p {
        margin: 8px 0 0;

        color: #777;

        font-size: 14px;
    }


    /* =====================================================
       کارت اصلی
    ===================================================== */

    .digi-text-card {
        width: 100%;
        max-width: 1420px;

        margin: 0 auto;

        background: #fffdf9;

        border-radius: 21px;

        box-shadow:
                0 12px 35px rgba(0,0,0,.08);

        overflow: hidden;

        box-sizing: border-box;
    }


    /* =====================================================
       هدر کارت
    ===================================================== */

    .digi-text-card-head {
        position: relative;

        padding: 20px 25px;

        background:
                linear-gradient(
                        135deg,
                        rgba(0,129,255,.08),
                        rgba(255,255,255,.5)
                );

        border-bottom: 1px solid #eee5da;
    }


    .digi-text-card-head::after {
        content: "";

        position: absolute;

        right: 0;
        top: 18px;
        bottom: 18px;

        width: 5px;

        background: #0081ff;

        border-radius: 5px 0 0 5px;
    }


    .digi-text-card-head h3 {
        margin: 0;

        color: #222;

        font-size: 20px;
        font-weight: 800;
    }


    .digi-text-card-head span {
        display: block;

        margin-top: 6px;

        color: #888;

        font-size: 13px;
    }


    /* =====================================================
       بدنه فرم
    ===================================================== */

    .digi-text-card-body {
        padding: 30px;
    }


    /* =====================================================
       لیبل
    ===================================================== */

    .digi-text-label {
        display: block;

        margin-bottom: 10px;

        color: #333;

        font-size: 15px;
        font-weight: 700;
    }


    /* =====================================================
       تکست اریا
    ===================================================== */

    .digi-text-input {
        display: block;

        width: 100%;
        min-height: 180px;

        padding: 15px 16px;

        background: #faf7f1;

        border: 1px solid #e2dbd0;
        border-radius: 12px;

        color: #333;

        font-size: 15px;
        line-height: 1.9;

        outline: none;

        resize: vertical;

        box-sizing: border-box;

        transition: .2s;
    }


    .digi-text-input::placeholder {
        color: #aaa;
    }


    .digi-text-input:focus {
        background: #fff;

        border-color: #0081ff;

        box-shadow:
                0 0 0 4px rgba(0,129,255,.10);
    }


    /* =====================================================
       قسمت آپلود تصویر
    ===================================================== */

    .digi-text-image-box {
        margin-top: 24px;

        padding: 22px;

        background: #faf7f1;

        border: 1px solid #e2dbd0;

        border-radius: 15px;
    }


    .digi-text-image-box label {
        display: block;

        margin-bottom: 12px;

        color: #333;

        font-size: 15px;

        font-weight: 700;
    }


    .digi-text-file {
        display: block;

        width: 100%;

        padding: 12px;

        background: #fffdf9;

        border: 1px dashed #cfc5b8;

        border-radius: 12px;

        color: #666;

        font-size: 14px;

        cursor: pointer;

        box-sizing: border-box;
    }


    .digi-text-file:hover {
        border-color: #0081ff;
    }


    /* =====================================================
       قسمت دکمه
    ===================================================== */

    .digi-text-save-area {
        display: flex;

        justify-content: flex-start;
        align-items: center;

        margin-top: 28px;
        padding-top: 22px;

        border-top: 1px solid #eee5da;
    }


    /* =====================================================
       دکمه ذخیره
    ===================================================== */

    .digi-text-save-btn {
        border: none;

        padding: 12px 30px;

        background: #0081ff;

        color: #fff;

        border-radius: 12px;

        font-size: 15px;
        font-weight: 700;

        cursor: pointer;

        box-shadow:
                0 8px 18px rgba(0,129,255,.20);

        transition: .2s;
    }


    .digi-text-save-btn:hover {
        background: #006fe0;

        transform: translateY(-2px);

        box-shadow:
                0 11px 22px rgba(0,129,255,.27);
    }


    .digi-text-save-btn:active {
        transform: translateY(0);
    }


    /* =====================================================
       نمایشگر متوسط
    ===================================================== */

    @media (max-width: 1100px) {

        .digi-text-page {

            margin-right: 0;

            padding: 25px 20px 80px;
        }

    }


    /* =====================================================
       موبایل
    ===================================================== */

    @media (max-width: 700px) {

        .digi-text-page {

            margin-right: 0;

            padding: 18px 12px 70px;
        }


        .digi-text-heading {

            padding: 20px;

            border-radius: 17px;
        }


        .digi-text-heading h1 {

            font-size: 23px;
        }


        .digi-text-card {

            border-radius: 17px;
        }


        .digi-text-card-head {

            padding: 18px 20px;
        }


        .digi-text-card-body {

            padding: 20px;
        }


        .digi-text-image-box {

            padding: 17px;
        }


        .digi-text-save-area {

            justify-content: stretch;
        }


        .digi-text-save-btn {

            width: 100%;
        }

    }


    /* =====================================================
       موبایل کوچک
    ===================================================== */

    @media (max-width: 430px) {

        .digi-text-page {

            padding: 12px 8px 60px;
        }


        .digi-text-heading {

            padding: 17px;
        }


        .digi-text-heading h1 {

            font-size: 20px;
        }


        .digi-text-card-head {

            padding: 17px 20px;
        }


        .digi-text-card-body {

            padding: 17px;
        }


        .digi-text-input {

            min-height: 150px;
        }

    }

</style>


<!-- =====================================================
     صفحه اصلی
===================================================== -->

<div class="digi-text-page">


    <!-- =================================================
         عنوان صفحه
    ================================================== -->

    <div class="digi-text-heading">

        <h1>
            اضافه کردن متن و تصویر
        </h1>

        <p>
            متن و تصویر مورد نظر را وارد کنید و ذخیره نمایید.
        </p>

    </div>


    <!-- =================================================
         کارت فرم
    ================================================== -->

    <div class="digi-text-card">


        <!-- =================================================
             هدر کارت
        ================================================== -->

        <div class="digi-text-card-head">

            <h3>
                محتوای جدید
            </h3>

            <span>
                متن و تصویر را در قسمت‌های زیر وارد کنید
            </span>

        </div>


        <!-- =================================================
             بدنه فرم
        ================================================== -->

        <div class="digi-text-card-body">

            <form
                    action=""
                    method="POST"
                    enctype="multipart/form-data"
            >


                <!-- =============================================
                     متن
                ============================================== -->

                <label class="digi-text-label">

                    متن

                </label>


                <textarea
                        class="digi-text-input"
                        name="time1"
                        placeholder="متن پاراگراف را وارد کنید"
                ></textarea>


                <!-- =============================================
                     تصویر
                ============================================== -->

                <div class="digi-text-image-box">

                    <label>
                        تصویر
                    </label>

                    <input
                            type="file"
                            name="image"
                            class="digi-text-file"
                    >

                </div>


                <!-- =============================================
                     دکمه ذخیره
                ============================================== -->

                <div class="digi-text-save-area">

                    <button
                            type="submit"
                            class="digi-text-save-btn"
                            name="save"
                    >
                        ذخیره محتوا
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<?php

include "footer.php";

?>
