<?php
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "multi_login";  

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Arama terimini al
$search_term = "";
if (isset($_POST['search'])) {
    $search_term = $_POST['search'];
}

// SQL sorgusu
$sql = "SELECT * FROM users WHERE username LIKE '%$search_term%' OR email LIKE '%$search_term%'";
$result = $conn->query($sql);
?>

<!-- HTML Formu -->
<!DOCTYPE html>
<html>
<head>
    <title>Kullanıcı Ara</title>
</head>
<body>
    <h2>Kullanıcı Ara</h2>
    <form method="POST" action="search_user.php">
        <input type="text" name="search" placeholder="İsim veya Email ile ara" value="<?php echo $search_term; ?>">
        <button type="submit">Ara</button>
    </form>

<?php
// Sonuçları gösterme
if (isset($_POST['search'])) {
    if ($result->num_rows > 0) {
        echo "<h3>Arama Sonuçları:</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row["id"] . " - Ad: " . $row["username"] . " - Email: " . $row["email"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "Sonuç bulunamadı.";
    }
}

// Bağlantıyı kapat
$conn->close();
?>
</body>
</html>
