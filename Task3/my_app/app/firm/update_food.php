<?php
session_start(); 

include('../db_connection.php');
include('../functions.php'); 

if (!isLoggedIn() || $_SESSION['user']['user_type'] != 'firm') {
    echo "Bu sayfaya erişim izniniz yok.";
    exit();
}

$user_id = $_SESSION['user']['id'];


$sql_user = "SELECT company_id FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->execute([$user_id]);
$company_id = $stmt_user->fetchColumn();
$stmt_user->closeCursor();

if ($company_id) {
    
    $sql_company = "SELECT id FROM restaurant WHERE company_id = ?";
    $stmt_company = $conn->prepare($sql_company);
    $stmt_company->execute([$company_id]);
    $restaurant_id = $stmt_company->fetchColumn();
    $stmt_company->closeCursor();

    if ($restaurant_id) {
       


        $sql_food = "SELECT * FROM food WHERE restaurant_id = ?";
        $stmt_food = $conn->prepare($sql_food);
        $stmt_food->execute([$restaurant_id]);
        $result_food = $stmt_food->fetchAll(PDO::FETCH_ASSOC);
    }
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['food_id'])) {
    $food_id = intval($_POST['food_id']); 
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']); 
    $discount = intval($_POST['discount']); 


    
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . uniqid() . '-' . $image_name; 
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path); 
    } else {
        $sql_current_image = "SELECT image_path FROM food WHERE id = ? AND restaurant_id = ?";
        $stmt_current_image = $conn->prepare($sql_current_image);
        $stmt_current_image->execute([$food_id, $restaurant_id]);
        $current_image_path = $stmt_current_image->fetchColumn();
        $image_path = $current_image_path; 
    }

    $sql_update = "UPDATE food SET name = ?, description = ?, price = ?, image_path = ?, discount = ? WHERE id = ? AND restaurant_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute([$name, $description, $price, $image_path, $discount, $food_id, $restaurant_id]);

    if ($stmt_update->rowCount() > 0) {
        echo "<div class='alert alert-success'>Yemek başarıyla güncellendi.</div>";
    } else {
        echo "<div class='alert alert-danger'>Hata: Yemek güncellenemedi.</div>";
    }
}

if (isset($_GET['food_id'])) {
    $food_id = intval($_GET['food_id']);
    $sql_food_detail = "SELECT * FROM food WHERE id = ? AND restaurant_id = ?";
    $stmt_food_detail = $conn->prepare($sql_food_detail);
    $stmt_food_detail->execute([$food_id, $restaurant_id]);
    $food_detail = $stmt_food_detail->fetch(PDO::FETCH_ASSOC);
    $stmt_food_detail->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Güncelle</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Yemek Güncelleme Formu</h2>
        <h3>Yemekler Listesi</h3>
        <div class="card shadow-lg p-4">
            <?php if (isset($result_food) && count($result_food) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($result_food as $row_food): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo htmlspecialchars($row_food['name']); ?></span>
                            <span><?php echo htmlspecialchars($row_food['price']); ?> TL</span>
                            <a href="update_food.php?food_id=<?php echo $row_food['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    Hiç yemek bulunamadı.
                </div>
            <?php endif; ?>
        </div>

        <!-- Güncelleme formu -->
        <form action="update_food.php" method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="form-group">
                <label for="food_id">Yemek ID:</label>
                <input type="number" name="food_id" class="form-control" value="<?php echo isset($food_detail) ? $food_detail['id'] : ''; ?>" readonly required>
            </div>
            <div class="form-group">
                <label for="name">Yemek Adı:</label>
                <input type="text" name="name" class="form-control" value="<?php echo isset($food_detail) ? htmlspecialchars($food_detail['name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <input type="text" name="description" class="form-control" value="<?php echo isset($food_detail) ? htmlspecialchars($food_detail['description']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Fiyat:</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo isset($food_detail) ? $food_detail['price'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Resim Yükle:</label>
                <input type="file" name="image" class="form-control">
                <small>Mevcut resim korunur eğer yeni bir resim yüklemezseniz.</small>
            </div>
            <div class="form-group">
                <label for="discount">İndirim (%):</label>
                <input type="number" name="discount" class="form-control" value="<?php echo isset($food_detail) ? $food_detail['discount'] : ''; ?>" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Yemek Güncelle">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
