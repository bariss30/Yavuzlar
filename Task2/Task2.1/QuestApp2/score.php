<?php

try {
    $conn = new PDO('sqlite:C:/xampp/htdocs/QuestApp2/database.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı kurulamadı: " . $e->getMessage();
    exit;
}


$sql = "SELECT username, score FROM users ORDER BY score ";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score</title>
</head>
<body>
    <h1>Scoreboard</h1>
    <table>
        <tr>
            <th>Öğrenci Adı</th>
            <th>Puan</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['score']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
