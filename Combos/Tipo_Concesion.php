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
        <title>AGM - Seleccionar Sociedad</title>
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
        function pasarTipoConcesion() {
            window.opener.infoTipoConcesion(document.getElementById("id_concesion").value,
                                            document.getElementById("concesion").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarTipoConcesion();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione la Sociedad</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_concesion'])) {
                                                    $query_select_concesion = mysqli_query($connection, "SELECT * FROM concesiones_2 WHERE ID_CONCESION = '" . $_GET['id_concesion'] . "'");
                                                    while ($row_concesion = mysqli_fetch_assoc($query_select_concesion)) {
                                                        $id_concesion = $row_concesion['ID_CONCESION'];
                                                        $concesion = $row_concesion['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_concesion" id="id_concesion" readonly="readonly" value="<?php echo $id_concesion; ?>" />
                                                        <input type="hidden" name="concesion" id="concesion" readonly="readonly" value="<?php echo $concesion; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_concesion = mysqli_query($connection, "SELECT * FROM concesiones_2 ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>SOCIEDAD</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_concesion = mysqli_fetch_assoc($query_select_all_concesion)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Tipo_Concesion.php?id_concesion=" . $row_all_concesion['ID_CONCESION'] . "'>" . $row_all_concesion['NOMBRE'] . "</a></td>";
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