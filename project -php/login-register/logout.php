<?php

session_start();

/* پاک کردن تمام اطلاعات Session */
$_SESSION = [];

/* حذف کوکی Session در صورت وجود */
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* نابود کردن Session */
session_destroy();

/* انتقال به صفحه ورود */
header("Location: login.php");
exit;

?>
