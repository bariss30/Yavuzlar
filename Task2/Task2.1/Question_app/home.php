<?php
session_start();
require_once 'conn.php'; 


if (!isset($_SESSION['username'])) {
    die("Kullanıcı oturum açmamış.");
}

$username = $_SESSION['username'];

$conn->exec("PRAGMA busy_timeout = 5000"); 

$stmt = $conn->prepare("SELECT solved_questions FROM member WHERE username = :username");
$stmt->execute(['username' => $username]);
$solvedQuestions = $stmt->fetchColumn();

if ($solvedQuestions === false) {
    die("Veritabanından veri alınamadı veya kullanıcı çözdüğü sorular bulunamadı.");
}

$solvedQuestionsArray = !empty($solvedQuestions) ? explode(',', $solvedQuestions) : [];

if (empty($solvedQuestionsArray)) {
    $sql = "SELECT * FROM sorular ORDER BY id";
} else {
    $sql = "SELECT * FROM sorular WHERE id NOT IN (" . implode(',', array_map('intval', $solvedQuestionsArray)) . ") ORDER BY id";
}

$questions = $conn->query($sql);

if (!$questions) {
    die("Sorular veritabanından alınamadı.");
}

$questions = $questions->fetchAll(PDO::FETCH_ASSOC);

if (empty($questions)) {
    header('Location: finish.php');
    exit();
}

$currentQuestion = $questions[0];

$alertMessage = '';
$answerStatus = ''; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer'])) {
        $selectedAnswer = $_POST['answer'];
        $correctAnswer = $currentQuestion['truecevap'];

        if ($selectedAnswer == $correctAnswer) {
            $alertMessage = "Doğru cevap! 10 puan kazandınız!";
            $answerStatus = 'correct';
        } else {
            $alertMessage = "Yanlış cevap!";
            $answerStatus = 'incorrect';
        }

        $solvedQuestionsArray[] = $currentQuestion['id'];
        $updatedSolvedQuestions = implode(',', $solvedQuestionsArray);

        $updateStmt = $conn->prepare("UPDATE member SET solved_questions = :solved_questions WHERE username = :username");
        $updateStmt->execute([
            'solved_questions' => $updatedSolvedQuestions,
            'username' => $username
        ]);

        header('Location: next.php');
        exit();
    }
}

$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .quiz {
            text-align: center;
            margin-top: 50px;
        }

        button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            width: 200px;
            margin: 10px auto;
        }

        button.correct {
            background-color: #4CAF50 !important;
            color: white;
        }

        button.incorrect {
            background-color: #f44336 !important;
            color: white;
        }

        .message {
            font-size: 18px;
            color: green;
        }

        .logout-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-button:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="app">
        <button class="logout-button" onclick="window.location.href='login.php';">Çıkış Yap</button>
        <h1>Quiz</h1>
        <div class="quiz">
            <?php if (!empty($alertMessage)): ?>
                <p class="message"><?php echo $alertMessage; ?></p>
            <?php endif; ?>

            <?php if ($currentQuestion): ?>
                <h2><?php echo htmlspecialchars($currentQuestion['soru']); ?></h2>
                <form id="quiz-form" method="post" action="">
                    <button name="answer" value="1" class="<?php echo ($answerStatus && $selectedAnswer == 1) ? $answerStatus : ''; ?>">
                        <?php echo htmlspecialchars($currentQuestion['cevap1']); ?>
                    </button>
                    <button name="answer" value="2" class="<?php echo ($answerStatus && $selectedAnswer == 2) ? $answerStatus : ''; ?>">
                        <?php echo htmlspecialchars($currentQuestion['cevap2']); ?>
                    </button>
                    <button name="answer" value="3" class="<?php echo ($answerStatus && $selectedAnswer == 3) ? $answerStatus : ''; ?>">
                        <?php echo htmlspecialchars($currentQuestion['cevap3']); ?>
                    </button>
                    <button name="answer" value="4" class="<?php echo ($answerStatus && $selectedAnswer == 4) ? $answerStatus : ''; ?>">
                        <?php echo htmlspecialchars($currentQuestion['cevap4']); ?>
                    </button>
                </form>
            <?php else: ?>
                <p>Soru bulunamadı</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
