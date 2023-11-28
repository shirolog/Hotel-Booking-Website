<?php 
require('../components/connect.php');

if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];
}else{
    $admin_id = '';
    header('Location: ./login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
    
<!-- header section -->
<?php  require('../components/admin_header.php'); ?>


<!-- dashboard section -->

<section class="dashboard">

    <h1 class="heading">dashboard</h1>

    <div class="box-container">

        
        <div class="box">
            <?php 
            $select_admins = $conn->prepare("SELECT * FROM  `admins` WHERE id= ? LIMIT 1");
            $select_admins->execute(array($admin_id));
            $fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);
            ?>
            <h3>welcome</h3>
            <p><?= $fetch_admins['name']; ?></p>
            <a href="./update.php" class="btn">update profile</a>
        </div>

        <div class="box">
            <?php 
            $select_bookings = $conn->prepare("SELECT * FROM  `bookings`");
            $select_bookings->execute();
            $count_bookings = $select_bookings->rowCount(); 
            ?>
            <h3><?= $count_bookings; ?></h3>
            <p>total bookings</p>
            <a href="./bookings.php" class="btn">view bookings</a>
        </div>

        <div class="box">
            <?php 
            $select_admins = $conn->prepare("SELECT * FROM  `admins`");
            $select_admins->execute();
            $count_admins = $select_admins->rowCount(); 
            ?>
            <h3><?= $count_admins; ?></h3>
            <p>total admins</p>
            <a href="./admins.php" class="btn">view admins</a>
        </div>

        <div class="box">
            <?php 
            $select_messages = $conn->prepare("SELECT * FROM  `messages`");
            $select_messages->execute();
            $count_messages = $select_messages->rowCount(); 
            ?>
            <h3><?= $count_messages; ?></h3>
            <p>total messages</p>
            <a href="./messages.php" class="btn">view messages</a>
        </div>

        <div class="box">
            <h3>quick select</h3>
            <p>login or register</p>
            <a href="./login.php" class="btn" style="margin-right: 1rem;">login</a>
            <a href="./register.php" class="btn" style="margin-left: 1rem;">regiter</a>
        </div>

    </div>

</section>




<!-- custom js -->
<script src="../js/admin.js"></script>
</body>
</html>