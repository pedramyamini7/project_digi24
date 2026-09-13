<?php
session_start();

require "../config/config.php";

$id = $_GET['id'];

$sql = "SELECT * FROM tamas_ba_ma_an WHERE id='$id'";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);


if (isset($_POST['update'])) {

    $text = $_POST['text'];

    $sql = "UPDATE tamas_ba_ma_an 
            SET adress='$text' 
            WHERE id='$id'";

    mysqli_query($conn, $sql);

    header("Location: tamas_ba_ma.php");
}

?>

<?php
include "haeder.php";
?>


<style>

    /* =====================================================
       صفحه اصلی
    ===================================================== */

    .digi-address-edit-page {
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

    .digi-address-edit-heading {
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


    .digi-address-edit-heading h1 {
        margin: 0;

        color: #222;

        font-size: 28px;
        font-weight: 800;
    }


    .digi-address-edit-heading p {
        margin: 8px 0 0;

        color: #777;

        font-size: 14px;
    }


    /* =====================================================
       پیام موفقیت
    ===================================================== */

    .digi-address-edit-success {
        width: 100%;
        max-width: 1420px;

        margin: 0 auto 20px;
        padding: 14px 18px;

        background: #eaf8ef;

        border: 1px solid #b9e5c8;

        border-radius: 13px;

        color: #24733d;

        font-size: 14px;

        box-sizing: border-box;
    }


    /* =====================================================
       کارت اصلی
    ===================================================== */

    .digi-address-edit-card {
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

    .digi-address-edit-card-head {
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


    .digi-address-edit-card-head::after {
        content: "";

        position: absolute;

        right: 0;
        top: 18px;
        bottom: 18px;

        width: 5px;

        background: #0081ff;

        border-radius: 5px 0 0 5px;
    }


    .digi-address-edit-card-head h3 {
        margin: 0;

        color: #222;

        font-size: 20px;
        font-weight: 800;
    }


    .digi-address-edit-card-head span {
        display: block;

        margin-top: 6px;

        color: #888;

        font-size: 13px;
    }


    /* =====================================================
       بدنه فرم
    ===================================================== */

    .digi-address-edit-body {
        padding: 30px;
    }


    /* =====================================================
       لیبل
    ===================================================== */

    .digi-address-edit-label {
        display: block;

        margin-bottom: 10px;

        color: #333;

        font-size: 15px;
        font-weight: 700;
    }


    /* =====================================================
       تکست اریا
    ===================================================== */

    .digi-address-edit-textarea {
        display: block;

        width: 100%;

        min-height: 190px;

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


    .digi-address-edit-textarea:focus {
        background: #fff;

        border-color: #0081ff;

        box-shadow:
                0 0 0 4px rgba(0,129,255,.10);
    }


    /* =====================================================
       قسمت دکمه
    ===================================================== */

    .digi-address-edit-save-area {
        display: flex;

        justify-content: flex-start;
        align-items: center;

        margin-top: 28px;

        padding-top: 22px;

        border-top: 1px solid #eee5da;
    }


    /* =====================================================
       دکمه
    ===================================================== */

    .digi-address-edit-btn {
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


    .digi-address-edit-btn:hover {
        background: #006fe0;

        transform: translateY(-2px);

        box-shadow:
                0 11px 22px rgba(0,129,255,.27);
    }


    .digi-address-edit-btn:active {
        transform: translateY(0);
    }


    /* =====================================================
       نمایشگر متوسط
    ===================================================== */

    @media (max-width: 1100px) {

        .digi-address-edit-page {

            margin-right: 0;

            padding: 25px 20px 80px;
        }

    }


    /* =====================================================
       موبایل
    ===================================================== */

    @media (max-width: 700px) {

        .digi-address-edit-page {

            margin-right: 0;

            padding: 18px 12px 70px;
        }


        .digi-address-edit-heading {

            padding: 20px;

            border-radius: 17px;
        }


        .digi-address-edit-heading h1 {

            font-size: 23px;
        }


        .digi-address-edit-card {

            border-radius: 17px;
        }


        .digi-address-edit-card-head {

            padding: 18px 20px;
        }


        .digi-address-edit-body {

            padding: 20px;
        }


        .digi-address-edit-save-area {

            justify-content: stretch;
        }


        .digi-address-edit-btn {

            width: 100%;
        }

    }


    /* =====================================================
       موبایل کوچک
    ===================================================== */

    @media (max-width: 430px) {

        .digi-address-edit-page {

            padding: 12px 8px 60px;
        }


        .digi-address-edit-heading {

            padding: 17px;
        }


        .digi-address-edit-heading h1 {

            font-size: 20px;
        }


        .digi-address-edit-card-head {

            padding: 17px 20px;
        }


        .digi-address-edit-body {

            padding: 17px;
        }


        .digi-address-edit-textarea {

            min-height: 160px;
        }

    }

</style>


<!-- =====================================================
     صفحه ویرایش آدرس
===================================================== -->

<div class="digi-address-edit-page">


    <!-- =================================================
         پیام موفقیت
    ================================================== -->

    <?php if (isset($_GET['updated'])) { ?>

        <div class="digi-address-edit-success">

            تغییرات با موفقیت ذخیره شد.

        </div>

    <?php } ?>


    <!-- =================================================
         عنوان
    ================================================== -->

    <div class="digi-address-edit-heading">

        <h1>
            ویرایش آدرس
        </h1>

        <p>
            آدرس مورد نظر را ویرایش کنید و تغییرات را ذخیره نمایید.
        </p>

    </div>


    <!-- =================================================
         کارت فرم
    ================================================== -->

    <div class="digi-address-edit-card">


        <!-- =================================================
             هدر کارت
        ================================================== -->

        <div class="digi-address-edit-card-head">

            <h3>
                اطلاعات آدرس
            </h3>

            <span>
                آدرس فعلی را تغییر دهید
            </span>

        </div>


        <!-- =================================================
             بدنه فرم
        ================================================== -->

        <div class="digi-address-edit-body">

            <form
                    method="POST"
                    enctype="multipart/form-data"
            >


                <!-- =============================================
                     آدرس
                ============================================== -->

                <label class="digi-address-edit-label">

                    آدرس

                </label>


                <textarea
                        name="text"
                        class="digi-address-edit-textarea"
                        required
                ><?php echo htmlspecialchars($row['adress']); ?></textarea>


                <!-- =============================================
                     دکمه ویرایش
                ============================================== -->

                <div class="digi-address-edit-save-area">

                    <button
                            type="submit"
                            name="update"
                            class="digi-address-edit-btn"
                    >
                        ویرایش آدرس
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<?php
include "footer.php";
?>
