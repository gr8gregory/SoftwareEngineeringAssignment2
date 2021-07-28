<?php 
    session_start();
?>


<!DOCTYPE html>
<html>
    <head>
        <title>members</title>
        <meta name='description' content='Page in project' />
        <meta name='robots' content='noindex nofollow' />
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, inital-scale=1"/>
        <meta http-equiv='author' content='ghuras' />
        <meta http-equiv='pragma' content='no-cache' />

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!--Bootstrap JS code-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <!--[if lt IE 9]>
                <script src=http://html5shiv.googlecode.com/svn/trunk/html5.js></script>
        <![endif]-->
    </head>
</html>

<?php
    if($_SESSION["loggedin"] == true){
        echo "<h1>Welcome ";
        echo $_SESSION["username"];
        echo " To the Members Only Page!</h1>";
    }
    else{
        echo $_SESSION["loggedin"];
        echo $_SESSION["username"];
        //echo '<script>alert("You are not logged in.")</script>';
        //header("location: login.php");
    }
?>
