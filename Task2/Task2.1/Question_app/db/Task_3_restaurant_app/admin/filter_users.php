<?php
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "multi_login";  

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// SQL sorgusunu oluştur
if ($status === 'active') {
    $sql = "SELECT * FROM users WHERE deleted_at = 0";
} elseif ($status === 'deleted') {
    $sql = "SELECT * FROM users WHERE deleted_at = 1";
} else {
    $sql = "SELECT * FROM users";
}

// Sorguyu çalıştır
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcıları Filtrele</title>
</head>
<body>
    <h2>Kullanıcıları Filtrele</h2>
    
    <form action="filter_users.php" method="GET">
        <label for="status">Kullanıcı Durumu:</label>
        <select name="status" id="status">
            <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>Hepsi</option>
            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Aktif</option>
            <option value="deleted" <?php echo $status === 'deleted' ? 'selected' : ''; ?>>Silinmiş</option>
        </select>
        <br><br>
        <button type="submit">Filtrele</button>
    </form>
    
    <h2>Kullanıcı Listesi</h2>
    
    <?php
    // Sonuçları kontrol et ve tabloyu oluştur
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Adı</th><th>Durum</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $statusText = $row['deleted_at'] ? 'Silinmiş' : 'Aktif';
            echo "<tr><td>" . $row["id"]. "</td><td>" . $row["username"]. "</td><td>" . $statusText. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Kayıt bulunamadı.";
    }

    // Bağlantıyı kapat
    $conn->close();
    ?>
</body>
</html>
