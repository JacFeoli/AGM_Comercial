<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login_2.php");
    } else {
        $_SESSION['timeout'] = time();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Seleccionar Usuario Bitacora</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="../Images/agm_desarrollos_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="../Css/AGM_Style.css" />
        <link rel="stylesheet" href="../Css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../Javascript/bootstrap.min.js"></script>
    </head>
    <script>
        function pasarUsuarioBitacora() {
            window.opener.infoUsuarioBitacora(document.getElementById("id_usuario_bitacora").value,
                                              document.getElementById("nombre_usuario_bitacora").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarUsuarioBitacora();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Usuario Bitacora</h1>
                                            <h2></h2>
                                            <?php
                                                if (isset($_GET['id_usuario_bitacora'])) {
                                                    $query_select_usuario_bitacora = mysqli_query($connection, "SELECT DISTINCT(USU.ID_USUARIO), USU.NOMBRE
                                                                                                                  FROM bitacora_libretas_2 BL, usuarios_2 USU
                                                                                                                 WHERE BL.ID_USUARIO = USU.ID_USUARIO
                                                                                                                   AND USU.ID_USUARIO = '" . $_GET['id_usuario_bitacora'] . "'");
                                                    while ($row_usuario_bitacora = mysqli_fetch_assoc($query_select_usuario_bitacora)) {
                                                        $id_usuario_bitacora = $row_usuario_bitacora['ID_USUARIO'];
                                                        $nombre_usuario_bitacora = $row_usuario_bitacora['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_usuario_bitacora" id="id_usuario_bitacora" readonly="readonly" value="<?php echo $id_usuario_bitacora; ?>" />
                                                        <input type="hidden" name="nombre_usuario_bitacora" id="nombre_usuario_bitacora" readonly="readonly" value="<?php echo $nombre_usuario_bitacora; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $query_select_all_usuario = mysqli_query($connection, "SELECT DISTINCT(USU.ID_USUARIO), USU.NOMBRE
                                                                                                             FROM bitacora_libretas_2 BL, usuarios_2 USU
                                                                                                            WHERE BL.ID_USUARIO = USU.ID_USUARIO
                                                                                                            ORDER BY USU.NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>USUARIO</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_usuario = mysqli_fetch_assoc($query_select_all_usuario)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Usuarios_Bitacora.php?id_usuario_bitacora=" . $row_all_usuario['ID_USUARIO'] . "'>" . $row_all_usuario['NOMBRE'] . "</a></td>";
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