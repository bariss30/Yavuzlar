<?php
session_start(); 

include 'conn.php'; 


if (isset($_POST['next'])) {
    $_SESSION['current_question_id']++;
}


$sql = "SELECT id FROM sorular ORDER BY id";
$questions = $conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);
$maxQuestionId = max($questions);
if ($_SESSION['current_question_id'] > $maxQuestionId) {
    $_SESSION['current_question_id'] = $questions[0]; 
   
}

header('Location: main.php');
exit;
?>
