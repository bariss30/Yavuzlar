<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Güncelle</title>
</head>
<body>
    <h1>Soru Güncelle</h1>
    
    <form action="update.php" method="GET">
        <label for="id">Güncellenecek Soru ID'si:</label><br>
        <input type="text" id="id" name="id" ><br><br>
        <button type="submit">Soru Getir</button>
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

    if (isset($_GET['id'])) {
        $conn = Config::getPDO();
        
        $id = $_POST['id'];
     
        $sql = "SELECT * FROM sorular WHERE id = $id";
        $stmt = $conn->query($sql);
        $soru = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($soru) {
           
            ?>
            <form action="update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $soru['id']; ?>">
                
                <label for="soru">Soru:</label><br>
                <input type="text" id="soru" name="soru" value="<?php echo $soru['soru']; ?>"><br><br>

                <label for="cevap1">Cevap 1:</label><br>
                <input type="text" id="cevap1" name="cevap1" value="<?php echo $soru['cevap1']; ?>"><br><br>

                <label for="cevap2">Cevap 2:</label><br>
                <input type="text" id="cevap2" name="cevap2" value="<?php echo $soru['cevap2']; ?>"><br><br>

                <label for="cevap3">Cevap 3:</label><br>
                <input type="text" id="cevap3" name="cevap3" value="<?php echo $soru['cevap3']; ?>"><br><br>

                <label for="cevap4">Cevap 4:</label><br>
                <input type="text" id="cevap4" name="cevap4" value="<?php echo $soru['cevap4']; ?>"><br><br>

                <label for="dogru_cevap">Doğru Cevap:</label><br>
                <input type="text" id="dogru_cevap" name="dogru_cevap" value="<?php echo $soru['truecevap']; ?>"><br><br>

                <label for="zorluk">Zorluk:</label><br>
                <input type="text" id="zorluk" name="zorluk" value="<?php echo $soru['zorluk']; ?>"><br><br>

                <button type="submit">Güncelle</button>
            </form>
            <?php
        } else {
            echo "ID ile eşleşen soru bulunamadı";
        }
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = Config::getPDO();
        $id = $_POST['id'];
        $soru = $_POST['soru'];
        $cevap1 = $_POST['cevap1'];
        $cevap2 = $_POST['cevap2'];
        $cevap3 = $_POST['cevap3'];
        $cevap4 = $_POST['cevap4'];
        $dogru_cevap = $_POST['dogru_cevap'];
        $zorluk = $_POST['zorluk'];

        
        $sql = "UPDATE sorular SET soru = '$soru', cevap1 = '$cevap1', cevap2 = '$cevap2', cevap3 = '$cevap3', cevap4 = '$cevap4', truecevap = '$dogru_cevap', zorluk = '$zorluk' WHERE id = $id";

        if ($conn->exec($sql)) {
            echo "Soru  güncellendi";
        } else {
            echo "Bir hata oluştu";
        }
    }
    ?>
</body>
</html>
