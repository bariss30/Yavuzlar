<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db_connection.php';

$db = mysqli_connect('db', 'root', 'rootpassword', 'multi_login');

$username = "";
$email    = "";
$errors   = array(); 

if (isset($_POST['register_btn'])) {
    register();
}

function register() {
    global $db, $errors, $username, $email;

    $username    = e($_POST['username']);
    $email       = e($_POST['email']);
    $password_1  = e($_POST['password_1']);
    $password_2  = e($_POST['password_2']);

    if (empty($username)) { 
        array_push($errors, "Username is required"); 
    }
    if (empty($email)) { 
        array_push($errors, "Email is required"); 
    }
    if (empty($password_1)) { 
        array_push($errors, "Password is required"); 
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    if (count($errors) == 0) {
        $password = md5($password_1);

        if (isset($_POST['user_type'])) {
            $user_type = e($_POST['user_type']);
            $query = "INSERT INTO users (username, email, user_type, password) VALUES ('$username', '$email', '$user_type', '$password')";
            mysqli_query($db, $query);
            $_SESSION['success'] = "New user successfully created!!";
            header('location: home.php');
        } else {
            $query = "INSERT INTO users (username, email, user_type, password) VALUES ('$username', '$email', 'user', '$password')";
            mysqli_query($db, $query);

            $logged_in_user_id = mysqli_insert_id($db);
            $_SESSION['user'] = getUserById($logged_in_user_id);
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');                          
        }
    }
}

function getUserById($id) {
    global $db;
    $query = "SELECT * FROM users WHERE id=$id";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}

function e($val) {
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
    global $errors;
    if (count($errors) > 0) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}

if (isset($_POST['login_btn'])) {
    login();
}

function login() {
    global $db, $username, $errors;

    $username = e($_POST['username']);
    $password = e($_POST['password']);

    if (empty($username)) {
        array_push($errors, "Kullanıcı adı gerekli");
    }
    if (empty($password)) {
        array_push($errors, "Şifre gerekli");
    }

    if (count($errors) == 0) {
        $password = md5($password); 
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND deleted_at IS NULL LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $logged_in_user = mysqli_fetch_assoc($results);
            $_SESSION['user'] = $logged_in_user;
            $_SESSION['success'] = "Giriş yaptınız";

            if ($logged_in_user['user_type'] == 'admin') {
                header('location: admin/home.php');
            } else {
                header('location: index.php');
            }
            exit();
        } else {
            array_push($errors, "Yanlış kullanıcı adı/şifre kombinasyonu veya kullanıcı silinmiş.");
        }
    }
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin';
}

function create_company($name, $description, $password) {
    global $db;
    $password_hash = md5($password);
    $query = "INSERT INTO company (name, description, password) VALUES ('$name', '$description', '$password_hash')";
    mysqli_query($db, $query);
}

function getUserBalance($userId) {
    global $db;
    $stmt = $db->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $balance = $result->fetch_assoc()['balance'];
    $stmt->close();
    return $balance;
}



function searchFood($name, $priceRange) {
    global $db;
    $sql = "SELECT * FROM food WHERE name LIKE ? OR price BETWEEN ? AND ?";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $searchName = "%" . $name . "%";
        $stmt->bind_param("sdd", $searchName, $priceRange[0], $priceRange[1]);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Sorgu hazırlamada hata: " . $db->error);
    }
}
?>
