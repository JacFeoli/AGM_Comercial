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
    <title>AGM - Recaudo Operadores</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="Css/AGM_Style.css" />
    <link rel="stylesheet" href="Css/bootstrap.min.css" />
    <link rel="stylesheet" href="Css/menu_style.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
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
<!--Eliminar Recaudo Operadores Modal-->
<div class="modal fade" id="modalEliminarRecaOper" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Eliminar Recaudo Operadores</h4>
            </div>
            <div class="modal-body">
                <p>¿Desea eliminar el Recaudo?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_reca_oper" name="eliminar_reca_oper"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Eliminar Recaudo Operadores Modal-->

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
                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-lightbulb"></i>
                                                                    <span>Operadores</span>
                                                                </a>
                                                                <div style="display: block;" class="sidebar-submenu">
                                                                    <ul class="nav nav-pills nav-stacked">
                                                                        <li>
                                                                            <a href='Recaudo_Operadores.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check"></i>
                                                                                <span>Recaudo</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Facturacion_Operadores.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                                <span>Facturación</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Reportes_Operadores.php'>
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
                                        <h1>Recaudo Operadores</h1>
                                        <h2></h2>
                                        <ul class="nav nav-pills" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#informacion_reca_operadores_tab" id="tab_info_reca_operadores" aria-controls="informacion_reca_operadores_tab" role="tab" data-toggle="tab">Información Reca. Operadores</a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#crear_reca_operadores_tab" id="tab_crear_reca_operadores" aria-controls="crear_reca_operadores_tab" role="tab" data-toggle="tab">Crear Reca. Operador</a>
                                            </li>
                                            <?php
                                            if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                <li role="presentation">
                                                    <a href="#cargar_soporte_recaudo_tab" id="tab_cargar_soporte_recaudo" aria-controls="cargar_soporte_recaudo_tab" role="tab" data-toggle="tab">Cargar Soporte Recaudo</a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <h2></h2>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active" id="informacion_reca_operadores_tab">
                                                <input class="form-control input-text input-sm" type="text" placeholder="Buscar Recaudo" name="buscar_recaudo" id="buscar_recaudo" />
                                                <br />
                                                <?php
                                                $query_select_reca_oper = "SELECT * FROM recaudo_operadores_2 ORDER BY ID_FACTURACION DESC";
                                                $sql_reca_oper = mysqli_query($connection, $query_select_reca_oper);
                                                if (mysqli_num_rows($sql_reca_oper) != 0) {
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table class='table table-condensed table-hover'>";
                                                    echo "<thead>";
                                                    echo "<tr>";
                                                    echo "<th width=4%>ESTADO</th>";
                                                    echo "<th width=12%>DEPARTAMENTO</th>";
                                                    echo "<th width=17%>MUNICIPIO</th>";
                                                    echo "<th width=38%>OPERADOR</th>";
                                                    echo "<th width=8%>PERIODO</th>";
                                                    echo "<th width=11%>VALOR RECA.</th>";
                                                    echo "<th width=5%>DETALLE</th>";
                                                    echo "<th width=5%>ELIMINAR</th>";
                                                    echo "</tr>";
                                                    echo "</thead>";
                                                    echo "<tbody id='resultado_reca_oper'>";

                                                    echo "</tbody>";
                                                    echo "</table>";
                                                    echo "</div>";
                                                    echo "<p></p>";
                                                    echo "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE PAGO.</span>";
                                                } else {
                                                    echo "<p class='message'>No se encontraron Recaudos Operadores Creados.</p>";
                                                }
                                                ?>
                                                <div id="div-pagination">
                                                    <ul id="pagination-reca_oper"></ul>
                                                    <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="crear_reca_operadores_tab">
                                                <?php
                                                if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_operador" name="crear_reca_operador" action="<?php echo "Modelo/Crear_Reca_Oper.php?editar=" . $_GET['id_reca_operador_editar']; ?>" method="post">
                                                        <?php
                                                        $query_select_reca_operador = mysqli_query($connection, "SELECT * FROM recaudo_operadores_2 WHERE ID_RECAUDO = " . $_GET['id_reca_operador_editar']);
                                                        $row_reca_operador = mysqli_fetch_array($query_select_reca_operador);
                                                        ?>
                                                        <input type="hidden" id="id_reca_operador_editar_hidden" name="id_reca_operador_editar_hidden" value="<?php echo $row_reca_operador['ID_RECAUDO']; ?>" />
                                                        <?php
                                                    } else {
                                                        if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_operador" name="crear_reca_operador" action="<?php echo "Modelo/Crear_Reca_Oper.php?eliminar=" . $_GET['id_reca_operador_eliminar']; ?>" method="post">
                                                                <?php
                                                                $query_select_reca_operador = mysqli_query($connection, "SELECT * FROM recaudo_operadores_2 WHERE ID_RECAUDO = " . $_GET['id_reca_operador_eliminar']);
                                                                $row_reca_operador = mysqli_fetch_array($query_select_reca_operador);
                                                                ?>
                                                                <input type="hidden" id="id_reca_operador_eliminar_hidden" name="id_reca_operador_eliminar_hidden" value="<?php echo $row_reca_operador['ID_RECAUDO']; ?>" />
                                                            <?php
                                                        } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_operador" name="crear_reca_operador" action="<?php echo "Modelo/Crear_Reca_Oper.php"; ?>" method="post">
                                                                <?php
                                                            }
                                                                ?>
                                                            <?php
                                                        }
                                                            ?>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_recaudo">Fech. Reca.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_recaudo" data-toogle="tooltip" title="FECHA RECAUDO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_recaudo" value="<?php echo $row_reca_operador['FECHA_RECAUDO'] ?>" placeholder="Fecha Recaudo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_recaudo" value="<?php echo $row_reca_operador['FECHA_RECAUDO'] ?>" placeholder="Fecha Recaudo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_recaudo" value="" placeholder="Fecha Recaudo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_pago_bitacora">Fech. Bit.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_pago_bitacora" data-toogle="tooltip" title="FECHA BITACORA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="<?php echo $row_reca_operador['FECHA_PAGO_BITACORA'] ?>" placeholder="Fecha Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="<?php echo $row_reca_operador['FECHA_PAGO_BITACORA'] ?>" placeholder="Fecha Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="" placeholder="Fecha Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="estado_recaudo">Est. Reca.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="styled-select">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="estado_recaudo" name="estado_recaudo" data-toggle="tooltip" title="ESTADO RECAUDO" required>
                                                                                <?php
                                                                            } else {
                                                                                if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                    <select class="form-control input-text input-sm" id="estado_recaudo" name="estado_recaudo" disabled="disabled" data-toggle="tooltip" title="ESTADO RECAUDO" required>
                                                                                    <?php
                                                                                } else { ?>
                                                                                        <select class="form-control input-text input-sm" id="estado_recaudo" name="estado_recaudo" data-toggle="tooltip" title="ESTADO RECAUDO" required>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                                    ?>
                                                                                    <option value="" selected="selected">-</option>
                                                                                    <option value="1">PAGADO</option>
                                                                                    <option value="2">PENDIENTE DE PAGO</option>
                                                                                    <?php
                                                                                    if (isset($_GET['id_reca_operador_editar']) || isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                        <input type="hidden" id="estado_recaudo_hidden" name="estado_recaudo_hidden" value="<?php echo $row_reca_operador['ESTADO_RECAUDO']; ?>" />
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN OPERADOR / CLIENTE</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="operador">Operador:</label>
                                                                <div class="col-xs-7">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_operador_editar'])) {
                                                                        $query_select_operador = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM operadores_2 O, "
                                                                            . "       facturacion_operadores_2 FO, "
                                                                            . "       recaudo_operadores_2 RO "
                                                                            . " WHERE RO.ID_FACTURACION = FO.ID_FACTURACION "
                                                                            . "   AND FO.ID_OPERADOR = O.ID_OPERADOR "
                                                                            . "   AND RO.ID_FACTURACION = " . $row_reca_operador['ID_FACTURACION']);
                                                                        $row_operador = mysqli_fetch_array($query_select_operador);
                                                                    ?>
                                                                        <input type="hidden" id="id_operador" name="id_operador" value="<?php echo $row_operador['ID_OPERADOR']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="operador" name="operador" value="<?php echo $row_operador['NOMBRE']; ?>" placeholder="Operador" required="required" data-toggle="tooltip" readonly="readonly" title="OPERADOR" onclick="operadorReca()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_operador_eliminar'])) {
                                                                            $query_select_operador = mysqli_query($connection, "SELECT * "
                                                                                . "  FROM operadores_2 O, "
                                                                                . "       facturacion_operadores_2 FO, "
                                                                                . "       recaudo_operadores_2 RO "
                                                                                . " WHERE RO.ID_FACTURACION = FO.ID_FACTURACION "
                                                                                . "   AND FO.ID_OPERADOR = O.ID_OPERADOR "
                                                                                . "   AND RO.ID_FACTURACION = " . $row_reca_operador['ID_FACTURACION']);
                                                                            $row_operador = mysqli_fetch_array($query_select_operador);
                                                                        ?>
                                                                            <input type="hidden" id="id_operador" name="id_operador" value="<?php echo $row_operador['ID_OPERADOR']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="operador" name="operador" value="<?php echo $row_operador['NOMBRE']; ?>" placeholder="Operador" data-toggle="tooltip" readonly="readonly" title="OPERADOR" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_operador" name="id_operador" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="operador" name="operador" placeholder="Operador" required="required" data-toggle="tooltip" readonly="readonly" title="OPERADOR" onclick="operadorReca()" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nit_operador">NIT:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_operador" name="nit_operador" value="<?php echo $row_operador['NIT_OPERADOR']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_operador" name="nit_operador" value="<?php echo $row_operador['NIT_OPERADOR']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_operador" name="nit_operador" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_operador_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM departamentos_operadores_2 "
                                                                            . " WHERE ID_OPERADOR = " . $row_operador['ID_OPERADOR'] . " "
                                                                            . "   AND ID_DEPARTAMENTO = " . $row_operador['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_operador_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * "
                                                                                . "  FROM departamentos_operadores_2 "
                                                                                . " WHERE ID_OPERADOR = " . $row_operador['ID_OPERADOR'] . " "
                                                                                . "   AND ID_DEPARTAMENTO = " . $row_operador['ID_COD_DPTO']);
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
                                                                    if (isset($_GET['id_reca_operador_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM municipios_operadores_2 "
                                                                            . " WHERE ID_OPERADOR = " . $row_operador['ID_OPERADOR'] . " "
                                                                            . "   AND ID_DEPARTAMENTO = " . $row_operador['ID_COD_DPTO'] . " "
                                                                            . "   AND ID_MUNICIPIO = " . $row_operador['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_operador_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * "
                                                                                . "  FROM municipios_operadores_2 "
                                                                                . " WHERE ID_OPERADOR = " . $row_operador['ID_OPERADOR'] . " "
                                                                                . "   AND ID_DEPARTAMENTO = " . $row_operador['ID_COD_DPTO'] . " "
                                                                                . "   AND ID_MUNICIPIO = " . $row_operador['ID_COD_MPIO']);
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_factura">Periodo:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_operador_editar'])) {
                                                                        $query_select_info_facturacion = mysqli_query($connection, "SELECT * FROM facturacion_operadores_2 WHERE ID_FACTURACION = " . $row_reca_operador['ID_FACTURACION']);
                                                                        $row_info_facturacion = mysqli_fetch_array($query_select_info_facturacion);
                                                                    ?>
                                                                        <input type="hidden" id="id_facturacion" name="id_facturacion" value="<?php echo $row_info_facturacion['ID_FACTURACION']; ?>" required="required" />
                                                                        <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php echo substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php echo substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                        $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                            . "  FROM periodos_facturacion_especiales_2 "
                                                                            . " WHERE ANO_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) . " "
                                                                            . "   AND MES_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2));
                                                                        $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoReca()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_operador_eliminar'])) {
                                                                            $query_select_info_facturacion = mysqli_query($connection, "SELECT * FROM facturacion_operadores_2 WHERE ID_FACTURACION = " . $row_reca_operador['ID_FACTURACION']);
                                                                            $row_info_facturacion = mysqli_fetch_array($query_select_info_facturacion);
                                                                        ?>
                                                                            <input type="hidden" id="id_facturacion" name="id_facturacion" value="<?php echo $row_info_facturacion['ID_FACTURACION']; ?>" required="required" />
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                            $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                . "  FROM periodos_facturacion_especiales_2 "
                                                                                . " WHERE ANO_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) . " "
                                                                                . "   AND MES_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2));
                                                                            $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" data-toggle="tooltip" readonly="readonly" title="PERIODO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_facturacion" name="id_facturacion" value="" required="required" />
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoReca()" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN VALORES / USUARIOS</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_factura">Val. Fact.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_factura" data-toogle="tooltip" title="VALOR FACTURA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Factura" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] ?>" readonly="readonly" placeholder="Valor Factura" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_factura" value="" maxlength="25" placeholder="Valor Factura" required="required" readonly="readonly" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="ajuste_fact">Ajus. Fact.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="ajuste_fact" data-toogle="tooltip" title="AJUSTE FACT.">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="<?php echo $row_info_facturacion['AJUSTE_FACT'] ?>" maxlength="25" readonly="readonly" placeholder="Ajuste Fact." onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="<?php echo $row_info_facturacion['AJUSTE_FACT'] ?>" readonly="readonly" placeholder="Ajuste Fact." onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="" maxlength="25" placeholder="Ajuste Fact." readonly="readonly" required="required" onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="total_factura">Tot. Fact.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="total_factura" data-toogle="tooltip" title="TOTAL FACTURA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_factura" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_info_facturacion['AJUSTE_FACT']; ?>" maxlength="25" readonly="readonly" placeholder="Total Factura" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="total_factura" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_info_facturacion['AJUSTE_FACT']; ?>" readonly="readonly" placeholder="Total Factura" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="total_factura" value="" maxlength="25" readonly="readonly" placeholder="Total Factura" required="required" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_recaudo_fact">Val. Reca.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_recaudo_fact" data-toogle="tooltip" title="VALOR RECAUDO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_recaudo_fact" value="<?php echo $row_info_facturacion['VALOR_RECAUDO'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Recaudo" onblur="convertValorRecaudoFact(); calcularTotalRecaudo();" onchange="return convertValorRecaudoFact(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo_fact" value="<?php echo $row_info_facturacion['VALOR_RECAUDO'] ?>" readonly="readonly" placeholder="Valor Recaudo" onblur="convertValorRecaudoFact(); calcularTotalRecaudo();" onchange="return convertValorRecaudoFact(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo_fact" value="" maxlength="25" placeholder="Valor Recaudo" required="required" readonly="readonly" onblur="convertValorRecaudoFact(); calcularTotalRecaudo();" onchange="return convertValorRecaudoFact(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="ajuste_reca">Ajus. Reca.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="ajuste_reca" data-toogle="tooltip" title="AJUSTE RECA.">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="<?php echo $row_info_facturacion['AJUSTE_RECA'] ?>" maxlength="25" placeholder="Ajuste Reca." onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="<?php echo $row_info_facturacion['AJUSTE_RECA'] ?>" placeholder="Ajuste Reca." onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="" maxlength="25" placeholder="Ajuste Reca." required="required" onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="total_recaudo">Tot. Reca.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="total_recaudo" data-toogle="tooltip" title="TOTAL RECAUDO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="<?php echo $row_info_facturacion['VALOR_RECAUDO'] - $row_info_facturacion['AJUSTE_RECA']; ?>" maxlength="25" readonly="readonly" placeholder="Total Recaudo" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="<?php echo $row_info_facturacion['VALOR_RECAUDO'] - $row_info_facturacion['AJUSTE_RECA']; ?>" readonly="readonly" placeholder="Total Recaudo" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="" maxlength="25" readonly="readonly" placeholder="Total Recaudo" required="required" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_energia">Val. Ener.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_energia" data-toogle="tooltip" title="VALOR ENERGÍA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_energia" value="<?php echo $row_info_facturacion['VALOR_ENERGIA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Energía" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_energia" value="<?php echo $row_info_facturacion['VALOR_ENERGIA'] ?>" readonly="readonly" placeholder="Valor Energía" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_energia" value="" maxlength="25" placeholder="Valor Energía" required="required" readonly="readonly" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="cuota_energia">Cuot. Ene.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="cuota_energia" data-toogle="tooltip" title="CUOTA ENERGÍA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="<?php echo $row_info_facturacion['CUOTA_ENERGIA'] ?>" maxlength="25" readonly="readonly" placeholder="Cuota Energía" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="<?php echo $row_info_facturacion['CUOTA_ENERGIA'] ?>" readonly="readonly" placeholder="Cuota Energía" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="" maxlength="25" placeholder="Cuota Energía" readonly="readonly" required="required" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="otros_ajustes">Otr. Ajus.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="otros_ajustes" data-toogle="tooltip" title="OTROS AJUSTES">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="<?php echo $row_info_facturacion['OTROS_AJUSTES'] ?>" maxlength="25" readonly="readonly" placeholder="Otros Ajustes" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="<?php echo $row_info_facturacion['OTROS_AJUSTES'] ?>" readonly="readonly" placeholder="Otros Ajustes" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="" maxlength="25" placeholder="Otros Ajustes" readonly="readonly" required="required" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_favor">Val. Fav.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_favor" data-toogle="tooltip" title="VALOR FAVOR">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_favor" value="<?php echo $row_info_facturacion['VALOR_FAVOR'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Favor" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_favor" value="<?php echo $row_info_facturacion['VALOR_FAVOR'] ?>" readonly="readonly" placeholder="Valor Favor" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_favor" value="" maxlength="25" placeholder="Valor Favor" readonly="readonly" required="required" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="consumo">Consumo:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="consumo" data-toogle="tooltip" title="CONSUMO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="consumo" value="<?php echo $row_info_facturacion['CONSUMO'] ?>" maxlength="10" readonly="readonly" placeholder="Consumo" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="consumo" value="<?php echo $row_info_facturacion['CONSUMO'] ?>" readonly="readonly" readonly="readonly" placeholder="Consumo" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="consumo" value="" maxlength="10" placeholder="Consumo" readonly="readonly" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="no_usuarios">No. Usuar.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="no_usuarios" data-toogle="tooltip" title="NO. USUARIOS">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="<?php echo $row_info_facturacion['NO_USUARIOS'] ?>" maxlength="10" readonly="readonly" placeholder="No. Usuarios" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="<?php echo $row_info_facturacion['NO_USUARIOS'] ?>" readonly="readonly" readonly="readonly" placeholder="No. Usuarios" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="" maxlength="10" placeholder="No. Usuarios" readonly="readonly" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN RECAUDO</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nota_fiducia">Nota Fid.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="nota_fiducia" data-toogle="tooltip" title="NOTA FIDUCIA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="<?php echo $row_reca_operador['NOTA_FIDUCIA'] ?>" maxlength="10" required="required" placeholder="Nota Fiducia" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="<?php echo $row_reca_operador['NOTA_FIDUCIA'] ?>" placeholder="Nota Fiducia" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="" maxlength="10" placeholder="Nota Fiducia" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_fiducia">Fecha Fid.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_fiducia" data-toogle="tooltip" title="FECHA FIDUCIA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="<?php echo $row_reca_operador['FECHA_FIDUCIA'] ?>" placeholder="Fecha Fiducia" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="<?php echo $row_reca_operador['FECHA_FIDUCIA'] ?>" placeholder="Fecha Fiducia" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="" placeholder="Fecha Fiducia" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_aplicacion_encargo">Fecha Apl.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_aplicacion_encargo" data-toogle="tooltip" title="FECHA APL. ENCARGO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="<?php echo $row_reca_operador['FECHA_APL_ENCARGO'] ?>" placeholder="Fecha Apl. Encargo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="<?php echo $row_reca_operador['FECHA_APL_ENCARGO'] ?>" placeholder="Fecha Apl. Encargo" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="" placeholder="Fecha Apl. Encargo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_recaudo">Val. Reca.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_recaudo" data-toogle="tooltip" title="VALOR RECAUDO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_reca_operador['VALOR_RECAUDO'] ?>" maxlength="25" placeholder="Valor Recaudo" required="required" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_reca_operador['VALOR_RECAUDO'] ?>" readonly="readonly" placeholder="Valor Recaudo" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="" maxlength="25" placeholder="Valor Recaudo" required="required" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for=""></label>
                                                                <div class="col-xs-3">

                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="saldo_fecha">Saldo:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="saldo_fecha" data-toogle="tooltip" title="SALDO A LA FECHA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input style="text-align: center;" type="text" class="form-control input-text input-sm" name="saldo_fecha" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_reca_operador['VALOR_RECAUDO']; ?>" maxlength="25" readonly="readonly" placeholder="Saldo Fecha" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input style="text-align: center;" type="text" class="form-control input-text input-sm" name="saldo_fecha" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_reca_operador['VALOR_RECAUDO']; ?>" readonly="readonly" placeholder="Saldo Fecha" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input style="text-align: center;" type="text" class="form-control input-text input-sm" name="saldo_fecha" value="" maxlength="25" placeholder="Saldo Fecha" readonly="readonly" required="required" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
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
                                                                    if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_operador" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Recaudo Oper.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Recaudo_Operadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_operador_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_operador" type="button" data-toggle="modal" data-target="#modalEliminarRecaOper"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Recaudo Oper.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Recaudo_Operadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_operador" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Recaudo Oper.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldRecaudoOperador();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                                </form>
                                            </div>
                                            <?php
                                            if (isset($_GET['id_reca_operador_editar'])) { ?>
                                                <div role="tabpanel" class="tab-pane fade" id="cargar_soporte_recaudo_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_soporte_recaudo" name="cargar_soporte_recaudo" action="Modelo/Subir_Archivos.php?archivo=recaudo_operador" method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_tabla_archivo" id="id_tabla_archivo" value="<?php echo $_GET['id_reca_operador_editar']; ?>" />
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
                                                                    <button class="btn btn-primary btn-sm font background cursor" type="submit" name="upload_files" id="upload_files"><i style="font-size: 14px;" class="fas fa-upload"></i>&nbsp;&nbsp;Subir Soporte</button>
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
                                                        . " WHERE ID_DEPARTAMENTO = " . $row_operador['ID_COD_DPTO'] . ""
                                                        . "   AND ID_MUNICIPIO = " . $row_operador['ID_COD_MPIO']);
                                                    $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                                                    $path = "Files/" . $row_nombre_municipio['NOMBRE'] . "/";
                                                    $query_select_files_recaudo_operadores = mysqli_query($connection, "SELECT * "
                                                        . "  FROM recaudo_operadores_archivos_2 "
                                                        . " WHERE ID_TABLA_RECAUDO = " . $_GET['id_reca_operador_editar']);
                                                    if (mysqli_num_rows($query_select_files_recaudo_operadores) != 0) {
                                                        while ($row_files = mysqli_fetch_assoc($query_select_files_recaudo_operadores)) {
                                                            $info_recaudo[] = $row_files['NOMBRE_ARCHIVO'];
                                                            $info_id_recaudo[] = $row_files['ID_TABLA'];
                                                        }
                                                        if ($handle = opendir($path)) {
                                                            foreach (array_combine($info_id_recaudo, $info_recaudo) as $id_files => $files) {
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
                                                                    . "   <td style='vertical-align: middle;'><a href='Modelo/Eliminar_Archivos.php?file=" . $files . "&id_tabla_archivo=" . $_GET['id_reca_operador_editar'] . "&file_id=" . $id_files . "&archivo=recaudo_operador'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16' /></button></a></td>"
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
                                                                <th style="width: 12%;">TAMAÑO</th>
                                                                <th style="width: 12%;">EXTENSIÓN</th>
                                                                <th style='width: 5%;'>ELIMINAR</th>
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
    function resetFieldRecaudoOperador() {
        $("#id_departamento").val("");
        $("#id_municipio").val("");
        $("#id_ano_fact").val("");
        $("#id_mes_fact").val("");
        $("#id_operador").val("");
        $("#operador").focus();
        $("input[name=saldo_fecha]").css("background-color", "#FFFFFF");
        $("input[name=saldo_fecha]").css("color", "#333333");
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

    function convertValorFactura() {
        var valorFactura = $("input[name=valor_factura]").val();
        var replaceValorFactura = valorFactura.replace(/,/g, '');
        var newValorFactura = replaceValorFactura.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_factura]").val(newValorFactura);
    }

    function convertAjusteFact() {
        var ajusteFact = $("input[name=ajuste_fact]").val();
        var replaceAjusteFact = ajusteFact.replace(/,/g, '');
        var newAjusteFact = replaceAjusteFact.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=ajuste_fact]").val(newAjusteFact);
    }

    function convertTotalFactura() {
        var totalFact = $("input[name=total_factura]").val();
        var replaceTotalFact = totalFact.replace(/,/g, '');
        var newTotalFact = replaceTotalFact.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=total_factura]").val(newTotalFact);
    }

    function calcularTotalFactura() {
        var valor_factura = $("input[name=valor_factura]").val();
        var ajuste_fact = $("input[name=ajuste_fact]").val();
        $("input[name=total_factura]").val(Math.round(valor_factura.replace(/,/g, "") - ajuste_fact.replace(/,/g, "")));
        convertTotalFactura();
    }

    function convertValorRecaudoFact() {
        var valorRecaudoFact = $("input[name=valor_recaudo_fact]").val();
        var replaceValorRecaudoFact = valorRecaudoFact.replace(/,/g, '');
        var newValorRecaudoFact = replaceValorRecaudoFact.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_recaudo_fact]").val(newValorRecaudoFact);
    }

    function convertAjusteReca() {
        var ajusteReca = $("input[name=ajuste_reca]").val();
        var replaceAjusteReca = ajusteReca.replace(/,/g, '');
        var newAjusteReca = replaceAjusteReca.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=ajuste_reca]").val(newAjusteReca);
    }

    function convertTotalRecaudo() {
        var totalReca = $("input[name=total_recaudo]").val();
        var replaceTotalReca = totalReca.replace(/,/g, '');
        var newTotalReca = replaceTotalReca.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=total_recaudo]").val(newTotalReca);
    }

    function calcularTotalRecaudo() {
        var valor_recaudo = $("input[name=valor_recaudo_fact]").val();
        var ajuste_reca = $("input[name=ajuste_reca]").val();
        $("input[name=total_recaudo]").val(Math.round(valor_recaudo.replace(/,/g, "") - ajuste_reca.replace(/,/g, "")));
        convertTotalRecaudo();
    }

    function convertValorEnergia() {
        var valorEnergia = $("input[name=valor_energia]").val();
        var replaceValorEnergia = valorEnergia.replace(/,/g, '');
        var newValorEnergia = replaceValorEnergia.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_energia]").val(newValorEnergia);
    }

    function convertCuotaEnergia() {
        var cuotaEnergia = $("input[name=cuota_energia]").val();
        var replaceCuotaEnergia = cuotaEnergia.replace(/,/g, '');
        var newCuotaEnergia = replaceCuotaEnergia.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=cuota_energia]").val(newCuotaEnergia);
    }

    function convertOtrosAjustes() {
        var otrosAjustes = $("input[name=otros_ajustes]").val();
        var replaceOtrosAjustes = otrosAjustes.replace(/,/g, '');
        var newOtrosAjustes = replaceOtrosAjustes.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=otros_ajustes]").val(newOtrosAjustes);
    }

    function convertValorFavor() {
        var valorFavor = $("input[name=valor_favor]").val();
        var replaceValorFavor = valorFavor.replace(/,/g, '');
        var newValorFavor = replaceValorFavor.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_favor]").val(newValorFavor);
    }

    function convertValorRecaudo() {
        var recaudo = $("input[name=valor_recaudo]").val();
        var replaceRecaudo = recaudo.replace(/,/g, '');
        var newRecaudo = replaceRecaudo.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_recaudo]").val(newRecaudo);
    }

    function convertSaldoFecha() {
        var saldoFecha = $("input[name=saldo_fecha]").val();
        var replaceSaldoFecha = saldoFecha.replace(/,/g, '');
        var newSaldoFecha = replaceSaldoFecha.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=saldo_fecha]").val(newSaldoFecha);
    }

    function calcularValorSaldoFecha() {
        var valor_favor = $("input[name=valor_favor]").val();
        var valor_recaudo = $("input[name=valor_recaudo]").val();
        if (Math.round(valor_favor.replace(/,/g, "") - valor_recaudo.replace(/,/g, "")) > 0) {
            $("input[name=saldo_fecha]").css("background-color", "#CC3300");
        } else {
            $("input[name=saldo_fecha]").css("background-color", "#00A328");
        }
        $("input[name=saldo_fecha]").css("color", "#FFFFFF");
        $("input[name=saldo_fecha]").val(Math.round(valor_favor.replace(/,/g, "") - valor_recaudo.replace(/,/g, "")));
        convertSaldoFecha();
    }
    //POPUPS
    function infoOperadorReca(id_operador, operador, nit_operador) {
        $("#id_operador").val(id_operador);
        $("#operador").val(operador);
        $("#nit_operador").val(nit_operador);
        $("#id_departamento").val("");
        $("#departamento").val("");
        $("#id_municipio").val("");
        $("#municipio").val("");
        $("#id_facturacion").val("");
        $("#periodo_factura").val("");
        $("input[name=valor_factura]").val("");
        $("input[name=ajuste_fact]").val("");
        $("input[name=total_factura]").val("");
        $("input[name=valor_recaudo_fact]").val("");
        $("input[name=ajuste_reca]").val("");
        $("input[name=total_recaudo]").val("");
        $("input[name=valor_energia]").val("");
        $("input[name=cuota_energia]").val("");
        $("input[name=otros_ajustes]").val("");
        $("input[name=valor_favor]").val("");
        $("input[name=no_usuarios]").val("");
        $("input[name=consumo]").val("");
        $("input[name=valor_recaudo]").val("");
        $("input[name=saldo_fecha]").val("");
        $("#departamento").focus();
    }

    function operadorReca() {
        window.open("Combos/Operador_Recaudo.php", "Popup", "width=500, height=500");
    }

    function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
        if (id_consulta == 1) {
            $("#id_departamento").val(id_departamento);
            $("#departamento").val(departamento);
            $("#id_municipio").val("");
            $("#municipio").val("");
            $("#id_facturacion").val("");
            $("#periodo_factura").val("");
            $("input[name=valor_factura]").val("");
            $("input[name=ajuste_fact]").val("");
            $("input[name=total_factura]").val("");
            $("input[name=valor_recaudo_fact]").val("");
            $("input[name=ajuste_reca]").val("");
            $("input[name=total_recaudo]").val("");
            $("input[name=valor_energia]").val("");
            $("input[name=cuota_energia]").val("");
            $("input[name=otros_ajustes]").val("");
            $("input[name=valor_favor]").val("");
            $("input[name=consumo]").val("");
            $("input[name=no_usuarios]").val("");
            $("input[name=valor_recaudo]").val("");
            $("input[name=saldo_fecha]").val("");
            $("#departamento").focus();
            $("#municipio").focus();
        }
    }

    function tipoDepartamento(id_consulta) {
        var id_operador = $("#id_operador").val();
        window.open("Combos/Tipo_Departamento_Recaudo_Operador.php?id_consulta=" + id_consulta + "&id_operador=" + id_operador, "Popup", "width=400, height=500");
    }

    function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
        if (id_consulta == 1) {
            $("#id_municipio").val(id_municipio);
            $("#municipio").val(municipio);
            $("#id_facturacion").val("");
            $("#periodo_factura").val("");
            $("input[name=valor_factura]").val("");
            $("input[name=ajuste_fact]").val("");
            $("input[name=total_factura]").val("");
            $("input[name=valor_recaudo_fact]").val("");
            $("input[name=ajuste_reca]").val("");
            $("input[name=total_recaudo]").val("");
            $("input[name=valor_energia]").val("");
            $("input[name=cuota_energia]").val("");
            $("input[name=otros_ajustes]").val("");
            $("input[name=valor_favor]").val("");
            $("input[name=consumo]").val("");
            $("input[name=no_usuarios]").val("");
            $("input[name=valor_recaudo]").val("");
            $("input[name=saldo_fecha]").val("");
            $("#departamento").focus();
            $("#periodo_factura").focus();
        }
    }

    function tipoMunicipio(id_consulta) {
        var id_operador;
        var id_departamento;
        if (id_consulta == 1) {
            id_operador = $("#id_operador").val();
            id_departamento = $("#id_departamento").val();
        }
        window.open("Combos/Tipo_Municipio_Recaudo_Operador.php?id_departamento=" + id_departamento + "&id_consulta=" + id_consulta + "&id_operador=" + id_operador, "Popup", "width=400, height=500");
    }

    function infoPeriodoReca(id_facturacion, periodo_factura, valor_factura, ajuste_fact, valor_recaudo_fact, ajuste_reca, valor_energia, cuota_energia, otros_ajustes, valor_favor, consumo, no_usuarios) {
        $("#id_facturacion").val(id_facturacion);
        $("#periodo_factura").val(periodo_factura);
        $("input[name=valor_factura]").val(valor_factura);
        $("input[name=ajuste_fact]").val(ajuste_fact);
        $("input[name=valor_recaudo_fact]").val(valor_recaudo_fact);
        $("input[name=ajuste_reca]").val(ajuste_reca);
        $("input[name=valor_energia]").val(valor_energia);
        $("input[name=cuota_energia]").val(cuota_energia);
        $("input[name=otros_ajustes]").val(otros_ajustes);
        $("input[name=valor_favor]").val(valor_favor);
        $("input[name=consumo]").val(consumo);
        $("input[name=no_usuarios]").val(no_usuarios);
        convertValorFactura();
        convertAjusteFact();
        convertTotalFactura();
        convertValorRecaudoFact();
        convertAjusteReca();
        convertTotalRecaudo();
        convertValorEnergia();
        convertCuotaEnergia();
        convertOtrosAjustes();
        convertValorFavor();
        calcularTotalFactura();
        calcularTotalRecaudo();
        $("input[name=nota_fiducia]").focus();
    }

    function periodoReca() {
        var id_operador = $("#id_operador").val();
        var id_departamento = $("#id_departamento").val();
        var id_municipio = $("#id_municipio").val();
        window.open("Combos/Periodo_Reca_Oper.php?id_operador=" + id_operador + "&id_departamento=" + id_departamento + "&id_municipio=" + id_municipio, "Popup", "width=500, height=500");
    }
    //END POPUPS
</script>
<script>
    $(document).ready(function() {
        $("#buscar_recaudo").focus();
        var id_reca_operador_editar = $("#id_reca_operador_editar_hidden").val();
        var id_reca_operador_eliminar = $("#id_reca_operador_eliminar_hidden").val();
        if (id_reca_operador_editar != undefined) {
            convertValorFactura();
            convertAjusteFact();
            convertTotalFactura();
            convertValorRecaudoFact();
            convertAjusteReca();
            convertTotalRecaudo();
            convertValorEnergia();
            convertCuotaEnergia();
            convertOtrosAjustes();
            convertValorFavor();
            convertValorRecaudo();
            calcularTotalFactura();
            calcularTotalRecaudo();
            calcularValorSaldoFecha();
            $(".nav-pills a[href='#crear_reca_operadores_tab']").tab("show");
            $(".nav-pills a[href='#crear_reca_operadores_tab']").text("Actualizar Reca. Operador");
            $("#estado_recaudo").val($("#estado_recaudo_hidden").val());
        } else {
            if (id_reca_operador_eliminar != undefined) {
                convertValorFactura();
                convertAjusteFact();
                convertTotalFactura();
                convertValorRecaudoFact();
                convertAjusteReca();
                convertTotalRecaudo();
                convertValorEnergia();
                convertCuotaEnergia();
                convertOtrosAjustes();
                convertValorFavor();
                convertValorRecaudo();
                calcularTotalFactura();
                calcularTotalRecaudo();
                calcularValorSaldoFecha();
                $(".nav-pills a[href='#crear_reca_operadores_tab']").tab("show");
                $(".nav-pills a[href='#crear_reca_operadores_tab']").text("Eliminar Reca. Operador");
                $("#estado_recaudo").val($("#estado_recaudo_hidden").val());
            }
        }
        $("#buscar_recaudo").keypress(function(e) {
            if (e.which == 13) {
                var busqueda_recaudo;
                if ($(this).val() == "") {
                    busqueda_recaudo = "";
                } else {
                    busqueda_recaudo = $(this).val().toUpperCase();
                }
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Reca_Oper.php",
                    dataType: "json",
                    data: "sw=1&busqueda_recaudo=" + busqueda_recaudo,
                    success: function(data) {
                        $("#pagination-reca_oper").twbsPagination('destroy');
                        $("#pagination-reca_oper").twbsPagination({
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
                                    url: "Modelo/Cargar_Reca_Oper.php",
                                    dataType: "json",
                                    data: "sw=1&busqueda_recaudo=" + data[1] + "&page=" + page,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_reca_oper").html(data[0]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
        $("#fecha_recaudo").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_pago_bitacora").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_fiducia").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_aplicacion_encargo").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#tab_info_reca_operadores").on("shown.bs.tab", function() {
            $("#buscar_recaudo").focus();
        });
        $("#tab_crear_reca_operadores").on("shown.bs.tab", function() {
            $("#operador").focus();
        });
        $("#tab_info_reca_operadores").on("click", function() {
            $("#buscar_recaudo").focus();
        });
        $("#tab_crear_reca_operadores").on("click", function() {
            $("#operador").focus();
        });
        if (id_reca_operador_editar == undefined && id_reca_operador_eliminar == undefined) {
            $("#btn_crear_recaudo_operador").click(function() {
                var fecha_recaudo = $("input[name=fecha_recaudo]").val();
                var fecha_pago_bitacora = $("input[name=fecha_pago_bitacora]").val();
                var estado_recaudo = $("#estado_recaudo").val();
                var operador = $("#id_operador").val();
                var departamento = $("#id_departamento").val();
                var municipio = $("#id_municipio").val();
                var id_facturacion = $("#id_facturacion").val();
                var nota_fiducia = $("input[name=nota_fiducia]").val();
                var fecha_fiducia = $("input[name=fecha_fiducia]").val();
                var fecha_aplicacion_encargo = $("input[name=fecha_aplicacion_encargo]").val();
                var valor_recaudo = $("input[name=valor_recaudo]").val();
                if (valor_recaudo != "") {
                    valor_recaudo = valor_recaudo.replace(/,/g, "");
                }
                if (fecha_recaudo.length == 0) {
                    $("input[name=fecha_recaudo]").focus();
                    return false;
                }
                if (fecha_pago_bitacora.length == 0) {
                    $("input[name=fecha_pago_bitacora]").focus();
                    return false;
                }
                if (estado_recaudo.length == 0) {
                    $("#estado_recaudo").focus();
                    return false;
                }
                if (operador.length == 0) {
                    $("#operador").focus();
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
                if (id_facturacion.length == 0) {
                    $("#periodo_factura").focus();
                    return false;
                }
                if (nota_fiducia.length == 0) {
                    $("input[name=nota_fiducia]").focus();
                    return false;
                }
                if (fecha_fiducia.length == 0) {
                    $("input[name=fecha_fiducia]").focus();
                    return false;
                }
                if (fecha_aplicacion_encargo.length == 0) {
                    $("input[name=fecha_aplicacion_encargo]").focus();
                    return false;
                }
                if (valor_recaudo.length == 0) {
                    $("input[name=valor_recaudo]").focus();
                    return false;
                }
                $("#btn_crear_recaudo_operador").attr("disabled", true);
                $("#btn_crear_recaudo_operador").css("pointer-events", "none");
                $("#btn_crear_recaudo_operador").html("Creando Reca. Operador...");
                $.ajax({
                    type: "POST",
                    data: "fecha_recaudo=" + fecha_recaudo +
                          "&fecha_pago_bitacora=" + fecha_pago_bitacora +
                          "&estado_recaudo=" + estado_recaudo +
                          "&id_facturacion=" + id_facturacion +
                          "&nota_fiducia=" + nota_fiducia +
                          "&fecha_fiducia=" + fecha_fiducia +
                          "&fecha_aplicacion_encargo=" + fecha_aplicacion_encargo +
                          "&valor_recaudo=" + valor_recaudo,
                    url: "Modelo/Crear_Reca_Oper.php",
                    success: function(data) {
                        //alert(data);
                        //$("#observaciones").val(data);
                        document.location.href = 'Recaudo_Operadores.php?id_reca_operador_editar=' + data;
                    }
                });
            });
        }
        $.ajax({
            type: "POST",
            url: "Modelo/Cargar_Paginacion_Reca_Oper.php",
            dataType: "json",
            data: "sw=0",
            success: function(data) {
                $("#pagination-reca_oper").twbsPagination('destroy');
                $("#pagination-reca_oper").twbsPagination({
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
                            url: "Modelo/Cargar_Reca_Oper.php",
                            dataType: "json",
                            data: "sw=0&page=" + page,
                            success: function(data) {
                                $("#loading-spinner").css('display', 'none');
                                $("#resultado_reca_oper").html(data[0]);
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
            $("#cargar_soporte_recaudo").ajaxSubmit({
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
        $('input[type=text][name=operador]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=nit_operador]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=departamento]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=municipio]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=periodo_factura]').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_factura').tooltip({
            container: "body",
            placement: "top"
        });
        $('#ajuste_fact').tooltip({
            container: "body",
            placement: "top"
        });
        $('#total_factura').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_recaudo_fact').tooltip({
            container: "body",
            placement: "top"
        });
        $('#ajuste_reca').tooltip({
            container: "body",
            placement: "top"
        });
        $('#total_recaudo').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_energia').tooltip({
            container: "body",
            placement: "top"
        });
        $('#cuota_energia').tooltip({
            container: "body",
            placement: "top"
        });
        $('#otros_ajustes').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_favor').tooltip({
            container: "body",
            placement: "top"
        });
        $('#consumo').tooltip({
            container: "body",
            placement: "top"
        });
        $('#no_usuarios').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_recaudo').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_pago_bitacora').tooltip({
            container: "body",
            placement: "top"
        });
        $('#nota_fiducia').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_fiducia').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_aplicacion_encargo').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_recaudo').tooltip({
            container: "body",
            placement: "right"
        });
        $('#saldo_fecha').tooltip({
            container: "body",
            placement: "top"
        });
        $('select[name=estado_recaudo]').tooltip({
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