<?php

require "../config/config.php";

$id=$_GET['id'];
$sql= " DELETE FROM products WHERE id='$id'";

mysqli_query($conn,$sql);

header("Location: products.php");
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
                        <h1 class="m-0 text-dark"> حذف محصولات </h1><br>






                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </div>
    <!-- /.content-wrapper -->


<?php
include "footer.php";
?>