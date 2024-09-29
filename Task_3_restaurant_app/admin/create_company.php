<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "multi_login"; 

// Veritabanı bağlantısı
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Veritabanına veri ekleme/güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formdan gelen verileri al
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $logo_path = $_POST['logo_path'] ?? '';
    $deleted_at = $_POST['deleted_at'] ?? '';
    
    // Hata kontrolü ve doğrulama
    if (empty($name) || empty($description) || empty($logo_path)) {
        echo "Name, description ve logo_path alanları gereklidir.";
    } else {
        if (empty($deleted_at)) {
            // Yeni şirket ekleme
            $sql = "INSERT INTO company (name, description, logo_path) VALUES ('$name', '$description', '$logo_path')";
        } else {
            // Silinmişse tarih ekle
            $sql = "UPDATE company SET name='$name', description='$description', logo_path='$logo_path', deleted_at='$deleted_at' WHERE id='$company_id'";
        }
        
        // Sorguyu çalıştır
        if ($conn->query($sql) === TRUE) {
            echo "Şirket başarıyla eklendi/güncellendi.";
        } else {
            echo "Hata: " . $conn->error;
        }
    }
}

// Bağlantıyı kapat
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şirket Ekle/Güncelle</title>
</head>
<body>
    <h2>Şirket Ekle/Güncelle</h2>
    
    <form action="create_company.php" method="POST">
        <label for="name">Şirket Adı:</label>
        <input type="text" name="name" id="name" required>
        <br><br>
        <label for="description">Açıklama:</label>
        <input type="text" name="description" id="description" required>
        <br><br>
        <label for="logo_path">Logo Yolu:</label>
        <input type="text" name="logo_path" id="logo_path" required>
        <br><br>
        <label for="deleted_at">Silinme Tarihi (boş bırakın eğer silinmemişse):</label>
        <input type="text" name="deleted_at" id="deleted_at">
        <br><br>
        <button type="submit">Şirketi Ekle/Güncelle</button>
    </form>
</body>
</html>
