<?php
$server="localhost";
$username="root";
$pass="";
$dbname="pedramm_digi_24";

$conn=mysqli_connect($server , $username, $pass ,$dbname);

if (!$conn){
    die("تصال برقرار نیست");
}
?>