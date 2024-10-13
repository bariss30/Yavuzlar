<?php

include '../db_connection.php'; 
include '../functions.php'; 

$foodName = '';
$priceRange = [0, 1000]; 

if (isset($_GET['foodName'])) {
    $foodName = $_GET['foodName'];
}

if (isset($_GET['minPrice']) && isset($_GET['maxPrice'])) {
    $priceRange[0] = floatval($_GET['minPrice']);
    $priceRange[1] = floatval($_GET['maxPrice']);
}


function searchFood($foodName, $priceRange) {
    global $conn;

    $sql = "SELECT * FROM food WHERE price BETWEEN :minPrice AND :maxPrice";
    
    if (!empty($foodName)) {
        $sql .= " AND name LIKE :foodName";
    }

    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(':minPrice', $priceRange[0]);
    $stmt->bindParam(':maxPrice', $priceRange[1]);
    
    if (!empty($foodName)) {
        $foodNameParam = "%" . $foodName . "%";
        $stmt->bindParam(':foodName', $foodNameParam);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$foods = searchFood($foodName, $priceRange);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yemek Arama</title>
</head>
<body>

<h1>Yemek Arama</h1>

<form method="GET" action="">
    <input type="text" name="foodName" placeholder="Yemek ismi" value="<?php echo htmlspecialchars($foodName); ?>">
    <input type="number" name="minPrice" placeholder="Min Fiyat" step="0.01" value="<?php echo htmlspecialchars($priceRange[0]); ?>">
    <input type="number" name="maxPrice" placeholder="Max Fiyat" step="0.01" value="<?php echo htmlspecialchars($priceRange[1]); ?>">
    <button type="submit">Ara</button>
</form>

<?php if (!empty($foods)): ?>
    <h2>Arama Sonuçları:</h2>
    <ul>
        <?php foreach ($foods as $food): ?>
            <li>
                <strong><?php echo htmlspecialchars($food['name']); ?></strong> - <?php echo htmlspecialchars($food['description']); ?> (Fiyat: <?php echo htmlspecialchars($food['price']); ?> TL)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Hiçbir sonuç bulunamadı.</p>
<?php endif; ?>

</body>
</html>
