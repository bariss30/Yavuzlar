<?php
include '../db_connection.php'; 

$status = isset($_GET['status']) ? $_GET['status'] : 'all';

if ($status === 'active') {
    $sql = "SELECT * FROM users WHERE deleted_at IS NULL"; 
} elseif ($status === 'deleted') {
    $sql = "SELECT * FROM users WHERE deleted_at IS NOT NULL"; 
} else {
    $sql = "SELECT * FROM users"; 
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Kullanıcıları Filtrele</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Kullanıcıları Filtrele</h2>
        
        <form action="filter_users.php" method="GET" class="mb-4">
            <div class="form-group">
                <label for="status">Kullanıcı Durumu:</label>
                <select name="status" id="status" class="form-control">
                    <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>Hepsi</option>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="deleted" <?php echo $status === 'deleted' ? 'selected' : ''; ?>>Silinmiş</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrele</button>
        </form>
        
        <h2>Kullanıcı Listesi</h2>
        
        <?php
        if ($users && count($users) > 0) {
            echo "<table class='table table-bordered'>";
            echo "<thead class='thead-light'><tr><th>ID</th><th>Adı</th><th>Durum</th></tr></thead>";
            echo "<tbody>";

            foreach ($users as $row) {
                $statusText = $row['deleted_at'] === NULL ? 'Aktif' : 'Silinmiş';
                echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" . htmlspecialchars($row["username"]) . "</td><td>" . htmlspecialchars($statusText) . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>Kayıt bulunamadı.</div>";
        }

        $conn = null;
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
