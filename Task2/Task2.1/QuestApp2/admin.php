<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main PAge</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app2">
        <h1>Edit Quiz</h1>
        <div class="edit">
            <h2 id="questions">Youcaneditquestionshere</h2> 
            <div id="edits-buttons">
               
            <form action="addquestions.php" method="GET">
    <button type="submit" class="btn">Soru ekle</button>
</form>
<form action="delete.php" method="GET">
    <button type="submit" class="btn">Soru sil</button>
</form>

<form action="update.php" method="GET">
    <button type="submit" class="btn">Soru Düzenle</button>
</form>

           
                
                <button id="search-question-btn" class="btn">Soru Arama</button>
            </div>
            <button id="next-btn">Next</button>
        </div>
    </div>
</body>
</html>