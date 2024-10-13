<?php
$servername = "localhost";
$username = "root";
$password = "rootpassword";
$dbname = "multi_login";


$conn = new mysqli($servername, $username, $password, $dbname);



if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$result = null;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];
    
  
    
    $sql = "SELECT * FROM coupon WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();  
    
    $result = $stmt->get_result();
    
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Kupon Arama</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Kupon Arama</h2>

      
        
        <form action="search_coupons.php" method="POST" class="mb-4">
            <div class="form-group">
                <label for="search">Kupon Kodu Arama:</label>
                <input type="text" name="search" id="search" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ara</button>
        </form>

    
        
        <?php if ($result !== null): ?>
            <h2>Kupon Arama Sonuçları</h2>
            <?php if ($result->num_rows > 0): ?>
                <ul class="list-group mt-3">
                    <!-- Kuponları listele -->
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="list-group-item">
                            Kupon Kodu: <?php echo htmlspecialchars($row['name']); ?>, 
                            İndirim: <?php echo htmlspecialchars($row['discount']); ?>%
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-warning mt-3">Hiç kupon bulunamadı.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>


    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php


if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
