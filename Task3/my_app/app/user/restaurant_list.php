<?php 
include '../db_connection.php'; 

try {

    $avg_scores_stmt = $conn->query("SELECT restaurant_id, AVG(score) AS average_score FROM comments GROUP BY restaurant_id");
    $restaurant_avg_scores = [];

    while ($row = $avg_scores_stmt->fetch(PDO::FETCH_ASSOC)) {
        $restaurant_avg_scores[$row['restaurant_id']] = $row['average_score'];
    }
    

    $restaurants_stmt = $conn->query("SELECT * FROM restaurant");
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoranlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Restoranlar</h2>

        <div class="row">
            <?php 

while ($restaurant = $restaurants_stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($restaurant['description']); ?></p>
                            <p class="card-text">
                                Ortalama Puan: 
                                <?php 
                                $avg_score = isset($restaurant_avg_scores[$restaurant['id']]) ? round($restaurant_avg_scores[$restaurant['id']], 2) : 'puan yok';
                                echo htmlspecialchars($avg_score);
                                ?>
                            </p>
                            <a href="comments.php?restaurant_id=<?php echo htmlspecialchars($restaurant['id']); ?>" class="btn btn-primary">Yorum Yaz</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
