<?php
    function print_error($description) {
        echo "<p class=\"paragraph_error\" style=\"color: red\">$description</p>";
    }
    
    function empty_field_error($name) {
        if (empty($_POST[$name]) and empty($_GET[$name])) {
            print_error("Заполните поле");
        }
    }
    
    function unmatch_passwords($pas1, $pas2) {
        if (($_POST[$pas1] !== $_POST[$pas2])) {
            print_error("Пароли не совпадают");
        }
    }
    
    function is_fill($array) {
        if (empty($array)) {
            return false;
        }
        
        foreach ($array as $elem) {
            if (empty($elem)) {
                return false;
            }
        }
        return true;
    }
?>