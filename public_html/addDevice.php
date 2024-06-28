<?php
    include "scripts/check_autorization.php";
    include "scripts/validation.php";
    
    function is_not_empty_fields() {
        if (!empty($_POST["device_name"]) and !empty($_POST["description"])) {
            return true;
        }
        return false;
    }
    
    if (is_not_empty_fields()) {
        $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
        
	    $device = mysqli_real_escape_string($db, $_POST["device_name"]);
    	$description = mysqli_real_escape_string($db, $_POST["description"]);
    	$username = mysqli_real_escape_string($db, $_COOKIE["is_autorized"]);
    	$min_temp = mysqli_real_escape_string($db, $_POST["min_temp"]);
    	$max_temp = mysqli_real_escape_string($db, $_POST["max_temp"]);
    	$token = md5(md5(microtime()));
    	
	    $query = "INSERT INTO `Devices`(`Name`, `Description`, `Lower_limit`, `Upper_limit`, `User_id`, `Token`) VALUES"
	    ."(\"$device\", \"$description\", \"$min_temp\", \"$max_temp\","
	    ."(SELECT Users.Id FROM Users WHERE Users.Nickname=\"$username\"), \"$token\")";
	    mysqli_query($db, $query);
	    
        mysqli_close($db);
	}
?>

<!DOCTYPE html> 
<html lang="ru"> 
    <head> 
        <meta charset="UTF-8" /> 
        <link rel="stylesheet" href="styles/style.css" /> 
        <link 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
          crossorigin="anonymous" 
        /> 
        <title>Добавить устройство</title> 
    </head> 
    <body class="h-100 bg-primary bg-opacity-25" style="margin: 0 auto;"> 
        <main> 
            <h1 class="title">Ваше устройство:</h1>
            <form class="device_object align-items-start" method="post">
                <div class="d-flex justify-content-center align-items-center flex-column"> 
                    <input class="device_div device_name" placeholder="Имя устройства" name="device_name" /> 
                    <?php
                        if (!empty($_POST["changed"])) {
                            empty_field_error("device_name");
                        }
                    ?>
                </div>
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <textarea class="device_div device_description_add" placeholder="Описание устройства" name="description" ></textarea>
                    <?php
                        if (!empty($_POST["changed"])) {
                            empty_field_error("description");
                        }
                    ?>
                </div>
                <div> 
                    <input class="form-control mb-3 ps-0 pe-0 " name="max_temp" placeholder="Установите максимальную температуру" type="text"  style="width: 300px;"/> 
					<input class="form-control mb-3 ps-0 pe-0" name="min_temp" placeholder="Установите минимальную температуру" type="text" style="width: 300px;"/>   
                </div> 
                <button class="button_token btn btn-primary" name="changed" value="yes">Добавить</button>
            </form>
            <?php
                if (is_not_empty_fields()) {
                     echo "<p class=\"paragraph_error\" style=\"color: black\">Устройство успешно добавлено!</p>";
                }
            ?>
            <a class="button_registration btn btn-primary" href="account.php">Назад</a> 
        </main>
    </body>
</html>