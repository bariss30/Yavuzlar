<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Tamamlandı</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            margin-top: 100px;
        }

        h1 {
            color: #4CAF50;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tebrikler!</h1>
        <p>Quiz'i tamamladınız. Tüm soruları  yanıtladınız!</p>
        <a href="index.php" class="button">Ana Sayfa</a>
        <a href="score.php" class="button">ScoreBoard </a>
    </div>
</body>
</html>
