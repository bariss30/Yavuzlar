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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Başarılı</title>
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
        <h2>Teşekkürler!</h2>
        <p>Siparişiniz başarıyla alınmıştır.</p>
        <p>Sipariş durumunuzu kontrol etmek için lütfen kullanıcı panelinize gidin.</p>
        
        <h4>Son Sipariş Bilgileri</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Yemek Adı</th>
                    <th>Fiyat</th>
                    <th>Adet</th>
                    <th>Toplam</th>
                    <?php if ($user_type == 'firm'): ?>
                        <th>Restoran</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                try {


                    $order_query = "SELECT * FROM `order` WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
                    $stmt = $conn->prepare($order_query);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $order = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_price = 0;

                    if ($order) {
                        $order_id = $order['id'];


                        $order_items_query = "SELECT oi.*, f.name AS food_name, r.name AS restaurant_name 
                                              FROM order_items oi
                                              JOIN food f ON oi.food_id = f.id
                                              JOIN restaurant r ON f.restaurant_id = r.id
                                              WHERE oi.order_id = :order_id";

                        if ($user_type == 'firm') {


                            $restaurant_query = "SELECT id FROM restaurant WHERE company_id = (SELECT company_id FROM users WHERE id = :user_id)";
                            $stmt_restaurant = $conn->prepare($restaurant_query);
                            $stmt_restaurant->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                            $stmt_restaurant->execute();
                            $restaurant_ids = $stmt_restaurant->fetchAll(PDO::FETCH_COLUMN);

                            if (!empty($restaurant_ids)) {
                                $restaurant_ids_str = implode(',', $restaurant_ids);
                                $order_items_query .= " AND f.restaurant_id IN ($restaurant_ids_str)";
                            }
                        }

                        $stmt_items = $conn->prepare($order_items_query);
                        $stmt_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                        $stmt_items->execute();
                        $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);



                        foreach ($order_items as $item) {
                            $item_total = $item['price'] * $item['quantity'];
                            $total_price += $item_total;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['food_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?> TL</td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($item_total); ?> TL</td>
                        <?php if ($user_type == 'firm'): ?>
                            <td><?php echo htmlspecialchars($item['restaurant_name']); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php
                        }
                    }
                } catch (PDOException $e) {
                    echo "Hata: " . $e->getMessage();
                }
                ?>
            </tbody>
        </table>
        <h4>Toplam Tutar: <?php echo htmlspecialchars($total_price); ?> TL</h4>

        <a href="home.php" class="btn btn-primary">Anasayfaya Dön</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
