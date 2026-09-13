<?php
session_start();

require "../config/config.php";

$id = $_GET['id'];

$sql = "SELECT * FROM tamas_ba_ma_an WHERE id='$id'";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {

    $text = $_POST['text'];

    $sql = "UPDATE tamas_ba_ma_an SET number='$text' WHERE id='$id'";
    mysqli_query($conn, $sql);

    header("Location: tamas_ba_ma.php");
    exit;
}

?>

<?php
include "haeder.php";
?>

<style>

    .digi-number-edit-page {
        min-height: 100vh;
        padding: 34px 38px 100px;
        margin-right: 250px;

        background:
                radial-gradient(
                        circle at top right,
                        rgba(0, 129, 255, 0.10),
                        transparent 30%
                ),
                radial-gradient(
                        circle at bottom left,
                        rgba(188, 154, 92, 0.10),
                        transparent 30%
                ),
                #f5f0e7;

        direction: rtl;
    }


    /* عنوان صفحه */
    .digi-number-heading {
        max-width: 1420px;
        margin: 0 auto 25px;

        padding: 22px 25px;

        display: flex;
        align-items: center;
        gap: 17px;

        background: rgba(255, 253, 249, 0.88);

        border: 1px solid rgba(255,255,255,0.7);

        border-radius: 22px;

        box-shadow:
                0 12px 35px rgba(40, 50, 70, 0.08);

        backdrop-filter: blur(10px);
    }


    .digi-number-heading-icon {
        width: 55px;
        height: 55px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 17px;

        background: linear-gradient(
                135deg,
                #0081ff,
                #0063e8
        );

        color: white;

        font-size: 24px;

        box-shadow:
                0 8px 18px rgba(0,129,255,0.25);
    }


    .digi-number-heading h1 {
        margin: 0;

        color: #20252b;

        font-size: 27px;
        font-weight: 800;
    }


    .digi-number-heading p {
        margin: 5px 0 0;

        color: #777;

        font-size: 14px;
    }


    /* کارت اصلی */
    .digi-number-card {
        max-width: 1420px;
        margin: auto;

        background: #fffdf9;

        border-radius: 21px;

        box-shadow:
                0 14px 40px rgba(40, 50, 70, 0.09);

        overflow: hidden;
    }


    /* هدر کارت */
    .digi-number-card-head {
        position: relative;

        padding: 20px 24px;

        background:
                linear-gradient(
                        180deg,
                        #fffdf9,
                        #faf7f1
                );

        border-bottom: 1px solid #e9e1d5;

        display: flex;
        align-items: center;

        gap: 12px;
    }


    .digi-number-card-head::before {
        content: "";

        position: absolute;

        right: 0;
        top: 15px;
        bottom: 15px;

        width: 5px;

        background: #0081ff;

        border-radius: 5px 0 0 5px;
    }


    .digi-number-card-icon {
        width: 43px;
        height: 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: rgba(0,129,255,0.10);

        color: #0081ff;

        font-size: 20px;
    }


    .digi-number-card-head h2 {
        margin: 0;

        font-size: 19px;
        font-weight: 800;

        color: #252a30;
    }


    /* بدنه */
    .digi-number-card-body {
        padding: 30px;
    }


    /* لیبل */
    .digi-number-label {
        display: block;

        margin-bottom: 10px;

        color: #30353b;

        font-size: 15px;
        font-weight: 700;
    }


    /* textarea */
    .digi-number-textarea {
        width: 100%;

        min-height: 150px;

        padding: 16px 18px;

        resize: vertical;

        background: #faf7f1;

        border: 1px solid #e2dbd0;

        border-radius: 13px;

        outline: none;

        color: #30353b;

        font-size: 16px;

        line-height: 1.8;

        transition: all 0.2s ease;

        box-sizing: border-box;
    }


    .digi-number-textarea:focus {
        background: #fff;

        border-color: #0081ff;

        box-shadow:
                0 0 0 4px rgba(0,129,255,0.10);
    }


    /* دکمه */
    .digi-number-save {
        margin-top: 22px;

        padding: 13px 28px;

        border: none;

        border-radius: 12px;

        background: #0081ff;

        color: white;

        font-size: 15px;
        font-weight: 700;

        cursor: pointer;

        box-shadow:
                0 8px 18px rgba(0,129,255,0.22);

        transition: all 0.2s ease;
    }


    .digi-number-save:hover {
        background: #006fe0;

        transform: translateY(-2px);

        box-shadow:
                0 11px 23px rgba(0,129,255,0.28);
    }


    /* پیام موفقیت */
    .digi-number-alert {
        max-width: 1420px;

        margin: 0 auto 20px;

        padding: 14px 18px;

        border-radius: 13px;

        background: #e9f8ef;

        border: 1px solid #bde8ca;

        color: #267442;

        font-size: 14px;
    }


    /* ریسپانسیو */
    @media (max-width: 1100px) {

        .digi-number-edit-page {
            margin-right: 0;
        }

    }


    @media (max-width: 700px) {

        .digi-number-edit-page {
            padding: 25px 18px 80px;
        }

        .digi-number-heading {
            padding: 18px;
        }

        .digi-number-heading h1 {
            font-size: 22px;
        }

        .digi-number-card-body {
            padding: 20px;
        }

    }


    @media (max-width: 430px) {

        .digi-number-edit-page {
            padding: 20px 12px 70px;
        }

        .digi-number-heading-icon {
            width: 48px;
            height: 48px;
        }

        .digi-number-heading h1 {
            font-size: 19px;
        }

        .digi-number-card-body {
            padding: 16px;
        }

        .digi-number-save {
            width: 100%;
        }

    }

</style>


<div class="digi-number-edit-page">

    <?php if (isset($_GET['updated'])) { ?>

        <div class="digi-number-alert">
            تغییرات با موفقیت ذخیره شد.
        </div>

    <?php } ?>


    <!-- عنوان -->
    <div class="digi-number-heading">

        <div class="digi-number-heading-icon">
            ☎
        </div>

        <div>

            <h1>
                ویرایش شماره تماس
            </h1>

            <p>
                شماره تماس ثبت‌شده را ویرایش کنید
            </p>

        </div>

    </div>


    <!-- کارت -->
    <div class="digi-number-card">

        <div class="digi-number-card-head">

            <div class="digi-number-card-icon">
                ☎
            </div>

            <h2>
                اطلاعات شماره تماس
            </h2>

        </div>


        <div class="digi-number-card-body">

            <form method="POST">

                <label class="digi-number-label">
                    شماره تماس
                </label>

                <textarea
                        name="text"
                        class="digi-number-textarea"
                ><?php echo htmlspecialchars($row['number']); ?></textarea>


                <input
                        type="submit"
                        name="update"
                        value="ویرایش شماره"
                        class="digi-number-save"
                >

            </form>

        </div>

    </div>

</div>


<?php
include "footer.php";
?>
