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
            $estado_factura = $_POST['estado_factura'];
            $id_comercializador = $_POST['id_comercializador'];
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $id_ano_fact = $_POST['id_ano_fact'];
            $id_mes_fact = $_POST['id_mes_fact'];
            $periodo_factura = $id_ano_fact . "" . $id_mes_fact;
            $valor_factura = str_replace(',', '', $_POST['valor_factura']);
            $ajuste_fact = str_replace(',', '', $_POST['ajuste_fact']);
            $valor_recaudo = str_replace(',', '', $_POST['valor_recaudo']);
            $ajuste_reca = str_replace(',', '', $_POST['ajuste_reca']);
            $valor_energia = str_replace(',', '', $_POST['valor_energia']);
            $cuota_energia = str_replace(',', '', $_POST['cuota_energia']);
            $otros_ajustes = str_replace(',', '', $_POST['otros_ajustes']);
            $valor_favor = str_replace(',', '', $_POST['valor_favor']);
            $consumo = $_POST['consumo'];
            $no_usuarios = $_POST['no_usuarios'];
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE facturacion_comercializadores_2
                                          SET ID_COMERCIALIZADOR = '" . $id_comercializador . "', "
                                          . " ID_COD_DPTO = '" . $departamento . "', "
                                          . " ID_COD_MPIO = '" . $municipio . "', "
                                          . " FECHA_FACTURA = '" . $fecha_factura . "', "
                                          . " PERIODO_FACTURA = '" . $periodo_factura . "', "
                                          . " VALOR_FACTURA = '" . $valor_factura . "', "
                                          . " AJUSTE_FACT = '" . $ajuste_fact . "', "
                                          . " VALOR_RECAUDO = '" . $valor_recaudo . "', "
                                          . " AJUSTE_RECA = '" . $ajuste_reca . "', "
                                          . " VALOR_ENERGIA = '" . $valor_energia . "', "
                                          . " CUOTA_ENERGIA = '" . $cuota_energia . "', "
                                          . " OTROS_AJUSTES = '" . $otros_ajustes . "', "
                                          . " VALOR_FAVOR = '" . $valor_favor . "', "
                                          . " CONSUMO = '" . $consumo . "', "
                                          . " NO_USUARIOS = '" . $no_usuarios . "', "
                                          . " ESTADO_FACTURA = '" . $estado_factura . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "' "
                                    . " WHERE ID_FACTURACION = " . $_GET['editar']);
            echo "<script>";
                echo "document.location.href = '../Facturacion_Comercializadores.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM facturacion_comercializadores_2 WHERE ID_FACTURACION = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../Facturacion_Comercializadores.php'";
                echo "</script>";
            } else {
                $id_comercializador = $_POST['comercializador'];
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                $periodo_factura = $_POST['periodo_factura'];
                $valor_factura = $_POST['valor_factura'];
                $ajuste_fact = $_POST['ajuste_fact'];
                $valor_recaudo = $_POST['valor_recaudo'];
                $ajuste_reca = $_POST['ajuste_reca'];
                $valor_energia = $_POST['valor_energia'];
                $cuota_energia = $_POST['cuota_energia'];
                $otros_ajustes = $_POST['otros_ajustes'];
                $valor_favor = $_POST['valor_favor'];
                $consumo = $_POST['consumo'];
                $no_usuarios = $_POST['no_usuarios'];
                $fecha_factura = $_POST['fecha_factura'];
                $estado_factura = $_POST['estado_factura'];
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO facturacion_comercializadores_2 (ID_COMERCIALIZADOR, ID_COD_DPTO, ID_COD_MPIO, FECHA_FACTURA,
                                                                                PERIODO_FACTURA, VALOR_FACTURA, AJUSTE_FACT, VALOR_RECAUDO,
                                                                                AJUSTE_RECA, VALOR_ENERGIA, CUOTA_ENERGIA, OTROS_AJUSTES,
                                                                                VALOR_FAVOR, CONSUMO, NO_USUARIOS, ESTADO_FACTURA,
                                                                                FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                                        VALUES ('" . $id_comercializador .
                                                                                "', '" . $departamento . "', '" . $municipio . "', '" . $fecha_factura .
                                                                                "', '" . $periodo_factura . "', '" . $valor_factura . "', '" . $ajuste_fact . 
                                                                                "', '" . $valor_recaudo .  "', '" . $ajuste_reca .
                                                                                "', '" . $valor_energia . "', '" . $cuota_energia .
                                                                                "', '" . $otros_ajustes . "', '" . $valor_favor . "', '" . $consumo .
                                                                                "', '" . $no_usuarios . "', '" . $estado_factura .
                                                                                "', '" . $fecha_actualizacion .
                                                                                "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                $query_select_max_id_facturacion = mysqli_query($connection, "SELECT MAX(ID_FACTURACION) AS ID_FACTURACION "
                                                                           . "  FROM facturacion_comercializadores_2 ");
                $row_max_id_facturacion = mysqli_fetch_array($query_select_max_id_facturacion);
                echo $row_max_id_facturacion['ID_FACTURACION'];
            }
        }
    }
?>