<?php
$servername = "db";  
$username = "root";
$password = "rootpassword";
$dbname = "multi_login";

$attempts = 5; 
$wait = 5;    

$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$conn = null;

for ($i = 0; $i < $attempts; $i++) {
    try {
        $conn = new PDO($dsn, $username, $password, $options);
        break;
    } catch (PDOException $e) {
        if ($i === $attempts - 1) {
         
            error_log("Database connection failed: " . $e->getMessage());
            die("Unable to connect to MySQL after $attempts attempts.");
        }
        sleep($wait);  
    }
}

if ($conn) {
    
   
}
?>