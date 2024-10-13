<?php
include '../db_connection.php';
include '../functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $food_id = intval($_GET['id']);
    

    

    $stmt = $conn->prepare("SELECT * FROM food WHERE id = :food_id");
    $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
    $stmt->execute();
    $food = $stmt->fetch();

    if ($food) {
        $note = isset($_POST['note']) ? $_POST['note'] : '';

        if (!isset($_SESSION['cart'][$food_id])) {
            $_SESSION['cart'][$food_id] = [
                'name' => $food['name'],
                'price' => $food['price'],
                'quantity' => 1,
                'note' => $note
            ];

            
            

            $stmt = $conn->prepare("INSERT INTO basket (user_id, food_id, note, quantity) VALUES (:user_id, :food_id, :note, :quantity)");
            $quantity = 1;
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
            $stmt->bindParam(':note', $note, PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $_SESSION['cart'][$food_id]['quantity']++;

          
            

            $stmt = $conn->prepare("UPDATE basket SET quantity = quantity + 1 WHERE user_id = :user_id AND food_id = :food_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'remove') {
    $food_id = intval($_GET['id']);
    
    unset($_SESSION['cart'][$food_id]);


    

    $stmt = $conn->prepare("DELETE FROM basket WHERE user_id = :user_id AND food_id = :food_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
    $stmt->execute();
}

if (isset($_GET['action']) && $_GET['action'] === 'update' && isset($_POST['note'])) {
    $food_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$food_id])) {
        $_SESSION['cart'][$food_id]['note'] = $_POST['note'];

        


        
        $stmt = $conn->prepare("UPDATE basket SET note = :note WHERE user_id = :user_id AND food_id = :food_id");
        $stmt->bindParam(':note', $_POST['note'], PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

$cart_items = $_SESSION['cart'];
$total_price = 0;
?>
