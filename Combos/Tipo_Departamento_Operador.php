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
        <title>AGM - Seleccionar Departamento</title>
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
        function pasarTipoDepartamento() {
            window.opener.infoTipoDepartamento(document.getElementById("id_consulta").value,
                                               document.getElementById("id_departamento").value,
                                               document.getElementById("departamento").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarTipoDepartamento();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Departamento</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_departamento'])) {
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $id_operador = $_GET['id_operador'];
                                                    $query_select_departamento = mysqli_query($connection, "SELECT * "
                                                                                                         . "  FROM departamentos_operadores_2 "
                                                                                                         . " WHERE ID_DEPARTAMENTO = '" . $_GET['id_departamento'] . "' "
                                                                                                         . "   AND ID_OPERADOR = '" . $id_operador . "'");
                                                    while ($row_departamento = mysqli_fetch_assoc($query_select_departamento)) {
                                                        $id_departamento = $row_departamento['ID_DEPARTAMENTO'];
                                                        $departamento = $row_departamento['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_departamento" id="id_departamento" readonly="readonly" value="<?php echo $id_departamento; ?>" />
                                                        <input type="hidden" name="departamento" id="departamento" readonly="readonly" value="<?php echo $departamento; ?>" />
                                                        <input type="hidden" name="id_consulta" id="id_consulta" readonly="readonly" value="<?php echo $id_consulta; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $id_operador = $_GET['id_operador'];
                                                    $query_select_all_departamento = mysqli_query($connection, "SELECT * "
                                                                                                             . "  FROM departamentos_operadores_2 "
                                                                                                             . " WHERE ID_OPERADOR = '" . $id_operador . "' "
                                                                                                             . " ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>DEPARTAMENTO</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_departamento = mysqli_fetch_assoc($query_select_all_departamento)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Tipo_Departamento_Operador.php?id_departamento=" . $row_all_departamento['ID_DEPARTAMENTO'] . "&id_consulta=" . $id_consulta . "&id_operador=" . $id_operador . "'>" . $row_all_departamento['NOMBRE'] . "</a></td>";
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