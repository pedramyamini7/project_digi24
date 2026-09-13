<?php
session_start();

require "../config/config.php";

if (isset($_POST['save'])) {

    $time1 = $_POST['time1'];

    $sql = "INSERT INTO tamas_ba_ma_an(adress) VALUE ('$time1')";

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

    .digi-address-page {
        direction: rtl;
        min-height: 100vh;

        /*
           فاصله از نوار کناری ادمین
           اگر سایدبار 250px باشد
        */
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

    .digi-address-heading {
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


    .digi-address-heading h1 {
        margin: 0;

        color: #222;

        font-size: 28px;

        font-weight: 800;
    }


    .digi-address-heading p {
        margin: 8px 0 0;

        color: #777;

        font-size: 14px;
    }


    /* =====================================================
       کارت اصلی
    ===================================================== */

    .digi-address-card {
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

    .digi-address-card-head {
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


    .digi-address-card-head::after {
        content: "";

        position: absolute;

        right: 0;
        top: 18px;
        bottom: 18px;

        width: 5px;

        background: #0081ff;

        border-radius: 5px 0 0 5px;
    }


    .digi-address-card-head h3 {
        margin: 0;

        color: #222;

        font-size: 20px;

        font-weight: 800;
    }


    .digi-address-card-head span {
        display: block;

        margin-top: 6px;

        color: #888;

        font-size: 13px;
    }


    /* =====================================================
       بدنه فرم
    ===================================================== */

    .digi-address-card-body {
        padding: 30px;
    }


    /* =====================================================
       لیبل
    ===================================================== */

    .digi-address-label {
        display: block;

        margin-bottom: 10px;

        color: #333;

        font-size: 15px;

        font-weight: 700;
    }


    /* =====================================================
       ورودی آدرس
    ===================================================== */

    .digi-address-input {
        display: block;

        width: 100%;

        min-height: 52px;

        padding: 12px 16px;

        background: #faf7f1;

        border: 1px solid #e2dbd0;

        border-radius: 12px;

        color: #333;

        font-size: 15px;

        outline: none;

        box-sizing: border-box;

        transition: .2s;
    }


    .digi-address-input::placeholder {
        color: #aaa;
    }


    .digi-address-input:focus {
        background: #fff;

        border-color: #0081ff;

        box-shadow:
                0 0 0 4px rgba(0,129,255,.10);
    }


    /* =====================================================
       قسمت دکمه
    ===================================================== */

    .digi-address-save-area {
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

    .digi-address-save-btn {
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


    .digi-address-save-btn:hover {
        background: #006fe0;

        transform: translateY(-2px);

        box-shadow:
                0 11px 22px rgba(0,129,255,.27);
    }


    .digi-address-save-btn:active {
        transform: translateY(0);
    }


    /* =====================================================
       نمایشگرهای متوسط
    ===================================================== */

    @media (max-width: 1100px) {

        .digi-address-page {

            /*
               در این اندازه معمولاً
               سایدبار جمع یا کوچک می‌شود
            */
            margin-right: 0;

            padding: 25px 20px 80px;
        }

    }


    /* =====================================================
       موبایل
    ===================================================== */

    @media (max-width: 700px) {

        .digi-address-page {

            margin-right: 0;

            padding: 18px 12px 70px;
        }


        .digi-address-heading {

            padding: 20px;

            border-radius: 17px;
        }


        .digi-address-heading h1 {

            font-size: 23px;
        }


        .digi-address-card {

            border-radius: 17px;
        }


        .digi-address-card-head {

            padding: 18px 20px;
        }


        .digi-address-card-body {

            padding: 20px;
        }


        .digi-address-save-area {

            justify-content: stretch;
        }


        .digi-address-save-btn {

            width: 100%;
        }

    }


    /* =====================================================
       موبایل کوچک
    ===================================================== */

    @media (max-width: 430px) {

        .digi-address-page {

            padding: 12px 8px 60px;
        }


        .digi-address-heading {

            padding: 17px;
        }


        .digi-address-heading h1 {

            font-size: 20px;
        }


        .digi-address-card-head {

            padding: 17px 20px;
        }


        .digi-address-card-body {

            padding: 17px;
        }

    }

</style>


<!-- =====================================================
     صفحه
===================================================== -->

<div class="digi-address-page">


    <!-- =================================================
         عنوان
    ================================================== -->

    <div class="digi-address-heading">

        <h1>
            اضافه کردن آدرس
        </h1>

        <p>
            آدرس جدید را وارد کنید و ذخیره نمایید.
        </p>

    </div>


    <!-- =================================================
         کارت فرم
    ================================================== -->

    <div class="digi-address-card">


        <!-- =================================================
             هدر کارت
        ================================================== -->

        <div class="digi-address-card-head">

            <h3>
                اطلاعات آدرس
            </h3>

            <span>
                آدرس جدید را در کادر زیر وارد کنید
            </span>

        </div>


        <!-- =================================================
             بدنه
        ================================================== -->

        <div class="digi-address-card-body">

            <form
                    action=""
                    method="POST"
                    enctype="multipart/form-data"
            >


                <!-- =============================================
                     آدرس
                ============================================== -->

                <label class="digi-address-label">

                    آدرس

                </label>


                <input
                        type="text"
                        class="digi-address-input"
                        name="time1"
                        placeholder="آدرس جدید را وارد کنید"
                >


                <!-- =============================================
                     ذخیره
                ============================================== -->

                <div class="digi-address-save-area">

                    <button
                            type="submit"
                            class="digi-address-save-btn"
                            name="save"
                    >
                        ذخیره آدرس
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<?php

include "footer.php";

?>
