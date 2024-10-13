<?php
include '../db_connection.php';  

$search_term = "";
$results = []; 

if (isset($_POST['search'])) {
    $search_term = $_POST['search'];

    try {
   
        
        $sql = "SELECT * FROM users WHERE username LIKE :search_term_username OR email LIKE :search_term_email";
        $stmt = $conn->prepare($sql);

        $search_with_wildcards = "%" . $search_term . "%";
        $stmt->bindParam(':search_term_username', $search_with_wildcards, PDO::PARAM_STR);
        $stmt->bindParam(':search_term_email', $search_with_wildcards, PDO::PARAM_STR);

        $stmt->execute();

        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Kullanıcı Ara</title>
    <style>
        body {
            font-size: 120%;
            background: #E0E0E0;
        }
        .container {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kullanıcı Ara</h2>
        <form method="POST" action="search_user.php">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="İsim veya Email ile ara" value="<?php echo htmlspecialchars($search_term); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Ara</button>
        </form>

        <?php
        if (isset($_POST['search'])) {
            if (count($results) > 0) {  
                echo "<h3 class='mt-4'>Arama Sonuçları:</h3>";
                echo "<ul class='list-group'>";
                foreach ($results as $row) {
                    echo "<li class='list-group-item'>ID: " . htmlspecialchars($row['id']) . 
                         " - Ad: " . htmlspecialchars($row['username']) . 
                         " - Email: " . htmlspecialchars($row['email']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='mt-4'>Sonuç bulunamadı.</p>";
            }
        }

    
        $conn = null;
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
