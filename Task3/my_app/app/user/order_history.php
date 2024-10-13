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

$user_id = $_SESSION['user']['id']; 


$query = "SELECT o.id, o.order_status, o.total_price, o.created_at 
          FROM `order` o 
          WHERE o.user_id = :user_id AND o.order_status = 'completed' 
          ORDER BY o.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Geçmişi</title>
</head>
<body>
    <h1>Sipariş Geçmişi</h1>

    <?php if (!empty($results)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Sipariş ID</th>
                    <th>Sipariş Durumu</th>
                    <th>Toplam Fiyat</th>
                    <th>Oluşturulma Tarihi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?> TL</td>
                        <td><?php echo date('d-m-Y H:i:s', strtotime($order['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Henüz tamamlanmış bir siparişiniz yok</p>
    <?php endif; ?>

    <a href="home.php">Ana Sayfaya Dön</a>
</body>
</html>
