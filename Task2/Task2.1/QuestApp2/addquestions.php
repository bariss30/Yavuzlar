<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Ekle</title>
</head>
<body>
    <h1>Soru Ekle</h1>
    
    <form action="" method="POST">
        <label for="soru">Soru:</label><br>
        <input type="text" id="soru" name="soru" ><br><br>

        <label for="cevap1">Cevap 1:</label><br>
        <input type="text" id="cevap1" name="cevap1" ><br><br>

        <label for="cevap2">Cevap 2:</label><br>
        <input type="text" id="cevap2" name="cevap2" ><br><br>

        <label for="cevap3">Cevap 3:</label><br>
        <input type="text" id="cevap3" name="cevap3" ><br><br>

        <label for="cevap4">Cevap 4:</label><br>
        <input type="text" id="cevap4" name="cevap4" ><br><br>

        <label for="dogru_cevap">Doğru Cevap:</label><br>
        <input type="text" id="dogru_cevap" name="dogru_cevap" ><br><br>

        <label for="zorluk">Zorluk:</label><br>
        <input type="text" id="zorluk" name="zorluk" ><br><br>

        <button type="submit">Gönder</button>
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

        $soru = $_POST['soru'];
        $cevap1 = $_POST['cevap1'];
        $cevap2 = $_POST['cevap2'];
        $cevap3 = $_POST['cevap3'];
        $cevap4 = $_POST['cevap4'];
        $dogru_cevap = $_POST['dogru_cevap'];
        $zorluk = $_POST['zorluk'];

       
        $sql = "INSERT INTO sorular(soru, cevap1, cevap2, cevap3, cevap4, truecevap, zorluk) 
                VALUES ('$soru', '$cevap1', '$cevap2', '$cevap3', '$cevap4', '$dogru_cevap', '$zorluk')";

       
        if ($conn->exec($sql)) {
            echo "Soru başarıyla eklendi!";
        } else {
            echo "Bir hata oluştu!";
        }
    }
    ?>
</body>
</html>
