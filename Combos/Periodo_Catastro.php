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
        function pasarPeriodoCatastro() {
            window.opener.infoPeriodoCatastro(document.getElementById("id_consulta").value,
                                              document.getElementById("id_ano_factura").value,
                                              document.getElementById("id_mes_factura").value,
                                              document.getElementById("mes_factura").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarPeriodoCatastro();">
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
                                                if(isset($_GET['id_periodo'])) {
                                                    $ano_factura = substr($_GET['id_periodo'], 0, 4);
                                                    $mes_factura = substr($_GET['id_periodo'], 4, 2);
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $query_select_periodo = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                                                                         . "  FROM archivos_cargados_facturacion_2 "
                                                                                                         . " WHERE ANO_FACTURA = " . $ano_factura . " "
                                                                                                         . "   AND ID_MES_FACTURA = " . $mes_factura . " "
                                                                                                         . " ORDER BY ANO_FACTURA DESC, ID_MES_FACTURA DESC");
                                                    while ($row_periodo = mysqli_fetch_assoc($query_select_periodo)) {
                                                        $id_ano_factura = $row_periodo['ANO_FACTURA'];
                                                        $id_mes_factura = $row_periodo['ID_MES_FACTURA'];
                                                        $mes_factura = strtolower($row_periodo['MES_FACTURA']);
                                                        ?>
                                                        <input type="hidden" name="id_ano_factura" id="id_ano_factura" readonly="readonly" value="<?php echo $id_ano_factura; ?>" />
                                                        <input type="hidden" name="id_mes_factura" id="id_mes_factura" readonly="readonly" value="<?php echo $id_mes_factura; ?>" />
                                                        <input type="hidden" name="mes_factura" id="mes_factura" readonly="readonly" value="<?php echo strtolower($mes_factura); ?>" />
                                                        <input type="hidden" name="id_consulta" id="id_consulta" readonly="readonly" value="<?php echo $id_consulta; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $query_select_all_periodo = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                                                                        . "  FROM archivos_cargados_facturacion_2 "
                                                                                                        . " ORDER BY ANO_FACTURA DESC, ID_MES_FACTURA DESC");
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
                                                                        if (strlen($row_all_periodo['ID_MES_FACTURA']) == 1) {
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Catastro.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . "0" . $row_all_periodo['ID_MES_FACTURA'] . "&id_consulta=" . $id_consulta . "'>" . $row_all_periodo['MES_FACTURA'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
                                                                        } else {
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Periodo_Catastro.php?id_periodo=" . $row_all_periodo['ANO_FACTURA'] . $row_all_periodo['ID_MES_FACTURA'] . "&id_consulta=" . $id_consulta . "'>" . $row_all_periodo['MES_FACTURA'] . " - " . $row_all_periodo['ANO_FACTURA'] . "</a></td>";
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