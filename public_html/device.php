<?php
    include "scripts/check_autorization.php";
    
    $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
    
    $token = mysqli_real_escape_string($db, $_GET["token"]);
    $name = mysqli_real_escape_string($db, $_COOKIE["is_autorized"]);
    $query = "SELECT * FROM `Devices` WHERE `Token`=\"$token\" AND `Devices`.`User_id`=(SELECT Id FROM `Users` WHERE `Users`.`Nickname`=\"$name\")";
    $result = mysqli_query($db, $query);
    
    if ($result->num_rows == 0) {
        header("Location: account.php");
        exit();
    }
    
    $row = mysqli_fetch_array($result);
    $device = $row["Name"];
    $id = $row["Id"];
    $max_temp = $row["Upper_limit"];
    $min_temp = $row["Lower_limit"];
    $state = $row["State"];
    
    $query = "SELECT * FROM `Measurements` WHERE `Measurements`.`Device_id`=$id ORDER BY `Measurements`.`Date` DESC";
    $measures = mysqli_query($db, $query);
    $last_temp = mysqli_fetch_array($measures)["Temperature"];
    $temperature = isset($last_temp) ? $last_temp : "Нет записей";
    
    mysqli_close($db);
?>

<!DOCTYPE html> 
<html> 
    <head> 
        <meta charset="UTF-8" /> 
        <link rel="stylesheet" href="styles/style.css" /> 
        <title><?php echo $device; ?></title> 
        <link 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
          crossorigin="anonymous" 
        /> 
    </head> 
    <body class="body body_device bg-primary bg-opacity-25" style="margin: 10vh auto 0;"> 
        <header class="d-flex justify-content-start"> 
            <a class="button_token btn btn-primary mt-3 d-flex align-items-center ps-5 pe-5" href="account.php">Назад</a> 
        </header>
        <main style="margin-top: 0;"> 
            <section class="device_object pt-0"> 
                <div class="device_div"><?php echo $device; ?></div> 
                <div class="device_div temperature_div"></div> 
                <input class="device_div form-control min_input" type="number" placeholder="Установите Min" value=<?php echo $min_temp;?>> 
                <input class="device_div form-control max_input" type="number" placeholder="Установите Max" value=<?php echo $max_temp;?>> 
            </section> 
            <section class="graph_section"> 
                <select class="periods">
                    <option class="week">Неделя</option>
                    <option class="day">День</option>
                    <option class="hour">Час</option>
                </select>
                <!--<div class="graph"></div>--> 
                 
                <p class="paragraph">График температуры от устройства</p> 
            </section> 
            <canvas></canvas> 
        </main> 
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> 
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
        <script> 
            //Получение контекста для рисования 
            let canvas = window.document.querySelector('canvas'); 
            let temperature = window.document.querySelector('.temperature_div');
            let minInput = window.document.querySelector('.min_input').value;
            let maxInput = window.document.querySelector('.max_input').value;
            let state = null;
            let context = canvas.getContext('2d'); 
            let chart = null;
 
            //Функции 
            const realTimeDemo = (xData, yData, data) => { 
                let i = 50; 
                let interval  
            } 
 
            const createLineChart = (xData, yData) => { 
                let data = { 
                    labels: xData, 
                    datasets: [{ 
                        label: '', 
                        data: yData, 
                        pointStyle: false, 
                        borderWidth: 1, 
                    }] 
                } 
 
                let xScaleConfig = { 
                    min: 0, 
                    ticks: { 
                        autoSkip: true, 
                        maxRotation: 0, 
                    } 
                } 
 
                let yScaleConfig = { 
                    min: 0,
                } 
 
                let config = { 
                    type: 'line', 
                    data: data, 
                    options: {
                        scales: { 
                            x: xScaleConfig,
                            y: yScaleConfig 
                        },
                        responsive: true,
                        animation: false
                    } 
                }
                chart = new Chart(context, config); 
            } 
            
            function show() {
                $.ajax({  
                    type: "GET",  
                    url: 'scripts/create_json_data.php',  
                    data: {token: <?php echo "'$token'"; ?>, period: "'" + window.document.querySelector('.periods').value + "'"}, 
                    success: function(response){
                        let data = response.data;
                        let xData = [];
                        let yData = [];
                        
                        for (let i = 0; i <data.length; i++) {
                            xData.push(data[i].date);
                            yData.push(data[i].value);
                        }
                        
                        if (chart != null) {
                            chart.destroy();
                        }
                        
                        currentTemp = yData[data.length - 1];
                        temperature.innerHTML = currentTemp + '°C';
                        if (currentTemp > maxInput && state != 1) {
                            if (temperature.classList.contains('normal_temperature')) 
                                temperature.classList.remove('normal_temperature');
                            else if (temperature.classList.contains('min_temperature')) 
                                temperature.classList.remove('min_temperature');
                            temperature.classList.add('max_temperature');
                            state = 1;
                            alert_bot();
                        }
                        else if (currentTemp < minInput && state != -1) {
                            if (temperature.classList.contains('normal_temperature'))
                                temperature.classList.remove('normal_temperature');
                            else if (temperature.classList.contains('max_temperature'))
                                temperature.classList.remove('max_temperature');
                            temperature.classList.add('min_temperature');
                            state = -1;
                            alert_bot();
                        }
                        else if (state != 0 && currentTemp >= minInput && currentTemp <= maxInput) {
                            if (temperature.classList.contains('min_temperature'))
                                temperature.classList.remove('min_temperature');
                            else if (temperature.classList.contains('max_temperature'))
                                temperature.classList.remove('max_temperature');
                            temperature.classList.add('normal_temperature');
                            state = 0;
                            alert_bot();
                        }
                        createLineChart(xData, yData);
                    }  
                });
            }
            
            function alert_bot() {
                $.ajax({  
                    type: "GET",  
                    url: 'scripts/alert.php',  
                    data: {token: <?php echo "'$token'"; ?>, temperature: currentTemp},
                    success: function() {
                    }
                });
            }
            
            $(document).ready(function() {
                show();
                setInterval('show()', 10000);
            })
        </script> 
    </body> 
</html>