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
        <title>AGM - Seleccionar Destinatario</title>
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
        function pasarDestinatarioCorreo() {
            window.opener.infoDestinatarioCorreo(document.getElementById("id_usuario").value,
                                                 document.getElementById("usuario").value,
                                                 document.getElementById("correo_electronico").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarDestinatarioCorreo();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Destinatario</h1>
                                            <h2></h2>
                                            <?php
                                                if (isset($_GET['id_tabla_correo']) && isset($_GET['id_destinatario']) && isset($_GET['id_usuario_remitente'])) {
                                                    $query_select_destinatario = mysqli_query($connection, "SELECT * FROM usuarios_2 WHERE ID_USUARIO = '" . $_GET['id_destinatario'] . "'");
                                                    while ($row_destinatario = mysqli_fetch_assoc($query_select_destinatario)) {
                                                        $id_usuario = $row_destinatario['ID_USUARIO'];
                                                        $usuario = $row_destinatario['NOMBRE'];
                                                        $correo_electronico = $row_destinatario['CORREO_ELECTRONICO'];
                                                        ?>
                                                        <input type="hidden" name="id_usuario" id="id_usuario" readonly="readonly" value="<?php echo $id_usuario; ?>" />
                                                        <input type="hidden" name="usuario" id="usuario" readonly="readonly" value="<?php echo $usuario; ?>" />
                                                        <input type="hidden" name="correo_electronico" id="correo_electronico" readonly="readonly" value="<?php echo $correo_electronico; ?>" />
                                                    <?php
                                                    }
                                                } else {
                                                    $id_tabla_correo = $_GET['id_tabla_correo'];
                                                    $id_usuario_remitente = $_GET['id_usuario_remitente'];
                                                    $query_select_all_destinatario = mysqli_query($connection, "SELECT * FROM usuarios_2 WHERE ID_USUARIO <> '" . $id_usuario_remitente . "' ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>USUARIO</th>";
                                                                    echo "<th>CORREO ELECTRÓNICO</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_destinatario = mysqli_fetch_assoc($query_select_all_destinatario)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Destinatario_Correo.php?id_destinatario=" . $row_all_destinatario['ID_USUARIO'] . "&id_tabla_correo=" . $id_tabla_correo . "&id_usuario_remitente=" . $id_usuario_remitente . "'>" . $row_all_destinatario['NOMBRE'] . "</a></td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Destinatario_Correo.php?id_destinatario=" . $row_all_destinatario['ID_USUARIO'] . "&id_tabla_correo=" . $id_tabla_correo . "&id_usuario_remitente=" . $id_usuario_remitente . "'>" . $row_all_destinatario['CORREO_ELECTRONICO'] . "</a></td>";
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