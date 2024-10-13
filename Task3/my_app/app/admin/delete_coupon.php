<?php


include '../db_connection.php';



$sql = "SELECT * FROM coupon";
$result = $conn->query($sql);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coupon_id = $_POST['coupon_id']; // Kupon ID'sini doğru yaz

  
    
    
    $sql = "DELETE FROM coupon WHERE id = :coupon_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':coupon_id', $coupon_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $message = "Kupon başarıyla silindi.";
    } else {
        $message = "Hata: Kupon silinemedi.";
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
    <title>Kupon Sil</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Kuponlar</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kupon Kodu</th>
                    <th>İndirim (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->rowCount() > 0): ?>
                    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['discount']); ?>%</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Hiç kupon bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Kupon Sil</h3>
        <form action="delete_coupon.php" method="POST">
            <div class="form-group">
                <label for="coupon_id">Silmek için Kupon ID'si:</label>
                <input type="number" name="coupon_id" id="coupon_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger">Sil</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
