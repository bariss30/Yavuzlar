<?php
session_start();
include('../functions.php'); 
include('../db_connection.php');


if (isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type'] == 'firm') {
    $user_id = $_SESSION['user']['id'];

    $sql_user = "SELECT company_id FROM users WHERE id = :user_id";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_user->execute();
    $company_id = $stmt_user->fetchColumn();

    
    if ($company_id) {
        $sql_company = "SELECT id FROM restaurant WHERE company_id = :company_id";
        $stmt_company = $conn->prepare($sql_company);
        $stmt_company->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $stmt_company->execute();
        $restaurant_id = $stmt_company->fetchColumn();

    
        

        if ($restaurant_id) {
            $sql_food = "SELECT * FROM food WHERE restaurant_id = :restaurant_id";
            $stmt_food = $conn->prepare($sql_food);
            $stmt_food->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
            $stmt_food->execute();
            $result_food = $stmt_food->fetchAll(PDO::FETCH_ASSOC);
        }
    }
} else {
    echo "Bu sayfaya erişim izniniz yok.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Listesi</title>

    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h2 {
            margin-top: 20px;
            margin-bottom: 20px;
            color: #343a40;
        }
        .card {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Yemekler Listesi</h2>
        <div class="card shadow-lg p-4">
            <?php if (isset($result_food) && count($result_food) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($result_food as $row_food): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo htmlspecialchars($row_food['name']); ?></span>
                            <span><?php echo htmlspecialchars($row_food['price']); ?> TL</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    Hiç yemek bulunamadı.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
