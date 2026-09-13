
<?php
include "haeder.php";
?>
<style>
    table{

    }
</style>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper hz">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 " >
                        <h1 class="m-0 text-dark "> محصولات</h1><br>



                        <?php
                        require "../config/config.php";

                        $sql= "SELECT * FROM maghale-dakhel";
                        $result = mysqli_query($conn , $sql);
                        ?>


                        <a href="add_maghale.php">+اضافه کردن محصول</a>
                        <br><br>
                        <table border="2px"  class="tab1">
                            <tr>
                                <td>نام</td>
                                <td>قیمت</td>
                                <td>عکس</td>
                                <td>توضیحات</td>
                                <td>عملیات</td>
                            </tr>
                            <?php
                            while ($row=mysqli_fetch_assoc($result)){
                            ?>
                                <tr>
                                    <td><?php  echo $row['name'] ; ?></td>
                                    <td><?php  echo "$".$row['price'] ; ; ?></td>
                                    <td> <?php echo "<img src='../up/".$row['image']."' width='100'>"; ?></td>
                                    <td><?php  echo $row['description'] ;  ?></td>

                                    <td>
                                        <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('از حذف کردن اطمینان دارید؟')">حذف</a>
                                        <br>
                                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" >ویرایش</a>

                                    </td>
                                </tr>

                                <?php
                            }
                            ?>



                        </table>



                    </div><!-- /.col -->



                </div><!-- /.row -->

            </div><!-- /.container-fluid -->

        </div>
    </div>
    <!-- /.content-wrapper -->








<?php
include "footer.php";
?>