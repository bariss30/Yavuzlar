<?php
session_start();
include '../db_connection.php';  




$query_food = "SELECT * FROM food";
$result_food = $conn->query($query_food);



if (isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type'] == 'firm') {

    
    if (isset($_POST['food_id'])) {
        $food_id = $_POST['food_id'];

        $sql_delete = "DELETE FROM food WHERE id = :food_id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':food_id', $food_id, PDO::PARAM_INT);

        
        if ($stmt_delete->execute()) {
            echo "<div class='alert alert-success'>Yemek başarıyla silindi.</div>";
        } else {
            echo "<div class='alert alert-danger'>Yemek silme işlemi sırasında hata oluştu.</div>";
        }
    }
} else {
    echo "<div class='alert alert-danger'>Bu sayfaya erişim izniniz yok.</div>";
}

$conn = null; 

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Listesi</title>
   
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Yemekler Listesi</h2>
        <div class="card shadow-lg p-4">
            <?php if ($result_food->rowCount() > 0): ?>
                <h5>Mevcut Yemekler:</h5>
                <ul class="list-group mb-4">
                    <?php while ($row_food = $result_food->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>ID: <?php echo htmlspecialchars($row_food['id']); ?></span>
                            <span><?php echo htmlspecialchars($row_food['name']); ?></span>
                            <span><?php echo htmlspecialchars($row_food['price']); ?> TL</span>
                        </li>
                    <?php endwhile; ?>
                </ul>

               
                
                <h5>Silmek için Yemek ID girin:</h5>
                <form action="delete_food.php" method="POST">
                    <div class="form-group">
                        <input type="number" name="food_id" class="form-control" placeholder="Yemek ID" required>
                    </div>
                    <input type="submit" class="btn btn-danger" value="Yemek Sil">
                </form>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    Hiç yemek bulunamadı.
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
