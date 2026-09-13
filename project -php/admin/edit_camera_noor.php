<?php
session_start();

require "../config/config.php";


// =====================================================
// گرفتن ID محصول
// =====================================================

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("محصول پیدا نشد");
}

$product_id = (int) $_GET['id'];


// =====================================================
// تابع آپلود تصویر
// =====================================================

function uploadImage($file)
{
    if (
        !isset($file) ||
        empty($file['name']) ||
        $file['error'] != 0
    ) {
        return false;
    }

    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    $allowed = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp'
    ];

    if (!in_array($extension, $allowed)) {
        return false;
    }

    $newName = uniqid('img_', true) . "." . $extension;

    $destination = "../up/" . $newName;

    if (move_uploaded_file(
        $file['tmp_name'],
        $destination
    )) {
        return $newName;
    }

    return false;
}


// =====================================================
// اگر فرم ارسال شده باشد
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =================================================
    // اطلاعات اصلی محصول
    // =================================================

    $title = mysqli_real_escape_string(
        $conn,
        $_POST['title'] ?? ''
    );

    $price = mysqli_real_escape_string(
        $conn,
        $_POST['price'] ?? ''
    );

    $price_fake = mysqli_real_escape_string(
        $conn,
        $_POST['price_fake'] ?? ''
    );

    $content = mysqli_real_escape_string(
        $conn,
        $_POST['content'] ?? ''
    );


    // =================================================
    // گرفتن محصول اصلی
    // =================================================

    $sql_product = "
        SELECT *
        FROM projects_noor
        WHERE id = '$product_id'
        AND project_id = '$product_id'
        LIMIT 1
    ";

    $result_product = mysqli_query(
        $conn,
        $sql_product
    );

    $product = mysqli_fetch_assoc(
        $result_product
    );

    if (!$product) {
        die("محصول پیدا نشد");
    }


    // =================================================
    // تصاویر قبلی
    // =================================================

    $image1 = $product['image1'];
    $image2 = $product['image2'];
    $image3 = $product['image3'];
    $image4 = $product['image4'];
    $image5 = $product['image5'];


    // =================================================
    // تصویر 1
    // =================================================

    $newImage = uploadImage(
        $_FILES['image1'] ?? null
    );

    if ($newImage !== false) {
        $image1 = mysqli_real_escape_string(
            $conn,
            $newImage
        );
    }


    // =================================================
    // تصویر 2
    // =================================================

    $newImage = uploadImage(
        $_FILES['image2'] ?? null
    );

    if ($newImage !== false) {
        $image2 = mysqli_real_escape_string(
            $conn,
            $newImage
        );
    }


    // =================================================
    // تصویر 3
    // =================================================

    $newImage = uploadImage(
        $_FILES['image3'] ?? null
    );

    if ($newImage !== false) {
        $image3 = mysqli_real_escape_string(
            $conn,
            $newImage
        );
    }


    // =================================================
    // تصویر 4
    // =================================================

    $newImage = uploadImage(
        $_FILES['image4'] ?? null
    );

    if ($newImage !== false) {
        $image4 = mysqli_real_escape_string(
            $conn,
            $newImage
        );
    }


    // =================================================
    // تصویر 5
    // =================================================

    $newImage = uploadImage(
        $_FILES['image5'] ?? null
    );

    if ($newImage !== false) {
        $image5 = mysqli_real_escape_string(
            $conn,
            $newImage
        );
    }


    // =================================================
    // بروزرسانی محصول اصلی
    // =================================================

    $sql_update = "
        UPDATE projects_noor SET

            title = '$title',
            price = '$price',
            price_fake = '$price_fake',
            image1 = '$image1',
            image2 = '$image2',
            image3 = '$image3',
            image4 = '$image4',
            image5 = '$image5',
            content = '$content'

        WHERE id = '$product_id'
        AND project_id = '$product_id'
    ";

    if (!mysqli_query($conn, $sql_update)) {
        die(
            "خطا در بروزرسانی محصول: " .
            mysqli_error($conn)
        );
    }


    // =====================================================
    // مشخصات
    // =====================================================

    // همه مشخصات قبلی این محصول
    $oldSpecIds = [];

    $sql = "
        SELECT id
        FROM projects_noor
        WHERE project_id = '$product_id'
        AND spec_title IS NOT NULL
        AND spec_title != ''
    ";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $oldSpecIds[] = (int) $row['id'];
    }


    $newSpecIds = [];

    $specNumbers = [];


    // پیدا کردن شماره مشخصات
    foreach ($_POST as $key => $value) {

        if (
            preg_match(
                '/^spec_name_(\d+)$/',
                $key,
                $match
            )
        ) {
            $specNumbers[] = (int) $match[1];
        }
    }


    foreach ($specNumbers as $number) {

        $specTitle = trim(
            $_POST['spec_name_' . $number] ?? ''
        );

        $specValue = trim(
            $_POST['spec_value_' . $number] ?? ''
        );


        // اگر هر دو خالی باشند، هیچ کاری نکن
        if (
            $specTitle === '' &&
            $specValue === ''
        ) {
            continue;
        }


        $specTitle = mysqli_real_escape_string(
            $conn,
            $specTitle
        );

        $specValue = mysqli_real_escape_string(
            $conn,
            $specValue
        );


        // ---------------------------------------------
        // مشخصه قبلی
        // ---------------------------------------------

        if (
            isset($_POST['spec_id_' . $number]) &&
            !empty($_POST['spec_id_' . $number])
        ) {

            $specId = (int) $_POST[
            'spec_id_' . $number
            ];

            $newSpecIds[] = $specId;


            $sql = "
                UPDATE projects_noor SET

                    spec_title = '$specTitle',
                    spec_value = '$specValue'

                WHERE id = '$specId'
                AND project_id = '$product_id'
            ";

            mysqli_query($conn, $sql);

        } else {

            // -----------------------------------------
            // مشخصه جدید
            // -----------------------------------------

            $sql = "
                INSERT INTO projects_noor
                (
                    project_id,
                    spec_title,
                    spec_value
                )

                VALUES
                (
                    '$product_id',
                    '$specTitle',
                    '$specValue'
                )
            ";

            mysqli_query($conn, $sql);
        }
    }


    // =====================================================
    // حذف مشخصاتی که از فرم حذف شده‌اند
    // =====================================================

    foreach ($oldSpecIds as $oldSpecId) {

        if (!in_array($oldSpecId, $newSpecIds)) {

            mysqli_query(
                $conn,
                "
                DELETE FROM projects_noor

                WHERE id = '$oldSpecId'
                AND project_id = '$product_id'
                "
            );
        }
    }


    // =====================================================
    // پرسش و پاسخ
    // =====================================================

    $oldQuestionIds = [];

    $sql = "
        SELECT id
        FROM projects_noor
        WHERE project_id = '$product_id'
        AND question_title IS NOT NULL
        AND question_title != ''
    ";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $oldQuestionIds[] = (int) $row['id'];
    }


    $newQuestionIds = [];

    $questionNumbers = [];


    foreach ($_POST as $key => $value) {

        if (
            preg_match(
                '/^question_(\d+)$/',
                $key,
                $match
            )
        ) {
            $questionNumbers[] = (int) $match[1];
        }
    }


    foreach ($questionNumbers as $number) {

        $question = trim(
            $_POST[
            'question_' . $number
            ] ?? ''
        );

        $answer = trim(
            $_POST[
            'answer_' . $number
            ] ?? ''
        );


        // اگر هر دو خالی باشند
        if (
            $question === '' &&
            $answer === ''
        ) {
            continue;
        }


        $question = mysqli_real_escape_string(
            $conn,
            $question
        );

        $answer = mysqli_real_escape_string(
            $conn,
            $answer
        );


        // ---------------------------------------------
        // پرسش قبلی
        // ---------------------------------------------

        if (
            isset(
                $_POST[
                'question_id_' . $number
                ]
            ) &&
            !empty(
            $_POST[
            'question_id_' . $number
            ]
            )
        ) {

            $questionId = (int) $_POST[
            'question_id_' . $number
            ];

            $newQuestionIds[] = $questionId;


            $sql = "
                UPDATE projects_noor SET

                    question_title = '$question',
                    question_answer = '$answer'

                WHERE id = '$questionId'
                AND project_id = '$product_id'
            ";

            mysqli_query($conn, $sql);

        } else {

            // -----------------------------------------
            // پرسش جدید
            // -----------------------------------------

            $sql = "
                INSERT INTO projects_noor
                (
                    project_id,
                    question_title,
                    question_answer
                )

                VALUES
                (
                    '$product_id',
                    '$question',
                    '$answer'
                )
            ";

            mysqli_query($conn, $sql);
        }
    }


    // =====================================================
    // حذف پرسش‌هایی که از فرم حذف شده‌اند
    // =====================================================

    foreach ($oldQuestionIds as $oldQuestionId) {

        if (!in_array(
            $oldQuestionId,
            $newQuestionIds
        )) {

            mysqli_query(
                $conn,
                "
                DELETE FROM projects_noor

                WHERE id = '$oldQuestionId'
                AND project_id = '$product_id'
                "
            );
        }
    }


    // =====================================================
    // گارانتی و زمان ارسال
    // =====================================================

    // گزینه‌های قبلی محصول
    $oldOptionIds = [];

    $sql = "
        SELECT id
        FROM projects_noor
        WHERE project_id = '$product_id'
        AND
        (
            (warranty IS NOT NULL AND warranty != '')
            OR
            (shipping_time IS NOT NULL AND shipping_time != '')
        )
    ";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $oldOptionIds[] = (int) $row['id'];
    }


    // همه گزینه‌های قبلی حذف می‌شوند
    foreach ($oldOptionIds as $oldOptionId) {

        mysqli_query(
            $conn,
            "
            DELETE FROM projects_noor

            WHERE id = '$oldOptionId'
            AND project_id = '$product_id'
            "
        );
    }


    // =====================================================
    // گارانتی
    // =====================================================

    if (
        isset($_POST['warranty']) &&
        is_array($_POST['warranty'])
    ) {

        foreach (
            $_POST['warranty']
            as $warranty
        ) {

            $warranty = mysqli_real_escape_string(
                $conn,
                $warranty
            );


            $sql = "
                INSERT INTO projects_noor
                (
                    project_id,
                    warranty
                )

                VALUES
                (
                    '$product_id',
                    '$warranty'
                )
            ";

            mysqli_query($conn, $sql);
        }
    }


    // =====================================================
    // زمان ارسال
    // =====================================================

    if (
        isset($_POST['shipping']) &&
        is_array($_POST['shipping'])
    ) {

        foreach (
            $_POST['shipping']
            as $shipping
        ) {

            $shipping = mysqli_real_escape_string(
                $conn,
                $shipping
            );


            $sql = "
                INSERT INTO projects_noor
                (
                    project_id,
                    shipping_time
                )

                VALUES
                (
                    '$product_id',
                    '$shipping'
                )
            ";

            mysqli_query($conn, $sql);
        }
    }


    // =====================================================
    // برگشت به جدول محصولات
    // =====================================================

    header("Location: jadval_camera_noor.php");
    exit;
}


// =====================================================
// گرفتن محصول اصلی
// =====================================================

$sql = "
    SELECT *
    FROM projects_noor
    WHERE id = '$product_id'
    AND project_id = '$product_id'
    LIMIT 1
";

$result = mysqli_query(
    $conn,
    $sql
);

$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("محصول پیدا نشد");
}


// =====================================================
// گرفتن مشخصات
// =====================================================

$sql_specs = "
    SELECT *
    FROM projects_noor
    WHERE project_id = '$product_id'
    AND spec_title IS NOT NULL
    AND spec_title != ''
    ORDER BY id ASC
";

$result_specs = mysqli_query(
    $conn,
    $sql_specs
);


// =====================================================
// گرفتن پرسش و پاسخ
// =====================================================

$sql_questions = "
    SELECT *
    FROM projects_noor
    WHERE project_id = '$product_id'
    AND question_title IS NOT NULL
    AND question_title != ''
    ORDER BY id ASC
";

$result_questions = mysqli_query(
    $conn,
    $sql_questions
);


// =====================================================
// گرفتن گارانتی و ارسال
// =====================================================

$sql_options = "
    SELECT *
    FROM projects_noor
    WHERE project_id = '$product_id'
    AND
    (
        (warranty IS NOT NULL AND warranty != '')
        OR
        (shipping_time IS NOT NULL AND shipping_time != '')
    )
    ORDER BY id ASC
";

$result_options = mysqli_query(
    $conn,
    $sql_options
);


$warranties = [];
$shippings = [];


while (
$option = mysqli_fetch_assoc($result_options)
) {

    if (!empty($option['warranty'])) {
        $warranties[] = $option['warranty'];
    }

    if (!empty($option['shipping_time'])) {
        $shippings[] = $option['shipping_time'];
    }
}

?>


<?php include "haeder.php"; ?>


<div class="content-wrapper a">

    <div class="content-header">

        <div class="container-fluid">


            <h1 class="m-0 text-dark">
                ویرایش محصول
            </h1>

            <br>


            <!-- =====================================================
                 فرم
            ====================================================== -->

            <form
                    method="POST"
                    enctype="multipart/form-data"
            >


                <!-- =====================================================
                     نام محصول
                ====================================================== -->

                <div class="mb-3">

                    <label class="form-label">
                        نام محصول
                    </label>

                    <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="<?php echo htmlspecialchars(
                                $product['title']
                            ); ?>"
                            required
                    >

                </div>


                <!-- =====================================================
                     قیمت
                ====================================================== -->

                <div class="mb-3">

                    <label class="form-label">
                        قیمت
                    </label>

                    <input
                            type="number"
                            name="price"
                            class="form-control"
                            value="<?php echo htmlspecialchars(
                                $product['price']
                            ); ?>"
                            required
                    >

                </div>


                <!-- =====================================================
                     قیمت غیر واقعی
                ====================================================== -->

                <div class="mb-3">

                    <label class="form-label">
                        قیمت غیر واقعی
                    </label>

                    <input
                            type="number"
                            name="price_fake"
                            class="form-control"
                            value="<?php echo htmlspecialchars(
                                $product['price_fake']
                            ); ?>"
                            required
                    >

                </div>


                <hr>


                <!-- =====================================================
                     تصاویر
                ====================================================== -->

                <h4>
                    تصاویر محصول
                </h4>


                <?php

                for (
                    $imageNumber = 1;
                    $imageNumber <= 5;
                    $imageNumber++
                ) {

                    $imageName =
                        $product[
                        'image' . $imageNumber
                        ];

                    ?>

                    <div class="mb-3">

                        <label class="form-label">
                            تصویر <?php echo $imageNumber; ?>
                        </label>

                        <br>


                        <?php if (!empty($imageName)) { ?>

                            <img
                                    src="../up/<?php echo htmlspecialchars(
                                        $imageName
                                    ); ?>"
                                    width="120"
                                    height="120"
                                    style="
                                    object-fit: cover;
                                    border-radius: 8px;
                                "
                            >

                            <br><br>

                        <?php } ?>


                        <input
                                type="file"
                                name="image<?php echo $imageNumber; ?>"
                                class="form-control"
                                accept="image/*"
                        >


                        <small class="text-muted">
                            اگر تصویر جدید انتخاب نکنید،
                            تصویر قبلی حفظ می‌شود.
                        </small>

                    </div>

                <?php } ?>


                <hr>


                <!-- =====================================================
                     مشخصات
                ====================================================== -->

                <h4>
                    مشخصات محصول
                </h4>


                <div id="specifications">

                    <?php

                    $specNumber = 1;

                    while (
                    $spec =
                        mysqli_fetch_assoc(
                            $result_specs
                        )
                    ) {

                        ?>

                        <div class="spec-row mb-3">

                            <input
                                    type="hidden"
                                    name="spec_id_<?php echo $specNumber; ?>"
                                    value="<?php echo $spec['id']; ?>"
                            >


                            <input
                                    type="text"
                                    name="spec_name_<?php echo $specNumber; ?>"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars(
                                        $spec['spec_title']
                                    ); ?>"
                                    placeholder="نام مشخصات"
                            >

                            <br>


                            <input
                                    type="text"
                                    name="spec_value_<?php echo $specNumber; ?>"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars(
                                        $spec['spec_value']
                                    ); ?>"
                                    placeholder="مقدار مشخصات"
                            >

                        </div>

                        <?php

                        $specNumber++;
                    }

                    ?>

                </div>


                <button
                        type="button"
                        id="add-spec"
                        class="btn btn-primary"
                >
                    + افزودن مشخصات
                </button>


                <hr>


                <!-- =====================================================
                     پرسش و پاسخ
                ====================================================== -->

                <h4>
                    پرسش و پاسخ
                </h4>


                <div id="questions">

                    <?php

                    $questionNumber = 1;

                    while (
                    $question =
                        mysqli_fetch_assoc(
                            $result_questions
                        )
                    ) {

                        ?>

                        <div class="question-row mb-4">

                            <input
                                    type="hidden"
                                    name="question_id_<?php echo $questionNumber; ?>"
                                    value="<?php echo $question['id']; ?>"
                            >


                            <input
                                    type="text"
                                    name="question_<?php echo $questionNumber; ?>"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars(
                                        $question['question_title']
                                    ); ?>"
                                    placeholder="سؤال"
                            >

                            <br>


                            <textarea
                                    name="answer_<?php echo $questionNumber; ?>"
                                    class="form-control"
                                    rows="4"
                                    placeholder="پاسخ"
                            ><?php echo htmlspecialchars(
                                    $question['question_answer']
                                ); ?></textarea>

                        </div>

                        <?php

                        $questionNumber++;
                    }

                    ?>

                </div>


                <button
                        type="button"
                        id="add-question"
                        class="btn btn-primary"
                >
                    + افزودن پرسش و پاسخ
                </button>


                <hr>


                <!-- =====================================================
                     گارانتی
                ====================================================== -->

                <h4>
                    گارانتی
                </h4>


                <label>
                    <input
                            type="checkbox"
                            name="warranty[]"
                            value="no_warranty"
                        <?php
                        if (
                            in_array(
                                'no_warranty',
                                $warranties
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    بدون گارانتی
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="warranty[]"
                            value="6_month"
                        <?php
                        if (
                            in_array(
                                '6_month',
                                $warranties
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۶ ماه
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="warranty[]"
                            value="12_month"
                        <?php
                        if (
                            in_array(
                                '12_month',
                                $warranties
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۱۲ ماه
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="warranty[]"
                            value="18_month"
                        <?php
                        if (
                            in_array(
                                '18_month',
                                $warranties
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۱۸ ماه
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="warranty[]"
                            value="24_month"
                        <?php
                        if (
                            in_array(
                                '24_month',
                                $warranties
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۲۴ ماه
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="warranty[]"
                            value="36_month"
                        <?php
                        if (
                            in_array(
                                '36_month',
                                $warranties
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۳۶ ماه
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="warranty[]"
                            value="48_month"
                        <?php
                        if (
                            in_array(
                                '48_month',
                                $warranties
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۴۸ ماه
                </label>


                <hr>


                <!-- =====================================================
                     زمان ارسال
                ====================================================== -->

                <h4>
                    زمان ارسال
                </h4>


                <label>
                    <input
                            type="checkbox"
                            name="shipping[]"
                            value="today"
                        <?php
                        if (
                            in_array(
                                'today',
                                $shippings
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ارسال امروز
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="shipping[]"
                            value="tomorrow"
                        <?php
                        if (
                            in_array(
                                'tomorrow',
                                $shippings
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ارسال فردا
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="shipping[]"
                            value="2_3_days"
                        <?php
                        if (
                            in_array(
                                '2_3_days',
                                $shippings
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۲ تا ۳ روز کاری
                </label>

                <br>


                <label>
                    <input
                            type="checkbox"
                            name="shipping[]"
                            value="3_5_days"
                        <?php
                        if (
                            in_array(
                                '3_5_days',
                                $shippings
                            )
                        ) {
                            echo "checked";
                        }
                        ?>
                    >
                    ۳ تا ۵ روز کاری
                </label>


                <hr>


                <!-- =====================================================
                     محتوا
                ====================================================== -->

                <h4>
                    محتوای محصول
                </h4>

                <div class="mb-3">

                    <textarea
                            name="content"
                            id="content"
                            class="form-control"
                            rows="10"
                    ><?php echo htmlspecialchars(
                            $product['content']
                        ); ?></textarea>

                </div>


                <hr>


                <!-- =====================================================
                     ذخیره
                ====================================================== -->

                <button
                        type="submit"
                        class="btn btn-success"
                >
                    ذخیره تغییرات
                </button>


            </form>

        </div>

    </div>

</div>


<!-- =====================================================
     JavaScript مشخصات
====================================================== -->

<script>

    let specNumber = <?php echo $specNumber; ?>;

    $('#add-spec').click(function () {

        specNumber++;

        $('#specifications').append(`

            <div class="spec-row mb-3">

                <input
                    type="text"
                    name="spec_name_${specNumber}"
                    class="form-control"
                    placeholder="نام مشخصات"
                >

                <br>

                <input
                    type="text"
                    name="spec_value_${specNumber}"
                    class="form-control"
                    placeholder="مقدار مشخصات"
                >

            </div>

        `);

    });

</script>


<!-- =====================================================
     JavaScript پرسش و پاسخ
====================================================== -->

<script>

    let questionNumber = <?php echo $questionNumber; ?>;

    $('#add-question').click(function () {

        questionNumber++;

        $('#questions').append(`

            <div class="question-row mb-4">

                <input
                    type="text"
                    name="question_${questionNumber}"
                    class="form-control"
                    placeholder="سؤال"
                >

                <br>

                <textarea
                    name="answer_${questionNumber}"
                    class="form-control"
                    rows="4"
                    placeholder="پاسخ"
                ></textarea>

            </div>

        `);

    });

</script>


<!-- =====================================================
     CKEditor
====================================================== -->

<script src="../ckeditor5-build-classic/ckeditor.js"></script>

<!-- =====================================================
     CKEditor
====================================================== -->

<script src="../ckeditor5-build-classic/ckeditor.js"></script>

<script src="../ckeditor5-build-classic/ckeditor.js"></script>

<script>
    let editor;

    class MyUploadAdapter {

        constructor(loader) {
            this.loader = loader;
            this.xhr = null;
        }

        upload() {

            return this.loader.file.then(file => {

                return new Promise((resolve, reject) => {

                    const data = new FormData();
                    data.append('upload', file);

                    this.xhr = new XMLHttpRequest();

                    this.xhr.open(
                        'POST',
                        'upload_image.php',
                        true
                    );

                    this.xhr.responseType = 'json';

                    this.xhr.onload = () => {

                        if (
                            this.xhr.status >= 200 &&
                            this.xhr.status < 300
                        ) {

                            if (
                                this.xhr.response &&
                                this.xhr.response.url
                            ) {

                                resolve({
                                    default: this.xhr.response.url
                                });

                            } else {

                                reject('آدرس تصویر دریافت نشد.');
                            }

                        } else {

                            reject('آپلود تصویر انجام نشد.');
                        }
                    };

                    this.xhr.onerror = () => {
                        reject('ارتباط با سرور برقرار نشد.');
                    };

                    this.xhr.send(data);
                });
            });
        }

        abort() {

            if (this.xhr) {
                this.xhr.abort();
            }
        }
    }


    function MyCustomUploadAdapterPlugin(editor) {

        editor.plugins
            .get('FileRepository')
            .createUploadAdapter = loader => {

            return new MyUploadAdapter(loader);
        };
    }


    ClassicEditor
        .create(
            document.querySelector('#content'),
            {
                language: 'fa',

                extraPlugins: [
                    MyCustomUploadAdapterPlugin
                ],

                toolbar: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'link',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'alignment',
                    'outdent',
                    'indent',
                    '|',
                    'insertTable',
                    'blockQuote',
                    'imageUpload',
                    '|',
                    'undo',
                    'redo'
                ]
            }
        )

        .then(newEditor => {

            editor = newEditor;

            document
                .querySelector('form')
                .addEventListener('submit', function () {

                    document.querySelector('#content').value =
                        editor.getData();

                });
        })

        .catch(error => {
            console.error(error);
        });
</script>
<?php include "footer.php"; ?>
