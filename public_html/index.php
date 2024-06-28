<?php
    include "scripts/validation.php";
    
    if (!empty($_COOKIE["is_autorized"])) {
        header("Location: account.php");
        exit();
    }
    
    if (!empty($_POST["register"])) {
        header("Location: registration.php");
        exit();
    }
    
    if (!empty($_POST["entrance"])) {
        $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
        
        $name = mysqli_real_escape_string($db, $_POST["username"]);
        $query = "SELECT Users.Nickname, Users.Password FROM Users WHERE Users.Nickname=\"$name\"";
    	$result = mysqli_fetch_array(mysqli_query($db, $query));
    	$probable_password = $result["Password"];
    
        $password = mysqli_real_escape_string($db, md5($_POST["password"]));
        if ($probable_password === $password and !empty($password)) {
            setcookie("is_autorized", $name);
        	header('Location: account.php');
            exit();
        }
    }
?>

<!DOCTYPE html> 
<html lang="ru" class="h-100">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
            crossorigin="anonymous"
        />
        <title>Вход</title>
    </head>
    <body class="h-100 bg-primary bg-opacity-25 d-flex justify-content-center align-items-center " >
        <section class="section_entrance  bg-white border border-primary border-5 rounded py-2 ">
            <form class="form_entrance p-5 align-items-center d-flex flex-column " method="post">
                <h1>Вход</h1>
                <div class="input_section mb-3">
                    <label for="inputName" class="paragraph form-label">Имя пользователя:</label>
                    <input class="input form-control" type="name" id="InputName" name="username"/> 
                    <?php
                        if (!empty($_POST["entrance"])) {
                            empty_field_error("username");
                        }
                    ?>
                </div>
                <div class="input_section mb-3">
                    <label for="inputPassword" class="paragraph form-label">Пароль:</label>
                    <input class="input form-control" type="password" id="inputPassword" name="password"/> 
                    <?php
                        if (!empty($_POST["entrance"])) {
                            empty_field_error("password");
                        }
                        if (is_fill($_POST)) {
                            print_error("Неверный логин или пароль");
                        }
                    ?>
                </div>
                <button type="submit" class="button_entrance btn btn-primary mb-3" name="entrance" value="yes">Войти</button>
                <button class="button_registration btn btn-primary" name="register" value="yes">Регистрация</button>
            </form> 
        </section> 
    </body> 
</html>
