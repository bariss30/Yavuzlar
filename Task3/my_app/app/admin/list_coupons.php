<?php
include '../db_connection.php';


$sql = "SELECT * FROM coupon";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Kupon Listesi</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Tüm Kuponlar</h2>

        <?php if (count($result) > 0): ?>
            <ul class="list-group mt-3">
                <?php foreach ($result as $row): ?>
                    <li class="list-group-item">
                        Kupon Kodu: <?php echo htmlspecialchars($row['name']); ?>, İndirim: <?php echo htmlspecialchars($row['discount']); ?>%
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning mt-3">Hiç kupon bulunamadı.</div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn = null; 
?>
