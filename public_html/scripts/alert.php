<?php
    include "check_autorization.php";
    include "bot_token.php";
    
    $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
    
    $token = mysqli_real_escape_string($db, $_GET["token"]);
    $name = mysqli_real_escape_string($db, $_COOKIE["is_autorized"]);
    
    $query = "SELECT * FROM `Devices` WHERE `Token`=\"$token\" AND `Devices`.`User_id`=(SELECT Id FROM `Users` WHERE `Users`.`Nickname`=\"$name\")";
    $result = mysqli_query($db, $query);
    
    if ($result->num_rows == 0) {
        exit();
    }
    
    $device = mysqli_fetch_array($result);
    $name = $device["Name"];
    $min_temp = $device["Lower_limit"];
    $max_temp = $device["Upper_limit"];
    $state = $device["State"];
    $user_id = $device["User_id"];
    $cur_temp = $_GET["temperature"];
    
    $new_state = $state;
    $responce_text;
    if ($cur_temp < $min_temp and $state != "-1") {
        $responce_text = "Ваше устройство $name зафиксировало критически низкую температуру: ".$cur_temp."°C при минимальной норме в ".$min_temp."°C";
        $new_state = -1;
        echo -1;
    }
    elseif ($cur_temp > $max_temp and $state != "1") {
        $responce_text = "Ваше устройство $name зафиксировало критически высокую температуру: ".$cur_temp."°C при максимальной норме в ".$max_temp."°C";
        $new_state = 1;
        echo 1;
    }
    elseif ($cur_temp > $min_temp and $cur_temp < $max_temp and $state != "0") {
        $responce_text = "Температура, фиксируемая устройством $name".", вернулась в нормальное состояние";
        $new_state = 0;
        echo 0;
    }
    $new_state = mysqli_real_escape_string($db, $new_state);
    $query = "UPDATE `Devices` SET `State`=$new_state WHERE `Devices`.`Token`=\"$token\"";
    $result = mysqli_query($db, $query);
    
    $query = "SELECT `Chat_id` FROM `Users` WHERE `Id`=$user_id";
    $chat_id = mysqli_fetch_array(mysqli_query($db, $query))["Chat_id"];
    
    $getQuery = array(
        "chat_id" 	=> $chat_id,
        "text"  	=> $responce_text,
        "parse_mode" => "html"
    );
    
    $bot_token = TOKEN;
    
    $ch = curl_init("https://api.telegram.org/bot$bot_token/sendMessage?".http_build_query($getQuery));
    $resultQuery = curl_exec($ch);
    
    curl_close($ch);
?>