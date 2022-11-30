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
            $id_contribuyente = $_POST['id_contribuyente'];
            $acuerdo_municipal = $_POST['acuerdo_municipal'];
            $tipo_cliente = $_POST['tipo_cliente'];
            $fecha_factura = $_POST['fecha_factura'];
            $fecha_entrega = $_POST['fecha_entrega'];
            $fecha_vencimiento = $_POST['fecha_vencimiento'];
            $consecutivo_fact = $_POST['consecutivo_fact'];
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $id_ano_fact = $_POST['id_ano_fact'];
            $id_mes_fact = $_POST['id_mes_fact'];
            $periodo_factura = $id_ano_fact . "" . $id_mes_fact;
            $no_liq_vencidas = $_POST['no_liq_vencidas'];
            $valor_liq_vencidas = str_replace(',', '', $_POST['valor_liq_vencidas']);
            $tipo_facturacion = $_POST['id_tipo_facturacion'];
            $tarifa = $_POST['tarifa'];
            $valor_tarifa = str_replace(',', '', $_POST['valor_tarifa']);
            $valor_factura = str_replace(',', '', $_POST['valor_factura']);
            $id_comercializador = $_POST['id_comercializador'];
            $facturado_por = $_POST['facturado_por'];
            $estado_factura = $_POST['estado_factura'];
            $observaciones = strtoupper($_POST['observaciones']);
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE facturacion_especiales_2
                                          SET ID_CONTRIBUYENTE = '" . $id_contribuyente . "', "
                                          . " ID_TIPO_CLIENTE = '" . $tipo_cliente . "', "
                                          . " FECHA_FACTURA = '" . $fecha_factura . "', "
                                          . " FECHA_ENTREGA = '" . $fecha_entrega . "', "
                                          . " FECHA_VENCIMIENTO = '" . $fecha_vencimiento . "', "
                                          . " CONSECUTIVO_FACT = '" . $consecutivo_fact . "', "
                                          . " ID_COD_DPTO = '" . $departamento . "', "
                                          . " ID_COD_MPIO = '" . $municipio . "', "
                                          . " ACUERDO_MCPAL = '" . $acuerdo_municipal . "', "
                                          . " PERIODO_FACTURA = '" . $periodo_factura . "', "
                                          . " NO_LIQ_VENCIDAS = '" . $no_liq_vencidas . "', "
                                          . " VALOR_LIQ_VENCIDAS = '" . $valor_liq_vencidas . "', "
                                          . " ID_TIPO_FACTURACION = '" . $tipo_facturacion . "', "
                                          . " TARIFA = '" . $tarifa . "', "
                                          . " VALOR_TARIFA = '" . $valor_tarifa . "', "
                                          . " VALOR_FACTURA = '" . $valor_factura . "', "
                                          . " ID_COMERCIALIZADOR = '" . $id_comercializador . "', "
                                          . " ID_FACTURADO_POR = '" . $facturado_por . "', "
                                          . " ESTADO_FACTURA = '" . $estado_factura . "', "
                                          . " OBSERVACIONES = '" . $observaciones . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "' "
                                    . " WHERE ID_FACTURACION = " . $_GET['editar']);
            echo "<script>";
                echo "document.location.href = '../Facturacion_Especiales.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM facturacion_especiales_2 WHERE ID_FACTURACION = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../Facturacion_Especiales.php'";
                echo "</script>";
            } else {
                $id_contribuyente = $_POST['contribuyente'];
                $acuerdo_municipal = strtoupper($_POST['acuerdo_municipal']);
                $tipo_cliente = $_POST['tipo_cliente'];
                $fecha_factura = $_POST['fecha_factura'];
                $fecha_entrega = $_POST['fecha_entrega'];
                $fecha_vencimiento = $_POST['fecha_vencimiento'];
                $consecutivo_fact = $_POST['consecutivo_fact'];
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                $periodo_factura = $_POST['periodo_factura'];
                $no_liq_vencidas = $_POST['no_liq_vencidas'];
                $valor_liq_vencidas = $_POST['valor_liq_vencidas'];
                $tipo_facturacion = $_POST['tipo_facturacion'];
                $tarifa = $_POST['tarifa'];
                $valor_tarifa = $_POST['valor_tarifa'];
                $valor_factura = $_POST['valor_factura'];
                $id_comercializador = $_POST['comercializador'];
                $facturado_por = $_POST['facturado_por'];
                $estado_factura = $_POST['estado_factura'];
                $observaciones = strtoupper($_POST['observaciones']);
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO facturacion_especiales_2 (ID_CONTRIBUYENTE, ID_TIPO_CLIENTE, FECHA_FACTURA, FECHA_ENTREGA,
                                                                                FECHA_VENCIMIENTO, CONSECUTIVO_FACT, ID_COD_DPTO, ID_COD_MPIO, ACUERDO_MCPAL,
                                                                                PERIODO_FACTURA, NO_LIQ_VENCIDAS, VALOR_LIQ_VENCIDAS,
                                                                                ID_TIPO_FACTURACION, TARIFA, VALOR_TARIFA,
                                                                                VALOR_FACTURA, ID_COMERCIALIZADOR, ID_FACTURADO_POR, ESTADO_FACTURA,
                                                                                OBSERVACIONES, FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                                        VALUES ('" . $id_contribuyente . "', '" . $tipo_cliente .
                                                                                "', '" . $fecha_factura . "', '" . $fecha_entrega .
                                                                                "', '" . $fecha_vencimiento . "', '" . $consecutivo_fact .
                                                                                "', '" . $departamento . "', '" . $municipio . "', '" . $acuerdo_municipal .
                                                                                "', '" . $periodo_factura . "', '" . $no_liq_vencidas .
                                                                                "', '" . $valor_liq_vencidas . "', '" . $tipo_facturacion .
                                                                                "', '" . $tarifa . "', '" . $valor_tarifa .
                                                                                "', '" . $valor_factura . "', '" . $id_comercializador .
                                                                                "', '" . $facturado_por .  "', '" . $estado_factura .
                                                                                "', '" . $observaciones . "', '" . $fecha_actualizacion .
                                                                                "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                $query_select_max_id_facturacion = mysqli_query($connection, "SELECT MAX(ID_FACTURACION) AS ID_FACTURACION "
                                                                           . "  FROM facturacion_especiales_2 ");
                $row_max_id_facturacion = mysqli_fetch_array($query_select_max_id_facturacion);
                echo $row_max_id_facturacion['ID_FACTURACION'];
            }
        }
    }
?>