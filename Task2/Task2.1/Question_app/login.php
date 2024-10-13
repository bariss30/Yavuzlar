<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
        <head>
                <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
                <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
        </head>
<body>
        <style>
            body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.well {
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    margin-top: 50px;
    text-align: center;
}

h3.text-primary {
    color: #007bff;
    margin-bottom: 20px;
}

a {
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
}

.form-group {
    margin-bottom: 15px;
    text-align: left;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}

button.btn {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 3px;
    width: 100%;
    cursor: pointer;
}

button.btn:hover {
    background-color: #0056b3;
}

.alert {
    margin-top: 10px;
    padding: 10px;
    color: white;
    text-align: center;
}

.alert-info {
    background-color: #17a2b8;
}

.alert-danger {
    background-color: #dc3545;
}
        </style>
        <div class="col-md-3"></div>
        <div class="col-md-6 well">
                <h3 class="text-primary">PHP - Login And Registration </h3>
                <hr style="border-top:1px dotted #ccc;"/>
                <!-- Link for redirecting page to Registration page -->
                <a href="index.php">Not a member yet? Register here...</a>
                <br style="clear:both;"/><br />
                <div class="col-md-3"></div>
                <div class="col-md-6">
                       
                        <form method="POST" action="login_query.php">  
                                <div class="alert alert-info">Login</div>
                                <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" required="required"/>
                                </div>
                                <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required="required"/>
                                </div>
                                <?php
                                        if(ISSET($_SESSION['error'])){
                                ?>
                                        <div class="alert alert-danger"><?php echo $_SESSION['error']?></div>
                                <?php
                                        unset($_SESSION['error']);
                                        }
                                ?>
                                <button class="btn btn-primary btn-block" name="login"><span class="glyphicon glyphicon-log-in"></span> Login</button>
                        </form>
                        
                </div>
        </div>
</body>
</html>