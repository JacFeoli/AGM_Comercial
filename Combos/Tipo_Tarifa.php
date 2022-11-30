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
        <title>AGM - Seleccionar Tarifa</title>
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
        function pasarTipoTarifa() {
            window.opener.infoTipoTarifa(document.getElementById("id_tarifa").value,
                                         document.getElementById("tarifa").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarTipoTarifa();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione la Tarifa</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_tarifa'])) {
                                                    $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE ID_TARIFA = '" . $_GET['id_tarifa'] . "'");
                                                    while ($row_tarifa = mysqli_fetch_assoc($query_select_tarifa)) {
                                                        $id_tarifa = $row_tarifa['ID_TARIFA'];
                                                        $tarifa = $row_tarifa['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_tarifa" id="id_tarifa" readonly="readonly" value="<?php echo $id_tarifa; ?>" />
                                                        <input type="hidden" name="tarifa" id="tarifa" readonly="readonly" value="<?php echo $tarifa; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>TARIFA</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_tarifa = mysqli_fetch_assoc($query_select_all_tarifa)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Tipo_Tarifa.php?id_tarifa=" . $row_all_tarifa['ID_TARIFA'] . "'>" . $row_all_tarifa['NOMBRE'] . "</a></td>";
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