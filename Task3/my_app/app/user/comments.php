<?php 
include '../db_connection.php'; 
include '../functions.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

if (isset($_GET['delete_comment'])) {
    $comment_id = $_GET['delete_comment'];
    

    $query = "UPDATE comments SET score = NULL, updated_at = NOW() WHERE id = :comment_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['comment_id' => $comment_id]);
    
    header('location: comments.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user']['id']; 
    $restaurant_id = $_POST['restaurant_id'];
    $surname = $_POST['surname'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $score = $_POST['score'];



$query = "INSERT INTO comments (user_id, restaurant_id, surname, title, description, score, created_at, updated_at) 
              VALUES (:user_id, :restaurant_id, :surname, :title, :description, :score, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        'user_id' => $user_id,
        'restaurant_id' => $restaurant_id,
        'surname' => $surname,
        'title' => $title,
        'description' => $description,
        'score' => $score
    ]);

    header('location: comments.php');
    exit();
}

$comments = $conn->query("
    SELECT comments.*, restaurant.name AS restaurant_name 
    FROM comments 
    JOIN restaurant ON comments.restaurant_id = restaurant.id 
    ORDER BY comments.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorumlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Restoran Yönetimi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Anasayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="restaurant_list.php">Restoranlar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="comments.php">Yorumlar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php" style="color: red;">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Yorumlar</h2>

        <form method="POST" class="mb-4">
            <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($_GET['restaurant_id']); ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="surname" placeholder="Soyadınız" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="title" placeholder="Başlık" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="description" rows="3" placeholder="Yorumunuzu buraya yazın..." required></textarea>
            </div>
            <div class="form-group">
                <label for="score">Puan (1-5):</label>
                <select class="form-control" name="score" required>
                    <option value="" disabled selected>Puanı seçin</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Yorum Ekle</button>
        </form>

        <div class="row">
        <?php foreach ($comments as $comment): ?>
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo htmlspecialchars($comment['surname']); ?> 
                            (Puan: <?php echo htmlspecialchars($comment['score'] > 0 ? $comment['score'] : 'Silinmiş'); ?>)
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?php echo htmlspecialchars($comment['title']); ?> - 
                            Restoran: <?php echo htmlspecialchars($comment['restaurant_name']); ?>
                        </h6>
                        <p class="card-text"><?php echo htmlspecialchars($comment['description']); ?></p>
                        <p class="text-muted"><small><?php echo htmlspecialchars($comment['created_at']); ?></small></p>
                        <a href="comments.php?delete_comment=<?php echo $comment['id']; ?>" class="btn btn-danger btn-sm">Yorumu Sil</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
