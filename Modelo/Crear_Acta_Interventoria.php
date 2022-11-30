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
            $fecha_acta = $_POST['fecha_acta'];
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $id_ano_acta = $_POST['id_ano_acta'];
            $id_mes_acta = $_POST['id_mes_acta'];
            $id_ano_liquidacion = $_POST['id_ano_liquidacion'];
            $id_mes_liquidacion = $_POST['id_mes_liquidacion'];
            $periodo_acta = $id_ano_acta . "" . $id_mes_acta;
            $periodo_liquidacion = $id_ano_liquidacion . "" . $id_mes_liquidacion;
            $valor_facturado = str_replace(',', '', $_POST['valor_facturado']);
            $valor_recaudo = str_replace(',', '', $_POST['valor_recaudo']);
            $costo_energia = str_replace(',', '', $_POST['costo_energia']);
            $otras_deducciones = str_replace(',', '', $_POST['otras_deducciones']);
            $traslado_neto = str_replace(',', '', $_POST['traslado_neto']);
            $observaciones = strtoupper($_POST['observaciones']);
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE actas_interventoria_2
                                          SET FECHA_ACTA = '" . $fecha_acta . "', "
                                          . " ID_COD_DPTO = '" . $departamento . "', "
                                          . " ID_COD_MPIO = '" . $municipio . "', "
                                          . " PERIODO_ACTA = '" . $periodo_acta . "', "
                                          . " VALOR_FACTURADO = '" . $valor_facturado . "', "
                                          . " VALOR_RECAUDO = '" . $valor_recaudo . "', "
                                          . " COSTO_ENERGIA = '" . $costo_energia . "', "
                                          . " OTRAS_DEDUCCIONES = '" . $otras_deducciones . "', "
                                          . " TRASLADO_NETO = '" . $traslado_neto . "', "
                                          . " OBSERVACIONES = '" . $observaciones . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "' "
                                    . " WHERE ID_ACTA_INTERVENTORIA = " . $_GET['editar']);
            echo "<script>";
                echo "document.location.href = '../Actas_Interventoria.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM actas_interventoria_2 WHERE ID_ACTA_INTERVENTORIA = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../Actas_Interventoria.php'";
                echo "</script>";
            } else {
                $fecha_acta = $_POST['fecha_acta'];
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                $periodo_acta = $_POST['periodo_acta'];
                $periodo_liquidacion = $_POST['periodo_liquidacion'];
                $valor_facturado = str_replace(',', '', $_POST['valor_facturado']);
                $valor_recaudo = str_replace(',', '', $_POST['valor_recaudo']);
                $costo_energia = str_replace(',', '', $_POST['costo_energia']);
                $otras_deducciones = str_replace(',', '', $_POST['otras_deducciones']);
                $traslado_neto = str_replace(',', '', $_POST['traslado_neto']);
                $observaciones = strtoupper($_POST['observaciones']);
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO actas_interventoria_2 (FECHA_ACTA, ID_COD_DPTO, ID_COD_MPIO, PERIODO_ACTA, PERIODO_LIQUIDACION,
                                                                              VALOR_FACTURADO, VALOR_RECAUDO, COSTO_ENERGIA, OTRAS_DEDUCCIONES, TRASLADO_NETO,
                                                                              OBSERVACIONES, FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                                      VALUES ('" . $fecha_acta . "', '" . $departamento . "', '" . $municipio .
                                                                              "', '" . $periodo_acta . "', '" . $periodo_liquidacion . "', '" . $valor_facturado .
                                                                              "', '" . $valor_recaudo . "', '" . $costo_energia .
                                                                              "', '" . $otras_deducciones .  "', '" . $traslado_neto .
                                                                              "', '" . $observaciones . "', '" . $fecha_actualizacion .
                                                                              "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                $query_select_max_id_acta_interventoria = mysqli_query($connection, "SELECT MAX(ID_ACTA_INTERVENTORIA) AS ID_ACTA_INTERVENTORIA "
                                                                                  . "  FROM actas_interventoria_2 ");
                $row_max_id_acta_interventoria = mysqli_fetch_array($query_select_max_id_acta_interventoria);
                echo $row_max_id_acta_interventoria['ID_ACTA_INTERVENTORIA'];
            }
        }
    }
?>