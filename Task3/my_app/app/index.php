<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'functions.php';
require_once 'db_connection.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'] ?? null;
if (!$user) {
    header('Location: login.php');
    exit();
}

$user_id = $user['id'];
$user_type = $user['user_type'];

$firma_adi = '';

if ($user_type == 'firm') {
    try {
        $sql = "SELECT c.name FROM company c JOIN users u ON c.id = u.company_id WHERE u.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $firma_adi = $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error fetching company name: " . $e->getMessage());
    }
}

if ($user_type == 'user') {
    header('Location: user/home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa - Restoran Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Restoran Yönetimi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Anasayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" style="color: red;">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Hoşgeldiniz, <?= htmlspecialchars($user['username']) ?></h2>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="profile_info text-center mb-4">
            <strong><?= htmlspecialchars($user['username']) ?></strong>
            <small><i style="color: #888;">(<?= ucfirst(htmlspecialchars($user_type)) ?>)</i></small>
        </div>

        <?php if ($user_type == 'firm') : ?>
            <div class="company_operations">
                <h3>Firma İşlemleri</h3>
                <p>Hoşgeldiniz, <strong><?= htmlspecialchars($firma_adi) ?></strong>!</p>
                <div class="list-group">
                    <a href="add_restaurant.php" class="list-group-item list-group-item-action">Yeni Restoran Ekle</a>
                    <a href="firm/list_restaurants.php" class="list-group-item list-group-item-action">Restoranları Listele</a>
                    <a href="firm/search_restaurant.php" class="list-group-item list-group-item-action">Restoran Arama</a>
                    <a href="firm/add_coupon.php" class="list-group-item list-group-item-action">Kupon Ekleme</a>
                    <a href="firm/add_food.php" class="list-group-item list-group-item-action">Yeni Yemek Ekle</a>
                    <a href="firm/list_food.php" class="list-group-item list-group-item-action">Yemekleri Listele</a>
                    <a href="firm/delete_food.php" class="list-group-item list-group-item-action list-group-item-danger">Yemek Sil</a>
                    <a href="firm/update_food.php" class="list-group-item list-group-item-action">Yemek Güncelleme</a>
                    <a href="firm/search_food.php" class="list-group-item list-group-item-action">Yemek Arama</a>
                    <a href="firm/view_orders.php" class="list-group-item list-group-item-action">Sipariş Görüntüleme</a>
                    <a href="firm/update_order_status.php" class="list-group-item list-group-item-action">Sipariş Durumu Güncelleme</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>