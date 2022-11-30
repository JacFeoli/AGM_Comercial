<?php
session_start();
require_once('../Includes/Config.php');
if ($_SESSION['timeout'] + 60 * 60 < time()) {
    session_unset();
    session_destroy();
    $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location:$ruta/Login.php");
} else {
    $_SESSION['timeout'];
    switch ($_POST['sw']) {
        case 'fact':
            $ano_factura = $_POST['ano_factura'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'reca':
            $ano_factura = $_POST['ano_factura'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'cata':
            $ano_factura = $_POST['ano_factura'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'nove':
            $ano_factura = $_POST['ano_factura'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'refact':
            $ano_factura = $_POST['ano_factura'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'liq':
            $ano_factura = $_POST['ano_factura'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
    }
}
