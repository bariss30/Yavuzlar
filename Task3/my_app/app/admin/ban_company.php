<?php

include '../db_connection.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_id = $_POST['company_id'];

    
    $sql = "UPDATE companies SET deleted = 1 WHERE id = :company_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $message = "Firma başarıyla yasaklandı.";
    } else {
        
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
    <title>Firma Yasaklama</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Firma Yasaklama</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="ban_company.php" method="POST" class="mt-4">
            <div class="form-group">
                <label for="company_id">Yasaklamak için Firma ID'si:</label>
                <input type="number" name="company_id" id="company_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger">Yasakla</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
