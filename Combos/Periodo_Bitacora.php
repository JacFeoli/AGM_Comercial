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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <script src="../Javascript/bootstrap.min.js"></script>
    </head>
    <script>
        function pasarPeriodoBitacora() {
            window.opener.infoPeriodoBitacora(document.getElementById("id_consulta").value,
                                              document.getElementById("id_ano").value,
                                              document.getElementById("id_mes").value,
                                              document.getElementById("periodo").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarPeriodoBitacora();">
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
                                                $sw = 0;
                                                $or = "";
                                                $ano = "";
                                                $mes = "";
                                                $where = "";
                                                $id_ano = "";
                                                $id_mes = "";
                                                $periodo = "";
                                                if (isset($_GET['periodos'])) {
                                                    $myPeriodos = explode(',', $_GET['periodos']);
                                                    foreach ($myPeriodos as $periodos) {
                                                        if ($sw == 0) {
                                                            $where = " WHERE (ANO_FACTURA = " . substr($periodos, 0, 4) . " AND MES_FACTURA = " . substr($periodos, 4, 2) . ") ";
                                                            $sw = 1;
                                                        } else {
                                                            $or = $or . " OR (ANO_FACTURA = " . substr($periodos, 0, 4) . " AND MES_FACTURA = " . substr($periodos, 4, 2) . ") ";
                                                        }
                                                    }
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $query_select_periodo = mysqli_query($connection, "SELECT * "
                                                                                                    . "  FROM periodos_facturacion_especiales_2 "
                                                                                                    . $where
                                                                                                    . substr($or, 0, -39)
                                                                                                    . " ORDER BY ANO_FACTURA DESC, MES_FACTURA DESC");
                                                    while ($row_periodo = mysqli_fetch_assoc($query_select_periodo)) {
                                                        $id_ano = $id_ano . $row_periodo['ANO_FACTURA'] . ", ";
                                                        $id_mes = $id_mes . $row_periodo['MES_FACTURA'] . ", ";
                                                        $periodo = $periodo . strtolower($row_periodo['PERIODO']) . ", ";
                                                        ?>
                                                    <?php
                                                    }
                                                    ?>
                                                        <input type="hidden" name="id_ano" id="id_ano" readonly="readonly" value="<?php echo substr($id_ano, 0, -2); ?>" />
                                                        <input type="hidden" name="id_mes" id="id_mes" readonly="readonly" value="<?php echo substr($id_mes, 0, -2); ?>" />
                                                        <input type="hidden" name="periodo" id="periodo" readonly="readonly" value="<?php echo substr(strtolower($periodo), 0, -2); ?>" />
                                                        <input type="hidden" name="id_consulta" id="id_consulta" readonly="readonly" value="<?php echo $id_consulta; ?>" />
                                                    <?php
                                                } else {
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $query_select_all_periodo = mysqli_query($connection, "SELECT * "
                                                                                                        . "  FROM periodos_facturacion_especiales_2 "
                                                                                                        . " ORDER BY ANO_FACTURA DESC, MES_FACTURA DESC");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>PERIODO</th>";
                                                                    echo "<th>SELECCIONAR</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_periodo = mysqli_fetch_assoc($query_select_all_periodo)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        if (strlen($row_all_periodo['MES_FACTURA']) == 1) {
                                                                            //echo "<td style='vertical-align: middle;'><a href='../Combos/Periodo_Bitacora.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . "0" . $row_all_periodo['MES_FACTURA'] . "&id_consulta=" . $id_consulta . "'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
                                                                            echo "<td style='vertical-align: middle;'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</td>";
                                                                            echo "<td style='vertical-align: middle;'><input class='form-check-input position-static' type='checkbox' name='periodo' value='" . $row_all_periodo['ANO_FACTURA'] . "0" . $row_all_periodo['MES_FACTURA'] . "'><input type='hidden' id='id_consulta' name='id_consulta' value='" . $id_consulta . "' /></td>";
                                                                        } else {
                                                                            //echo "<td style='vertical-align: middle;'><a href='../Combos/Periodo_Bitacora.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . $row_all_periodo['MES_FACTURA'] . "&id_consulta=" . $id_consulta . "'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
                                                                            echo "<td style='vertical-align: middle;'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</td>";
                                                                            echo "<td style='vertical-align: middle;'><input class='form-check-input position-static' type='checkbox' name='periodo' value='" . $row_all_periodo['ANO_FACTURA'] . $row_all_periodo['MES_FACTURA'] . "'><input type='hidden' id='id_consulta' name='id_consulta' value='" . $id_consulta . "' /></td>";
                                                                        }
                                                                    echo "</tr>";
                                                                }
                                                            echo "</tbody>";
                                                        echo "</table>";
                                                    echo "</div>";
                                                    echo "<div class='row'>";
                                                        echo "<div class='col-xs-12 text-center'>";
                                                            echo "<button class='btn btn-primary btn-sm font background cursor' id='btn_enviar' type='button'><i style='font-size: 14px;' class='fas fa-search'></i>&nbsp;&nbsp;Enviar</button>";
                                                        echo "</div>";
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
    <script>
        $(document).ready(function() {
            $("#btn_enviar").click(function() {
                var periodos = "";
                var id_consulta = $("#id_consulta").val();
                var checkBoxes = document.getElementsByName("periodo");
                for (var i=0;i<checkBoxes.length;i++) {
                    if (checkBoxes[i].checked == true) {
                        periodos = periodos + checkBoxes[i].value + ",";
                    }
                }
                document.location.href = '../Combos/Periodo_Bitacora.php?periodos='+periodos+'&id_consulta='+id_consulta;
            });
        });
    </script>
</html>