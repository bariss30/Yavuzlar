<?php
include '../db_connection.php'; 

$sql = "SELECT * FROM company";
$stmt = $conn->prepare($sql);
$stmt->execute();
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Şirket, Restoran ve Ürün Listesi</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Şirket, Restoran ve Ürün Listesi</h2>

        <?php if (count($companies) > 0): ?>
            <?php foreach ($companies as $company): ?>
                <h3><?php echo htmlspecialchars($company['name']); ?></h3>
                <img src="<?php echo htmlspecialchars($company['logo_path']); ?>" alt="Logo" style="max-width: 100px;">
                <p><?php echo htmlspecialchars($company['description']); ?></p>

                <?php
                $company_id = $company['id'];
                $restaurant_sql = "SELECT * FROM restaurant WHERE company_id = :company_id";
                $restaurant_stmt = $conn->prepare($restaurant_sql);
                $restaurant_stmt->bindParam(':company_id', $company_id);
                $restaurant_stmt->execute();
                $restaurants = $restaurant_stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <h4>Restoranlar</h4>
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Ad</th>
                            <th>Açıklama</th>
                            <th>Ürünler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($restaurants) > 0): ?>
                            <?php foreach ($restaurants as $restaurant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                                    <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                                    <td><?php echo htmlspecialchars($restaurant['description']); ?></td>
                                    <td>
                                        <?php
                                        $restaurant_id = $restaurant['id'];
                                        $food_sql = "SELECT * FROM food WHERE restaurant_id = :restaurant_id";
                                        $food_stmt = $conn->prepare($food_sql);
                                        $food_stmt->bindParam(':restaurant_id', $restaurant_id);
                                        $food_stmt->execute();
                                        $foods = $food_stmt->fetchAll(PDO::FETCH_ASSOC);
                                        ?>

                                        <ul>
                                            <?php if (count($foods) > 0): ?>
                                                <?php foreach ($foods as $food): ?>
                                                    <li><?php echo htmlspecialchars($food['name']) . " - " . htmlspecialchars($food['price']) . "₺"; ?></li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li>Ürün bulunamadı.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Restoran bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Kayıt bulunamadı.</p>
        <?php endif; ?>

        <?php $conn = null; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
