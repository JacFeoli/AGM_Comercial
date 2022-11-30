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
        <title>AGM - Seleccionar Tipo Visita</title>
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
        function pasarTipoVisita() {
            window.opener.infoTipoVisita(document.getElementById("id_tipo_visita").value,
                                           document.getElementById("tipo_visita").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarTipoVisita();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Tipo Visita</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_tipo_visita'])) {
                                                    $query_select_tipo_visita = mysqli_query($connection, "SELECT * FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = '" . $_GET['id_tipo_visita'] . "'");
                                                    while ($row_tipo_visita = mysqli_fetch_assoc($query_select_tipo_visita)) {
                                                        $id_tipo_visita = $row_tipo_visita['ID_TIPO_VISITA'];
                                                        $tipo_visita = $row_tipo_visita['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_tipo_visita" id="id_tipo_visita" readonly="readonly" value="<?php echo $id_tipo_visita; ?>" />
                                                        <input type="hidden" name="tipo_visita" id="tipo_visita" readonly="readonly" value="<?php echo $tipo_visita; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_tipo_visita = mysqli_query($connection, "SELECT * FROM tipo_visitas_2 ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>TIPO VISITA</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_tipo_visita = mysqli_fetch_assoc($query_select_all_tipo_visita)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Tipo_Visita.php?id_tipo_visita=" . $row_all_tipo_visita['ID_TIPO_VISITA'] . "'>" . $row_all_tipo_visita['NOMBRE'] . "</a></td>";
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