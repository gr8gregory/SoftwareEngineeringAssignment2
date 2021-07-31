<?php 
    require_once __DIR__ . "/Database.php";
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

    $database = new Database('127.0.0.1', 'choeksema', 'eMach1ne', 'elevator');

    $columns = array('requestedFloor', 'nodeID');
    $conditions = array('status'=>2, 'currentFloor'=>0, 'otherInfo'=>'"elevatorCar"');
    try
    {
        $success = $database->Select('elevatorNetwork', $columns, $conditions, 'or');
    }
    catch (Exception $e)
    {
        echo "<p>Exception thrown while accessing database.</p>";
        echo $e->getMessage();
    }
    echo '<p>Obtained ' . count($success) . ' result(s)</p>';
    foreach($success as $x)
        echo "<p>ID: " . $x['nodeID'] . ", floor " . $x['requestedFloor'] . "</p>";


    $columns = array('date'=>'CURRENT_DATE', 'time'=>'CURRENT_TIME', 'status'=>0, 'currentFloor'=>0, 'requestedFloor'=>13 /*'otherInfo'=>'elevatorCar'*/);
    try
    {
        $numRows = 0;
        $numRows = $database->Insert('elevatorNetwork', $columns);
    }
    catch (Exception $e)
    {
        echo "<p>Exception thrown while accessing database.</p>";
        echo $e->getMessage();
    }
    echo '<p>Inserted ' . $numRows . ' row(s) into the database</p>';


    $conditions = array('requestedFloor'=>13);
    try
    {
        $numRows = 0;
        $numRows = $database->Delete('elevatorNetwork', $conditions, 'and');
    }
    catch (Exception $e)
    {
        echo "<p>Exception thrown while accessing database.</p>";
        echo $e->getMessage();
    }
    echo '<p>Deleted ' . $numRows . ' row(s) from the database</p>';  


    $columns = array('requestedFloor'=>13);
    $conditions = array('nodeID'=>91, 'currentFloor'=>0);
    try
    {
        $numRows = 0;
        $numRows = $database->Update('elevatorNetwork', $columns, $conditions, 'and');
    }
    catch (Exception $e)
    {
        echo "<p>Exception thrown while accessing database.</p>";
        echo $e->getMessage();
    }
    echo '<p>Updated ' . $numRows . ' row(s) in the database</p>';    
?>
