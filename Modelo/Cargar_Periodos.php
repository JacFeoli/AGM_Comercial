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
            case 'oymri':
                $ano_factura = $_POST['ano_factura'];
                $query_select_periodos = mysqli_query($connection, "SELECT * FROM periodos_facturacion_municipales_2 WHERE ANO_FACTURA = '" . $ano_factura . "'");
                echo "<option value='' selected='selected'>-</option>";
                while ($row_periodos = mysqli_fetch_assoc($query_select_periodos)) {
                    if (strlen($row_periodos['MES_FACTURA']) == 1) {
                        echo "<option value='" . "0" . $row_periodos['MES_FACTURA'] . "'>" . $row_periodos['PERIODO'] . "</option>";
                    } else {
                        echo "<option value='" . $row_periodos['MES_FACTURA'] . "'>" . $row_periodos['PERIODO'] . "</option>";
                    }
                }
                break;
        }
    }
?>