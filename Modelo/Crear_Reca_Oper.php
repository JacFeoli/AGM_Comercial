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
            $fecha_recaudo = $_POST['fecha_recaudo'];
            $fecha_pago_bitacora = $_POST['fecha_pago_bitacora'];
            $valor_recaudo = str_replace(',', '', $_POST['valor_recaudo']);
            $estado_recaudo = $_POST['estado_recaudo'];
            $nota_fiducia = $_POST['nota_fiducia'];
            $fecha_fiducia = $_POST['fecha_fiducia'];
            $fecha_aplicacion_encargo = $_POST['fecha_aplicacion_encargo'];
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE recaudo_operadores_2
                                          SET ID_FACTURACION = '" . $id_facturacion . "', "
                                          . " FECHA_RECAUDO = '" . $fecha_recaudo . "', "
                                          . " FECHA_PAGO_BITACORA = '" . $fecha_pago_bitacora . "', "
                                          . " VALOR_RECAUDO = '" . trim($valor_recaudo) . "', "
                                          . " ESTADO_RECAUDO = '" . $estado_recaudo . "', "
                                          . " NOTA_FIDUCIA = '" . $nota_fiducia . "', "
                                          . " FECHA_FIDUCIA = '" . $fecha_fiducia . "', "
                                          . " FECHA_APL_ENCARGO = '" . $fecha_aplicacion_encargo . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "' "
                                    . " WHERE ID_RECAUDO = " . $_GET['editar']);
            echo "<script>";
                echo "document.location.href = '../Recaudo_Operadores.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM recaudo_operadores_2 WHERE ID_RECAUDO = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../Recaudo_Operadores.php'";
                echo "</script>";
            } else {
                $id_facturacion = $_POST['id_facturacion'];
                $fecha_recaudo = $_POST['fecha_recaudo'];
                $fecha_pago_bitacora = $_POST['fecha_pago_bitacora'];
                $valor_recaudo = str_replace(',', '', $_POST['valor_recaudo']);
                $estado_recaudo = $_POST['estado_recaudo'];
                $nota_fiducia = $_POST['nota_fiducia'];
                $fecha_fiducia = $_POST['fecha_fiducia'];
                $fecha_aplicacion_encargo = $_POST['fecha_aplicacion_encargo'];
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO recaudo_operadores_2 (ID_FACTURACION, FECHA_RECAUDO, FECHA_PAGO_BITACORA,
                                                                             NOTA_FIDUCIA, FECHA_FIDUCIA, FECHA_APL_ENCARGO,
                                                                             VALOR_RECAUDO, ESTADO_RECAUDO,
                                                                             FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                                     VALUES ('" . $id_facturacion .
                                                                             "', '" . $fecha_recaudo . "', '" . $fecha_pago_bitacora .
                                                                             "', '" . $nota_fiducia . "', '" . $fecha_fiducia .
                                                                             "', '" . $fecha_aplicacion_encargo .
                                                                             "', '" . $valor_recaudo . "', '" . $estado_recaudo .
                                                                             "', '" . $fecha_actualizacion .
                                                                             "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                mysqli_query($connection, "UPDATE facturacion_operadores_2 SET ESTADO_FACTURA = 1 WHERE ID_FACTURACION = '" . $id_facturacion . "'");
                $query_select_max_id_recaudo = mysqli_query($connection, "SELECT MAX(ID_RECAUDO) AS ID_RECAUDO "
                                                                           . "  FROM recaudo_operadores_2 ");
                $row_max_id_recaudo = mysqli_fetch_array($query_select_max_id_recaudo);
                echo $row_max_id_recaudo['ID_RECAUDO'];
            }
        }
    }
?>