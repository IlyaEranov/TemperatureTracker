<?php
    include "scripts/check_autorization.php";
    include "scripts/validation.php";
    
    $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
    
    $token = mysqli_real_escape_string($db, $_GET["token"]);
    $name = mysqli_real_escape_string($db, $_COOKIE["is_autorized"]);
    $query = "SELECT * FROM `Devices` WHERE `Token`=\"$token\" AND `Devices`.`User_id`=(SELECT Id FROM `Users` WHERE `Users`.`Nickname`=\"$name\")";
    $result = mysqli_query($db, $query);
    
    if ($result->num_rows == 0) {
        header("Location: account.php");
        exit();
    }
    else {
        $device = mysqli_fetch_array($result);
        $name = $device["Name"];
        $desc = $device["Description"];
        $min_temp = $device["Lower_limit"];
        $max_temp = $device["Upper_limit"];
    }
    
    if($_GET["save"] == "yes" and !empty($_GET["device_name"]) and !empty($_GET["description"])) {
        $name = mysqli_real_escape_string($db, $_GET["device_name"]);
        $desc = mysqli_real_escape_string($db, $_GET["description"]);
        $query = "UPDATE `Devices` SET `Name`=\"$name\", `Description`=\"$desc\" WHERE `Devices`.`Token`= \"$token\"";
        mysqli_query($db, $query);
    }
    
    if($_GET["set"] == "yes" and !empty($_GET["min_temp"]) and !empty($_GET["max_temp"])) {
        $min_temp = mysqli_real_escape_string($db, $_GET["min_temp"]);
        $max_temp = mysqli_real_escape_string($db, $_GET["max_temp"]);
        $query = "UPDATE `Devices` SET `Lower_limit`=\"$min_temp\", `Upper_limit`=\"$max_temp\" WHERE `Devices`.`Token`= \"$token\"";
        mysqli_query($db, $query);
    }
    
    if($_GET["change"] == "yes") {
        $new_token = md5(md5(microtime()));
        $query = "UPDATE `Devices` SET `Token`=\"$new_token\" WHERE `Devices`.`Id`=(SELECT Id WHERE `Devices`.`Token`=\"$token\")"; 
        $token = $new_token;
        mysqli_query($db, $query);
    }
    
    mysqli_close($db);
?>


<!DOCTYPE html> 
<html lang="ru" class="h-100 d-flex justify-content-center"> 
    <head> 
        <meta charset="UTF-8" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="styles/style.css" /> 
        <link 
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
            rel="stylesheet" 
            integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
            crossorigin="anonymous" 
        /> 
        <title>Изменить токен устройства</title> 
    </head> 
    <body class="h-100 bg-primary bg-opacity-25"> 
           <main class="main_token" >   
            <form class="form_token h-100">
                <input hidden name="token" value="<?php echo $token; ?>" />
              <div class="d-flex justify-content-center align-items-center flex-column"> 
                  <label class="form-label mb-2">Имя:</label> 
                  <input class="form-control" placeholder="Имя устройство" name="device_name" value="<?php echo $name; ?>"/>  
                    <?php
                        if (!empty($_GET["save"])) {
                            empty_field_error("device_name");
                        }
                    ?>
              </div> 
              <div class="d-flex justify-content-center align-items-center flex-column"> 
                  <label class="form-label">Описание:</label> 
                  <textarea class="form-control mb-2" placeholder="Описание устройства" name="description" style="width: 206px;"><?php echo $desc; ?></textarea> 
                    <?php
                        if (!empty($_GET["save"])) {
                            empty_field_error("description");
                        }
                    ?>
              </div> 
              <button class="button_token btn btn-primary" name="save" value="yes">Сохранить</button> 
            </form> 
            <form class="form_token h-100"> 
              <input class="form-control" value="<?php echo $token; ?>" type="text" disabled="disabled" style="width: 300px;"/>  
              <input hidden name="token" value="<?php echo $token; ?>" />
              <button class="button_token btn btn-primary mt-3" name="change" value="yes">Изменить токен</button>  
            </form>
            <form class="form_token h-100">
                <input hidden name="token" value="<?php echo $token; ?>" />
        		<input class="form-control mb-3 ps-0 pe-0" name="max_temp" value="<?php echo $max_temp; ?>"
        		    placeholder="Установите максимальную температуру" type="number"  style="width: 300px;"/>
                <?php
                    if (!empty($_GET["set"])) {
                        empty_field_error("max_temp");
                    }
                ?>
        		<input class="form-control mb-3 ps-0 pe-0" name="min_temp" value="<?php echo $min_temp; ?>"
        		    placeholder="Установите минимальную температуру" type="number" style="width: 300px;"/>
                <?php
                    if (!empty($_GET["set"])) {
                        empty_field_error("min_temp");
                    }
                ?>
        		<button class="button_token btn btn-primary mt-3" name="set" value="yes">Установить граничные значения</button>  
            </form> 
        </main> 
        <footer> 
          <a class="button_registration btn btn-primary" href="account.php">Назад</a> 
        </footer> 
    </body>   
</html>