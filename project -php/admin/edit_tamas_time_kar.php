<?php
session_start();

require "../config/config.php";

$id = $_GET['id'];

$sql = "SELECT * FROM tamas_ba_ma_t WHERE id='$id'";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {

    $text = $_POST['text'];

    $image = $_FILES['image']['name'];

    if ($image != "") {
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../up/" . $image
        );
    } else {
        $image = $row['image'];
    }

    $sql = "UPDATE tamas_ba_ma_t SET text='$text',image='$image' WHERE id='$id'";
    mysqli_query($conn, $sql);

    header("Location: tamas_ba_ma.php");
}

?>

<?php
include "haeder.php";
?>


<style>

    .digi-text-edit-page {
        min-height: 100vh;

        padding: 34px 38px 100px;

        margin-right: 250px;

        direction: rtl;

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
    }


    /* =========================================
       عنوان صفحه
    ========================================= */

    .digi-text-heading {
        max-width: 1420px;

        margin: 0 auto 25px;

        padding: 22px 25px;

        display: flex;
        align-items: center;

        gap: 17px;

        background: rgba(255, 253, 249, 0.88);

        border: 1px solid rgba(255, 255, 255, 0.7);

        border-radius: 22px;

        box-shadow:
                0 12px 35px rgba(40, 50, 70, 0.08);

        backdrop-filter: blur(10px);
    }


    .digi-text-heading-icon {
        width: 55px;
        height: 55px;

        flex-shrink: 0;

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

        font-size: 25px;

        box-shadow:
                0 8px 18px rgba(0, 129, 255, 0.25);
    }


    .digi-text-heading h1 {
        margin: 0;

        color: #20252b;

        font-size: 27px;

        font-weight: 800;
    }


    .digi-text-heading p {
        margin: 5px 0 0;

        color: #777;

        font-size: 14px;
    }


    /* =========================================
       پیام موفقیت
    ========================================= */

    .digi-text-alert {
        max-width: 1420px;

        margin: 0 auto 20px;

        padding: 14px 18px;

        border-radius: 13px;

        background: #e9f8ef;

        border: 1px solid #bde8ca;

        color: #267442;

        font-size: 14px;
    }


    /* =========================================
       کارت اصلی
    ========================================= */

    .digi-text-card {
        max-width: 1420px;

        margin: 0 auto;

        background: #fffdf9;

        border-radius: 21px;

        overflow: hidden;

        box-shadow:
                0 14px 40px rgba(40, 50, 70, 0.09);
    }


    /* =========================================
       هدر کارت
    ========================================= */

    .digi-text-card-head {
        position: relative;

        padding: 20px 24px;

        display: flex;
        align-items: center;

        gap: 12px;

        background:
                linear-gradient(
                        180deg,
                        #fffdf9,
                        #faf7f1
                );

        border-bottom: 1px solid #e9e1d5;
    }


    .digi-text-card-head::before {
        content: "";

        position: absolute;

        right: 0;

        top: 15px;
        bottom: 15px;

        width: 5px;

        background: #0081ff;

        border-radius: 5px 0 0 5px;
    }


    .digi-text-card-icon {
        width: 43px;
        height: 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: rgba(0, 129, 255, 0.10);

        color: #0081ff;

        font-size: 21px;
    }


    .digi-text-card-head h2 {
        margin: 0;

        color: #252a30;

        font-size: 19px;

        font-weight: 800;
    }


    /* =========================================
       بدنه کارت
    ========================================= */

    .digi-text-card-body {
        padding: 30px;
    }


    /* =========================================
       فیلد متن
    ========================================= */

    .digi-text-field {
        margin-bottom: 28px;
    }


    .digi-text-label {
        display: block;

        margin-bottom: 10px;

        color: #30353b;

        font-size: 15px;

        font-weight: 700;
    }


    .digi-text-textarea {
        width: 100%;

        min-height: 180px;

        padding: 16px 18px;

        resize: vertical;

        box-sizing: border-box;

        background: #faf7f1;

        border: 1px solid #e2dbd0;

        border-radius: 13px;

        outline: none;

        color: #30353b;

        font-size: 16px;

        line-height: 1.9;

        transition: all 0.2s ease;
    }


    .digi-text-textarea:focus {
        background: #fff;

        border-color: #0081ff;

        box-shadow:
                0 0 0 4px rgba(0, 129, 255, 0.10);
    }


    /* =========================================
       بخش تصویر
    ========================================= */

    .digi-text-image-box {
        padding: 22px;

        background: #faf7f1;

        border: 1px solid #e2dbd0;

        border-radius: 17px;
    }


    .digi-text-image-title {
        margin-bottom: 15px;

        color: #30353b;

        font-size: 15px;

        font-weight: 700;
    }


    /* تصویر فعلی */

    .digi-text-current-image {
        width: 170px;
        height: 130px;

        object-fit: contain;

        display: block;

        margin-bottom: 18px;

        padding: 8px;

        background: white;

        border: 1px solid #e2dbd0;

        border-radius: 13px;

        box-shadow:
                0 6px 15px rgba(40, 50, 70, 0.07);
    }


    /* آپلود */

    .digi-text-file {
        width: 100%;

        padding: 12px;

        background: #fffdf9;

        border: 1px solid #e2dbd0;

        border-radius: 12px;

        color: #555;

        font-size: 14px;

        box-sizing: border-box;
    }


    .digi-text-file:focus {
        outline: none;

        border-color: #0081ff;

        box-shadow:
                0 0 0 4px rgba(0, 129, 255, 0.08);
    }


    /* =========================================
       دکمه ذخیره
    ========================================= */

    .digi-text-save-area {
        margin-top: 30px;

        padding-top: 24px;

        border-top: 1px solid #e9e1d5;

        display: flex;

        justify-content: flex-start;
    }


    .digi-text-save {
        padding: 13px 30px;

        border: none;

        border-radius: 12px;

        background: #0081ff;

        color: white;

        font-size: 15px;

        font-weight: 700;

        cursor: pointer;

        box-shadow:
                0 8px 18px rgba(0, 129, 255, 0.22);

        transition: all 0.2s ease;
    }


    .digi-text-save:hover {
        background: #006fe0;

        transform: translateY(-2px);

        box-shadow:
                0 11px 23px rgba(0, 129, 255, 0.28);
    }


    /* =========================================
       ریسپانسیو
    ========================================= */

    @media (max-width: 1100px) {

        .digi-text-edit-page {
            margin-right: 0;
        }

    }


    @media (max-width: 700px) {

        .digi-text-edit-page {
            padding: 25px 18px 80px;
        }

        .digi-text-heading {
            padding: 18px;
        }

        .digi-text-heading h1 {
            font-size: 22px;
        }

        .digi-text-card-body {
            padding: 20px;
        }

        .digi-text-current-image {
            width: 150px;
            height: 115px;
        }

    }


    @media (max-width: 430px) {

        .digi-text-edit-page {
            padding: 20px 12px 70px;
        }

        .digi-text-heading-icon {
            width: 48px;
            height: 48px;
        }

        .digi-text-heading h1 {
            font-size: 19px;
        }

        .digi-text-card-body {
            padding: 16px;
        }

        .digi-text-save {
            width: 100%;
        }

    }

</style>


<div class="digi-text-edit-page">


    <?php if (isset($_GET['updated'])) { ?>

        <div class="digi-text-alert">
            تغییرات با موفقیت ذخیره شد.
        </div>

    <?php } ?>


    <!-- =========================================
         عنوان صفحه
    ========================================== -->

    <div class="digi-text-heading">

        <div class="digi-text-heading-icon">
            ✎
        </div>

        <div>

            <h1>
                ویرایش متن و تصویر
            </h1>

            <p>
                متن و تصویر بخش تماس با ما را ویرایش کنید
            </p>

        </div>

    </div>


    <!-- =========================================
         کارت فرم
    ========================================== -->

    <div class="digi-text-card">


        <div class="digi-text-card-head">

            <div class="digi-text-card-icon">
                ✎
            </div>

            <h2>
                اطلاعات اصلی
            </h2>

        </div>


        <div class="digi-text-card-body">


            <form
                    method="POST"
                    enctype="multipart/form-data"
            >


                <!-- متن -->

                <div class="digi-text-field">

                    <label class="digi-text-label">
                        متن
                    </label>

                    <textarea
                            name="text"
                            class="digi-text-textarea"
                    ><?php echo htmlspecialchars($row['text']); ?></textarea>

                </div>


                <!-- تصویر -->

                <div class="digi-text-field">

                    <label class="digi-text-label">
                        تصویر
                    </label>


                    <div class="digi-text-image-box">

                        <div class="digi-text-image-title">
                            تصویر فعلی
                        </div>


                        <img
                                src="../up/<?php echo $row['image']; ?>"
                                class="digi-text-current-image"
                        >


                        <input
                                type="file"
                                name="image"
                                class="digi-text-file"
                        >

                    </div>

                </div>


                <!-- دکمه -->

                <div class="digi-text-save-area">

                    <input
                            type="submit"
                            name="update"
                            value="ویرایش محصول"
                            class="digi-text-save"
                    >

                </div>


            </form>


        </div>

    </div>

</div>


<?php
include "footer.php";
?>
