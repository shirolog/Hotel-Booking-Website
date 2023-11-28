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

    $select_messages = $conn->prepare("SELECT * FROM `messages` WHERE id= ?");
    $select_messages->execute(array($delete_id));

    if($select_messages->rowCount() > 0){
        $delete_messages= $conn->prepare("DELETE FROM `messages` WHERE id= ?");
        $delete_messages->execute(array($delete_id));
        $success_msg[] = 'Message deleted!';
        $_SESSION['success_msg'] = $success_msg;
    }else{
        $warning_msg[] = 'Message already deleted!';
        $_SESSION['warning_msg'] = $warning_msg;
    }
    header('Location:./messages.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
    
<!-- header section -->
<?php  require('../components/admin_header.php'); ?>


<!-- messages section -->

    <section class="grid">

        <h1 class="heading">messages</h1>

        <div class="box-container">
            <?php 
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            if($select_messages->rowCount() > 0){
                while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){

            ?>
                <div class="box">
                    <p>name : <span><?= $fetch_messages['name']; ?></span></p>
                    <p>email : <span><?= $fetch_messages['email']; ?></span></p>
                    <p>number : <span><?= $fetch_messages['number']; ?></span></p>
                    <p>message : <span><?= $fetch_messages['message']; ?></span></p>
                    <form action="" method="post">
                        <input type="hidden" name="delete_id" value="<?= $fetch_messages['id']; ?>">
                        <input type="submit" value="delete message" name="delete" class="btn"
                        onclick="return confirm('delete this message?');">
                    </form>
                </div>
            <?php 
            }
            }else{
            ?>
                <div class="box" style="text-align: center;">
                    <p>no messages found!</p>
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
