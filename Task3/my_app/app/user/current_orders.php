<?php
include '../db_connection.php';
include '../functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn()) {
    $_SESSION['msg'] = "Önce giriş yapmalısınız.";
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];
$user_type = $_SESSION['user']['user_type'];

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    if ($user_type == 'user') {
        $orders_query = "SELECT id, total_price, order_status, created_at 
                         FROM `order` 
                         WHERE user_id = :user_id AND order_status = 'pending'
                         ORDER BY created_at DESC";
        $stmt = $conn->prepare($orders_query);
        $stmt->bindParam(':user_id', $user_id);
    }

    if ($user_type == 'firm') {
        $company_id = $_SESSION['user']['company_id'];
        $orders_query = "SELECT DISTINCT o.id, o.total_price, o.order_status, o.created_at, u.username as customer_name
                         FROM `order` o
                         JOIN order_items oi ON o.id = oi.order_id
                         JOIN food f ON oi.food_id = f.id
                         JOIN restaurant r ON f.restaurant_id = r.id
                         JOIN users u ON o.user_id = u.id
                         WHERE r.company_id = :company_id AND o.order_status = 'pending'
                         ORDER BY o.created_at DESC";
        $stmt = $conn->prepare($orders_query);
        $stmt->bindParam(':company_id', $company_id);
    }

    $stmt->execute();
    $orders_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bekleyen Siparişler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Restoran Yönetimi</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Anasayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php" style="color: red;">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Bekleyen Siparişler</h2>
        <?php if (!empty($orders_result)): ?>
            <div class="row">
                <?php foreach ($orders_result as $order): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-header">
                                Sipariş ID: <?php echo htmlspecialchars($order['id']); ?>
                            </div>
                            <div class="card-body">
                                <p><strong>Toplam Fiyat:</strong> <?php echo htmlspecialchars($order['total_price']); ?> TL</p>
                                <p><strong>Durum:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>
                                <p><strong>Tarih:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Detaylar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Bekleyen siparişiniz yok.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
