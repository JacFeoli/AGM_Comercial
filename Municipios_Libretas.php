<?php
    session_start();
    require_once('Includes/Config.php');
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
        <title>AGM - Crear Municipios</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
        <style type="text/css">
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }
            .inputfile + label {
                max-width: 80%;
                /*font-size: 1.25rem;*/
                /* 20px */
                font-weight: normal;
                font-size: 12px;
                font-family: 'Cabin';
                text-overflow: ellipsis;
                white-space: nowrap;
                cursor: pointer;
                display: inline-block;
                overflow: hidden;
                padding: 0.625rem 1.25rem;
                border-radius: 3px;
                /* 10px 20px */
            }
            .inputfile:focus + label,
            .inputfile.has-focus + label {
                outline: 1px dotted #000;
                outline: -webkit-focus-ring-color auto 5px;
            }
            .inputfile-1 + label {
                border-color: #2C592C;
                color: #FFFFFF;
                background-image: linear-gradient(#6CBF6C, #408040);
                box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
            }
            .inputfile-1:focus + label,
            .inputfile-1.has-focus + label,
            .inputfile-1 + label:hover {
                border-color: #2C592C;
                background-image: linear-gradient(#61AB61, #397339);
                box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
            }
        </style>
    </head>
    <!--Upload Modal-->
    <div class="modal fade" id="modalUpload" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Carga Exitosa</h4>
                </div>
                <div class="modal-body">
                    <p>El Archivo, se cargo de forma Exitosa.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Upload Modal-->
    <!--Municipio Libreta Modal-->
    <div class="modal fade" id="modalMunicipioLibreta" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Municipio Existente</h4>
                </div>
                <div class="modal-body">
                    <p>El Municipio que intenta crear, ya se encuentra Creado. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Municipio Libreta Modal-->
    <!--Eliminar Municipio Modal-->
    <div class="modal fade" id="modalEliminarMunicipio" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Municipio</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Municipio?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_municipio" name="eliminar_municipio"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Municipio Modal-->
    <!--Eliminar Municipio Error-->
    <div class="modal fade" id="modalEliminarMunicipioError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Municipio</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Municipio, ya que existen Registros creados con ésta. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Municipio Error-->
    <!--Guardar Histórico Municipio Modal-->
    <div class="modal fade" id="modalHistoricoMunicipio" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Crear Histórico Municipio</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea guardar la información del Periodo Actual?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-sm font background-success cursor" id="si_guardar" data-dismiss="modal"><i style="font-size: 14px;" class="fas fa-check"></i>&nbsp;&nbsp;Guardar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="no_guardar" data-dismiss="modal"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;No Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Guardar Histórico Municipio Modal-->
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Home.php"); ?>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="leftcol">
                                            <div class="page-wrapper chiller-theme toggled">
                                                <nav id="sidebar" class="sidebar-wrapper">
                                                    <div class="sidebar-content">
                                                        <div class="sidebar-menu">
                                                            <ul>
                                                                <li class="header-menu">
                                                                    <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                                                </li>
                                                                <li class="sidebar-dropdown active">
                                                                    <a href="#">
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-globe-americas"></i>
                                                                        <span>Visitas</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Municipios_Libretas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-edit"></i>
                                                                                    <span>Municipios</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Bitacora_Acuerdos.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-book"></i>
                                                                                    <span>Bitacora / Acuerdos</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Reportes_Bitacora.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-chart-pie"></i>
                                                                                    <span>Reportes</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="rightcol">
                                            <h1>Municipios</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_municipio_tab" id="tab_info_municipio" aria-controls="informacion_municipio_tab" role="tab" data-toggle="tab">Información Municipios</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_municipio_tab" id="tab_crear_municipio" aria-controls="crear_municipio_tab" role="tab" data-toggle="tab">Crear Municipio</a>
                                                </li>
                                                <?php
                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                        <li role="presentation">
                                                            <a href="#cargar_acuerdo_municipio_tab" id="tab_cargar_acuerdo_municipio" aria-controls="cargar_acuerdo_municipio_tab" role="tab" data-toggle="tab">Cargar Acuerdo Municipio</a>
                                                        </li>
                                                    <?php
                                                    }
                                                ?>
                                                <?php
                                                    if (isset($_GET['id_municipio_libreta_historico'])) { ?>
                                                        <li role="presentation">
                                                            <a href="#historico_municipio_tab" id="tab_historico_municipio" aria-controls="historico_municipio_tab" role="tab" data-toggle="tab">Historico Municipio</a>
                                                        </li>
                                                    <?php
                                                    }
                                                ?>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_municipio_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Municipio" name="buscar_municipio" id="buscar_municipio" />
                                                    <br />
                                                    <?php
                                                        $query_select_municipios_libreta = "SELECT * FROM municipios_libreta_2 ORDER BY ID_DEPARTAMENTO, ID_MUNICIPIO";
                                                        $sql_municipios_libreta = mysqli_query($connection, $query_select_municipios_libreta);
                                                        if (mysqli_num_rows($sql_municipios_libreta) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=13%>DEPARTAMENTO</th>";
                                                                            echo "<th width=20%>MUNICIPIO</th>";
                                                                            echo "<th width=20%>CONCESIÓN</th>";
                                                                            echo "<th width=20%>EMPRESA</th>";
                                                                            echo "<th width=12%>CONTRATO</th>";
                                                                            echo "<th width=5%>HISTORICO</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_municipio_libreta'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Municipios Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-municipio_libreta"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_municipio_tab">
                                                    <?php
                                                        if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_municipio_libreta" name="crear_municipio_libreta" action="<?php echo "Modelo/Crear_Municipio_Libreta.php?editar=" . $_GET['id_municipio_libreta_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_municipio_libreta = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 WHERE ID_MUNICIPIO_LIBRETA = " . $_GET['id_municipio_libreta_editar']);
                                                            $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                                        ?>
                                                            <input type="hidden" id="id_municipio_libreta_editar_hidden" name="id_municipio_libreta_editar_hidden" value="<?php echo $row_municipio_libreta['ID_MUNICIPIO_LIBRETA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_municipio_libreta" name="crear_municipio_libreta" action="<?php echo "Modelo/Crear_Municipio_Libreta.php?eliminar=" . $_GET['id_municipio_libreta_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_municipio_libreta = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 WHERE ID_MUNICIPIO_LIBRETA = " . $_GET['id_municipio_libreta_eliminar']);
                                                                $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                                            ?>
                                                                <input type="hidden" id="id_municipio_libreta_eliminar_hidden" name="id_municipio_libreta_eliminar_hidden" value="<?php echo $row_municipio_libreta['ID_MUNICIPIO_LIBRETA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_municipio_libreta" name="crear_municipio_libreta" action="<?php echo "Modelo/Crear_Municipio_Libreta.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
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
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . " AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . " AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
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
                                                            <div class="col-xs-4">
                                                                <div style="float: right;" class="btn-group" data-toggle="buttons">
                                                                    <?php
                                                                        if (isset($_GET['id_municipio_libreta_editar'])) {
                                                                            $query_select_info_historico = mysqli_query($connection, "SELECT ID_HISTORICO_MUNICIPIO_LIBRETA "
                                                                                                                                   . "  FROM historico_municipios_libreta_2 "
                                                                                                                                   . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                                                                   . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO'] . ""
                                                                                                                                   . "   AND NOMBRE_ALCALDE = '" . $row_municipio_libreta['NOMBRE_ALCALDE'] . "'");
                                                                            if (mysqli_num_rows($query_select_info_historico) == 0) { ?>
                                                                                <label class="btn btn-primary cursor font background" name="label_periodo_nuevo_si" data-toogle="tooltip" title="PERIODO NUEVO - SI">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="periodo_nuevo_si" name="periodo_nuevo" value="1" />Periodo Nuevo - Si
                                                                                </label>
                                                                                <!--<label class="btn btn-primary cursor font background" name="label_periodo_nuevo_no" data-toogle="tooltip" title="PERIODO NUEVO - NO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="periodo_nuevo_si" name="periodo_nuevo" value="0" required />Periodo Nuevo - No
                                                                                </label>-->
                                                                            <?php
                                                                            } else { ?>
                                                                                <span style="font-size: 12px; background-image: linear-gradient(#61AB61, #397339);" class="label label-success">Periodo Guardado</span>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_alcalde" name="nombre_alcalde" value="<?php echo $row_municipio_libreta['NOMBRE_ALCALDE']; ?>" maxlength="100" placeholder="Nombre Alcalde" data-toogle="tooltip" title="NOMBRE ALCALDE" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_alcalde" name="nombre_alcalde" readonly="readonly" placeholder="Nombre Alcalde" value="<?php echo $row_municipio_libreta['NOMBRE_ALCALDE']; ?>" data-toogle="tooltip" title="NOMBRE ALCALDE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_alcalde" name="nombre_alcalde" maxlength="100" placeholder="Nombre Alcalde" data-toogle="tooltip" title="NOMBRE ALCALDE" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_sec_hacienda" name="nombre_sec_hacienda" value="<?php echo $row_municipio_libreta['NOMBRE_SEC_HACIENDA']; ?>" maxlength="100" placeholder="Nombre Sec. Hacienda" data-toogle="tooltip" title="NOMBRE SEC. HACIENDA" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_sec_hacienda" name="nombre_sec_hacienda" readonly="readonly" placeholder="Nombre Sec. Hacienda" value="<?php echo $row_municipio_libreta['NOMBRE_SEC_HACIENDA']; ?>" data-toogle="tooltip" title="NOMBRE SEC. HACIENDA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_sec_hacienda" name="nombre_sec_hacienda" maxlength="100" placeholder="Nombre Sec. Hacienda" data-toogle="tooltip" title="NOMBRE SEC. HACIENDA" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_interventor" name="nombre_interventor" value="<?php echo $row_municipio_libreta['NOMBRE_INTERVENTOR']; ?>" maxlength="100" placeholder="Nombre Interventor" data-toogle="tooltip" title="NOMBRE INTERVENTOR" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_interventor" name="nombre_interventor" readonly="readonly" placeholder="Nombre Interventor" value="<?php echo $row_municipio_libreta['NOMBRE_INTERVENTOR']; ?>" data-toogle="tooltip" title="NOMBRE INTERVENTOR" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_interventor" name="nombre_interventor" maxlength="100" placeholder="Nombre Interventor" data-toogle="tooltip" title="NOMBRE INTERVENTOR" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="direccion_alcaldia" name="direccion_alcaldia" value="<?php echo $row_municipio_libreta['DIRECCION_ALCALDIA']; ?>" maxlength="100" placeholder="Dirección Alcaldía" data-toogle="tooltip" title="DIRECCIÓN ALCALDÍA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_alcaldia" name="direccion_alcaldia" readonly="readonly" placeholder="Dirección Alcaldía" value="<?php echo $row_municipio_libreta['DIRECCION_ALCALDIA']; ?>" data-toogle="tooltip" title="DIRECCIÓN ALCALDÍA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_alcaldia" name="direccion_alcaldia" maxlength="100" placeholder="Dirección Alcaldía" data-toogle="tooltip" title="DIRECCIÓN ALCALDÍA" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" value="<?php echo $row_municipio_libreta['CORREO_ELECTRONICO']; ?>" maxlength="100" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" readonly="readonly" placeholder="Correo Electrónico" value="<?php echo $row_municipio_libreta['CORREO_ELECTRONICO']; ?>" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
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
                                                            <div class="col-xs-10">
                                                                <div class="btn-group" data-toggle="buttons">
                                                                    <?php
                                                                        if (isset($_GET['id_municipio_libreta_editar']) || isset($_GET['id_municipio_libreta_eliminar'])) {
                                                                            if ($row_municipio_libreta['ID_TIPO_CLIENTE'] == 1) { ?>
                                                                                <label class="btn btn-primary cursor font background active" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - REGULADO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" checked="checked" value="1" required />Tipo Cliente - Regulado
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - NO REGULADO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="2" required />Tipo Cliente - No Regulado
                                                                                </label>
                                                                            <?php
                                                                            } else { ?>
                                                                                <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - REGULADO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="1" required />Tipo Cliente - Regulado
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background active" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - NO REGULADO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" checked="checked" value="2" required />Tipo Cliente - No Regulado
                                                                                </label>
                                                                            <?php
                                                                            }
                                                                        } else { ?>
                                                                            <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - REGULADO">
                                                                                <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="1" required />Tipo Cliente - Regulado
                                                                            </label>
                                                                            <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - NO REGULADO">
                                                                                <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="2" required />Tipo Cliente - No Regulado
                                                                            </label>
                                                                        <?php
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
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="clase_contrato" name="clase_contrato" data-toggle="tooltip" title="CLASE CONTRATO" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="clase_contrato" name="clase_contrato" disabled="disabled" data-toggle="tooltip" title="CLASE CONTRATO" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="clase_contrato" name="clase_contrato" data-toggle="tooltip" title="CLASE CONTRATO" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                            <option value="" selected="selected">-</option>
                                                                            <option value="1">CONCESIÓN</option>
                                                                            <option value="2">OPERACIÓN</option>
                                                                            <?php
                                                                                if (isset($_GET['id_municipio_libreta_editar']) || isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                                    <input type="hidden" id="clase_contrato_hidden" name="clase_contrato_hidden" value="<?php echo $row_municipio_libreta['CLASE_CONTRATO']; ?>" />
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) {
                                                                        $query_select_concesion = mysqli_query($connection, "SELECT * FROM concesiones_2 WHERE ID_CONCESION = " . $row_municipio_libreta['ID_CONCESION']);
                                                                        $row_concesion = mysqli_fetch_array($query_select_concesion);
                                                                    ?>
                                                                        <input type="hidden" id="id_concesion" name="id_concesion" value="<?php echo $row_concesion['ID_CONCESION']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="concesion" name="concesion" value="<?php echo $row_concesion['NOMBRE']; ?>" placeholder="Sociedad" required="required" data-toggle="tooltip" readonly="readonly" title="SOCIEDAD" onclick="tipoConcesion()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) {
                                                                            $query_select_concesion = mysqli_query($connection, "SELECT * FROM concesiones_2 WHERE ID_CONCESION = " . $row_municipio_libreta['ID_CONCESION']);
                                                                            $row_concesion = mysqli_fetch_array($query_select_concesion);
                                                                        ?>
                                                                            <input type="hidden" id="id_concesion" name="id_concesion" value="<?php echo $row_concesion['ID_CONCESION']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="concesion" name="concesion" value="<?php echo $row_concesion['NOMBRE']; ?>" placeholder="Sociedad" data-toggle="tooltip" readonly="readonly" title="SOCIEDAD" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_concesion" name="id_concesion" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="concesion" name="concesion" placeholder="Sociedad" required="required" data-toggle="tooltip" readonly="readonly" title="SOCIEDAD" onclick="tipoConcesion()" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) {
                                                                        $query_select_empresa = mysqli_query($connection, "SELECT * FROM empresas_2 WHERE ID_EMPRESA = " . $row_municipio_libreta['ID_EMPRESA']);
                                                                        $row_empresa = mysqli_fetch_array($query_select_empresa);
                                                                    ?>
                                                                        <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo $row_empresa['ID_EMPRESA']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="empresa" name="empresa" value="<?php echo $row_empresa['NOMBRE']; ?>" placeholder="Empresa" required="required" data-toggle="tooltip" readonly="readonly" title="EMPRESA" onclick="tipoEmpresa()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) {
                                                                            $query_select_empresa = mysqli_query($connection, "SELECT * FROM empresas_2 WHERE ID_EMPRESA = " . $row_municipio_libreta['ID_EMPRESA']);
                                                                            $row_empresa = mysqli_fetch_array($query_select_empresa);
                                                                        ?>
                                                                            <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo $row_empresa['ID_EMPRESA']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="empresa" name="empresa" value="<?php echo $row_empresa['NOMBRE']; ?>" placeholder="Empresa" data-toggle="tooltip" readonly="readonly" title="EMPRESA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_empresa" name="id_empresa" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="empresa" name="empresa" placeholder="Empresa" required="required" data-toggle="tooltip" readonly="readonly" title="EMPRESA" onclick="tipoEmpresa()" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="direccion_agm_municipio" name="direccion_agm_municipio" value="<?php echo $row_municipio_libreta['DIRECCION_AGM_MUNICIPIO']; ?>" maxlength="100" placeholder="Dirección AGM Municipio" data-toogle="tooltip" title="DIRECCIÓN AGM MUNICIPIO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_agm_municipio" name="direccion_agm_municipio" readonly="readonly" placeholder="Dirección AGM Municipio" value="<?php echo $row_municipio_libreta['DIRECCION_AGM_MUNICIPIO']; ?>" data-toogle="tooltip" title="DIRECCIÓN AGM MUNICIPIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_agm_municipio" name="direccion_agm_municipio" maxlength="100" placeholder="Dirección AGM Municipio" data-toogle="tooltip" title="DIRECCIÓN AGM MUNICIPIO" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="direccion_agm_principal" name="direccion_agm_principal" value="<?php echo $row_municipio_libreta['DIRECCION_AGM_PRINCIPAL']; ?>" maxlength="100" placeholder="Dirección AGM Principal" data-toogle="tooltip" title="DIRECCIÓN AGM PRINCIPAL" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_agm_principal" name="direccion_agm_principal" readonly="readonly" placeholder="Dirección AGM Principal" value="<?php echo $row_municipio_libreta['DIRECCION_AGM_PRINCIPAL']; ?>" data-toogle="tooltip" title="DIRECCIÓN AGM PRINCIPAL" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_agm_principal" name="direccion_agm_principal" maxlength="100" placeholder="Dirección AGM Principal" data-toogle="tooltip" title="DIRECCIÓN AGM PRINCIPAL" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="identificacion_rep_legal" name="identificacion_rep_legal" value="<?php echo $row_municipio_libreta['IDENTIFICACION_REP_LEGAL'] ?>" maxlength="25" placeholder="Identificación Rep. Legal" data-toggle="tooltip" title="IDENTIFICACIÓN REP. LEGAL" onkeypress="return isNumeric(event)" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="identificacion_rep_legal" name="identificacion_rep_legal" readonly="readonly" placeholder="Identificación Rep. Legal" value="<?php echo $row_municipio_libreta['IDENTIFICACION_REP_LEGAL'] ?>" data-toggle="tooltip" title="IDENTIFICACIÓN REP. LEGAL" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="identificacion_rep_legal" name="identificacion_rep_legal" maxlength="20" placeholder="Identificación Rep. Legal" data-toggle="tooltip" title="IDENTIFICACIÓN REP. LEGAL" onkeypress="return isNumeric(event)" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_rep_legal" name="nombre_rep_legal" value="<?php echo $row_municipio_libreta['REPR_LEGAL']; ?>" maxlength="100" placeholder="Nombre Rep. Legal" data-toogle="tooltip" title="NOMBRE REP. LEGAL" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_rep_legal" name="nombre_rep_legal" readonly="readonly" placeholder="Nombre Rep. Legal" value="<?php echo $row_municipio_libreta['REPR_LEGAL']; ?>" data-toogle="tooltip" title="NOMBRE REP. LEGAL" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_rep_legal" name="nombre_rep_legal" maxlength="100" placeholder="Nombre Rep. Legal" data-toogle="tooltip" title="NOMBRE REP. LEGAL" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) {
                                                                        $query_select_operador = mysqli_query($connection, "SELECT * FROM operadores_2 WHERE ID_OPERADOR = " . $row_municipio_libreta['ID_OPERADOR']);
                                                                        $row_operador = mysqli_fetch_array($query_select_operador);
                                                                    ?>
                                                                        <input type="hidden" id="id_operador" name="id_operador" value="<?php echo $row_operador['ID_OPERADOR']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="operador" name="operador" value="<?php echo $row_operador['NOMBRE']; ?>" placeholder="Operador" required="required" data-toggle="tooltip" readonly="readonly" title="OPERADOR" onclick="tipoOperador()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) {
                                                                            $query_select_operador = mysqli_query($connection, "SELECT * FROM operadores_2 WHERE ID_OPERADOR = " . $row_municipio_libreta['ID_OPERADOR']);
                                                                            $row_operador = mysqli_fetch_array($query_select_operador);
                                                                        ?>
                                                                            <input type="hidden" id="id_operador" name="id_operador" value="<?php echo $row_operador['ID_CONCESION']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="operador" name="operador" value="<?php echo $row_operador['NOMBRE']; ?>" placeholder="Operador" data-toggle="tooltip" readonly="readonly" title="OPERADOR" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_operador" name="id_operador" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="operador" name="operador" placeholder="Operador" required="required" data-toggle="tooltip" readonly="readonly" title="OPERADOR" onclick="tipoOperador()" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="no_contrato_concesion" name="no_contrato_concesion" value="<?php echo $row_municipio_libreta['NO_CONTRATO_CONCESION']; ?>" maxlength="30" placeholder="No. Contrato Conc." data-toogle="tooltip" title="NO. CONTRATO CONC." required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="no_contrato_concesion" name="no_contrato_concesion" readonly="readonly" placeholder="No. Contrato Conc." value="<?php echo $row_municipio_libreta['NO_CONTRATO_CONCESION']; ?>" data-toogle="tooltip" title="NO. CONTRATO CONC." />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="no_contrato_concesion" name="no_contrato_concesion" maxlength="30" placeholder="No. Contrato Conc." data-toogle="tooltip" title="NO. CONTRATO CONC." required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_firma_contrato" data-toogle="tooltip" title="FECHA FIRMA CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_firma_contrato" value="<?php echo $row_municipio_libreta['FECHA_FIRMA_CONTRATO'] ?>" placeholder="Fecha Firma Contrato" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_firma_contrato" value="<?php echo $row_municipio_libreta['FECHA_FIRMA_CONTRATO'] ?>" placeholder="Fecha Firma Contrato" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_firma_contrato" value="" placeholder="Fecha Firma Contrato" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_inicio_contrato" data-toogle="tooltip" title="FECHA INICIO CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_inicio_contrato" value="<?php echo $row_municipio_libreta['FECHA_INICIO_CONTRATO'] ?>" placeholder="Fecha Inicio Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_inicio_contrato" value="<?php echo $row_municipio_libreta['FECHA_INICIO_CONTRATO'] ?>" placeholder="Fecha Inicio Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_inicio_contrato" value="" placeholder="Fecha Inicio Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_fin_contrato" data-toogle="tooltip" title="FECHA FIN CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_fin_contrato" value="<?php echo $row_municipio_libreta['FECHA_FIN_CONTRATO'] ?>" placeholder="Fecha Fin Contrato" onblur="return getDuracionContrato()" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_fin_contrato" value="<?php echo $row_municipio_libreta['FECHA_FIN_CONTRATO'] ?>" placeholder="Fecha Fin Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_fin_contrato" value="" placeholder="Fecha Fin Contrato" onblur="return getDuracionContrato()" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="duracion_contrato" data-toogle="tooltip" title="DURACIÓN CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="duracion_contrato" value="<?php echo $row_municipio_libreta['DURACION_CONTRATO'] ?>" placeholder="Duración Contrato" readonly="readonly" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)"  />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="duracion_contrato" value="<?php echo $row_municipio_libreta['DURACION_CONTRATO'] ?>" placeholder="Duración Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)"  />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="duracion_contrato" value="" placeholder="Duración Contrato" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)"  />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="otro_si_contrato" name="otro_si_contrato" value="<?php echo $row_municipio_libreta['OTRO_SI_CONTRATO']; ?>" maxlength="30" placeholder="Otro Sí Contrato" data-toogle="tooltip" title="OTRO SÍ CONTRATO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="otro_si_contrato" name="otro_si_contrato" readonly="readonly" placeholder="Otro Sí Contrato" value="<?php echo $row_municipio_libreta['OTRO_SI_CONTRATO']; ?>" data-toogle="tooltip" title="OTRO SÍ CONTRATO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="otro_si_contrato" name="otro_si_contrato" maxlength="30" placeholder="Otro Sí Contrato" data-toogle="tooltip" title="OTRO SÍ CONTRATO" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_otro_si_contrato" data-toogle="tooltip" title="FECHA OTRO SÍ CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_otro_si_contrato" value="<?php echo $row_municipio_libreta['FECHA_OTRO_SI_CONTRATO'] ?>" placeholder="Fecha Otro Sí Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_otro_si_contrato" value="<?php echo $row_municipio_libreta['FECHA_OTRO_SI_CONTRATO'] ?>" placeholder="Fecha Otro Sí Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_otro_si_contrato" value="" placeholder="Fecha Otro Sí Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="objeto_contrato" name="objeto_contrato" rows="4" placeholder="Objeto Contrato" data-toogle="tooltip" title="OBJETO CONTRATO"><?php echo trim($row_municipio_libreta['OBJETO_CONTRATO']); ?></textarea>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="objeto_contrato" name="objeto_contrato" rows="4" placeholder="Objeto Contrato" data-toogle="tooltip" readonly="readonly" title="OBJETO CONTRATO"><?php echo trim($row_municipio_libreta['OBJETO_CONTRATO']); ?></textarea>
                                                                            <?php
                                                                            } else { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="objeto_contrato" name="objeto_contrato" rows="4" placeholder="Objeto Contrato" data-toogle="tooltip" title="OBJETO CONTRATO"></textarea>
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
                                                        <div class="form-group">
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="contrato_energia" name="contrato_energia" value="<?php echo $row_municipio_libreta['CONTRATO_ENERGIA']; ?>" maxlength="50" placeholder="Contrato Energía" data-toogle="tooltip" title="CONTRATO ENERGÍA" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="contrato_energia" name="contrato_energia" readonly="readonly" placeholder="Contrato Energía" value="<?php echo $row_municipio_libreta['CONTRATO_ENERGIA']; ?>" data-toogle="tooltip" title="CONTRATO ENERGÍA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="contrato_energia" name="contrato_energia" maxlength="50" placeholder="Contrato Energía" data-toogle="tooltip" title="CONTRATO ENERGÍA" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="periodicidad_contrato" name="periodicidad_contrato" data-toggle="tooltip" title="PERIODICIDAD CONT. (FACT & RECA)" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="periodicidad_contrato" name="periodicidad_contrato" disabled="disabled" data-toggle="tooltip" title="PERIODICIDAD CONT. (FACT & RECA)" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="periodicidad_contrato" name="periodicidad_contrato" data-toggle="tooltip" title="PERIODICIDAD CONT. (FACT & RECA)" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                            <option value="" selected="selected">-</option>
                                                                            <option value="1">ANUAL</option>
                                                                            <option value="2">NO HAY CONTRATO</option>
                                                                            <option value="3">RENOVACIÓN AUTOMÁTICA</option>
                                                                            <?php
                                                                                if (isset($_GET['id_municipio_libreta_editar']) || isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                                    <input type="hidden" id="periodicidad_contrato_hidden" name="periodicidad_contrato_hidden" value="<?php echo $row_municipio_libreta['PERIODICIDAD_RENOVACION']; ?>" />
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                               <div class="input-group date" id="fecha_firma_contrato_f_r" data-toogle="tooltip" title="FECHA FIRMA CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_firma_contrato_f_r" value="<?php echo $row_municipio_libreta['FECHA_FIRMA_CONTRATOS_F_R'] ?>" placeholder="Fecha Firma Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_firma_contrato_f_r" value="<?php echo $row_municipio_libreta['FECHA_FIRMA_CONTRATOS_F_R'] ?>" placeholder="Fecha Firma Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_firma_contrato_f_r" value="" placeholder="Fecha Firma Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                               <div class="input-group date" id="fecha_ven_ini_contrato_f_r" data-toogle="tooltip" title="FECHA INICIAL CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_ven_ini_contrato_f_r" value="<?php echo $row_municipio_libreta['FECHA_VENC_CONTRATOS_INI_F_R'] ?>" placeholder="Fecha Inicial Contrato" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_ven_ini_contrato_f_r" value="<?php echo $row_municipio_libreta['FECHA_VENC_CONTRATOS_INI_F_R'] ?>" placeholder="Fecha Inicial Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_ven_ini_contrato_f_r" value="" placeholder="Fecha Inicial Contrato" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                               <div class="input-group date" id="fecha_ven_act_contrato_f_r" data-toogle="tooltip" title="FECHA FIN CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_ven_act_contrato_f_r" value="<?php echo $row_municipio_libreta['FECHA_VENC_CONTRATOS_ACT_F_R'] ?>" placeholder="Fecha Fin Contrato" onblur="return getDuracionContratoFR()" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_ven_act_contrato_f_r" value="<?php echo $row_municipio_libreta['FECHA_VENC_CONTRATOS_ACT_F_R'] ?>" placeholder="Fecha Fin Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_ven_act_contrato_f_r" value="" placeholder="Fecha Fin Contrato" onblur="return getDuracionContratoFR()" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="duracion_contrato_f_r" data-toogle="tooltip" title="DURACIÓN CONTRATO">
                                                                    <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-hashtag"></span>
                                                                        </span>
                                                                    <input type="text" class="form-control input-text input-sm" name="duracion_contrato_f_r" value="<?php echo $row_municipio_libreta['DURACION_CONTRATOS_F_R'] ?>" placeholder="Duración Contrato" readonly="readonly" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)"  />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="duracion_contrato_f_r" value="<?php echo $row_municipio_libreta['DURACION_CONTRATOS_F_R'] ?>" placeholder="Duración Contrato" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)"  />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="duracion_contrato_f_r" value="" placeholder="Duración Contrato" onkeypress="return isNothing(event)" readonly="readonly" onkeydown="return isNothing(event)"  />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-10">
                                                                <div class="btn-group" data-toggle="buttons">
                                                                    <?php
                                                                        if (isset($_GET['id_municipio_libreta_editar']) || isset($_GET['id_municipio_libreta_eliminar'])) {
                                                                            if ($row_municipio_libreta['EN_EJECUCION'] == 1) { ?>
                                                                                <label class="btn btn-primary cursor font background active" name="label_en_ejecucion" data-toogle="tooltip" title="EN EJECUCIÓN - SI">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="en_ejecucion" name="en_ejecucion" checked="checked" value="1" required />En Ejecución - Si
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background" name="label_en_ejecucion" data-toogle="tooltip" title="EN EJECUCIÓN - NO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="en_ejecucion" name="en_ejecucion" value="2" required />En Ejecución - No
                                                                                </label>
                                                                            <?php
                                                                            } else { ?>
                                                                                <label class="btn btn-primary cursor font background" name="label_en_ejecucion" data-toogle="tooltip" title="EN EJECUCIÓN - SI">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="en_ejecucion" name="en_ejecucion" value="1" required />En Ejecución - Si
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background active" name="label_en_ejecucion" data-toogle="tooltip" title="EN EJECUCIÓN - NO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="en_ejecucion" name="en_ejecucion" checked="checked" value="2" required />En Ejecución - No
                                                                                </label>
                                                                            <?php
                                                                            }
                                                                        } else { ?>
                                                                            <label class="btn btn-primary cursor font background" name="label_en_ejecucion" data-toogle="tooltip" title="EN EJECUCIÓN - SI">
                                                                                <input type="radio" class="form-control input-text input-sm" id="en_ejecucion" name="en_ejecucion" value="1" required />En Ejecución - Si
                                                                            </label>
                                                                            <label class="btn btn-primary cursor font background" name="label_en_ejecucion" data-toogle="tooltip" title="EN EJECUCIÓN - NO">
                                                                                <input type="radio" class="form-control input-text input-sm" id="en_ejecucion" name="en_ejecucion" value="2" required />En Ejecución - No
                                                                            </label>
                                                                        <?php
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
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="no_ultimo_acuerdo_tarifas" name="no_ultimo_acuerdo_tarifas" value="<?php echo $row_municipio_libreta['NO_ULTIMO_ACUERDO_TARIFAS']; ?>" maxlength="30" placeholder="No. Ult. Acuerdo Tar." data-toogle="tooltip" title="NO. ULT. ACUERDO TAR." />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="no_ultimo_acuerdo_tarifas" name="no_ultimo_acuerdo_tarifas" readonly="readonly" placeholder="No. Ult. Acuerdo Tar." value="<?php echo $row_municipio_libreta['NO_ULTIMO_ACUERDO_TARIFAS']; ?>" data-toogle="tooltip" title="NO. ULT. ACUERDO TAR." />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="no_ultimo_acuerdo_tarifas" name="no_ultimo_acuerdo_tarifas" maxlength="30" placeholder="No. Ult. Acuerdo Tar." data-toogle="tooltip" title="NO. ULT. ACUERDO TAR." />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="no_acuerdo_vigente" name="no_acuerdo_vigente" value="<?php echo $row_municipio_libreta['NO_ACUERDO_VIGENTE']; ?>" maxlength="30" placeholder="No. Acuerdo Vigente" data-toogle="tooltip" title="NO. ACUERDO VIGENTE" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="no_acuerdo_vigente" name="no_acuerdo_vigente" readonly="readonly" placeholder="No. Acuerdo Vigente" value="<?php echo $row_municipio_libreta['NO_ACUERDO_VIGENTE']; ?>" data-toogle="tooltip" title="NO. ACUERDO VIGENTE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="no_acuerdo_vigente" name="no_acuerdo_vigente" maxlength="30" placeholder="No. Acuerdo Vigente" data-toogle="tooltip" title="NO. ACUERDO VIGENTE" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="estatuto_tributario" name="estatuto_tributario" value="<?php echo $row_municipio_libreta['ESTATUTO_TRIBUTARIO']; ?>" maxlength="50" placeholder="Estatuto Tributario" data-toogle="tooltip" title="ESTATUTO TRIBUTARIO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="estatuto_tributario" name="estatuto_tributario" readonly="readonly" placeholder="Estatuto Tributario" value="<?php echo $row_municipio_libreta['ESTATUTO_TRIBUTARIO']; ?>" data-toogle="tooltip" title="ESTATUTO TRIBUTARIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="estatuto_tributario" name="estatuto_tributario" maxlength="50" placeholder="Estatuto Tributario" data-toogle="tooltip" title="ESTATUTO TRIBUTARIO" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"><?php echo trim($row_municipio_libreta['OBSERVACIONES']); ?></textarea>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES"><?php echo trim($row_municipio_libreta['OBSERVACIONES']); ?></textarea>
                                                                            <?php
                                                                            } else { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"></textarea>
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
                                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_municipio" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Municipio</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Municipios_Libretas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_libreta_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_municipio" type="button" data-toggle="modal" data-target="#modalEliminarMunicipio"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Municipio</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Municipios_Libretas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_municipio" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Municipio</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldMunicipio();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <?php
                                                    if (isset($_GET['id_municipio_libreta_editar'])) { ?>
                                                        <div role="tabpanel" class="tab-pane fade" id="cargar_acuerdo_municipio_tab">
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_acuerdo_municipio" name="cargar_acuerdo_municipio" action="Modelo/Subir_Archivos.php?archivo=acuerdo" method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="id_tabla_archivo" id="id_tabla_archivo" value="<?php echo $_GET['id_municipio_libreta_editar']; ?>" />
                                                                <div class="form-group">
                                                                    <div class="col-xs-3">
                                                                        <div class="styled-select">
                                                                            <select class="form-control input-text input-sm" id="estado_archivo_acuerdo" name="estado_archivo_acuerdo" data-toggle="tooltip" title="ESTADO ARCHIVO" required>
                                                                                <option value="">-</option>
                                                                                <option value="1">ACTIVO</option>
                                                                                <option value="2">INACTIVO</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 5px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="styled-select">
                                                                            <input type="file" name="files" id="files" class="inputfile inputfile-1" data-multiple-caption="{count} Archivos Seleccionados" />
                                                                            <label for="files"><i class="fas fa-folder-open"></i> <span>Seleccionar Archivo(s)&hellip;</span></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-3">
                                                                        <div class="styled-select">
                                                                            <button class="btn btn-primary btn-sm font background cursor" type="submit" name="upload_files" id="upload_files"><i style="font-size: 14px;" class="fas fa-upload"></i>&nbsp;&nbsp;Subir Acuerdo</button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-9">
                                                                        <div class="progress">
                                                                            <div id="progressBarFile" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <div style="margin-bottom: 15px; margin-top: 15px;" class="divider"></div>
                                                            <?php
                                                                function file_size($url) {
                                                                    $size = filesize($url);
                                                                    if ($size >= 1073741824) {
                                                                        $fileSize = round($size / 1024 / 1024 / 1024, 1) . ' GB';
                                                                    } elseif ($size >= 1048576) {
                                                                        $fileSize = round($size / 1024 / 1024, 1) . ' MB';
                                                                    } elseif ($size >= 1024) {
                                                                        $fileSize = round($size / 1024, 1) . ' KB';
                                                                    } else {
                                                                        $fileSize = $size . ' bytes';
                                                                    }
                                                                    return $fileSize;
                                                                }
                                                                $i = 0;
                                                                $estado = "";
                                                                $theList = "";
                                                                $tag = "";
                                                                $total_size = 0;
                                                                $total_files = 0;
                                                                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                                                         . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                                                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                                                                $path = "Files/" . $row_nombre_municipio['NOMBRE'] . "/";
                                                                $query_select_files_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                                                               . "  FROM municipios_libreta_archivos_2 "
                                                                                                                               . " WHERE ID_TABLA_MUNICIPIO = " . $_GET['id_municipio_libreta_editar']);
                                                                if (mysqli_num_rows($query_select_files_municipio_libreta) != 0) {
                                                                    while ($row_files = mysqli_fetch_assoc($query_select_files_municipio_libreta)) {
                                                                        $info_estado[] = $row_files['ESTADO_ACUERDO'];
                                                                        $info_municipio[] = $row_files['NOMBRE_ARCHIVO'];
                                                                        $info_id_municipio[] = $row_files['ID_TABLA'];
                                                                    }
                                                                    if ($handle = opendir($path)) {
                                                                        foreach (array_combine($info_id_municipio, $info_municipio) as $id_files => $files) {
                                                                            if (pathinfo($path . $files, PATHINFO_EXTENSION) == "pdf" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PDF") {
                                                                                $tag = "<i title='PDF' class='fas fa-file-pdf fa-lg' aria-hidden='true'></i>";
                                                                            }
                                                                            if (pathinfo($path . $files, PATHINFO_EXTENSION) == "png" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PNG" || pathinfo($path . $files, PATHINFO_EXTENSION) == "jpg" || pathinfo($path . $files, PATHINFO_EXTENSION) == "JPG") {
                                                                                $tag = "<i title='IMAGE' class='fas fa-file-image fa-lg' aria-hidden='true'></i>";
                                                                            }
                                                                            if (pathinfo($path . $files, PATHINFO_EXTENSION) == "zip" || pathinfo($path . $files, PATHINFO_EXTENSION) == "ZIP" || pathinfo($path . $files, PATHINFO_EXTENSION) == "rar" || pathinfo($path . $files, PATHINFO_EXTENSION) == "RAR") {
                                                                                $tag = "<i title='ZIP - RAR' class='fas fa-file-archive fa-lg' aria-hidden='true'></i>";
                                                                            }
                                                                            if (pathinfo($path . $files, PATHINFO_EXTENSION) == "doc" || pathinfo($path . $files, PATHINFO_EXTENSION) == "DOC" || pathinfo($path . $files, PATHINFO_EXTENSION) == "docx" || pathinfo($path . $files, PATHINFO_EXTENSION) == "DOCX") {
                                                                                $tag = "<i title='WORD' class='fas fa-file-word fa-lg' aria-hidden='true'></i>";
                                                                            }
                                                                            if (pathinfo($path . $files, PATHINFO_EXTENSION) == "xls" || pathinfo($path . $files, PATHINFO_EXTENSION) == "XLS" || pathinfo($path . $files, PATHINFO_EXTENSION) == "xlsx" || pathinfo($path . $files, PATHINFO_EXTENSION) == "XLSX") {
                                                                                $tag = "<i title='EXCEL' class='fas fa-file-excel fa-lg' aria-hidden='true'></i>";
                                                                            }
                                                                            if (pathinfo($path . $files, PATHINFO_EXTENSION) == "ppt" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PPT" || pathinfo($path . $files, PATHINFO_EXTENSION) == "pptx" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PPTX") {
                                                                                $tag = "<i title='POWERPOINT' class='fas fa-file-powerpoint fa-lg' aria-hidden='true'></i>";
                                                                            }
                                                                            if ($info_estado[$i] == 1) {
                                                                                $estado = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>A</b></span></td>";
                                                                            } else {
                                                                                $estado = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>IN</b></span></td>";
                                                                            }
                                                                            $theList .= "<tr>"
                                                                                      . $estado
                                                                                      . "   <td style='vertical-align: middle;'><a href='" . $path . $files . "' target='_blank' title='" . $files . "'>" . $files . "</a></td>"
                                                                                      . "   <td style='vertical-align: middle;'>" . file_size($path . $files) . "</td>"
                                                                                      . "   <td style='vertical-align: middle;'>" . $tag . " - " . strtoupper(pathinfo($path . $files, PATHINFO_EXTENSION)) . "</td>"
                                                                                      . "   <td style='vertical-align: middle;'><a href='Modelo/Eliminar_Archivos.php?file=" . $files . "&id_tabla_archivo=" . $_GET['id_municipio_libreta_editar'] . "&file_id=" . $id_files . "&archivo=acuerdo'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16' /></button></a></td>"
                                                                                      . "</tr>";
                                                                            $i = $i + 1;
                                                                        }
                                                                        closedir($handle);
                                                                    }
                                                                }
                                                            ?>
                                                            <div class="table-responsive">
                                                                <table class="table table-condensed table-hover">
                                                                    <thead>
                                                                        <th style="width: 12%;">ESTADO</th>
                                                                        <th style="width: 40%;">NOMBRE ARCHIVO</th>
                                                                        <th style="width: 12%;">TAMAÑO</th>
                                                                        <th style="width: 12%;">EXTENSIÓN</th>
                                                                        <th style='width: 5%;'>ELIMINAR</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php echo $theList; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <p></p>
                                                            <span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>A</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ACTIVO.</span>
                                                            &nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>IN</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = INACTIVO.</span>
                                                        </div>
                                                    <?php
                                                    }
                                                ?>
                                                <div role="tabpanel" class="tab-pane fade" id="historico_municipio_tab">
                                                    <input type="hidden" id="id_municipio_libreta_historico_hidden" name="id_municipio_libreta_historico_hidden" value="<?php echo $_GET['id_municipio_libreta_historico']; ?>" />
                                                    <?php
                                                        $query_select_historico = mysqli_query($connection, "SELECT * FROM historico_municipios_libreta_2 WHERE ID_MUNICIPIO_LIBRETA = " . $_GET['id_municipio_libreta_historico']);
                                                        if (mysqli_num_rows($query_select_historico) != 0) { ?>
                                                            <div style='margin-top: 0px;' class='panel-group' id='accordion_historico_municipio'>
                                                                <div class='panel panel-default'>
                                                                    <div style='padding: 5px 5px;' class='panel-heading'>
                                                                        <h4 style='font-size: 11px;' class='panel-title'>
                                                                            <a style='font-size: 11px;' data-toggle='collapse' data-parent='#accordion_historico_municipio' href='#collapseLoad'>CARGANDO...</a>
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        } else { ?>
                                                            <p class='message'>No se encontraron Historicos del Municipio.</p>
                                                        <?php
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-historico_municipio"></ul>
                                                        <span id='loading-spinner_historico' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                            </div>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/menu.js"></script>
    <script src="Javascript/moment-with-locales.js"></script>
    <script src="Javascript/bootstrap-datetimepicker.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script src="Javascript/custom-file-input.js"></script>
    <script src="http://malsup.github.io/jquery.form.js"></script>
    <script>
        function resetFieldMunicipio() {
            $('label[name=label_tipo_cliente]').attr("disabled", false);
            $('label[name=label_tipo_cliente]').attr("readonly", false);
            $('label[name=label_tipo_cliente]').removeClass("active");
            $('label[name=label_en_ejecucion]').attr("disabled", false);
            $('label[name=label_en_ejecucion]').attr("readonly", false);
            $('label[name=label_en_ejecucion]').removeClass("active");
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#id_concesion").val("");
            $("#id_empresa").val("");
            $("#id_operador").val("");
            $("#departamento").focus();
        }
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function isNothing(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 0 && (charCode < 255)) {
                return false;
            }
            return true;
        }
        function getDuracionContrato() {
            var fecha_inicio_contrato = $("input[name=fecha_inicio_contrato]").val();
            var fecha_fin_contrato = $("input[name=fecha_fin_contrato]").val();
            var initialDate = new Date(fecha_inicio_contrato);
            var finalDate = new Date(fecha_fin_contrato);
            var year = finalDate.getFullYear() - initialDate.getFullYear();
            /*var m = finalDate.getMonth() - initialDate.getMonth();
            if (m < 0 || (m == 0 && finalDate.getDate() < initialDate.getDate())) {
                year--;
            }*/
            $("input[name=duracion_contrato]").val(year);
        }
        function getDuracionContratoFR() {
            var fecha_ven_ini_contrato = $("input[name=fecha_ven_ini_contrato_f_r]").val();
            var fecha_ven_act_contrato = $("input[name=fecha_ven_act_contrato_f_r]").val();
            var initialDate = new Date(fecha_ven_ini_contrato);
            var finalDate = new Date(fecha_ven_act_contrato);
            var year = finalDate.getFullYear() - initialDate.getFullYear();
            /*var m = finalDate.getMonth() - initialDate.getMonth();
            if (m < 0 || (m == 0 && finalDate.getDate() < initialDate.getDate())) {
                year--;
            }*/
            $("input[name=duracion_contrato_f_r]").val(year);
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
                $("#nombre_alcalde").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio_Visita.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoTipoConcesion(id_concesion, concesion) {
            $("#id_concesion").val(id_concesion);
            $("#concesion").val(concesion);
            $("#empresa").focus();
        }
        function tipoConcesion() {
            window.open("Combos/Tipo_Concesion.php", "Popup", "width=640, height=500");
        }
        function infoTipoEmpresa(id_empresa, empresa) {
            $("#id_empresa").val(id_empresa);
            $("#empresa").val(empresa);
            $("#direccion_agm_municipio").focus();
        }
        function tipoEmpresa() {
            window.open("Combos/Tipo_Empresa.php", "Popup", "width=400, height=500");
        }
        function infoTipoOperador(id_operador, operador) {
            $("#id_operador").val(id_operador);
            $("#operador").val(operador);
            $("#no_contrato_concesion").focus();
        }
        function tipoOperador() {
            window.open("Combos/Tipo_Operador.php", "Popup", "width=500, height=500");
        }
        /*function infoTipoContrato(id_tipo_contrato, tipo_contrato) {
            $("#id_tipo_contrato").val(id_tipo_contrato);
            $("#tipo_contrato").val(tipo_contrato);
            $("#periodicidad_contrato").focus();
        }
        function tipoContrato() {
            window.open("Combos/Tipo_Contrato.php", "Popup", "width=400, height=500");
        }*/
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_municipio").focus();
            var id_municipio_libreta_editar = $("#id_municipio_libreta_editar_hidden").val();
            var id_municipio_libreta_eliminar = $("#id_municipio_libreta_eliminar_hidden").val();
            var id_municipio_libreta_historico = $("#id_municipio_libreta_historico_hidden").val();
            if (id_municipio_libreta_editar != undefined) {
                getDuracionContrato();
                getDuracionContratoFR();
                $(".nav-pills a[href='#crear_municipio_tab']").tab("show");
                $(".nav-pills a[href='#crear_municipio_tab']").text("Actualizar Municipio");
                $("#clase_contrato").val($("#clase_contrato_hidden").val());
                $("#periodicidad_contrato").val($("#periodicidad_contrato_hidden").val());
            } else {
                if (id_municipio_libreta_eliminar != undefined) {
                    getDuracionContrato();
                    getDuracionContratoFR();
                    $(".nav-pills a[href='#crear_municipio_tab']").tab("show");
                    $(".nav-pills a[href='#crear_municipio_tab']").text("Eliminar Municipio");
                    $("#clase_contrato").val($("#clase_contrato_hidden").val());
                    $("#periodicidad_contrato").val($("#periodicidad_contrato_hidden").val());
                } else {
                    if (id_municipio_libreta_historico != undefined) {
                        $(".nav-pills a[href='#historico_municipio_tab']").tab("show");
                        $.ajax({
                            type: "POST",
                            url: "Modelo/Cargar_Historico_Municipio_Libreta.php",
                            dataType: "json",
                            data: "id_municipio_libreta_historico="+id_municipio_libreta_historico,
                            success: function(data) {
                                $("#loading-spinner_historico").css('display', 'none');
                                $("#accordion_historico_municipio").html(data[0]);
                            }
                        });
                    }
                }
            }
            $("#buscar_municipio").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_municipio;
                    if ($(this).val() == "") {
                        busqueda_municipio = "";
                    } else {
                        busqueda_municipio = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Municipio_Libreta.php",
                        dataType: "json",
                        data: "sw=1&busqueda_municipio="+busqueda_municipio,
                        success: function(data) {
                            $("#pagination-municipio_libreta").twbsPagination('destroy');
                            $("#pagination-municipio_libreta").twbsPagination({
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
                                        url: "Modelo/Cargar_Municipio_Libreta.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_municipio="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_municipio_libreta").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#fecha_firma_contrato").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#fecha_inicio_contrato").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#fecha_fin_contrato").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#fecha_otro_si_contrato").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#fecha_firma_contrato_f_r").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#fecha_ven_ini_contrato_f_r").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#fecha_ven_act_contrato_f_r").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#tab_info_municipio").on("shown.bs.tab", function() {
                $("#buscar_municipio").focus();
            });
            $("#tab_crear_municipio").on("shown.bs.tab", function() {
                $("#departamento").focus();
            });
            $("#tab_info_municipio").on("click", function() {
                $("#buscar_municipio").focus();
            });
            $("#tab_crear_municipio").on("click", function() {
                $("#departamento").focus();
            });
            $("input:radio[name=periodo_nuevo]").change(function() {
                var periodo_nuevo = $(this).val();
                if (periodo_nuevo == 1) {
                    $("#modalHistoricoMunicipio").modal("show");
                }
            });
            $("#modalHistoricoMunicipio .modal-footer button").on('click', function(event) {
                var $button = $(event.target);
                var id_municipio_libreta_editar = $("#id_municipio_libreta_editar_hidden").val();
                var departamento = $("#id_departamento").val();
                var municipio = $("#id_municipio").val();
                var nombre_alcalde = $("#nombre_alcalde").val();
                var nombre_sec_hacienda = $("#nombre_sec_hacienda").val();
                var nombre_interventor = $("#nombre_interventor").val();
                var direccion_alcaldia = $("#direccion_alcaldia").val();
                var correo_electronico = $("#correo_electronico").val();
                var tipo_cliente = $("input[name=tipo_cliente]:checked").val();
                var clase_contrato = $("#clase_contrato").val();
                var concesion = $("#id_concesion").val();
                var empresa = $("#id_empresa").val();
                var direccion_agm_municipio = $("#direccion_agm_municipio").val();
                var direccion_agm_principal = $("#direccion_agm_principal").val();
                var identificacion_rep_legal = $("#identificacion_rep_legal").val();
                var nombre_rep_legal = $("#nombre_rep_legal").val();
                var operador = $("#id_operador").val();
                var no_contrato_concesion = $("#no_contrato_concesion").val();
                var fecha_firma_contrato = $("input[name=fecha_firma_contrato]").val();
                var fecha_inicio_contrato = $("input[name=fecha_inicio_contrato]").val();
                var fecha_fin_contrato = $("input[name=fecha_fin_contrato]").val();
                var duracion_contrato = $("input[name=duracion_contrato]").val();
                var otro_si_contrato = $("#otro_si_contrato").val();
                var fecha_otro_si_contrato = $("input[name=fecha_otro_si_contrato]").val();
                var objeto_contrato = $("#objeto_contrato").val();
                var contrato_energia = $("#contrato_energia").val();
                var periodicidad_contrato = $("#periodicidad_contrato").val();
                var fecha_firma_contrato_f_r = $("input[name=fecha_firma_contrato_f_r]").val();
                var fecha_ven_ini_contrato_f_r = $("input[name=fecha_ven_ini_contrato_f_r]").val();
                var fecha_ven_act_contrato_f_r = $("input[name=fecha_ven_act_contrato_f_r]").val();
                var duracion_contrato_f_r = $("input[name=duracion_contrato_f_r]").val();
                var en_ejecucion = $("input[name=en_ejecucion]:checked").val();
                var no_ultimo_acuerdo_tarifas = $("#no_ultimo_acuerdo_tarifas").val();
                var no_acuerdo_vigente = $("#no_acuerdo_vigente").val();
                var estatuto_tributario = $("#estatuto_tributario").val();
                var observaciones = $("#observaciones").val();
                $(this).closest('.modal').one('hidden.bs.modal', function() {
                    if ($button[0].id == "si_guardar") {
                        $.ajax({
                            type: 'POST',
                            data: 'guardar='+id_municipio_libreta_editar+
                                  "&departamento="+departamento+"&municipio="+municipio+
                                  "&nombre_alcalde="+nombre_alcalde+
                                  "&nombre_sec_hacienda="+nombre_sec_hacienda+
                                  "&nombre_interventor="+nombre_interventor+
                                  "&direccion_alcaldia="+direccion_alcaldia+
                                  "&correo_electronico="+correo_electronico+
                                  "&tipo_cliente="+tipo_cliente+"&clase_contrato="+clase_contrato+
                                  "&concesion="+concesion+
                                  "&empresa="+empresa+
                                  "&direccion_agm_municipio="+direccion_agm_municipio+
                                  "&direccion_agm_principal="+direccion_agm_principal+
                                  "&identificacion_rep_legal="+identificacion_rep_legal+
                                  "&nombre_rep_legal="+nombre_rep_legal+"&operador="+operador+
                                  "&no_contrato_concesion="+no_contrato_concesion+
                                  "&fecha_firma_contrato="+fecha_firma_contrato+
                                  "&fecha_inicio_contrato="+fecha_inicio_contrato+
                                  "&fecha_fin_contrato="+fecha_fin_contrato+
                                  "&duracion_contrato="+duracion_contrato+
                                  "&otro_si_contrato="+otro_si_contrato+
                                  "&fecha_otro_si_contrato="+fecha_otro_si_contrato+
                                  "&objeto_contrato="+objeto_contrato+
                                  "&contrato_energia="+contrato_energia+
                                  "&periodicidad_contrato="+periodicidad_contrato+
                                  "&fecha_firma_contrato_f_r="+fecha_firma_contrato_f_r+
                                  "&fecha_ven_ini_contrato_f_r="+fecha_ven_ini_contrato_f_r+
                                  "&fecha_ven_act_contrato_f_r="+fecha_ven_act_contrato_f_r+
                                  "&duracion_contrato_f_r="+duracion_contrato_f_r+
                                  "&en_ejecucion="+en_ejecucion+
                                  "&no_ultimo_acuerdo_tarifas="+no_ultimo_acuerdo_tarifas+
                                  "&no_acuerdo_vigente="+no_acuerdo_vigente+
                                  "&estatuto_tributario="+estatuto_tributario+
                                  "&observaciones="+observaciones,
                            url: 'Modelo/Crear_Municipio_Libreta.php',
                            success: function(data) {
                                document.location.href = 'Municipios_Libretas.php?id_municipio_libreta_editar='+id_municipio_libreta_editar;
                            }
                        });
                    } else {
                        $("label[name=label_periodo_nuevo_si]").removeClass("active");
                        $("#periodo_nuevo_si").prop("checked", false);
                    }
                });
            });
            if (id_municipio_libreta_editar == undefined && id_municipio_libreta_eliminar == undefined) {
                $("#btn_crear_municipio").click(function() {
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    var nombre_alcalde = $("#nombre_alcalde").val();
                    var nombre_sec_hacienda = $("#nombre_sec_hacienda").val();
                    var nombre_interventor = $("#nombre_interventor").val();
                    var direccion_alcaldia = $("#direccion_alcaldia").val();
                    var correo_electronico = $("#correo_electronico").val();
                    var tipo_cliente = $("input[name=tipo_cliente]:checked").val();
                    var clase_contrato = $("#clase_contrato").val();
                    var concesion = $("#id_concesion").val();
                    var empresa = $("#id_empresa").val();
                    var direccion_agm_municipio = $("#direccion_agm_municipio").val();
                    var direccion_agm_principal = $("#direccion_agm_principal").val();
                    var identificacion_rep_legal = $("#identificacion_rep_legal").val();
                    var nombre_rep_legal = $("#nombre_rep_legal").val();
                    var operador = $("#id_operador").val();
                    var no_contrato_concesion = $("#no_contrato_concesion").val();
                    var fecha_firma_contrato = $("input[name=fecha_firma_contrato]").val();
                    var fecha_inicio_contrato = $("input[name=fecha_inicio_contrato]").val();
                    var fecha_fin_contrato = $("input[name=fecha_fin_contrato]").val();
                    var duracion_contrato = $("input[name=duracion_contrato]").val();
                    if (duracion_contrato == "NaN") {
                        duracion_contrato = 0;
                    } else {
                        duracion_contrato = $("input[name=duracion_contrato]").val();
                    }
                    var otro_si_contrato = $("#otro_si_contrato").val();
                    var fecha_otro_si_contrato = $("input[name=fecha_otro_si_contrato]").val();
                    var objeto_contrato = $("#objeto_contrato").val();
                    var contrato_energia = $("#contrato_energia").val();
                    var periodicidad_contrato = $("#periodicidad_contrato").val();
                    var fecha_firma_contrato_f_r = $("input[name=fecha_firma_contrato_f_r]").val();
                    var fecha_ven_ini_contrato_f_r = $("input[name=fecha_ven_ini_contrato_f_r]").val();
                    var fecha_ven_act_contrato_f_r = $("input[name=fecha_ven_act_contrato_f_r]").val();
                    var duracion_contrato_f_r = $("input[name=duracion_contrato_f_r]").val();
                    if (duracion_contrato_f_r == "NaN") {
                        duracion_contrato_f_r = 0;
                    } else {
                        duracion_contrato_f_r = $("input[name=duracion_contrato]").val();
                    }
                    var en_ejecucion = $("input[name=en_ejecucion]:checked").val();
                    var no_ultimo_acuerdo_tarifas = $("#no_ultimo_acuerdo_tarifas").val();
                    var no_acuerdo_vigente = $("#no_acuerdo_vigente").val();
                    var estatuto_tributario = $("#estatuto_tributario").val();
                    var observaciones = $("#observaciones").val();
                    if (departamento.length == 0) {
                        $("#departamento").focus();
                        return false;
                    }
                    if (municipio.length == 0) {
                        $("#municipio").focus();
                        return false;
                    }
                    if (nombre_alcalde.length == 0) {
                        $("#nombre_alcalde").focus();
                        return false;
                    }
                    /*if (nombre_sec_hacienda.length == 0) {
                        $("#nombre_sec_hacienda").focus();
                        return false;
                    }
                    if (nombre_interventor.length == 0) {
                        $("#nombre_interventor").focus();
                        return false;
                    }*/
                    if (direccion_alcaldia.length == 0) {
                        $("#direccion_alcaldia").focus();
                        return false;
                    }
                    if (correo_electronico.length == 0) {
                        $("#correo_electronico").focus();
                        return false;
                    }
                    if (tipo_cliente == undefined) {
                        $("#tipo_cliente").focus();
                        return false;
                    }
                    if (clase_contrato.length == 0) {
                        $("#clase_contrato").focus();
                        return false;
                    }
                    if (concesion.length == 0) {
                        $("#concesion").focus();
                        return false;
                    }
                    if (empresa.length == 0) {
                        $("#empresa").focus();
                        return false;
                    }
                    if (direccion_agm_municipio.length == 0) {
                        $("#direccion_agm_municipio").focus();
                        return false;
                    }
                    if (direccion_agm_principal.length == 0) {
                        $("#direccion_agm_principal").focus();
                        return false;
                    }
                    if (identificacion_rep_legal.length == 0) {
                        $("#identificacion_rep_legal").focus();
                        return false;
                    }
                    if (nombre_rep_legal.length == 0) {
                        $("#nombre_rep_legal").focus();
                        return false;
                    }
                    if (operador.length == 0) {
                        $("#operador").focus();
                        return false;
                    }
                    if (no_contrato_concesion.length == 0) {
                        $("#no_contrato_concesion").focus();
                        return false;
                    }
                    if (fecha_firma_contrato.length == 0) {
                        $("input[name=fecha_firma_contrato]").focus();
                        return false;
                    }
                    if (objeto_contrato.length == 0) {
                        $("#objeto_contrato").focus();
                        return false;
                    }
                    /*if (fecha_inicio_contrato.length == 0) {
                        $("input[name=fecha_inicio_contrato]").focus();
                        return false;
                    }
                    if (fecha_fin_contrato.length == 0) {
                        $("input[name=fecha_fin_contrato]").focus();
                        return false;
                    }
                    if (duracion_contrato.length == 0) {
                        $("input[name=duracion_contrato]").focus();
                        return false;
                    }
                    if (otro_si_contrato.length == 0) {
                        $("#otro_si_contrato").focus();
                        return false;
                    }
                    if (fecha_otro_si_contrato.length == 0) {
                        $("input[name=fecha_otro_si_contrato]").focus();
                        return false;
                    }
                    if (contrato_energia.length == 0) {
                        $("#contrato_energia").focus();
                        return false;
                    }*/
                    if (periodicidad_contrato.length == 0) {
                        $("#periodicidad_contrato").focus();
                        return false;
                    }
                    /*if (fecha_firma_contrato_f_r.length == 0) {
                        $("input[name=fecha_firma_contrato_f_r]").focus();
                        return false;
                    }
                    if (fecha_ven_ini_contrato_f_r.length == 0) {
                        $("input[name=fecha_ven_ini_contrato_f_r]").focus();
                        return false;
                    }
                    if (fecha_ven_act_contrato_f_r.length == 0) {
                        $("input[name=fecha_ven_act_contrato_f_r]").focus();
                        return false;
                    }
                    if (duracion_contrato_f_r.length == 0) {
                        $("input[name=duracion_contrato_f_r]").focus();
                        return false;
                    }*/
                    if (en_ejecucion == undefined) {
                        $("#en_ejecucion").focus();
                        return false;
                    }
                    /*if (no_ultimo_acuerdo_tarifas.length == 0) {
                        $("#no_ultimo_acuerdo_tarifas").focus();
                        return false;
                    }
                    if (no_acuerdo_vigente.length == 0) {
                        $("#no_acuerdo_vigente").focus();
                        return false;
                    }
                    if (estatuto_tributario.length == 0) {
                        $("#estatuto_tributario").focus();
                        return false;
                    }*/
                    $("#btn_crear_municipio").attr("disabled", true);
                    $("#btn_crear_municipio").css("pointer-events", "none");
                    $("#btn_crear_municipio").html("Creando Municipio...");
                    $.ajax({
                        type: "POST",
                         url: "Verify/Verificar_Municipio_Libreta.php",
                        data: "departamento="+departamento+
                              "&municipio="+municipio,
                        success: function(data) {
                            if (data == 0) {
                                $.ajax({
                                    type: "POST",
                                    data: "departamento="+departamento+"&municipio="+municipio+
                                          "&nombre_alcalde="+nombre_alcalde+
                                          "&nombre_sec_hacienda="+nombre_sec_hacienda+
                                          "&nombre_interventor="+nombre_interventor+
                                          "&direccion_alcaldia="+direccion_alcaldia+
                                          "&correo_electronico="+correo_electronico+
                                          "&tipo_cliente="+tipo_cliente+"&clase_contrato="+clase_contrato+
                                          "&concesion="+concesion+
                                          "&empresa="+empresa+
                                          "&direccion_agm_municipio="+direccion_agm_municipio+
                                          "&direccion_agm_principal="+direccion_agm_principal+
                                          "&identificacion_rep_legal="+identificacion_rep_legal+
                                          "&nombre_rep_legal="+nombre_rep_legal+"&operador="+operador+
                                          "&no_contrato_concesion="+no_contrato_concesion+
                                          "&fecha_firma_contrato="+fecha_firma_contrato+
                                          "&fecha_inicio_contrato="+fecha_inicio_contrato+
                                          "&fecha_fin_contrato="+fecha_fin_contrato+
                                          "&duracion_contrato="+duracion_contrato+
                                          "&otro_si_contrato="+otro_si_contrato+
                                          "&fecha_otro_si_contrato="+fecha_otro_si_contrato+
                                          "&objeto_contrato="+objeto_contrato+
                                          "&contrato_energia="+contrato_energia+
                                          "&periodicidad_contrato="+periodicidad_contrato+
                                          "&fecha_firma_contrato_f_r="+fecha_firma_contrato_f_r+
                                          "&fecha_ven_ini_contrato_f_r="+fecha_ven_ini_contrato_f_r+
                                          "&fecha_ven_act_contrato_f_r="+fecha_ven_act_contrato_f_r+
                                          "&duracion_contrato_f_r="+duracion_contrato_f_r+
                                          "&en_ejecucion="+en_ejecucion+
                                          "&no_ultimo_acuerdo_tarifas="+no_ultimo_acuerdo_tarifas+
                                          "&no_acuerdo_vigente="+no_acuerdo_vigente+
                                          "&estatuto_tributario="+estatuto_tributario+
                                          "&observaciones="+observaciones,
                                    url: "Modelo/Crear_Municipio_Libreta.php",
                                    success: function(data) {
                                        document.location.href = 'Municipios_Libretas.php';
                                    }
                                });
                            } else {
                                $("#modalMunicipioLibreta").modal("show");
                                $("#btn_crear_municipio").attr("disabled", false);
                                $("#btn_crear_municipio").css("pointer-events", "auto");
                                $("#btn_crear_municipio").html("Crear Municipio");
                            }
                        }
                    });
                });
            }
            $("#eliminar_municipio").click(function() {
                var id_municipio_libreta_eliminar = $("#id_municipio_libreta_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Municipio_Libreta_Libretas.php",
                    data: "id_municipio_libreta_eliminar="+id_municipio_libreta_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_municipio_libreta").submit();
                        } else {
                            $("#modalEliminarMunicipioError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Municipio_Libreta.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-municipio_libreta").twbsPagination('destroy');
                    $("#pagination-municipio_libreta").twbsPagination({
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
                                url: "Modelo/Cargar_Municipio_Libreta.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_municipio_libreta").html(data[0]);
                                }
                            });
                        }
                    });
                }
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Historico_Municipio.php",
                dataType: "json",
                data: "id_municipio_libreta_historico="+id_municipio_libreta_historico,
                success: function(data) {
                    $("#pagination-historico_municipio").twbsPagination('destroy');
                    $("#pagination-historico_municipio").twbsPagination({
                        totalPages: data[0],
                        visiblePages: 15,
                        first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                        prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                        next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                        last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                        onPageClick: function (event, page) {
                            $("#loading-spinner_historico").css('display', 'block');
                            $.ajax({
                                type: "POST",
                                url: "Modelo/Cargar_Historico_Municipio.php",
                                dataType: "json",
                                data: "page="+page+"&id_municipio_libreta_historico="+id_municipio_libreta_historico,
                                success: function(data) {
                                    $("#loading-spinner_historico").css('display', 'none');
                                    $("#accordion_historico_municipio").html(data[0]);
                                }
                            });
                        }
                    });
                }
            });
            $("#upload_files").click(function() {
                var files = $("#files").val();
                if (files.length == 0) {
                    $("#files").focus();
                    return false;
                }
                $("#upload_files").attr("disabled", true);
                $("#upload_files").css("pointer-events", "none");
                $("#upload_files").html("Subiendo archivo...");
                $("#cargar_acuerdo_municipio").ajaxSubmit({
                    beforeSend: function() {
                        $("#progressBarFile").css("display", "block");
                        $("#progressBarFile").width("100%");
                        $("#progressBarFile").text("100%");
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        $("#progressBarFile").width(percentComplete + "%");
                        $("#progressBarFile").text(percentComplete + "%");
                    },
                    success: function() {
                        $("#modalUpload").modal("show");
                    }
                });
                return false;
            });
            $("#modalUpload").on('hidden.bs.modal', function() {
                location.reload();
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
                placement: "top"
            });
            $('label[name=label_periodo_nuevo]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=nombre_alcalde]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=nombre_sec_hacienda]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_interventor]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=direccion_alcaldia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=correo_electronico]').tooltip({
                container: "body",
                placement: "top"
            });
            $('label[name=label_tipo_cliente]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=clase_contrato]').tooltip({
                container : "body",
                placement : "right"
            });
            $('input[type=text][name=concesion]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=empresa]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=direccion_agm_municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=direccion_agm_principal]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=identificacion_rep_legal]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_rep_legal]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=operador]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=no_contrato_concesion]').tooltip({
                container: "body",
                placement: "top"
            });
            $('#fecha_firma_contrato').tooltip({
                container : "body",
                placement : "top"
            });
            $('#fecha_inicio_contrato').tooltip({
                container : "body",
                placement : "top"
            });
            $('#fecha_fin_contrato').tooltip({
                container : "body",
                placement : "top"
            });
            $('#duracion_contrato').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=otro_si_contrato]').tooltip({
                container: "body",
                placement: "top"
            });
            $('#fecha_otro_si_contrato').tooltip({
                container : "body",
                placement : "right"
            });
            $('textarea[name=objeto_contrato]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=contrato_energia]').tooltip({
                container: "body",
                placement: "top"
            });
            $('select[name=periodicidad_contrato]').tooltip({
                container : "body",
                placement : "top"
            });
            $('#fecha_firma_contrato_f_r').tooltip({
                container : "body",
                placement : "top"
            });
            $('#fecha_ven_ini_contrato_f_r').tooltip({
                container : "body",
                placement : "top"
            });
            $('#fecha_ven_act_contrato_f_r').tooltip({
                container : "body",
                placement : "top"
            });
            $('#duracion_contrato_f_r').tooltip({
                container : "body",
                placement : "top"
            });
            $('label[name=label_en_ejecucion]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=no_ultimo_acuerdo_tarifas]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=no_acuerdo_vigente]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=estatuto_tributario]').tooltip({
                container: "body",
                placement: "top"
            });
            $('textarea[name=observaciones]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=estado_archivo_acuerdo]').tooltip({
                container : "body",
                placement : "right"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>