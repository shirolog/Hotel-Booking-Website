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

    $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE id= ?");
    $select_admins->execute(array($delete_id));

    if($select_admins->rowCount() > 0){
        $delete_admins= $conn->prepare("DELETE FROM `admins` WHERE id= ?");
        $delete_admins->execute(array($delete_id));
        $success_msg[] = 'Admin deleted!';
        $_SESSION['success_msg'] = $success_msg;
    }else{
        $warning_msg[] = 'Admin already deleted!';
        $_SESSION['warning_msg'] = $warning_msg;
    }
    header('Location:./admins.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
    
<!-- header section -->
<?php  require('../components/admin_header.php'); ?>


<!-- admins section -->

    <section class="grid">

        <h1 class="heading">admins</h1>

        <div class="box-container">

            <div class="box">
                <p>careate a new admin</p>
                <a href="./register.php" class="btn">register now</a>
            </div>
            <?php 
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            if($select_admins->rowCount() > 0){
                while($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)){

            ?>
                <div class="box"<?php if($fetch_admins['name'] == 'admin'){echo
                'style="display:none;"';} ?>>
                    <p>name: <span><?= $fetch_admins['name']; ?></span></p>
                    <form action="" method="post">
                        <input type="hidden" name="delete_id" value="<?= $fetch_admins['id']; ?>">
                        <input type="submit" value="delete admins" name="delete" class="btn"
                        onclick="return confirm('delete this admin?');">
                    </form>
                </div>
            <?php 
            }
            }else{
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
