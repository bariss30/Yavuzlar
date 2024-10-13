<?php 
session_start(); 

include('functions.php');   
include('db_connection.php'); 

if (!isLoggedIn() || $_SESSION['user']['user_type'] != 'firm') {
    $_SESSION['msg'] = "Firma olarak giriş yapmalısınız";
    header('location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
  
    

    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $company_id = $_SESSION['user']['company_id']; 

   
    

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo = $_FILES['logo'];

        $logoName = $logo['name'];
        $logoTmpName = $logo['tmp_name'];
        $logoSize = $logo['size'];
        $logoError = $logo['error'];
        $logoType = $logo['type'];

       
        


        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($logoName, PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowed)) {
            $logoNewName = uniqid('', true) . "." . $fileExt;
            $logoDestination = 'uploads/' . $logoNewName;

       
            

            if (move_uploaded_file($logoTmpName, $logoDestination)) {
               
                

                $query = "INSERT INTO restaurant (company_id, name, description, image_path, created_at) VALUES (?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($query);
                $stmt->execute([$company_id, $name, $description, $logoNewName]);

                $_SESSION['msg'] = "Restoran başarıyla eklendi";
                header('location: index.php');
                exit();
            } else {
                $_SESSION['msg'] = "Dosya yüklenirken bir hata oluştu.";
            }
        } else {
            $_SESSION['msg'] = "Yalnızca JPG, JPEG, PNG ve GIF formatında dosyalar yükleyebilirsiniz.";
        }
    } else {
        $_SESSION['msg'] = "Lütfen bir logo dosyası seçin.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Restoran Ekle</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Yeni Restoran Ekle</h2>
    </div>
    <div class="content">
        
        <?php if (isset($_SESSION['msg'])) : ?>
            <div class="error success">
                <h3>
                    <?php 
                        echo $_SESSION['msg']; 
                        unset($_SESSION['msg']); 
                        
                        
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- Restoran Form -->
        <form method="post" action="add_restaurant.php" enctype="multipart/form-data">
            <div class="input-group">
                <label>Restoran Adı</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-group">
                <label>Açıklama</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="input-group">
                <label>Logo Yükle</label>
                <input type="file" name="logo" required>
            </div>
            <div class="input-group">
                <button type="submit" name="submit">Restoran Ekle</button>
            </div>
        </form>
    </div>
</body>
</html>
