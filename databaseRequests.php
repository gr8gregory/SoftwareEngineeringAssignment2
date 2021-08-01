<?php
    require_once __DIR__ . "/Database.php";
    require_once __DIR__ . "/Logger.php";

    if(isset($_POST['Execute']))
    {
    	try
    	{
	    	// Get query type
	    	$queryType = $_POST['query'];

	    	// Get tableName
	    	$tableName = $_POST['tableName'];

	    	// Get columns
	    	$colNames = array_filter($_POST['colName']);
	    	$colVals = array_filter($_POST['colVal']);

	    	if ($_POST['colChoice'] == 'all')
	    	{
	    		$columns = array();
	    	}
	    	else if ($_POST['colChoice'] == 'choose')
	    	{
	    		if (count($colNames) != count($colVals))
	        		throw new Exception("Column entry counts don't match");
	    		
	    		$columns = array_combine($colNames, $colVals);
	    	}

	    	// Get conditions
	    	$condNames = array_filter($_POST['condName']);
	    	$condVals = array_filter($_POST['condVal']);
	    	$overwrite = false;

	    	if ($_POST['condChoice'] == 'all')
	    	{
	    		$conditions = array();
	    		$condJoin = '';
	    		$overwrite = true;
	    	}
	    	
	    	else if ($_POST['condChoice'] == 'choose')
	    	{
	    		if ((count($condNames) == 0) || (count($condNames) != count($condVals)))
	        		throw new Exception("Condition entry counts don't match or empty");

	    		$conditions = array_combine($condNames, $condVals);

	    		$condJoin = $_POST['condJoin'];
	    	}


			$database = new Database('127.0.0.1', 'choeksema', 'eMach1ne', 'elevator');

	    	if ($queryType == 'select')
	    	{
	    		try
			    {
			        $success = $database->Select($tableName, $colNames, $conditions, $condJoin);
			    }
			    catch (Exception $e)
			    {
			        echo "<p>Oops. Something really bad happened.</p>";
			        echo Logger::writeToConsole($e->getMessage());
			    }
			    echo '<p>Obtained ' . count($success) . ' result(s)</p>';
			    foreach($success as $row)
			    {
			    	echo '<p>';
			    	foreach($row as $key=>$value)
			    		echo $key . ': ' . $value . ', ';
			    	
			    	echo '</p>';
			    }
	    	}
	    	else if ($queryType == 'insert')
	    	{
	    		try
			    {
			        $success = $database->Insert($tableName, $columns);
			    }
			    catch (Exception $e)
			    {
			        echo "<p>Oops. Something really bad happened.</p>";
			        echo Logger::writeToConsole($e->getMessage());
			    }
			    echo '<p>Inserted ' . $success . ' row(s)</p>';
	    	}
	    	else if ($queryType == 'update')
	    	{
	    		try
			    {
			        $success = $database->Update($tableName, $columns, $conditions, $condJoin, $overwrite);
			    }
			    catch (Exception $e)
			    {
			        echo "<p>Oops. Something really bad happened.</p>";
			        echo Logger::writeToConsole($e->getMessage());
			    }
			    echo '<p>Updated ' . $success . ' row(s)</p>';
	    	}
	    	else if ($queryType == 'delete')
	    	{
	    		try
			    {
			        $success = $database->Delete($tableName, $conditions, $condJoin, $overwrite);
			    }
			    catch (Exception $e)
			    {
			        echo "<p>Oops. Something really bad happened.</p>";
			        echo Logger::writeToConsole($e->getMessage());
			    }
			    echo '<p>Deleted ' . $success . ' row(s)</p>';
	    	}
	    }
	    catch(Exception $e)
	    {
	    	echo "<p>Database error: " . $e->getMessage() . ". Please try again.</p>";
	    	echo Logger::writeToConsole($e->getMessage());
    		header("location: members.php");
	    }
    }
    else
    {
        header("location: members.php");
    }


    // Later, could sign in with session credentials
	/*$database = new Database('127.0.0.1', 'choeksema', 'eMach1ne', 'elevator');

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


    $columns = array('date'=>'CURRENT_DATE', 'time'=>'CURRENT_TIME', 'status'=>0, 'currentFloor'=>0, 'requestedFloor'=>13 /*'otherInfo'=>'elevatorCar'*//*);
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
    echo '<p>Updated ' . $numRows . ' row(s) in the database</p>';*/

?>