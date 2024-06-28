<?php
    include "scripts/check_autorization.php";
    include "scripts/validation.php";
    
    if ($_POST["exit"] == "yes") {
        setcookie("is_autorized", "", time() - 3600);
        header("Location: index.php");
        exit();
    }
    
    $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
    
    $nikcname = mysqli_real_escape_string($db, $_POST["nickname"]);
    $name = mysqli_real_escape_string($db, $_COOKIE["is_autorized"]);
        
    $query = "SELECT `Telegram_id` FROM `Users` WHERE `Nickname`=\"$name\"";
    $result = mysqli_query($db, $query);
    $cur_telegram_id = mysqli_fetch_array($result)["Telegram_id"];
    
    if ($_POST["save"] == "yes") {
        $query = "UPDATE `Users` SET `Telegram_id`=\"$nikcname\" WHERE (`Users`.`Nickname`=\"$name\")";
    	$result = mysqli_query($db, $query);
    }
    mysqli_close($db);
?>

<!DOCTYPE html> 
<html lang="ru" class="h-100"> 
    <head> 
        <meta charset="UTF-8" /> 
        <link rel="stylesheet" href="../styles/style.css" /> 
        <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
        crossorigin="anonymous" 
        /> 
        <title>Профиль</title> 
    </head> 
    <body class="bg-primary bg-opacity-25 h-100" style="margin: 0 auto"> 
        <main class="m-0"> 
            <h1 class="title">Профиль</h1> 
            <form class="profile_form" method="post"> 
                <input class="form-control mb-1 h-25 fs-3" name="nickname" type="name"
                    value="<?php echo $cur_telegram_id;?>" placeholder="IvanIvanov"/> 
                <?php
                    if ($_POST["save"] == "yes") {
                        empty_field_error("nickname");
                    }
                ?>
                <label class="mb-1">Ваш ник в Telegram</label> 
                <button class="button_save btn btn-primary mb-3" name="save" value="yes"
                    onclick="alert('Напишите нашему боту в Telegram @tmp_bot_nikita_bot.')">Сохранить</button> 
                <button class="btn btn-primary" name="exit" value="yes">Выйти из профиля</button> 
            </form> 
            <a class="btn btn-primary" href="account.php">Назад</a> 
        </main> 
    </body> 
</html>