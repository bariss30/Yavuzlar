<?php
session_start(); 


if (isset($_SESSION['user'])) {
   
    unset($_SESSION['user']);
    
    
    $_SESSION['msg'] = "Başarıyla çıkış yaptınız.";
}


header('location: login.php');
exit();
?>
