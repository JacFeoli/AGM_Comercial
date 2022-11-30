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
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Seleccionar Periodo</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="../Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="../Css/AGM_Style.css" />
        <link rel="stylesheet" href="../Css/bootstrap.min.css" />
        <link rel="stylesheet" href="../Css/font-awesome.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../Javascript/bootstrap.min.js"></script>
    </head>
    <script>
        function pasarPeriodoLiquidacionesActas() {
            window.opener.infoperiodoLiquidacionesActas(document.getElementById("id_ano_liquidacion").value,
                                                        document.getElementById("mes_liquidacion").value,
                                                        document.getElementById("periodo_liquidacion").value,
                                                        document.getElementById("valor_facturado").value,
                                                        document.getElementById("valor_recaudo").value,
                                                        document.getElementById("costo_energia").value,
                                                        document.getElementById("valor_favor").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarPeriodoLiquidacionesActas();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Periodo</h1>
                                            <h2></h2>
                                            <?php
                                                if (isset($_GET['id_periodo']) && isset($_GET['id_departamento']) && isset($_GET['id_municipio'])) {
                                                    $query_select_periodo = mysqli_query($connection, "SELECT * "
                                                                                                         . "  FROM periodos_facturacion_especiales_2 "
                                                                                                         . " WHERE ANO_FACTURA = " . substr($_GET['id_periodo'], 0, 4) . " "
                                                                                                         . "   AND MES_FACTURA = " . substr($_GET['id_periodo'], 4, 2) . " "
                                                                                                         . " ORDER BY ANO_FACTURA DESC, MES_FACTURA DESC");
                                                    $row_periodo = mysqli_fetch_array($query_select_periodo);
                                                    $id_ano_liquidacion = $row_periodo['ANO_FACTURA'];
                                                    $mes_liquidacion = $row_periodo['MES_FACTURA'];
                                                    $periodo_liquidacion = strtolower($row_periodo['PERIODO']);
                                                    $query_select_liquidacion_oper = mysqli_query($connection, "SELECT SUM(FO.VALOR_FACTURA), SUM(FO.VALOR_RECAUDO), SUM(FO.VALOR_ENERGIA), SUM(FO.VALOR_FAVOR) "
                                                                                                            . "  FROM facturacion_operadores_2 FO "
                                                                                                            . " WHERE FO.ID_COD_DPTO = " . $_GET['id_departamento'] . " "
                                                                                                            . "   AND FO.ID_COD_MPIO = " . $_GET['id_municipio'] . " "
                                                                                                            . "   AND FO.PERIODO_FACTURA = " . $_GET['id_periodo'] . " ");
                                                    $row_liquidacion_oper = mysqli_fetch_array($query_select_liquidacion_oper);
                                                    $valor_facturado = $row_liquidacion_oper['SUM(FO.VALOR_FACTURA)'];
                                                    $valor_recaudo = $row_liquidacion_oper['SUM(FO.VALOR_RECAUDO)'];
                                                    $costo_energia = $row_liquidacion_oper['SUM(FO.VALOR_ENERGIA)'];
                                                    $valor_favor = $row_liquidacion_oper['SUM(FO.VALOR_FAVOR)'];
                                                    ?>
                                                        <input type="hidden" name="id_ano_liquidacion" id="id_ano_liquidacion" readonly="readonly" value="<?php echo $id_ano_liquidacion; ?>" />
                                                        <input type="hidden" name="mes_liquidacion" id="mes_liquidacion" readonly="readonly" value="<?php echo $mes_liquidacion; ?>" />
                                                        <input type="hidden" name="periodo_liquidacion" id="periodo_liquidacion" readonly="readonly" value="<?php echo strtolower($periodo_liquidacion); ?>" />
                                                        <input type="hidden" name="valor_facturado" id="valor_facturado" readonly="readonly" value="<?php echo $valor_facturado; ?>" />
                                                        <input type="hidden" name="valor_recaudo" id="valor_recaudo" readonly="readonly" value="<?php echo $valor_recaudo; ?>" />
                                                        <input type="hidden" name="costo_energia" id="costo_energia" readonly="readonly" value="<?php echo $costo_energia; ?>" />
                                                        <input type="hidden" name="valor_favor" id="valor_favor" readonly="readonly" value="<?php echo $valor_favor; ?>" />
                                                    <?php
                                                } else {
                                                    $id_departamento = $_GET['id_departamento'];
                                                    $id_municipio = $_GET['id_municipio'];
                                                    $query_select_all_periodo = mysqli_query($connection, "SELECT * "
                                                                                                        . "  FROM periodos_facturacion_especiales_2 "
                                                                                                        . " ORDER BY ANO_FACTURA DESC, MES_FACTURA DESC");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>PERIODO</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_periodo = mysqli_fetch_assoc($query_select_all_periodo)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        if (strlen($row_all_periodo['MES_FACTURA']) == 1) {
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Liquidaciones_Actas.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . "0" . $row_all_periodo['MES_FACTURA'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
                                                                        } else {
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Liquidaciones_Actas.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . $row_all_periodo['MES_FACTURA'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
                                                                        }
                                                                    echo "</tr>";
                                                                }
                                                            echo "</tbody>";
                                                        echo "</table>";
                                                    echo "</div>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>