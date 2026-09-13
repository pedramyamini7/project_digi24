<?php

require "../config/config.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];


    // حذف مشخصات
    mysqli_query($conn, "DELETE FROM tamas_ba_ma_t WHERE id = '$id'");


    // برگشت به صفحه محصولات
    header("Location: tamas_ba_ma.php");
    exit;
}

?>