<?php
    session_start();
    if(!empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        echo '<script>alert("login Failed")</script>';
        $_SESSION['message'] = null;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body{ font: 14px sans-serif; }
            .wrapper{ width: 360px; padding: 20px; }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h2>Login</h2>
            <p>Please fill in your credentials to login.</p>

            <form action="authentication.php" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control ">
                   
                </div>    
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control ">
                    
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary"name="login" value="Login">
                </div>

            </form>
        </div>
    </body>
</html>
