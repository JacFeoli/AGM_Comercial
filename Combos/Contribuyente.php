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
        function pasarContribuyenteFact() {
            window.opener.infoContribuyenteFact(document.getElementById("id_contribuyente").value,
                                               document.getElementById("contribuyente").value,
                                               document.getElementById("nit_contribuyente").value,
                                               document.getElementById("acuerdo_municipal").value,
                                               document.getElementById("tipo_facturacion").value,
                                               document.getElementById("tarifa").value,
                                               document.getElementById("salario_minimo").value,
                                               document.getElementById("consecutivo").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarContribuyenteFact();">
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
                                                if(isset($_GET['id_contribuyente']) && isset($_GET['id_municipio']) && isset($_GET['id_departamento'])) {
                                                    $id_departamento = $_GET['id_departamento'];
                                                    $id_municipio = $_GET['id_municipio'];
                                                    $query_select_contribuyente = mysqli_query($connection, "SELECT * "
                                                                                                          . "  FROM contribuyentes_2 "
                                                                                                          . " WHERE ID_CONTRIBUYENTE = '" . $_GET['id_contribuyente'] . "' "
                                                                                                          . "   AND ID_DEPARTAMENTO = '" . $id_departamento . "' "
                                                                                                          . "   AND ID_MUNICIPIO = '" . $id_municipio . "'");
                                                    while ($row_contribuyente = mysqli_fetch_assoc($query_select_contribuyente)) {
                                                        $id_contribuyente = $row_contribuyente['ID_CONTRIBUYENTE'];
                                                        $contribuyente = $row_contribuyente['NOMBRE'];
                                                        $nit_contribuyente = $row_contribuyente['NIT_CONTRIBUYENTE'];
                                                        $acuerdo_municipal = $row_contribuyente['ACUERDO_MCPAL'];
                                                        $tipo_facturacion = $row_contribuyente['ID_TIPO_FACTURACION'];
                                                        $tarifa = $row_contribuyente['TARIFA'];
                                                        switch ($tipo_facturacion) {
                                                            case '2':
                                                                $query_select_salario_minimo = mysqli_query($connection, "SELECT SALARIO_MINIMO FROM salarios_minimos_2 ORDER BY ANO DESC LIMIT 1");
                                                                $row_salario_minimo = mysqli_fetch_array($query_select_salario_minimo);
                                                                $salario_minimo = $row_salario_minimo['SALARIO_MINIMO'];
                                                                break;
                                                            case '3':
                                                                $query_select_uvt = mysqli_query($connection, "SELECT UVT FROM uvts_2 ORDER BY ANO DESC LIMIT 1");
                                                                $row_uvt = mysqli_fetch_array($query_select_uvt);
                                                                $salario_minimo = $row_uvt['UVT'];
                                                                break;
                                                            default:
                                                                $salario_minimo = 0;
                                                                break;
                                                        }
                                                        ?>
                                                        <input type="hidden" name="id_contribuyente" id="id_contribuyente" readonly="readonly" value="<?php echo $id_contribuyente; ?>" />
                                                        <input type="hidden" name="contribuyente" id="contribuyente" readonly="readonly" value="<?php echo $contribuyente; ?>" />
                                                        <input type="hidden" name="nit_contribuyente" id="nit_contribuyente" readonly="readonly" value="<?php echo $nit_contribuyente; ?>" />
                                                        <input type="hidden" name="acuerdo_municipal" id="acuerdo_municipal" readonly="readonly" value="<?php echo $acuerdo_municipal; ?>" />
                                                        <input type="hidden" name="tipo_facturacion" id="tipo_facturacion" readonly="readonly" value="<?php echo $tipo_facturacion; ?>" />
                                                        <input type="hidden" name="tarifa" id="tarifa" readonly="readonly" value="<?php echo $tarifa; ?>" />
                                                        <input type="hidden" name="salario_minimo" id="salario_minimo" readonly="readonly" value="<?php echo $salario_minimo; ?>" />
                                                    <?php
                                                    }
                                                    $query_select_consecutivo = mysqli_query($connection, "SELECT * "
                                                                                                        . "  FROM facturacion_especiales_2 "
                                                                                                        . " WHERE ID_CONTRIBUYENTE = " . $id_contribuyente . " ");
                                                    if (mysqli_num_rows($query_select_consecutivo) != 0) { ?>
                                                        <input type="hidden" name="consecutivo" id="consecutivo" readonly="readonly" value="<?php echo mysqli_num_rows($query_select_consecutivo) + 1; ?>" />
                                                        <?php
                                                    } else { ?>
                                                        <input type="hidden" name="consecutivo" id="consecutivo" readonly="readonly" value="1" />
                                                        <?php
                                                    }
                                                } else {
                                                    $id_departamento = $_GET['id_departamento'];
                                                    $id_municipio = $_GET['id_municipio'];
                                                    $query_select_all_contribuyente = mysqli_query($connection, "SELECT * "
                                                                                                              . "  FROM contribuyentes_2 "
                                                                                                              . " WHERE ID_DEPARTAMENTO = '" . $id_departamento . "' "
                                                                                                              . "   AND ID_MUNICIPIO = '" . $id_municipio . "' "
                                                                                                              . " ORDER BY NOMBRE");
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
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Contribuyente.php?id_contribuyente=" . $row_all_contribuyente['ID_CONTRIBUYENTE'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "'>" . $row_all_contribuyente['NOMBRE'] . "</a></td>";
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