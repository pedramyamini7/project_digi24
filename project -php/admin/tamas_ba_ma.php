<?php
session_start();

require "../config/config.php";

$sql = "SELECT * FROM tamas_ba_ma ORDER BY id DESC";
$sql2 = "SELECT * FROM tamas_ba_ma_t ORDER BY id DESC";
$sql3 = "SELECT * FROM tamas_ba_ma_an ORDER BY id DESC";
$sql4 = "SELECT * FROM tamas_ba_ma_an ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);
$result4 = mysqli_query($conn, $sql4);

?>

<?php include "haeder.php"; ?>

<style>

    /* =====================================================
       CONTACT PAGE
    ===================================================== */

    .digi-contact-page {
        min-height: 90vh;
        padding: 35px 30px 70px;

        background:
                radial-gradient(
                        circle at 90% 4%,
                        rgba(0, 129, 255, .08),
                        transparent 28%
                ),
                radial-gradient(
                        circle at 8% 90%,
                        rgba(230, 150, 55, .10),
                        transparent 30%
                ),
                #f8f6f2;

        font-family: inherit;
    }


    /* =====================================================
       TOP
    ===================================================== */

    .digi-contact-top {
        max-width: 1450px;
        margin: 0 auto 30px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .digi-contact-title h1 {
        margin: 0;

        color: #302a24;

        font-size: 30px;
        font-weight: 900;

        letter-spacing: -1px;
    }


    .digi-contact-title p {
        margin: 8px 0 0;

        color: #91867b;

        font-size: 13px;
    }


    /* =====================================================
       MAIN ADD BUTTON
    ===================================================== */

    .digi-contact-add {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 9px;

        min-width: 190px;
        height: 50px;

        padding: 0 23px;

        border-radius: 14px;

        background:
                linear-gradient(
                        135deg,
                        #0081ff,
                        #0066ff
                );

        color: #fff !important;

        text-decoration: none !important;

        font-size: 13px;
        font-weight: 800;

        box-shadow:
                0 10px 25px rgba(0, 129, 255, .20);

        transition: .3s ease;
    }


    .digi-contact-add .plus {
        width: 27px;
        height: 27px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: rgba(255,255,255,.18);

        font-size: 19px;
    }


    .digi-contact-add:hover {
        transform: translateY(-3px);

        box-shadow:
                0 15px 32px rgba(0,129,255,.28);
    }


    /* =====================================================
       SECTION
    ===================================================== */

    .digi-contact-section {
        max-width: 1450px;

        margin: 0 auto 28px;
    }


    /* =====================================================
       SECTION HEADER
    ===================================================== */

    .digi-section-head {
        min-height: 66px;

        margin-bottom: 12px;

        padding: 0 4px;

        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .digi-section-title {
        display: flex;

        align-items: center;

        gap: 13px;
    }


    .digi-section-icon {
        width: 44px;
        height: 44px;

        flex-shrink: 0;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background:
                linear-gradient(
                        135deg,
                        #0081ff,
                        #6c5ce7
                );

        color: #fff;

        font-size: 16px;
        font-weight: 900;

        box-shadow:
                0 8px 20px rgba(0,129,255,.18);

        transition: .3s ease;
    }


    .digi-section-title:hover .digi-section-icon {
        transform: translateY(-2px) rotate(-3deg);

        box-shadow:
                0 12px 25px rgba(0,129,255,.25);
    }


    .digi-section-title h2 {
        margin: 0;

        color: #302a24;

        font-size: 18px;
        font-weight: 900;
    }


    .digi-section-title span {
        display: block;

        margin-top: 4px;

        color: #91867b;

        font-size: 11px;
    }


    /* =====================================================
       SECTION ADD BUTTON
    ===================================================== */

    .digi-section-add {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-height: 40px;

        padding: 0 16px;

        border: 1px solid #d7e8fa;

        border-radius: 11px;

        background: #edf6ff;

        color: #006dcc !important;

        text-decoration: none !important;

        font-size: 11px;
        font-weight: 800;

        transition: .25s ease;
    }


    .digi-section-add:hover {
        border-color: #0081ff;

        background: #0081ff;

        color: #fff !important;

        transform: translateY(-2px);

        box-shadow:
                0 8px 18px rgba(0,129,255,.20);
    }


    /* =====================================================
       CARD
    ===================================================== */

    .digi-contact-card {
        overflow: hidden;

        border: 1px solid #e9e1d7;

        border-radius: 22px;

        background: #fffdf9;

        box-shadow:
                0 20px 50px rgba(75,60,45,.08),
                0 3px 12px rgba(75,60,45,.04);

        transition: .3s ease;
    }


    .digi-contact-card:hover {
        box-shadow:
                0 25px 60px rgba(75,60,45,.11),
                0 5px 15px rgba(75,60,45,.05);
    }


    /* =====================================================
       TABLE
    ===================================================== */

    .digi-contact-table {
        width: 100%;

        border-collapse: separate !important;

        border-spacing: 0;

        border: none !important;

        text-align: center;

        color: #403a34;
    }


    /* =====================================================
       TABLE HEADER
    ===================================================== */

    .digi-contact-table thead th {
        height: 62px;

        padding: 0 18px;

        border: none !important;

        border-bottom:
                2px solid #e7dfd5 !important;

        background:
                linear-gradient(
                        180deg,
                        #faf7f2,
                        #f5f0e9
                );

        color: #82786d;

        font-size: 11px;
        font-weight: 900;
    }


    /* =====================================================
       TABLE BODY
    ===================================================== */

    .digi-contact-table tbody tr {
        background: #fffdf9;

        transition:
                background .25s ease,
                box-shadow .25s ease;
    }


    .digi-contact-table tbody tr:nth-child(even) {
        background: #fcfaf6;
    }


    .digi-contact-table tbody tr:not(:last-child) td {
        border-bottom:
                1px solid #e9e1d7 !important;
    }


    .digi-contact-table tbody tr:hover {
        background: #fff8ed;

        box-shadow:
                inset 4px 0 0 #0081ff,
                0 4px 16px rgba(120,90,50,.05);
    }


    /* =====================================================
       CELLS
    ===================================================== */

    .digi-contact-table tbody td {
        min-height: 88px;

        padding: 17px 20px;

        border-top: none !important;

        vertical-align: middle;
    }


    /* =====================================================
       NUMBER
    ===================================================== */

    .digi-contact-number {
        width: 36px;
        height: 36px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #f0ebe4;

        color: #756b61;

        font-size: 12px;
        font-weight: 900;

        transition: .25s ease;
    }


    .digi-contact-table tbody tr:hover .digi-contact-number {
        background: #ffe5bc;

        color: #c96a00;

        transform: scale(1.08);
    }


    /* =====================================================
       TITLE TEXT
    ===================================================== */

    .digi-contact-title-text {
        text-align: right;

        color: #302a24;

        font-size: 13px;
        font-weight: 800;

        line-height: 1.8;
    }


    /* =====================================================
       CONTENT
    ===================================================== */

    .digi-contact-content {
        max-width: 750px;

        margin: auto;

        color: #665e56;

        font-size: 12px;
        font-weight: 500;

        line-height: 2;

        text-align: right;

        white-space: normal;

        word-break: break-word;
    }


    /* =====================================================
       CONTACT VALUE
    ===================================================== */

    .digi-contact-value {
        display: inline-flex;

        align-items: center;

        min-height: 42px;

        padding: 9px 15px;

        border: 1px solid #e6ddd2;

        border-radius: 11px;

        background: #faf7f2;

        color: #403a34;

        font-size: 13px;
        font-weight: 700;

        transition: .25s ease;
    }


    .digi-contact-table tbody tr:hover .digi-contact-value {
        border-color: #cfe4fa;

        background: #edf6ff;

        color: #006dcc;

        transform: translateX(-2px);
    }


    /* =====================================================
       IMAGE
    ===================================================== */

    .digi-contact-image {
        width: 76px;
        height: 76px;

        display: block;

        margin: auto;

        padding: 5px;

        object-fit: contain;

        border: 1px solid #e7ded3;

        border-radius: 16px;

        background:
                linear-gradient(
                        145deg,
                        #fffefa,
                        #f5eee5
                );

        box-shadow:
                0 8px 20px rgba(75,60,45,.07);

        transition: .3s ease;
    }


    .digi-contact-table tbody tr:hover .digi-contact-image {
        border-color: #f3c987;

        box-shadow:
                0 12px 28px rgba(214,107,0,.12);

        transform: translateY(-3px) scale(1.03);
    }


    /* =====================================================
       NO IMAGE
    ===================================================== */

    .digi-contact-table td span[style] {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 90px;
        min-height: 38px;

        padding: 0 12px;

        border: 1px dashed #d9d0c6;

        border-radius: 10px;

        background: #faf7f2;

        color: #94887c !important;

        font-size: 10px !important;
    }


    /* =====================================================
       ACTIONS
    ===================================================== */

    .digi-contact-actions {
        display: flex;

        align-items: center;
        justify-content: center;

        gap: 8px;
    }


    .digi-contact-action {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 62px;
        height: 38px;

        padding: 0 13px;

        border-radius: 11px;

        text-decoration: none !important;

        font-size: 11px;
        font-weight: 800;

        transition: .25s ease;
    }


    /* =====================================================
       EDIT
    ===================================================== */

    .digi-contact-edit {
        border: 1px solid #cfe4fa;

        background: #edf6ff;

        color: #006dcc !important;
    }


    .digi-contact-edit:hover {
        border-color: #0081ff;

        background: #0081ff;

        color: #fff !important;

        transform: translateY(-2px);

        box-shadow:
                0 7px 17px rgba(0,129,255,.22);
    }


    /* =====================================================
       DELETE
    ===================================================== */

    .digi-contact-delete {
        border: 1px solid #efd8d2;

        background: #fff5f2;

        color: #d44b3e !important;
    }


    .digi-contact-delete:hover {
        border-color: #d44b3e;

        background: #d44b3e;

        color: #fff !important;

        transform: translateY(-2px);

        box-shadow:
                0 7px 17px rgba(212,75,62,.18);
    }


    /* =====================================================
       EMPTY TABLE
    ===================================================== */

    .digi-contact-table tbody:empty::after {
        content: "موردی برای نمایش وجود ندارد";

        display: block;

        padding: 45px;

        color: #968b7f;

        font-size: 12px;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 900px) {

        .digi-contact-page {
            padding: 25px 15px 50px;
        }


        .digi-contact-top {
            align-items: flex-start;

            flex-direction: column;
        }


        .digi-contact-add {
            width: 100%;
        }


        .digi-section-head {
            align-items: flex-start;

            flex-direction: column;

            padding: 0 2px;
        }


        .digi-section-add {
            width: 100%;
        }


        .digi-contact-card {
            overflow-x: auto;

            border-radius: 18px;
        }


        .digi-contact-table {
            min-width: 850px;
        }

    }


    @media (max-width: 500px) {

        .digi-contact-title h1 {
            font-size: 23px;
        }


        .digi-contact-title p {
            font-size: 11px;
        }


        .digi-section-title h2 {
            font-size: 16px;
        }


        .digi-section-title span {
            font-size: 10px;
        }

    }


    .digi-contact-table ::selection {
        background: #0081ff;

        color: #fff;
    }

</style>


<div class="content-wrapper digi-contact-page">


    <!-- =================================================
         HEADER
    ================================================= -->

    <div class="digi-contact-top">

        <div class="digi-contact-title">

            <h1>
                مدیریت تماس با ما
            </h1>

            <p>
                مدیریت پاراگراف‌ها، ساعت کاری، راه‌های ارتباطی و شماره‌های تماس
            </p>

        </div>


        <a href="add_tamas.php" class="digi-contact-add">
            <span class="plus">
                +
            </span>

            اضافه کردن پاراگراف
        </a>

    </div>



    <!-- =================================================
         PARAGRAPH
    ================================================= -->

    <div class="digi-contact-section">

        <div class="digi-section-head">

            <div class="digi-section-title">

                <div class="digi-section-icon">
                    P
                </div>

                <div>

                    <h2>
                        پاراگراف
                    </h2>

                    <span>
                        مدیریت متن صفحه تماس با ما
                    </span>

                </div>

            </div>

        </div>


        <div class="digi-contact-card">

            <table class="digi-contact-table">

                <thead>

                <tr>

                    <th style="width: 80px;">
                        تعداد
                    </th>

                    <th style="width: 250px;">
                        عنوان پاراگراف
                    </th>

                    <th>
                        متن پاراگراف
                    </th>

                    <th style="width: 200px;">
                        عملیات
                    </th>

                </tr>

                </thead>


                <tbody>

                <?php

                $z = 1;

                while ($row = mysqli_fetch_assoc($result)) {

                    ?>

                    <tr>

                        <td>

                            <span class="digi-contact-number">
                                <?php echo $z; ?>
                            </span>

                        </td>


                        <td>

                            <div class="digi-contact-title-text">

                                <?php

                                echo htmlspecialchars(
                                    $row['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </div>

                        </td>


                        <td>

                            <div class="digi-contact-content">

                                <?php

                                echo htmlspecialchars(
                                    $row['content'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </div>

                        </td>


                        <td>

                            <div class="digi-contact-actions">

                                <a
                                        href="edit_tamas.php?id=<?php echo $row['id']; ?>"
                                        class="digi-contact-action digi-contact-edit"
                                >
                                    ویرایش
                                </a>


                                <a
                                        href="del_tamas.php?id=<?php echo $row['id']; ?>"
                                        class="digi-contact-action digi-contact-delete"

                                        onclick="return confirm('آیا از حذف این مورد مطمئن هستید؟');"
                                >
                                    حذف
                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php

                    $z++;

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>



    <!-- =================================================
         WORKING HOURS
    ================================================= -->

    <div class="digi-contact-section">

        <div class="digi-section-head">

            <div class="digi-section-title">

                <div class="digi-section-icon">
                    ⏱
                </div>

                <div>

                    <h2>
                        ساعت کاری
                    </h2>

                    <span>
                        مدیریت تصویر و متن ساعت کاری
                    </span>

                </div>

            </div>


            <a
                    href="add_tamas_time_kar.php"
                    class="digi-contact-add"
            >
                + اضافه کردن
            </a>

        </div>


        <div class="digi-contact-card">

            <table class="digi-contact-table">

                <thead>

                <tr>

                    <th style="width: 80px;">
                        تعداد
                    </th>

                    <th style="width: 220px;">
                        تصویر
                    </th>

                    <th>
                        متن
                    </th>

                    <th style="width: 200px;">
                        عملیات
                    </th>

                </tr>

                </thead>


                <tbody>

                <?php

                $z = 1;

                while ($row2 = mysqli_fetch_assoc($result2)) {

                    ?>

                    <tr>

                        <td>

                            <span class="digi-contact-number">
                                <?php echo $z; ?>
                            </span>

                        </td>


                        <td>

                            <?php if (!empty($row2['image'])) { ?>

                                <img
                                        src="../up/<?php
                                        echo htmlspecialchars(
                                            $row2['image'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>"
                                        class="digi-contact-image"
                                        alt="تصویر ساعت کاری"
                                >

                            <?php } else { ?>

                                <span style="color:#98a2b3;">
                                    بدون تصویر
                                </span>

                            <?php } ?>

                        </td>


                        <td>

                            <div class="digi-contact-content">

                                <?php

                                echo htmlspecialchars(
                                    $row2['text'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </div>

                        </td>


                        <td>

                            <div class="digi-contact-actions">

                                <a
                                        href="edit_tamas_time_kar.php?id=<?php echo $row2['id']; ?>"
                                        class="digi-contact-action digi-contact-edit"
                                >
                                    ویرایش
                                </a>


                                <a
                                        href="del_tamas_time_kar.php?id=<?php echo $row2['id']; ?>"
                                        class="digi-contact-action digi-contact-delete"

                                        onclick="return confirm('آیا از حذف این مورد مطمئن هستید؟');"
                                >
                                    حذف
                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php

                    $z++;

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>



    <!-- =================================================
         CONTACT WAYS
    ================================================= -->

    <div class="digi-contact-section">

        <div class="digi-section-head">

            <div class="digi-section-title">

                <div class="digi-section-icon">
                    @
                </div>

                <div>

                    <h2>
                        راه‌های ارتباطی
                    </h2>

                    <span>
                        مدیریت آدرس‌های ارتباطی
                    </span>

                </div>

            </div>


            <a
                    href="add_tamas_a.php"
                    class="digi-contact-add"
            >
                + اضافه کردن آدرس
            </a>

        </div>


        <div class="digi-contact-card">

            <table class="digi-contact-table">

                <thead>

                <tr>

                    <th style="width: 80px;">
                        تعداد
                    </th>

                    <th>
                        آدرس
                    </th>

                    <th style="width: 200px;">
                        عملیات
                    </th>

                </tr>

                </thead>


                <tbody>

                <?php

                $z = 1;

                while ($row3 = mysqli_fetch_assoc($result3)) {

                    if (empty($row3['adress'])) {
                        continue;
                    }

                    ?>

                    <tr>

                        <td>

                            <span class="digi-contact-number">
                                <?php echo $z; ?>
                            </span>

                        </td>


                        <td>

                            <span class="digi-contact-value">

                                <?php

                                echo htmlspecialchars(
                                    $row3['adress'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </span>

                        </td>


                        <td>

                            <div class="digi-contact-actions">

                                <a
                                        href="edit_tamas_a.php?id=<?php echo $row3['id']; ?>"
                                        class="digi-contact-action digi-contact-edit"
                                >
                                    ویرایش
                                </a>


                                <a
                                        href="del_tamas_a.php?id=<?php echo $row3['id']; ?>"
                                        class="digi-contact-action digi-contact-delete"

                                        onclick="return confirm('آیا از حذف این مورد مطمئن هستید؟');"
                                >
                                    حذف
                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php

                    $z++;

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>



    <!-- =================================================
         PHONE
    ================================================= -->

    <div class="digi-contact-section">

        <div class="digi-section-head">

            <div class="digi-section-title">

                <div class="digi-section-icon">
                    ☎
                </div>

                <div>

                    <h2>
                        شماره تماس
                    </h2>

                    <span>
                        مدیریت شماره‌های تماس
                    </span>

                </div>

            </div>


            <a
                    href="add_tamas_n.php"
                    class="digi-contact-add"
            >
                + اضافه کردن شماره
            </a>

        </div>


        <div class="digi-contact-card">

            <table class="digi-contact-table">

                <thead>

                <tr>

                    <th style="width: 80px;">
                        تعداد
                    </th>

                    <th>
                        شماره
                    </th>

                    <th style="width: 200px;">
                        عملیات
                    </th>

                </tr>

                </thead>


                <tbody>

                <?php

                $z = 1;

                while ($row4 = mysqli_fetch_assoc($result4)) {

                    if (empty($row4['number'])) {
                        continue;
                    }

                    ?>

                    <tr>

                        <td>

                            <span class="digi-contact-number">
                                <?php echo $z; ?>
                            </span>

                        </td>


                        <td>

                            <span class="digi-contact-value">

                                <?php

                                echo htmlspecialchars(
                                    $row4['number'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </span>

                        </td>


                        <td>

                            <div class="digi-contact-actions">

                                <a
                                        href="edit_tamas_n.php?id=<?php echo $row4['id']; ?>"
                                        class="digi-contact-action digi-contact-edit"
                                >
                                    ویرایش
                                </a>


                                <a
                                        href="del_tamas_a.php?id=<?php echo $row4['id']; ?>"
                                        class="digi-contact-action digi-contact-delete"

                                        onclick="return confirm('آیا از حذف این مورد مطمئن هستید؟');"
                                >
                                    حذف
                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php

                    $z++;

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<?php include "footer.php"; ?>

