<!DOCTYPE html>
<html>
    <head>
        <title>Тестируем PHP</title>
    </head>
    <body>
        <h2>User:</h2>
		<form action="reader.php" method="get">
		    <p>Nickname:
		    <input type="text" name="nickname" />
		    </p>
		    <p>Password:
    	    <input type="text" name="password" />
    	    </p>
    	    <input type="submit" value="Send" />
		</form>
		
		<h2>Device:</h2>
		<form action="reader.php" method="get">
		    <p>Name:
		    <input type="text" name="device" />
		    </p>
		    <p>Description:
    	    <input type="text" name="description" />
    	    </p>
    	    <p>Username:
    	    <input type="text" name="username" />
    	    </p>
    	    <input type="submit" value="Send" />
		</form>
		
		<h2>Measurement:</h2>
		<form action="reader.php" method="get">
		    <p>Device ID:
		    <input type="number" name="device_id" />
		    </p>
		    <p>Temperature:
    	    <input type="text" name="temperature" />
    	    </p>
		    <p>Token:
    	    <input type="text" name="token" />
    	    </p>
    	    <input type="submit" value="Send" />
		</form>

    </body>
</html>