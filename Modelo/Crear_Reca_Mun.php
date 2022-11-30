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
            $id_facturacion = $_POST['id_facturacion'];
            $fecha_pago_bitacora = $_POST['fecha_pago_bitacora'];
            $fecha_pago_municipio = $_POST['fecha_pago_municipio'];
            $valor_recaudo = str_replace(',', '', $_POST['valor_recaudo']);
            $estado_recaudo = $_POST['estado_recaudo'];
            $nota_fiducia = $_POST['nota_fiducia'];
            $fecha_fiducia = $_POST['fecha_fiducia'];
            $fecha_aplicacion_encargo = $_POST['fecha_aplicacion_encargo'];
            $observaciones = strtoupper($_POST['observaciones']);
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE recaudo_municipales_2
                                          SET ID_FACTURACION = '" . $id_facturacion . "', "
                                          . " FECHA_PAGO_BITACORA = '" . $fecha_pago_bitacora . "', "
                                          . " FECHA_PAGO_MUNICIPIO = '" . $fecha_pago_municipio . "', "
                                          . " VALOR_RECAUDO = '" . $valor_recaudo . "', "
                                          . " ESTADO_RECAUDO = '" . $estado_recaudo . "', "
                                          . " NOTA_FIDUCIA = '" . $nota_fiducia . "', "
                                          . " FECHA_FIDUCIA = '" . $fecha_fiducia . "', "
                                          . " FECHA_APL_ENCARGO = '" . $fecha_aplicacion_encargo . "', "
                                          . " OBSERVACIONES = '" . $observaciones . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "' "
                                    . " WHERE ID_RECAUDO = " . $_GET['editar']);
            echo "<script>";
                echo "document.location.href = '../Recaudo_Municipales.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM recaudo_municipales_2 WHERE ID_FACTURACION = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../Recaudo_Municipales.php'";
                echo "</script>";
            } else {
                $id_facturacion = $_POST['id_facturacion'];
                $fecha_pago_bitacora = $_POST['fecha_pago_bitacora'];
                $fecha_pago_municipio = $_POST['fecha_pago_municipio'];
                $valor_recaudo = str_replace(',', '', $_POST['valor_recaudo']);
                $estado_recaudo = $_POST['estado_recaudo'];
                $nota_fiducia = $_POST['nota_fiducia'];
                $fecha_fiducia = $_POST['fecha_fiducia'];
                $fecha_aplicacion_encargo = $_POST['fecha_aplicacion_encargo'];
                $observaciones = strtoupper($_POST['observaciones']);
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO recaudo_municipales_2 (ID_FACTURACION, FECHA_PAGO_BITACORA, FECHA_PAGO_MUNICIPIO,
                                                                              NOTA_FIDUCIA, FECHA_FIDUCIA, FECHA_APL_ENCARGO,
                                                                              VALOR_RECAUDO, ESTADO_RECAUDO, OBSERVACIONES,
                                                                              FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                                      VALUES ('" . $id_facturacion .
                                                                              "', '" . $fecha_pago_bitacora . "', '" . $fecha_pago_municipio .
                                                                              "', '" . $nota_fiducia . "', '" . $fecha_fiducia .
                                                                              "', '" . $fecha_aplicacion_encargo .
                                                                              "', '" . $valor_recaudo . "', '" . $estado_recaudo .
                                                                              "', '" . $observaciones . "', '" . $fecha_actualizacion .
                                                                              "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                $query_select_info_dpto = mysqli_query($connection, "SELECT * FROM facturacion_municipales_2 WHERE ID_FACTURACION = " . $id_facturacion);
                $row_info_dpto = mysqli_fetch_array($query_select_info_dpto);
                mysqli_query($connection, "UPDATE alcaldias_2 SET VALOR_CARTERA = VALOR_CARTERA - " . $valor_recaudo . " WHERE ID_COD_DPTO = '" . $row_info_dpto['ID_COD_DPTO'] . "' AND ID_COD_MPIO = '" . $row_info_dpto['ID_COD_MPIO'] . "'");
                $query_select_max_id_recaudo = mysqli_query($connection, "SELECT MAX(ID_RECAUDO) AS ID_RECAUDO "
                                                                           . "  FROM recaudo_municipales_2 ");
                $row_max_id_recaudo = mysqli_fetch_array($query_select_max_id_recaudo);
                echo $row_max_id_recaudo['ID_RECAUDO'];
            }
        }
    }
?>