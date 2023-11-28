<?php 

try{
    $db_name = 'mysql:dbname=hotel_db;host=localhost';
    $user_name = 'root';
    $password = 'HTMLCSS1728';

    $conn = new PDO($db_name, $user_name, $password);


    function create_unique_id(){
       $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
       $rand = array();
       $length = strlen($str) - 1;

       for($i = 0; $i < 20; $i++){
            $n = mt_rand(0, $length);
            $rand[] = $str[$n];
       }
       return implode($rand);
    }

}catch(PDOException $e){
    echo 'Connection failed!'. $e->getMessage();
}

?>