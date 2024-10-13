<?php 
session_start();
include('../functions.php');
include('../db_connection.php'); 


if (!isLoggedIn() || $_SESSION['user']['user_type'] != 'firm') {
    $_SESSION['msg'] = "Firma olarak giriş yapmalısınız";
    header('location: login.php');
    exit();
}

$company_id = $_SESSION['user']['company_id'];

$query = "SELECT * FROM restaurant WHERE company_id = :company_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
$stmt->execute();
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoranları Listele</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .content {
            margin: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #fff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Restoranlarınız</h2>
    </div>
    <div class="content">
        <ul>
            <?php if (count($restaurants) > 0): ?>
                <?php foreach ($restaurants as $restaurant) : ?>
                    <li>
                        <?php echo htmlspecialchars($restaurant['name']); ?> 
                        <span>
                            <a href="edit_restaurant.php?id=<?php echo $restaurant['id']; ?>">Güncelle</a> - 
                            <a href="delete_restaurant.php?id=<?php echo $restaurant['id']; ?>">Sil</a>
                        </span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Hiç restoran bulunamadı.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
