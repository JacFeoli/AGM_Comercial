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
        <title>AGM - Seleccionar Empresa</title>
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
        function pasarTipoEmpresa() {
            window.opener.infoTipoEmpresa(document.getElementById("id_empresa").value,
                                          document.getElementById("empresa").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarTipoEmpresa();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione la Empresa</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_empresa'])) {
                                                    $query_select_empresa = mysqli_query($connection, "SELECT * FROM empresas_2 WHERE ID_EMPRESA = '" . $_GET['id_empresa'] . "'");
                                                    while ($row_empresa = mysqli_fetch_assoc($query_select_empresa)) {
                                                        $id_empresa = $row_empresa['ID_EMPRESA'];
                                                        $empresa = $row_empresa['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_empresa" id="id_empresa" readonly="readonly" value="<?php echo $id_empresa; ?>" />
                                                        <input type="hidden" name="empresa" id="empresa" readonly="readonly" value="<?php echo $empresa; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_empresa = mysqli_query($connection, "SELECT * FROM empresas_2 ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>EMPRESA</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_empresa = mysqli_fetch_assoc($query_select_all_empresa)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Tipo_Empresa.php?id_empresa=" . $row_all_empresa['ID_EMPRESA'] . "'>" . $row_all_empresa['NOMBRE'] . "</a></td>";
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