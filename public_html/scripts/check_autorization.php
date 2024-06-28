<?php
    if (empty($_COOKIE["is_autorized"])) {
        header("Location: index.php");
        exit();
    }
?>