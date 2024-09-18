<?php 
include('functions.php');

// Kullanıcı giriş yapmış mı kontrol et
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Home Page</h2>
    </div>
    <div class="content">
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error success">
                <h3>
                    <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- logged in user information -->
        <div class="profile_info">
            <img src="images/user_profile.png" alt="Profile Image">

            <div>
                <?php if (isset($_SESSION['user'])) : ?>
                    <strong><?php echo $_SESSION['user']['username']; ?></strong>

                    <small>
                        <i style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
                        <br>
                        <a href="index.php?logout='1'" style="color: red;">Logout</a>
                    </small>
                <?php endif ?>
            </div>
        </div>

        <!-- Firma kullanıcıları için özel işlemler -->
        <?php if ($_SESSION['user']['user_type'] == 'firm') : ?>
            <div class="company_operations">
                <h3>Firma İşlemleri</h3>
                <ul>
                    <li><a href="add_restaurant.php">Yeni Restoran Ekle</a></li> <!-- Restoran ekleme bağlantısı -->
                    <li><a href="add_dish.php">Yeni Yemek Ekle</a></li>
                    <li><a href="manage_dishes.php">Yemekleri Yönet</a></li>
                    <li><a href="view_orders.php">Siparişleri Görüntüle</a></li>
                    <li><a href="view_customers.php">Müşterileri Görüntüle</a></li>
                    <li><a href="apply_discount.php">İndirim Uygula</a></li>
                    <!-- Diğer firma işlemleri -->
                </ul>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
