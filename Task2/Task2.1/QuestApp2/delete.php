<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Sil</title>
</head>
<body>
    <h1>Soru Sil</h1>

    <form action="delete.php" method="POST">
        <label for="id">Silinecek Soru ID'si:</label><br>
        <input type="text" id="id" name="id" required><br><br>
        <button type="submit">Sil</button>
    </form>

    <?php
    class Config {
        private static $pdo = null;

        public static function getPDO() {
            if (self::$pdo === null) {
                try {
                    self::$pdo = new PDO('sqlite:C:/xampp/htdocs/QuestApp2/database.db');
                    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    echo "Veritabanı bağlantısı kurulamadı: " . $e->getMessage();
                    exit;
                }
            }
            return self::$pdo;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = Config::getPDO();
  
        $id = $_POST['id'];
        
        $sql = "DELETE FROM sorular WHERE id = $id";

        if ($conn->exec($sql)) {
            if ($conn->exec($sql) > 0) {
                echo "Soru başarıyla silindi!";
            } else {
                echo "soru bulunamadı";
            }
        } else {
            echo "Bir hata oluştu";
        }
    }
    ?>
</body>
</html>
