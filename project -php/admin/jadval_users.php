<?php

session_start();


// ==================================================
// فقط ادمین اجازه ورود دارد
// ==================================================

if (
    !isset($_SESSION['id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {

    header("Location: ../login-register/login.php");
    exit;

}


require "../config/config.php";


// ==================================================
// حذف کاربر
// ==================================================

if (isset($_POST['delete_user'])) {

    $user_id = intval($_POST['user_id']);


    if ($user_id <= 0) {

        die("شناسه کاربر نامعتبر است.");

    }


    // ==================================================
    // جلوگیری از حذف خود ادمین
    // ==================================================

    if ($user_id == $_SESSION['id']) {

        die("شما نمی‌توانید حساب خودتان را حذف کنید.");

    }


    // ==================================================
    // دریافت اطلاعات کاربر
    // ==================================================

    $sql_user = "
        SELECT
            profile_image,
            role
        FROM login_register2
        WHERE id = $user_id
        LIMIT 1
    ";


    $result_user = mysqli_query(
        $conn,
        $sql_user
    );


    if (!$result_user) {

        die(
            "خطا در دریافت اطلاعات کاربر: "
            . mysqli_error($conn)
        );

    }


    $user_row = mysqli_fetch_assoc(
        $result_user
    );


    if (!$user_row) {

        die("کاربر موردنظر پیدا نشد.");

    }


    $profile_image = $user_row['profile_image'] ?? '';


// ==================================================
// حذف کاربر
// ==================================================

    $sql_delete = "
        DELETE FROM login_register2
        WHERE id = $user_id
    ";


    $delete_result = mysqli_query(
        $conn,
        $sql_delete
    );


    if (!$delete_result) {

        die(
            "خطا در حذف کاربر: "
            . mysqli_error($conn)
        );

    }


// ==================================================
// حذف تصویر پروفایل
// ==================================================

    if (!empty($profile_image)) {

        $image_path =
            "../uploads/"
            . basename($profile_image);


        if (file_exists($image_path)) {

            unlink($image_path);

        }

    }


// ==================================================
// انتقال
// ==================================================

    header(
        "Location: jadval_users.php?deleted=1"
    );

    exit;

}


// ==================================================
// دریافت کاربران
// ==================================================

$sql = "
    SELECT
        id,
        first_name,
        last_name,
        username,
        role,
        profile_image
    FROM login_register2
    WHERE role = 'user'
    ORDER BY id DESC
";

$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    die(
        "خطا در دریافت کاربران: "
        . mysqli_error($conn)
    );

}

?>


<?php

include "haeder.php";

?>


<style>


    /* =====================================================
       PAGE
    ===================================================== */

    .digi-users-page {

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

    .digi-users-top {

        max-width: 1450px;

        margin: 0 auto 25px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

    }


    .digi-users-title h1 {

        margin: 0;

        color: #302a24;

        font-size: 30px;

        font-weight: 900;

        letter-spacing: -1px;

    }


    .digi-users-title p {

        margin: 8px 0 0;

        color: #91867b;

        font-size: 13px;

    }


    /* =====================================================
       USER COUNT
    ===================================================== */

    .digi-users-count {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 150px;

        height: 48px;

        padding: 0 18px;

        border-radius: 13px;

        background: #eaf4ff;

        color: #0073e6;

        font-size: 13px;

        font-weight: 900;

    }


    /* =====================================================
       TABLE CARD
    ===================================================== */

    .digi-users-table-card {

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

    .digi-users-table {

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

    .digi-users-table thead th {

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


    .digi-users-table thead th:first-child {

        width: 80px;

    }


    .digi-users-table thead th:nth-child(2) {

        text-align: right;

    }


    .digi-users-table thead th:nth-child(3) {

        width: 220px;

    }


    .digi-users-table thead th:nth-child(4) {

        width: 150px;

    }


    .digi-users-table thead th:nth-child(5) {

        width: 170px;

    }


    .digi-users-table thead th:last-child {

        width: 220px;

    }


    /* =====================================================
       ROW
    ===================================================== */

    .digi-users-table tbody tr {

        background: #fffdf9;

        transition:
                background .25s ease,
                box-shadow .25s ease;

    }


    .digi-users-table tbody tr:nth-child(even) {

        background: #fcfaf6;

    }


    /* =====================================================
       SEPARATOR
    ===================================================== */

    .digi-users-table tbody tr:not(:last-child) td {

        border-bottom: 1px solid #e9e1d7 !important;

    }


    .digi-users-table tbody tr:not(:last-child) {

        box-shadow:
                0 1px 0 rgba(255,255,255,.9);

    }


    /* =====================================================
       HOVER
    ===================================================== */

    .digi-users-table tbody tr:hover {

        background: #fff8ed;

        box-shadow:
                inset 4px 0 0 #0081ff,
                0 4px 16px rgba(120,90,50,.06);

    }


    /* =====================================================
       CELLS
    ===================================================== */

    .digi-users-table tbody td {

        height: 105px;

        padding: 15px 20px;

        border-top: none !important;

        vertical-align: middle;

    }


    /* =====================================================
       NUMBER
    ===================================================== */

    .digi-user-number {

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


    .digi-users-table tbody tr:hover .digi-user-number {

        background: #ffe5bc;

        color: #c96a00;

        transform: scale(1.08);

    }


    /* =====================================================
       USER INFO
    ===================================================== */

    .digi-user-info {

        display: flex;

        align-items: center;

        justify-content: flex-start;

        gap: 14px;

        text-align: right;

    }


    .digi-user-name {

        display: block;

        color: #302a24;

        font-size: 14px;

        font-weight: 900;

        line-height: 1.8;

    }


    .digi-user-label {

        display: inline-block;

        margin-top: 5px;

        padding: 4px 9px;

        border-radius: 7px;

        background: #eaf4ff;

        color: #0073e6;

        font-size: 9px;

        font-weight: 800;

    }


    /* =====================================================
       USERNAME
    ===================================================== */

    .digi-username {

        color: #5f574f;

        font-size: 13px;

        font-weight: 700;

        direction: ltr;

    }


    /* =====================================================
       PROFILE IMAGE
    ===================================================== */

    .digi-user-image-box {

        position: relative;

        display: flex;

        align-items: center;

        justify-content: center;

        width: 65px;

        height: 65px;

        margin: auto;

        border: 1px solid #e7ded3;

        border-radius: 50%;

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


    .digi-user-image-box img {

        width: 100%;

        height: 100%;

        object-fit: cover;

    }


    .digi-users-table tbody tr:hover
    .digi-user-image-box {

        border-color: #f3c987;

        box-shadow:
                0 12px 28px rgba(214,107,0,.12);

        transform: translateY(-3px);

    }


    /* =====================================================
       NO IMAGE
    ===================================================== */

    .digi-user-no-image {

        display: flex;

        align-items: center;

        justify-content: center;

        width: 65px;

        height: 65px;

        margin: auto;

        border: 1px dashed #d9d0c6;

        border-radius: 50%;

        background: #faf7f2;

        color: #94887c;

        font-size: 10px;

        font-weight: 700;

    }


    /* =====================================================
       ROLE
    ===================================================== */

    .digi-user-role {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 85px;

        height: 34px;

        padding: 0 12px;

        border-radius: 10px;

        font-size: 11px;

        font-weight: 900;

    }


    .digi-user-role-admin {

        border: 1px solid #d8c9f3;

        background: #f3edff;

        color: #6f42c1;

    }


    .digi-user-role-user {

        border: 1px solid #cfe4fa;

        background: #edf6ff;

        color: #006dcc;

    }


    /* =====================================================
       ACTIONS
    ===================================================== */

    .digi-user-actions {

        display: flex;

        justify-content: center;

        align-items: center;

        gap: 9px;

        flex-wrap: wrap;

    }


    .digi-user-action {

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
       VIEW
    ===================================================== */

    .digi-user-action-view {

        border: 1px solid #ded8d1;

        background: #f7f4ef;

        color: #665d54 !important;

    }


    .digi-user-action-view:hover {

        border-color: #665d54;

        background: #665d54;

        color: white !important;

        transform: translateY(-2px);

    }


    /* =====================================================
       DELETE
    ===================================================== */

    .digi-user-action-delete {

        border: 1px solid #efd8d2;

        background: #fff5f2;

        color: #d44b3e !important;

    }


    .digi-user-action-delete:hover {

        border-color: #d44b3e;

        background: #d44b3e;

        color: white !important;

        transform: translateY(-2px);

        box-shadow:
                0 7px 17px rgba(212,75,62,.18);

    }


    /* =====================================================
       SUCCESS MESSAGE
    ===================================================== */

    .digi-user-success {

        max-width: 1450px;

        margin: 0 auto 20px;

        padding: 15px 20px;

        border: 1px solid #cfe8d5;

        border-radius: 13px;

        background: #effaf1;

        color: #27813b;

        font-size: 13px;

        font-weight: 800;

    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 900px) {

        .digi-users-page {

            padding: 25px 15px 50px;

        }


        .digi-users-top {

            align-items: flex-start;

            flex-direction: column;

        }


        .digi-users-table-card {

            overflow-x: auto;

            border-radius: 18px;

        }


        .digi-users-table {

            min-width: 1000px;

        }

    }


    /* =====================================================
       SMALL MOBILE
    ===================================================== */

    @media (max-width: 500px) {

        .digi-users-title h1 {

            font-size: 23px;

        }


        .digi-users-title p {

            font-size: 11px;

        }

    }


</style>


<div class="content-wrapper digi-users-page">


    <!-- =================================================
         TOP
    ================================================= -->

    <div class="digi-users-top">


        <div class="digi-users-title">

            <h1>
                کاربران
            </h1>

            <p>
                مدیریت، مشاهده و حذف کاربران سایت
            </p>

        </div>


        <div class="digi-users-count">

            تعداد کاربران:

            <?php

            echo mysqli_num_rows($result);

            ?>

        </div>


    </div>


    <!-- =================================================
         SUCCESS
    ================================================= -->

    <?php

    if (
        isset($_GET['deleted']) &&
        $_GET['deleted'] == 1
    ) {

        ?>

        <div class="digi-user-success">

            کاربر با موفقیت حذف شد.

        </div>

        <?php

    }

    ?>


    <!-- =================================================
         TABLE
    ================================================= -->

    <div class="digi-users-table-card">


        <table class="digi-users-table">


            <thead>

            <tr>

                <th>
                    شماره
                </th>

                <th>
                    نام و نام خانوادگی
                </th>

                <th>
                    نام کاربری
                </th>

                <th>
                    نقش
                </th>

                <th>
                    تصویر
                </th>

                <th>
                    عملیات
                </th>

            </tr>

            </thead>


            <tbody>


            <?php

            $z = 1;


            while (
            $row = mysqli_fetch_assoc($result)
            ) {

                ?>


                <tr>


                    <!-- =================================================
                         NUMBER
                    ================================================= -->

                    <td>

                        <span class="digi-user-number">

                            <?php

                            echo $z;

                            ?>

                        </span>

                    </td>


                    <!-- =================================================
                         NAME
                    ================================================= -->

                    <td>

                        <div class="digi-user-info">


                            <div>

                                <span class="digi-user-name">

                                    <?php

                                    $full_name =
                                        trim(
                                            ($row['first_name'] ?? '')
                                            . ' '
                                            . ($row['last_name'] ?? '')
                                        );


                                    echo htmlspecialchars(
                                        $full_name ?: 'بدون نام',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );

                                    ?>

                                </span>


                                <span class="digi-user-label">

                                    کاربر سایت

                                </span>

                            </div>


                        </div>

                    </td>


                    <!-- =================================================
                         USERNAME
                    ================================================= -->

                    <td>

                        <span class="digi-username">

                            <?php

                            echo htmlspecialchars(
                                $row['username'],
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </span>

                    </td>


                    <!-- =================================================
                         ROLE
                    ================================================= -->

                    <td>


                        <?php

                        if ($row['role'] === 'admin') {

                            ?>

                            <span
                                    class="digi-user-role digi-user-role-admin"
                            >

                                مدیر

                            </span>

                            <?php

                        } else {

                            ?>

                            <span
                                    class="digi-user-role digi-user-role-user"
                            >

                                کاربر

                            </span>

                            <?php

                        }

                        ?>

                    </td>


                    <!-- =================================================
                         PROFILE IMAGE
                    ================================================= -->

                    <td>


                        <?php

                        if (!empty($row['profile_image'])) {

                            $image_path =
                                "../uploads/profile/"
                                . basename(
                                    $row['profile_image']
                                );

                            ?>

                            <div class="digi-user-image-box">

                                <img
                                        src="<?php echo htmlspecialchars(
                                            $image_path,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
                                        alt="تصویر پروفایل"
                                >

                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="digi-user-no-image">

                                بدون عکس

                            </div>

                            <?php

                        }

                        ?>

                    </td>


                    <!-- =================================================
                         ACTIONS
                    ================================================= -->

                    <td>

                        <div class="digi-user-actions">


                            <!-- مشاهده -->

<!--                            <a-->
<!--                                    href="user_view.php?id=--><?php //echo $row['id']; ?><!--"-->
<!--                                    class="digi-user-action digi-user-action-view"-->
<!--                            >-->
<!---->
<!--                                مشاهده-->
<!---->
<!--                            </a>-->


                            <!-- حذف -->

                            <?php

                            if ($row['id'] != $_SESSION['id']) {

                                ?>

                                <form
                                        method="POST"
                                        style="display: inline;"
                                >

                                    <input
                                            type="hidden"
                                            name="user_id"
                                            value="<?php echo $row['id']; ?>"
                                    >


                                    <button
                                            type="submit"
                                            name="delete_user"
                                            class="digi-user-action digi-user-action-delete"
                                            onclick="
                                            return confirm(
                                                'آیا از حذف این کاربر مطمئن هستید؟'
                                            );
                                        "
                                            style="cursor: pointer;"
                                    >

                                        حذف

                                    </button>

                                </form>

                                <?php

                            }

                            ?>


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
