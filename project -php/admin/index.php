<?php

session_start();


// ==================================================
// بررسی دسترسی ادمین
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
// تعداد محصولات
// ==================================================

$product_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM projects"
);

$product_data = mysqli_fetch_assoc($product_query);

$total_products = $product_data['total'];


// ==================================================
// تعداد کاربران
// فقط کاربران عادی
// ==================================================

$user_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM login_register2
     WHERE role = 'user'"
);

$user_data = mysqli_fetch_assoc($user_query);

$total_users = $user_data['total'];


// ==================================================
// تعداد مقالات
// ==================================================

$article_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM maghale_dakhel"
);

$article_data = mysqli_fetch_assoc($article_query);

$total_articles = $article_data['total'];


// ==================================================
// تعداد ادمین‌ها
// ==================================================

$admin_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM login_register2
     WHERE role = 'admin'"
);

$admin_data = mysqli_fetch_assoc($admin_query);

$total_admins = $admin_data['total'];

?>


<?php

include "haeder.php";

?>


<style>


    /* =====================================================
       DASHBOARD PAGE
    ===================================================== */

    .digi-dashboard-page {

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

    }


    /* =====================================================
       TOP
    ===================================================== */

    .digi-dashboard-top {

        max-width: 1450px;

        margin: 0 auto 25px;

    }


    .digi-dashboard-title {

        margin: 0;

        color: #302a24;

        font-size: 30px;

        font-weight: 900;

        letter-spacing: -1px;

    }


    .digi-dashboard-subtitle {

        margin: 8px 0 0;

        color: #91867b;

        font-size: 13px;

    }


    /* =====================================================
       WELCOME
    ===================================================== */

    .digi-dashboard-welcome {

        max-width: 1450px;

        margin: 0 auto 25px;

        padding: 25px 28px;

        border: 1px solid #d9e9f8;

        border-radius: 20px;

        background:
                linear-gradient(
                        135deg,
                        #f8fcff,
                        #edf6ff
                );

        box-shadow:
                0 15px 35px rgba(75,60,45,.07);

    }


    .digi-dashboard-welcome h3 {

        margin: 0 0 8px;

        color: #302a24;

        font-size: 20px;

        font-weight: 900;

    }


    .digi-dashboard-welcome p {

        margin: 0;

        color: #756b61;

        font-size: 13px;

        line-height: 2;

    }


    /* =====================================================
       STATISTICS
    ===================================================== */

    .digi-dashboard-stat {

        position: relative;

        height: 100%;

        margin-bottom: 20px;

        padding: 22px;

        border: 1px solid #e9e1d7;

        border-radius: 20px;

        background: #fffdf9;

        box-shadow:
                0 15px 35px rgba(75,60,45,.07),
                0 3px 12px rgba(75,60,45,.03);

        overflow: hidden;

        transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;

    }


    .digi-dashboard-stat:hover {

        transform: translateY(-4px);

        border-color: #d7c9b9;

        box-shadow:
                0 20px 40px rgba(75,60,45,.11);

    }


    .digi-dashboard-stat::before {

        content: "";

        position: absolute;

        top: 0;

        right: 0;

        width: 100%;

        height: 4px;

        background: #0081ff;

    }


    .digi-dashboard-stat-products::before {

        background: #0081ff;

    }


    .digi-dashboard-stat-users::before {

        background: #2f9e44;

    }


    .digi-dashboard-stat-articles::before {

        background: #d88919;

    }


    .digi-dashboard-stat-admins::before {

        background: #7652b8;

    }


    .digi-dashboard-stat-inner {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

    }


    .digi-dashboard-stat-title {

        margin: 0 0 7px;

        color: #91867b;

        font-size: 12px;

        font-weight: 800;

    }


    .digi-dashboard-stat-number {

        margin: 0;

        color: #302a24;

        font-size: 29px;

        font-weight: 900;

        line-height: 1.2;

    }


    .digi-dashboard-stat-icon {

        display: flex;

        align-items: center;

        justify-content: center;

        flex-shrink: 0;

        width: 58px;

        height: 58px;

        border: 1px solid #e7ded3;

        border-radius: 17px;

        background: #faf7f2;

        font-size: 25px;

        transition: .25s ease;

    }


    .digi-dashboard-stat:hover
    .digi-dashboard-stat-icon {

        transform: scale(1.07);

    }


    .digi-dashboard-stat-products
    .digi-dashboard-stat-icon {

        border-color: #cfe4fa;

        background: #edf6ff;

    }


    .digi-dashboard-stat-users
    .digi-dashboard-stat-icon {

        border-color: #cfe8d5;

        background: #effaf1;

    }


    .digi-dashboard-stat-articles
    .digi-dashboard-stat-icon {

        border-color: #f1dfbc;

        background: #fff7e8;

    }


    .digi-dashboard-stat-admins
    .digi-dashboard-stat-icon {

        border-color: #ded3f2;

        background: #f5f0ff;

    }


    /* =====================================================
       SECTION CARD
    ===================================================== */

    .digi-dashboard-card {

        max-width: 1450px;

        margin: 0 auto 25px;

        padding: 25px;

        border: 1px solid #e9e1d7;

        border-radius: 24px;

        background: #fffdf9;

        box-shadow:
                0 22px 55px rgba(75,60,45,.09),
                0 3px 12px rgba(75,60,45,.04);

    }


    .digi-dashboard-card-title {

        margin: 0;

        color: #302a24;

        font-size: 18px;

        font-weight: 900;

    }


    .digi-dashboard-card-subtitle {

        margin: 7px 0 22px;

        color: #91867b;

        font-size: 12px;

    }


    /* =====================================================
       QUICK ACCESS
    ===================================================== */

    .digi-dashboard-quick {

        display: block;

        height: 100%;

        padding: 20px;

        border: 1px solid #e9e1d7;

        border-radius: 17px;

        background: #fffdf9;

        color: #302a24 !important;

        text-decoration: none !important;

        transition:
                transform .25s ease,
                background .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;

    }


    .digi-dashboard-quick:hover {

        transform: translateY(-4px);

        border-color: #cfe4fa;

        background: #f8fcff;

        box-shadow:
                0 12px 25px rgba(75,60,45,.08);

    }


    .digi-dashboard-quick-icon {

        display: flex;

        align-items: center;

        justify-content: center;

        width: 52px;

        height: 52px;

        margin-bottom: 14px;

        border: 1px solid #dfe8f0;

        border-radius: 15px;

        background: #edf6ff;

        font-size: 23px;

    }


    .digi-dashboard-quick-title {

        display: block;

        color: #302a24;

        font-size: 13px;

        font-weight: 900;

    }


    .digi-dashboard-quick-text {

        margin: 7px 0 0;

        color: #91867b;

        font-size: 11px;

        line-height: 1.9;

    }


    /* =====================================================
       ACCOUNT CARD
    ===================================================== */

    .digi-dashboard-account {

        height: 100%;

        padding: 25px;

        border: 1px solid #e9e1d7;

        border-radius: 20px;

        background: #fffdf9;

        box-shadow:
                0 15px 35px rgba(75,60,45,.07);

    }


    .digi-dashboard-account-title {

        margin: 0 0 20px;

        color: #302a24;

        font-size: 16px;

        font-weight: 900;

    }


    .digi-dashboard-account-user {

        display: flex;

        align-items: center;

        gap: 15px;

    }


    .digi-dashboard-account-icon {

        display: flex;

        align-items: center;

        justify-content: center;

        width: 58px;

        height: 58px;

        flex-shrink: 0;

        border: 1px solid #cfe4fa;

        border-radius: 50%;

        background: #edf6ff;

        font-size: 24px;

    }


    .digi-dashboard-account-name {

        color: #302a24;

        font-size: 14px;

        font-weight: 900;

    }


    .digi-dashboard-account-role {

        margin: 5px 0 0;

        color: #27813b;

        font-size: 11px;

        font-weight: 800;

    }


    /* =====================================================
       BUTTONS
    ===================================================== */

    .digi-dashboard-btn {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        height: 40px;

        padding: 0 16px;

        margin-left: 7px;

        border-radius: 11px;

        text-decoration: none !important;

        font-size: 11px;

        font-weight: 800;

        transition: .25s ease;

    }


    .digi-dashboard-btn-site {

        border: 1px solid #cfe4fa;

        background: #edf6ff;

        color: #0073e6 !important;

    }


    .digi-dashboard-btn-site:hover {

        border-color: #0073e6;

        background: #0073e6;

        color: white !important;

        transform: translateY(-2px);

        box-shadow:
                0 7px 17px rgba(0,115,230,.16);

    }


    .digi-dashboard-btn-logout {

        border: 1px solid #efd8d2;

        background: #fff5f2;

        color: #d44b3e !important;

    }


    .digi-dashboard-btn-logout:hover {

        border-color: #d44b3e;

        background: #d44b3e;

        color: white !important;

        transform: translateY(-2px);

        box-shadow:
                0 7px 17px rgba(212,75,62,.18);

    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 900px) {

        .digi-dashboard-page {

            padding: 25px 15px 50px;

        }


        .digi-dashboard-title {

            font-size: 24px;

        }


        .digi-dashboard-card {

            padding: 20px;

            border-radius: 18px;

        }

    }


    @media (max-width: 500px) {

        .digi-dashboard-title {

            font-size: 22px;

        }


        .digi-dashboard-subtitle {

            font-size: 11px;

        }


        .digi-dashboard-stat {

            padding: 18px;

        }


        .digi-dashboard-stat-number {

            font-size: 25px;

        }


        .digi-dashboard-welcome {

            padding: 20px;

        }

    }


</style>


<div class="content-wrapper digi-dashboard-page">


    <!-- =================================================
         TOP
    ================================================= -->

    <div class="digi-dashboard-top">

        <h1 class="digi-dashboard-title">
            داشبورد
        </h1>

        <p class="digi-dashboard-subtitle">
            مدیریت و کنترل بخش‌های مختلف سایت DIGI24
        </p>

    </div>


    <!-- =================================================
         WELCOME
    ================================================= -->

    <div class="digi-dashboard-welcome">

        <h3>
            خوش آمدید 👋
        </h3>

        <p>
            به پنل مدیریت DIGI24 خوش آمدید.
            از این قسمت می‌توانید بخش‌های مختلف سایت را مدیریت کنید.
        </p>

    </div>


    <!-- =================================================
         STATISTICS
    ================================================= -->

    <div class="row">


        <!-- محصولات -->

        <div class="col-lg-3 col-md-6">

            <div class="digi-dashboard-stat digi-dashboard-stat-products">

                <div class="digi-dashboard-stat-inner">

                    <div>

                        <p class="digi-dashboard-stat-title">
                            محصولات
                        </p>

                        <h2 class="digi-dashboard-stat-number">
                            <?php echo $total_products; ?>
                        </h2>

                    </div>

                    <div class="digi-dashboard-stat-icon">
                        📦
                    </div>

                </div>

            </div>

        </div>


        <!-- کاربران -->

        <div class="col-lg-3 col-md-6">

            <div class="digi-dashboard-stat digi-dashboard-stat-users">

                <div class="digi-dashboard-stat-inner">

                    <div>

                        <p class="digi-dashboard-stat-title">
                            کاربران
                        </p>

                        <h2 class="digi-dashboard-stat-number">
                            <?php echo $total_users; ?>
                        </h2>

                    </div>

                    <div class="digi-dashboard-stat-icon">
                        👤
                    </div>

                </div>

            </div>

        </div>


        <!-- مقالات -->

        <div class="col-lg-3 col-md-6">

            <div class="digi-dashboard-stat digi-dashboard-stat-articles">

                <div class="digi-dashboard-stat-inner">

                    <div>

                        <p class="digi-dashboard-stat-title">
                            مقالات
                        </p>

                        <h2 class="digi-dashboard-stat-number">
                            <?php echo $total_articles; ?>
                        </h2>

                    </div>

                    <div class="digi-dashboard-stat-icon">
                        📝
                    </div>

                </div>

            </div>

        </div>


        <!-- مدیران -->

        <div class="col-lg-3 col-md-6">

            <div class="digi-dashboard-stat digi-dashboard-stat-admins">

                <div class="digi-dashboard-stat-inner">

                    <div>

                        <p class="digi-dashboard-stat-title">
                            مدیران
                        </p>

                        <h2 class="digi-dashboard-stat-number">
                            <?php echo $total_admins; ?>
                        </h2>

                    </div>

                    <div class="digi-dashboard-stat-icon">
                        🛡️
                    </div>

                </div>

            </div>

        </div>

    </div>

    <br>
    <!-- =================================================
         QUICK ACCESS
    ================================================= -->

    <div class="digi-dashboard-card">

        <h4 class="digi-dashboard-card-title">
            دسترسی سریع
        </h4>

        <p class="digi-dashboard-card-subtitle">
            دسترسی سریع به بخش‌های اصلی پنل مدیریت
        </p>


        <div class="row">


            <!-- =================================================
                 مدیریت محصولات
            ================================================= -->

<!--            <div class="col-lg-3 col-md-6 mb-3">-->
<!---->
<!--                <a-->
<!--                        href="jadval_camera_ax.php"-->
<!--                        class="digi-dashboard-quick"-->
<!--                >-->
<!---->
<!--                    <div class="digi-dashboard-quick-icon">-->
<!--                        📦-->
<!--                    </div>-->
<!---->
<!--                    <span class="digi-dashboard-quick-title">-->
<!--                    مدیریت محصولات-->
<!--                </span>-->
<!---->
<!--                    <p class="digi-dashboard-quick-text">-->
<!--                        مشاهده، ویرایش و حذف محصولات-->
<!--                    </p>-->
<!---->
<!--                </a>-->
<!---->
<!--            </div>-->


            <!-- =================================================
                 مدیریت کاربران
            ================================================= -->

            <div class="col-lg-3 col-md-6 mb-3">

                <a
                        href="jadval_users.php"
                        class="digi-dashboard-quick"
                >

                    <div class="digi-dashboard-quick-icon">
                        👥
                    </div>

                    <span class="digi-dashboard-quick-title">
                    مدیریت کاربران
                </span>

                    <p class="digi-dashboard-quick-text">
                        مشاهده و مدیریت کاربران سایت
                    </p>

                </a>

            </div>


            <!-- =================================================
                 مدیریت مقالات
            ================================================= -->

            <div class="col-lg-3 col-md-6 mb-3">

                <a
                        href="jadval_maghale.php"
                        class="digi-dashboard-quick"
                >

                    <div class="digi-dashboard-quick-icon">
                        📝
                    </div>

                    <span class="digi-dashboard-quick-title">
                    مدیریت مقالات
                </span>

                    <p class="digi-dashboard-quick-text">
                        مشاهده، ویرایش و حذف مقالات
                    </p>

                </a>

            </div>


            <!-- =================================================
                 افزودن مقاله
            ================================================= -->

            <div class="col-lg-3 col-md-6 mb-3">

                <a
                        href="add_maghale.php"
                        class="digi-dashboard-quick"
                >

                    <div class="digi-dashboard-quick-icon">
                        ✍️
                    </div>

                    <span class="digi-dashboard-quick-title">
                    افزودن مقاله
                </span>

                    <p class="digi-dashboard-quick-text">
                        ایجاد و انتشار مقاله جدید
                    </p>

                </a>

            </div>


        </div>

    </div>


    <!-- =================================================
         ACCOUNT + SHORTCUTS
    ================================================= -->

    <div class="row">


        <!-- وضعیت حساب -->

        <div class="col-md-6 mb-4">

            <div class="digi-dashboard-account">

                <h4 class="digi-dashboard-account-title">
                    وضعیت حساب مدیر
                </h4>


                <div class="digi-dashboard-account-user">

                    <div class="digi-dashboard-account-icon">
                        👑
                    </div>


                    <div>

                        <div class="digi-dashboard-account-name">

                            <?php

                            echo htmlspecialchars(
                                $_SESSION['username'] ?? 'مدیر',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </div>

                        <p class="digi-dashboard-account-role">
                            ● مدیر سیستم
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- میانبرها -->

        <div class="col-md-6 mb-4">

            <div class="digi-dashboard-account">

                <h4 class="digi-dashboard-account-title">
                    میانبرها
                </h4>


                <a
                        href="../index.php"
                        class="digi-dashboard-btn digi-dashboard-btn-site"
                >
                    مشاهده سایت
                </a>


                <a
                        href="../login-register/logout.php"
                        class="digi-dashboard-btn digi-dashboard-btn-logout"
                >
                    خروج از حساب
                </a>

            </div>

        </div>


    </div>


</div>


<?php

include "footer.php";

?>
