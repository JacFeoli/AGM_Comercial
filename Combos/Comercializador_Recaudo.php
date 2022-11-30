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
        <title>AGM - Seleccionar Comercializador</title>
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
        function pasarComercializadorReca() {
            window.opener.infoComercializadorReca(document.getElementById("id_comercializador").value,
                                                  document.getElementById("comercializador").value,
                                                  document.getElementById("nit_comercializador").value,
                                                  document.getElementById("departamento").value,
                                                  document.getElementById("municipio").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarComercializadorReca();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Comercializador</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_comercializador'])) {
                                                    $query_select_comercializador = mysqli_query($connection, "SELECT * "
                                                                                                            . "  FROM comercializadores_2 C, facturacion_comercializadores_2 FC "
                                                                                                            . " WHERE C.ID_COMERCIALIZADOR = FC.ID_COMERCIALIZADOR "
                                                                                                            . "   AND C.ID_COMERCIALIZADOR = '" . $_GET['id_comercializador'] . "' ");
                                                    while ($row_comercializador = mysqli_fetch_assoc($query_select_comercializador)) {
                                                        $id_comercializador = $row_comercializador['ID_COMERCIALIZADOR'];
                                                        $comercializador = $row_comercializador['NOMBRE'];
                                                        $nit_comercializador = $row_comercializador['NIT_COMERCIALIZADOR'];
                                                        $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE FROM departamentos_comercializadores_2 WHERE ID_DEPARTAMENTO = '" . $row_comercializador['ID_COD_DPTO'] . "'");
                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                        $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_comercializadores_2 WHERE ID_DEPARTAMENTO = '" . $row_comercializador['ID_COD_DPTO'] . "' AND ID_MUNICIPIO = '" . $row_comercializador['ID_COD_MPIO'] . "'");
                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                        ?>
                                                        <input type="hidden" name="id_comercializador" id="id_comercializador" readonly="readonly" value="<?php echo $id_comercializador; ?>" />
                                                        <input type="hidden" name="comercializador" id="comercializador" readonly="readonly" value="<?php echo $comercializador; ?>" />
                                                        <input type="hidden" name="nit_comercializador" id="nit_comercializador" readonly="readonly" value="<?php echo $nit_comercializador; ?>" />
                                                        <input type="hidden" name="departamento" id="departamento" readonly="readonly" value="<?php echo $row_departamento['NOMBRE']; ?>" />
                                                        <input type="hidden" name="municipio" id="municipio" readonly="readonly" value="<?php echo $row_municipio['NOMBRE']; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_comercializador = mysqli_query($connection, "SELECT DISTINCT(C.ID_COMERCIALIZADOR), C.NOMBRE "
                                                                                                                . "  FROM comercializadores_2 C, facturacion_comercializadores_2 FC "
                                                                                                                . " WHERE C.ID_COMERCIALIZADOR = FC.ID_COMERCIALIZADOR "
                                                                                                                . " ORDER BY C.NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>COMERCIALIZADOR</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_comercializador = mysqli_fetch_assoc($query_select_all_comercializador)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Comercializador_Recaudo.php?id_comercializador=" . $row_all_comercializador['ID_COMERCIALIZADOR'] . "'>" . $row_all_comercializador['NOMBRE'] . "</a></td>";
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