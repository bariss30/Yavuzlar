<?php 
include('../functions.php'); 
include('../db_connection.php'); 


if (!isLoggedIn() || $_SESSION['user']['user_type'] != 'firm') {
    $_SESSION['msg'] = "Firma olarak giriş yapmalısınız";
    header('location: login.php');
    exit();
}

$company_id = $_SESSION['user']['company_id'];
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';




$query = "SELECT * FROM restaurant WHERE company_id = :company_id AND name LIKE :searchTerm";
$stmt = $conn->prepare($query);
$searchParam = "%$searchTerm%";
$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
$stmt->bindParam(':searchTerm', $searchParam, PDO::PARAM_STR);
$stmt->execute();

$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restoran Arama</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Restoran Ara</h2>
    </div>
    <div class="content">
        <form method="get" action="search_restaurant.php">
            <div class="input-group">
                <input type="text" name="search" placeholder="Restoran adı ara" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Ara</button>
            </div>
        </form>

        <ul>
            <?php if (empty($restaurants)): ?>
                <li>Hiç restoran bulunamadı.</li>
            <?php else: ?>
                <?php foreach ($restaurants as $restaurant) : ?>
                    <li><?php echo htmlspecialchars($restaurant['name']); ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
