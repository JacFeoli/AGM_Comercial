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
        <title>AGM - Seleccionar Municipio</title>
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
        function pasarTipoMunicipio() {
            window.opener.infoTipoMunicipio(document.getElementById("id_consulta").value,
                                            document.getElementById("id_municipio").value,
                                            document.getElementById("municipio").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarTipoMunicipio();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Municipio</h1>
                                            <h2></h2>
                                            <?php
                                                if (isset($_GET['id_municipio']) && isset($_GET['id_departamento'])) {
                                                    $departamento = $_GET['id_departamento'];
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $id_comercializador = $_GET['id_comercializador'];
                                                    $query_select_municipio = mysqli_query($connection, "SELECT * "
                                                                                                      . "  FROM municipios_comercializadores_2 "
                                                                                                      . " WHERE ID_DEPARTAMENTO = '" . $departamento . "' "
                                                                                                      . "   AND ID_MUNICIPIO = '" . $_GET['id_municipio'] . "' "
                                                                                                      . "   AND ID_COMERCIALIZADOR = '" . $id_comercializador . "'");
                                                    while ($row_municipio = mysqli_fetch_assoc($query_select_municipio)) {
                                                        $id_municipio = $row_municipio['ID_MUNICIPIO'];
                                                        $municipio = $row_municipio['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_municipio" id="id_municipio" readonly="readonly" value="<?php echo $id_municipio; ?>" />
                                                        <input type="hidden" name="municipio" id="municipio" readonly="readonly" value="<?php echo $municipio; ?>" />
                                                        <input type="hidden" name="id_consulta" id="id_consulta" readonly="readonly" value="<?php echo $id_consulta; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $departamento = $_GET['id_departamento'];
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $id_comercializador = $_GET['id_comercializador'];
                                                    $query_select_all_municipio = mysqli_query($connection, "SELECT * "
                                                                                                          . "  FROM municipios_comercializadores_2 "
                                                                                                          . " WHERE ID_DEPARTAMENTO = '" . $departamento . "' "
                                                                                                          . "   AND ID_COMERCIALIZADOR = '" . $id_comercializador . "' "
                                                                                                          . " ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>MUNICIPIO</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_municipio = mysqli_fetch_assoc($query_select_all_municipio)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Tipo_Municipio_Comercializador.php?id_departamento=" . $departamento . "&id_municipio=" . $row_all_municipio['ID_MUNICIPIO'] . "&id_consulta=" . $id_consulta . "&id_comercializador=" . $id_comercializador . "'>" . $row_all_municipio['NOMBRE'] . "</a></td>";
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