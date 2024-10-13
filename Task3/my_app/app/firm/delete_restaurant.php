<?php 
session_start();
include('../functions.php'); 
include('../db_connection.php'); 




if (!isLoggedIn() || $_SESSION['user']['user_type'] != 'firm') {
    $_SESSION['msg'] = "Firma olarak giriş yapmalısınız";
    header('location: login.php');
    exit();
}



if (isset($_GET['id'])) {
    $restaurant_id = $_GET['id'];
    $company_id = $_SESSION['user']['company_id'];

   
    
    $query = "DELETE FROM restaurant WHERE id = :restaurant_id AND company_id = :company_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Restoran başarıyla silindi";
    } else {
        $_SESSION['msg'] = "Restoran silinirken bir hata oluştu";
    }

    header('location: list_restaurants.php');
    exit();
}
?>
