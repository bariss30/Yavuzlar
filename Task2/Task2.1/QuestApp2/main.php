<?php
session_start(); 

require_once 'conn.php';


$sql = "SELECT * FROM sorular ORDER BY id"; 
$questions = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);




$mevcutid = array_column($questions, 'id');
$maxQuestionId = max($mevcutid);


if (!isset($_SESSION['current_question_id'])) {
    $_SESSION['current_question_id'] = $mevcutid[0];
}



foreach ($questions as $question) {
    if ($question['id'] == $_SESSION['current_question_id']) {
        $currentQuestion = $question;
        break;
    }
}


$alertMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer'])) {
        $selectedAnswer = $_POST['answer']; 
        $correctAnswer = $currentQuestion['truecevap'];

        

       
        if ($selectedAnswer === $correctAnswer) { 
            $username = $_SESSION['username']; 

          
$username = $_SESSION['username'];


$updateScore = "UPDATE users SET score = score + 10 WHERE username = '$username'";
$stmt = $conn->prepare($updateScore);
$stmt->execute();

          
            $alertMessage = "10 puan kazandınız!";
        } else {
            
            $alertMessage = "Yanlış cevap!";
        }
    }

  
    if (isset($_POST['next'])) {
        
        $_SESSION['current_question_id']++;

        
        if ($_SESSION['current_question_id'] > $maxQuestionId) {
            $_SESSION['current_question_id'] = $mevcutid[0]; 
           
        }

    
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="style.css">
















    
    <script>
        // JavaScript kodu ile alert mesajını göstermek için
        window.onload = function() {
            <?php if (!empty($alertMessage)) echo "alert('$alertMessage');"; ?>
        };
    </script>

























</head>
<body>
    <div class="app">
        <h1>Quiz</h1>
        <div class="quiz">
        <?php
        if ($currentQuestion) {
            echo "<h2>" . $currentQuestion['soru'] . "</h2>";
            echo "<div>";
            echo "<form id='quiz-form' method='post' action=''>";
            echo "<button name='answer' value='1'>" . $currentQuestion['cevap1'] . "</button>";
            echo "<button name='answer' value='2'>" . $currentQuestion['cevap2'] . "</button>";
            echo "<button name='answer' value='3'>" . $currentQuestion['cevap3'] . "</button>";
            echo "<button name='answer' value='4'>" . $currentQuestion['cevap4'] . "</button>";
            echo "<button name='next' type='submit'>Next</button>";
            echo "</form>";
            echo "</div>";
        } else {
            echo "<p>Soru bulunamadı</p>";
        }
        ?>
        </div>
    </div>
</body>
</html>
