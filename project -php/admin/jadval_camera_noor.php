<?php
session_start();

require "../config/config.php";

$sql = "
    SELECT *
    FROM projects_noor
    WHERE id = project_id
    ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("خطا در دریافت محصولات: " . mysqli_error($conn));
}

?>

<?php
include "haeder.php";
?>

<style>

    /* =====================================================
       PAGE
    ===================================================== */

    .digi-products-page {
        min-height: 90vh;
        padding: 35px 30px 70px;

        background:
                radial-gradient(
                        circle at 90% 5%,
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

    .digi-products-top {
        max-width: 1450px;
        margin: 0 auto 25px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }

    .digi-products-title h1 {
        margin: 0;

        color: #302a24;

        font-size: 30px;
        font-weight: 900;

        letter-spacing: -1px;
    }

    .digi-products-title p {
        margin: 8px 0 0;

        color: #91867b;

        font-size: 13px;
    }


    /* =====================================================
       ADD BUTTON
    ===================================================== */

    .digi-add-product {
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

        color: white !important;

        text-decoration: none !important;

        font-size: 13px;
        font-weight: 800;

        box-shadow:
                0 10px 25px rgba(0,129,255,.20);

        transition: .3s ease;
    }

    .digi-add-product .plus {
        width: 27px;
        height: 27px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: rgba(255,255,255,.18);

        font-size: 19px;
    }

    .digi-add-product:hover {
        transform: translateY(-3px);

        box-shadow:
                0 15px 32px rgba(0,129,255,.28);
    }


    /* =====================================================
       TABLE CARD
    ===================================================== */

    .digi-table-card {
        max-width: 1450px;

        margin: auto;

        overflow: hidden;

        border: 1px solid #e9e1d7;

        border-radius: 24px;

        background: #fffdf9;

        box-shadow:
                0 22px 55px rgba(75,60,45,.09),
                0 3px 12px rgba(75,60,45,.04);
    }


    /* =====================================================
       TABLE
    ===================================================== */

    .digi-product-table {
        width: 100%;

        border-collapse: separate !important;
        border-spacing: 0;

        border: none !important;

        text-align: center;

        color: #403a34;
    }


    /* =====================================================
       HEADER
    ===================================================== */

    .digi-product-table thead th {
        height: 66px;

        padding: 0 20px;

        border: none !important;

        border-bottom: 2px solid #e7dfd5 !important;

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

    .digi-product-table thead th:first-child {
        width: 85px;
    }

    .digi-product-table thead th:nth-child(2) {
        text-align: right;
    }

    .digi-product-table thead th:nth-child(3) {
        width: 190px;
    }

    .digi-product-table thead th:nth-child(4) {
        width: 210px;
    }

    .digi-product-table thead th:last-child {
        width: 210px;
    }


    /* =====================================================
       ROW
    ===================================================== */

    .digi-product-table tbody tr {
        background: #fffdf9;

        transition:
                background .25s ease,
                box-shadow .25s ease;
    }

    .digi-product-table tbody tr:nth-child(even) {
        background: #fcfaf6;
    }


    /* =====================================================
       STRONG ROW SEPARATOR
    ===================================================== */

    .digi-product-table tbody tr:not(:last-child) td {
        border-bottom: 1px solid #e9e1d7 !important;
    }


    /*
       یک خط ظریف دوم برای جدا شدن بهتر ردیف‌ها
    */

    .digi-product-table tbody tr:not(:last-child) {
        box-shadow:
                0 1px 0 rgba(255,255,255,.9);
    }


    /* =====================================================
       HOVER
    ===================================================== */

    .digi-product-table tbody tr:hover {
        background: #fff8ed;

        box-shadow:
                inset 4px 0 0 #0081ff,
                0 4px 16px rgba(120,90,50,.06);
    }


    /* =====================================================
       CELLS
    ===================================================== */

    .digi-product-table tbody td {
        height: 120px;

        padding: 15px 20px;

        border-top: none !important;

        vertical-align: middle;
    }


    /* =====================================================
       NUMBER
    ===================================================== */

    .digi-number {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        width: 36px;
        height: 36px;

        border-radius: 11px;

        background: #f0ebe4;

        color: #756b61;

        font-size: 12px;
        font-weight: 900;

        transition: .25s ease;
    }

    .digi-product-table tbody tr:hover .digi-number {
        background: #ffe5bc;

        color: #c96a00;

        transform: scale(1.08);
    }


    /* =====================================================
       PRODUCT TITLE
    ===================================================== */

    .digi-product-title {
        text-align: right;
    }

    .digi-product-title .main-title {
        display: block;

        max-width: 440px;

        overflow: hidden;

        color: #302a24;

        font-size: 14px;
        font-weight: 800;

        line-height: 1.8;

        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .digi-product-title .product-label {
        display: inline-block;

        margin-top: 7px;

        padding: 4px 10px;

        border-radius: 7px;

        background: #eaf4ff;

        color: #0073e6;

        font-size: 9px;
        font-weight: 800;
    }


    /* =====================================================
       IMAGE
    ===================================================== */

    .digi-image-box {
        position: relative;

        display: flex;

        align-items: center;
        justify-content: center;

        width: 82px;
        height: 82px;

        margin: auto;

        border: 1px solid #e7ded3;

        border-radius: 17px;

        background:
                linear-gradient(
                        145deg,
                        #fffefa,
                        #f5eee5
                );

        box-shadow:
                0 8px 20px rgba(75,60,45,.07);

        overflow: hidden;

        transition: .3s ease;
    }

    .digi-image-box::after {
        content: "";

        position: absolute;

        width: 48px;
        height: 48px;

        right: -23px;
        top: -23px;

        border-radius: 50%;

        background:
                rgba(0,129,255,.07);
    }

    .digi-image-box img {
        position: relative;

        z-index: 2;

        width: 72px !important;
        height: 72px;

        object-fit: contain;

        transition: .35s ease;
    }

    .digi-product-table tbody tr:hover .digi-image-box {
        border-color: #f3c987;

        box-shadow:
                0 12px 28px rgba(214,107,0,.12);

        transform: translateY(-3px);
    }

    .digi-product-table tbody tr:hover .digi-image-box img {
        transform: scale(1.08);
    }


    /* =====================================================
       NO IMAGE
    ===================================================== */

    .digi-no-image {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        width: 82px;
        height: 82px;

        border: 1px dashed #d9d0c6;

        border-radius: 17px;

        background: #faf7f2;

        color: #94887c;

        font-size: 11px;
        font-weight: 700;
    }


    /* =====================================================
       PRICE
    ===================================================== */

    .digi-price {
        display: inline-flex;

        align-items: baseline;

        gap: 6px;

        direction: rtl;
    }

    .digi-price-number {
        color: #29231e;

        font-size: 16px;
        font-weight: 900;
    }

    .digi-price-unit {
        color: #8e8378;

        font-size: 10px;
        font-weight: 700;
    }


    /* =====================================================
       ACTIONS
    ===================================================== */

    .digi-actions {
        display: flex;

        justify-content: center;
        align-items: center;

        gap: 9px;
    }

    .digi-action {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        height: 38px;

        padding: 0 14px;

        border-radius: 11px;

        text-decoration: none !important;

        font-size: 11px;
        font-weight: 800;

        transition: .25s ease;
    }


    /* =====================================================
       EDIT
    ===================================================== */

    .digi-action-edit {
        border: 1px solid #cfe4fa;

        background: #edf6ff;

        color: #006dcc !important;
    }

    .digi-action-edit:hover {
        border-color: #0081ff;

        background: #0081ff;

        color: white !important;

        transform: translateY(-2px);

        box-shadow:
                0 7px 17px rgba(0,129,255,.22);
    }


    /* =====================================================
       DELETE
    ===================================================== */

    .digi-action-delete {
        border: 1px solid #efd8d2;

        background: #fff5f2;

        color: #d44b3e !important;
    }

    .digi-action-delete:hover {
        border-color: #d44b3e;

        background: #d44b3e;

        color: white !important;

        transform: translateY(-2px);

        box-shadow:
                0 7px 17px rgba(212,75,62,.18);
    }


    /* =====================================================
       EMPTY
    ===================================================== */

    .digi-empty {
        padding: 55px !important;

        color: #968b7f;

        font-size: 13px;
    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 900px) {

        .digi-products-page {
            padding: 25px 15px 50px;
        }

        .digi-products-top {
            align-items: flex-start;

            flex-direction: column;
        }

        .digi-add-product {
            width: 100%;
        }

        .digi-table-card {
            overflow-x: auto;

            border-radius: 18px;
        }

        .digi-product-table {
            min-width: 950px;
        }

    }


    /* =====================================================
       SMALL MOBILE
    ===================================================== */

    @media (max-width: 500px) {

        .digi-products-title h1 {
            font-size: 23px;
        }

        .digi-products-title p {
            font-size: 11px;
        }

    }


    /* =====================================================
       SELECTION
    ===================================================== */

    .digi-product-table ::selection {
        background: #0081ff;
        color: white;
    }

</style>


<div class="content-wrapper digi-products-page">


    <!-- =================================================
         TOP
    ================================================= -->

    <div class="digi-products-top">

        <div class="digi-products-title">

            <h1>
                جدول دوربین های عکاسی
            </h1>

            <p>
                مدیریت، ویرایش و حذف محصولات فروشگاه
            </p>

        </div>


        <a
                href="add_camera_noor.php"
                class="digi-add-product"
        >

            <span class="plus">
                +
            </span>

            اضافه کردن محصول

        </a>

    </div>


    <!-- =================================================
         TABLE
    ================================================= -->

    <div class="digi-table-card">

        <table class="digi-product-table">

            <thead>

            <tr>

                <th>
                    شماره
                </th>

                <th>
                    عنوان محصول
                </th>

                <th>
                    تصویر
                </th>

                <th>
                    قیمت
                </th>

                <th>
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


                    <!-- شماره -->

                    <td>

                        <span class="digi-number">

                            <?php

                            echo $z;

                            ?>

                        </span>

                    </td>


                    <!-- عنوان -->

                    <td>

                        <div class="digi-product-title">

                            <span class="main-title">

                                <?php

                                echo htmlspecialchars(
                                    $row['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </span>


                            <span class="product-label">
                                محصول
                            </span>

                        </div>

                    </td>


                    <!-- تصویر -->

                    <td>

                        <?php

                        if (!empty($row['image1'])) {

                            ?>

                            <div class="digi-image-box">

                                <img
                                        src="../up/<?php

                                        echo htmlspecialchars(
                                            $row['image1'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                        ?>"
                                        alt="تصویر محصول"
                                >

                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="digi-no-image">
                                بدون تصویر
                            </div>

                            <?php

                        }

                        ?>

                    </td>


                    <!-- قیمت -->

                    <td>

                        <div class="digi-price">

                            <span class="digi-price-number">

                                <?php

                                echo number_format(
                                    (int)$row['price']
                                );

                                ?>

                            </span>

                            <span class="digi-price-unit">
                                تومان
                            </span>

                        </div>

                    </td>


                    <!-- عملیات -->

                    <td>

                        <div class="digi-actions">

                            <a
                                    href="edit_camera_noor.php?id=<?php echo $row['id']; ?>"
                                    class="digi-action digi-action-edit"
                            >
                                ویرایش
                            </a>


                            <a
                                    href="del_camera_noor.php?id=<?php echo $row['id']; ?>"
                                    class="digi-action digi-action-delete"

                                    onclick="
                                    return confirm(
                                        'آیا از حذف این محصول مطمئن هستید؟'
                                    );
                                "
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


<?php

include "footer.php";

?>

