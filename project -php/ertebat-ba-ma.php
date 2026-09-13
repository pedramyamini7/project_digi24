<?php

session_start();

require "config/config.php";

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

$sql = "SELECT * FROM tamas_ba_ma";

$sql2 = "SELECT * FROM tamas_ba_ma_t";

$sql3 = "SELECT * FROM tamas_ba_ma_an";
$sql4 = "SELECT * FROM tamas_ba_ma_an";

$result = mysqli_query($conn, $sql);

$result2 = mysqli_query($conn, $sql2);

$result3 = mysqli_query($conn, $sql3);
$result4 = mysqli_query($conn, $sql3);



?>

<?php
include "header.php";
?>


<!-- ================= BANNER ================= -->

<section class="hero">

    <div class="pink-shape"></div>



</section>
    <div class="container">

        <img src="image/cam-b.png" class="camera" style="position: absolute; top: 160px; left: 15%;" alt="Camera">

    </div>


<!-- ================= ABOUT ================= -->

<section class="about-section">

    <div class="container">

        <div class="about-content">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
            <h2 class="overflow-visible">
                 <span>
                     <?php echo $row['title']; ?>
                 </span>
            </h2>

            <p>
                <?= nl2br(htmlspecialchars($row['content'])) ?>
            </p>
        <?php
         }
        ?>
        </div>

    </div>

</section>



<!-- ================= CONTACT AREA ================= -->

<section class="contact-section">

    <div class="container">

        <div class="row g-4">

            <!-- فرم تماس -->

            <div class="col-lg-6 col-md-12">

                <div class="contact-form">

                    <h3 class="overflow-visible">فرم ارتباط با ما</h3>

                    <form>

                        <input type="text"
                               placeholder="نام">

                        <div class="row">

                            <div class="col-md-6">

                                <input type="email"
                                       placeholder="آدرس ایمیل">

                            </div>

                            <div class="col-md-6">

                                <input type="text"
                                       placeholder="موضوع">

                            </div>

                        </div>

                        <textarea placeholder="متن پیام"></textarea>

                        <button type="submit">
                            ارسال
                        </button>

                    </form>

                </div>

            </div>

            <!-- ساعت کاری -->

            <div class="col-lg-3 col-md-4">

                <div class="work-time">

                    <h3 class="overflow-visible">ساعت کاری</h3>
<?php
while ($row2 = mysqli_fetch_assoc($result2)) {
    ?>
<!--                    <img src="image/car1.webp" alt="ساعت کاری">-->
                  <?php echo "<img src='up/".$row2['image']."' >"; ?>


    <p>
                        <?= nl2br(htmlspecialchars($row2['text'])) ?>
                    </p>
    <?php
}
?>
                </div>

            </div>

            <!-- راه های ارتباطی -->

            <div class="col-lg-3 col-md-4">

                <div class="contact-info">

                    <h3 class="overflow-visible">راه های ارتباطی</h3>
                    <?php
                    while ($row3 = mysqli_fetch_assoc($result3)) {

                        if (empty($row3['adress'])) {
                            continue;
                        }
                        ?>
                        <p>
                            <img src="image/ertebat/map-marker.svg" class="img-fluid" style="width: 1.2vw;" alt="">

                            <?php echo $row3['adress']; ?>
                        </p>

                        <?php
                    }
                    ?>


                    <?php
                    while ($row4 = mysqli_fetch_assoc($result4)) {

                        if (empty($row4['number'])) {
                            continue;
                        }
                        ?>
                        <p>
                            <img src="image/ertebat/incoming-call.svg" class="img-fluid" style="width: 1.2vw;" alt="">

                            <?php echo $row4['number']; ?>
                        </p>
                        <?php
                    }
                    ?>



                </div>

            </div>









        </div>

    </div>

</section>



    <script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

<?php
include "footer.php";
?>