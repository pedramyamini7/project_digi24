<?php

require "../config/config.php";


$article_id = $_GET['article_id'];


/* ==================================================
   آپدیت اطلاعات اصلی مقاله
================================================== */

if (isset($_POST['update_article'])) {

    $title = $_POST['title'];

    $author = $_POST['author'];

    $category = $_POST['category'];


    /* عکس اصلی */

    $image = $_FILES['image']['name'];


    if ($image != "") {

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../up/" . $image
        );

    } else {

        $sql_old_image = "SELECT image
                          FROM maghale_dakhel
                          WHERE article_id = $article_id
                          LIMIT 1";

        $result_old_image = mysqli_query(
            $conn,
            $sql_old_image
        );

        $row_old_image = mysqli_fetch_assoc(
            $result_old_image
        );

        $image = $row_old_image['image'];

    }


    /* آپدیت اطلاعات اصلی مقاله */

    $sql_update_article = "UPDATE maghale_dakhel

                           SET
                               title = '$title',
                               author = '$author',
                               category = '$category',
                               image = '$image'

                           WHERE article_id = $article_id";


    mysqli_query(
        $conn,
        $sql_update_article
    );


    echo "<script>

        alert('اطلاعات مقاله با موفقیت ذخیره شد');

        window.location.href =
        'maghale-moshahede.php?article_id=$article_id';

    </script>";

}


/* ==================================================
   آپدیت یک بخش مقاله
================================================== */

if (isset($_POST['update_section'])) {

    $section_id = $_POST['section_id'];

    $paragraph_title = $_POST['paragraph_title'];

    $paragraph = $_POST['paragraph'];

    $image_caption = $_POST['image_caption'];


    /* عکس بخش */

    $par_image = $_FILES['par_image']['name'];


    if ($par_image != "") {

        move_uploaded_file(
            $_FILES['par_image']['tmp_name'],
            "../up/" . $par_image
        );

    } else {

        /* اگر عکس جدید انتخاب نشده،
           عکس قبلی حفظ شود */

        $sql_old_par_image = "SELECT par_image
                              FROM maghale_dakhel
                              WHERE id = $section_id
                              AND article_id = $article_id";

        $result_old_par_image = mysqli_query(
            $conn,
            $sql_old_par_image
        );

        $row_old_par_image = mysqli_fetch_assoc(
            $result_old_par_image
        );

        $par_image = $row_old_par_image['par_image'];

    }


    /* آپدیت فقط همان بخش */

    $sql_update_section = "UPDATE maghale_dakhel

                           SET
                               paragraph_title = '$paragraph_title',
                               paragraph = '$paragraph',
                               par_image = '$par_image',
                               image_caption = '$image_caption'

                           WHERE id = $section_id
                           AND article_id = $article_id";


    mysqli_query(
        $conn,
        $sql_update_section
    );


    echo "<script>

        alert('بخش با موفقیت ذخیره شد');

        window.location.href =
        'maghale-moshahede.php?article_id=$article_id';

    </script>";

}


/* ==================================================
   حذف یک بخش مقاله
================================================== */

if (isset($_POST['delete_section'])) {

    $section_id = $_POST['section_id'];


    /* حذف فقط همان بخش */

    $sql_delete = "DELETE FROM maghale_dakhel

                   WHERE id = $section_id
                   AND article_id = $article_id";


    mysqli_query(
        $conn,
        $sql_delete
    );


    echo "<script>

        alert('بخش با موفقیت حذف شد');

        window.location.href =
        'maghale-moshahede.php?article_id=$article_id';

    </script>";

}


/* ==================================================
   گرفتن اطلاعات اصلی مقاله
================================================== */

$sql_article = "SELECT *
                FROM maghale_dakhel
                WHERE article_id = $article_id
                LIMIT 1";


$result_article = mysqli_query(
    $conn,
    $sql_article
);


$article = mysqli_fetch_assoc(
    $result_article
);


/* ==================================================
   گرفتن تمام بخش های مقاله
================================================== */

$sql_sections = "SELECT *
                 FROM maghale_dakhel
                 WHERE article_id = $article_id
                 ORDER BY id ASC";


$result_sections = mysqli_query(
    $conn,
    $sql_sections
);

?>


<?php

include "haeder.php";

?>


    <div class="content-wrapper a">

        <div class="content-header">

            <div class="container-fluid">


                <h1 class="m-0 text-dark">

                    مدیریت مقاله

                </h1>

                <br>


                <!-- ==================================================
                     اطلاعات اصلی مقاله
                ================================================== -->


                <h3>
                    اطلاعات مقاله
                </h3>

                <br>


                <form
                        action=""
                        method="post"
                        enctype="multipart/form-data"
                >


                    <!-- عنوان مقاله -->

                    <label>
                        عنوان مقاله
                    </label>

                    <br>

                    <input
                            type="text"
                            name="title"
                            value="<?php echo $article['title']; ?>"
                    >

                    <br>
                    <br>


                    <!-- نویسنده -->

                    <label>
                        نویسنده
                    </label>

                    <br>

                    <input
                            type="text"
                            name="author"
                            value="<?php echo $article['author']; ?>"
                    >

                    <br>
                    <br>


                    <!-- دسته بندی -->

                    <label>
                        دسته بندی
                    </label>

                    <br>

                    <input
                            type="text"
                            name="category"
                            value="<?php echo $article['category']; ?>"
                    >

                    <br>
                    <br>


                    <!-- عکس اصلی -->

                    <label>
                        عکس اصلی مقاله
                    </label>

                    <br>


                    <?php

                    if ($article['image'] != "") {

                        ?>

                        <img
                                src="../up/<?php echo $article['image']; ?>"
                                width="250"
                        >

                        <br>
                        <br>

                        <?php

                    }

                    ?>


                    <input
                            type="file"
                            name="image"
                    >

                    <br>
                    <br>


                    <!-- ذخیره اطلاعات مقاله -->

                    <input
                            type="submit"
                            name="update_article"
                            value="ذخیره اطلاعات مقاله"
                    >


                </form>


                <br>

                <hr>

                <br>


                <!-- ==================================================
                     بخش های مقاله
                ================================================== -->


                <h3>
                    بخش های مقاله
                </h3>

                <br>


                <?php

                /*
                 * شماره نمایشی بخش‌ها
                 * مستقل از id دیتابیس است.
                 */

                $section_number = 1;


                while ($row = mysqli_fetch_assoc($result_sections)) {

                    ?>


                    <div>


                        <h4>

                            بخش شماره
                            <?php echo $section_number; ?>

                        </h4>


                        <form
                                action=""
                                method="post"
                                enctype="multipart/form-data"
                        >


                            <!-- ID واقعی بخش -->

                            <input
                                    type="hidden"
                                    name="section_id"
                                    value="<?php echo $row['id']; ?>"
                            >


                            <!-- سرفصل -->

                            <label>
                                سرفصل پاراگراف
                            </label>

                            <br>

                            <input
                                    type="text"
                                    name="paragraph_title"
                                    value="<?php echo $row['paragraph_title']; ?>"
                            >

                            <br>
                            <br>


                            <!-- متن پاراگراف -->

                            <label>
                                متن اصلی پاراگراف
                            </label>

                            <br>

                            <textarea
                                    name="paragraph"
                            ><?php echo $row['paragraph']; ?></textarea>

                            <br>
                            <br>


                            <!-- عکس بخش -->

                            <label>
                                عکس پاراگراف
                            </label>

                            <br>


                            <?php

                            if ($row['par_image'] != "") {

                                ?>

                                <img
                                        src="../up/<?php echo $row['par_image']; ?>"
                                        width="250"
                                >

                                <br>
                                <br>

                                <?php

                            }

                            ?>


                            <input
                                    type="file"
                                    name="par_image"
                            >

                            <br>
                            <br>


                            <!-- توضیح عکس -->

                            <label>
                                توضیحات عکس
                            </label>

                            <br>

                            <textarea
                                    name="image_caption"
                            ><?php echo $row['image_caption']; ?></textarea>

                            <br>
                            <br>


                            <!-- دکمه ذخیره -->

                            <input
                                    type="submit"
                                    name="update_section"
                                    value="ذخیره تغییرات این بخش"
                            >


                            <!-- دکمه حذف -->

                            <input
                                    type="submit"
                                    name="delete_section"
                                    value="حذف بخش"
                                    onclick="return confirm('آیا مطمئن هستید که می‌خواهید این بخش را حذف کنید؟');"
                            >


                        </form>


                        <br>

                        <hr>

                        <br>


                    </div>


                    <?php

                    /*
                     * افزایش شماره نمایشی
                     */

                    $section_number++;

                }

                ?>


                <!-- ==================================================
                     افزودن بخش جدید
                ================================================== -->


                <a
                        href="add_magh_bakhsh.php?article_id=<?php echo $article_id; ?>"
                >

                    + افزودن بخش

                </a>


            </div>

        </div>

    </div>


<?php

include "footer.php";

?>