<?php
session_start();

require "../config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $i = 1;

    while (isset($_POST['spec_name_' . $i])) {

        $name = $_POST['spec_name_' . $i];
        $value = $_POST['spec_value_' . $i];

        if ($name != "" && $value != "") {

            mysqli_query(
                $conn,
                "INSERT INTO tamas_ba_ma (title, content)
                 VALUES ('$name', '$value')"
            );

        }

        $i++;
    }

    header("location: tamas_ba_ma.php");
    echo "محصول با موفقیت ذخیره شد.";
}

?>

<?php
include "haeder.php";
?>


<style>

    /* =========================================================
       DIGI24 — CONTACT / INFORMATION EDITOR
    ========================================================= */

    .digi-tamas-page {
        direction: rtl;
        min-height: 100vh;
        padding: 34px 38px 100px;

        background:
                radial-gradient(
                        circle at 85% 5%,
                        rgba(0,129,255,.10),
                        transparent 25%
                ),
                radial-gradient(
                        circle at 10% 35%,
                        rgba(188,154,92,.10),
                        transparent 28%
                ),
                #f5f0e7;

        font-family: inherit;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .digi-tamas-header {
        max-width: 1420px;
        margin: 0 auto 26px;
    }

    .digi-tamas-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;

        padding: 25px 28px;

        border: 1px solid rgba(32,43,55,.08);
        border-radius: 22px;

        background: rgba(255,253,249,.88);

        box-shadow:
                0 15px 45px rgba(66,52,30,.07);
    }

    .digi-tamas-heading-main {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .digi-tamas-heading-icon {
        width: 54px;
        height: 54px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 17px;

        color: #fff;

        font-size: 27px;
        font-weight: 300;

        background:
                linear-gradient(
                        145deg,
                        #0081ff,
                        #0063e8
                );

        box-shadow:
                0 12px 25px rgba(0,129,255,.25);
    }

    .digi-tamas-heading h1 {
        margin: 0;

        color: #202832;

        font-size: 24px;
        font-weight: 800;

        letter-spacing: -.4px;
    }

    .digi-tamas-heading p {
        margin: 6px 0 0;

        color: #8b8d91;

        font-size: 13px;
    }


    /* =========================================================
       FORM
    ========================================================= */

    #tamas-form {
        max-width: 1420px;
        margin: auto;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .digi-tamas-card {
        position: relative;

        margin-bottom: 18px;

        overflow: hidden;

        border: 1px solid rgba(38,44,50,.075);
        border-radius: 21px;

        background: #fffdf9;

        box-shadow:
                0 12px 35px rgba(65,52,33,.065);

        transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
    }

    .digi-tamas-card:hover {
        transform: translateY(-2px);

        border-color:
                rgba(0,129,255,.15);

        box-shadow:
                0 18px 45px rgba(65,52,33,.09);
    }


    /* =========================================================
       CARD HEADER
    ========================================================= */

    .digi-tamas-card-head {
        position: relative;

        display: flex;
        align-items: center;

        gap: 13px;

        padding: 19px 23px;

        border-bottom:
                1px solid #eee7dc;

        background:
                linear-gradient(
                        90deg,
                        rgba(0,129,255,.025),
                        rgba(255,255,255,0)
                );
    }

    .digi-tamas-card-head::after {
        content: "";

        position: absolute;

        right: 0;
        top: 0;

        width: 3px;
        height: 100%;

        background:
                linear-gradient(
                        180deg,
                        #0081ff,
                        #65b7ff
                );
    }

    .digi-tamas-card-icon {
        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        color: #fff;

        font-size: 17px;

        background:
                linear-gradient(
                        145deg,
                        #0081ff,
                        #0068ef
                );

        box-shadow:
                0 8px 18px rgba(0,129,255,.18);
    }

    .digi-tamas-card-head h2 {
        margin: 0;

        color: #252c34;

        font-size: 17px;
        font-weight: 800;
    }

    .digi-tamas-card-head span {
        display: block;

        margin-top: 4px;

        color: #98958f;

        font-size: 12px;
    }


    /* =========================================================
       CARD BODY
    ========================================================= */

    .digi-tamas-card-body {
        padding: 24px;
    }


    /* =========================================================
       SPEC ITEM
    ========================================================= */

    .digi-tamas-spec-item {
        position: relative;

        margin-bottom: 13px;

        padding: 17px;

        border: 1px solid #e9e1d5;
        border-radius: 15px;

        background: #faf7f1;

        transition: .2s ease;
    }

    .digi-tamas-spec-item:hover {
        border-color: rgba(0,129,255,.18);

        background: #fffdf9;
    }

    .digi-tamas-spec-item h4 {
        margin: 0 0 12px;

        color: #565b61;

        font-size: 12px;
        font-weight: 800;
    }


    /* =========================================================
       INPUT
    ========================================================= */

    .digi-tamas-spec-item .form-control {
        min-height: 47px;

        border:
                1px solid #e2dbd0;

        border-radius: 12px;

        background: #faf7f1;

        color: #293039;

        font-size: 14px;

        box-shadow: none;

        transition: .2s ease;
    }

    .digi-tamas-spec-item .form-control:focus {
        border-color: #0081ff;

        background: #fff;

        box-shadow:
                0 0 0 4px rgba(0,129,255,.08);
    }

    .digi-tamas-spec-item textarea.form-control {
        min-height: 120px;

        resize: vertical;

        padding-top: 13px;
    }

    .digi-tamas-spec-item .form-control::placeholder {
        color: #aaa59d;

        font-size: 13px;
    }


    /* =========================================================
       ADD BUTTON
    ========================================================= */

    #add-spec {
        margin-top: 5px;

        padding: 11px 18px;

        border: 0;
        border-radius: 11px;

        color: #fff;

        background: #0081ff;

        font-size: 12px;
        font-weight: 700;

        box-shadow:
                0 8px 20px rgba(0,129,255,.18);

        transition: .2s ease;
    }

    #add-spec:hover {
        background: #006ee0;

        transform:
                translateY(-1px);
    }


    /* =========================================================
       SAVE AREA
    ========================================================= */

    .digi-tamas-save-area {
        position: sticky;

        bottom: 15px;

        z-index: 20;

        display: flex;
        justify-content: flex-end;

        margin-top: 24px;

        padding: 13px;

        border:
                1px solid rgba(218,208,194,.8);

        border-radius: 17px;

        background:
                rgba(255,253,249,.88);

        box-shadow:
                0 15px 40px rgba(55,43,28,.12);

        backdrop-filter: blur(12px);
    }

    .digi-tamas-save-button {
        min-width: 190px;

        height: 48px;

        padding: 0 27px;

        border: 0;
        border-radius: 12px;

        color: #fff;

        background:
                linear-gradient(
                        135deg,
                        #0081ff,
                        #0065e7
                );

        font-family: inherit;

        font-size: 14px;
        font-weight: 800;

        cursor: pointer;

        box-shadow:
                0 10px 24px rgba(0,129,255,.25);

        transition: .22s ease;
    }

    .digi-tamas-save-button:hover {
        transform:
                translateY(-2px);

        box-shadow:
                0 14px 30px rgba(0,129,255,.3);
    }

    .digi-tamas-save-button:active {
        transform:
                translateY(0);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 700px) {

        .digi-tamas-page {
            padding: 20px 13px 80px;
        }

        .digi-tamas-heading {
            padding: 18px;
        }

        .digi-tamas-heading h1 {
            font-size: 20px;
        }

        .digi-tamas-card-body {
            padding: 17px;
        }

        .digi-tamas-save-area {
            bottom: 8px;
        }

        .digi-tamas-save-button {
            width: 100%;
        }
    }


    @media (max-width: 430px) {

        .digi-tamas-card-head {
            padding: 16px;
        }

        .digi-tamas-card-icon {
            width: 36px;
            height: 36px;
        }

        .digi-tamas-card-head h2 {
            font-size: 15px;
        }

    }

</style>


<div class="content-wrapper digi-tamas-page">


    <!-- =====================================================
         HEADER
    ===================================================== -->

    <div class="digi-tamas-header">

        <div class="digi-tamas-heading">

            <div class="digi-tamas-heading-main">

                <div class="digi-tamas-heading-icon">
                    +
                </div>

                <div>

                    <h1>
                        اضافه کردن اطلاعات
                    </h1>

                    <p>
                        اطلاعات مورد نظر را وارد کنید
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         FORM
    ===================================================== -->

    <form
            action=""
            method="POST"
            enctype="multipart/form-data"
            id="tamas-form"
    >


        <!-- =================================================
             مشخصات
        ================================================= -->

        <div class="digi-tamas-card">


            <div class="digi-tamas-card-head">

                <div class="digi-tamas-card-icon">
                    ≡
                </div>

                <div>

                    <h2>
                        مشخصات محصول
                    </h2>

                    <span>
                        عنوان و متن مورد نظر را به صورت ردیفی وارد کنید
                    </span>

                </div>

            </div>


            <div class="digi-tamas-card-body">


                <div id="specifications">


                    <div class="digi-tamas-spec-item">

                        <h4>
                            ردیف 1
                        </h4>


                        <input
                                type="text"
                                class="form-control mb-2"
                                name="spec_name_1"
                                placeholder="عنوان پاراگراف"
                        >


                        <textarea
                                class="form-control"
                                name="spec_value_1"
                                placeholder="متن پاراگراف"
                        ></textarea>

                    </div>


                </div>





            </div>

        </div>


        <!-- =================================================
             SAVE
        ================================================= -->

        <div class="digi-tamas-save-area">

            <button
                    type="submit"
                    class="digi-tamas-save-button"
            >
                ذخیره محصول
            </button>

        </div>


    </form>

</div>


<!-- =====================================================
     JavaScript مشخصات
====================================================== -->

<script>

    let specNumber = 1;

    $('#add-spec').click(function () {

        specNumber++;

        $('#specifications').append(`

            <div class="digi-tamas-spec-item">

                <h4>
                    ردیف ${specNumber}
                </h4>

                <input
                    type="text"
                    class="form-control mb-2"
                    name="spec_name_${specNumber}"
                    placeholder="نام مشخصات"
                >

                <textarea
                    class="form-control"
                    name="spec_value_${specNumber}"
                    placeholder="متن پاراگراف"
                ></textarea>

            </div>

        `);

    });

</script>


<?php
include "footer.php";
?>

