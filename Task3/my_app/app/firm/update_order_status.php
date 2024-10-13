<?php
include '../db_connection.php'; 
include '../functions.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn()) {
    header('location: login.php'); 
    exit();
}

if (isset($_POST['update_order'])) {
    $order_id = intval($_POST['order_id']); 
    $new_status = e($_POST['order_status']); 

    try {
        $query = "UPDATE `order` SET order_status = :order_status WHERE id = :order_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':order_status', $new_status);
        $stmt->bindParam(':order_id', $order_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Sipariş durumu başarıyla güncellendi."; 
        } else {
            $_SESSION['error'] = "Sipariş durumu güncellenirken bir hata oluştu."; 
        }

        header('location: view_orders.php'); 
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Hata: " . $e->getMessage();
        header('location: view_orders.php'); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Durumu Güncelle</title>
</head>
<body>
    <h1>Sipariş Durumu Güncelle</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form action="update_order_status.php" method="POST">
        <label for="order_id">Sipariş ID:</label>
        <input type="number" name="order_id" required>

        <label for="order_status">Yeni Sipariş Durumu:</label>
        <select name="order_status" required>
            <option value="pending">Beklemede</option>
            <option value="completed">Tamamlandı</option>
            <option value="cancelled">İptal Edildi</option>
        </select>

        <button type="submit" name="update_order">Güncelle</button>
    </form>

    <a href="view_orders.php">Siparişleri Görüntüle</a>
</body>
</html>
