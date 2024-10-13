<?php
include '../db_connection.php';

$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];

 
    
    $sql = "SELECT * FROM company WHERE name LIKE :search";
    $stmt = $pdo->prepare($sql);
    $searchParam = "%" . $search . "%";
    $stmt->bindParam(':search', $searchParam);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Firma Arama</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Firma Arama</h2>
        
        <form action="search_companies.php" method="POST" class="mb-4">
            <div class="form-group">
                <label for="search">Arama:</label>
                <input type="text" name="search" id="search" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ara</button>
        </form>

        <?php if (!empty($searchResults)): ?>
            <h2>Arama Sonuçları</h2>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div class="alert alert-warning">Hiç firma bulunamadı.</div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
