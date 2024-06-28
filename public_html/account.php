<?php
    include "scripts/check_autorization.php";
    
    $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
    
    $name = mysqli_real_escape_string($db, $_COOKIE["is_autorized"]);
    $query = "SELECT * FROM Devices WHERE Devices.User_id=(SELECT Users.Id FROM Users WHERE Users.Nickname=\"$name\")";
	$result = mysqli_query($db, $query);
?>

<!DOCTYPE html> 
<html> 
    <head> 
        <meta charset="UTF-8" /> 
        <link rel="stylesheet" href="styles/style.css" /> 
        <link 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
          crossorigin="anonymous" 
        /> 
        <title>Личный кабинет</title> 
    </head> 
    <body class="bg-primary bg-opacity-25" style="margin: 10vh auto 0;"> 
        <header> 
            <a class="btn btn-primary me-3" href="profile.php">Профиль</a> 
            <a class="button_add btn btn-primary" href="addDevice.php">Добавить устройство</a> 
        </header> 
        <main class="main"> 
            <h1 class="title">Все устройства пользователя:</h1> 
            <?php
                if ($result->num_rows == 0) {
                    echo "Нет сохраненных устройств";
                }
                
                while($str = mysqli_fetch_array($result)) {
                    echo "<section class=\"device_object\">";
                    
                    $device = $str["Name"];
                    echo "<div class=\"device_div device_name p-1\">$device</div>";
                    
                    $id = mysqli_real_escape_string($db, $str["Id"]);
                    echo "<div class=\"device_div device_id p-1\">$id</div>";
                    
                    $desc = $str["Description"];
                    echo "<textarea class=\"device_div device_description-lk p-1\">$desc</textarea>";
                    
                    $query = "SELECT * FROM `Measurements` WHERE `Measurements`.`Device_id`=$id ORDER BY `Measurements`.`Date` DESC";
                    $measures = mysqli_query($db, $query);
                    
                    $last_temp = mysqli_fetch_array($measures)["Temperature"];
                    $temperature = isset($last_temp) ? $last_temp."°C" : "Нет записей";
                    echo "<div class=\"device_div device_temperature p-1\">$temperature</div>";
                    
                    $token = $str["Token"];
                    echo "<a class=\"button_token btn btn-primary p-1\" href=\"changeDevice.php?token=$token\">Изменить параметры</a>";
                    echo "<a class=\"button_token btn btn-primary p-1\" href=\"device.php?token=$token\">Посмотреть историю</a>"; 
                    
                    echo "</section>";
                }
                
                mysqli_close($db);
            ?>
        </main>
    </body>
</html>