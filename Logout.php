<?php
    session_start();
    unset($_SESSION['fullname']);
    session_destroy();
    $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location:$ruta/Login.php");
?>