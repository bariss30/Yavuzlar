<?php 
include('functions.php');
// Veritabanı bağlantısını ayarla
$host = 'localhost'; // Veritabanı sunucusu
$db   = 'multi_login'; // Veritabanı adı
$user = 'root'; // Veritabanı kullanıcı adı
$pass = ''; // Veritabanı şifresi
$charset = 'utf8mb4';

// PDO bağlantısı kur
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}



// Check if user is logged in and is a firm
if (!isLoggedIn() || $_SESSION['user']['user_type'] != 'firm') {
    $_SESSION['msg'] = "You must be logged in as a firm to add a restaurant";
    header('location: login.php');
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $imagePath = $_POST['imagePath'];
    $company_id = $_SESSION['user']['company_id']; // Assuming the company_id is stored in the session

    // Validate inputs
    if (empty($name) || empty($description) || empty($imagePath)) {
        $_SESSION['msg'] = "All fields are required";
    } else {
        // Insert the new restaurant into the database
        $query = "INSERT INTO restaurant (company_id, name, description, image_path, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$company_id, $name, $description, $imagePath]);

        $_SESSION['msg'] = "Restaurant added successfully";
        header('location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Restaurant</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Add New Restaurant</h2>
    </div>
    <div class="content">
        <!-- notification message -->
        <?php if (isset($_SESSION['msg'])) : ?>
            <div class="error success">
                <h3>
                    <?php 
                        echo $_SESSION['msg']; 
                        unset($_SESSION['msg']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- Restaurant Form -->
        <form method="post" action="add_restaurant.php">
            <div class="input-group">
                <label>Restaurant Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="input-group">
                <label>Image Path</label>
                <input type="text" name="imagePath" required>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Add Restaurant</button>
            </div>
        </form>
    </div>
</body>
</html>
