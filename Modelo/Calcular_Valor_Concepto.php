<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $info_valores = array();
        $id_departamento = $_POST['id_departamento'];
        $id_municipio = $_POST['id_municipio'];
        $query_select_valor_concepto = mysqli_query($connection, "SELECT VALOR_CONCEPTO, VALOR_CARTERA "
                                                               . "  FROM alcaldias_2 "
                                                               . " WHERE ID_COD_DPTO = '$id_departamento' "
                                                               . "   AND ID_COD_MPIO = '$id_municipio'");
        $row_valor_concepto = mysqli_fetch_array($query_select_valor_concepto);
        $info_valores[0] = $row_valor_concepto['VALOR_CONCEPTO'];
        $info_valores[1] = $row_valor_concepto['VALOR_CARTERA'];
        echo json_encode($info_valores);
        exit();
    }
?>