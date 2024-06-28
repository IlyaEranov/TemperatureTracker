<?php
    include "scripts/validation.php";
    if (!empty($_POST["registred"])) {
        $db = mysqli_connect("localhost", "c92793sz_db", "SAnu5aR%", "c92793sz_db");
        $name = mysqli_real_escape_string($db, $_POST["username"]);
        $query = "SELECT Users.Nickname FROM Users WHERE Users.Nickname=\"$name\"";
    	$result = mysqli_query($db, $query);
    	$probable_name = mysqli_fetch_array($result)["Nickname"];
    	
        if (is_fill($_POST) and sizeof($_POST) != 0 and ($_POST["pas1"] === $_POST["pas2"]) and !isset($probable_name)) {
            $password = mysqli_real_escape_string($db, md5($_POST["pas1"]));
            $query = "INSERT INTO `Users`(`Nickname`, `Password`) VALUES (\"$name\", \"$password\")";
            mysqli_query($db, $query);
            
        	mysqli_close($db);
        	header('Location: index.php');
            exit();
        }
    }
?>

<!DOCTYPE html> 
<html lang="ru" class="h-100"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1" /> 
        <link 
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
			rel="stylesheet" 
			integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
			crossorigin="anonymous" 
        /> 
        <title>Регистрация</title> 
    </head> 
    <body class="h-100 bg-primary bg-opacity-25 d-flex justify-content-center align-items-center "> 
        <main>
            <section class="section_entrance bg-white border border-primary border-5 rounded p-5 d-flex align-items-center flex-column"> 
                <form class="d-flex justify-content-center align-items-center flex-column" method="post"> 
                    <h1 class="title">Регистрация</h1> 
                    <div class="input_section mb-3"> 
                        <label for="inputName" class="paragraph form-label">Имя пользователя:</label> 
                        <input class="input form-control" type="name" id="InputName" placeholder="IvanIvanov" name="username"/> 
                        <?php
                            if (!empty($_POST["registred"])) {
                                empty_field_error("username");
                            }
                            
                        	if (isset($probable_name)) {
                        	    print_error("Данное имя  уже занято");
                        	}
                        ?>
                    </div> 
                    <div class="input_section mb-3"> 
                        <label for="inputPassword" class="paragraph form-label">Пароль:</label> 
                        <input class="input form-control" type="password" id="inputPassword" name="pas1" /> 
                        <?php
                             if (!empty($_POST["registred"])) {
                                empty_field_error("pas1");
                                unmatch_passwords("pas1", "pas2");
                            }
                        ?>
                    </div> 
                    <div class="input_section mb-3"> 
                        <label for="secondPassword" class="paragraph form-label">Повторите пароль:</label> 
                        <input class="input form-control" type="password" id="secondPassword" name="pas2" /> 
                        <?php
                             if (!empty($_POST["registred"])) {
                                empty_field_error("pas2");
                                unmatch_passwords("pas1", "pas2");
                            }
                        ?>
                    </div> 
                    <button type="submit" class="button_registration btn btn-primary mb-3" name="registred" value="pressed">Зарегистрироваться</button> 
                </form>
                <a class="button_registration btn btn-primary" href="index.php">Назад</a>
            </section>
        </main> 
    </body> 
</html>