<?php
include '../db_connection.php'; 
include '../functions.php'; 


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn() || $_SESSION['user']['user_type'] !== 'admin') {
    $_SESSION['msg'] = "Bu işlemi gerçekleştirmek için admin olmalısınız.";
    header('location: login.php');
    exit();
}


$result = mysqli_query($db, "SELECT id, username, balance FROM users");
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $amount = floatval($_POST['amount']);

  
    if ($amount > 0) {
    
        $result = mysqli_query($db, "SELECT balance FROM users WHERE id = $user_id");
        $row = mysqli_fetch_assoc($result);
        $current_balance = $row['balance'];

     
        $new_balance = $current_balance + $amount;

    
        $update_query = "UPDATE users SET balance = $new_balance WHERE id = $user_id";
        if (mysqli_query($db, $update_query)) {
            $_SESSION['msg'] = "Bakiyeniz başarıyla güncellendi.";
        } else {
            $_SESSION['msg'] = "Bir hata oluştu. Lütfen tekrar deneyin.";
        }
    } else {
        $_SESSION['msg'] = "Lütfen geçerli bir miktar girin.";
    }

    header('location: load_balance.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta username="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakiyeyi Yükle</title>
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
        <h2>Bakiyeyi Yükle</h2>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-info">
                <?php
                echo $_SESSION['msg'];
                unset($_SESSION['msg']); 
                ?>
            </div>
        <?php endif; ?>

        <form action="load_balance.php" method="POST">
            <div class="mb-3">
                <label for="user_id" class="form-label">Kullanıcı Seçin:</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Seçin</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>">
                            <?php echo htmlspecialchars($user['username']); ?> - Mevcut Bakiye: <?php echo htmlspecialchars($user['balance']); ?> TL
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Yüklemek istediğiniz miktar:</label>
                <input type="number" name="amount" id="amount" class="form-control" required min="1" step="0.01">
            </div>
            <button type="submit" class="btn btn-success">Yükle</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
