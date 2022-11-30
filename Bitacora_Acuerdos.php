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
        <title>AGM - Bitácora - Libreta / Acuerdos</title>
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
    <!--Files Modal-->
    <div class="modal fade" id="modalFiles" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title archivos-title"></h4>
                </div>
                <div class="modal-body archivos-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Files Modal-->
    <!--Observaciones Modal-->
    <div class="modal fade" id="modalObservaciones" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title observaciones-title"></h4>
                </div>
                <div class="modal-body observaciones-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Observaciones Modal-->
    <!--Envio Correo Modal-->
    <div class="modal fade" id="modalEnvioCorreo" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Envío de Correo</h4>
                </div>
                <div class="modal-body">
                    <p>Su Correo ha sido enviado con Exito!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" id="envio_correo_ok" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Envio Correo Modal-->
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Home.php");?>
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
                                                                                <a href='Bitacora_Acuerdos.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-book"></i>
                                                                                    <span>Bitacora / Acuerdos</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Municipios_Libretas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-edit"></i>
                                                                                    <span>Municipios</span>
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
                                            <h1>Bitacora / Acuerdos</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_bitacora_tab" id="tab_info_bitacora" aria-controls="informacion_bitacora_tab" role="tab" data-toggle="tab">Información Bitacora / Acuerdos</a>
                                                </li>
                                                <?php
                                                    if (isset($_GET['id_bitacora_libreta_editar'])) { ?>
                                                        <li role="presentation">
                                                            <a href="#crear_bitacora_tab" id="tab_crear_bitacora" aria-controls="crear_bitacora_tab" role="tab" data-toggle="tab">Crear Bitacora / Acuerdo</a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if (isset($_GET['id_bitacora_libreta_archivo'])) { ?>
                                                        <li role="presentation">
                                                            <a href="#cargar_archivo_tab" id="tab_cargar_archivo" aria-controls="cargar_archivo_tab" role="tab" data-toggle="tab">Cargar Archivos</a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if (isset($_GET['id_bitacora_libreta_correo'])) { ?>
                                                        <li role="presentation">
                                                            <a href="#enviar_correo_tab" id="tab_enviar_correo" aria-controls="enviar_correo_tab" role="tab" data-toggle="tab">Enviar Correo</a>
                                                        </li>
                                                    <?php
                                                    }
                                                ?>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_bitacora_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Libreta" name="buscar_bitacora_libreta" id="buscar_bitacora_libreta" />
                                                    <br />
                                                    <?php
                                                        $query_select_bitacoras_libreta = "SELECT * FROM municipios_libreta_2 ORDER BY ID_DEPARTAMENTO, ID_MUNICIPIO";
                                                        $sql_bitacoras_libreta = mysqli_query($connection, $query_select_bitacoras_libreta);
                                                        if (mysqli_num_rows($sql_bitacoras_libreta) != 0) {
                                                            echo "<div style='margin-top: 0px;' class='panel-group' id='accordion_bitacora_libretas'>";
                                                                echo "<div class='panel panel-default'>";
                                                                    echo "<div style='padding: 5px 5px;' class='panel-heading'>";
                                                                        echo "<h4 style='font-size: 11px;' class='panel-title'>";
                                                                            echo "<a style='font-size: 11px;' data-toggle='collapse' data-parent='#accordion_bitacora_libretas' href='#collapseLoad'>CARGANDO...</a>";
                                                                        echo "</h4>";
                                                                    echo "</div>";
                                                                echo "</div>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Libretas Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-bitacora_libreta"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <?php
                                                    if (isset($_GET['id_bitacora_libreta_editar'])) { ?>
                                                        <div role="tabpanel" class="tab-pane fade" id="crear_bitacora_tab">
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_bitacora_libreta" name="crear_bitacora_libreta">
                                                                <input type="hidden" name="id_tabla" id="id_tabla" value="<?php echo $_GET['id_bitacora_libreta_editar']; ?>" />
                                                                <?php
                                                                    $query_select_info_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                                                                  . "   FROM municipios_libreta_2 "
                                                                                                                                  . "  WHERE ID_MUNICIPIO_LIBRETA = " . $_GET['id_bitacora_libreta_editar']);
                                                                    $row_info_municipio_libreta = mysqli_fetch_array($query_select_info_municipio_libreta);
                                                                ?>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <?php
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_info_municipio_libreta['ID_DEPARTAMENTO']);
                                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                        ?>
                                                                        <label><b>DEPARTAMENTO: </b></label> <font style="font-weight: normal;"><?php echo $row_departamento['NOMBRE']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-5">
                                                                        <?php
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                                                                              . " WHERE ID_DEPARTAMENTO = " . $row_info_municipio_libreta['ID_DEPARTAMENTO'] . " "
                                                                                                                              . "   AND ID_MUNICIPIO = " . $row_info_municipio_libreta['ID_MUNICIPIO']);
                                                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                        ?>
                                                                        <label><b>MUNICIPIO: </b></label> <font style="font-weight: normal;"><?php echo $row_municipio['NOMBRE']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <label><b>ALCALDE: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['NOMBRE_ALCALDE']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <label><b>SEC. HACIENDA: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['NOMBRE_SEC_HACIENDA']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-5">
                                                                        <label><b>INTERVENTOR: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['NOMBRE_INTERVENTOR']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <label><b>DIRECCIÓN: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['DIRECCION_ALCALDIA']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-5">
                                                                        <label><b>EMAIL: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['CORREO_ELECTRONICO']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <?php
                                                                            if ($row_info_municipio_libreta['ID_TIPO_CLIENTE'] == 1) { ?>
                                                                                <label><b>TIPO CLIENTE: </b></label> <font style="font-weight: normal;">REGULADO</font>
                                                                            <?php
                                                                            } else { ?>
                                                                                <label><b>TIPO CLIENTE: </b></label> <font style="font-weight: normal;">NO REGULADO</font>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 4px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <?php
                                                                            $query_select_concesion = mysqli_query($connection, "SELECT NOMBRE FROM concesiones_2 WHERE ID_CONCESION = " . $row_info_municipio_libreta['ID_CONCESION']);
                                                                            $row_concesion = mysqli_fetch_array($query_select_concesion);
                                                                        ?>
                                                                        <label><b>CONCESIÓN: </b></label> <font style="font-weight: normal;"><?php echo $row_concesion['NOMBRE']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-5">
                                                                        <?php
                                                                            $query_select_empresa = mysqli_query($connection, "SELECT NOMBRE FROM empresas_2 WHERE ID_EMPRESA = " . $row_info_municipio_libreta['ID_EMPRESA']);
                                                                            $row_empresa = mysqli_fetch_array($query_select_empresa);
                                                                        ?>
                                                                        <label><b>EMPRESA: </b></label> <font style="font-weight: normal;"><?php echo $row_empresa['NOMBRE']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <label><b>DIR. AGM MUNC.: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['DIRECCION_AGM_MUNICIPIO']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <label><b>DIR. AGM PRINC.: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['DIRECCION_AGM_PRINCIPAL']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <label><b>IDENT. REP. LEGAL: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['IDENTIFICACION_REP_LEGAL']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-5">
                                                                        <label><b>REPR. LEGAL: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['REPR_LEGAL']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-5">
                                                                        <?php
                                                                            $query_select_operador = mysqli_query($connection, "SELECT NOMBRE FROM operadores_2 WHERE ID_OPERADOR = " . $row_info_municipio_libreta['ID_OPERADOR']);
                                                                            $row_operador = mysqli_fetch_array($query_select_operador);
                                                                        ?>
                                                                        <label><b>OPERADOR: </b></label> <font style="font-weight: normal;"><?php echo $row_operador['NOMBRE']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 4px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-3">
                                                                        <label><b>NO. CONTRATO: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['NO_CONTRATO_CONCESION']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>FECHA FIRMA: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['FECHA_FIRMA_CONTRATO']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>FECHA INICIO: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['FECHA_INICIO_CONTRATO']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>FECHA FIN: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['FECHA_FIN_CONTRATO']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-3">
                                                                        <label><b>DURACIÓN: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['DURACION_CONTRATO'] . " AÑOS"; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>OTRO SÍ: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['OTRO_SI_CONTRATO']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>FECHA OTRO SÍ: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['FECHA_OTRO_SI_CONTRATO']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 4px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                       <label><b>CONTRATO ENERGÍA: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['CONTRATO_ENERGIA']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-4">
                                                                        <?php
                                                                            if ($row_info_municipio_libreta['PERIODICIDAD_RENOVACION'] == 1) { ?>
                                                                                <label><b>PERIODICIDAD: </b></label> <font style="font-weight: normal;">ANUAL</font>
                                                                            <?php
                                                                            } else { ?>
                                                                                <label><b>PERIODICIDAD: </b></label> <font style="font-weight: normal;">NO HAY CONTRATO</font>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>FECHA FIRMA: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['FECHA_FIRMA_CONTRATOS_F_R']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-3">
                                                                       <label><b>FECHA INICIAL: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['FECHA_VENC_CONTRATOS_INI_F_R']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>FECHA ACTUAL: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['FECHA_VENC_CONTRATOS_ACT_F_R']; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <label><b>DURACIÓN: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['DURACION_CONTRATOS_F_R'] . " AÑOS"; ?></font>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <?php
                                                                            if ($row_info_municipio_libreta['EN_EJECUCION'] == 1) { ?>
                                                                                <label><b>EN EJECUCIÓN: </b></label> <font style="font-weight: normal;">SI</font>
                                                                            <?php
                                                                            } else { ?>
                                                                                <label><b>EN EJECUCIÓN: </b></label> <font style="font-weight: normal;">NO</font>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 4px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                       <label><b>NO. ÚLTIMO ACUERDO: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['NO_ULTIMO_ACUERDO_TARIFAS']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <label><b>ACUERDO VIGENTE: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['NO_ACUERDO_VIGENTE']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <label><b>ESTATUTO TRIBUTARIO: </b></label> <font style="font-weight: normal;"><?php echo $row_info_municipio_libreta['ESTATUTO_TRIBUTARIO']; ?></font>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-3">
                                                                        <div class="input-group date" id="fecha_visita" data-toogle="tooltip" title="FECHA VISITA">
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_visita" value="" placeholder="Fecha Visita" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-9">
                                                                        <input type="hidden" id="id_tipo_visita" name="id_tipo_visita" value="" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="tipo_visita" name="tipo_visita" placeholder="Tipo Visita" required="required" data-toggle="tooltip" readonly="readonly" title="TIPO VISITA" onclick="tipoVisita()" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="styled-select">
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="5" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"></textarea>
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
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_bitacora" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Bitacora</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Bitacora_Acuerdos.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                                <?php
                                                    if (isset($_GET['id_bitacora_libreta_archivo'])) { ?>
                                                        <div role="tabpanel" class="tab-pane fade" id="cargar_archivo_tab">
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_archivo" name="cargar_archivo" action="Modelo/Subir_Archivos.php?archivo=bitacora" method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="id_tabla_archivo" id="id_tabla_archivo" value="<?php echo $_GET['id_bitacora_libreta_archivo']; ?>" />
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
                                                                            <button class="btn btn-primary btn-sm font background cursor" type="submit" name="upload_files" id="upload_files"><i style="font-size: 14px;" class="fas fa-upload"></i>&nbsp;&nbsp;Subir Archivo</button>
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
                                                                $theList = "";
                                                                $tag = "";
                                                                $total_size = 0;
                                                                $total_files = 0;
                                                                $query_select_id_municipio_libreta = mysqli_query($connection, "SELECT ID_MUNICIPIO_LIBRETA FROM bitacora_libretas_2 WHERE ID_TABLA = " . $_GET['id_bitacora_libreta_archivo']);
                                                                $row_id_municipio_libreta = mysqli_fetch_array($query_select_id_municipio_libreta);
                                                                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                                                                                     . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_id_municipio_libreta['ID_MUNICIPIO_LIBRETA']);
                                                                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                                                                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                                                                         . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
                                                                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                                                                $path = "Files/" . $row_nombre_municipio['NOMBRE'] . "/";
                                                                $query_select_files_bitacora_libreta = mysqli_query($connection, "SELECT * "
                                                                                                                               . "  FROM bitacora_libretas_archivos_2 "
                                                                                                                               . " WHERE ID_TABLA_BITACORA = " . $_GET['id_bitacora_libreta_archivo']);
                                                                if (mysqli_num_rows($query_select_files_bitacora_libreta) != 0) {
                                                                    while ($row_files = mysqli_fetch_assoc($query_select_files_bitacora_libreta)) {
                                                                        $info_bitacora[] = $row_files['NOMBRE_ARCHIVO'];
                                                                        $info_id_bitacora[] = $row_files['ID_TABLA'];
                                                                    }
                                                                    if ($handle = opendir($path)) {
                                                                        foreach (array_combine($info_id_bitacora, $info_bitacora) as $id_files => $files) {
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
                                                                            $theList .= "<tr>"
                                                                                      . "   <td style='vertical-align: middle;'><a href='" . $path . $files . "' target='_blank' title='" . $files . "'>" . $files . "</a></td>"
                                                                                      . "   <td style='vertical-align: middle;'>" . file_size($path . $files) . "</td>"
                                                                                      . "   <td style='vertical-align: middle;'>" . $tag . " - " . strtoupper(pathinfo($path . $files, PATHINFO_EXTENSION)) . "</td>"
                                                                                      . "</tr>";
                                                                        }
                                                                        closedir($handle);
                                                                    }
                                                                }
                                                            ?>
                                                            <div class="table-responsive">
                                                                <table class="table table-condensed table-hover">
                                                                    <thead>
                                                                        <th style="width: 40%;">NOMBRE ARCHIVO</th>
                                                                        <th style="width: 12%;">TAMAÑO ARCHIVO</th>
                                                                        <th style="width: 12%;">EXTENSIÓN ARCHIVO</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php echo $theList; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                ?>
                                                <?php
                                                    if (isset($_GET['id_bitacora_libreta_correo'])) { ?>
                                                        <div role="tabpanel" class="tab-pane fade" id="enviar_correo_tab">
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="enviar_correo" name="enviar_correo">
                                                                <input type="hidden" name="id_tabla_correo" id="id_tabla_correo" value="<?php echo $_GET['id_bitacora_libreta_correo']; ?>" />
                                                                <div class="form-group">
                                                                    <div class="col-xs-10">
                                                                        <?php
                                                                            $query_select_info_correo = mysqli_query($connection, "SELECT *, U.CORREO_ELECTRONICO "
                                                                                                                                      . "  FROM usuarios_2 U, bitacora_libretas_2 B, municipios_libreta_2 ML "
                                                                                                                                      . " WHERE U.ID_USUARIO = B.ID_USUARIO "
                                                                                                                                      . "   AND B.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA "
                                                                                                                                      . "   AND B.ID_TABLA = " . $_GET['id_bitacora_libreta_correo']);
                                                                            $row_info_correo = mysqli_fetch_array($query_select_info_correo);
                                                                        ?>
                                                                        <input type="hidden" id="id_usuario_remitente" name="id_usuario_remitente" value="<?php echo $row_info_correo['ID_USUARIO']; ?>" required="required" />
                                                                        <input type="hidden" id="email_usuario_remitente" name="email_usuario_remitente" value="<?php echo $row_info_correo['CORREO_ELECTRONICO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="usuario_remitente" name="usuario_remitente"  value="<?php echo $row_info_correo['NOMBRE'] . " <" . $row_info_correo['CORREO_ELECTRONICO'] . ">"; ?>" placeholder="Remitente" required="required" data-toggle="tooltip" readonly="readonly" title="REMITENTE" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-10">
                                                                        <input type="hidden" id="id_usuario_destinatario" name="id_usuario_destinatario" value="" required="required" />
                                                                        <input type="hidden" id="email_usuario_destinatario" name="email_usuario_destinatario" value="" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="usuario_destinatario" name="usuario_destinatario" value="" placeholder="Destinatario" required="required" data-toggle="tooltip" readonly="readonly" title="DESTINATARIO" onclick="destinatarioCorreo()" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-10">
                                                                        <?php
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = '" .$row_info_correo['ID_DEPARTAMENTO'] . "'");
                                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = '" .$row_info_correo['ID_DEPARTAMENTO'] . "' AND ID_MUNICIPIO = '" . $row_info_correo['ID_MUNICIPIO'] . "'");
                                                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                            $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = '" . $row_info_correo['ID_TIPO_VISITA'] . "'");
                                                                            $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="asunto_correo" name="asunto_correo" value="<?php echo $row_departamento['NOMBRE'] . " - " . $row_municipio['NOMBRE'] . ": " . $row_tipo_visita['NOMBRE'] . ". FECHA VISITA: " . $row_info_correo['FECHA_VISITA']; ?>" placeholder="Asunto" maxlength="200" required="required" data-toggle="tooltip" title="ASUNTO" />
                                                                    </div>
                                                                </div>
                                                                <!--<div class="form-group">
                                                                    <div class="col-xs-10">
                                                                        <div class="btn-group" data-toggle="buttons">
                                                                            <label class="btn btn-primary cursor font background" name="label_observaciones" data-toogle="tooltip" title="OBSERVACIONES - SI">
                                                                                <input type="radio" class="form-control input-text input-sm" id="agregar_observaciones" name="agregar_observaciones" value="1" required />Observaciones - Si
                                                                            </label>
                                                                            <label class="btn btn-primary cursor font background" name="label_observaciones" data-toogle="tooltip" title="OBSERVACIONES - NO">
                                                                                <input type="radio" class="form-control input-text input-sm" id="agregar_observaciones" name="agregar_observaciones" value="0" required />Observaciones - No
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                                                <div class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="mensaje_correo" name="mensaje_correo" rows="7" placeholder="Mensaje" data-toogle="tooltip" title="MENSAJE"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-bottom: 0px;" class="form-group">
                                                                    <div style="text-align: center;" class="col-xs-12">
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_enviar_correo" type="submit"><i style="font-size: 14px;" class="fas fa-envelope"></i>&nbsp;&nbsp;Enviar Correo</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Bitacora_Acuerdos.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    <?php
                                                    }
                                                ?>
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
        //POPUPS
        function infoTipoVisita(id_tipo_visita, tipo_visita) {
            $("#id_tipo_visita").val(id_tipo_visita);
            $("#tipo_visita").val(tipo_visita);
            $("#observaciones").focus();
        }
        function tipoVisita() {
            window.open("Combos/Tipo_Visita.php", "Popup", "width=400, height=500");
        }
        function infoDestinatarioCorreo(id_usuario_destinatario, usuario_destinatario, correo_electronico) {
            $("#id_usuario_destinatario").val($("#id_usuario_destinatario").val() + id_usuario_destinatario + ", ");
            $("#email_usuario_destinatario").val($("#email_usuario_destinatario").val() + correo_electronico + ", ");
            $("#usuario_destinatario").val($("#usuario_destinatario").val() + usuario_destinatario + ", ");
        }
        function destinatarioCorreo() {
            var id_tabla_correo = $("#id_tabla_correo").val();
            var id_usuario_remitente = $("#id_usuario_remitente").val();
            //alert(id_tabla_correo + " - " + id_usuario_remitente);
            window.open("Combos/Destinatario_Correo.php?id_tabla_correo="+id_tabla_correo+"&id_usuario_remitente="+id_usuario_remitente, "Popup", "width=600, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_bitacora_libreta").focus();
            var id_tabla = $("#id_tabla").val();
            var id_tabla_archivo = $("#id_tabla_archivo").val();
            var id_tabla_correo = $("#id_tabla_correo").val();
            if (id_tabla != undefined) {
                $(".nav-pills a[href='#crear_bitacora_tab']").tab("show");
            } else {
                if (id_tabla_archivo != undefined) {
                    $(".nav-pills a[href='#cargar_archivo_tab']").tab("show");
                } else {
                    if (id_tabla_correo != undefined) {
                        $(".nav-pills a[href='#enviar_correo_tab']").tab("show");
                    }
                }
            }
            $("#buscar_bitacora_libreta").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_bitacora_libreta;
                    if ($(this).val() == "") {
                        busqueda_bitacora_libreta = "";
                    } else {
                        busqueda_bitacora_libreta = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Bitacora_Libreta.php",
                        dataType: "json",
                        data: "sw=1&busqueda_bitacora_libreta="+busqueda_bitacora_libreta,
                        success: function(data) {
                            $("#pagination-bitacora_libreta").twbsPagination('destroy');
                            $("#pagination-bitacora_libreta").twbsPagination({
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
                                        url: "Modelo/Cargar_Bitacora_Libreta.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_bitacora_libreta="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#accordion_bitacora_libretas").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#fecha_visita").datetimepicker({
                format: 'YYYY-MM-DD hh:mm'
            });
            $("#tab_info_bitacora").on("shown.bs.tab", function() {
                $("#buscar_bitacora_libreta").focus();
            });
            $("#tab_crear_bitacora").on("shown.bs.tab", function() {
                $("input[name=fecha_visita]").focus();
            });
            $("#tab_info_bitacora").on("click", function() {
                $("#buscar_bitacora_libreta").focus();
            });
            $("#tab_crear_bitacora").on("click", function() {
                $("input[name=fecha_visita]").focus();
            });
            /*$("input:radio[name=agregar_observaciones]").change(function() {
                var agregar_observaciones = $(this).val();
                if (agregar_observaciones == 1) {
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Observaciones_Bitacora_Libreta.php",
                        dataType: "json",
                        data: "file_id="+id_tabla_correo+"&id_tabla_observacion="+id_tabla_correo,
                        success: function(data) {
                            var str = data[1];
                            var str1 = str.replace("<p>", "");
                            var observaciones = str1.replace("</p>", "");
                            $("#mensaje_correo").html(observaciones);
                            $("#mensaje_correo").focus();
                        }
                    });
                } else {
                    $("#mensaje_correo").html("");
                }
            });*/
            if (id_tabla != undefined) {
                $("#btn_crear_bitacora").click(function() {
                    var fecha_visita = $("input[name=fecha_visita]").val();
                    var tipo_visita = $("#id_tipo_visita").val();
                    var observaciones = $("#observaciones").val();
                    if (fecha_visita.length == 0) {
                        $("input[name=fecha_visita]").focus();
                        return false;
                    }
                    if (tipo_visita.length == 0) {
                        $("#tipo_visita").focus();
                        return false;
                    }
                    if (observaciones.length == 0) {
                        $("#observaciones").focus();
                        return false;
                    }
                    $("#btn_crear_bitacora").attr("disabled", true);
                    $("#btn_crear_bitacora").css("pointer-events", "none");
                    $("#btn_crear_bitacora").html("Creando Bitacora...");
                    $.ajax({
                        type: "POST",
                        data: "fecha_visita="+fecha_visita+"&tipo_visita="+tipo_visita+"&observaciones="+observaciones+"&id_tabla="+id_tabla,
                         url: "Modelo/Crear_Bitacora.php",
                        success: function(data) {
                            document.location.href = 'Bitacora_Acuerdos.php';
                        }
                    });
                });
            }
            if (id_tabla_correo != undefined) {
                $("#btn_enviar_correo").click(function() {
                    var email_usuario_remitente = $("#email_usuario_remitente").val();
                    var email_usuario_destinatario = $("#email_usuario_destinatario").val();
                    var asunto_correo = $("#asunto_correo").val();
                    var mensaje = $("#mensaje_correo").val();
                    if (email_usuario_destinatario.length == 0) {
                        $("#usuario_destinatario").focus();
                        return false;
                    }
                    if (asunto_correo.length == 0) {
                        $("#asunto_correo").focus();
                        return false;
                    }
                    if (mensaje.length == 0) {
                        $("#mensaje_correo").focus();
                        return false;
                    }
                    $("#btn_enviar_correo").attr("disabled", true);
                    $("#btn_enviar_correo").css("pointer-events", "none");
                    $("#btn_enviar_correo").html("Enviando Correo...");
                    $.ajax({
                        type: "POST",
                        data: "id_tabla_correo="+id_tabla_correo+
                              "&email_usuario_remitente="+email_usuario_remitente+
                              "&email_usuario_destinatario="+email_usuario_destinatario+
                              "&asunto_correo="+asunto_correo+
                              "&mensaje="+mensaje,
                         url: "Modelo/Enviar_Correo.php",
                        success: function(data) {
                            alert(data);
                            $("#modalEnvioCorreo").modal("show");
                        }
                    });
                });
            }
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Bitacora_Libreta.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-bitacora_libreta").twbsPagination('destroy');
                    $("#pagination-bitacora_libreta").twbsPagination({
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
                                url: "Modelo/Cargar_Bitacora_Libreta.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#accordion_bitacora_libretas").html(data[0]);
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
                $("#cargar_archivo").ajaxSubmit({
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
            $("#modalFiles").on('show.bs.modal', function(e) {
                var file_id = e.relatedTarget.id;
                $(".archivos-title").html("");
                $(".archivos-body").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-archivos' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Files_Bitacora_Libreta.php",
                    dataType: "json",
                    data: "file_id="+file_id+"&id_tabla_archivo="+file_id,
                    success: function(data) {
                        $("#loading-spinner-archivos").css('display', 'none');
                        $(".archivos-title").html(data[0]);
                        $(".archivos-body").html(data[1]);
                    }
                });
            });
            $("#modalObservaciones").on('show.bs.modal', function(e) {
                var file_id = e.relatedTarget.id;
                $(".observaciones-title").html("");
                $(".observaciones-body").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-observaciones' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Observaciones_Bitacora_Libreta.php",
                    dataType: "json",
                    data: "file_id="+file_id+"&id_tabla_observacion="+file_id,
                    success: function(data) {
                        $("#loading-spinner-observaciones").css('display', 'none');
                        $(".observaciones-title").html(data[0]);
                        $(".observaciones-body").html(data[1]);
                    }
                });
            });
            $("#modalEnvioCorreo .modal-footer button").on('click', function(event) {
                var $button = $(event.target);
                $(this).closest('.modal').one('hidden.bs.modal', function() {
                    if ($button[0].id == "envio_correo_ok") {
                        document.location.href = 'Bitacora_Acuerdos.php';
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#fecha_visita').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=tipo_visita]').tooltip({
                container: "body",
                placement: "top"
            });
            $('textarea[name=observaciones]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=usuario_remitente]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=usuario_destinatario]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=asunto_correo]').tooltip({
                container: "body",
                placement: "right"
            });
            $('label[name=label_observaciones]').tooltip({
                container : "body",
                placement : "top"
            });
            $('textarea[name=mensaje_correo]').tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>