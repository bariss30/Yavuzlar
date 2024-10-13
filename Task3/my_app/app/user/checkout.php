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

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('location: cart.php');
    exit();
}

$cart_items = $_SESSION['cart'];
$total_price = 0;

foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

$user_id = $_SESSION['user']['id'];




$balance_query = "SELECT balance FROM users WHERE id = :user_id";
$stmt = $conn->prepare($balance_query);
$stmt->execute(['user_id' => $user_id]);
$user_balance = $stmt->fetchColumn();




$coupons_query = "SELECT name, discount FROM coupon";
$stmt = $conn->query($coupons_query);
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_coupon = $_POST['coupon'] ?? ''; 
    $discount = 0;

   
    

    if (!empty($selected_coupon)) {
        $coupon_query = "SELECT discount FROM coupon WHERE name = :name AND expiry_date > CURDATE()";
        $stmt = $conn->prepare($coupon_query);
        $stmt->execute(['name' => $selected_coupon]);
        $coupon_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($coupon_data) {
            $discount = $coupon_data['discount'];
        } else {
            $_SESSION['error_msg'] = "Geçersiz kupon kodu.";
            header('location: checkout.php');
            exit();
        }
    }

    $discounted_price = $total_price - ($total_price * ($discount / 100));


    

    if ($user_balance < $discounted_price) {
        $_SESSION['error_msg'] = "Yetersiz bakiye. Lütfen bakiyenizi yükleyin.";
        header('location: checkout.php');
        exit();
    }

   
    

    $order_status = "pending";
    $created_at = date('Y-m-d H:i:s');

    

    try {
        $conn->beginTransaction();

    
        

        $order_query = "INSERT INTO `order` (user_id, total_price, order_status, created_at) VALUES (:user_id, :total_price, :order_status, :created_at)";
        $stmt = $pdo->prepare($order_query);
        $stmt->execute([
            'user_id' => $user_id,
            'total_price' => $discounted_price,
            'order_status' => $order_status,
            'created_at' => $created_at
        ]);
        $order_id = $conn->lastInsertId();


        

        foreach ($cart_items as $food_id => $item) {
            $quantity = $item['quantity'];
            $price = $item['price'];

           
            

            $food_query = "SELECT restaurant_id FROM food WHERE id = :food_id";
            $stmt = $conn->prepare($food_query);
            $stmt->execute(['food_id' => $food_id]);
            $restaurant_id = $stmt->fetchColumn();

            

            $order_item_query = "INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (:order_id, :food_id, :quantity, :price)";
            $stmt = $conn->prepare($order_item_query);
            $stmt->execute([
                'order_id' => $order_id,
                'food_id' => $food_id,
                'quantity' => $quantity,
                'price' => $price
            ]);
        }

    
        

        $new_balance = $user_balance - $discounted_price;
        $update_balance_query = "UPDATE users SET balance = :balance WHERE id = :user_id";
        $stmt = $conn->prepare($update_balance_query);
        $stmt->execute(['balance' => $new_balance, 'user_id' => $user_id]);

       
        

        $conn->commit();



        unset($_SESSION['cart']);
        header('location: order_success.php');
        exit();

    } catch (Exception $e) {
      
        
        $conn->rollBack();
        die("Sipariş oluşturulurken bir hata oluştu: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
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
        <h2>Ödeme Sayfası</h2>
        <?php
        if (isset($_SESSION['error_msg'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_msg'] . '</div>';
            unset($_SESSION['error_msg']);
        }
        ?>
        <h4>Mevcut Bakiyeniz: <?php echo htmlspecialchars($user_balance); ?> TL</h4>
        <h4>Sepetinizdeki Ürünler</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Yemek Adı</th>
                    <th>Fiyat</th>
                    <th>Adet</th>
                    <th>Toplam</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): 
                    $item_total = $item['price'] * $item['quantity'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?> TL</td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($item_total); ?> TL</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4>Toplam Tutar: <?php echo htmlspecialchars($total_price); ?> TL</h4>

        <form method="POST" action="checkout.php">
            <h4>Ödeme Bilgileri</h4>
            <div class="mb-3">
                <label for="address" class="form-label">Adresiniz</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="coupon" class="form-label">Kupon Seçin</label>
                <select class="form-select" id="coupon" name="coupon">
                    <option value="">Kupon Seçmeyin</option>
                    <?php foreach ($coupons as $coupon): ?>
                        <option value="<?php echo $coupon['name']; ?>">
                            <?php echo $coupon['name'] . ' - ' . $coupon['discount'] . '% indirim'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Siparişi Onayla</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
