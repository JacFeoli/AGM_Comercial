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
        <title>AGM - Seleccionar Periodo Comercializador</title>
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
        function pasarInfoReca() {
            window.opener.infoPeriodoReca(document.getElementById("id_facturacion").value,
                                          document.getElementById("periodo_factura").value,
                                          document.getElementById("valor_factura").value,
                                          document.getElementById("valor_recaudo_fact").value,
                                          document.getElementById("valor_energia").value,
                                          document.getElementById("valor_favor").value,
                                          document.getElementById("ajuste_fact").value,
                                          document.getElementById("ajuste_reca").value,
                                          document.getElementById("cuota_energia").value,
                                          document.getElementById("consumo").value,
                                          document.getElementById("otros_ajustes").value,
                                          document.getElementById("no_usuarios").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarInfoReca();">
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
                                                if(isset($_GET['id_facturacion']) && isset($_GET['id_comercializador']) && isset($_GET['id_departamento']) && isset($_GET['id_municipio'])) {
                                                    $query_select_info_facturacion = mysqli_query($connection, "SELECT * "
                                                                                                             . "  FROM facturacion_comercializadores_2 "
                                                                                                             . " WHERE ID_FACTURACION = '" . $_GET['id_facturacion'] . "' ");
                                                    while ($row_info_facturacion = mysqli_fetch_assoc($query_select_info_facturacion)) {
                                                        $id_facturacion = $row_info_facturacion['ID_FACTURACION'];
                                                        $periodo_factura = $row_info_facturacion['PERIODO_FACTURA'];
                                                        $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                . " WHERE ANO_FACTURA = " . substr($periodo_factura, 0, 4) . " "
                                                                                                                . "   AND MES_FACTURA = " . substr($periodo_factura, 4, 2));
                                                        $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                        $periodo_factura = $row_periodo_factura['PERIODO'] . " - " . substr($periodo_factura, 0, 4);
                                                        $valor_factura = $row_info_facturacion['VALOR_FACTURA'];
                                                        $valor_recaudo_fact = $row_info_facturacion['VALOR_RECAUDO'];
                                                        $valor_energia = $row_info_facturacion['VALOR_ENERGIA'];
                                                        $valor_favor = $row_info_facturacion['VALOR_FAVOR'];
                                                        $ajuste_fact = $row_info_facturacion['AJUSTE_FACT'];
                                                        $cuota_energia = $row_info_facturacion['CUOTA_ENERGIA'];
                                                        $otros_ajustes = $row_info_facturacion['OTROS_AJUSTES'];
                                                        $no_usuarios = $row_info_facturacion['NO_USUARIOS'];
                                                        $ajuste_reca = $row_info_facturacion['AJUSTE_RECA'];
                                                        $consumo = $row_info_facturacion['CONSUMO'];
                                                        ?>
                                                        <input type="hidden" name="id_facturacion" id="id_facturacion" readonly="readonly" value="<?php echo $id_facturacion; ?>" />
                                                        <input type="hidden" name="periodo_factura" id="periodo_factura" readonly="readonly" value="<?php echo $periodo_factura; ?>" />
                                                        <input type="hidden" name="valor_factura" id="valor_factura" readonly="readonly" value="<?php echo $valor_factura; ?>" />
                                                        <input type="hidden" name="valor_recaudo_fact" id="valor_recaudo_fact" readonly="readonly" value="<?php echo $valor_recaudo_fact; ?>" />
                                                        <input type="hidden" name="valor_energia" id="valor_energia" readonly="readonly" value="<?php echo $valor_energia; ?>" />
                                                        <input type="hidden" name="valor_favor" id="valor_favor" readonly="readonly" value="<?php echo $valor_favor; ?>" />
                                                        <input type="hidden" name="ajuste_fact" id="ajuste_fact" readonly="readonly" value="<?php echo $ajuste_fact; ?>" />
                                                        <input type="hidden" name="ajuste_reca" id="ajuste_reca" readonly="readonly" value="<?php echo $ajuste_reca; ?>" />
                                                        <input type="hidden" name="cuota_energia" id="cuota_energia" readonly="readonly" value="<?php echo $cuota_energia; ?>" />
                                                        <input type="hidden" name="consumo" id="consumo" readonly="readonly" value="<?php echo $consumo ?>" />
                                                        <input type="hidden" name="otros_ajustes" id="otros_ajustes" readonly="readonly" value="<?php echo $otros_ajustes; ?>" />
                                                        <input type="hidden" name="no_usuarios" id="no_usuarios" readonly="readonly" value="<?php echo $no_usuarios; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $id_comercializador = $_GET['id_comercializador'];
                                                    $id_departamento = $_GET['id_departamento'];
                                                    $id_municipio = $_GET['id_municipio'];
                                                    $query_select_all_info_facturacion = mysqli_query($connection, "SELECT * "
                                                                                                                 . "  FROM facturacion_comercializadores_2 "
                                                                                                                 . " WHERE ID_COMERCIALIZADOR = '" . $id_comercializador . "' "
                                                                                                                 . "   AND ID_COD_DPTO = '" . $id_departamento . "' "
                                                                                                                 . "   AND ID_COD_MPIO = '" . $id_municipio . "' "
                                                                                                                 . " ORDER BY ID_FACTURACION DESC");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>PERIODO</th>";
                                                                    echo "<th>VALOR FACTURA</th>";
                                                                    echo "<th>ESTADO FACT.</th>";
                                                                    echo "<th>ESTADO RECA.</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_info_facturacion = mysqli_fetch_assoc($query_select_all_info_facturacion)) {
                                                                    $estado = "";
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 "
                                                                                                                             . " WHERE ID_FACTURACION = '" . $row_all_info_facturacion['ID_FACTURACION'] . "'");
                                                                        $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                                                        if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                                                            if ($row_info_recaudo['ESTADO_RECAUDO'] == 1) {
                                                                                echo "<td style='vertical-align:middle;'>" . $row_all_info_facturacion['PERIODO_FACTURA'] . "</td>";
                                                                                $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                                                            } else {
                                                                                echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Reca_Comer.php?id_facturacion=" . $row_all_info_facturacion['ID_FACTURACION'] . "&id_comercializador=" . $id_comercializador . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_info_facturacion['PERIODO_FACTURA'] . "</a></td>";
                                                                            }
                                                                        } else {
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Reca_Comer.php?id_facturacion=" . $row_all_info_facturacion['ID_FACTURACION'] . "&id_comercializador=" . $id_comercializador . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_info_facturacion['PERIODO_FACTURA'] . "</a></td>";
                                                                        }
                                                                        switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                                                            case "1":
                                                                                $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                                                                break;
                                                                            case "2":
                                                                                $estado = "<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span>";
                                                                                break;
                                                                        }
                                                                        echo "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_all_info_facturacion['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                                                        switch ($row_all_info_facturacion['ESTADO_FACTURA']) {
                                                                            case "1":
                                                                                echo "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span></td>";
                                                                                break;
                                                                            case "2":
                                                                                echo "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span></td>";
                                                                                break;
                                                                        }
                                                                        echo "<td style='vertical-align:middle;'>$estado</td>";
                                                                    echo "</tr>";
                                                                }
                                                            echo "</tbody>";
                                                        echo "</table>";
                                                    echo "</div>";
                                                    echo "<p></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE DE PAGO.</span></p>";
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