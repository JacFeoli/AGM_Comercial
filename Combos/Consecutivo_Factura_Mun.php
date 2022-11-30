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
        <title>AGM - Seleccionar Consecutivo Factura</title>
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
        function pasarInfoFact() {
            window.opener.infoFacturacion(document.getElementById("id_facturacion").value,
                                          document.getElementById("consecutivo_factura").value,
                                          document.getElementById("periodo_factura").value,
                                          document.getElementById("valor_factura").value,
                                          document.getElementById("valor_cartera").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarInfoFact();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Consecutivo Factura</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_facturacion']) && isset($_GET['id_departamento']) && isset($_GET['id_municipio'])) {
                                                    $query_select_info_facturacion = mysqli_query($connection, "SELECT * "
                                                                                                             . "  FROM facturacion_municipales_2 "
                                                                                                             . " WHERE ID_COD_DPTO = '" . $_GET['id_departamento'] . "' "
                                                                                                             . "   AND ID_COD_MPIO = '" . $_GET['id_municipio'] . "' "
                                                                                                             . "   AND ID_FACTURACION = '" . $_GET['id_facturacion'] . "' ");
                                                    while ($row_info_facturacion = mysqli_fetch_assoc($query_select_info_facturacion)) {
                                                        $id_facturacion = $row_info_facturacion['ID_FACTURACION'];
                                                        $consecutivo_factura = $row_info_facturacion['CONSECUTIVO_FACT'];
                                                        $periodo_factura = $row_info_facturacion['PERIODO_FACTURA'];
                                                        $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                . "  FROM periodos_facturacion_municipales_2 "
                                                                                                                . " WHERE ANO_FACTURA = " . substr($periodo_factura, 0, 4) . " "
                                                                                                                . "   AND MES_FACTURA = " . substr($periodo_factura, 4, 2));
                                                        $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                        $periodo_factura = $row_periodo_factura['PERIODO'] . " - " . substr($periodo_factura, 0, 4);
                                                        $valor_factura = $row_info_facturacion['VALOR_FACTURA'];
                                                        $query_select_valor_cartera = mysqli_query($connection, "SELECT VALOR_CARTERA "
                                                                                                              . "  FROM alcaldias_2 "
                                                                                                              . " WHERE ID_COD_DPTO = " . $_GET['id_departamento'] . " "
                                                                                                              . "   AND ID_COD_MPIO = " . $_GET['id_municipio'] . " ");
                                                        $row_valor_cartera = mysqli_fetch_array($query_select_valor_cartera);
                                                        ?>
                                                        <input type="hidden" name="id_facturacion" id="id_facturacion" readonly="readonly" value="<?php echo $id_facturacion; ?>" />
                                                        <input type="hidden" name="consecutivo_factura" id="consecutivo_factura" readonly="readonly" value="<?php echo $consecutivo_factura; ?>" />
                                                        <input type="hidden" name="periodo_factura" id="periodo_factura" readonly="readonly" value="<?php echo $periodo_factura; ?>" />
                                                        <input type="hidden" name="valor_factura" id="valor_factura" readonly="readonly" value="<?php echo $valor_factura; ?>" />
                                                        <input type="hidden" name="valor_cartera" id="valor_cartera" readonly="readonly" value="<?php echo $row_valor_cartera['VALOR_CARTERA']; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $id_departamento = $_GET['id_departamento'];
                                                    $id_municipio = $_GET['id_municipio'];
                                                    $query_select_all_info_facturacion = mysqli_query($connection, "SELECT * "
                                                                                                                 . "  FROM facturacion_municipales_2 "
                                                                                                                 . " WHERE ID_COD_DPTO = '" . $id_departamento . "' "
                                                                                                                 . "   AND ID_COD_MPIO = '$id_municipio' "
                                                                                                                 . " ORDER BY ID_FACTURACION DESC");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>CONSECUTIVO FACTURA</th>";
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
                                                                        $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                                                             . " WHERE ID_FACTURACION = '" . $row_all_info_facturacion['ID_FACTURACION'] . "'");
                                                                        $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                                                        if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                                                            if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                                                                echo "<td style='vertical-align:middle;'>" . $row_all_info_facturacion['CONSECUTIVO_FACT'] . "</td>";
                                                                                $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                                                            } else {
                                                                                if ($row_info_recaudo['ESTADO_RECAUDO'] == 6) {
                                                                                    echo "<td style='vertical-align:middle;'>" . $row_all_info_facturacion['CONSECUTIVO_FACT'] . "</td>";
                                                                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>PA</b></span>";
                                                                                } else {
                                                                                    echo "<td style='vertical-align:middle;'><a href='../Combos/Consecutivo_Factura_Mun.php?id_facturacion=" . $row_all_info_facturacion['ID_FACTURACION'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_info_facturacion['CONSECUTIVO_FACT'] . "</a></td>";
                                                                                }
                                                                                
                                                                            }
                                                                        } else {
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Consecutivo_Factura_Mun.php?id_facturacion=" . $row_all_info_facturacion['ID_FACTURACION'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_info_facturacion['CONSECUTIVO_FACT'] . "</a></td>";
                                                                        }
                                                                        switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                                                            case "1":
                                                                                $estado = "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span>";
                                                                                break;
                                                                            case "2":
                                                                                $estado = "<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span>";
                                                                                break;
                                                                            case "3":
                                                                                $estado = "<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span>";
                                                                                break;
                                                                            case "4":
                                                                                $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                                                                break;
                                                                            case "5":
                                                                                $estado = "<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span>";
                                                                                break;
                                                                            case "6":
                                                                                $estado = "<span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span>";
                                                                                break;
                                                                        }
                                                                        echo "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_all_info_facturacion['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                                                        switch ($row_all_info_facturacion['ESTADO_FACTURA']) {
                                                                            case "1":
                                                                                echo "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                                                                                break;
                                                                            case "2":
                                                                                echo "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span></td>";
                                                                                break;
                                                                            case "3":
                                                                                echo "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span></td>";
                                                                                break;
                                                                            case "6":
                                                                                echo "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span></td>";
                                                                                break;
                                                                        }
                                                                        echo "<td style='vertical-align:middle;'>$estado</td>";
                                                                    echo "</tr>";
                                                                }
                                                            echo "</tbody>";
                                                        echo "</table>";
                                                    echo "</div>";
                                                    echo "<p></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADA.</span></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO POR ACUERDO.</span></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGO PARCIAL.</span></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span></p>";
                                                    echo "<p style='margin-bottom: 0px;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span></p>";
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