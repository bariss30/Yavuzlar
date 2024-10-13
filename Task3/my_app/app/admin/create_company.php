<?php
include '../db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $logo_path = '';

    if (isset($_FILES['logo_path']) && $_FILES['logo_path']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["logo_path"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["logo_path"]["tmp_name"], $target_file)) {
                $logo_path = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Dosya yükleme hatası.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Sadece JPG, JPEG, PNG ve GIF dosyaları yüklenebilir.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Logo yüklenmedi.</div>";
    }

    if (empty($name) || empty($description) || empty($username) || empty($password) || empty($email) || empty($logo_path)) {
        echo "Tüm alanlar gereklidir.";
    } else {
        $user_type = "firm";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); 

        try {
        


            $stmt_company = $conn->prepare("INSERT INTO company (name, description, logo_path) VALUES (:name, :description, :logo_path)");
            $stmt_company->bindParam(':name', $name);
            $stmt_company->bindParam(':description', $description);
            $stmt_company->bindParam(':logo_path', $logo_path);
            $stmt_company->execute();

            

            $last_id = $conn->lastInsertId();

         
            


            $stmt_user = $conn->prepare("INSERT INTO users (username, password, email, user_type, company_id) VALUES (:username, :password, :email, :user_type, :company_id)");
            $stmt_user->bindParam(':username', $username);
            $stmt_user->bindParam(':password', $hashed_password);
            $stmt_user->bindParam(':email', $email);
            $stmt_user->bindParam(':user_type', $user_type);
            $stmt_user->bindParam(':company_id', $last_id);

            if ($stmt_user->execute()) {
                echo "Şirket ve kullanıcı başarıyla eklendi.";
            } else {
                echo "Kullanıcı eklerken hata.";
            }
        } catch (PDOException $e) {
            echo "Hata: " . $e->getMessage();
        }
    }
}

$conn = null;


?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Şirket Ekle/Güncelle</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Şirket Ekle/Güncelle</h2>
        <form action="create_company.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Şirket Adı:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <input type="text" name="description" id="description" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="logo_path">Logo Yükle:</label>
                <input type="file" name="logo_path" id="logo_path" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Şirketi Ekle/Güncelle</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
