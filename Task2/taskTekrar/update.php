<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Güncelle</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
          
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin-bottom: 20px;
        }
        label, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            color: #333;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form action="update.php" method="GET">
        <h1>Soru Güncelle</h1>
        <label for="id">Güncellenecek Soru ID'si:</label>
        <input type="text" id="id" name="id">
        <button type="submit">Soru Getir</button>
    </form>

    <?php
    class Config {
        private static $pdo = null;

        public static function getPDO() {
            if (self::$pdo === null) {
                try {
                    self::$pdo = new PDO('sqlite:C:/xampp/htdocs/taskTekrar/db/db_member.sqlite3');
                    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    echo "<p>Veritabanı bağlantısı kurulamadı: " . $e->getMessage() . "</p>";
                    exit;
                }
            }
            return self::$pdo;
        }
    }

    if (isset($_GET['id'])) {
        $conn = Config::getPDO();
        $id = $_GET['id'];

        $sql = "SELECT * FROM sorular WHERE id = $id";
        $stmt = $conn->query($sql);
        $soru = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($soru) {
    ?>
            <form action="update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $soru['id']; ?>">

                <label for="soru">Soru:</label>
                <input type="text" id="soru" name="soru" value="<?php echo $soru['soru']; ?>">

                <label for="cevap1">Cevap 1:</label>
                <input type="text" id="cevap1" name="cevap1" value="<?php echo $soru['cevap1']; ?>">

                <label for="cevap2">Cevap 2:</label>
                <input type="text" id="cevap2" name="cevap2" value="<?php echo $soru['cevap2']; ?>">

                <label for="cevap3">Cevap 3:</label>
                <input type="text" id="cevap3" name="cevap3" value="<?php echo $soru['cevap3']; ?>">

                <label for="cevap4">Cevap 4:</label>
                <input type="text" id="cevap4" name="cevap4" value="<?php echo $soru['cevap4']; ?>">

                <label for="dogru_cevap">Doğru Cevap:</label>
                <input type="text" id="dogru_cevap" name="dogru_cevap" value="<?php echo $soru['truecevap']; ?>">

                <label for="zorluk">Zorluk:</label>
                <input type="text" id="zorluk" name="zorluk" value="<?php echo $soru['zorluk']; ?>">

                <button type="submit">Güncelle</button>
            </form>
    <?php
        } else {
            echo "<p>ID ile eşleşen soru bulunamadı</p>";
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
            echo "<p>Soru güncellendi</p>";
        } else {
            echo "<p>Bir hata oluştu</p>";
        }
    }
    ?>
</body>
</html>
