<?php
require "../config/config.php";

if (isset($_POST['login'])){
//    echo "<script>alert('salam')</script>";

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username' AND password= '$password'";
    $result = mysqli_query($conn , $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row){
        header("location: index.php");
    }else{
    echo "<script>alert('نامعتبر')</script>";
    }

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../css.css">
</head>
<body class="asli">


<form action="" method="post" class="gh1">

    <label for="username" class="t-form"> <strong>Username</strong> </label><br><br>
    <input type="text" name="username"  class="box1" required>
    <br>
    <br>
    <label for="password" class="t-form"> <strong>Password</strong>  </label><br><br>
    <input type="text" name="password" class="box1" required>

    <br>

    <input type="submit" value="ورود" name="login" class="butt1">
</form>


</body>
</html>