<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Genel stil ayarları */
        body {
            font-family: Arial, sans-serif;
          
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background:#001e4d;
        }

      
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

       
        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

     
        .form-group {
            margin-bottom: 15px;
        }

       
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

     
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group button{
        
            background: #001e4d;
    color: #fff;
    font-weight: 500;
    width: 150px;
    border :0 ;
padding: 10px;
margin: 20px auto 0 ;
    border-radius: 4px;
display: none;
    cursor: pointer;

     }
       

       

       
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button  type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
