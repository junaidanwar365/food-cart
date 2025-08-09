<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- custom css file link  -->
<link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <?php
        @include 'config.php';
        include 'header.php';
        // session_start();
        
        if(isset($_REQUEST['login']))
        {
            $user=$_REQUEST['user'];
            $ps=$_REQUEST['ps'];
            $q="SELECT * FROM `users` WHERE username='$user' AND password='$ps'";
            $result=mysqli_query($conn,$q) or die('query failed');
            $num=mysqli_num_rows($result);
            if($num===1)
            {
                // session_destroy();
                session_start();
                $_SESSION['user']=$user;
                 header('location:admin.php');
                 
            }
            else{
                 echo"<script>alert('Username or Password is wrong');</script>";
                }
        }
    ?>
    <form action="" method="post" class="add-product-form">
        <h3>Authentication</h3>
        <input type="text" class="box" name="user" placeholder="Enter your Username">
        
        <input type="password" class="box" name="ps" placeholder="Enter your Password">

        <input type="submit" name="login" class="btn">

    </form>
    <?php include 'example/logfooter.html'; ?>
    <!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>