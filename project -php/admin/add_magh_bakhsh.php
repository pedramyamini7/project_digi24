<?php
session_start();

require "../config/config.php";


$article_id = $_GET['article_id'];


if (isset($_POST['save'])) {

    $paragraph_title = $_POST['paragraph_title'];

    $paragraph = $_POST['paragraph'];

    $image_caption = $_POST['image_caption'];


    $par_image = $_FILES['par_image']['name'];

    $tmp_par_image = $_FILES['par_image']['tmp_name'];


    if ($par_image != "") {

        move_uploaded_file(
            $tmp_par_image,
            "../up/" . $par_image
        );

    }


    $sql = "INSERT INTO maghale_dakhel
    (
        article_id,
        paragraph_title,
        paragraph,
        par_image,
        image_caption
    )
    VALUES
    (
        '$article_id',
        '$paragraph_title',
        '$paragraph',
        '$par_image',
        '$image_caption'
    )";


    mysqli_query($conn, $sql);


    echo "<script>

        alert('بخش با موفقیت اضافه شد');

        window.location.href =
        'maghale-moshahede.php?article_id=$article_id';

    </script>";

}

?>


<?php

include "haeder.php";

?>


    <div class="content-wrapper a">

        <div class="content-header">

            <div class="container-fluid">

                <div class="row mb-2">

                    <div class="col-sm-6">


                        <h1 class="m-0 text-dark">

                            افزودن بخش جدید

                        </h1>

                        <br>


                        <form
                                action=""
                                method="post"
                                enctype="multipart/form-data"
                        >


                            <label>
                                سرفصل پاراگراف
                            </label>

                            <br>

                            <input
                                    type="text"
                                    name="paragraph_title"
                            >

                            <br>
                            <br>


                            <label>
                                پاراگراف
                            </label>

                            <br>

                            <textarea
                                    name="paragraph"
                            ></textarea>

                            <br>
                            <br>


                            <label>
                                عکس
                            </label>

                            <br>

                            <input
                                    type="file"
                                    name="par_image"
                            >

                            <br>
                            <br>


                            <label>
                                توضیح عکس
                            </label>

                            <br>

                            <textarea
                                    name="image_caption"
                            ></textarea>

                            <br>
                            <br>


                            <input
                                    type="submit"
                                    name="save"
                                    value="افزودن بخش"
                            >


                        </form>


                    </div>

                </div>

            </div>

        </div>

    </div>


<?php

include "footer.php";

?>