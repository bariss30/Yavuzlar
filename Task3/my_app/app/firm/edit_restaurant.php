<?php 
session_start();
include('../functions.php');
include('../db_connection.php'); ,



if (!isLoggedIn() || $_SESSION['user']['user_type'] != 'firm') {
    $_SESSION['msg'] = "Firma olarak giriş yapmalısınız";
    header('location: login.php');
    exit();
}

$restaurant_id = $_GET['id'];
$company_id = $_SESSION['user']['company_id'];

$query = "SELECT * FROM restaurant WHERE id = :restaurant_id AND company_id = :company_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
$stmt->execute();
$restaurant = $stmt->fetch();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

  
    
    $query = "UPDATE restaurant SET name = :name, description = :description WHERE id = :restaurant_id AND company_id = :company_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Restoran başarıyla güncellendi";
    } else {
        $_SESSION['msg'] = "Restoran güncellenirken bir hata oluştu";
    }

    header('location: list_restaurants.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restoran Güncelle</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
    <div class="header">
        <h2>Restoran Güncelle</h2>
    </div>
    <div class="content">
        <form method="post">
            <div class="input-group">
                <label>Restoran Adı</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>
            </div>
            <div class="input-group">
                <label>Açıklama</label>
                <textarea name="description" required><?php echo htmlspecialchars($restaurant['description']); ?></textarea>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Güncelle</button>
            </div>
        </form>
    </div>
</body>
</html>
