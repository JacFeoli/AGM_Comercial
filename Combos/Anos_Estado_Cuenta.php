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
        <title>AGM - Seleccionar Año Estado de Cuenta</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="../Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="../Css/AGM_Style.css" />
        <link rel="stylesheet" href="../Css/bootstrap.min.css" />
        <link rel="stylesheet" href="../Css/font-awesome.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <script src="../Javascript/bootstrap.min.js"></script>
    </head>
    <script>
        function pasarAnoEstadoCuenta() {
            window.opener.infoAnoEstadoCuenta(document.getElementById("id_consulta").value,
                                              document.getElementById("ano").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarAnoEstadoCuenta();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Año</h1>
                                            <h2></h2>
                                            <?php
                                                $sw = 0;
                                                $or = "";
                                                $where = "";
                                                $id_ano = "";
                                                $id_mes = "";
                                                $ano = "";
                                                $concatAno = "";
                                                if (isset($_GET['anos'])) {
                                                    $myAnos = explode(',', $_GET['anos']);
                                                    foreach ($myAnos as $anos) {
                                                        $concatAno = $concatAno . $anos . ", ";
                                                    }
                                                    $where = " WHERE NOMBRE IN (" . substr($concatAno, 0, -4) . ") ";
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $query_select_ano = mysqli_query($connection, "SELECT * "
                                                                                                . "  FROM anos_2 "
                                                                                                . $where
                                                                                                . " ORDER BY NOMBRE DESC");
                                                    while ($row_ano = mysqli_fetch_assoc($query_select_ano)) {
                                                        $ano = $ano . $row_ano['NOMBRE'] . ", ";
                                                        ?>
                                                    <?php
                                                    }
                                                    ?>
                                                        <input type="hidden" name="ano" id="ano" readonly="readonly" value="<?php echo substr(strtolower($ano), 0, -1); ?>" />
                                                        <input type="hidden" name="id_consulta" id="id_consulta" readonly="readonly" value="<?php echo $id_consulta; ?>" />
                                                    <?php
                                                } else {
                                                    $id_consulta = $_GET['id_consulta'];
                                                    $query_select_all_anos = mysqli_query($connection, "SELECT * "
                                                                                                     . "  FROM anos_2 "
                                                                                                     . " ORDER BY NOMBRE DESC LIMIT 5");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>AÑO</th>";
                                                                    echo "<th>SELECCIONAR</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_ano = mysqli_fetch_assoc($query_select_all_anos)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align: middle;'>" . $row_all_ano['NOMBRE'] . "</td>";
                                                                        echo "<td style='vertical-align: middle;'><input class='form-check-input position-static' type='checkbox' name='ano' value='" . $row_all_ano['NOMBRE'] . "'><input type='hidden' id='id_consulta' name='id_consulta' value='" . $id_consulta . "' /></td>";
                                                                    echo "</tr>";
                                                                }
                                                            echo "</tbody>";
                                                        echo "</table>";
                                                    echo "</div>";
                                                    echo "<div class='row'>";
                                                        echo "<div class='col-xs-12 text-center'>";
                                                            echo "<button class='btn btn-primary btn-sm font background cursor' id='btn_enviar' type='button'><i style='font-size: 14px;' class='fas fa-search'></i>&nbsp;&nbsp;Enviar</button>";
                                                        echo "</div>";
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
    <script>
        $(document).ready(function() {
            $("#btn_enviar").click(function() {
                var anos = "";
                var id_consulta = $("#id_consulta").val();
                var checkBoxes = document.getElementsByName("ano");
                for (var i=0;i<checkBoxes.length;i++) {
                    if (checkBoxes[i].checked == true) {
                        anos = anos + checkBoxes[i].value + ",";
                    }
                }
                document.location.href = '../Combos/Anos_Estado_Cuenta.php?anos='+anos+'&id_consulta='+id_consulta;
            });
        });
    </script>
</html>