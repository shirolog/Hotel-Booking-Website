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



if(isset($_POST['check'])){
    $check_in = $_POST['check_in'];
    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

    $total_rooms = 0;

    $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE  check_in= ?");
    $select_bookings->execute(array($check_in));

    while($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)){
        $total_rooms += $fetch_bookings['rooms'];
    }

    if($total_rooms >= 30){
        $warning_msg[] = 'room are not available!';
        $_SESSION['warning_msg'] = $warning_msg;
    }else{
        $success_msg[] = 'room are available!';
        $_SESSION['success_msg'] = $success_msg;
    }
    header('Location:./index.php');
    exit();
}


if(isset($_POST['book'])){

    $booking_id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $rooms = $_POST['rooms'];
    $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
    $check_in = $_POST['check_in'];
    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
    $check_out = $_POST['check_out'];
    $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
    $adults = $_POST['adults'];
    $adults = filter_var($adults, FILTER_SANITIZE_STRING);
    $childs = $_POST['childs'];
    $childs = filter_var($childs, FILTER_SANITIZE_STRING);

    $total_rooms = 0;

    $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE  check_in= ?");
    $select_bookings->execute(array($check_in));

    while($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)){
        $total_rooms += $fetch_bookings['rooms'];
    }

    if($total_rooms >= 30){
        $warning_msg[] = 'room are not available!';
        $_SESSION['warning_msg'] = $warning_msg;
    }else{
        $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id= ?
         AND name=? AND email=? AND number= ? AND rooms=? AND check_in= ? AND
        check_out= ? AND adults= ? AND childs= ?");
        $select_bookings->execute(array($user_id, $name, $email, $number, $rooms, $check_in,
        $check_out, $adults, $childs));

        if($select_bookings->rowCount() > 0){
            $warning_msg[] = 'room booked already!';
            $_SESSION['warning_msg'] = $warning_msg;
        }else{
            $insert_bookings = $conn->prepare("INSERT INTO `bookings` (user_id, booking_id, name, email, number,
            rooms, check_in, check_out, adults, childs) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_bookings->execute(array($user_id, $booking_id, $name, $email, $number, $rooms,
            $check_in, $check_out, $adults, $childs));
            $success_msg[] = 'room booked successfully!';
            $_SESSION['success_msg'] = $success_msg;
        }

    }
    header('Location:./index.php');
    exit();
}


if(isset($_POST['send'])){
    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    $select_messages = $conn->prepare("SELECT * FROM  `messages` WHERE name= ?
    AND email= ? AND number= ? AND message= ?");
    $select_messages->execute(array($name, $email, $number, $msg));

    if($select_messages->rowCount() > 0){
        $warning_msg[] = 'message sent already!';
        $_SESSION['warning_msg'] = $warning_msg;

    }else{
        $insert_messages= $conn->prepare("INSERT INTO  `messages` (id, name, email, number, message )
        VALUES(?,?,?,?,?)");
        $insert_messages->execute(array($id, $name, $email, $number, $msg));
        $success_msg[] = 'message send successfully!';
        $_SESSION['success_msg'] = $success_msg;
    }
    header('Location:./index.php');
    exit();
}





?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- swiper css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>

    
    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>

<?php require('./assets/components/user_header.php'); ?>

<!-- home section -->
<section class="home" id="home">

    <div class="swiper home-silder">

        <div class="swiper-wrapper">
            <div class="swiper-slide box">
                <img src="./assets/images/home-img-1.jpg" alt="">
                <div class="flex">
                    <h3>luxurious rooms</h3>
                    <a href="#availablility" class="btn">check availablility</a>
                </div>
            </div>
            
            <div class="swiper-slide box">
                <img src="./assets/images/home-img-2.jpg" alt="">
                <div class="flex">
                    <h3>foodds and drinks</h3>
                    <a href="#reservation" class="btn">make a reservation</a>
                </div>
            </div>
            
            <div class="swiper-slide box">
                <img src="./assets/images/home-img-3.jpg" alt="">
                <div class="flex">
                    <h3>luxurious halls</h3>
                    <a href="#contact" class="btn">contact us</a>
                </div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>



<!-- availability section -->
<section class="availability" id="availability">

    <form action="" method="post">
        <div class="flex">
            <div class="box">
                <p>check in <span>*</span></p>
                <input type="date" name="check_in" class="input" required>
            </div>

            <div class="box">
                <p>check out <span>*</span></p>
                <input type="date" name="check_out" class="input" required>
            </div>

            <div class="box">
                <p>adults <span>*</span></p>
                <select name="adults" class="input" required>
                    <option value="1">1 adults</option>
                    <option value="2">2 adults</option>
                    <option value="3">3 adults</option>
                    <option value="4">4 adults</option>
                    <option value="5">5 adults</option>
                    <option value="6">6 adults</option>
                </select>
            </div>

            <div class="box">
                <p>childs <span>*</span></p>
                <select name="childs" class="input" required>
                    <option value="-">0 childs</option>
                    <option value="1">1 childs</option>
                    <option value="2">2 childs</option>
                    <option value="3">3 childs</option>
                    <option value="4">4 childs</option>
                    <option value="5">5 childs</option>
                    <option value="6">6 childs</option>
                </select>
            </div>

            <div class="box">
                <p>rooms <span>*</span></p>
                <select name="rooms" class="input" required>
                    <option value="1">1 rooms</option>
                    <option value="2">2 rooms</option>
                    <option value="3">3 rooms</option>
                    <option value="4">4 rooms</option>
                    <option value="5">5 rooms</option>
                    <option value="6">6 rooms</option>
                </select>
            </div>
        </div>
        <input type="submit" value="check availability" name="check" class="btn">
    </form>
</section>


<!-- about section -->
<section class="about" id="about">

    <div class="row">
        <div class="image">
            <img src="./assets/images/about-img-1.jpg" alt="">
        </div>

        <div class="content">
            <h3>best staff</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
            Dicta, cum atque ea ut ducimus fugiat!</p>
            <a href="#reservation" class="btn">make a reservation</a>
        </div>
    </div>

    <div class="row revers">
        <div class="image">
            <img src="./assets/images/about-img-2.jpg" alt="">
        </div>

        <div class="content">
            <h3>best foods</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
            Dicta, cum atque ea ut ducimus fugiat!</p>
            <a href="#contact" class="btn">contact us</a>
        </div>
    </div>

    <div class="row">
        <div class="image">
            <img src="./assets/images/about-img-3.jpg" alt="">
        </div>

        <div class="content">
            <h3>swimming pool</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
            Dicta, cum atque ea ut ducimus fugiat!</p>
            <a href="#availability" class="btn">check availability</a>
        </div>
    </div>

</section>

<!-- services section -->
<section class="services">

    <div class="box-container">

    <div class="box">
        <img src="./assets/images/icon-1.png" alt="">
        <h3>food & drinks</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eaque, iusto.</p>
    </div>

    <div class="box">
        <img src="./assets/images/icon-2.png" alt="">
        <h3>outdoor dining</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eaque, iusto.</p>
    </div>

    <div class="box">
        <img src="./assets/images/icon-3.png" alt="">
        <h3>beach view</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eaque, iusto.</p>
    </div>

    <div class="box">
        <img src="./assets/images/icon-4.png" alt="">
        <h3>decorations</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eaque, iusto.</p>
    </div>

    <div class="box">
        <img src="./assets/images/icon-5.png" alt="">
        <h3>swimming pool</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eaque, iusto.</p>
    </div>

    <div class="box">
        <img src="./assets/images/icon-6.png" alt="">
        <h3>resort beach</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eaque, iusto.</p>
    </div>

    </div>

</section>


<!-- reservation section -->
<section class="reservation" id="reservation">
    
    <form action="" method="post">
        <h3>make a reservation</h3>
            <div class="flex">

                <div class="box">
                    <p>your name <span>*</span></p>
                    <input type="text" name="name" maxlength="50" required
                    placeholder="enter your name" class="input">
                </div>

                <div class="box">
                    <p>your email <span>*</span></p>
                    <input type="email" name="email" maxlength="50" required
                    placeholder="enter your email" class="input">
                </div>

                <div class="box">
                    <p>your number <span>*</span></p>
                    <input type="number" name="number" maxlength="10" min="0" max="999999999" required
                    placeholder="enter your number" class="input">
                </div>

                
                <div class="box">
                    <p>rooms <span>*</span></p>
                    <select name="rooms" class="input" required>
                        <option value="1">1 rooms</option>
                        <option value="2">2 rooms</option>
                        <option value="3">3 rooms</option>
                        <option value="4">4 rooms</option>
                        <option value="5">5 rooms</option>
                        <option value="6">6 rooms</option>
                    </select>
                </div>

                <div class="box">
                    <p>check in <span>*</span></p>
                    <input type="date" name="check_in" class="input" required>
                </div>

                <div class="box">
                    <p>check out <span>*</span></p>
                    <input type="date" name="check_out" class="input" required>
                </div>

                <div class="box">
                    <p>adults <span>*</span></p>
                    <select name="adults" class="input" required>
                        <option value="1">1 adults</option>
                        <option value="2">2 adults</option>
                        <option value="3">3 adults</option>
                        <option value="4">4 adults</option>
                        <option value="5">5 adults</option>
                        <option value="6">6 adults</option>
                    </select>
                </div>

                <div class="box">
                    <p>childs <span>*</span></p>
                    <select name="childs" class="input" required>
                        <option value="-">0 childs</option>
                        <option value="1">1 childs</option>
                        <option value="2">2 childs</option>
                        <option value="3">3 childs</option>
                        <option value="4">4 childs</option>
                        <option value="5">5 childs</option>
                        <option value="6">6 childs</option>
                    </select>
                </div>

            </div>
            <input type="submit" value="book now" name="book" class="btn">
        </form>
</section>

<!-- gallery section -->
<section class="gallery" id="gallery">

    <div class="swiper gallery-slider">

        <div class="swiper-wrapper">
            <img class="swiper-slide" src="./assets/images/gallery-img-1.jpg" alt="">
            <img class="swiper-slide" src="./assets/images/gallery-img-2.webp" alt="">
            <img class="swiper-slide" src="./assets/images/gallery-img-3.webp" alt="">
            <img class="swiper-slide" src="./assets/images/gallery-img-4.webp" alt="">
            <img class="swiper-slide" src="./assets/images/gallery-img-5.webp" alt="">
            <img class="swiper-slide" src="./assets/images/gallery-img-6.webp" alt="">
        </div>
        <div class="swiper-pagination"></div>
    </div>

</section>

<!-- contact section -->
<section class="contact" id="contact">

    <div class="row">
        <form action="" method="post">
            <h3>send us message</h3>
            <input type="text" name="name" required maxlength="50" placeholder="enter your name"
             class="box">
            <input type="email" name="email" required maxlength="50" placeholder="enter your email" 
            class="box">
            <input type="number" name="number" required maxlength="10" min="0" max="9999999999" 
            placeholder="enter your number" class="box">
            <textarea name="msg" class="box" required maxlength="1000" 
            placeholder="enter your message" cols="30" rows="10"></textarea>
            <input type="submit" value="send message" name="send" class="btn">
        </form>

        <div class="faq">
            <h3 class="title">frequently asked questions</h3>

            <div class="box active">
                <h3>how to cancel?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
                Beatae suscipit voluptas id, hic iure voluptates?</p>
            </div>

            <div class="box">
                <h3>is there any vacancy?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
                Beatae suscipit voluptas id, hic iure voluptates?</p>
            </div>

            <div class="box">
                <h3>what are patment methods?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
                Beatae suscipit voluptas id, hic iure voluptates?</p>
            </div>

            <div class="box">
                <h3>how to claim coupons codes?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
                Beatae suscipit voluptas id, hic iure voluptates?</p>
            </div>

            <div class="box">
                <h3>what are the age requirements?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
                Beatae suscipit voluptas id, hic iure voluptates?</p>
            </div>
        </div>

    </div>

</section>

<!-- reviews section -->
<section class="reviews" id="reviews">

    <div class="swiper reviews-slider">

        <div class="swiper-wrapper">

            <div class="box swiper-slide">
                <img src="./assets/images/pic-1.png" alt="">
                <h3>john deo</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Autem impedit, in fugit itaque quam nesciunt.</p>
            </div>

            <div class="box swiper-slide">
                <img src="./assets/images/pic-2.png" alt="">
                <h3>john deo</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Autem impedit, in fugit itaque quam nesciunt.</p>
            </div>

            <div class="box swiper-slide">
                <img src="./assets/images/pic-3.png" alt="">
                <h3>john deo</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Autem impedit, in fugit itaque quam nesciunt.</p>
            </div>

            <div class="box swiper-slide">
                <img src="./assets/images/pic-4.png" alt="">
                <h3>john deo</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Autem impedit, in fugit itaque quam nesciunt.</p>
            </div>

            <div class="box swiper-slide">
                <img src="./assets/images/pic-5.png" alt="">
                <h3>john deo</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Autem impedit, in fugit itaque quam nesciunt.</p>
            </div>

            <div class="box swiper-slide">
                <img src="./assets/images/pic-6.png" alt="">
                <h3>john deo</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Autem impedit, in fugit itaque quam nesciunt.</p>
            </div>
        </div>
        <div class="swiper-pagination"></div>
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