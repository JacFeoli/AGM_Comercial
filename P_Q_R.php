<?php
session_start();
require_once('Includes/Config.php');
if ($_SESSION['rol'] == 'A' || $_SESSION['rol'] == 'PQR') {
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        switch ($_SESSION['rol']) {
            case 'PQR':
                $readOnly = "readonly='readonly'";
                $tipoDepartamento = "";
                $tipoMunicipio = "";
                $tipoAsunto = "";
                $styleTipoPersona = " pointer-events: none; background-color: #FFFFFF;";
                $stylePeticion = " pointer-events: none; background-color: #FFFFFF;";
                $styleRequiereVisita = " pointer-events: none; background-color: #FFFFFF;";
                break;
            case 'A':
                $readOnly = "";
                $tipoDepartamento = "onclick='tipoDepartamento(1)'";
                $tipoMunicipio = "onclick='tipoMunicipio(1)'";
                $tipoAsunto = "onclick='tipoAsuntoPQR()'";
                $styleTipoPersona = "";
                $stylePeticion = "";
                $styleRequiereVisita = "";
                break;
        }
    }
} else {
    $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location:$ruta/Acceso_Restringido.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>AGM - P.Q.R.</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="Css/AGM_Style.css" />
    <link rel="stylesheet" href="Css/bootstrap.min.css" />
    <link rel="stylesheet" href="Css/menu_style.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
    <style type="text/css">
        .text-divider {
            margin: 2em 0;
            line-height: 0;
            text-align: center;
        }

        .text-divider span {
            background-color: #D0DEE7;
            padding: 1em;
        }

        .text-divider:before {
            content: " ";
            display: block;
            border-top: 1px solid #A9BDC8;
        }

        .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .inputfile+label {
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

        .inputfile:focus+label,
        .inputfile.has-focus+label {
            outline: 1px dotted #000;
            outline: -webkit-focus-ring-color auto 5px;
        }

        .inputfile-1+label {
            border-color: #2C592C;
            color: #FFFFFF;
            background-image: linear-gradient(#6CBF6C, #408040);
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        }

        .inputfile-1:focus+label,
        .inputfile-1.has-focus+label,
        .inputfile-1+label:hover {
            border-color: #2C592C;
            background-image: linear-gradient(#61AB61, #397339);
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        }

        td.day {
            position: relative;
        }

        td.day.disabled:hover:before {
            content: 'Fecha no Disponible!';
            color: red;
            background-color: white;
            top: -22px;
            position: absolute;
            width: 136px;
            left: -34px;
            z-index: 1000;
            text-align: center;
            padding: 2px;
        }
    </style>
</head>
<!--Eliminar P.Q.R. Modal-->
<div class="modal fade" id="modalEliminarPQR" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Eliminar P.Q.R.</h4>
            </div>
            <div class="modal-body">
                <p>¿Desea eliminar la P.Q.R.?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_pqr" name="eliminar_pqr"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Eliminar P.Q.R. Modal-->
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
                                                                <a href='#'>
                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-headset"></i>
                                                                    <span>P.Q.R.</span>
                                                                </a>
                                                                <div style="display: block;" class="sidebar-submenu">
                                                                    <ul class="nav nav-pills nav-stacked">
                                                                        <li>
                                                                            <a href='P_Q_R.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-headset"></i>
                                                                                <span>P.Q.R.</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Reportes_P_Q_R.php'>
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
                                        <h1>P.Q.R.</h1>
                                        <h2></h2>
                                        <ul class="nav nav-pills" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#informacion_pqr_tab" id="tab_info_pqr" aria-controls="informacion_pqr_tab" role="tab" data-toggle="tab">Información P.Q.R.</a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#crear_pqr_tab" id="tab_crear_pqr" aria-controls="crear_pqr_tab" role="tab" data-toggle="tab">Crear P.Q.R.</a>
                                            </li>
                                            <?php
                                            if (isset($_GET['id_pqr_editar'])) { ?>
                                                <!--<li role="presentation">
                                                    <a href="#cargar_archivo_pqr_tab" id="tab_cargar_archivo_pqr" aria-controls="cargar_archivo_pqr_tab" role="tab" data-toggle="tab">Cargar Archivos</a>
                                                </li>-->
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <h2></h2>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active" id="informacion_pqr_tab">
                                                <input class="form-control input-text input-sm" type="text" placeholder="Buscar P.Q.R." name="buscar_pqr" id="buscar_pqr" />
                                                <br />
                                                <?php
                                                if ($_SESSION['id_departamento'] != 0) {
                                                    if ($_SESSION['id_user'] == 34) {
                                                        $query_select_pqr = "SELECT * "
                                                            . "  FROM pqr_2 "
                                                            . " WHERE ID_COD_DPTO = " . $_SESSION['id_departamento'] . " "
                                                            . "   AND ID_COD_MPIO IN (518, 553) "
                                                            . " ORDER BY FECHA_INGRESO DESC ";
                                                    } else {
                                                        $query_select_pqr = "SELECT * "
                                                            . "  FROM pqr_2 "
                                                            . " WHERE ID_COD_DPTO = " . $_SESSION['id_departamento'] . " "
                                                            . "   AND ID_COD_MPIO = " . $_SESSION['id_municipio'] . " "
                                                            . " ORDER BY FECHA_INGRESO DESC ";
                                                    }
                                                } else {
                                                    $query_select_pqr = "SELECT * "
                                                        . "  FROM pqr_2 "
                                                        . " ORDER BY ID_COD_DPTO, ID_COD_MPIO, FECHA_INGRESO DESC ";
                                                }
                                                $sql_pqr = mysqli_query($connection, $query_select_pqr);
                                                if (mysqli_num_rows($sql_pqr) != 0) {
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table class='table table-condensed table-hover'>";
                                                    echo "<thead>";
                                                    echo "<tr>";
                                                    echo "<th width=5%>ESTADO</th>";
                                                    echo "<th width=15%>RADICADO</th>";
                                                    echo "<th width=25%>USUARIO</th>";
                                                    echo "<th width=20%>MUNICIPIO</th>";
                                                    echo "<th width=15%>FECHA VENC.</th>";
                                                    echo "<th width=10%>DÍAS REST.</th>";
                                                    echo "<th width=5%>DETALLE</th>";
                                                    echo "<th width=5%>ELIMINAR</th>";
                                                    echo "</tr>";
                                                    echo "</thead>";
                                                    echo "<tbody id='resultado_pqr'>";

                                                    echo "</tbody>";
                                                    echo "</table>";
                                                    echo "</div>";
                                                    echo "<p></p>";
                                                    echo "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>RC</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECIBIDO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>RS</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RESUELTO.</span>";
                                                } else {
                                                    echo "<p class='message'>No se encontraron P.Q.R. Creadas.</p>";
                                                }
                                                ?>
                                                <div id="div-pagination">
                                                    <ul id="pagination-pqr"></ul>
                                                    <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="crear_pqr_tab">
                                                <?php
                                                if (isset($_GET['id_pqr_editar'])) { ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_pqr" name="crear_pqr" action="<?php echo "Modelo/Crear_PQR.php?editar=" . $_GET['id_pqr_editar']; ?>" method="post">
                                                        <?php
                                                        $query_select_pqr = mysqli_query($connection, "SELECT * FROM pqr_2 WHERE ID_PQR = " . $_GET['id_pqr_editar']);
                                                        $row_pqr = mysqli_fetch_array($query_select_pqr);
                                                        ?>
                                                        <input type="hidden" id="id_pqr_editar_hidden" name="id_pqr_editar_hidden" value="<?php echo $row_pqr['ID_PQR']; ?>" />
                                                        <?php
                                                    } else {
                                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_pqr" name="crear_pqr" action="<?php echo "Modelo/Crear_PQR.php?eliminar=" . $_GET['id_pqr_eliminar']; ?>" method="post">
                                                                <?php
                                                                $query_select_pqr = mysqli_query($connection, "SELECT * FROM pqr_2 WHERE ID_PQR = " . $_GET['id_pqr_eliminar']);
                                                                $row_pqr = mysqli_fetch_array($query_select_pqr);
                                                                ?>
                                                                <input type="hidden" id="id_pqr_eliminar_hidden" name="id_pqr_eliminar_hidden" value="<?php echo $row_pqr['ID_PQR']; ?>" />
                                                            <?php
                                                        } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_pqr" name="crear_pqr" action="<?php echo "Modelo/Crear_PQR.php"; ?>" method="post">
                                                                <?php
                                                            }
                                                                ?>
                                                            <?php
                                                        }
                                                            ?>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN ESTADOS / FECHAS</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="radicado_pqr">Radicado:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_pqr_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="radicado_pqr" name="radicado_pqr" value="<?php echo $row_pqr['RADICADO']; ?>" readonly="readonly" placeholder="Radicado" data-toogle="tooltip" title="RADICADO" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="radicado_pqr" name="radicado_pqr" value="<?php echo $row_pqr['RADICADO']; ?>" readonly="readonly" placeholder="Radicado" data-toogle="tooltip" title="RADICADO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="radicado_pqr" name="radicado_pqr" readonly="readonly" placeholder="Radicado" data-toogle="tooltip" title="RADICADO" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>

                                                                <?php
                                                                if (isset($_GET['id_pqr_editar']) || isset($_GET['id_pqr_eliminar'])) {
                                                                    if ($row_pqr['ESTADO_PQR'] == 2) { ?>
                                                                        <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="estado_pqr">Tiempo Transcurrido:</label>
                                                                        <div class="col-xs-3 text-center">
                                                                            <?php
                                                                            $dias_restantes = 0;
                                                                            $fecha_ingreso = strtotime($row_pqr['FECHA_INGRESO']);
                                                                            $fecha_respuesta = strtotime($row_pqr['FECHA_RESPUESTA']);
                                                                            for ($fecha_ingreso; $fecha_ingreso <= $fecha_respuesta; $fecha_ingreso = strtotime('+1 day' . date('Y-m-d', $fecha_ingreso))) {
                                                                                if ((strcmp(date('D', $fecha_ingreso), 'Sun') != 0) and (strcmp(date('D', $fecha_ingreso), 'Sat') != 0)) {
                                                                                    $dias_restantes = $dias_restantes + 1;
                                                                                }
                                                                            }
                                                                            echo '<label style="text-align: center; padding-top: 4px; white-space: nowrap; color: #CC3300;" class="control-label row-label" for="">' . $dias_restantes . ' Días</label>';
                                                                            ?>
                                                                        </div>
                                                                    <?php
                                                                    } else { ?>
                                                                        <div class="col-xs-4 text-center"></div>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="estado_pqr">Estado:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="styled-select">
                                                                        <?php
                                                                        if (isset($_GET['id_pqr_editar'])) {
                                                                            if ($row_pqr['ESTADO_PQR'] == 2) { ?>
                                                                                <select style="pointer-events: none;" class="form-control input-text input-sm" id="estado_pqr" name="estado_pqr" data-toggle="tooltip" title="ESTADO P.Q.R." required>
                                                                                <?php
                                                                            } else { ?>
                                                                                    <select class="form-control input-text input-sm" id="estado_pqr" name="estado_pqr" data-toggle="tooltip" title="ESTADO P.Q.R." required>
                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                                                        <select class="form-control input-text input-sm" id="estado_pqr" name="estado_pqr" disabled="disabled" data-toggle="tooltip" title="ESTADO P.Q.R." required>
                                                                                        <?php
                                                                                    } else { ?>
                                                                                            <select class="form-control input-text input-sm" id="estado_pqr" name="estado_pqr" data-toggle="tooltip" title="ESTADO P.Q.R." required>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                        ?>
                                                                                        <option value="" selected="selected">-</option>
                                                                                        <option value="1">RECIBIDO</option>
                                                                                        <option value="2">RESUELTO</option>
                                                                                        <?php
                                                                                        if (isset($_GET['id_pqr_editar']) || isset($_GET['id_pqr_eliminar'])) { ?>
                                                                                            <input type="hidden" id="estado_pqr_hidden" name="estado_pqr_hidden" value="<?php echo $row_pqr['ESTADO_PQR']; ?>" />
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                            </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_ingreso_pqr">Fec. Ingr.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_ingreso_pqr" data-toogle="tooltip" title="FECHA INGRESO">
                                                                        <?php
                                                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_ingreso_pqr" value="<?php echo $row_pqr['FECHA_INGRESO'] ?>" placeholder="Fecha Ingreso" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_ingreso_pqr" value="<?php echo $row_pqr['FECHA_INGRESO'] ?>" placeholder="Fecha Ingreso" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_ingreso_pqr" value="" placeholder="Fecha Ingreso" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                if (isset($_GET['id_pqr_editar']) || isset($_GET['id_pqr_eliminar'])) { ?>
                                                                    <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_respuesta_pqr">Fec. Resp.:</label>
                                                                <?php
                                                                } else { ?>
                                                                    <label style="text-align: center; padding-top: 4px; white-space: nowrap; color: #CC3300;" id="calculoFechas" class="col-xs-4 control-label row-label" for=""></label>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                if (isset($_GET['id_pqr_editar'])) { ?>
                                                                    <div class="col-xs-3">
                                                                        <div class="input-group date" id="fecha_respuesta_pqr" data-toogle="tooltip" title="FECHA RESPUESTA">
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_respuesta_pqr" value="<?php echo $row_pqr['FECHA_RESPUESTA'] ?>" placeholder="Fecha Respuesta" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                                        <div class="col-xs-3">
                                                                            <div class="input-group date" id="fecha_respuesta_pqr" data-toogle="tooltip" title="FECHA RESPUESTA">
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_respuesta_pqr" value="<?php echo $row_pqr['FECHA_RESPUESTA'] ?>" placeholder="Fecha Respuesta" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_vencimiento_pqr">Fec. Vcto.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_vencimiento_pqr" data-toogle="tooltip" title="FECHA VENCIMIENTO">
                                                                        <?php
                                                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento_pqr" value="<?php echo $row_pqr['FECHA_VENCIMIENTO'] ?>" placeholder="Fecha Vencimiento" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento_pqr" value="<?php echo $row_pqr['FECHA_VENCIMIENTO'] ?>" placeholder="Fecha Vencimiento" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento_pqr" value="" placeholder="Fecha Vencimiento" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                            <?php
                                                            if (isset($_GET['id_pqr_editar']) || isset($_GET['id_pqr_eliminar'])) { ?>
                                                                <div class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="divider"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-12">
                                                                        <div class="styled-select">
                                                                        <?php
                                                                    }
                                                                        ?>
                                                                        <?php
                                                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones_respuesta" name="observaciones_respuesta" rows="4" placeholder="Observaciones Resp." data-toogle="tooltip" title="OBSERVACIONES RESP."><?php echo trim($row_pqr['OBSERVACIONES_RESPUESTA']); ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                        } else {
                                                                            if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                                    <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones_respuesta" name="observaciones_respuesta" rows="4" placeholder="Observaciones Resp." data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES RESP."><?php echo trim($row_pqr['OBSERVACIONES_RESPUESTA']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                                                            }
                                                                        }
                            ?>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN USUARIO</span></h2>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                <div class="col-xs-3">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) {
                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_pqr['ID_COD_DPTO']);
                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                    ?>
                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" <?php echo $tipoDepartamento; ?> />
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) {
                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_pqr['ID_COD_DPTO']);
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
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio">Mpio:</label>
                                <div class="col-xs-3">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) {
                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_pqr['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_pqr['ID_COD_MPIO']);
                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                    ?>
                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                        <input type="hidden" id="abreviatura_municipio" name="abreviatura_municipio" value="<?php echo $row_municipio['ABREVIATURA']; ?>" required="required" />
                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" <?php echo $tipoMunicipio; ?> />
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) {
                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_pqr['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_pqr['ID_COD_MPIO']);
                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                        ?>
                                            <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" />
                                            <input type="hidden" id="abreviatura_municipio" name="abreviatura_municipio" value="<?php echo $row_municipio['ABREVIATURA']; ?>" required="required" />
                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                        <?php
                                        } else { ?>
                                            <input type="hidden" id="id_municipio" name="id_municipio" value="" required="required" />
                                            <input type="hidden" id="abreviatura_municipio" name="abreviatura_municipio" value="" required="required" />
                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="barrio">Barrio:</label>
                                <div class="col-xs-7">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) { ?>
                                        <input type="text" class="form-control input-text input-sm" id="barrio" name="barrio" value="<?php echo $row_pqr['BARRIO']; ?>" placeholder="Barrio" <?php echo $readOnly; ?> data-toogle="tooltip" title="BARRIO" />
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                            <input type="text" class="form-control input-text input-sm" id="barrio" name="barrio" value="<?php echo $row_pqr['BARRIO']; ?>" placeholder="Barrio" readonly="readonly" data-toogle="tooltip" title="BARRIO" />
                                        <?php
                                        } else { ?>
                                            <input type="text" class="form-control input-text input-sm" id="barrio" name="barrio" placeholder="Barrio" data-toogle="tooltip" title="BARRIO" />
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tipo_persona">T. Persona:</label>
                                <div class="col-xs-3">
                                    <div class="styled-select">
                                        <?php
                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                            <select style="<?php echo $styleRequiereVisita; ?>" class="form-control input-text input-sm" id="tipo_persona" name="tipo_persona" data-toggle="tooltip" title="TIPO PERSONA" <?php echo $readOnly; ?> required>
                                                <?php
                                            } else {
                                                if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                    <select class="form-control input-text input-sm" id="tipo_persona" name="tipo_persona" disabled="disabled" data-toggle="tooltip" title="TIPO PERSONA" readonly="readonly" required>
                                                    <?php
                                                } else { ?>
                                                        <select class="form-control input-text input-sm" id="tipo_persona" name="tipo_persona" data-toggle="tooltip" title="TIPO PERSONA" required>
                                                    <?php
                                                }
                                            }
                                                    ?>
                                                    <option value="" selected="selected">-</option>
                                                    <option value="1">NATURAL</option>
                                                    <option value="2">JURÍDICA</option>
                                                    <?php
                                                    if (isset($_GET['id_pqr_editar']) || isset($_GET['id_pqr_eliminar'])) { ?>
                                                        <input type="hidden" id="tipo_persona_hidden" name="tipo_persona_hidden" value="<?php echo $row_pqr['TIPO_PERSONA']; ?>" />
                                                    <?php
                                                    }
                                                    ?>
                                                        </select>
                                    </div>
                                </div>
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="identificacion">Ident.:</label>
                                <div class="col-xs-3">
                                    <div class="input-group date" id="identificacion" data-toogle="tooltip" title="IDENTIFICACIÓN">
                                        <?php
                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                            <span class="input-group-addon">
                                                <span class="fas fa-hashtag"></span>
                                            </span>
                                            <input type="text" class="form-control input-text input-sm" name="identificacion" value="<?php echo $row_pqr['IDENTIFICACION'] ?>" maxlength="20" placeholder="Identificación" <?php echo $readOnly; ?> required="required" onkeypress="return isNumeric(event)" />
                                            <?php
                                        } else {
                                            if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                <span class="input-group-addon">
                                                    <span class="fas fa-hashtag"></span>
                                                </span>
                                                <input type="text" class="form-control input-text input-sm" name="identificacion" value="<?php echo $row_pqr['IDENTIFICACION'] ?>" readonly="readonly" placeholder="Identificación" required="required" />
                                            <?php
                                            } else { ?>
                                                <span class="input-group-addon">
                                                    <span class="fas fa-hashtag"></span>
                                                </span>
                                                <input type="text" class="form-control input-text input-sm" name="identificacion" value="" maxlength="20" placeholder="Identificación" required="required" onkeypress="return isNumeric(event)" />
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nombres">Nombres:</label>
                                <div class="col-xs-5">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) { ?>
                                        <input type="text" class="form-control input-text input-sm" id="nombres" name="nombres" value="<?php echo $row_pqr['NOMBRES']; ?>" placeholder="Nombres" <?php echo $readOnly; ?> data-toogle="tooltip" title="NOMBRES" />
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                            <input type="text" class="form-control input-text input-sm" id="nombres" name="nombres" value="<?php echo $row_pqr['NOMBRES']; ?>" placeholder="Nombres" readonly="readonly" data-toogle="tooltip" title="NOMBRES" />
                                        <?php
                                        } else { ?>
                                            <input type="text" class="form-control input-text input-sm" id="nombres" name="nombres" placeholder="Nombres" data-toogle="tooltip" title="NOMBRES" />
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="apellidos">Apellidos:</label>
                                <div class="col-xs-5">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) { ?>
                                        <input type="text" class="form-control input-text input-sm" id="apellidos" name="apellidos" value="<?php echo $row_pqr['APELLIDOS']; ?>" placeholder="Apellidos" <?php echo $readOnly; ?> data-toogle="tooltip" title="APELLIDOS" />
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                            <input type="text" class="form-control input-text input-sm" id="apellidos" name="apellidos" value="<?php echo $row_pqr['APELLIDOS']; ?>" placeholder="Apellidos" readonly="readonly" data-toogle="tooltip" title="APELLIDOS" />
                                        <?php
                                        } else { ?>
                                            <input type="text" class="form-control input-text input-sm" id="apellidos" name="apellidos" placeholder="Apellidos" data-toogle="tooltip" title="APELLIDOS" />
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="cargo_usuario">Cargo:</label>
                                <div class="col-xs-5">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) {
                                        if ($row_pqr['TIPO_PERSONA'] != 1) { ?>
                                            <input type="text" class="form-control input-text input-sm" id="cargo_usuario" name="cargo_usuario" value="<?php echo $row_pqr['CARGO_USUARIO']; ?>" placeholder="Cargo Usuario" <?php echo $readOnly; ?> data-toogle="tooltip" title="CARGO USUARIO" />
                                        <?php
                                        } else { ?>
                                            <input style="background-color: #EEEEEE;" type="text" class="form-control input-text input-sm" id="cargo_usuario" name="cargo_usuario" value="" readonly="readonly" placeholder="Cargo Usuario" data-toogle="tooltip" title="CARGO USUARIO" />
                                        <?php
                                        }
                                        ?>
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                            <input type="text" class="form-control input-text input-sm" id="cargo_usuario" name="cargo_usuario" value="<?php echo $row_pqr['CARGO_USUARIO']; ?>" placeholder="Cargo Usuario" readonly="readonly" data-toogle="tooltip" title="CARGO USUARIO" />
                                        <?php
                                        } else { ?>
                                            <input type="text" class="form-control input-text input-sm" id="cargo_usuario" name="cargo_usuario" placeholder="Cargo Usuario" data-toogle="tooltip" title="CARGO USUARIO" />
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="direccion">Dirección:</label>
                                <div class="col-xs-5">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) { ?>
                                        <input type="text" class="form-control input-text input-sm" id="direccion" name="direccion" value="<?php echo $row_pqr['DIRECCION']; ?>" placeholder="Dirección" <?php echo $readOnly; ?> data-toogle="tooltip" title="DIRECCIÓN" />
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                            <input type="text" class="form-control input-text input-sm" id="direccion" name="direccion" value="<?php echo $row_pqr['DIRECCION']; ?>" placeholder="Dirección" readonly="readonly" data-toogle="tooltip" title="DIRECCIÓN" />
                                        <?php
                                        } else { ?>
                                            <input type="text" class="form-control input-text input-sm" id="direccion" name="direccion" placeholder="Dirección" data-toogle="tooltip" title="DIRECCIÓN" />
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="telefono">Tel. / Cel.:</label>
                                <div class="col-xs-5">
                                    <div class="input-group date" id="telefono" data-toogle="tooltip" title="TEL. / CEL.">
                                        <?php
                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                            <span class="input-group-addon">
                                                <span class="fas fa-hashtag"></span>
                                            </span>
                                            <input type="text" class="form-control input-text input-sm" name="telefono" value="<?php echo $row_pqr['TELEFONO'] ?>" <?php echo $readOnly; ?> placeholder="Tel. / Cel." required="required" />
                                            <?php
                                        } else {
                                            if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                <span class="input-group-addon">
                                                    <span class="fas fa-hashtag"></span>
                                                </span>
                                                <input type="text" class="form-control input-text input-sm" name="telefono" value="<?php echo $row_pqr['TELEFONO'] ?>" readonly="readonly" placeholder="Tel. / Cel." required="required" />
                                            <?php
                                            } else { ?>
                                                <span class="input-group-addon">
                                                    <span class="fas fa-hashtag"></span>
                                                </span>
                                                <input type="text" class="form-control input-text input-sm" name="telefono" value="" placeholder="Tel. / Cel." required="required" />
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="correo_electronico">Email:</label>
                                <div class="col-xs-5">
                                    <div class="input-group date" id="correo_electronico" data-toogle="tooltip" title="EMAIL">
                                        <?php
                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                            <span class="input-group-addon">
                                                <span class="fas fa-at"></span>
                                            </span>
                                            <input type="text" class="form-control input-text input-sm" name="correo_electronico" value="<?php echo $row_pqr['CORREO_ELECTRONICO'] ?>" <?php echo $readOnly; ?> placeholder="Email" required="required" />
                                            <?php
                                        } else {
                                            if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                <span class="input-group-addon">
                                                    <span class="fas fa-at"></span>
                                                </span>
                                                <input type="text" class="form-control input-text input-sm" name="correo_electronico" value="<?php echo $row_pqr['CORREO_ELECTRONICO'] ?>" readonly="readonly" placeholder="Email" required="required" />
                                            <?php
                                            } else { ?>
                                                <span class="input-group-addon">
                                                    <span class="fas fa-at"></span>
                                                </span>
                                                <input type="text" class="form-control input-text input-sm" name="correo_electronico" value="" placeholder="Email" required="required" />
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN P.Q.R.</span></h2>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tipo_asunto_pqr">T. Asunto:</label>
                                <div class="col-xs-5">
                                    <?php
                                    if (isset($_GET['id_pqr_editar'])) {
                                        $query_select_tipo_asunto = mysqli_query($connection, "SELECT * FROM tipo_asuntos_pqr_2 WHERE ID_TIPO_ASUNTO = " . $row_pqr['ID_TIPO_ASUNTO']);
                                        $row_tipo_asunto = mysqli_fetch_array($query_select_tipo_asunto);
                                    ?>
                                        <input type="hidden" id="id_tipo_asunto_pqr" name="id_tipo_asunto_pqr" value="<?php echo $row_tipo_asunto['ID_TIPO_ASUNTO']; ?>" required />
                                        <input type="hidden" id="abreviatura_tipo_asunto_pqr" name="abreviatura_tipo_asunto_pqr" value="<?php echo $row_tipo_asunto['ABREVIATURA']; ?>" required />
                                        <input type="text" class="form-control input-text input-sm" id="tipo_asunto_pqr" name="tipo_asunto_pqr" value="<?php echo $row_tipo_asunto['NOMBRE']; ?>" placeholder="Tipo Asunto" data-toggle="tooltip" readonly="readonly" title="TIPO ASUNTO" <?php echo $tipoAsunto; ?> required />
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) {
                                            $query_select_tipo_asunto = mysqli_query($connection, "SELECT * FROM tipo_asuntos_pqr_2 WHERE ID_TIPO_ASUNTO = " . $row_pqr['ID_TIPO_ASUNTO']);
                                            $row_tipo_asunto = mysqli_fetch_array($query_select_tipo_asunto);
                                        ?>
                                            <input type="hidden" id="id_tipo_asunto_pqr" name="id_tipo_asunto_pqr" value="<?php echo $row_tipo_asunto['ID_TIPO_ASUNTO']; ?>" />
                                            <input type="hidden" id="abreviatura_tipo_asunto_pqr" name="abreviatura_tipo_asunto_pqr" value="<?php echo $row_tipo_asunto['ABREVIATURA']; ?>" required="required" />
                                            <input type="text" class="form-control input-text input-sm" id="tipo_asunto_pqr" name="tipo_asunto_pqr" value="<?php echo $row_tipo_asunto['NOMBRE']; ?>" placeholder="Tipo Asunto" data-toggle="tooltip" readonly="readonly" title="TIPO ASUNTO" />
                                        <?php
                                        } else { ?>
                                            <input type="hidden" id="id_tipo_asunto_pqr" name="id_tipo_asunto_pqr" value="" required />
                                            <input type="hidden" id="abreviatura_tipo_asunto_pqr" name="abreviatura_tipo_asunto_pqr" value="" required />
                                            <input type="text" class="form-control input-text input-sm" id="tipo_asunto_pqr" name="tipo_asunto_pqr" placeholder="Tipo Asunto" data-toggle="tooltip" readonly="readonly" title="TIPO ASUNTO" onclick="tipoAsuntoPQR()" required />
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="peticion">Petición:</label>
                                <div class="col-xs-2">
                                    <div class="styled-select">
                                        <?php
                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                            <select style="<?php echo $stylePeticion; ?>" class="form-control input-text input-sm" id="peticion" name="peticion" data-toggle="tooltip" title="PETICIÓN" <?php echo $readOnly; ?> required>
                                                <?php
                                            } else {
                                                if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                    <select class="form-control input-text input-sm" id="peticion" name="peticion" disabled="disabled" data-toggle="tooltip" title="PETICIÓN" readonly="readonly" required>
                                                    <?php
                                                } else { ?>
                                                        <select class="form-control input-text input-sm" id="peticion" name="peticion" data-toggle="tooltip" title="PETICIÓN" required>
                                                    <?php
                                                }
                                            }
                                                    ?>
                                                    <option value="" selected="selected">-</option>
                                                    <option value="1">ESCRITA</option>
                                                    <option value="2">VERBAL</option>
                                                    <?php
                                                    if (isset($_GET['id_pqr_editar']) || isset($_GET['id_pqr_eliminar'])) { ?>
                                                        <input type="hidden" id="peticion_hidden" name="peticion_hidden" value="<?php echo $row_pqr['PETICION']; ?>" />
                                                    <?php
                                                    }
                                                    ?>
                                                        </select>
                                    </div>
                                </div>
                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="requiere_visita">Req. Visita:</label>
                                <div class="col-xs-2">
                                    <div class="styled-select">
                                        <?php
                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                            <select style="<?php echo $styleRequiereVisita; ?>" class="form-control input-text input-sm" id="requiere_visita" name="requiere_visita" data-toggle="tooltip" title="REQUIERE VISITA" <?php echo $readOnly; ?> required>
                                                <?php
                                            } else {
                                                if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                    <select class="form-control input-text input-sm" id="requiere_visita" name="requiere_visita" disabled="disabled" data-toggle="tooltip" title="REQUIERE VISITA" readonly="readonly" required>
                                                    <?php
                                                } else { ?>
                                                        <select class="form-control input-text input-sm" id="requiere_visita" name="requiere_visita" data-toggle="tooltip" title="REQUIERE VISITA" required>
                                                    <?php
                                                }
                                            }
                                                    ?>
                                                    <option value="" selected="selected">-</option>
                                                    <option value="1">SI</option>
                                                    <option value="2">NO</option>
                                                    <?php
                                                    if (isset($_GET['id_pqr_editar']) || isset($_GET['id_pqr_eliminar'])) { ?>
                                                        <input type="hidden" id="requiere_visita_hidden" name="requiere_visita_hidden" value="<?php echo $row_pqr['REQUIERE_VISITA']; ?>" />
                                                    <?php
                                                    }
                                                    ?>
                                                        </select>
                                    </div>
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
                                        if (isset($_GET['id_pqr_editar'])) { ?>
                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"><?php echo trim($row_pqr['OBSERVACIONES']); ?></textarea>
                                            <?php
                                        } else {
                                            if (isset($_GET['id_pqr_eliminar'])) { ?>
                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES"><?php echo trim($row_pqr['OBSERVACIONES']); ?></textarea>
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
                                    if (isset($_GET['id_pqr_editar'])) { ?>
                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_pqr" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar P.Q.R.</button>&nbsp;&nbsp;
                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'P_Q_R.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                        <?php
                                    } else {
                                        if (isset($_GET['id_pqr_eliminar'])) { ?>
                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_pqr" type="button" data-toggle="modal" data-target="#modalEliminarPQR"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar P.Q.R.</button>&nbsp;&nbsp;
                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'P_Q_R.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                        <?php
                                        } else { ?>
                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_pqr" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear P.Q.R.</button>&nbsp;&nbsp;
                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldPQR();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            </form>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="cargar_archivo_pqr_tab">
                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_archivo_pqr" name="cargar_archivo_pqr" action="Modelo/Subir_Archivos.php?archivo=pqr" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id_tabla_archivo" id="id_tabla_archivo" value="<?php echo $_GET['id_pqr_editar']; ?>" />
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
                                    function file_size($url)
                                    {
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
                                    $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                        . " WHERE ID_DEPARTAMENTO = " . $row_pqr['ID_COD_DPTO'] . ""
                                        . "   AND ID_MUNICIPIO = " . $row_pqr['ID_COD_MPIO']);
                                    $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                                    $path = "Files/" . $row_nombre_municipio['NOMBRE'] . "/";
                                    $query_select_files_pqr = mysqli_query($connection, "SELECT * "
                                        . "  FROM pqr_archivos_2 "
                                        . " WHERE ID_TABLA_PQR = " . $_GET['id_pqr_editar']);
                                    if (mysqli_num_rows($query_select_files_pqr) != 0) {
                                        while ($row_files = mysqli_fetch_assoc($query_select_files_pqr)) {
                                            $info_pqr[] = $row_files['NOMBRE_ARCHIVO'];
                                            $info_id_pqr[] = $row_files['ID_TABLA'];
                                        }
                                        if ($handle = opendir($path)) {
                                            foreach (array_combine($info_id_pqr, $info_pqr) as $id_files => $files) {
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
                                                    . "   <td style='vertical-align: middle;'><a href='Modelo/Eliminar_Archivos.php?file=" . $files . "&id_tabla_archivo=" . $_GET['id_pqr_editar'] . "&file_id=" . $id_files . "&archivo=pqr'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16' /></button></a></td>"
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
                                                <th style='width: 5%;'>ELIMINAR</th>
                                            </thead>
                                            <tbody>
                                                <?php echo $theList; ?>
                                            </tbody>
                                        </table>
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
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="Javascript/bootstrap.min.js"></script>
<script src="Javascript/menu.js"></script>
<script src="Javascript/moment-with-locales.js"></script>
<script src="Javascript/bootstrap-datetimepicker.js"></script>
<script src="Javascript/jquery.twbsPagination.js"></script>
<script src="Javascript/custom-file-input.js"></script>
<script src="http://malsup.github.io/jquery.form.js"></script>
<!--Progress Bar Script with Form-->
<script>
    function resetFieldPQR() {
        $("#id_departamento").val("");
        $("#id_municipio").val("");
        $("#abreviatura_municipio").val("");
        $("#id_tipo_asunto_pqr").val("");
        $("#abreviatura_tipo_asunto_pqr").val("");
        $("input[name=fecha_ingreso_pqr]").focus();
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

    function generarRadicado() {
        var abreviatura_tipo_asunto_pqr = $("#abreviatura_tipo_asunto_pqr").val();
        var abreviatura_municipio = $("#abreviatura_municipio").val();
        var fecha_ingreso_pqr = $("input[name=fecha_ingreso_pqr]").val();
        $("#radicado_pqr").val(abreviatura_tipo_asunto_pqr + "" + abreviatura_municipio + "" + fecha_ingreso_pqr.replaceAll("-", ""));
        /*switch(consecutivo_municipio.length) {
            case 1:
                $("#radicado_pqr").val(abreviatura_tipo_asunto_pqr + "" + abreviatura_municipio + "" + fecha_ingreso_pqr.replaceAll("-", "") + "00" + consecutivo_municipio);
                //alert(abreviatura_tipo_asunto_pqr + "" + abreviatura_municipio + "" + fecha_ingreso_pqr.replaceAll("-", "") + "00" + consecutivo_municipio);
                break;
            case 2:
                $("#radicado_pqr").val(abreviatura_tipo_asunto_pqr + "" + abreviatura_municipio + "" + fecha_ingreso_pqr.replaceAll("-", "") + "0" + consecutivo_municipio);
                //alert(abreviatura_tipo_asunto_pqr + "" + abreviatura_municipio + "" + fecha_ingreso_pqr.replaceAll("-", "") + "0" + consecutivo_municipio);
                break;
            case 3:
                $("#radicado_pqr").val(abreviatura_tipo_asunto_pqr + "" + abreviatura_municipio + "" + fecha_ingreso_pqr.replaceAll("-", "") + "" + consecutivo_municipio);
                //alert(abreviatura_tipo_asunto_pqr + "" + abreviatura_municipio + "" + fecha_ingreso_pqr.replaceAll("-", "") + "" + consecutivo_municipio);
                break;
        }*/
    }
    //POPUPS
    function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
        if (id_consulta == 1) {
            $("#id_departamento").val(id_departamento);
            $("#departamento").val(departamento);
            $("#id_tipo_asunto_pqr").val("");
            $("#tipo_asunto_pqr").val("");
            $("#id_municipio").val("");
            $("#municipio").val("");
            $("#municipio").focus();
        }
    }

    function tipoDepartamento(id_consulta) {
        window.open("Combos/Tipo_Departamento_Visita.php?id_consulta=" + id_consulta, "Popup", "width=400, height=500");
    }

    function infoTipoMunicipio(id_consulta, id_municipio, municipio, abreviatura_municipio) {
        if (id_consulta == 1) {
            $("#id_municipio").val(id_municipio);
            $("#municipio").val(municipio);
            $("#abreviatura_municipio").val(abreviatura_municipio);
            $("#id_tipo_asunto_pqr").val("");
            $("#tipo_asunto_pqr").val("");
            $("#barrio").val("");
            $("#barrio").focus();
        }
    }

    function tipoMunicipio(id_consulta) {
        var id_departamento;
        if (id_consulta == 1) {
            id_departamento = $("#id_departamento").val();
        }
        window.open("Combos/Tipo_Municipio_Visita.php?id_departamento=" + id_departamento + "&id_consulta=" + id_consulta, "Popup", "width=400, height=500");
    }

    function infoTipoAsuntoPQR(id_tipo_asunto_pqr, tipo_asunto_pqr, abreviatura_tipo_asunto_pqr) {
        $("#id_tipo_asunto_pqr").val(id_tipo_asunto_pqr);
        $("#tipo_asunto_pqr").val(tipo_asunto_pqr);
        $("#abreviatura_tipo_asunto_pqr").val(abreviatura_tipo_asunto_pqr);
        generarRadicado();
    }

    function tipoAsuntoPQR() {
        var fecha_ingreso_pqr = $("input[name=fecha_ingreso_pqr]").val();
        var id_departamento = $("#id_departamento").val();
        var id_municipio = $("#id_municipio").val();
        var id_pqr_editar = $("#id_pqr_editar_hidden").val();
        if (id_pqr_editar == undefined) {
            id_pqr_editar = 0;
        }
        window.open("Combos/Tipo_Asunto_PQR.php?id_departamento=" + id_departamento + "&id_municipio=" + id_municipio + "&id_pqr_editar=" + id_pqr_editar + "&fecha_ingreso_pqr=" + fecha_ingreso_pqr, "Popup", "width=400, height=500");
    }
    //END POPUPS
</script>
<script>
    $(document).ready(function() {
        $("#buscar_pqr").focus();
        var id_pqr_editar = $("#id_pqr_editar_hidden").val();
        var id_pqr_eliminar = $("#id_pqr_eliminar_hidden").val();
        if (id_pqr_editar != undefined) {
            $(".nav-pills a[href='#crear_pqr_tab']").tab("show");
            $(".nav-pills a[href='#crear_pqr_tab']").text("Actualizar P.Q.R.");
            $("#estado_pqr").val($("#estado_pqr_hidden").val());
            $("#tipo_persona").val($("#tipo_persona_hidden").val());
            $("#peticion").val($("#peticion_hidden").val());
            $("#requiere_visita").val($("#requiere_visita_hidden").val());
            $("#fecha_respuesta_pqr").datetimepicker({
                format: 'YYYY-MM-DD',
                daysOfWeekDisabled: [0, 6],
                disabledDates: ['2023-01-09', '2023-03-20', '2023-04-06', '2023-04-07', '2023-05-01',
                                '2023-05-22', '2023-06-12', '2023-06-19', '2023-07-03', '2023-07-20', '2023-08-07',
                                '2023-08-21', '2023-10-16', '2023-11-06', '2023-11-13', '2023-12-08', '2023-12-25'
                    ],
                useCurrent: false
            });
        } else {
            if (id_pqr_eliminar != undefined) {
                $(".nav-pills a[href='#crear_pqr_tab']").tab("show");
                $(".nav-pills a[href='#crear_pqr_tab']").text("Eliminar P.Q.R.");
                $("#estado_pqr").val($("#estado_pqr_hidden").val());
                $("#tipo_persona").val($("#tipo_persona_hidden").val());
                $("#peticion").val($("#peticion_hidden").val());
                $("#requiere_visita").val($("#requiere_visita_hidden").val());
                $("#tipo_persona").css("pointer-events", "none");
                $("#peticion").css("pointer-events", "none");
                $("#requiere_visita").css("pointer-events", "none");
                $("#fecha_respuesta_pqr").datetimepicker({
                    format: 'YYYY-MM-DD',
                    daysOfWeekDisabled: [0, 6],
                    disabledDates: ['2023-01-09', '2023-03-20', '2023-04-06', '2023-04-07', '2023-05-01',
                                    '2023-05-22', '2023-06-12', '2023-06-19', '2023-07-03', '2023-07-20', '2023-08-07',
                                    '2023-08-21', '2023-10-16', '2023-11-06', '2023-11-13', '2023-12-08', '2023-12-25'
                    ],
                    useCurrent: false
                });
            }
        }
        $("#fecha_ingreso_pqr").on('dp.hide', function() {
            $("#id_tipo_asunto_pqr").val("");
            $("#tipo_asunto_pqr").val("");
            $("#radicado_pqr").val("");
        });
        $("#tipo_persona").change(function() {
            switch ($(this).val()) {
                case '1':
                    $("#cargo_usuario").val("");
                    $("#cargo_usuario").attr("readonly", true);
                    $("#cargo_usuario").css("background-color", "#EEEEEE");
                    break;
                case '2':
                    $("#cargo_usuario").val("");
                    $("#cargo_usuario").attr("readonly", false);
                    $("#cargo_usuario").css("background-color", "#FFFFFF");
                    break;
                default:
                    $("#cargo_usuario").val("");
                    $("#cargo_usuario").attr("readonly", false);
                    $("#cargo_usuario").css("background-color", "#FFFFFF");
                    break;
            }
        });
        $("#buscar_pqr").keypress(function(e) {
            if (e.which == 13) {
                var busqueda_pqr;
                if ($(this).val() == "") {
                    busqueda_pqr = "";
                } else {
                    busqueda_pqr = $(this).val().toUpperCase();
                }
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_PQR.php",
                    dataType: "json",
                    data: "sw=1&busqueda_pqr=" + busqueda_pqr,
                    success: function(data) {
                        $("#pagination-pqr").twbsPagination('destroy');
                        $("#pagination-pqr").twbsPagination({
                            totalPages: data[0],
                            visiblePages: 15,
                            first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                            prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                            next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                            last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                            onPageClick: function(event, page) {
                                $("#loading-spinner").css('display', 'block');
                                $.ajax({
                                    type: "POST",
                                    url: "Modelo/Cargar_PQR.php",
                                    dataType: "json",
                                    data: "sw=1&busqueda_pqr=" + data[1] + "&page=" + page,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_pqr").html(data[0]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
        $("#fecha_ingreso_pqr").datetimepicker({
            format: 'YYYY-MM-DD',
            daysOfWeekDisabled: [0, 6],
            disabledDates: ['2023-01-09', '2023-03-20', '2023-04-06', '2023-04-07', '2023-05-01',
                            '2023-05-22', '2023-06-12', '2023-06-19', '2023-07-03', '2023-07-20', '2023-08-07',
                            '2023-08-21', '2023-10-16', '2023-11-06', '2023-11-13', '2023-12-08', '2023-12-25'
            ],
            useCurrent: false
        });
        $("#fecha_vencimiento_pqr").datetimepicker({
            format: 'YYYY-MM-DD',
            daysOfWeekDisabled: [0, 6],
            disabledDates: ['2023-01-09', '2023-03-20', '2023-04-06', '2023-04-07', '2023-05-01',
                            '2023-05-22', '2023-06-12', '2023-06-19', '2023-07-03', '2023-07-20', '2023-08-07',
                            '2023-08-21', '2023-10-16', '2023-11-06', '2023-11-13', '2023-12-08', '2023-12-25'
            ],
            useCurrent: false
        });
        /*$("#fecha_vencimiento_pqr").on('dp.change', function() {
            var fecha_ingreso = $("input[name=fecha_ingreso_pqr]").val();
            var fecha_vencimiento = $("input[name=fecha_vencimiento_pqr]").val();
            var dias_restantes = (((Date.parse(fecha_vencimiento) - Date.parse(fecha_ingreso)) / 60 / 60 / 24) / 1000) - 6;
            $("#calculoFechas").html(dias_restantes + " Días para Responder");
        });*/
        $("#tab_info_pqr").on("shown.bs.tab", function() {
            $("#buscar_pqr").focus();
        });
        $("#tab_crear_pqr").on("shown.bs.tab", function() {
            $("input[name=fecha_ingreso_pqr]").focus();
        });
        $("#tab_info_pqr").on("click", function() {
            $("#buscar_pqr").focus();
        });
        $("#tab_crear_pqr").on("click", function() {
            $("input[name=fecha_ingreso_pqr]").focus();
        });
        if (id_pqr_editar == undefined && id_pqr_eliminar == undefined) {
            $("#btn_crear_pqr").click(function() {
                var radicado_pqr = $("#radicado_pqr").val();
                var fecha_ingreso_pqr = $("input[name=fecha_ingreso_pqr]").val();
                var fecha_vencimiento_pqr = $("input[name=fecha_vencimiento_pqr]").val();
                var estado_pqr = $("#estado_pqr").val();
                var departamento = $("#id_departamento").val();
                var municipio = $("#id_municipio").val();
                var barrio = $("#barrio").val();
                var tipo_persona = $("#tipo_persona").val();
                var identificacion = $("input[name=identificacion]").val();
                var nombres = $("#nombres").val();
                var apellidos = $("#apellidos").val();
                var cargo_usuario = $("#cargo_usuario").val();
                var direccion = $("#direccion").val();
                var telefono = $("input[name=telefono]").val();
                var correo_electronico = $("input[name=correo_electronico]").val();
                var tipo_asunto_pqr = $("#id_tipo_asunto_pqr").val();
                var peticion = $("#peticion").val();
                var requiere_visita = $("#requiere_visita").val();
                var observaciones = $("#observaciones").val();
                /*alert(radicado_pqr + " - " + fecha_ingreso_pqr + " - " + estado_pqr + " - " + departamento + " - " + municipio + " - " +
                      barrio + " - " + tipo_persona + " - " + identificacion + " - " + nombres + " - " + apellidos + " - " + cargo_usuario + " - " +
                      direccion + " - " + telefono + " - " + correo_electronico + " - " + tipo_asunto_pqr + " - " + peticion + " - " +
                      requiere_visita + " - " + observaciones);*/
                if (fecha_ingreso_pqr.length == 0) {
                    $("input[name=fecha_ingreso_pqr]").focus();
                    return false;
                }
                if (fecha_vencimiento_pqr.length == 0) {
                    $("input[name=fecha_vencimiento_pqr]").focus();
                    return false;
                }
                if (estado_pqr.length == 0) {
                    $("#estado_pqr").focus();
                    return false;
                }
                if (departamento.length == 0) {
                    $("#departamento").focus();
                    return false;
                }
                if (municipio.length == 0) {
                    $("#municipio").focus();
                    return false;
                }
                if (barrio.length == 0) {
                    $("#barrio").focus();
                    return false;
                }
                if (tipo_persona.length == 0) {
                    $("#tipo_persona").focus();
                    return false;
                }
                if (identificacion.length == 0) {
                    $("input[name=identificacion]").focus();
                    return false;
                }
                if (nombres.length == 0) {
                    $("#nombres").focus();
                    return false;
                }
                if (apellidos.length == 0) {
                    $("#apellidos").focus();
                    return false;
                }
                if (tipo_asunto_pqr.length == 0) {
                    $("#tipo_asunto_pqr").focus();
                    return false;
                }
                if (peticion.length == 0) {
                    $("#peticion").focus();
                    return false;
                }
                if (requiere_visita.length == 0) {
                    $("#requiere_visita").focus();
                    return false;
                }
                $("#btn_crear_pqr").attr("disabled", true);
                $("#btn_crear_pqr").css("pointer-events", "none");
                $("#btn_crear_pqr").html("Creando P.Q.R....");
                $.ajax({
                    type: "POST",
                    data: "radicado_pqr=" + radicado_pqr +
                        "&fecha_ingreso_pqr=" + fecha_ingreso_pqr +
                        "&fecha_vencimiento_pqr=" + fecha_vencimiento_pqr +
                        "&estado_pqr=" + estado_pqr +
                        "&id_departamento=" + departamento +
                        "&id_municipio=" + municipio +
                        "&barrio=" + barrio +
                        "&tipo_persona=" + tipo_persona +
                        "&identificacion=" + identificacion +
                        "&nombres=" + nombres +
                        "&apellidos=" + apellidos +
                        "&cargo_usuario=" + cargo_usuario +
                        "&direccion=" + direccion +
                        "&telefono=" + telefono +
                        "&correo_electronico=" + correo_electronico +
                        "&tipo_asunto_pqr=" + tipo_asunto_pqr +
                        "&peticion=" + peticion +
                        "&requiere_visita=" + requiere_visita +
                        "&observaciones=" + observaciones,
                    url: "Modelo/Crear_PQR.php",
                    success: function(data) {
                        //alert(data);
                        //$("#observaciones").val(data);
                        document.location.href = 'P_Q_R.php?id_pqr_editar=' + data;
                    }
                });
            });
        }
        $("#eliminar_pqr").click(function() {
            $("#crear_pqr").submit();
        });
        $.ajax({
            type: "POST",
            url: "Modelo/Cargar_Paginacion_PQR.php",
            dataType: "json",
            data: "sw=0",
            success: function(data) {
                $("#pagination-pqr").twbsPagination('destroy');
                $("#pagination-pqr").twbsPagination({
                    totalPages: data[0],
                    visiblePages: 15,
                    first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                    prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                    next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                    last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                    onPageClick: function(event, page) {
                        $("#loading-spinner").css('display', 'block');
                        $.ajax({
                            type: "POST",
                            url: "Modelo/Cargar_PQR.php",
                            dataType: "json",
                            data: "sw=0&page=" + page,
                            success: function(data) {
                                $("#loading-spinner").css('display', 'none');
                                $("#resultado_pqr").html(data[0]);
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
            $("#cargar_archivo_pqr").ajaxSubmit({
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
        $('input[type=text][name=radicado_pqr]').tooltip({
            container: "body",
            placement: "right"
        });
        $('#fecha_ingreso_pqr').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_vencimiento_pqr').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_respuesta_pqr').tooltip({
            container: "body",
            placement: "top"
        });
        $('select[name=estado_pqr]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=departamento]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=municipio]').tooltip({
            container: "body",
            placement: "right"
        });
        $('input[type=text][name=barrio]').tooltip({
            container: "body",
            placement: "right"
        });
        $('select[name=tipo_persona]').tooltip({
            container: "body",
            placement: "top"
        });
        $('#identificacion').tooltip({
            container: "body",
            placement: "right"
        });
        $('input[type=text][name=nombres]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=apellidos]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=cargo_usuario]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=direccion]').tooltip({
            container: "body",
            placement: "top"
        });
        $('#telefono').tooltip({
            container: "body",
            placement: "top"
        });
        $('#correo_electronico').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=tipo_asunto_pqr]').tooltip({
            container: "body",
            placement: "top"
        });
        $('select[name=peticion]').tooltip({
            container: "body",
            placement: "top"
        });
        $('select[name=requiere_visita]').tooltip({
            container: "body",
            placement: "top"
        });
        $('textarea[name=observaciones]').tooltip({
            container: "body",
            placement: "top"
        });
        $('textarea[name=observaciones_respuesta]').tooltip({
            container: "body",
            placement: "top"
        });
        $("#menu_home").tooltip({
            container: "body",
            placement: "top"
        });
    });
</script>

</html>