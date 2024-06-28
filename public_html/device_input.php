<?php
	$db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
	
	if (!empty($_GET["temperature"]) and !empty($_GET["token"])) {
    	$temperature = mysqli_real_escape_string($db, $_GET["temperature"]);
    	$token = mysqli_real_escape_string($db, $_GET["token"]);
    	$ip = mysqli_real_escape_string($db, $_SERVER["REMOTE_ADDR"]);
    	
    	$query = "SELECT Devices.Id FROM Devices WHERE Devices.Token=\"$token\"";
    	$result = mysqli_query($db, $query);
    	$device_id = mysqli_fetch_array($result)["Id"];
    	
    	$query = "SELECT Devices.Token FROM Devices WHERE Devices.Token=\"$token\"";
    	$result = mysqli_query($db, $query);
    	$correctToken = mysqli_fetch_array($result)["Token"];
    	
    	if ($token == $correctToken) {
    	    $query = "INSERT INTO `Measurements`(`Device_id`, `Date`, `Temperature`, `IP`) VALUES (\"$device_id\", NOW(), \"$temperature\", \"$ip\")";
        	if (mysqli_query($db, $query)) {
        	    echo "Data successful added";
        	}
    	}
    	else {
    	    echo "Fail";
    	}
	}
?>