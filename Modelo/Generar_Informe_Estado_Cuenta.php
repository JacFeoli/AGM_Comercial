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
        $table = "";
        $info_estado_cuenta = array();
        $sw = 0;
        $count = 0;
        $departamento = $_POST['departamento'];
        $municipio = $_POST['municipio'];
        $deuda_desde = $_POST['deuda_desde'];
        $deuda_hasta = $_POST['deuda_hasta'];
        $periodo_estado_cuenta = $_POST['periodo_estado_cuenta'];
        $query_select_max_ano = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                        . "  FROM archivos_cargados_facturacion_2 "
                                                        . " WHERE ANO_FACTURA IN (" . $periodo_estado_cuenta . ") "
                                                        . "   AND ID_MES_FACTURA = 12 "
                                                        . " ORDER BY ANO_FACTURA ASC");
        $table = $table . "<a onClick='generarWordCobros()'><button style='border: 1px solid;'><img src='Images/word_2.png' title='Imprimir' width='16' height='16' /></button></a>";
        $table = $table . "<br />";
        $table = $table . "<br />";
        $table = $table . "<table class='table table-condensed table-hover'>";
            $table = $table . "<thead>";
                $table = $table . "<tr>";
                    $table = $table . "<th width=5%>#</th>";
                    $table = $table . "<th width=5%>PERIODO</th>";
                    $table = $table . "<th width=10%>NIC</th>";
                    $table = $table . "<th width=50%>NOMBRE</th>";
                    $table = $table . "<th width=10%>DEUDA COR.</th>";
                    $table = $table . "<th width=10%>CART. FAL.</th>";
                    $table = $table . "<th width=4%>EXP.</th>";
                    $table = $table . "<th width=5%>SELEC.</th>";
                $table = $table . "</tr>";
            $table = $table . "</thead>";
            $table = $table . "<tbody>";
                while ($row_max_ano = mysqli_fetch_assoc($query_select_max_ano)) {
                    if ($sw == 0) {
                        $bd_tabla_catastro = "catastro_" . strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
                        $bd_tabla_cartera = "cartera_fallida_" . $row_max_ano['ANO_FACTURA'] . "_2";
                        $sw = 1;
                    }
                    mysqli_query($connection, "SET NAMES 'utf8'");
                    $query_select_nics_catastro = mysqli_query($connection, "SELECT * "
                                                                          . "  FROM $bd_tabla_catastro "
                                                                          . " WHERE ID_COD_DPTO = '$departamento' "
                                                                          . "   AND ID_COD_MPIO = '$municipio' "
                                                                          . "   AND DEUDA_CORRIENTE BETWEEN " . $deuda_desde . " AND " . $deuda_hasta . " "
                                                                          . " ORDER BY NIC ASC");
                    while ($row_nics_catastro = mysqli_fetch_assoc($query_select_nics_catastro)) {
                        $count = $count + 1;
                        $table = $table . "<tr>";
                            $table = $table . "<td style='vertical-align:middle;'>$count</td>";
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_nics_catastro['ANO_CATASTRO'] . "</td>";
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_nics_catastro['NIC'] . "</td>";
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_nics_catastro['NOMBRE_PROPIETARIO'] . "</td>";
                            $table = $table . "<td style='vertical-align:middle;'><b style='font-size: 12px;'>$ </b>" . number_format($row_nics_catastro['DEUDA_CORRIENTE'], 0, ',', '.') . "</td>";
                            $query_select_cartera_fallida = mysqli_query($connection, "SELECT SUM(IMPORTE_TOTAL) AS IMPORTE_TOTAL "
                                                                                    . "  FROM $bd_tabla_cartera "
                                                                                    . " WHERE NIC = " . $row_nics_catastro['NIC'] . " "
                                                                                    . "   AND ANO_CARTERA_FALLIDA = '" . $row_nics_catastro['ANO_CATASTRO'] . "'");
                            $row_cartera_fallida = mysqli_fetch_array($query_select_cartera_fallida);
                            $table = $table . "<td style='vertical-align:middle;'><b style='font-size: 12px;'>$ </b>" . number_format($row_cartera_fallida['IMPORTE_TOTAL'], 0, ',', '.') . "</td>";
                            $table = $table . "<td style='vertical-align:middle;'><button class='btn_historial' type='button' data-toggle='modal' id='" . $row_nics_catastro['NIC'] . $row_nics_catastro['ANO_CATASTRO'] . "' data-target='#modalHistorialNIC' data-tooltip='tooltip' title='HISTORIAL CARTERA'><img id='historial_factura' src='Images/search_history.png' width='16' height='16' /></button></td>";
                            $table = $table . "<td style='vertical-align:middle;'><input class='form-check-input position-static' type='checkbox' name='nic' value='" . $row_nics_catastro['NIC'] . $row_nics_catastro['ANO_CATASTRO'] . "'></td>";
                        $table = $table . "</tr>";
                    }
                    $sw = 0;
                }
            $table = $table . "</tbody>";
        $table = $table . "</table>";
        $info_estado_cuenta[0] = $table;
        echo json_encode($info_estado_cuenta);
        exit();
    }
?>