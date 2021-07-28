<?php
// Initialize the session
//session_start();

// We start the session, then we login saving the login variables as part of the session. 
 
// Check if the user is already logged in, if yes then redirect him to welcome page
/*if($_SESSION["loggedin"] != false){
    header("location: /menu.html");
    exit;
}*/

 
// Define variables and initialize with empty values
$username = $_POST['username'];
$password = $_POST['password'];
$username_login = "Test";
$password_login = "Test";
 
// Processing form data when form is submitted
if(isset($_POST["login"])){
    
    if($username == $username_login && $password == $password_login){ 
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;                            
        echo '<script>alert("Login sucsessful")</script>';              
        header("location: /members.php");
    } 
    else{
        //$_SESSION["message"] = true;
        echo "Authentication Failed";
        header("location: /login.php");
    }
                   
}
?>
