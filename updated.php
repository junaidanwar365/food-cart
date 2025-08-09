<?php
include "config.php";
    if(isset($_REQUEST['update_product'])){
      $update_id=$_REQUEST['update_p_id'];
      $update_nm=$_REQUEST['update_p_name'];
      $update_pr=$_REQUEST['update_p_price'];
      if ($_FILES['update_p_image']['size'] > 0) {
         $update_im=$_FILES['update_p_image']['name'];
         move_uploaded_file($_FILES['update_p_image']['tmp_name'], 'uploaded_img/' . $update_im);
 
         // Perform the update with the new image
         $query = "UPDATE products SET name='$update_nm', price='$update_pr', image='$update_im' WHERE id = $update_id";
     } else {
         // Perform the update without changing the existing image
         $query = "UPDATE products SET name='$update_nm', price='$update_pr' WHERE id = $update_id";
     }
    $update=mysqli_query($conn, $query) or die('Update query failed');
    if($update){
      $message[]='Product update Sucessfully';
    }else{
      $message[]='Product did not update Sucessfully';
    }
    
    };
    header('location: admin.php?update_message=' . urlencode(implode(' ', $message)));
    exit;
    ?>
