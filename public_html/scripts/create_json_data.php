<?php

    $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
    
    $token = mysqli_real_escape_string($db, $_GET["token"]);
    $period = $_GET["period"];
    $record_number = 1;
    
    switch($period) {
        case "'Час'": $record_number *= 60; break;
        case "'День'": $record_number *= 60 * 24; break;
        case "'Неделя'": $record_number *= 60 * 24 * 7; break;
        default: $record_number = 60;
    }
    file_put_contents("log.txt", $period);
    
    $query = "SELECT `Id` FROM `Devices` WHERE `Devices`.`Token` = \"$token\"";
    $result = mysqli_query($db, $query);
    
    if ($result->num_rows == 0) {
        header("Location: account.php");
        exit();
    }
    
    $id = mysqli_fetch_array($result)["Id"];
    $query = "SELECT * FROM (SELECT * FROM `Measurements` WHERE `Measurements`.`Device_id`=$id ORDER BY `Measurements`.`Date` DESC LIMIT $record_number) tbl ORDER BY `Date`";
    $result = mysqli_query($db, $query);
    
    $json_array = array(
        "name" => "Динамика температуры",
        "interval" => "monthly",
        "unit" => "°C",
        "data" => array()
    );
    
    if ($result->num_rows == 0) {
        echo "Нет записей";
        exit();
    }
    
    while ($str = mysqli_fetch_array($result)) {
        $date = $str["Date"];
        $temp = $str["Temperature"];
        $json_array["data"][] = array("date" => $date, "value" => $temp);
    }
    
    header('Content-type: application/json');
    echo json_encode($json_array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
    mysqli_close($db);
?>
