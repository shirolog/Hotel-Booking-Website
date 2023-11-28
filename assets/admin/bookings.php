<?php 
require('../components/connect.php');
session_start();


if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];
}else{
    $admin_id = '';
    header('Location: ./login.php');
    exit();
}


if(isset($_POST['delete'])){

    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id= ?");
    $select_bookings->execute(array($delete_id));

    if($select_bookings->rowCount() > 0){
        $delete_bookings= $conn->prepare("DELETE FROM `bookings` WHERE booking_id= ?");
        $delete_bookings->execute(array($delete_id));
        $success_msg[] = 'Booking deleted!';
        $_SESSION['success_msg'] = $success_msg;
    }else{
        $warning_msg[] = 'Booking already deleted!';
        $_SESSION['warning_msg'] = $warning_msg;
    }
    header('Location:./bookings.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
    
<!-- header section -->
<?php  require('../components/admin_header.php'); ?>


<!-- bookings section -->

    <section class="grid">

        <h1 class="heading">bookings</h1>

        <div class="box-container">
            <?php 
            $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
            $select_bookings->execute();
            if($select_bookings->rowCount() > 0){
                while($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)){

            ?>
                <div class="box">
                    <p>booking id: <span><?= $fetch_bookings['booking_id']; ?></span></p>
                    <p>name : <span><?= $fetch_bookings['name']; ?></span></p>
                    <p>email : <span><?= $fetch_bookings['email']; ?></span></p>
                    <p>number : <span><?= $fetch_bookings['number']; ?></span></p>
                    <p>check in : <span><?= $fetch_bookings['check_in']; ?></span></p>
                    <p>check out : <span><?= $fetch_bookings['check_out']; ?></span></p>
                    <p>rooms : <span><?= $fetch_bookings['rooms']; ?></span></p>
                    <p>adults: <span><?= $fetch_bookings['adults']; ?></span></p>
                    <p>childs: <span><?= $fetch_bookings['childs']; ?></span></p>
                    <form action="" method="post">
                        <input type="hidden" name="delete_id" value="<?= $fetch_bookings['booking_id']; ?>">
                        <input type="submit" value="delete booking" name="delete" class="btn"
                        onclick="return confirm('delete this booking?');">
                    </form>
                </div>
            <?php 
            }
            }else{
            ?>
                <div class="box" style="text-align: center;">
                    <p>no bookings found!</p>
                    <a href="./dashboard.php" class="btn">go to home</a>
                </div>
            <?php 
            }
            ?>
        </div>

    </section>



<!-- sweetalert js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


<!-- custom js -->
<script src="../js/admin.js"></script>

<?php require('../components/message.php'); ?>

</body>
</html>
