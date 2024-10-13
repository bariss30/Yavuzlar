
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcıyı Banla</title>
</head>
<body>
    <h2>Kullanıcı Banlama</h2>
    
    <form action="ban_user.php" method="POST">
        <label for="user_id">Kullanıcı ID'si:</label>
        <input type="number" name="user_id" id="user_id" required>
        <br><br>
        <button type="submit">Kullanıcıyı Banla</button>
    </form>
</body>
</html>




<?php

$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "multi_login";  

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Kullanıcı ID'sini POST ile al ve kontrol et
if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Kullanıcıyı banlama (soft delete) SQL sorgusu
    $sql = "UPDATE users SET deleted_at = 1 WHERE id = $user_id";

    // Sorguyu çalıştır
    if ($conn->query($sql) === TRUE) {
        echo "Kullanıcı başarıyla banlandı (soft delete).";
    } else {
        echo "Hata: " . $conn->error;
    }
} else {
    echo "Geçersiz kullanıcı ID'si.";
}

// Bağlantıyı kapat
$conn->close();
?>