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
        <title>AGM - Seleccionar Usuario</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="../Images/agm_desarrollos_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="../Css/AGM_Style.css" />
        <link rel="stylesheet" href="../Css/bootstrap.min.css" />
        <!--<link rel="stylesheet" href="Css/font-awesome.css">-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../Javascript/bootstrap.min.js"></script>
    </head>
    <script>
        function pasarUsuario() {
            window.opener.infoUsuariosRol(document.getElementById("id_usuario_rol").value,
                                          document.getElementById("nombre_usuario_rol").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarUsuario();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Usuario</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_usuario_rol']) && isset($_GET['id_tipo_compania'])) {
                                                    $id_tipo_compania = $_GET['id_tipo_compania'];
                                                    $query_select_usuario = mysqli_query($connection, "SELECT *
                                                                                                         FROM usuarios_2 USU, usuarios_tipo_companias_2 USUTP
                                                                                                        WHERE USU.ID_USUARIO = USUTP.ID_USUARIO
                                                                                                          AND USUTP.ID_TIPO_COMPANIA = '" . $id_tipo_compania . "'
                                                                                                          AND USU.ID_USUARIO = '" . $_GET['id_usuario_rol'] . "'");
                                                    while ($row_usuario = mysqli_fetch_assoc($query_select_usuario)) {
                                                        $id_usuario_rol = $row_usuario['ID_USUARIO'];
                                                        $nombre_usuario_rol = $row_usuario['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_usuario_rol" id="id_usuario_rol" readonly="readonly" value="<?php echo $id_usuario_rol; ?>" />
                                                        <input type="hidden" name="nombre_usuario_rol" id="nombre_usuario_rol" readonly="readonly" value="<?php echo $nombre_usuario_rol; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $id_tipo_compania = $_GET['id_tipo_compania'];
                                                    $query_select_all_usuario = mysqli_query($connection, "SELECT *
                                                                                                             FROM usuarios_2 USU, usuarios_tipo_companias_2 USUTP
                                                                                                            WHERE USU.ID_USUARIO = USUTP.ID_USUARIO
                                                                                                              AND USUTP.ID_TIPO_COMPANIA = '" . $id_tipo_compania . "' ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    //echo "<th>ID. CLASE PROCESO</th>";
                                                                    echo "<th>USUARIO</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_usuario = mysqli_fetch_assoc($query_select_all_usuario)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        //echo "<td style='vertical-align:middle;'>" . $row_all_clase_proceso['ID_CLASE_PROCESO'] . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Usuarios.php?id_tipo_compania=" . $id_tipo_compania . "&id_usuario_rol=" . $row_all_usuario['ID_USUARIO'] . "'>" . $row_all_usuario['NOMBRE'] . "</a></td>";
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