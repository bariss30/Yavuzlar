<?php

session_start();

require_once 'conn.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['username'] = $username; 
        header("Location: admin.php");
        exit();
    }

    $query = "SELECT COUNT(*) as count FROM `member` WHERE `username` = :username AND `password` = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $row = $stmt->fetch();
    $count = $row['count'];

    if ($count > 0) {
        $_SESSION['username'] = $username; 
        header('location:home.php');
    } else {
        $_SESSION['error'] = "Invalid username or password";
        header('location:login.php');
    }
}
?>
