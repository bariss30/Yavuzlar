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

// Şirketleri çekmek için SQL sorgusu
$sql = "SELECT * FROM company";
$result = $conn->query($sql);

// Bağlantıyı kapat
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şirket Listesi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Şirket Listesi</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Açıklama</th>
                <th>Logo Yolu</th>
                <th>Silinme Tarihi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['logo_path']); ?></td>
                        <td><?php echo htmlspecialchars($row['deleted_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Kayıt bulunamadı.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
