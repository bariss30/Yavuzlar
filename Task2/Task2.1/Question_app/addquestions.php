<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Soru Ekle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
      
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>




    <form action="" method="POST">
        <h1>Soru Ekle</h1>
        <div class="form-container">
            <label for="soru">Soru:</label>
            <input type="text" id="soru" name="soru" required>

            <label for="cevap1">Cevap 1:</label>
            <input type="text" id="cevap1" name="cevap1" required>

            <label for="cevap2">Cevap 2:</label>
            <input type="text" id="cevap2" name="cevap2" required>

            <label for="cevap3">Cevap 3:</label>
            <input type="text" id="cevap3" name="cevap3" required>

            <label for="cevap4">Cevap 4:</label>
            <input type="text" id="cevap4" name="cevap4" required>

            <label for="dogru_cevap">Doğru Cevap:</label>
            <input type="text" id="dogru_cevap" name="dogru_cevap" required>

            <label for="zorluk">Zorluk:</label>
            <input type="text" id="zorluk" name="zorluk" required>

            <button type="submit">Gönder</button>
        </div>
    </form>
</body>
</html>
<?php
    class Config {
        private static $pdo = null;

        public static function getPDO() {
            if (self::$pdo === null) {
                try {
                    self::$pdo = new PDO('sqlite:C:/xampp/htdocs/taskTekrar/db/db_member.sqlite3');
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
            echo "Soru başarıyla eklendi ";
        } else {
            echo "Bir hata oluştu!";
        }
    }
    ?>
</body>
</html>
