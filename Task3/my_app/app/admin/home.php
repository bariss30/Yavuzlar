<?php 
include('../functions.php');

if (!isAdmin()) {
        $_SESSION['msg'] = "You must log in first";
        header('location: ../login.php');
}

if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['user']);
        header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="../style.css">
        <style>
        .header {
                background: #003366;
        }
        button[name=register_btn] {
                background: #003366;
        }
        </style>
</head>
<body>
        <div class="header">
                <h2>Admin - Home Page</h2>
        </div>
        <div class="content">
                <!-- notification message -->
                <?php if (isset($_SESSION['success'])) : ?>
                        <div class="error success" >
                                <h3>
                                        <?php 
                                                echo $_SESSION['success']; 
                                                unset($_SESSION['success']);
                                        ?>
                                </h3>
                        </div>
                <?php endif ?>

               
                <div class="profile_info">
                        <img src="images/yavuzlarlogo.jpeg"  >

                        <div>
                                <?php  if (isset($_SESSION['user'])) : ?>
                                        <strong><?php echo $_SESSION['user']['username']; ?></strong>

                                        <small>
                                                <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
                                                <br>
                                                <a href="home.php?logout='1'" style="color: red;">logout</a>
                       &nbsp; <br>      
                       <a href="create_user.php">Kullanıcı Ekle</a><br>
<a href="list_users.php">Kullanıcıları Listele</a><br>
<a href="search_user.php">Kullanıcı Ara</a><br>
<a href="ban_user.php">Kullanıcı Banla</a><br>
<a href="filter_users.php">Kullanıcıları Filtrele</a><br>

<a href="create_company.php">Firma Ekle</a><br>
<a href="list_companies.php">Firmaları Listele</a><br>
<a href="search_companies.php">Firma Ara</a><br>
<a href="ban_company.php">Firma Banla</a><br>


<a href="add_coupon.php">Kupon Ekle</a><br>
<a href="list_coupons.php">Kuponları Listele</a><br>
<a href="search_coupons.php">Kupon Ara</a><br>
<a href="delete_coupon.php">Kupon Sil</a><br>
                                        </small>
<strong><a href="load_balance.php">bakiye Yükle Sil</a><br></strong>
                                <?php endif ?>
                        </div>
                </div>
        </div>
</body>