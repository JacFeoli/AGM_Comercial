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
        <title>AGM - Seleccionar Tipo Asunto P.Q.R.</title>
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
        function pasarTipoAsuntoPQR() {
            window.opener.infoTipoAsuntoPQR(document.getElementById("id_tipo_asunto_pqr").value,
                                            document.getElementById("tipo_asunto_pqr").value,
                                            document.getElementById("abreviatura_tipo_asunto_pqr").value);
            window.close();
        }
    </script>
    <body onload="javascript:pasarTipoAsuntoPQR();">
        <div class="wrapper">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Seleccione el Tipo Asunto P.Q.R.</h1>
                                            <h2></h2>
                                            <?php
                                                if (isset($_GET['id_tipo_asunto_pqr'])) {
                                                    $query_select_tipo_asunto_pqr = mysqli_query($connection, "SELECT * FROM tipo_asuntos_pqr_2 WHERE ID_TIPO_ASUNTO = '" . $_GET['id_tipo_asunto_pqr'] . "'");
                                                    while ($row_tipo_asunto_pqr = mysqli_fetch_assoc($query_select_tipo_asunto_pqr)) {
                                                        $id_tipo_asunto_pqr = $row_tipo_asunto_pqr['ID_TIPO_ASUNTO'];
                                                        $tipo_asunto_pqr = $row_tipo_asunto_pqr['NOMBRE'];
                                                        $abreviatura_tipo_asunto_pqr = $row_tipo_asunto_pqr['ABREVIATURA'];
                                                        ?>
                                                        <input type="hidden" name="id_tipo_asunto_pqr" id="id_tipo_asunto_pqr" readonly="readonly" value="<?php echo $id_tipo_asunto_pqr; ?>" />
                                                        <input type="hidden" name="tipo_asunto_pqr" id="tipo_asunto_pqr" readonly="readonly" value="<?php echo $tipo_asunto_pqr; ?>" />
                                                        <input type="hidden" name="abreviatura_tipo_asunto_pqr" id="abreviatura_tipo_asunto_pqr" readonly="readonly" value="<?php echo $abreviatura_tipo_asunto_pqr; ?>" />
                                                    <?php
                                                    }
                                                    /*if ($_GET['id_pqr_editar'] == 0) {
                                                        $query_select_consecutivo = mysqli_query($connection, "SELECT * "
                                                                                                            . "  FROM pqr_2 "
                                                                                                            . " WHERE ID_COD_DPTO = " . $_GET['id_departamento'] . " "
                                                                                                            . "   AND ID_COD_MPIO = " . $_GET['id_municipio'] . " "
                                                                                                            . "   AND FECHA_INGRESO = '" . $_GET['fecha_ingreso_pqr'] . "' ");
                                                        if (mysqli_num_rows($query_select_consecutivo) != 0) { ?>
                                                            <input type="hidden" name="consecutivo_municipio" id="consecutivo_municipio" readonly="readonly" value="<?php echo mysqli_num_rows($query_select_consecutivo) + 1; ?>" />
                                                            <?php
                                                        } else { ?>
                                                            <input type="hidden" name="consecutivo_municipio" id="consecutivo_municipio" readonly="readonly" value="1" />
                                                            <?php
                                                        }
                                                    } else {
                                                        $query_select_consecutivo = mysqli_query($connection, "SELECT * "
                                                                                                            . "  FROM pqr_2 "
                                                                                                            . " WHERE ID_PQR = " . $_GET['id_pqr_editar'] . " ");
                                                        $row_consecutivo = mysqli_fetch_array($query_select_consecutivo);
                                                        if ($row_consecutivo['FECHA_INGRESO'] == $_GET['fecha_ingreso_pqr']) {
                                                            $consecutivo = substr($row_consecutivo['RADICADO'], -3);
                                                            ?>
                                                                <input type="hidden" name="consecutivo_municipio" id="consecutivo_municipio" readonly="readonly" value="<?php echo $consecutivo; ?>" />
                                                            <?php
                                                        } else {
                                                            $query_select_consecutivo = mysqli_query($connection, "SELECT * "
                                                                                                                . "  FROM pqr_2 "
                                                                                                                . " WHERE ID_COD_DPTO = " . $_GET['id_departamento'] . " "
                                                                                                                . "   AND ID_COD_MPIO = " . $_GET['id_municipio'] . " "
                                                                                                                . "   AND FECHA_INGRESO = '" . $_GET['fecha_ingreso_pqr'] . "' ");
                                                            if (mysqli_num_rows($query_select_consecutivo) != 0) { ?>
                                                                <input type="hidden" name="consecutivo_municipio" id="consecutivo_municipio" readonly="readonly" value="<?php echo mysqli_num_rows($query_select_consecutivo) + 1; ?>" />
                                                                <?php
                                                            } else { ?>
                                                                <input type="hidden" name="consecutivo_municipio" id="consecutivo_municipio" readonly="readonly" value="1" />
                                                                <?php
                                                            }
                                                        }
                                                    }*/
                                                } else {
                                                    $fecha_ingreso_pqr = $_GET['fecha_ingreso_pqr'];
                                                    $id_departamento = $_GET['id_departamento'];
                                                    $id_municipio = $_GET['id_municipio'];
                                                    $id_pqr_editar = $_GET['id_pqr_editar'];
                                                    $query_select_all_tipo_asunto_pqr = mysqli_query($connection, "SELECT * FROM tipo_asuntos_pqr_2 ORDER BY NOMBRE");
                                                    echo "<div class='table-responsive'>";
                                                        echo "<table class='table table-condensed table-hover'>";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th>#</th>";
                                                                    echo "<th>TIPO ASUNTO P.Q.R.</th>";
                                                                echo "</tr>";
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                                $cont = 0;
                                                                while ($row_all_tipo_asunto_pqr = mysqli_fetch_assoc($query_select_all_tipo_asunto_pqr)) {
                                                                    $cont = $cont + 1;
                                                                    echo "<tr>";
                                                                        echo "<td style='vertical-align:middle;'>" . $cont . "</td>";
                                                                        echo "<td style='vertical-align:middle;'><a href='../Combos/Tipo_Asunto_PQR.php?id_tipo_asunto_pqr=" . $row_all_tipo_asunto_pqr['ID_TIPO_ASUNTO'] . "&id_departamento=" . $id_departamento . "&id_municipio=" . $id_municipio . "&id_pqr_editar=" . $id_pqr_editar . "&fecha_ingreso_pqr=" . $fecha_ingreso_pqr . "'>" . $row_all_tipo_asunto_pqr['NOMBRE'] . "</a></td>";
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