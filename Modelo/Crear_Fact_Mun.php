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
        if (isset($_GET['editar'])) {
            $fecha_factura = $_POST['fecha_factura'];
            $revisado_por = $_POST['revisado_por'];
            $fecha_entrega = $_POST['fecha_entrega'];
            $fecha_vencimiento = $_POST['fecha_vencimiento'];
            $consecutivo_fact = $_POST['consecutivo_fact'];
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $id_ano_fact = $_POST['id_ano_fact'];
            $id_mes_fact = $_POST['id_mes_fact'];
            $periodo_factura = $id_ano_fact . "" . $id_mes_fact;
            $no_cc_vencidas = $_POST['no_cc_vencidas'];
            $valor_factura = str_replace(',', '', $_POST['valor_factura']);
            $estado_factura = $_POST['estado_factura'];
            $observaciones = strtoupper($_POST['observaciones']);
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE facturacion_municipales_2
                                          SET ID_COD_DPTO = '" . $departamento . "', "
                                          . " ID_COD_MPIO = '" . $municipio . "', "
                                          . " FECHA_FACTURA = '" . $fecha_factura . "', "
                                          . " FECHA_ENTREGA = '" . $fecha_entrega . "', "
                                          . " FECHA_VENCIMIENTO = '" . $fecha_vencimiento . "', "
                                          . " CONSECUTIVO_FACT = '" . $consecutivo_fact . "', "
                                          . " PERIODO_FACTURA = '" . $periodo_factura . "', "
                                          . " NO_CC_VENCIDAS = '" . $no_cc_vencidas . "', "
                                          . " VALOR_FACTURA = '" . $valor_factura . "', "
                                          . " ESTADO_FACTURA = '" . $estado_factura . "', "
                                          . " OBSERVACIONES = '" . $observaciones . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "', "
                                          . " ID_USUARIO_REVISADO = '" . $revisado_por. "' "
                                    . " WHERE ID_FACTURACION = " . $_GET['editar']);
            echo "<script>";
                echo "document.location.href = '../Facturacion_Municipales.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM facturacion_municipales_2 WHERE ID_FACTURACION = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../Facturacion_Municipales.php'";
                echo "</script>";
            } else {
                $fecha_factura = $_POST['fecha_factura'];
                $fecha_entrega = $_POST['fecha_entrega'];
                $fecha_vencimiento = $_POST['fecha_vencimiento'];
                $consecutivo_fact = $_POST['consecutivo_fact'];
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                $periodo_factura = $_POST['periodo_factura'];
                $no_cc_vencidas = $_POST['no_cc_vencidas'];
                $valor_factura = $_POST['valor_factura'];
                $estado_factura = $_POST['estado_factura'];
                $observaciones = strtoupper($_POST['observaciones']);
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO facturacion_municipales_2 (ID_COD_DPTO, ID_COD_MPIO, FECHA_FACTURA, FECHA_ENTREGA,
                                                                                  FECHA_VENCIMIENTO, CONSECUTIVO_FACT, PERIODO_FACTURA,
                                                                                  NO_CC_VENCIDAS, VALOR_FACTURA, ESTADO_FACTURA,
                                                                                  OBSERVACIONES, FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                                          VALUES ('" . $departamento . "', '" . $municipio .
                                                                                  "', '" . $fecha_factura . "', '" . $fecha_entrega .
                                                                                  "', '" . $fecha_vencimiento . "', '" . $consecutivo_fact .
                                                                                  "', '" . $periodo_factura . "', '" . $no_cc_vencidas .
                                                                                  "', '" . $valor_factura . "', '" . $estado_factura .
                                                                                  "', '" . $observaciones . "', '" . $fecha_actualizacion .
                                                                                  "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                mysqli_query($connection, "UPDATE alcaldias_2 SET VALOR_CARTERA = VALOR_CARTERA + " . $valor_factura . " WHERE ID_COD_DPTO = '$departamento' AND ID_COD_MPIO = '$municipio'");
                $query_select_max_id_facturacion = mysqli_query($connection, "SELECT MAX(ID_FACTURACION) AS ID_FACTURACION "
                                                                           . "  FROM facturacion_municipales_2 ");
                $row_max_id_facturacion = mysqli_fetch_array($query_select_max_id_facturacion);
                echo $row_max_id_facturacion['ID_FACTURACION'];
            }
        }
    }
?>