<?php
    $DB_SERVER = 'localhost';
    $DB_NAME = 'agm';
    $DB_USER = 'root';
    $DB_PASS = '';
    $connection = mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS);
    if (!$connection) {
        die('No se pudo conectar con la Base de Datos ' . mysqli_error());
    } else {
        $db_select = mysqli_select_db($connection, $DB_NAME);
        if (!$db_select) {
            die('Error al seleccionar la Base de Datos ' . mysqli_error());
        }
    }
?>