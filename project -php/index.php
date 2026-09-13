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

$sql = "SELECT * FROM maghale_dakhel ORDER BY id DESC LIMIT 3";

$result = mysqli_query($conn, $sql);

$article1 = mysqli_fetch_assoc($result);
$article2 = mysqli_fetch_assoc($result);
$article3 = mysqli_fetch_assoc($result);


?>

<?php
include "header.php";
?>


<!-- Carousel -->
<div class="container-fluid  overflow-visible" >

    <!-- شماره اسلاید -->
    <div class="slide-number " style="color: black; position: absolute; right: 6%; top: 50%;">

        <span id="currentSlide">01</span>

        <span>/</span>

        <span>04</span>

    </div>

    <!-- دکمه ها -->
    <div class="slider-buttons do-caro" style="color: white; position: absolute; right: 5%; top: 80%;">

        <button id="prevBtn" class="overflow-visible">
            ↑
        </button>

        <button id="nextBtn" class="overflow-visible">
            ↓
        </button>

    </div>
    <section class="hero-carousel" style="position: relative; margin-top: -5vw; z-index: 1">

        <div class="container ">


            <div class="row align-items-center ">



                <!-- تصویر -->
                <div class="col-8 order-lg-1 ax-caro">

                    <div class="slider ">

                        <img id="slideImage" src="image/car1.webp" alt="دوربین" class="slider-image">




                    </div>

                </div>
                <!-- تصویر/ -->

                <!-- متن -->
                <div class="col-4 order-lg-2 bg-danger d-flex justify-content-center align-items-center mx-auto text-start t-box-car" style="height: 350px;">

                    <div class="hero-content">

                        <h1 id="slideTitle">
                            ۶نکته دوربین‌های سینمایی
                        </h1>

                        <p id="slideDescription">
                            آشنایی با انواع دوربین‌های عکاسی
                            و فیلمبرداری
                        </p>

                    </div>

                </div>
                <!-- متن/ -->


            </div>

        </div>

    </section>
    <!--/Carousel -->
    <img src="image/c" alt="">
<script>
    const slides = [

        {
            image: "image/car1.webp",

            title: `
            ۶نکته دوربین‌های سینمایی

        `,

            description:
                "آشنایی با انواع دوربین‌های عکاسی و فیلمبرداری"
        },

        {
            image: "image/car3.jpg",

            title: `
            دوربین های
            حرفه ای
        `,

            description:
                "بررسی دوربین‌های حرفه‌ای برای عکاسی"
        },

        {
            image: "image/car4.jpg",

            title: `
            انواع لنز های
            عکاسی
        `,

            description:
                "آشنایی با انواع لنزها و کاربرد آن‌ها"
        },

        {
            image: "image/car5.jpg",

            title: `
            تجهیزات
            عکاسی
        `,

            description:
                "تجهیزات مورد نیاز برای عکاسی حرفه‌ای"
        },



    ];


    let currentSlide = 0;


    const image = document.getElementById("slideImage");

    const title = document.getElementById("slideTitle");

    const description =
        document.getElementById("slideDescription");

    const currentNumber =
        document.getElementById("currentSlide");


    function changeSlide(index) {

        /*
           شروع انیمیشن
        */

        image.style.opacity = "0";

        image.style.transform =
            "translateY(30px)";


        setTimeout(() => {

            /*
               تغییر اطلاعات
            */

            image.src = slides[index].image;

            title.innerHTML = slides[index].title;

            description.innerHTML =
                slides[index].description;


            /*
               شماره اسلاید
            */

            currentNumber.innerText =
                String(index + 1).padStart(2, "0");


            /*
               برگشت انیمیشن
            */

            image.style.transform =
                "translateY(0)";

            image.style.opacity = "1";

        }, 300);
    }


    /* دکمه پایین */

    document
        .getElementById("nextBtn")
        .addEventListener("click", () => {

            currentSlide++;

            if (currentSlide >= slides.length) {
                currentSlide = 0;
            }

            changeSlide(currentSlide);

        });


    /* دکمه بالا */

    document
        .getElementById("prevBtn")
        .addEventListener("click", () => {

            currentSlide--;

            if (currentSlide < 0) {
                currentSlide = slides.length - 1;
            }

            changeSlide(currentSlide);

        });
</script>
</div>
<!-- /Carousel -->


<!--کوتاه از digi24-->
<div class="container-fluid overflow-visible" >
    <section class="about-section" style="background-color: #fcfcfc;">
        <div class="container day1 ">

            <div class="about-content">

                <h2 class="d-flex justify-content-center overflow-visible">
                    درباره Digi24
                </h2>

                <p class="fs-6">
                    طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی
                    برای پر کردن صفحه و ارائه اولیه شکل ظاهری و کلی طرح
                    سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی
                    نشانگر چگونگی نوع و اندازه فونت و ظاهر متن باشد.
                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.
                </p>

            </div>

        </div>

    </section>

<div class="row d-flex justify-content-center gap-5 overflow-visible ">
    <div class="col-2  box-add overflow-visible ">
        <h5 class=" overflow-visible" style="font-size: clamp(15px, 2vw , 20px) !important;" >ارسال سریع</h5>
            <p class="fs-6 overflow-visible" style="font-size: clamp(5px, 2vw , 15px) !important;">
            طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارائه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده
        </p>
        <strong> <h1 class="add-1  overflow-visible">01</h1></strong>
    </div>

    <div class="col-2  box-add overflow-visible">
        <h5 class=" overflow-visible" style="font-size: clamp(15px, 2vw , 20px) !important;"> پشتیبانی 24 ساعته</h5>
        <p class="fs-6 overflow-visible" style="font-size: clamp(5px, 2vw , 15px) !important;">
            طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارائه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده
        </p>
        <strong> <h1 class="add-1  overflow-visible">02</h1></strong>

    </div>
    <div class="col-2  box-add overflow-visible">
        <h5 class=" overflow-visible " style="font-size: clamp(15px, 2vw , 20px) !important;"> گارانتی محصولات</h5>
        <p class="fs-6 overflow-visible" style="font-size: clamp(5px, 2vw , 15px) !important;">
            طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارائه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده
        </p>
        <strong> <h1 class="add-1  overflow-visible">03</h1></strong>

    </div>
</div>

</div>
<!--کوتاه از digi24-->

<br><br><br><br>
<!--گالری محصولات-->
<div class="container-fluid d-flex justify-content-center">
    <div class="row ">
        <h1 class="overflow-visible" style="font-size: clamp(15px, 2vw , 30px) ; text-align: center" >گالری محصولات</h1>
    </div>
</div>
<div class="hr-ga1"></div>
<div class="hr-ga1" style="width: 6vw !important; margin-top: 7px;"></div>
<br>
<div class="row d-flex justify-content-center gap-3">
<!--ax1-->

    <div class="col-md-3 col-4" style="position: relative;" ><a href="daste-mahsool.php" style="color: inherit;">
        <br><br><br><br><br>
    <div class="mah-ga" style="position: relative;"></div>
        <br>
            <img src="image/galery/ax1.png" alt="" class="ax1-galery">
        <h1 class="overflow-visible" style="margin-right: 8%; font-size: clamp(12px, 2vw , 20px) ">انواع دوربین عکاسی</h1>

        </a></div>
    <!--/ax1-->

    <!--ax2-->
    <div class="col-md-3 col-4" style="position: relative;" ><a href="daste-mahsool-film.php" style="color: inherit;">
        <br><br><br><br><br>
        <div class="mah-ga" style="position: relative;"></div>
        <br>
        <img src="image/galery/ax2.png" alt="" class="ax1-galery">
        <h1 class="overflow-visible" style="margin-right: 8%; font-size: clamp(12px, 2vw , 20px) ">انواع دوربین فیلم برداری</h1>

        </a></div>
    <!--ax2/-->

    <!--ax3-->
    <div class="col-md-3 col-4" style="position: relative;" ><a href="daste-mahsool-lenz.php" style="color: inherit;">
        <br><br><br><br><br>
        <div class="mah-ga" style="position: relative;"></div>
        <br>
        <img src="image/galery/ax3.png" alt="" class="ax1-galery">
        <h1 class="overflow-visible" style="margin-right: 8%; font-size: clamp(12px, 2vw , 20px) ">انواع  لنز دوربین</h1>

        </a> </div>
    <!--ax3/-->

    <!--ax4-->
    <div class="col-md-3 col-4" style="position: relative;" ><a href="daste-mahsool-noor.php" style="color: inherit;">
        <br><br><br><br><br>
        <div class="mah-ga" style="position: relative;"></div>
        <br>
        <img src="image/galery/ax4.png" alt="" class="ax1-galery">
        <h1 class="overflow-visible" style="margin-right: 8%; font-size: clamp(12px, 2vw , 20px) "> تجهیزات نورپردازی </h1>

        </a> </div>
    <!--ax4/-->

    <!--ax5-->
    <div class="col-md-3 col-4" style="position: relative;" >
        <br><br><br><br><br>
        <div class="mah-ga" style="position: relative;"></div>
        <br>
        <img src="image/galery/ax5.png" alt="" class="ax1-galery">
        <h1 class="overflow-visible" style="margin-right: 8%; font-size: clamp(12px, 2vw , 20px) ">  پایه دوربین</h1>

        </div>
    <!--ax5/-->

    <!--ax6-->
    <div class="col-md-3 col-4" style="position: relative;" ><a href="daste-mahsool-jan.php" style="color: inherit;">
        <br><br><br><br><br>
        <div class="mah-ga" style="position: relative;"></div>
        <br>
        <img src="image/galery/ax6.png" alt="" class="ax1-galery">
        <h1 class="overflow-visible" style="margin-right: 8%; font-size: clamp(12px, 2vw , 20px) ">لوازم جانبی  </h1>

        </a> </div>
    <!--ax6-->

</div>
<!--/گالری محصولات-->

<br><br><br><br>



<div class="row ">
    <h1 class="overflow-visible" style="font-size: clamp(15px, 2vw , 30px) ; text-align: center" > برخی از جدید ترین محصولات</h1>
</div>
<br>
<img class="noghte1" src="image/galery/noghte.png"/>

<!---------------------------------------------->

<!--اسلایدر 1-->
<div class="container  bg-white" style="width: 80vw; z-index: 10; border-radius:10px;  position: relative;   box-shadow: rgba(0, 22, 166, 0.22) 0px 0px 10px 5px;">


<div class="row overflow-visible">
    <div class="qv8-wrapper overflow-visible">

        <div class="qv8-title-row overflow-visible">

            <h4 class="qv8-title overflow-visible overflow-visible">
                دوربین های عکاسی
            </h4>

            <div class="qv8-buttons">

                <button type="button" class="qv8-arrow overflow-visible" id="qv8-prev">
                    &#10094;
                </button>

                <button type="button" class="qv8-arrow overflow-visible"  id="qv8-next">
                    &#10095;
                </button>

            </div>

        </div>

        <?php

        $sqlax = "
    SELECT *
    FROM projects
    WHERE title IS NOT NULL
      AND title != ''
    ORDER BY id DESC
    LIMIT 7
";

        $resultax = mysqli_query($conn, $sqlax);

        ?>
        <div class="qv8-viewport overflow-visible">

            <div class="qv8-track overflow-visible" id="qv8-track">



                <?php while ($rowax = mysqli_fetch_assoc($resultax)) { ?>

                    <div class="qv8-item overflow-visible"><a href="products.php?id=<?php echo $rowax['project_id']; ?>"
                                                              style="text-decoration: none; color: inherit;">

                        <div class="qv8-card">


                                <img
                                        src="up/<?php echo htmlspecialchars($rowax['image1']); ?>"
                                        class="img-fluid"
                                        style="width: 150px;"
                                        alt="دوربین"
                                >



                            <h6>
                                <?php echo htmlspecialchars($rowax['title']); ?>
                            </h6>

                            <p class="qv8-price overflow-visible">
                                <?php echo number_format($rowax['price']); ?> تومان
                            </p>

                            <del>
                                <?php echo number_format($rowax['price_fake']); ?> تومان
                            </del>

                        </div>

                        </a></div>

                <?php } ?>




            </div>

        </div>
        <br>
        <div class="row d-flex justify-content-end ">
        <a href="daste-mahsool.php" class="but-jad" style="border-radius: 7px; text-align: center; color: white;">مشاهده دیگر محصولات</a>
        </div>
    </div>
</div>
    <br>
</div>

<script>

    const qv8Track =
        document.getElementById("qv8-track");

    const qv8Next =
        document.getElementById("qv8-next");

    const qv8Prev =
        document.getElementById("qv8-prev");


    let qv8Position = 0;


    /* ================================
       تعداد محصولات قابل مشاهده
    ================================= */

    function qv8GetVisibleCount() {

        if (window.innerWidth <= 576) {
            return 1;
        }

        if (window.innerWidth <= 768) {
            return 2;
        }

        if (window.innerWidth <= 992) {
            return 3;
        }

        return 5;
    }


    /* ================================
       حرکت اسلایدر
    ================================= */

    function qv8MoveSlider() {

        const qv8Items =
            qv8Track.querySelectorAll(".qv8-item");

        const qv8Visible =
            qv8GetVisibleCount();


        /*
           حداکثر موقعیت ممکن

           مثال:
           7 محصول - 5 محصول قابل نمایش
           = 2 مرحله حرکت
        */

        const qv8MaxPosition =
            Math.max(0, qv8Items.length - qv8Visible);


        /* جلوگیری از رفتن قبل از اول */

        if (qv8Position < 0) {
            qv8Position = 0;
        }


        /* جلوگیری از رفتن بعد از آخر */

        if (qv8Position > qv8MaxPosition) {
            qv8Position = qv8MaxPosition;
        }


        /*
           عرض هر محصول
        */

        const qv8ItemWidth =
            100 / qv8Visible;


        /*
           حرکت اسلایدر
        */

        qv8Track.style.transform =
            `translateX(${qv8Position * qv8ItemWidth}%)`;


        /* ================================
           کنترل فلش‌ها
        ================================= */

        /*
           اگر به آخر رسیده باشیم
           فلش بعدی غیرفعال می‌شود
        */

        if (qv8Position >= qv8MaxPosition) {

            qv8Next.disabled = true;

        } else {

            qv8Next.disabled = false;

        }


        /*
           اگر به اول رسیده باشیم
           فلش قبلی غیرفعال می‌شود
        */

        if (qv8Position <= 0) {

            qv8Prev.disabled = true;

        } else {

            qv8Prev.disabled = false;

        }

    }


    /* ================================
       فلش چپ
       رفتن به محصولات جدیدتر
    ================================= */

    qv8Next.addEventListener("click", function () {

        const qv8Items =
            qv8Track.querySelectorAll(".qv8-item");

        const qv8Visible =
            qv8GetVisibleCount();


        const qv8MaxPosition =
            Math.max(0, qv8Items.length - qv8Visible);


        /*
           فقط اگر هنوز به آخر نرسیده‌ایم
        */

        if (qv8Position < qv8MaxPosition) {

            qv8Position++;

            qv8MoveSlider();

        }

    });


    /* ================================
       فلش راست
       برگشت به محصولات قبلی
    ================================= */

    qv8Prev.addEventListener("click", function () {

        /*
           فقط اگر در اول نیستیم
        */

        if (qv8Position > 0) {

            qv8Position--;

            qv8MoveSlider();

        }

    });


    /* ================================
       هنگام تغییر اندازه صفحه
    ================================= */

    window.addEventListener("resize", function () {

        qv8MoveSlider();

    });


    /* ================================
       اجرای اولیه
    ================================= */

    qv8MoveSlider();

</script>
<!---------------------------------------------->
<?php

$sqllenz = "
    SELECT *
    FROM projects_lenz
    WHERE title IS NOT NULL
      AND title != ''
    ORDER BY id DESC
    LIMIT 7
";

$resultlenz = mysqli_query($conn, $sqllenz);

?>
<!--اسلایدر 2-->

<br><br>
<div class="container bg-white"
     style="width: 80vw; z-index: 10; border-radius:10px; position:relative; box-shadow:rgba(0, 22, 166, 0.22) 0px 0px 10px 5px;">

    <div class="row overflow-visible">

        <div class="qv8-wrapper overflow-visible">

            <div class="qv8-title-row overflow-visible">

                <h4 class="qv8-title overflow-visible">
                    انواع لنز دوربین
                </h4>

                <div class="qv8-buttons">

                    <!-- قبلاً qv8-prev بود -->
                    <button type="button"
                            class="qv8-arrow overflow-visible"
                            id="camera-qv8-prev">
                        &#10094;
                    </button>

                    <!-- قبلاً qv8-next بود -->
                    <button type="button"
                            class="qv8-arrow overflow-visible"
                            id="camera-qv8-next">
                        &#10095;
                    </button>

                </div>

            </div>


            <div class="qv8-viewport overflow-visible">

                <!-- قبلاً qv8-track بود -->
                <div class="qv8-track overflow-visible"
                     id="camera-qv8-track">


                    <!-- محصول 1 -->
                    <?php while ($rowlenz = mysqli_fetch_assoc($resultlenz)) { ?>

                        <div class="qv8-item overflow-visible"><a href="products-lenz.php?id=<?php echo $rowlenz['project_id']; ?>"
                                                                  style="text-decoration: none; color: inherit;">

                            <div class="qv8-card">


                                    <img
                                            src="up/<?php echo htmlspecialchars($rowlenz['image1']); ?>"
                                            class="img-fluid"
                                            style="width: 150px;"
                                            alt="دوربین"
                                    >



                                <h6>
                                    <?php echo htmlspecialchars($rowlenz['title']); ?>
                                </h6>

                                <p class="qv8-price overflow-visible">
                                    <?php echo number_format($rowlenz['price']); ?> تومان
                                </p>

                                <del>
                                    <?php echo number_format($rowlenz['price_fake']); ?> تومان
                                </del>

                            </div>

                            </a></div>

                    <?php } ?>




                </div>

            </div>


            <br>


            <div class="row d-flex justify-content-end">

                <a href="daste-mahsool-film.php" class="but-jad" style="border-radius: 7px; text-align: center; color: white;">مشاهده دیگر محصولات</a>


            </div>

        </div>

    </div>

    <br>

</div>

<script>
    const cameraQv8Track =
        document.getElementById("camera-qv8-track");

    const cameraQv8Next =
        document.getElementById("camera-qv8-next");

    const cameraQv8Prev =
        document.getElementById("camera-qv8-prev");


    let cameraQv8Position = 0;


    /* تعداد محصولات قابل مشاهده */

    function cameraQv8GetVisibleCount() {

        if (window.innerWidth <= 576) {
            return 1;
        }

        if (window.innerWidth <= 768) {
            return 2;
        }

        if (window.innerWidth <= 992) {
            return 3;
        }

        return 5;
    }


    /* حرکت اسلایدر */

    function cameraQv8MoveSlider() {

        const cameraQv8Items =
            cameraQv8Track.querySelectorAll(".qv8-item");

        const cameraQv8Visible =
            cameraQv8GetVisibleCount();


        /* حداکثر تعداد حرکت */

        const cameraQv8MaxPosition =
            Math.max(0, cameraQv8Items.length - cameraQv8Visible);


        /* محدود کردن موقعیت */

        if (cameraQv8Position < 0) {
            cameraQv8Position = 0;
        }

        if (cameraQv8Position > cameraQv8MaxPosition) {
            cameraQv8Position = cameraQv8MaxPosition;
        }


        /* عرض هر محصول */

        const cameraQv8ItemWidth =
            100 / cameraQv8Visible;


        cameraQv8Track.style.transform =
            `translateX(${cameraQv8Position * cameraQv8ItemWidth}%)`;


        /* =========================
           فعال / غیرفعال کردن فلش‌ها
        ========================= */

        if (cameraQv8Position >= cameraQv8MaxPosition) {

            cameraQv8Next.disabled = true;

        } else {

            cameraQv8Next.disabled = false;

        }


        if (cameraQv8Position <= 0) {

            cameraQv8Prev.disabled = true;

        } else {

            cameraQv8Prev.disabled = false;

        }

    }


    /* محصول بعدی */

    cameraQv8Next.addEventListener("click", function () {

        const cameraQv8Items =
            cameraQv8Track.querySelectorAll(".qv8-item");

        const cameraQv8Visible =
            cameraQv8GetVisibleCount();

        const cameraQv8MaxPosition =
            Math.max(0, cameraQv8Items.length - cameraQv8Visible);


        if (cameraQv8Position < cameraQv8MaxPosition) {

            cameraQv8Position++;

            cameraQv8MoveSlider();

        }

    });


    /* محصول قبلی */

    cameraQv8Prev.addEventListener("click", function () {

        if (cameraQv8Position > 0) {

            cameraQv8Position--;

            cameraQv8MoveSlider();

        }

    });


    /* تغییر اندازه صفحه */

    window.addEventListener("resize", function () {

        cameraQv8MoveSlider();

    });


    /* اجرای اولیه */

    cameraQv8MoveSlider();
</script>
<!----------------------------------->
<?php

$sqlvar = "
    SELECT *
    FROM projects_var
    WHERE title IS NOT NULL
      AND title != ''
    ORDER BY id DESC
    LIMIT 7
";

$resultvar = mysqli_query($conn, $sqlvar);

?>
<!--اسلایدر 3-->
<br><br>

<div class="container bg-white"
     style="width: 80vw; z-index: 10; border-radius:10px; position:relative; box-shadow:rgba(0, 22, 166, 0.22) 0px 0px 10px 5px;">

    <div class="row overflow-visible">

        <div class="qv8-wrapper overflow-visible">

            <div class="qv8-title-row overflow-visible">

                <h4 class="qv8-title overflow-visible">
                    انواع تجهیزات ورزشی
                </h4>

                <div class="qv8-buttons">

                    <button type="button"
                            class="qv8-arrow"
                            id="lens-qv8-prev">
                        &#10094;
                    </button>

                    <button type="button"
                            class="qv8-arrow"
                            id="lens-qv8-next">
                        &#10095;
                    </button>

                </div>

            </div>


            <div class="qv8-viewport overflow-visible">

                <div class="qv8-track overflow-visible"
                     id="lens-qv8-track">


                    <!-- محصول 1 -->
                    <?php while ($rowvar = mysqli_fetch_assoc($resultvar)) { ?>

                        <div class="qv8-item overflow-visible"><a href="products-var.php?id=<?php echo $rowvar['project_id']; ?>"
                                                                  style="text-decoration: none; color: inherit;">

                                <div class="qv8-card">


                                    <img
                                            src="up/<?php echo htmlspecialchars($rowvar['image1']); ?>"
                                            class="img-fluid"
                                            style="width: 150px;"
                                            alt="دوربین"
                                    >



                                    <h6>
                                        <?php echo htmlspecialchars($rowvar['title']); ?>
                                    </h6>

                                    <p class="qv8-price overflow-visible">
                                        <?php echo number_format($rowvar['price']); ?> تومان
                                    </p>

                                    <del>
                                        <?php echo number_format($rowvar['price_fake']); ?> تومان
                                    </del>

                                </div>

                            </a></div>

                    <?php } ?>

                </div>

            </div>


            <br>


            <div class="row d-flex justify-content-end">

                <a href="daste-mahsool-var.php" class="but-jad" style="border-radius: 7px; text-align: center; color: white;">مشاهده دیگر محصولات</a>


            </div>

        </div>

    </div>

    <br>

</div>

<script>

    /* ================================
       عناصر اسلایدر لنز
    ================================= */

    const lensQv8Track =
        document.getElementById("lens-qv8-track");

    const lensQv8Next =
        document.getElementById("lens-qv8-next");

    const lensQv8Prev =
        document.getElementById("lens-qv8-prev");


    let lensQv8Position = 0;


    /* ================================
       تعداد محصولات قابل مشاهده
    ================================= */

    function lensQv8GetVisibleCount() {

        if (window.innerWidth <= 576) {
            return 1;
        }

        if (window.innerWidth <= 768) {
            return 2;
        }

        if (window.innerWidth <= 992) {
            return 3;
        }

        return 5;
    }


    /* ================================
       حرکت اسلایدر
    ================================= */

    function lensQv8MoveSlider() {

        const lensQv8Items =
            lensQv8Track.querySelectorAll(".qv8-item");

        const lensQv8Visible =
            lensQv8GetVisibleCount();


        /* حداکثر موقعیت */

        const lensQv8MaxPosition =
            Math.max(
                0,
                lensQv8Items.length - lensQv8Visible
            );


        /* جلوگیری از رفتن قبل از اول */

        if (lensQv8Position < 0) {
            lensQv8Position = 0;
        }


        /* جلوگیری از رفتن بعد از آخر */

        if (lensQv8Position > lensQv8MaxPosition) {
            lensQv8Position = lensQv8MaxPosition;
        }


        /* عرض هر محصول */

        const lensQv8ItemWidth =
            100 / lensQv8Visible;


        /* حرکت */

        lensQv8Track.style.transform =
            `translateX(${lensQv8Position * lensQv8ItemWidth}%)`;


        /* ================================
           کنترل فلش‌ها
        ================================= */

        if (lensQv8Position >= lensQv8MaxPosition) {

            lensQv8Next.disabled = true;

        } else {

            lensQv8Next.disabled = false;

        }


        if (lensQv8Position <= 0) {

            lensQv8Prev.disabled = true;

        } else {

            lensQv8Prev.disabled = false;

        }

    }


    /* ================================
       محصول بعدی
    ================================= */

    lensQv8Next.addEventListener("click", function () {

        const lensQv8Items =
            lensQv8Track.querySelectorAll(".qv8-item");

        const lensQv8Visible =
            lensQv8GetVisibleCount();


        const lensQv8MaxPosition =
            Math.max(
                0,
                lensQv8Items.length - lensQv8Visible
            );


        if (lensQv8Position < lensQv8MaxPosition) {

            lensQv8Position++;

            lensQv8MoveSlider();

        }

    });


    /* ================================
       محصول قبلی
    ================================= */

    lensQv8Prev.addEventListener("click", function () {

        if (lensQv8Position > 0) {

            lensQv8Position--;

            lensQv8MoveSlider();

        }

    });


    /* ================================
       تغییر اندازه صفحه
    ================================= */

    window.addEventListener("resize", function () {

        lensQv8MoveSlider();

    });


    /* ================================
       اجرای اولیه
    ================================= */

    lensQv8MoveSlider();

</script>

<!----------------------------------->
<br><br>

<div style="background-color: #0065c7; width: 100vw;"><br>
    <div class="row ">
        <h1 class="overflow-visible" style="font-size: clamp(15px, 2.8vw , 50px) ; text-align: center; color: white;" >  تازه های دنیای عکاسی و فیلم برداری </h1>
        <h1 class="overflow-visible" style="font-size: clamp(15px, 1.4vw , 50px) ; text-align: center; color: white; margin-top: 1%;" >
        در این قسمت برخی از تازه ترین مطلب بخش وبلاگ دیجیتال 24 برآورده شده. برای دیدن سایر مطالب...
        </h1>
        <a href="maghalat.php"><h1 class="overflow-visible" style="font-size: clamp(15px, 1.2vw , 50px) ; text-align: center; color: white; margin-top: 1%;" > مطالب بیشتر >> </h1></a>
    </div>
    <br>
    <div class="row d-flex justify-content-center overflow-visible">

        <div class="col-4   overflow-hidden" style="position: relative !important; height: 22vw !important;">

            <a href="maghalat-dakhel.php?id=<?php echo $article2['id']; ?>"><div class="ax-nature1 overflow-visible" style="position: absolute !important; top: 0;">
                <img src="uploads/<?php echo $article2['image']; ?>" class="overflow-visible"  alt="">
                <h1 class="t-ax2-backblue overflow-visible" style="font-size: 20px;"><?php echo $article2['title']; ?>   </h1>
                <div class="hr-ax2-backblue"></div>
                </div></a>

            <a href="maghalat-dakhel.php?id=<?php echo $article3['id']; ?>"><div class="ax-nature2 overflow-visible" style="position: absolute !important; bottom: 0;">
                <img src="uploads/<?php echo $article3['image']; ?>" class="overflow-visible" alt="">
                <h1 class="t-ax3-backblue overflow-visible" style="font-size: 20px;"><?php echo $article3['title']; ?> </h1>
                <div class="hr-ax3-backblue"></div>
                </div></a>

        </div>

        <div class="col-5   overflow-hidden" style="height: 22vw !important; position: relative !important;">
            <a href="maghalat-dakhel.php?id=<?php echo $article1['id']; ?>"><div class="ax-nature overflow-visible">
            <img src="uploads/<?php echo $article1['image']; ?>" class="overflow-visible" alt="">

                <h1 class="t-ax1-backblue overflow-visible" style="font-size: 20px;"><?php echo $article1['title']; ?> </h1>
                <div class="hr-ax1-backblue"></div>
            </div></a>

        </div>
    </div>

    <br><br><br><br>
    </div>

<!-------------------------------->
<br><br>
<div class="row">


    <section class="zx91-main">

        <div class="row ">
            <h1 class="overflow-visible" style="font-size: clamp(15px, 2vw , 30px) ; text-align: center" > برخی از جدید ترین محصولات</h1>
        </div>
        <div class="zx91-circle zx91-circle-a"></div>
        <div class="zx91-circle zx91-circle-b"></div>
        <div class="zx91-circle zx91-circle-c"></div>


        <div class="zx91-slider">

            <button type="button" class="zx91-arrow zx91-next" id="zx91-next">
                &#10094;
            </button>


            <div class="zx91-content">

                <p class="zx91-description" id="zx91-description">
                    طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی
                    برای پر کردن صفحه و ارائه اولیه شکل ظاهری و کلی طرح
                    سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی
                    نشانگر چگونگی نوع و اندازه فونت و نحوه قرار گرفتن متن
                    باشد.
                </p>


                <div class="zx91-dots" id="zx91-dots">

                    <span class="zx91-dot zx91-active"></span>
                    <span class="zx91-dot"></span>
                    <span class="zx91-dot"></span>
                    <span class="zx91-dot"></span>
                    <span class="zx91-dot"></span>

                </div>


                <h3 class="zx91-title overflow-visible" id="zx91-title">
                    طناز طباطبایی
                </h3>

            </div>


            <button type="button"
                    class="zx91-arrow zx91-prev"
                    id="zx91-prev">
                &#10094;
            </button>

        </div>


        <div class="zx91-brands">

            <h2 class="zx91-brands-title overflow-visible">
                برندهایی که ما با آن ها کار میکنیم
            </h2>


            <div class="zx91-brand-list">

                <div class="zx91-brand zx91-fuji">
                    FUJIFILM
                </div>

                <div class="zx91-brand zx91-panasonic">
                    Panasonic
                </div>

                <div class="zx91-brand zx91-sony">
                    SONY
                </div>

                <div class="zx91-brand zx91-canon">
                    Canon
                </div>

                <div class="zx91-brand zx91-nikon">
                    Nikon
                </div>

            </div>

        </div>

    </section>
</div>

<script>
    const zx91Description =
        document.getElementById("zx91-description");

    const zx91Title =
        document.getElementById("zx91-title");

    const zx91Next =
        document.getElementById("zx91-next");

    const zx91Prev =
        document.getElementById("zx91-prev");

    const zx91Dots =
        document.querySelectorAll(".zx91-dot");


    const zx91Slides = [

        {
            title: "طراح طباطبایی",

            description:
                "طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارائه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی نشانگر چگونگی نوع و اندازه فونت و نحوه قرار گرفتن متن باشد."
        },

        {
            title: "طراحی حرفه‌ای",

            description:
                "طراحی حرفه‌ای باعث می‌شود محصولات و خدمات شما ظاهر جذاب‌تر و منظم‌تری داشته باشند و مخاطب ارتباط بهتری با مجموعه شما برقرار کند."
        },

        {
            title: "تجربه متفاوت",

            description:
                "ما تلاش می‌کنیم با استفاده از طراحی مناسب و خلاقانه، تجربه‌ای متفاوت برای کاربران ایجاد کنیم و جزئیات را با دقت بیشتری نمایش دهیم."
        },

        {
            title: "کیفیت و خلاقیت",

            description:
                "ترکیب خلاقیت و کیفیت در طراحی باعث می‌شود نتیجه نهایی علاوه بر زیبایی، کاربردی و متناسب با نیاز پروژه باشد."
        },

        {
            title: "طراحی مدرن",

            description:
                "استفاده از طراحی مدرن و اصولی کمک می‌کند تا ظاهر پروژه حرفه‌ای‌تر باشد و محتوای آن به شکل ساده و قابل فهم در اختیار مخاطب قرار بگیرد."
        }

    ];


    let zx91CurrentSlide = 0;


    /* نمایش اسلاید */

    function zx91ShowSlide(index) {

        zx91CurrentSlide = index;

        zx91Description.textContent =
            zx91Slides[index].description;

        zx91Title.textContent =
            zx91Slides[index].title;


        zx91Dots.forEach(function(dot, i) {

            dot.classList.toggle(
                "zx91-active",
                i === index
            );

        });

    }


    /* اسلاید بعدی */

    zx91Next.addEventListener("click", function() {

        zx91CurrentSlide++;

        if (
            zx91CurrentSlide >=
            zx91Slides.length
        ) {

            zx91CurrentSlide = 0;

        }

        zx91ShowSlide(zx91CurrentSlide);

    });


    /* اسلاید قبلی */

    zx91Prev.addEventListener("click", function() {

        zx91CurrentSlide--;

        if (zx91CurrentSlide < 0) {

            zx91CurrentSlide =
                zx91Slides.length - 1;

        }

        zx91ShowSlide(zx91CurrentSlide);

    });


    /* کلیک روی نقطه ها */

    zx91Dots.forEach(function(dot, index) {

        dot.addEventListener("click", function() {

            zx91ShowSlide(index);

        });

    });


    /* اجرای اولیه */

    zx91ShowSlide(0);
</script>

<!-------------------------------------------->
<script  src="css/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

<?php
include "footer.php";
?>






