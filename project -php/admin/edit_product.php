<?php
session_start();

require "../config/config.php";

$id =$_GET['id'];

$sql= "SELECT * FROM products WHERE id='$id'";
$result= mysqli_query($conn,$sql);

$row=mysqli_fetch_assoc($result);

if (isset($_POST['update'])){
    $name= $_POST['name'];
    $price= $_POST['price'];
    $desc= $_POST['desc'];

    $image= $_FILES['image']['name'];

    if ($image != ""){
        move_uploaded_file($_FILES['image']['tmp_name'],"../up/".$image);
    }else{
        $image= $row['image'];
    }

    $sql = "UPDATE products SET name='$name',price='$price',`description`='$desc', image='$image' WHERE id='$id'";
    mysqli_query($conn,$sql);

    header("Location: products.php");
}

?>


<?php
include "haeder.php";
?>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper a">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"> ویرایش محصولات </h1><br>

                        <form action="" method="post" enctype="multipart/form-data">

                            <label>نام محصول</label><br>
                            <input type="text" name="name" value="<?php echo $row['name'];?>"><br><br>

                            <label>قیمت </label><br>
                            <input type="text" name="price" value="<?php echo $row['price'];?>"><br><br>

                            <label>تصویر </label><br>
                            <img src="../up/<?php echo $row['image']; ?>" width="100px">
                            <input type="file" name="image" ><br><br>

                            <label>توضیحات </label><br>
                            <textarea name="desc" ><?php echo $row['description'];?></textarea><br><br>

                            <input type="submit" name="update" value="ویرایش محصول">
                        </form>




                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </div>
    <!-- /.content-wrapper -->


<?php
include "footer.php";
?>