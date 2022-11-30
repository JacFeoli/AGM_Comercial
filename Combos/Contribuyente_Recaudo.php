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
        <title>AGM - Seleccionar Contribuyente</title>
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
        function pasarContribuyenteReca() {
            window.opener.infoContribuyenteReca(document.getElementById("id_contribuyente").value,
                                                document.getElementById("contribuyente").value,
                                                document.getElementById("nit_contribuyente").value,
                                                document.getElementById("departamento").value,
                                                document.getElementById("municipio").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarContribuyenteReca();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Contribuyente</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_contribuyente'])) {
                                                    $query_select_contribuyente = mysqli_query($connection, "SELECT * "
                                                                                                          . "  FROM contribuyentes_2 C, facturacion_especiales_2 FE "
                                                                                                          . " WHERE C.ID_CONTRIBUYENTE = FE.ID_CONTRIBUYENTE "
                                                                                                          . "   AND C.ID_CONTRIBUYENTE = '" . $_GET['id_contribuyente'] . "' ");
                                                    while ($row_contribuyente = mysqli_fetch_assoc($query_select_contribuyente)) {
                                                        $id_contribuyente = $row_contribuyente['ID_CONTRIBUYENTE'];
                                                        $contribuyente = $row_contribuyente['NOMBRE'];
                                                        $nit_contribuyente = $row_contribuyente['NIT_CONTRIBUYENTE'];
                                                        $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = '" . $row_contribuyente['ID_DEPARTAMENTO'] . "'");
                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                        $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = '" . $row_contribuyente['ID_DEPARTAMENTO'] . "' AND ID_MUNICIPIO = '" . $row_contribuyente['ID_MUNICIPIO'] . "'");
                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                        ?>
                                                        <input type="hidden" name="id_contribuyente" id="id_contribuyente" readonly="readonly" value="<?php echo $id_contribuyente; ?>" />
                                                        <input type="hidden" name="contribuyente" id="contribuyente" readonly="readonly" value="<?php echo $contribuyente; ?>" />
                                                        <input type="hidden" name="nit_contribuyente" id="nit_contribuyente" readonly="readonly" value="<?php echo $nit_contribuyente; ?>" />
                                                        <input type="hidden" name="departamento" id="departamento" readonly="readonly" value="<?php echo $row_departamento['NOMBRE']; ?>" />
                                                        <input type="hidden" name="municipio" id="municipio" readonly="readonly" value="<?php echo $row_municipio['NOMBRE']; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_contribuyente = mysqli_query($connection, "SELECT DISTINCT(C.ID_CONTRIBUYENTE), C.NOMBRE "
                                                                                                              . "  FROM contribuyentes_2 C, facturacion_especiales_2 FE "
                                                                                                              . " WHERE C.ID_CONTRIBUYENTE = FE.ID_CONTRIBUYENTE "
                                                                                                              . " ORDER BY C.NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>CONTRIBUYENTE</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_contribuyente = mysqli_fetch_assoc($query_select_all_contribuyente)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Contribuyente_Recaudo.php?id_contribuyente=" . $row_all_contribuyente['ID_CONTRIBUYENTE'] . "'>" . $row_all_contribuyente['NOMBRE'] . "</a></td>";
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