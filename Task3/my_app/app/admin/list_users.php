<?php
include '../db_connection.php';  

$sql = "SELECT * FROM users";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll();  

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Kullanıcılar</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kullanıcılar</h2>
        <div class="row">
            <?php
            if ($users) {
                foreach ($users as $user) {
                    echo "<div class='col-md-4'>";
                    echo "<div class='card mb-4'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($user['username']) . "</h5>";
                    echo "<h6 class='card-subtitle mb-2 text-muted'>ID: " . htmlspecialchars($user['id']) . "</h6>";
                    echo "<p class='card-text'>Email: " . htmlspecialchars($user['email']) . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='col-12'><p class='text-center'>Kullanıcı bulunamadı.</p></div>";
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php

$conn = null;
?>
