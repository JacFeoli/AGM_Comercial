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
        function pasarPeriodoActas() {
            window.opener.infoperiodoActas(document.getElementById("id_ano_acta").value,
                                           document.getElementById("mes_acta").value,
                                           document.getElementById("periodo_acta").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarPeriodoActas();">
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
                                                    $id_ano_acta = $row_periodo['ANO_FACTURA'];
                                                    $mes_acta = $row_periodo['MES_FACTURA'];
                                                    $periodo_acta = strtolower($row_periodo['PERIODO']);
                                                    ?>
                                                        <input type="hidden" name="id_ano_acta" id="id_ano_acta" readonly="readonly" value="<?php echo $id_ano_acta; ?>" />
                                                        <input type="hidden" name="mes_acta" id="mes_acta" readonly="readonly" value="<?php echo $mes_acta; ?>" />
                                                        <input type="hidden" name="periodo_acta" id="periodo_acta" readonly="readonly" value="<?php echo strtolower($periodo_acta); ?>" />
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
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Actas.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . "0" . $row_all_periodo['MES_FACTURA'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
                                                                        } else {
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Actas.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . $row_all_periodo['MES_FACTURA'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_periodo['PERIODO'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
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