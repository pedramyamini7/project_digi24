<style>

    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .digi-admin-footer {
        margin-top: auto !important;
    }
    /* =========================================
       DIGI24 ADMIN FOOTER
    ========================================= */

    .digi-admin-footer {
        position: relative;

        margin-right: 250px;

        margin-top: 35px;

        padding: 28px 35px;

        background:
                radial-gradient(
                        circle at 90% 20%,
                        rgba(0, 129, 255, 0.16),
                        transparent 28%
                ),
                radial-gradient(
                        circle at 10% 80%,
                        rgba(188, 154, 92, 0.13),
                        transparent 30%
                ),
                #fffdf9;

        border-top: 1px solid #e8e0d5;

        box-shadow:
                0 -8px 30px rgba(40, 50, 70, 0.06);

        overflow: hidden;
    }


    /* خط آبی بالای فوتر */

    .digi-admin-footer::before {
        content: "";

        position: absolute;

        top: 0;
        right: 0;

        width: 100%;
        height: 3px;

        background: linear-gradient(
                90deg,
                #0063e8,
                #0081ff,
                #42a5ff,
                #0081ff,
                #0063e8
        );
    }


    /* محتوای فوتر */

    .digi-footer-inner {
        max-width: 1420px;

        margin: auto;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 25px;
    }


    /* بخش برند */

    .digi-footer-brand {
        display: flex;

        align-items: center;

        gap: 14px;
    }


    /* آیکون */

    .digi-footer-logo {
        width: 48px;
        height: 48px;

        display: flex;

        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 15px;

        background: linear-gradient(
                135deg,
                #0081ff,
                #0063e8
        );

        color: white;

        font-size: 22px;

        font-weight: 800;

        box-shadow:
                0 8px 18px rgba(0, 129, 255, 0.25);
    }


    /* متن برند */

    .digi-footer-text {
        display: flex;

        flex-direction: column;

        gap: 4px;
    }


    .digi-footer-text strong {
        color: #252a30;

        font-size: 15px;

        font-weight: 800;
    }


    .digi-footer-text span {
        color: #858585;

        font-size: 12px;
    }


    /* لینک */

    .digi-footer-text a {
        color: #0081ff;

        text-decoration: none;

        transition: 0.2s ease;
    }


    .digi-footer-text a:hover {
        color: #0063e8;
    }


    /* سمت راست اطلاعات */

    .digi-footer-status {
        display: flex;

        align-items: center;

        gap: 10px;

        padding: 10px 16px;

        background: #faf7f1;

        border: 1px solid #e7dfd4;

        border-radius: 13px;

        color: #666;

        font-size: 13px;
    }


    /* چراغ وضعیت */

    .digi-footer-status-dot {
        width: 9px;
        height: 9px;

        border-radius: 50%;

        background: #20b26b;

        box-shadow:
                0 0 0 4px rgba(32, 178, 107, 0.12);
    }


    /* متن CopyLeft */

    .digi-footer-copy {
        color: #777;

        font-size: 12px;
    }


    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 1100px) {

        .digi-admin-footer {
            margin-right: 0;
        }

    }


    @media (max-width: 700px) {

        .digi-admin-footer {
            padding: 23px 18px;
        }

        .digi-footer-inner {
            flex-direction: column;

            align-items: flex-start;
        }

        .digi-footer-status {
            width: 100%;

            box-sizing: border-box;
        }

    }


    @media (max-width: 430px) {

        .digi-admin-footer {
            padding: 20px 14px;
        }

        .digi-footer-logo {
            width: 43px;
            height: 43px;
        }

        .digi-footer-text strong {
            font-size: 14px;
        }

    }
    .main-footer.digi-admin-footer {
        margin-right: 250px !important;
        margin-left: 0 !important;

        width: auto !important;

        box-sizing: border-box;

        position: relative !important;

        bottom: auto !important;
    }
</style>

<?php

//session_start();

require "../config/config.php";

/* =====================================================
   اطلاعات کاربر
===================================================== */

$user_id = $_SESSION['id'] ?? null;

$first_name = '';
$last_name = '';
$username = '';
$profile_image = '';

if ($user_id) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT first_name, last_name, username, profile_image
         FROM login_register2
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    // نتیجه کاربر را داخل متغیر جدا می‌ریزیم
    $user_result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($user_result);

    // مهم: statement را می‌بندیم تا کوئری‌های بعدی روی $conn
    // با خطای "commands out of sync" مواجه نشوند
    mysqli_stmt_close($stmt);

    if ($user) {
        $first_name    = $user['first_name'] ?? '';
        $last_name     = $user['last_name'] ?? '';
        $username      = $user['username'] ?? '';
        $profile_image = $user['profile_image'] ?? '';
    }
}

?>

<footer class="main-footer digi-admin-footer" style="height: 100px;  ">

    <div class="digi-footer-inner">


        <!-- برند -->

        <div class="digi-footer-brand">

            <div class="digi-footer-logo">
                D
            </div>


            <div class="digi-footer-text">

                <strong>
                    CopyLeft &copy; 2026
                    <a href="">
                        <?php echo htmlspecialchars($username); ?>
                    </a>
                </strong>

                <span>
                    پنل مدیریت DIGI24
                </span>

            </div>

        </div>


        <!-- وضعیت سیستم -->

        <div class="digi-footer-status">

            <span class="digi-footer-status-dot"></span>

            <span>
                سیستم فعال است
            </span>

        </div>


    </div>

</footer>


<!-- Control Sidebar -->
<!--
<aside class="control-sidebar control-sidebar-dark">
</aside>
-->
<!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->


<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>

<script src="../js/jquery-ui-1.14.2.custom/jquery-ui.min.js"></script>


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>


<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<script src="plugins/morris/morris.min.js"></script>


<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>


<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>

<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>


<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>


<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>

<script src="plugins/daterangepicker/daterangepicker.js"></script>


<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>


<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>


<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>


<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>


<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>


</body>

</html>
