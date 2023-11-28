<?php 
require('./assets/components/connect.php');
session_start();


if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/' );
    header('Location:./index.php');
    exit();
}


if(isset($_POST['cancel'])){
    $booking_id = $_POST['booking_id'];
    $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);
    
    $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id= ?");
    $select_bookings->execute(array($booking_id));

    if($select_bookings->rowCount() > 0){
        $delete_bookings = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
        $delete_bookings->execute(array($booking_id));
        $success_msg[] = 'booking cancelled successfully!';
        $_SESSION['success_msg'] = $success_msg;
    }else{
        $warning_msg[] = 'booking cancelled already!';
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
    <title>bookings</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- swiper css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>

    
    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>

<?php require('./assets/components/user_header.php'); ?>

<!-- bookings section -->
<section class="bookings">

    <h1 class="heading">my bookings</h1>

    <div class="box-container">
        <?php 
        $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id= ?");
        $select_bookings->execute(array($user_id));
        if($select_bookings->rowCount() > 0){
            while($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)){
        ?>
            <div class="box">
                <p>name: <span><?= $fetch_bookings['name']; ?></span></p>
                <p>email: <span><?= $fetch_bookings['email']; ?></span></p>
                <p>number: <span><?= $fetch_bookings['number']; ?></span></p>
                <p>check_in: <span><?= $fetch_bookings['check_in']; ?></span></p>
                <p>check_out: <span><?= $fetch_bookings['check_out']; ?></span></p>
                <p>rooms: <span><?= $fetch_bookings['rooms']; ?></span></p>
                <p>adults: <span><?= $fetch_bookings['adults']; ?></span></p>
                <p>childs: <span><?= $fetch_bookings['childs']; ?></span></p>
                <p>booking id: <span><?= $fetch_bookings['booking_id']; ?></span></p>
                <form action="" method="post">
                    <input type="hidden" name="booking_id" value="<?= $fetch_bookings['booking_id']; ?>">
                    <input type="submit" value="cancel booking" name="cancel" class="btn"
                    onclick="return confirm('cancel this booking?');">
                </form>
            </div>
        <?php 
        }
        }else{
        ?>
            <div class="box" style="text-align:center">
                <p style="padding-bottom: .5rem; text-transform:capitalize;">no bookings found!</p>
                <a href="./index.php#reservation" class="btn">book new</a>
            </div>
        <?php 
        }        
        ?>
    </div>

</section>


<?php require('./assets/components/footer.php'); ?>




<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- sweetalert js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>
    
<?php require('./assets/components/message.php'); ?>

</body>
</html>