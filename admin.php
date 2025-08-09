<?php
session_start();
if(!isset($_SESSION['user'])){
    header('location:login.php');
    exit;
}
if(isset($_GET['update_message'])){
    $msg = urldecode($_GET['update_message']);
    echo '<div class="message"><span>' . $msg . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';

}
@include 'config.php';
if(isset($_POST['add_product'])){
   $p_name = $_POST['p_name'];
   $p_price = $_POST['p_price'];
   $p_image = $_FILES['p_image']['name'];
   $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
   $p_image_folder = 'uploaded_img/'.$p_image;

   $insert_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) VALUES('$p_name', '$p_price', '$p_image')") or die('query failed');

   if($insert_query){
      move_uploaded_file($p_image_tmp_name, $p_image_folder);
      $message[] = 'product add succesfully';
   }else{
      $message[] = 'could not add the product';
   }
};
if(isset($_GET['delete'])){
  $delete_id=$_GET['delete'];
  $delete_query=mysqli_query($conn,"DELETE FROM `products` WHERE id = $delete_id");
  if($delete_query){
  header('location:admin.php');
  $message[] = 'product is deleted successfully';
}else{
  header('location:admin.php');
  $message[] = 'product could not delete successfully';
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <?php
if(isset($message)){
  foreach($message as $message){
     echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
  };
};
?>
    <?php include 'header.php';
?>
<div class="container">

    <section> 
    <form action="" method="post" class="add-product-form" enctype="multipart/form-data">
        <h3>Add a New Product</h3>
        <input type="text" name="p_name" placeholder="Enter Your Product Name" class="box" Required>
        <input type="number" name="p_price" min="0" placeholder="Enter Your Price" class="box" Required>
        <input type="file" name="p_image" accept="image/png,image/jpg,image/jpeg" class="box" Required>
        <input type="submit" value="Add The Product" name="add_product" class="btn">
    </form>
    </section>

    <section class="display-product-table">
    <table>
        <thead>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Product Price</th>
            <th>Action</th>
        </thead>

        <tbody>
        <?php
            $select_products=mysqli_query($conn,"SELECT * FROM `products`");
            if(mysqli_num_rows($select_products) > 0){
            while($row=mysqli_fetch_assoc($select_products)){
        
        ?>
            <tr>
                <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
                <td><?php echo $row['name']?></td>
                <td>$<?php echo $row['price']?>/-</td>
                <td>
                    <a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn"
                        onclick="return confirm('are your sure you want to delete this?');"> <i
                            class="fas fa-trash"></i> delete </a>
                    <a href="admin.php?edit=<?php echo $row['id']; ?>" class="option-btn"> <i
                            class="fas fa-edit"></i> update </a>
                </td>
            </tr>
        <?php 
                };
            }else{
                echo "<div class='empty'>no product added</div>";
            } 
        ?>
        </tbody>
    </table>
    </section>
    <section class="edit-form-container">
        <?php
            if(isset($_GET['edit'])) {
                $edit_id = $_GET['edit'];
                $sql="SELECT * FROM `products` WHERE id = $edit_id";
                $result = mysqli_query($conn,$sql);
                if(mysqli_num_rows($result)>0){
                    while($fetch_edit=mysqli_fetch_assoc($result)){
        ?>
            <form action="updated.php" method="post" enctype="multipart/form-data">
                <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="200" alt="">
                <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
                <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">
                <input type="number" min="0" class="box" name="update_p_price" value="<?php echo
                $fetch_edit['price']; ?>">
                <input type="file" class="box" name="update_p_image" accept="image/png, image/jpg, image/jpeg">
                <input type="submit" value="update the prodcut" name="update_product" class="btn">
                <button type="reset" value="cancel" id="close-edit" class="option-btn">
            </form>

        <?php
                };
            };
        };
        ?>
    </section>

</div>
<?php include 'footer.html'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var editContainer = document.querySelector('.edit-form-container');

        // Function to toggle the display property of the edit form
        function toggleEditForm() {
            editContainer.style.display = (editContainer.style.display === 'none' || editContainer.style
                .display === '') ? 'flex' : 'none';
        }

        // Check if the 'edit' parameter is present in the URL
        var urlParams = new URLSearchParams(window.location.search);
        var editParam = urlParams.get('edit');

        // If 'edit' parameter is present, show the edit form
        if (editParam) {
            toggleEditForm();
        }
    });
    </script>
    <!-- custom js file link -->
    <script src="js/script.js"></script>

</body>

</html>