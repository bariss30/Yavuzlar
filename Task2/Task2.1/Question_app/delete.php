<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Soru Sil</title>
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
            color: #333;
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
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
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
    <form action="delete.php" method="POST">
        <h1>Soru Sil</h1>
        <label for="id">Silinecek Soru ID'si:</label>
        <input type="text" id="id" name="id" required>
        <button type="submit">Sil</button>
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = Config::getPDO();
  
        $id = $_POST['id'];
        
        $sql = "DELETE FROM sorular WHERE id = $id";

        if ($conn->exec($sql)) {
            if ($conn->exec($sql) > 0) {
                echo "<p>Soru bulunamadı.</p>";
            } else {
                echo "<p>Soru başarıyla silindi!</p>";
            }
        } else {
            echo "<p>Bir hata oluştu.</p>";
        }
    }
    ?>
</body>
</html>
