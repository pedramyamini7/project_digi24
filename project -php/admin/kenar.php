

<li class="nav-item">
    <a href="index.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/dashboard.svg" alt="" width="25px"></i>
        <p>داشبورد</p>
    </a>
</li>
<li class="nav-item">
    <a href="jadval_users.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/clipboard-alt.svg" alt="" width="25px"></i>
        <p>کاربران </p>
    </a>
</li>

<li class="nav-item">
    <a href="jadval_maghale.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/clipboard-alt.svg" alt="" width="25px"></i>
        <p>جدول وبلاگ</p>
    </a>
</li>

<li class="nav-item">
    <a href="jadval_camera_ax.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/camera.svg" alt="" width="25px"></i>
        <p>جدول دوربین های عکاسی</p>
    </a>
</li>

<li class="nav-item">
    <a href="jadval_camera_film.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/video.svg" alt="" width="25px"></i>
        <p>جدول دوربین های فیلم برداری</p>
    </a>
</li>

<li class="nav-item">
    <a href="jadval_camera_lenz.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/mobile-android.svg" alt="" width="25px"></i>
        <p>جدول لنز های دوربین</p>
    </a>
</li>

<li class="nav-item">
    <a href="jadval_camera_var.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/football.svg" alt="" width="25px"></i>
        <p>جدول دوربین های ورزشی</p>
    </a>
</li>

<li class="nav-item">
    <a href="jadval_camera_noor.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/lamp.svg" alt="" width="25px"></i>
        <p>جدول نور پردازی</p>
    </a>
</li>

<li class="nav-item">
    <a href="jadval_camera_jan.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/tv-retro.svg" alt="" width="25px"></i>
        <p>جدول لوازم جانبی</p>
    </a>
</li>

<li class="nav-item">
    <a href="tamas_ba_ma.php" class="nav-link">
        <i class="nav-icon fas "><img src="../image/icon/phone.svg" alt="" width="25px"></i>
        <p>تماس با ما</p>
    </a>
</li>


<li class="nav-item">
    <a href="../login-register/logout.php" class="nav-link">
        <i class="nav-icon fas " ><img src="../image/icon/signout-1.svg" alt="" width="25px" ></i>
        <p>خروج از حساب  </p>
    </a>
</li>
<style>
    /* =========================
       رنگ آیکون‌ها
    ========================= */

    .main-sidebar .nav-sidebar .nav-item:nth-child(1) .nav-icon {
        background: #0066ff !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(2) .nav-icon {
        background: #6c2cff !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(3) .nav-icon {
        background: #ff7a00 !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(4) .nav-icon {
        background: #00a86b !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(5) .nav-icon {
        background: #0066ff !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(6) .nav-icon {
        background: #e53935 !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(7) .nav-icon {
        background: #6c2cff !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(8) .nav-icon {
        background: #00a86b !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(9) .nav-icon {
        background: #ff7a00 !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(10) .nav-icon {
        background: #0066ff !important;
        color: #ffffff !important;
    }

    .main-sidebar .nav-sidebar .nav-item:nth-child(11) .nav-icon {
        background: #e53935 !important;
        color: #ffffff !important;
    }


    /* =========================
       آیکون‌ها واضح‌تر
    ========================= */

    .main-sidebar .nav-sidebar .nav-link .nav-icon {
        width: 38px !important;
        height: 38px !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        border-radius: 11px !important;

        font-size: 16px !important;
        font-weight: 900 !important;

        margin-left: 11px !important;
        margin-right: 0 !important;

        flex-shrink: 0;

        box-shadow:
                0 5px 12px rgba(0,0,0,.14);

        transition:
                transform .2s ease,
                box-shadow .2s ease;
    }


    /* هاور */

    .main-sidebar .nav-sidebar .nav-link:hover .nav-icon {
        transform: scale(1.12) rotate(-3deg) !important;

        box-shadow:
                0 8px 18px rgba(0,0,0,.22) !important;
    }


    /* آیتم فعال */

    .main-sidebar .nav-sidebar .nav-link.active .nav-icon {
        background: linear-gradient(
                135deg,
                #0066ff,
                #6c2cff
        ) !important;

        color: #fff !important;

        transform: scale(1.08);

        box-shadow:
                0 8px 20px rgba(0,102,255,.35) !important;
    }


    /* متن */

    .main-sidebar .nav-sidebar .nav-link p {
        font-size: 12px !important;
        font-weight: 900 !important;
        color: #202938 !important;
    }


    /* هاور متن */

    .main-sidebar .nav-sidebar .nav-link:hover p {
        color: #0066ff !important;
    }


    /* فعال */

    .main-sidebar .nav-sidebar .nav-link.active p {
        color: #111827 !important;
        font-weight: 900 !important;
    }
</style>
