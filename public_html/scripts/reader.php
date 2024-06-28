<!DOCTYPE html>
<html>
    <head>
        <title>Тестируем PHP</title>
    </head>
    <body>
		<?php
        	$db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
        	if ($db != false) {
        		echo "Success";
        	}
        	else {
        		echo mysqli_connect_error();
        	}
        	
        	if (!empty($_GET["nickname"]) and !empty($_GET["password"])) {
            	$nick = mysqli_real_escape_string($db, $_GET["nickname"]);
            	$password = mysqli_real_escape_string($db, $_GET["password"]);
            	$query = "INSERT INTO `Users`(`Nickname`, `Password`) VALUES (\"$nick\", \"$password\")";
            	echo $query;
            	if (mysqli_query($db, $query)) {
            	    echo "Data successful added";
            	}
        	}
        	elseif (!empty($_GET["device"]) and !empty($_GET["description"]) and !empty($_GET["username"])) {
        	    $device = mysqli_real_escape_string($db, $_GET["device"]);
            	$description = mysqli_real_escape_string($db, $_GET["description"]);
            	$username = mysqli_real_escape_string($db, $_GET["username"]);
            	$token = md5(md5(microtime()));
        	    $query = "INSERT INTO `Devices`(`Name`, `Description`, `User_id`, `Token`) VALUES"
        	    ."(\"$device\", \"$description\", (SELECT Users.Id FROM Users WHERE Users.Nickname=\"$username\"), \"$token\")";
        	    echo $query;
            	if (mysqli_query($db, $query)) {
            	    echo "Data successful added";
            	}
        	}
        	elseif (!empty($_GET["device_id"]) and !empty($_GET["temperature"]) and !empty($_GET["token"])) {
        	    $device_id = mysqli_real_escape_string($db, $_GET["device_id"]);
            	$temperature = mysqli_real_escape_string($db, $_GET["temperature"]);
            	$token = mysqli_real_escape_string($db, $_GET["token"]);
            	$ip = mysqli_real_escape_string($db, $_SERVER["REMOTE_ADDR"]);
            	
            	
            	$query = "SELECT Devices.Token FROM Devices WHERE Devices.Id=$device_id";
            	$result = mysqli_query($db, $query);
            	$correctToken = mysqli_fetch_array($result)["Token"];
            	echo $correctToken;
            	//echo $result;
            	if ($token == $correctToken) {
            	    $query = "INSERT INTO `Measurements`(`Device_id`, `Date`, `Temperature`, `IP`) VALUES (\"$device_id\", NOW(), \"$temperature\", \"$ip\")";
            	    echo $query;
                	if (mysqli_query($db, $query)) {
                	    echo "Data successful added";
                	}
            	}
        	}
        	else {
        	    echo "Incorrect data";
        	}

        	$print_table = function($table) use($db) {
            	$query = mysqli_real_escape_string($db, "SELECT * FROM `$table` WHERE 1");
            	if ($result = mysqli_query($db, $query)) {
            	    echo "<p>$table:</p><table>";
            	    foreach ($result as $row) {
            	        echo "<tr>";
            	        foreach ($row as $elem) {
            	            echo "<td>$elem</td>";
            	        }
            	        echo "</tr>";
            	    }
            	    echo "</table><br>";
            	}
        	};
        	
        	$print_table("Users");
        	$print_table("Devices");
        	$print_table("Measurements");
        	
        	mysqli_close($db);
        ?>   
    </body>
</html>