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

$user = $_SESSION['user'];
$user_id = $user['id'];
$user_type = $user['user_type'];




$foods_query = "SELECT * FROM food";
$stmt = $conn->prepare($foods_query);
$stmt->execute();
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);




$discounted_foods_query = "SELECT * FROM food WHERE discount != 0";
$stmt_discounted = $conn->prepare($discounted_foods_query);
$stmt_discounted->execute();
$discounted_foods = $stmt_discounted->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anasayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Restoran Yönetimi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Anasayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php" style="color: red;">Çıkış Yap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="restaurant_list.php" style="color: red;">Restoranlar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="header">
            <h2 class="text-center">Hoşgeldiniz, <?php echo htmlspecialchars($user['username']); ?></h2>
        </div>

        <div class="user-info mb-4">
            <h3>Bakiye: <?php echo getUserBalance($user_id); ?> TL</h3>
            <h4>Profil Bilgileri</h4>
            <p>Kullanıcı Adı: <?php echo htmlspecialchars($user['username']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

       
        

        <h4>Yemekler</h4>
        <div class="row">
            <?php foreach ($foods as $food): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php
                        $imagePath = "../uploads/" . basename($food['image_path']);
                        if (file_exists($imagePath)): ?>
                            <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($food['name']); ?>">
                        <?php else: ?>
                            <p class="text-center">Resim bulunamadı.</p>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($food['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($food['description']); ?></p>

                            <?php if ($food['discount'] == 0): ?>
                                <p class="card-text"><strong>Fiyat: <?php echo htmlspecialchars($food['price']); ?> TL</strong></p>
                            <?php endif; ?>

                            <a href="food_details.php?id=<?php echo $food['id']; ?>" class="btn btn-primary">Detayları Gör</a>
                            <a href="cart.php?action=add&id=<?php echo $food['id']; ?>" class="btn btn-warning">Sepete Ekle</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

     
        
        
        <h4>İndirimli Yemekler</h4>
        <div class="row">
            <?php foreach ($discounted_foods as $discounted_food): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php
                        $imagePath = "../uploads/" . basename($discounted_food['image_path']);
                        if (file_exists($imagePath)): ?>
                            <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($discounted_food['name']); ?>">
                        <?php else: ?>
                            <p class="text-center">Resim bulunamadı.</p>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($discounted_food['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($discounted_food['description']); ?></p>
                            <p class="card-text">
                                <strong>İndirimli Fiyat: 
                                    <?php
                                    $discounted_price = $discounted_food['price'] - $discounted_food['discount'];
                                    echo htmlspecialchars($discounted_price); 
                                    ?> TL
                                </strong>
                                <br>
                                <small><del>Eski Fiyat: <?php echo htmlspecialchars($discounted_food['price']); ?> TL</del></small>
                            </p>
                            <a href="food_details.php?id=<?php echo $discounted_food['id']; ?>" class="btn btn-primary">Detayları Gör</a>
                            <a href="cart.php?action=add&id=<?php echo $discounted_food['id']; ?>" class="btn btn-warning">Sepete Ekle</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h4>Aktif Siparişler</h4>
        <a href="current_orders.php" class="btn btn-info mb-2">Aktif Siparişleri Görüntüle</a>

        <h4>Geçmiş Siparişler</h4>
        <a href="order_history.php" class="btn btn-secondary mb-4">Geçmiş Siparişleri Görüntüle</a>

        <h4>Sepet</h4>
        <a href="cart.php" class="btn btn-warning">Sepeti Görüntüle</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
