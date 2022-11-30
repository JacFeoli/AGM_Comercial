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
        <title>AGM - Seleccionar Operador</title>
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
        function pasarOperadorReca() {
            window.opener.infoOperadorReca(document.getElementById("id_operador").value,
                                           document.getElementById("operador").value,
                                           document.getElementById("nit_operador").value,
                                           document.getElementById("departamento").value,
                                           document.getElementById("municipio").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarOperadorReca();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Operador</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_operador'])) {
                                                    $query_select_operador = mysqli_query($connection, "SELECT * "
                                                                                                     . "  FROM operadores_2 O, facturacion_operadores_2 FO "
                                                                                                     . " WHERE O.ID_OPERADOR = FO.ID_OPERADOR "
                                                                                                     . "   AND O.ID_OPERADOR = '" . $_GET['id_operador'] . "' ");
                                                    while ($row_operador = mysqli_fetch_assoc($query_select_operador)) {
                                                        $id_operador = $row_operador['ID_OPERADOR'];
                                                        $operador = $row_operador['NOMBRE'];
                                                        $nit_operador = $row_operador['NIT_OPERADOR'];
                                                        $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE FROM departamentos_operadores_2 WHERE ID_DEPARTAMENTO = '" . $row_operador['ID_COD_DPTO'] . "'");
                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                        $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_operadores_2 WHERE ID_DEPARTAMENTO = '" . $row_operador['ID_COD_DPTO'] . "' AND ID_MUNICIPIO = '" . $row_operador['ID_COD_MPIO'] . "'");
                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                        ?>
                                                        <input type="hidden" name="id_operador" id="id_operador" readonly="readonly" value="<?php echo $id_operador; ?>" />
                                                        <input type="hidden" name="operador" id="operador" readonly="readonly" value="<?php echo $operador; ?>" />
                                                        <input type="hidden" name="nit_operador" id="nit_operador" readonly="readonly" value="<?php echo $nit_operador; ?>" />
                                                        <input type="hidden" name="departamento" id="departamento" readonly="readonly" value="<?php echo $row_departamento['NOMBRE']; ?>" />
                                                        <input type="hidden" name="municipio" id="municipio" readonly="readonly" value="<?php echo $row_municipio['NOMBRE']; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_operador = mysqli_query($connection, "SELECT DISTINCT(O.ID_OPERADOR), O.NOMBRE "
                                                                                                         . "  FROM operadores_2 O, facturacion_operadores_2 FO "
                                                                                                         . " WHERE O.ID_OPERADOR = FO.ID_OPERADOR "
                                                                                                         . " ORDER BY O.NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>OPERADOR</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_operador = mysqli_fetch_assoc($query_select_all_operador)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Operador_Recaudo.php?id_operador=" . $row_all_operador['ID_OPERADOR'] . "'>" . $row_all_operador['NOMBRE'] . "</a></td>";
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