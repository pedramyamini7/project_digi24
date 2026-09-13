<?php

session_start();

require "../config/config.php";


if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];


    $sql = "
        SELECT *
        FROM login_register2
        WHERE username = '$username'
    ";


    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {

        $jadval = mysqli_fetch_assoc($result);


        // بررسی رمز عبور

        if (password_verify($password, $jadval['password'])) {


            $_SESSION['id']         = $jadval['id'];
            $_SESSION['username']   = $jadval['username'];
            $_SESSION['first_name'] = $jadval['first_name'];
            $_SESSION['last_name']  = $jadval['last_name'];
            $_SESSION['role']       = $jadval['role'];


            // اگر ادمین بود

            if ($jadval['role'] == 'admin') {

                header("Location: ../index.php");

            } else {

                // اگر کاربر عادی بود

                header("Location: ../user.php");
            }


            exit;


        } else {

            echo "<script>
                    alert('رمز عبور صحیح نیست');
                  </script>";
        }


    } else {

        echo "<script>
                alert('نام کاربری پیدا نشد');
              </script>";
    }

}

?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
    >

    <title>ورود | DIGI24</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            min-height: 100%;
        }


        body {

            font-family:
                    Tahoma,
                    Arial,
                    sans-serif;

            background:

                    radial-gradient(
                            circle at 10% 10%,
                            rgba(36, 180, 178, .11),
                            transparent 28%
                    ),

                    radial-gradient(
                            circle at 90% 90%,
                            rgba(77, 154, 166, .08),
                            transparent 30%
                    ),

                    #f4f7f7;

            color: #29383a;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 35px 18px;
        }


        /* =========================================
           MAIN
        ========================================= */

        .d24-login-wrapper {

            width: 100%;

            max-width: 1040px;

            display: flex;

            justify-content: center;

            align-items: stretch;
        }


        /* =========================================
           CARD
        ========================================= */

        .d24-login-card {

            width: 100%;

            max-width: 880px;

            min-height: 550px;

            display: grid;

            grid-template-columns: .85fr 1.15fr;

            background: #ffffff;

            border: 1px solid #dfe9e9;

            border-radius: 28px;

            overflow: hidden;

            box-shadow:

                    0 25px 70px rgba(41, 86, 91, .11),

                    0 5px 18px rgba(41, 86, 91, .05);
        }


        /* =========================================
           LEFT SIDE
        ========================================= */

        .d24-login-side {

            position: relative;

            display: flex;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            text-align: center;

            padding: 45px 35px;

            background:

                    linear-gradient(
                            145deg,
                            #269d9d,
                            #187b80
                    );

            color: #ffffff;

            overflow: hidden;
        }


        /* دایره بالا */

        .d24-login-side::before {

            content: "";

            position: absolute;

            width: 280px;

            height: 280px;

            border-radius: 50%;

            border: 1px solid rgba(255,255,255,.10);

            top: -150px;

            right: -130px;
        }


        /* دایره پایین */

        .d24-login-side::after {

            content: "";

            position: absolute;

            width: 240px;

            height: 240px;

            border-radius: 50%;

            border: 1px solid rgba(255,255,255,.08);

            bottom: -130px;

            left: -110px;
        }


        /* =========================================
           LOGO
        ========================================= */

        .d24-login-logo {

            position: relative;

            z-index: 2;

            width: 72px;

            height: 72px;

            border-radius: 22px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: rgba(255,255,255,.10);

            border: 1px solid rgba(191,240,237,.65);

            color: #c9f1ed;

            font-size: 17px;

            font-weight: 900;

            letter-spacing: 1px;

            margin-bottom: 22px;

            box-shadow:
                    0 15px 35px rgba(20,75,78,.15);
        }


        /* =========================================
           BRAND
        ========================================= */

        .d24-login-side h1 {

            position: relative;

            z-index: 2;

            margin: 0;

            font-size: 29px;

            font-weight: 950;

            letter-spacing: 2px;
        }


        .d24-login-line {

            position: relative;

            z-index: 2;

            width: 42px;

            height: 2px;

            margin: 17px 0;

            background: #b9e7e3;

            border-radius: 10px;
        }


        .d24-login-side p {

            position: relative;

            z-index: 2;

            max-width: 230px;

            margin: 0;

            color: #e3f4f3;

            font-size: 11px;

            line-height: 2.2;
        }


        .d24-login-side-bottom {

            position: relative;

            z-index: 2;

            margin-top: 35px;

            color: #b9dedd;

            font-size: 9px;
        }


        /* =========================================
           FORM AREA
        ========================================= */

        .d24-login-form-area {

            padding: 52px 55px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            background: #ffffff;
        }


        /* =========================================
           HEADER
        ========================================= */

        .d24-login-header {

            margin-bottom: 28px;
        }


        .d24-login-header h2 {

            margin: 0;

            color: #293b3e;

            font-size: 22px;

            font-weight: 950;
        }


        .d24-login-header h2 span {

            color: #168c90;
        }


        .d24-login-header p {

            margin: 9px 0 0;

            color: #8a999b;

            font-size: 10px;

            line-height: 1.9;
        }


        /* =========================================
           FIELDS
        ========================================= */

        .d24-login-fields {

            display: flex;

            flex-direction: column;

            gap: 18px;
        }


        .d24-login-field label {

            display: block;

            margin-bottom: 8px;

            color: #536568;

            font-size: 10px;

            font-weight: 900;
        }


        .d24-login-field label::after {

            content: " *";

            color: #1a9b9e;
        }


        /* =========================================
           INPUT
        ========================================= */

        .d24-login-field input {

            width: 100%;

            height: 51px;

            padding: 0 15px;

            border: 1px solid #dce7e7;

            border-radius: 11px;

            outline: none;

            background: #f7fafa;

            color: #293b3e;

            font-family: inherit;

            font-size: 11px;

            font-weight: 600;

            transition: all .25s ease;
        }


        .d24-login-field input::placeholder {

            color: #a3afb0;

            font-size: 10px;
        }


        .d24-login-field input:hover {

            background: #fbfdfd;

            border-color: #a9cdcf;
        }


        .d24-login-field input:focus {

            background: #ffffff;

            border-color: #2aa5a7;

            box-shadow:

                    0 0 0 3px rgba(36,166,168,.11),

                    0 7px 20px rgba(42,91,95,.06);
        }


        /* =========================================
           BUTTON
        ========================================= */

        .d24-login-button {

            width: 100%;

            height: 52px;

            margin-top: 24px;

            border: none;

            border-radius: 12px;

            background:

                    linear-gradient(
                            135deg,
                            #249b9d,
                            #187c81
                    );

            color: #ffffff;

            font-family: inherit;

            font-size: 11px;

            font-weight: 950;

            cursor: pointer;

            box-shadow:

                    0 10px 24px rgba(25,121,126,.18);

            transition: all .25s ease;
        }


        .d24-login-button:hover {

            transform: translateY(-2px);

            background:

                    linear-gradient(
                            135deg,
                            #2eaaac,
                            #1d858a
                    );

            box-shadow:

                    0 14px 28px rgba(25,121,126,.23);
        }


        .d24-login-button:active {

            transform: translateY(0);
        }


        /* =========================================
           REGISTER LINK
        ========================================= */

        .d24-register-link {

            margin-top: 21px;

            padding-top: 18px;

            border-top: 1px solid #e4ebeb;

            text-align: center;

            color: #929e9f;

            font-size: 10px;
        }


        .d24-register-link a {

            color: #178c90;

            text-decoration: none;

            font-weight: 950;

            margin-right: 5px;

            font-size: 10px;

            transition: .2s ease;
        }


        .d24-register-link a:hover {

            color: #116f73;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 800px) {

            .d24-login-card {

                grid-template-columns: 1fr;

                max-width: 520px;
            }


            .d24-login-side {

                min-height: 230px;

                padding: 35px 25px;
            }


            .d24-login-side p {

                display: none;
            }


            .d24-login-side-bottom {

                margin-top: 15px;
            }


            .d24-login-form-area {

                padding: 38px 30px;
            }
        }


        @media (max-width: 520px) {

            body {

                padding: 18px 12px;
            }


            .d24-login-card {

                border-radius: 21px;

                min-height: auto;
            }


            .d24-login-side {

                min-height: 190px;

                padding: 28px 20px;
            }


            .d24-login-logo {

                width: 58px;

                height: 58px;

                border-radius: 17px;

                margin-bottom: 14px;
            }


            .d24-login-side h1 {

                font-size: 23px;
            }


            .d24-login-form-area {

                padding: 30px 20px;
            }


            .d24-login-header h2 {

                font-size: 19px;
            }


            .d24-login-header p {

                font-size: 10px;
            }


            .d24-login-field label {

                font-size: 10px;
            }


            .d24-login-field input {

                font-size: 11px;
            }


            .d24-login-button {

                font-size: 11px;
            }


            .d24-register-link {

                font-size: 10px;
            }

        }

    </style>

</head>


<body>


<div class="d24-login-wrapper">


    <div class="d24-login-card">


        <!-- =========================================
             LEFT SIDE
        ========================================== -->

        <div class="d24-login-side">


            <div class="d24-login-logo">
                D24
            </div>


            <h1>
                DIGI24
            </h1>


            <div class="d24-login-line"></div>


            <p>
                دوباره به DIGI24 خوش آمدید.
                وارد حساب کاربری خود شوید
                و ادامه دهید.
            </p>


            <div class="d24-login-side-bottom">
                تجربه‌ای ساده، سریع و حرفه‌ای
            </div>


        </div>


        <!-- =========================================
             FORM AREA
        ========================================== -->

        <div class="d24-login-form-area">


            <div class="d24-login-header">

                <h2>
                    ورود به <span>حساب کاربری</span>
                </h2>

                <p>
                    نام کاربری و رمز عبور خود را وارد کنید.
                </p>

            </div>


            <form
                    action=""
                    method="POST"
            >


                <div class="d24-login-fields">


                    <!-- نام کاربری -->

                    <div class="d24-login-field">

                        <label>
                            نام کاربری
                        </label>

                        <input
                                type="text"
                                name="username"
                                placeholder="نام کاربری خود را وارد کنید"
                                required
                        >

                    </div>


                    <!-- رمز عبور -->

                    <div class="d24-login-field">

                        <label>
                            رمز عبور
                        </label>

                        <input
                                type="password"
                                name="password"
                                placeholder="رمز عبور خود را وارد کنید"
                                required
                        >

                    </div>


                </div>


                <button
                        type="submit"
                        name="login"
                        class="d24-login-button"
                >
                    ورود به حساب
                </button>


            </form>


            <div class="d24-register-link">

                حساب کاربری ندارید؟

                <a href="register.php">
                    ثبت نام کنید
                </a>

            </div>


        </div>


    </div>


</div>


</body>

</html>
