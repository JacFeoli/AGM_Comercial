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
        <title>AGM - Seleccionar Rol</title>
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
        function pasarRol() {
            window.opener.infoRol(document.getElementById("id_rol").value,
                                  document.getElementById("nombre_rol").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarRol();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Rol</h1>
                                            <h2></h2>
                                            <?php
                                                if(isset($_GET['id_rol']) && isset($_GET['id_tipo_compania'])) {
                                                    $id_tipo_compania = $_GET['id_tipo_compania'];
                                                    $query_select_rol = mysqli_query($connection, "SELECT *
                                                                                                     FROM roles_2 ROL
                                                                                                    WHERE ROL.ID_TIPO_COMPANIA = '" . $id_tipo_compania . "'
                                                                                                      AND ROL.ID_ROL = '" . $_GET['id_rol'] . "'");
                                                    while ($row_rol = mysqli_fetch_assoc($query_select_rol)) {
                                                        $id_rol = $row_rol['ID_ROL'];
                                                        $nombre_rol = $row_rol['NOMBRE'];
                                                        ?>
                                                        <input type="hidden" name="id_rol" id="id_rol" readonly="readonly" value="<?php echo $id_rol; ?>" />
                                                        <input type="hidden" name="nombre_rol" id="nombre_rol" readonly="readonly" value="<?php echo $nombre_rol; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $id_tipo_compania = $_GET['id_tipo_compania'];
                                                    $id_usuario_rol = $_GET['id_usuario_rol'];
                                                    $query_select_all_rol = mysqli_query($connection, "SELECT ROL.ID_ROL, ROL.NOMBRE
                                                                                                         FROM roles_2 ROL
                                                                                                        WHERE ROL.ID_ROL NOT IN (SELECT ROLUSU.ID_ROL
                                                                                                                                   FROM roles_usuarios_2 ROLUSU
                                                                                                                                  WHERE ROLUSU.ID_USUARIO = '" . $id_usuario_rol . "')
                                                                                                          AND ROL.ID_TIPO_COMPANIA = '" . $id_tipo_compania . "' ORDER BY NOMBRE");
                                                    if (mysqli_num_rows($query_select_all_rol) != 0) {
                                                        echo "<div class='table-responsive'>";
                                                            echo "<table class='table table-condensed table-hover'>";
                                                                echo "<thead>";
                                                                    echo "<tr>";
                                                                        echo "<th>#</th>";
                                                                        echo "<th>ROL</th>";
                                                                    echo "</tr>";
                                                                echo "</thead>";
                                                                echo "<tbody>";
                                                                    $cont = 0;
                                                                    while ($row_all_rol = mysqli_fetch_assoc($query_select_all_rol)) {
                                                                        $cont = $cont + 1;
                                                                        echo "<tr>";
                                                                            echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                            echo "<td style='vertical-align:middle;'><a href='../Combos/Roles.php?id_tipo_compania=" . $id_tipo_compania . "&id_rol=" . $row_all_rol['ID_ROL'] . "'>" . $row_all_rol['NOMBRE'] . "</a></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                echo "</tbody>";
                                                            echo "</table>";
                                                        echo "</div>";
                                                    } else {
                                                        echo "<p class='message'>El Usuario ya tiene asignado todos los Roles. Favor revisar información.</p>";
                                                    }
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