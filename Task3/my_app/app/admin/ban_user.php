<?php
include '../db_connection.php';  

if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $sql = "UPDATE users SET deleted_at = NOW() WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Kullanıcı başarıyla banlandı.</div>";
    } else {
        echo "<div class='alert alert-danger'>Banlama işlemi başarısız oldu.</div>";
    }
}


$sql = "SELECT * FROM users WHERE deleted_at IS NULL";
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
    <title>Kullanıcıyı Banla</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Kullanıcı Banlama</h2>
        
        <h3>Kullanıcı Listesi</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad</th>
                    <th>Email</th>
                    <th>Banla</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($users) > 0) {
                    foreach ($users as $row) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row["id"]) . "</td>
                                <td>" . htmlspecialchars($row["username"]) . "</td>
                                <td>" . htmlspecialchars($row["email"]) . "</td>
                                <td>
                                    <form action='' method='POST' style='display:inline;'>
                                        <input type='hidden' name='user_id' value='" . htmlspecialchars($row["id"]) . "'>
                                        <button type='submit' class='btn btn-danger btn-sm'>Banla</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Kullanıcı bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn = null;
?>
