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
            $departamento = $_POST['departamento'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Facturacion/" . $departamento . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'reca':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $departamento = $_POST['departamento'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Recaudo/" . $departamento . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'cata':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $departamento = $_POST['departamento'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Catastro/" . $departamento . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'nove':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $departamento = $_POST['departamento'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Novedades/" . $departamento . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'cartera':
            $ano_factura = $_POST['ano_factura'];
            $departamento = $_POST['departamento'];
            $result = scandir("D:/BASES DE DATOS/Cartera Fallida/" . $ano_factura . "/" . $departamento . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'refact':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $departamento = $_POST['departamento'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Refacturacion/" . $departamento . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
        case 'mpio_gral':
            $periodo_inicio = str_replace("-", "", $_POST['periodo_inicio']);
            $periodo_fin = str_replace("-", "", $_POST['periodo_fin']);
            $departamento_departamento = $_POST['departamento_departamento'];
            $query_select_fact_oper_municipios = mysqli_query($connection, "SELECT DISTINCT(MV.NOMBRE), MV.ID_MUNICIPIO "
                . "  FROM facturacion_operadores_2 FO, departamentos_visitas_2 DV, municipios_visitas_2 MV "
                . " WHERE FO.PERIODO_FACTURA BETWEEN '$periodo_inicio' AND '$periodo_fin' "
                . "   AND FO.ID_COD_DPTO = '$departamento_departamento' "
                . "   AND FO.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                . "   AND FO.ID_COD_MPIO = MV.ID_MUNICIPIO "
                . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO");
            echo "<option value='' selected='selected'>-</option>";
            while ($row_fact_oper_municipios = mysqli_fetch_assoc($query_select_fact_oper_municipios)) {
                echo "<option value='" . $row_fact_oper_municipios['ID_MUNICIPIO'] . "'>" . $row_fact_oper_municipios['NOMBRE'] . "</option>";
            }
            break;
        case 'liq':
            $ano_factura = $_POST['ano_factura'];
            $mes_consolidado = $_POST['mes_consolidado'];
            $departamento = $_POST['departamento'];
            $result = scandir("D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Liquidaciones/" . $departamento . "/");
            echo "<option value='' selected='selected'>-</option>";
            foreach ($result as $directories) {
                if ($directories === '.' or $directories === '..') continue;
                echo "<option value='" . $directories . "'>" . $directories . "</option>";
            }
            break;
    }
}
