<?php
session_start(); 


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin'){
        $_SESSION['username'] = $username; 
        header("Location: admin.php");
    } 
    if ($username === 'baris' && $password === 'savak'){
        $_SESSION['username'] = $username;
        header("Location: main.php");
    }
    else {
        echo "<div class='alert alert-danger'>Geçersiz kullanıcı adı veya şifre</div>";
    }
}
?>
