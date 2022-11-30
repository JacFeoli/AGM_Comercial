<?php
    session_start();
    require_once('Includes/Config.php');
    if ($_SESSION['rol'] == 'A') {
        if ($_SESSION['timeout'] + 60 * 60 < time()) {
            session_unset();
            session_destroy();
            $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            header("Location:$ruta/Login.php");
        } else {
            $_SESSION['timeout'] = time();
        }
    } else {
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Acceso_Restringido.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Admin. Alcaldías</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <!--<link rel="stylesheet" href="Css/font-awesome.css">-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <style type="text/css">
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
        </style>
    </head>
    <!--Eliminar Alcaldía Modal-->
    <div class="modal fade" id="modalEliminarAlcaldia" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Alcaldía</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar la Alcaldía?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_alcaldia" name="eliminar_alcaldia"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Alcaldía Modal-->
    <!--Eliminar Alcaldía Error-->
    <div class="modal fade" id="modalEliminarAlcaldiaError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Alcaldía</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar la Alcaldía, ya que existen Registros creados con éste. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Alcaldía Error-->
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Admin.php"); ?>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div style="width: 170px;" class="leftcol">
                                            <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class='active'>
                                                    <a href="Admin_Alcaldias.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Alcaldías</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Admin. Alcaldías</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_alcaldia_tab" id="tab_info_alcaldia" aria-controls="informacion_alcaldia_tab" role="tab" data-toggle="tab">Información Alcaldías</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_alcaldia_tab" id="tab_crear_alcaldia" aria-controls="crear_alcaldia_tab" role="tab" data-toggle="tab">Crear Alcaldía</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_alcaldia_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Alcaldía" name="buscar_alcaldia" id="buscar_alcaldia" />
                                                    <br />
                                                    <?php
                                                        $query_select_alcaldias = "SELECT * FROM alcaldias_2 ORDER BY NOMBRE";
                                                        $sql_alcaldias = mysqli_query($connection, $query_select_alcaldias);
                                                        if (mysqli_num_rows($sql_alcaldias) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=13%>DEPARTAMENTO</th>";
                                                                            echo "<th width=20%>MUNICIPIO</th>";
                                                                            echo "<th width=13%>NIT</th>";
                                                                            echo "<th width=44%>ALCALDÍA</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_alcaldia'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Alcaldías Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-alcaldia"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_alcaldia_tab">
                                                    <?php
                                                        if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_alcaldia" name="crear_alcaldia" action="<?php echo "Modelo/Crear_Admin_Alcaldia.php?editar=" . $_GET['id_alcaldia_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_alcaldia = mysqli_query($connection, "SELECT * FROM alcaldias_2 WHERE ID_ALCALDIA = " . $_GET['id_alcaldia_editar']);
                                                            $row_alcaldia = mysqli_fetch_array($query_select_alcaldia);
                                                        ?>
                                                            <input type="hidden" id="id_alcaldia_editar_hidden" name="id_alcaldia_editar_hidden" value="<?php echo $row_alcaldia['ID_ALCALDIA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_alcaldia" name="crear_alcaldia" action="<?php echo "Modelo/Crear_Admin_Alcaldia.php?eliminar=" . $_GET['id_alcaldia_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_alcaldia = mysqli_query($connection, "SELECT * FROM alcaldias_2 WHERE ID_ALCALDIA = " . $_GET['id_alcaldia_eliminar']);
                                                                $row_alcaldia = mysqli_fetch_array($query_select_alcaldia);
                                                            ?>
                                                                <input type="hidden" id="id_alcaldia_eliminar_hidden" name="id_alcaldia_eliminar_hidden" value="<?php echo $row_alcaldia['ID_ALCALDIA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_alcaldia" name="crear_alcaldia" action="<?php echo "Modelo/Crear_Admin_Alcaldia.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_alcaldia['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_alcaldia['ID_COD_DPTO']);
                                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                        ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_alcaldia['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_alcaldia['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_alcaldia['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_alcaldia['ID_COD_MPIO']);
                                                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                        ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_alcaldia" name="nombre_alcaldia" value="<?php echo $row_alcaldia['NOMBRE']; ?>" maxlength="150" placeholder="Alcaldía" data-toogle="tooltip" title="ALCALDÍA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_alcaldia" name="nombre_alcaldia" readonly="readonly" placeholder="Alcaldía" value="<?php echo $row_alcaldia['NOMBRE']; ?>" data-toogle="tooltip" title="ALCALDÍA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_alcaldia" name="nombre_alcaldia" maxlength="150" placeholder="Alcaldía" data-toogle="tooltip" title="ALCALDÍA" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_alcaldia" name="nit_alcaldia" value="<?php echo $row_alcaldia['NIT_ALCALDIA'] ?>" maxlength="20" placeholder="NIT" data-toggle="tooltip" title="NIT" onkeypress="return isNumeric(event)" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_alcaldia" name="nit_alcaldia" readonly="readonly" placeholder="NIT" value="<?php echo $row_alcaldia['NIT_ALCALDIA'] ?>" data-toggle="tooltip" title="NIT" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_alcaldia" name="nit_alcaldia" maxlength="20" placeholder="NIT" data-toggle="tooltip" title="NIT" onkeypress="return isNumeric(event)" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_sec_hacienda" name="nombre_sec_hacienda" value="<?php echo $row_alcaldia['NOMBRE_SEC_HACIENDA']; ?>" maxlength="100" placeholder="Nombre Sec. Hacienda" data-toogle="tooltip" title="NOMBRE SEC. HACIENDA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_sec_hacienda" name="nombre_sec_hacienda" readonly="readonly" placeholder="Nombre Sec. Hacienda" value="<?php echo $row_alcaldia['NOMBRE_SEC_HACIENDA']; ?>" data-toogle="tooltip" title="NOMBRE SEC. HACIENDA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_sec_hacienda" name="nombre_sec_hacienda" maxlength="100" placeholder="Nombre Sec. Hacienda" data-toogle="tooltip" title="NOMBRE SEC. HACIENDA" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="direccion_alcaldia" name="direccion_alcaldia" value="<?php echo $row_alcaldia['DIRECCION_ALCALDIA']; ?>" maxlength="100" placeholder="Dirección" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_alcaldia" name="direccion_alcaldia" readonly="readonly" placeholder="Dirección" value="<?php echo $row_alcaldia['DIRECCION_ALCALDIA']; ?>" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_alcaldia" name="direccion_alcaldia" maxlength="100" placeholder="Dirección" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="telefono_alcaldia" name="telefono_alcaldia" value="<?php echo $row_alcaldia['TELEFONO_ALCALDIA'] ?>" maxlength="25" placeholder="Telefono" data-toggle="tooltip" title="TELEFONO" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="telefono_alcaldia" name="telefono_alcaldia" readonly="readonly" placeholder="Telefono" value="<?php echo $row_alcaldia['TELEFONO_ALCALDIA'] ?>" data-toggle="tooltip" title="TELEFONO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="telefono_alcaldia" name="telefono_alcaldia" maxlength="20" placeholder="Telefono" data-toggle="tooltip" title="TELEFONO" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" value="<?php echo $row_alcaldia['CORREO_ELECTRONICO_ALCALDIA']; ?>" maxlength="100" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" readonly="readonly" placeholder="Correo Electrónico" value="<?php echo $row_alcaldia['CORREO_ELECTRONICO_ALCALDIA']; ?>" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" maxlength="100" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN FIDUCIA</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-4">
                                                                <div class="input-group" id="cuenta_fiducia" data-toogle="tooltip" title="CUENTA FIDUCIA">
                                                                    <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="cuenta_fiducia" value="<?php echo $row_alcaldia['CUENTA_FIDUCIA'] ?>" maxlength="20" placeholder="Cuenta Fiducia" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuenta_fiducia" value="<?php echo $row_alcaldia['CUENTA_FIDUCIA'] ?>" placeholder="Cuenta Fiducia" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuenta_fiducia" value="" maxlength="20" placeholder="Cuenta Fiducia" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="banco_fiducia" name="banco_fiducia" value="<?php echo $row_alcaldia['BANCO_FIDUCIA']; ?>" maxlength="100" placeholder="Banco Fiducia" data-toogle="tooltip" title="BANCO FIDUCIA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="banco_fiducia" name="banco_fiducia" readonly="readonly" placeholder="Banco Fiducia" value="<?php echo $row_alcaldia['BANCO_FIDUCIA']; ?>" data-toogle="tooltip" title="BANCO FIDUCIA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="banco_fiducia" name="banco_fiducia" maxlength="100" placeholder="Banco Fiducia" data-toogle="tooltip" title="BANCO FIDUCIA" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_fiducia" name="nombre_fiducia" value="<?php echo $row_alcaldia['NOMBRE_FIDUCIA']; ?>" maxlength="150" placeholder="Fiducia" data-toogle="tooltip" title="FIDUCIA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_fiducia" name="nombre_fiducia" readonly="readonly" placeholder="Fiducia" value="<?php echo $row_alcaldia['NOMBRE_FIDUCIA']; ?>" data-toogle="tooltip" title="FIDUCIA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_fiducia" name="nombre_fiducia" maxlength="150" placeholder="Fiducia" data-toogle="tooltip" title="FIDUCIA" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_fiducia" name="nit_fiducia" value="<?php echo $row_alcaldia['NIT_FIDUCIA'] ?>" maxlength="20" placeholder="NIT Fiducia" data-toggle="tooltip" title="NIT FIDUCIA" onkeypress="return isNumeric(event)" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_fiducia" name="nit_fiducia" readonly="readonly" placeholder="NIT Fiducia" value="<?php echo $row_alcaldia['NIT_FIDUCIA'] ?>" data-toggle="tooltip" title="NIT FIDUCIA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_fiducia" name="nit_fiducia" maxlength="20" placeholder="NIT Fiducia" data-toggle="tooltip" title="NIT FIDUCIA" onkeypress="return isNumeric(event)" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN APORTES MUNICIPALES</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-4">
                                                                <div class="input-group" id="cuenta_destino" data-toogle="tooltip" title="CUENTA DESTINO">
                                                                    <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="cuenta_destino" value="<?php echo $row_alcaldia['CUENTA_DESTINO'] ?>" maxlength="20" placeholder="Cuenta Destino" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuenta_destino" value="<?php echo $row_alcaldia['CUENTA_DESTINO'] ?>" placeholder="Cuenta Destino" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuenta_destino" value="" maxlength="20" placeholder="Cuenta Destino" required onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="banco_destino" name="banco_destino" value="<?php echo $row_alcaldia['BANCO_DESTINO']; ?>" maxlength="100" placeholder="Banco Destino" data-toogle="tooltip" title="BANCO DESTINO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="banco_destino" name="banco_destino" readonly="readonly" placeholder="Banco Destino" value="<?php echo $row_alcaldia['BANCO_DESTINO']; ?>" data-toogle="tooltip" title="BANCO DESTINO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="banco_destino" name="banco_destino" maxlength="100" placeholder="Banco Destino" data-toogle="tooltip" title="BANCO DESTINO" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="concepto_aporte" name="concepto_aporte" value="<?php echo $row_alcaldia['CONCEPTO_APORTE']; ?>" maxlength="180" placeholder="Concepto" data-toogle="tooltip" title="CONCEPTO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="concepto_aporte" name="concepto_aporte" readonly="readonly" placeholder="Concepto" value="<?php echo $row_alcaldia['CONCEPTO_APORTE']; ?>" data-toogle="tooltip" title="CONCEPTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="concepto_aporte" name="concepto_aporte" maxlength="180" placeholder="Concepto" data-toogle="tooltip" title="CONCEPTO" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_concepto" data-toogle="tooltip" title="VALOR CONCEPTO">
                                                                    <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_concepto" value="<?php echo $row_alcaldia['VALOR_CONCEPTO'] ?>" maxlength="25" placeholder="Valor Concepto" onblur="return convertValorConcepto()" onchange="return convertValorConcepto()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_concepto" value="<?php echo $row_alcaldia['VALOR_CONCEPTO'] ?>" placeholder="Valor Concepto" onblur="return convertValorConcepto()" onchange="return convertValorConcepto()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_concepto" value="" maxlength="25" placeholder="Valor Concepto" required="required" onblur="return convertValorConcepto()" onchange="return convertValorConcepto()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_cartera" data-toogle="tooltip" title="VALOR CARTERA">
                                                                    <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="<?php echo $row_alcaldia['VALOR_CARTERA'] ?>" maxlength="25" placeholder="Valor Cartera" onblur="return convertValorCartera()" onchange="return convertValorCartera()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="<?php echo $row_alcaldia['VALOR_CARTERA'] ?>" placeholder="Valor Cartera" onblur="return convertValorCartera()" onchange="return convertValorCartera()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="" maxlength="25" placeholder="Valor Cartera" required="required" onblur="return convertValorCartera()" onchange="return convertValorCartera()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <?php
                                                                    if (isset($_GET['id_alcaldia_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_alcaldia" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Alcaldía</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Alcaldias.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_alcaldia_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_alcaldia" type="button" data-toggle="modal" data-target="#modalEliminarAlcaldia"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Alcaldía</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Alcaldias.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_alcaldia" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Alcaldía</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldAlcaldia();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script>
        function resetFieldAlcaldia() {
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#departamento").focus();
        }
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function convertValorCartera() {
            var valor_cartera = $("input[name=valor_cartera]").val();
            var replaceValor_cartera = valor_cartera.replace(/,/g, '');
            var newValor_cartera = replaceValor_cartera.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_cartera]").val(newValor_cartera);
        }
        function convertValorConcepto() {
            var valor_concepto = $("input[name=valor_concepto]").val();
            var replaceValor_concepto = valor_concepto.replace(/,/g, '');
            var newValor_concepto = replaceValor_concepto.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_concepto]").val(newValor_concepto);
        }
        //POPUPS
        function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
            if (id_consulta == 1) {
                $("#id_departamento").val(id_departamento);
                $("#departamento").val(departamento);
                $("#id_municipio").val("");
                $("#municipio").val("");
                $("#municipio").focus();
            }
        }
        function tipoDepartamento(id_consulta) {
            window.open("Combos/Tipo_Departamento_Visita.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio").val(id_municipio);
                $("#municipio").val(municipio);
                $("#nombre_alcaldia").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio_Visita.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_alcaldia").focus();
            var id_alcaldia_editar = $("#id_alcaldia_editar_hidden").val();
            var id_alcaldia_eliminar = $("#id_alcaldia_eliminar_hidden").val();
            if (id_alcaldia_editar != undefined) {
                convertValorConcepto();
                convertValorCartera();
                $(".nav-pills a[href='#crear_alcaldia_tab']").tab("show");
                $(".nav-pills a[href='#crear_alcaldia_tab']").text("Actualizar Alcaldía");
            } else {
                if (id_alcaldia_eliminar != undefined) {
                    convertValorConcepto();
                    convertValorCartera();
                    $(".nav-pills a[href='#crear_alcaldia_tab']").tab("show");
                    $(".nav-pills a[href='#crear_alcaldia_tab']").text("Eliminar Alcaldía");
                }
            }
            $("#buscar_alcaldia").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_alcaldia;
                    if ($(this).val() == "") {
                        busqueda_alcaldia = " ";
                    } else {
                        busqueda_alcaldia = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Alcaldia.php",
                        dataType: "json",
                        data: "sw=1&busqueda_alcaldia="+busqueda_alcaldia,
                        success: function(data) {
                            $("#pagination-alcaldia").twbsPagination('destroy');
                            $("#pagination-alcaldia").twbsPagination({
                                totalPages: data[0],
                                visiblePages: 15,
                                first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                                prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                                next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                                last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                                onPageClick: function (event, page) {
                                    $("#loading-spinner").css('display', 'block');
                                    $.ajax({
                                        type: "POST",
                                        url: "Modelo/Cargar_Admin_Alcaldia.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_alcaldia="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_alcaldia").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_alcaldia").on("shown.bs.tab", function() {
                $("#buscar_alcaldia").focus();
            });
            $("#tab_crear_alcaldia").on("shown.bs.tab", function() {
                $("#departamento").focus();
            });
            $("#tab_info_alcaldia").on("click", function() {
                $("#buscar_alcaldia").focus();
            });
            $("#tab_crear_alcaldia").on("click", function() {
                $("#departamento").focus();
            });
            if (id_alcaldia_editar == undefined && id_alcaldia_eliminar == undefined) {
                $("#btn_crear_alcaldia").click(function() {
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    var nombre_alcaldia = $("#nombre_alcaldia").val().toUpperCase();
                    var nit_alcaldia = $("#nit_alcaldia").val();
                    var cuenta_fiducia = $("input[name=cuenta_fiducia]").val();
                    var banco_fiducia = $("#banco_fiducia").val().toUpperCase();
                    var nombre_fiducia = $("#nombre_fiducia").val().toUpperCase();
                    var nit_fiducia = $("#nit_fiducia").val();
                    var direccion_alcaldia = $("#direccion_alcaldia").val().toUpperCase();
                    var telefono_alcaldia = $("#telefono_alcaldia").val();
                    var correo_electronico = $("#correo_electronico").val();
                    var nombre_sec_hacienda = $("#nombre_sec_hacienda").val().toUpperCase();
                    var cuenta_destino = $("input[name=cuenta_destino]").val();
                    var banco_destino = $("#banco_destino").val().toUpperCase();
                    var concepto_aporte = $("#concepto_aporte").val().toUpperCase();
                    var valor_concepto = $("input[name=valor_concepto]").val();
                    if (valor_concepto != "") {
                        valor_concepto = valor_concepto.replace(/,/g, "");
                    }
                    var valor_cartera = $("input[name=valor_cartera]").val();
                    if (valor_cartera != "") {
                        valor_cartera = valor_cartera.replace(/,/g, "");
                    }
                    if (departamento.length == 0) {
                        $("#departamento").focus();
                        return false;
                    }
                    if (municipio.length == 0) {
                        $("#municipio").focus();
                        return false;
                    }
                    if (nombre_alcaldia.length == 0) {
                        $("#nombre_alcaldia").focus();
                        return false;
                    }
                    if (nit_alcaldia.length == 0) {
                        $("#nit_alcaldia").focus();
                        return false;
                    }
                    if (cuenta_fiducia.length == 0) {
                        $("input[name=cuenta_fiducia]").focus();
                        return false;
                    }
                    if (banco_fiducia.length == 0) {
                        $("#banco_fiducia").focus();
                        return false;
                    }
                    if (nombre_fiducia.length == 0) {
                        $("#nombre_fiducia").focus();
                        return false;
                    }
                    if (nit_fiducia.length == 0) {
                        $("#nit_fiducia").focus();
                        return false;
                    }
                    if (correo_electronico.length == 0) {
                        $("#correo_electronico").focus();
                        return false;
                    }
                    if (direccion_alcaldia.length == 0) {
                        $("#direccion_alcaldia").focus();
                        return false;
                    }
                    if (telefono_alcaldia.length == 0) {
                        $("#telefono_alcaldia").focus();
                        return false;
                    }
                    if (nombre_sec_hacienda.length == 0) {
                        $("#nombre_sec_hacienda").focus();
                        return false;
                    }
                    if (cuenta_destino.length == 0) {
                        $("input[name=cuenta_destino]").focus();
                        return false;
                    }
                    if (banco_destino.length == 0) {
                        $("#banco_destino").focus();
                        return false;
                    }
                    if (concepto_aporte.length == 0) {
                        $("#concepto_aporte").focus();
                        return false;
                    }
                    if (valor_concepto.length == 0) {
                        $("input[name=valor_concepto]").focus();
                        return false;
                    }
                    if (valor_cartera.length == 0) {
                        $("input[name=valor_cartera]").focus();
                        return false;
                    }
                    $("#btn_crear_alcaldia").attr("disabled", true);
                    $("#btn_crear_alcaldia").css("pointer-events", "none");
                    $("#btn_crear_alcaldia").html("Creando Alcaldía...");
                    $.ajax({
                        type: "POST",
                        data: "departamento="+departamento+"&municipio="+municipio+
                              "&nombre_alcaldia="+nombre_alcaldia+
                              "&nit_alcaldia="+nit_alcaldia+
                              "&cuenta_fiducia="+cuenta_fiducia+
                              "&banco_fiducia="+banco_fiducia+
                              "&nombre_fiducia="+nombre_fiducia+
                              "&nit_fiducia="+nit_fiducia+
                              "&correo_electronico="+correo_electronico+
                              "&direccion_alcaldia="+direccion_alcaldia+
                              "&telefono_alcaldia="+telefono_alcaldia+
                              "&nombre_sec_hacienda="+nombre_sec_hacienda+
                              "&cuenta_destino="+cuenta_destino+
                              "&banco_destino="+banco_destino+
                              "&concepto_aporte="+concepto_aporte+
                              "&valor_concepto="+valor_concepto+
                              "&valor_cartera="+valor_cartera,
                        url: "Modelo/Crear_Admin_Alcaldia.php",
                        success: function(data) {
                            document.location.href = 'Admin_Alcaldias.php';
                        }
                    });
                });
            }
            $("#eliminar_alcaldia").click(function() {
                var id_alcaldia_eliminar = $("#id_alcaldia_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Alcaldia_Registros.php",
                    data: "id_alcaldia_eliminar="+id_alcaldia_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_alcaldia").submit();
                        } else {
                            $("#modalEliminarAlcaldiaError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Alcaldia.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-alcaldia").twbsPagination('destroy');
                    $("#pagination-alcaldia").twbsPagination({
                        totalPages: data[0],
                        visiblePages: 15,
                        first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                        prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                        next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                        last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                        onPageClick: function (event, page) {
                            $("#loading-spinner").css('display', 'block');
                            $.ajax({
                                type: "POST",
                                url: "Modelo/Cargar_Admin_Alcaldia.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_alcaldia").html(data[0]);
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=text][name=departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=nombre_alcaldia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nit_alcaldia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('#cuenta_fiducia').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=banco_fiducia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_fiducia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nit_fiducia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=direccion_alcaldia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=telefono_alcaldia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=correo_electronico]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_sec_hacienda]').tooltip({
                container: "body",
                placement: "right"
            });
            $('#cuenta_destino').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=banco_destino]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=concepto_aporte]').tooltip({
                container: "body",
                placement: "top"
            });
            $('#valor_concepto').tooltip({
                container: "body",
                placement: "top"
            });
            $('#valor_cartera').tooltip({
                container: "body",
                placement: "right"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_administrador").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>