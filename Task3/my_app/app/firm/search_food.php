<?php
session_start();
include('../db_connection.php'); 
include('../functions.php'); 

if (!isLoggedIn()) {
    $_SESSION['msg'] = "Giriş yapmalısınız.";
    header('location: login.php');
    exit();
}

$food_items = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['search'])) {
    $search = $_POST['search'];
    $user_id = $_SESSION['user']['id'];

  
    $sql_user = "SELECT company_id FROM users WHERE id = :user_id";
    $stmt_user = $pdo->prepare($sql_user);
    $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_user->execute();
    $company_id = $stmt_user->fetchColumn();

   
    $sql_company = "SELECT id FROM restaurant WHERE company_id = :company_id";
    $stmt_company = $pdo->prepare($sql_company);
    $stmt_company->bindParam(':company_id', $company_id, PDO::PARAM_INT);
    $stmt_company->execute();
    $restaurant_id = $stmt_company->fetchColumn();

    if ($restaurant_id) {
       
        $sql = "SELECT * FROM food WHERE restaurant_id = :restaurant_id AND (name LIKE :search OR price BETWEEN :min_price AND :max_price) AND deleted_at IS NULL";
        $stmt = $pdo->prepare($sql);

        $search_param = "%" . $search . "%";
        $min_price = 0; 
        $max_price = 10000; 

        $stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':min_price', $min_price, PDO::PARAM_INT);
        $stmt->bindParam(':max_price', $max_price, PDO::PARAM_INT);
        
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as $row) {
                $food_items[] = htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['price']) . " TL";
            }
        } else {
            $food_items[] = "Hiç yemek bulunamadı.";
        }
    } else {
        $food_items[] = "Restoran bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Arama</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Yemek Arama</h2>
        
     
        <form action="search_food.php" method="POST" class="form-inline mb-4">
            <div class="form-group mr-2">
                <label for="search" class="sr-only">Yemek Adı veya Fiyat Aralığı:</label>
                <input type="text" name="search" class="form-control" placeholder="Yemek Adı veya Fiyat Aralığı" required>
            </div>
            <button type="submit" class="btn btn-primary">Ara</button>
        </form>

        
        <?php if (!empty($food_items)): ?>
            <h3>Arama Sonuçları</h3>
            <ul class="list-group">
                <?php foreach ($food_items as $item): ?>
                    <li class="list-group-item"><?php echo $item; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
