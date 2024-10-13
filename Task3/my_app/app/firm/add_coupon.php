<?php
// 
include '../db_connection.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];
    $discount = $_POST['discount'];

    
    $sql = "INSERT INTO coupon (name, discount) VALUES (:code, :discount)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->bindParam(':discount', $discount, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $message = "Yeni kupon başarıyla eklendi.";
    } else {
        $message = "Hata: " . $stmt->errorInfo()[2]; 
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
    <title>Kupon Ekle</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Kupon Ekle</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="add_coupon.php" method="POST" class="mt-4">
            <div class="form-group">
                <label for="code">Kupon Kodu:</label>
                <input type="text" name="code" id="code" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="discount">İndirim Miktarı (%):</label>
                <input type="number" name="discount" id="discount" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Kupon Ekle</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
