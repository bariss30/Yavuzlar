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


if (isset($_GET['query'])) {
    $conn = Config::getPDO();
    $query = $_GET['query'];

    // Sorgu
    $sql = "SELECT * FROM sorular WHERE soru LIKE :query";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':query', '%' . $query . '%');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Soru Ara</title>
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

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        h2 {
            color: 0;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        li:nth-child(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div>
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Soru ara...">
            <button type="submit">Ara</button>
        </form>

        <h2>Arama Sonuçları:</h2>
        <ul>
            <?php if (!empty($results)) { ?>
                <?php foreach ($results as $result) { ?>
                    <li><?php echo htmlspecialchars($result['soru']); ?></li>
                <?php } ?>
            <?php } else { ?>
                <li>Sonuç bulunamadı.</li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
