<?php

$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "multi_login";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}


$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Kullanıcılar</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>ID: " . $row["id"] . " - Ad: " . $row["username"] . " - Email: " . $row["email"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Kullanıcı bulunamadı.";
}


$conn->close();
?>
