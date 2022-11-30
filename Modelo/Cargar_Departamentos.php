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
            $mes_consolidado = $_POST['mes_consolidado'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Facturacion/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'reca':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Recaudo/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'cata':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Catastro/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'nove':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Novedades/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'cartera':
            $ano_factura = $_POST['ano_factura'];
            $result = scandir("D:/BASES DE DATOS/Cartera Fallida/" . $ano_factura . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'refact':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Refacturacion/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'mpio_gral':
            $periodo_inicio = str_replace("-", "", $_POST['periodo_inicio']);
            $periodo_fin = str_replace("-", "", $_POST['periodo_fin']);
            $query_select_fact_oper_departamentos = mysqli_query($connection, "SELECT DISTINCT(DV.NOMBRE), DV.ID_DEPARTAMENTO "
                . "  FROM facturacion_operadores_2 FO, departamentos_visitas_2 DV "
                . " WHERE FO.PERIODO_FACTURA BETWEEN '$periodo_inicio' AND '$periodo_fin' "
                . "   AND FO.ID_COD_DPTO = DV.ID_DEPARTAMENTO");
            echo "<option value='' selected='selected'>-</option>";
            while ($row_fact_oper_departamentos = mysqli_fetch_assoc($query_select_fact_oper_departamentos)) {
                echo "<option value='" . $row_fact_oper_departamentos['ID_DEPARTAMENTO'] . "'>" . $row_fact_oper_departamentos['NOMBRE'] . "</option>";
            }
            break;
        case 'liq':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Liquidaciones/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
    }
}
