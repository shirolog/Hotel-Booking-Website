<?php 
require('./connect.php');

setcookie('admin_id', '', time() - 1, '/');

header('Location: ../admin/login.php');
exit();

?>
