<?php

try {
    $conn = new PDO('sqlite:C:/xampp/htdocs/QuestApp2/database.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı kurulamadı: " . $e->getMessage();
    exit;
}
?>