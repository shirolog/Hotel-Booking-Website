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

$select_admins = $conn->prepare("SELECT * FROM `admins` WHERE id= ? LIMIT 1");
$select_admins->execute(array($admin_id));
$fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);


if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    if(!empty($name)){
        $select_admins = $conn->prepare("SELECT * FROM  `admins` WHERE name= ?");
        $select_admins->execute(array($name));
        if($select_admins->rowCount() > 0){
            $warning_msg[] = 'username already taken!';
            $_SESSION['warning_msg'] = $warning_msg;
        }else{
            $update_admins = $conn->prepare("UPDATE  `admins` SET  name= ? WHERE id= ?");
            $update_admins->execute(array($name, $admin_id));
            $success_msg[] = 'Username updated!';
            $_SESSION['success_msg'] = $success_msg;
        }
    }

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $fetch_admins['password'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $c_pass = sha1($_POST['c_pass']);
    $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
            $warning_msg[] = 'Old password not matched!';
            $_SESSION['warning_msg'] = $warning_msg;
        }elseif($new_pass != $c_pass){
            $warning_msg[] = 'New password not matched!';
            $_SESSION['warning_msg'] = $warning_msg;
        }else{
            if($new_pass != $empty_pass){
                $update_admins = $conn->prepare("UPDATE  `admins` SET  password= ? WHERE id= ?");
                $update_admins->execute(array($c_pass, $admin_id));
                $success_msg[] = 'Password updated!';
                $_SESSION['success_msg'] = $success_msg;
            }else{
                $warning_msg[] = 'Please enter new password!';
                $_SESSION['warning_msg'] = $warning_msg;
            }
        }
    }

    header('Location:./update.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
    
<!-- header section -->
<?php  require('../components/admin_header.php'); ?>

<!-- update section -->
<section class="form-container">

    <form action="" method="post">
        <h3>update profile</h3>
        <input type="text" name="name" required class="box" placeholder="<?= $fetch_admins['name']; ?>"
        maxlength="20" class="box" oninput="this.value= this.value.replace(/\s/g, '');">
        <input type="password" name="old_pass" class="box" placeholder="enter old password"
        maxlength="20" class="box"  oninput="this.value= this.value.replace(/\s/g, '');">
        <input type="password" name="new_pass" class="box" placeholder="enter new password"
        maxlength="20" class="box"  oninput="this.value= this.value.replace(/\s/g, '');">
        <input type="password" name="c_pass" class="box" placeholder="confirm new password"
        maxlength="20" class="box"  oninput="this.value= this.value.replace(/\s/g, '');">
        <input type="submit" name="submit" value="update now" class="btn">
    </form>

</section>


<!-- sweetalert js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="../js/admin.js"></script>

    
<?php require('../components/message.php'); ?>
</body>
</html>

