<?php
session_start();
include '../db_connection.php'; 


$message = "";
$user_id = $_SESSION['user']['id'];

$sql_user = "SELECT company_id FROM users WHERE id = :user_id";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_user->execute();
$company_id = $stmt_user->fetchColumn(); 


if (!$company_id) {
    die("Kullanıcıya ait firma bulunamadı.");
}

$sql_company = "SELECT id FROM restaurant WHERE company_id = :company_id";
$stmt_company = $conn->prepare($sql_company);
$stmt_company->bindParam(':company_id', $company_id, PDO::PARAM_INT);
$stmt_company->execute();
$restaurant_id = $stmt_company->fetchColumn(); 


if (!$restaurant_id) {
    die("Restoran bulunamadı.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['image_file']['name'];
        $imageTmpPath = $_FILES['image_file']['tmp_name'];
        $uploadDir = '../uploads/';

        if (move_uploaded_file($imageTmpPath, $uploadDir . $imageFileName)) {
            $image_path = $uploadDir . $imageFileName;
        } else {
            die("Resim yüklenirken hata oluştu.");
        }
    } else {
        die("Resim dosyası yüklenmedi.");
    }

    if (!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['price']) && isset($image_path) && isset($_POST['discount'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];

        $sql = "INSERT INTO food (restaurant_id, name, description, price, image_path, discount, created_at) 
                VALUES (:restaurant_id, :name, :description, :price, :image_path, :discount, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
        $stmt->bindParam(':discount', $discount, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Yeni yemek başarıyla eklendi.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Hata: " . implode(", ", $stmt->errorInfo()) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Lütfen tüm alanları doldurun.</div>";
    }
}

$conn = null; 

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Yeni Yemek Ekle</h2>
            <?php if (!empty($message)) echo $message; ?>
            <form action="add_food.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Yemek Adı</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Fiyat (TL)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="image_file" class="form-label">Resim Dosyası</label>
                    <input type="file" class="form-control" id="image_file" name="image_file" required>
                </div>
                <div class="mb-3">
                    <label for="discount" class="form-label">İndirim (%)</label>
                    <input type="number" class="form-control" id="discount" name="discount" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Yemek Ekle</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
