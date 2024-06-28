<?php
    include "scripts/bot_token.php";
    
    $data = json_decode(file_get_contents('php://input'), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
    $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
        
    $id = $data["message"]["chat"]["id"];
    $text = $data["message"]["text"];
    $name = $data["message"]["from"]["username"];
    $getQuery = array(
        "chat_id" 	=> $id,
        "text"  	=> "Привет!",
        "parse_mode" => "html",
    );
    
    if ($text == "/start") {
        $getQuery["reply_markup"] = json_encode(array(
            'keyboard' => array(
                array (
        	        array(
        		        'text' => 'Ваши устройства',
        		        'url' => 'https://temparature-praktic.ru/bot.php',
            	        ),
        	        ),
                ),
                'one_time_keyboard' => FALSE,
                'resize_keyboard' => TRUE,
            ));
        
        $query = "UPDATE `Users` SET `Chat_id`=$id WHERE (`Users`.`Telegram_id`=\"$name\")";
    	$result = mysqli_query($db, $query);
    }
    
    if ($text == "Ваши устройства") {
        $query = "SELECT * FROM Devices WHERE Devices.User_id=(SELECT Users.Id FROM Users WHERE Users.Telegram_id=\"$name\")";
    	$result = mysqli_query($db, $query);
        
        if ($result->num_rows == 0) {
            $responce = "Нет сохраненных устройств";
        }
        
        $counter = 0;
        while($str = mysqli_fetch_array($result)) {
            $counter++;
            $responce .= "Устройство $counter:\n";
            
            $device = $str["Name"];
            $responce .= "Наименование: $device\n";
            
            $query = "SELECT * FROM `Measurements` WHERE `Measurements`.`Device_id`=".$str["Id"]." ORDER BY `Measurements`.`Date` DESC";
            $measures = mysqli_query($db, $query);
            
            $last_temp = mysqli_fetch_array($measures)["Temperature"];
            if (isset($last_temp)) {
                $responce .= "Последняя зафиксированная температура: ".$last_temp."°C\n\n";
            }
            else {
                $responce .= "Нет записей о температуре\n\n";
            }
        }
        $getQuery["text"] = $responce;
    }
    
    $token = TOKEN;
    $ch = curl_init("https://api.telegram.org/bot$token/sendMessage?".http_build_query($getQuery));
    $resultQuery = curl_exec($ch);
    curl_close($ch);
    
    mysqli_close($db);
?>