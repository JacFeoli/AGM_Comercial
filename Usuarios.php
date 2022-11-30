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
        <title>AGM - Usuarios</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
    </head>
    <!--Eliminar Usuario Modal-->
    <div class="modal fade" id="modalEliminarUsuario" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Usuario</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Usuario?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_usuario" name="eliminar_usuario"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Usuario Modal-->
    <!--Eliminar Usuario Error-->
    <div class="modal fade" id="modalEliminarUsuarioError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Usuario</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Usuario, ya que existen registros asignados a éste. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Usuario Error-->
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Seguridad.php"); ?>
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-user-plus"></i>
                                                                        <span>Usuarios</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Usuarios.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-user-plus"></i>
                                                                                    <span>Usuarios</span>
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
                                            <h1>Usuarios</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_usuario_tab" id="tab_info_usuario" aria-controls="informacion_usuario_tab" role="tab" data-toggle="tab">Información Usuarios</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_usuario_tab" id="tab_crear_usuario" aria-controls="crear_usuario_tab" role="tab" data-toggle="tab">Crear Usuario</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_usuario_tab">
                                                    <ul class="nav nav-pills" role="tablist">
                                                        <?php
                                                            $sw = 0;
                                                            $array_id_area_interna = array();
                                                            $array_nombre_compania = array();
                                                            $query_tipo_companias = mysqli_query($connection, "SELECT DISTINCT(TP.NOMBRE) AS NOMBRE_COMPANIA, TP.ID_TIPO_COMPANIA
                                                                                                                 FROM usuarios_tipo_companias_2 UTP, tipo_companias_2 TP
                                                                                                                WHERE UTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                ORDER BY UTP.ID_TIPO_COMPANIA");
                                                            while ($tipo_companias = mysqli_fetch_assoc($query_tipo_companias)) {
                                                                $nombre_compania = strtolower(str_replace(' ', '_', $tipo_companias['NOMBRE_COMPANIA']));
                                                                if ($sw == 0) {
                                                                    $sw = 1;
                                                                    $array_id_area_interna[] = $tipo_companias['ID_TIPO_COMPANIA'];
                                                                    $array_nombre_compania[] = $nombre_compania;
                                                                ?>
                                                                    <li role="presentation" class="active">
                                                                        <a href="#informacion_<?php echo $nombre_compania; ?>_tab" id="tab_info_<?php echo $nombre_compania; ?>" aria-controls="informacion_<?php echo $nombre_compania; ?>_tab" role="tab" data-toggle="tab"><?php echo ucwords(strtolower($tipo_companias['NOMBRE_COMPANIA'])); ?></a>
                                                                    </li>
                                                                <?php
                                                                } else {
                                                                    $array_id_area_interna[] = $tipo_companias['ID_TIPO_COMPANIA'];
                                                                    $array_nombre_compania[] = $nombre_compania;
                                                                ?>
                                                                    <li role="presentation">
                                                                        <a href="#informacion_<?php echo $nombre_compania; ?>_tab" id="tab_info_<?php echo $nombre_compania; ?>" aria-controls="informacion_<?php echo $nombre_compania; ?>_tab" role="tab" data-toggle="tab"><?php echo ucwords(strtolower($tipo_companias['NOMBRE_COMPANIA'])); ?></a>
                                                                    </li>
                                                                <?php
                                                                }
                                                            }
                                                        ?>
                                                    </ul>
                                                    <h2></h2>
                                                    <div class="tab-content">
                                                        <?php
                                                            $sw = 0;
                                                            $array_pagination = array();
                                                            $query_tipo_companias = mysqli_query($connection, "SELECT DISTINCT(TP.NOMBRE) AS NOMBRE_COMPANIA, TP.ID_TIPO_COMPANIA
                                                                                                                 FROM usuarios_tipo_companias_2 UTP, tipo_companias_2 TP
                                                                                                                WHERE UTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                ORDER BY UTP.ID_TIPO_COMPANIA");
                                                            while ($tipo_companias = mysqli_fetch_assoc($query_tipo_companias)) {
                                                                $nombre_compania = strtolower(str_replace(' ', '_', $tipo_companias['NOMBRE_COMPANIA']));
                                                                if ($sw == 0) {
                                                                    $id_tipo_compania = $tipo_companias['ID_TIPO_COMPANIA'];
                                                                    $sw = 1;
                                                                    $array_pagination[] = "pagination-" . $nombre_compania;
                                                                    ?>
                                                                    <div role="tabpanel" class="tab-pane fade in active" id="informacion_<?php echo $nombre_compania; ?>_tab">
                                                                        <?php
                                                                            $query_usuarios = mysqli_query($connection, "SELECT *
                                                                                                                           FROM usuarios_2 US, usuarios_tipo_companias_2 UTP
                                                                                                                          WHERE US.ID_USUARIO = UTP.ID_USUARIO
                                                                                                                            AND UTP.ID_TIPO_COMPANIA = " . $id_tipo_compania . "
                                                                                                                          ORDER BY US.ID_USUARIO");
                                                                            if (mysqli_num_rows($query_usuarios) != 0) {
                                                                                echo "<div class='table-responsive'>";
                                                                                    echo "<table class='table table-condensed table-hover'>";
                                                                                        echo "<thead>";
                                                                                            echo "<tr>";
                                                                                                echo "<th width=25%>NOMBRE</th>";
                                                                                                echo "<th width=12%>IDENTIFICACIÓN</th>";
                                                                                                echo "<th width=25%>CORREO ELECTRÓNICO</th>";
                                                                                                echo "<th width=12%>USUARIO</th>";
                                                                                                echo "<th width=10%>BLOQUEADO</th>";
                                                                                                echo "<th width=5%>EDITAR</th>";
                                                                                                echo "<th width=5%>ELIMINAR</th>";
                                                                                            echo "</tr>";
                                                                                        echo "</thead>";
                                                                                        echo "<tbody id='resultado_usuario_" . $nombre_compania . "'>";

                                                                                        echo "</tbody>";
                                                                                    echo "</table>";
                                                                                echo "</div>";
                                                                            } else {
                                                                                echo "<br />";
                                                                                echo "<p class='message'>No se encontraron Usuarios Creados.</p>";
                                                                            }
                                                                        ?>
                                                                        <div id="div-pagination">
                                                                                <ul id="pagination-<?php echo $nombre_compania; ?>"></ul>
                                                                                <span id='loading-spinner-<?php echo $id_tipo_compania; ?>' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                } else {
                                                                    $id_tipo_compania = $tipo_companias['ID_TIPO_COMPANIA'];
                                                                    $array_pagination[] = "pagination-" . $nombre_compania;
                                                                ?>
                                                                    <div role="tabpanel" class="tab-pane fade" id="informacion_<?php echo $nombre_compania; ?>_tab">
                                                                        <?php
                                                                            $query_usuarios = mysqli_query($connection, "SELECT *
                                                                                                                           FROM usuarios_2 US, usuarios_tipo_companias_2 UTP
                                                                                                                          WHERE US.ID_USUARIO = UTP.ID_USUARIO
                                                                                                                            AND UTP.ID_TIPO_COMPANIA = " . $id_tipo_compania . "
                                                                                                                          ORDER BY US.ID_USUARIO");
                                                                            if (mysqli_num_rows($query_usuarios) != 0) {
                                                                                echo "<div class='table-responsive'>";
                                                                                    echo "<table class='table table-condensed table-hover'>";
                                                                                        echo "<thead>";
                                                                                            echo "<tr>";
                                                                                                echo "<th width=25%>NOMBRE</th>";
                                                                                                echo "<th width=12%>IDENTIFICACIÓN</th>";
                                                                                                echo "<th width=25%>CORREO ELECTRÓNICO</th>";
                                                                                                echo "<th width=12%>USUARIO</th>";
                                                                                                echo "<th width=10%>BLOQUEADO</th>";
                                                                                                echo "<th width=5%>EDITAR</th>";
                                                                                                echo "<th width=5%>ELIMINAR</th>";
                                                                                            echo "</tr>";
                                                                                        echo "</thead>";
                                                                                        echo "<tbody id='resultado_usuario_" . $nombre_compania . "'>";

                                                                                        echo "</tbody>";
                                                                                    echo "</table>";
                                                                                echo "</div>";
                                                                            } else {
                                                                                echo "<br />";
                                                                                echo "<p class='message'>No se encontraron Usuarios Creados.</p>";
                                                                            }
                                                                        ?>
                                                                        <div id="div-pagination">
                                                                                <ul id="pagination-<?php echo $nombre_compania; ?>"></ul>
                                                                                <span id='loading-spinner-<?php echo $id_tipo_compania; ?>' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_usuario_tab">
                                                    <?php
                                                        if (isset($_GET['id_usuario_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_usuario" name="crear_usuario" action="<?php echo "Modelo/Crear_Usuario.php?editar=" . $_GET['id_usuario_editar'] . "&id_area_interna=" . $_GET['id_area_interna']; ?>" method="post">
                                                        <?php
                                                            $query_select_usuario = mysqli_query($connection, "SELECT * FROM usuarios_2 USU, usuarios_tipo_companias_2 USUTC WHERE USU.ID_USUARIO = USUTC.ID_USUARIO AND USU.ID_USUARIO = " . $_GET['id_usuario_editar'] . " AND USUTC.ID_TIPO_COMPANIA = " . $_GET['id_area_interna']);
                                                            $row_usuario = mysqli_fetch_array($query_select_usuario);
                                                        ?>
                                                            <input type="hidden" id="id_usuario_editar_hidden" name="id_usuario_editar_hidden" value="<?php echo $row_usuario['ID_USUARIO']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_usuario" name="crear_usuario" action="<?php echo "Modelo/Crear_Usuario.php?eliminar=" . $_GET['id_usuario_eliminar'] . "&id_area_interna=" . $_GET['id_area_interna']; ?>" method="post">
                                                                <?php
                                                                    $query_select_usuario = mysqli_query($connection, "SELECT * FROM usuarios_2 USU, usuarios_tipo_companias_2 USUTC WHERE USU.ID_USUARIO = USUTC.ID_USUARIO AND USU.ID_USUARIO = " . $_GET['id_usuario_eliminar'] . " AND USUTC.ID_TIPO_COMPANIA = " . $_GET['id_area_interna']);
                                                                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                                                                ?>
                                                                    <input type="hidden" id="id_usuario_eliminar_hidden" name="id_usuario_eliminar_hidden" value="<?php echo $row_usuario['ID_USUARIO']; ?>" />
                                                                <?php
                                                            } else { ?>
                                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_usuario" name="crear_usuario" action="<?php echo "Modelo/Crear_Usuario.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tipo_identificacion_usuario">T. Ident.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_usuario_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="tipo_identificacion_usuario" name="tipo_identificacion_usuario" data-toogle="tooltip" title="TIPO IDENTIFICACION" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="tipo_identificacion_usuario" name="tipo_identificacion_usuario" disabled="disabled" data-toogle="tooltip" title="TIPO IDENTIFICACION" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="tipo_identificacion_usuario" name="tipo_identificacion_usuario" data-toogle="tooltip" title="TIPO IDENTIFICACION" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <option value="" selected="selected">-</option>
                                                                        <?php
                                                                            $query_select_tipo_identificacion_usuario = "SELECT * FROM tipo_identificaciones_2 ORDER BY NOMBRE";
                                                                            $sql_select_tipo_identificacion_usuario = mysqli_query($connection, $query_select_tipo_identificacion_usuario);
                                                                            while ($row_tipo_identificacion_usuario = mysqli_fetch_assoc($sql_select_tipo_identificacion_usuario)) {
                                                                                echo "<option value='" . $row_tipo_identificacion_usuario['ID_TIPO_IDENTIFICACION'] . "'>" . $row_tipo_identificacion_usuario['NOMBRE'] . "</option>";
                                                                            }
                                                                            if (isset($_GET['id_usuario_editar']) || isset($_GET['id_usuario_eliminar'])) { ?>
                                                                                <input type="hidden" id="tipo_identificacion_usuario_hidden" name="tipo_identificacion_usuario_hidden" value="<?php echo $row_usuario['ID_TIPO_IDENTIFICACION'] ?>" />
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="identificacion_usuario">Ident.:</label>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="identificacion_usuario" name="identificacion_usuario" value="<?php echo $row_usuario['IDENTIFICACION'] ?>" maxlength="20" placeholder="Identificación" data-toogle="tooltip" title="IDENTIFICACIÓN" onkeypress="return isNumeric(event)" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="identificacion_usuario" name="identificacion_usuario" readonly="readonly" placeholder="Identificación" value="<?php echo $row_usuario['IDENTIFICACION'] ?>" data-toogle="tooltip" title="IDENTIFICACIÓN" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="identificacion_usuario" name="identificacion_usuario" maxlength="20" placeholder="Identificación" data-toogle="tooltip" title="IDENTIFICACIÓN" onkeypress="return isNumeric(event)" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nombre_usuario">Nombre:</label>
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_usuario" name="nombre_usuario" value="<?php echo $row_usuario['NOMBRE']; ?>" maxlength="80" placeholder="Nombre" data-toogle="tooltip" title="NOMBRE" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_usuario" name="nombre_usuario" readonly="readonly" placeholder="Nombre" value="<?php echo $row_usuario['NOMBRE']; ?>" data-toogle="tooltip" title="NOMBRE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_usuario" name="nombre_usuario" maxlength="80" placeholder="Nombre" data-toogle="tooltip" title="NOMBRE" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="area_interna_usuario">Área Int.:</label>
                                                            <div class="col-xs-8">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_usuario_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="area_interna_usuario" name="area_interna_usuario" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="area_interna_usuario" name="area_interna_usuario" disabled="disabled" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="area_interna_usuario" name="area_interna_usuario" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <option value="" selected="selected">-</option>
                                                                        <?php
                                                                            $query_select_area_interna_usuario = "SELECT * FROM tipo_companias_2 ORDER BY NOMBRE";
                                                                            $sql_select_area_interna_usuario = mysqli_query($connection, $query_select_area_interna_usuario);
                                                                            while ($row_area_interna_usuario = mysqli_fetch_assoc($sql_select_area_interna_usuario)) {
                                                                                echo "<option value='" . $row_area_interna_usuario['ID_TIPO_COMPANIA'] . "'>" . $row_area_interna_usuario['NOMBRE'] . "</option>";
                                                                            }
                                                                            if (isset($_GET['id_usuario_editar']) || isset($_GET['id_usuario_eliminar'])) { ?>
                                                                                <input type="hidden" id="area_interna_usuario_hidden" name="area_interna_usuario_hidden" value="<?php echo $row_usuario['ID_TIPO_COMPANIA'] ?>" />
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-10">
                                                                <div class="btn-group" data-toggle="buttons">
                                                                    <?php
                                                                        if (isset($_GET['id_usuario_editar']) || isset($_GET['id_usuario_eliminar'])) {
                                                                            if ($row_usuario['BLOQUEADO'] == 0) { ?>
                                                                                <label class="btn btn-primary cursor font background" name="label_activo" data-toogle="tooltip" title="BLOQUEADO - SI">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="usuario_bloqueado" name="usuario_bloqueado" value="1" required />Bloqueado - Si
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background active" name="label_activo" data-toogle="tooltip" title="BLOQUEADO - NO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="usuario_bloqueado" name="usuario_bloqueado" value="0" checked="checked" required />Bloqueado - No
                                                                                </label>
                                                                            <?php
                                                                            } else { ?>
                                                                                <label class="btn btn-primary cursor font background active" name="label_activo" data-toogle="tooltip" title="BLOQUEADO - SI">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="usuario_bloqueado" name="usuario_bloqueado" value="1" checked="checked" required />Bloqueado - Si
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background" name="label_activo" data-toogle="tooltip" title="BLOQUEADO - NO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="usuario_bloqueado" name="usuario_bloqueado" value="0" required />Bloqueado - No
                                                                                </label>
                                                                            <?php
                                                                            }
                                                                        } else { ?>
                                                                            <label class="btn btn-primary cursor font background" name="label_activo" data-toogle="tooltip" title="BLOQUEADO - SI">
                                                                                <input type="radio" class="form-control input-text input-sm" id="usuario_bloqueado" name="usuario_bloqueado" value="1" required />Bloqueado - Si
                                                                            </label>
                                                                            <label class="btn btn-primary cursor font background" name="label_activo" data-toogle="tooltip" title="BLOQUEADO - NO">
                                                                                <input type="radio" class="form-control input-text input-sm" id="usuario_bloqueado" name="usuario_bloqueado" value="0" required />Bloqueado - No
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
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="user_usuario">Usuario:</label>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="user_usuario" name="user_usuario" value="<?php echo $row_usuario['USUARIO']; ?>" maxlength="15" required placeholder="Usuario" data-toogle="tooltip" title="USUARIO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="user_usuario" name="user_usuario" readonly="readonly" required placeholder="Usuario" value="<?php echo $row_usuario['USUARIO']; ?>" data-toogle="tooltip" title="USUARIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="user_usuario" name="user_usuario" maxlength="15" required placeholder="Usuario" data-toogle="tooltip" title="USUARIO" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="password1_usuario">Password:</label>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) { ?>
                                                                        <input type="password" class="form-control input-text input-sm" id="password1_usuario" name="password1_usuario" value="<?php echo $row_usuario['PASSWORD']; ?>" maxlength="15" required placeholder="Password" data-toogle="tooltip" title="PASSWORD" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                            <input type="password" class="form-control input-text input-sm" id="password1_usuario" name="password1_usuario" readonly="readonly" required placeholder="Password" value="<?php echo $row_usuario['PASSWORD']; ?>" data-toogle="tooltip" title="PASSWORD" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="password" class="form-control input-text input-sm" id="password1_usuario" name="password1_usuario" maxlength="15" required placeholder="Password" data-toogle="tooltip" title="PASSWORD" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="password2_usuario">Conf. Pass.:</label>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) { ?>
                                                                        <input type="password" class="form-control input-text input-sm" id="password2_usuario" name="password2_usuario" value="<?php echo $row_usuario['PASSWORD']; ?>" maxlength="15" required placeholder="Confirmar Password" data-toogle="tooltip" title="CONFIRMAR PASSWORD" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                            <input type="password" class="form-control input-text input-sm" id="password2_usuario" name="password2_usuario" readonly="readonly" required placeholder="Confirmar Password" value="<?php echo $row_usuario['PASSWORD']; ?>" data-toogle="tooltip" title="CONFIRMAR PASSWORD" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="password" class="form-control input-text input-sm" id="password2_usuario" name="password2_usuario" maxlength="15" required placeholder="Confirmar Password" data-toogle="tooltip" title="CONFIRMAR PASSWORD" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="correo_electronico_usuario">Email:</label>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="correo_electronico_usuario" name="correo_electronico_usuario" value="<?php echo $row_usuario['CORREO_ELECTRONICO']; ?>" maxlength="70" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico_usuario" name="correo_electronico_usuario" readonly="readonly" placeholder="Correo Electrónico" value="<?php echo $row_usuario['CORREO_ELECTRONICO']; ?>" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico_usuario" name="correo_electronico_usuario" maxlength="70" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <label id="password-error"></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_usuario['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_usuario['ID_COD_DPTO']);
                                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                        ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" placeholder="Departamento" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio">Mpio:</label>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_usuario['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_usuario['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_usuario['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_usuario['ID_COD_MPIO']);
                                                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                        ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" placeholder="Municipio" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
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
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <?php
                                                                    if (isset($_GET['id_usuario_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_usuario" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Usuario</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Usuarios.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_usuario_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_usuario" type="button" data-toggle="modal" data-target="#modalEliminarUsuario"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Usuario</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Usuarios.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_usuario" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Usuario</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldUsuario();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
    <script src="Javascript/menu.js"></script>
    <script language="JavaScript">
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function resetFieldUsuario() {
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#tipo_identificacion_usuario").focus();
            $("#password-error").html("");
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
        function infoTipoMunicipio(id_consulta, id_municipio, municipio, abreviatura_municipio) {
            if (id_consulta == 1) {
                $("#id_municipio").val(id_municipio);
                $("#municipio").val(municipio);
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
            var password_ok = 0;
            var id_usuario_editar = $("#id_usuario_editar_hidden").val();
            var id_usuario_eliminar = $("#id_usuario_eliminar_hidden").val();
            var array_id_area_interna = <?php echo json_encode($array_id_area_interna); ?>;
            var array_pagination = <?php echo json_encode($array_pagination); ?>;
            var array_nombre_compania = <?php echo json_encode($array_nombre_compania); ?>;
            $("#tipo_identificacion_usuario").change(function () {
                var tipo_identificacion_usuario = $(this).val();
                if (tipo_identificacion_usuario != "") {
                    $("#identificacion_usuario").val("");
                    $("#identificacion_usuario").focus();
                }
            });
            for (var i=0; i<array_id_area_interna.length; i++) {
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Usuario.php",
                    dataType: "json",
                    data: "id_area_interna="+array_id_area_interna[i]+"&pagination="+array_pagination[i]+"&nombre_compania="+array_nombre_compania[i],
                    success: function(data) {
                        $("#"+data[0]).twbsPagination({
                            totalPages: data[1],
                            visiblePages: 15,
                            first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                            prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                            next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                            last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                            onPageClick: function (event, page) {
                                $("#loading-spinner-"+data[3]).css('display', 'block');
                                $.ajax({
                                    type: "POST",
                                    url: "Modelo/Cargar_Usuario.php",
                                    dataType: "json",
                                    data: "id_area_interna="+data[3]+"&nombre_compania="+data[2]+"&page="+page,
                                    success: function(data) {
                                        $("#loading-spinner-"+data[2]).css('display', 'none');
                                        $("#resultado_usuario_"+data[0]).html(data[1]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
            if (id_usuario_editar != undefined) {
                $(".nav-pills a[href='#crear_usuario_tab']").tab("show");
                $(".nav-pills a[href='#crear_usuario_tab']").text("Actualizar Usuario");
                $("#tipo_identificacion_usuario").val($("#tipo_identificacion_usuario_hidden").val());
                $("#area_interna_usuario").val($("#area_interna_usuario_hidden").val());
            } else {
                if (id_usuario_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_usuario_tab']").tab("show");
                    $(".nav-pills a[href='#crear_usuario_tab']").text("Eliminar Usuario");
                    $("#tipo_identificacion_usuario").val($("#tipo_identificacion_usuario_hidden").val());
                    $("#area_interna_usuario").val($("#area_interna_usuario_hidden").val());
                }
            }
            $("#tab_crear_usuario").on("shown.bs.tab", function() {
                $("#tipo_identificacion_usuario").focus();
            });
            $("#tab_crear_usuario").on("click", function() {
                $("#tipo_identificacion_usuario").focus();
            });
            $("#password1_usuario").focusin(function() {
                $("#password2_usuario").val("");
                $("#password-error").html("");
            })
            $("#password2_usuario").keyup(function() {
                if ($(this).val() != $("#password1_usuario").val()) {
                    password_ok = 0;
                    $("#password-error").html("<font color='C9302C'>Las Contraseñas no Coinciden</font>");
                } else {
                    password_ok = 1;
                    $("#password-error").html("<font color='149114'>Las Contraseñas Coinciden</font>");
                }
            });
            $("#eliminar_usuario").click(function() {
                var id_usuario_eliminar = $("#id_usuario_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Usuarios_.php",
                    data: "id_usuario_eliminar=" + id_usuario_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#tipo_identificacion_usuario").attr("disabled", false);
                            $("#area_interna_usuario").attr("disabled", false);
                            $("#crear_usuario").submit();
                        } else {
                            $("#modalEliminarUsuarioError").modal("show");
                        }
                    }
                });
            });
            $("#btn_crear_usuario").click(function() {
                if (id_usuario_eliminar == undefined) {
                    var tipo_identificacion_usuario = $("#tipo_identificacion_usuario").val();
                    var identificacion_usuario = $("#identificacion_usuario").val();
                    var nombre_usuario = $("#nombre_usuario").val();
                    var area_interna_usuario = $("#area_interna_usuario").val();
                    var usuario_bloqueado = $("input[name=usuario_bloqueado]:checked").val();
                    var user_usuario = $("#user_usuario").val();
                    var password1_usuario = $("#password1_usuario").val();
                    var password2_usuario = $("#password2_usuario").val();
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    if (tipo_identificacion_usuario.length == 0) {
                        $("#tipo_identificacion_usuario").focus();
                        return false;
                    }
                    if (identificacion_usuario.length == 0) {
                        $("#identificacion_usuario").focus();
                        return false;
                    }
                    if (nombre_usuario.length == 0) {
                        $("#nombre_usuario").focus();
                        return false;
                    }
                    if (area_interna_usuario.length == 0) {
                        $("#area_interna_usuario").focus();
                        return false;
                    }
                    if (usuario_bloqueado == undefined) {
                        $("#usuario_bloqueado").focus();
                        return false;
                    }
                    if (user_usuario.length == 0) {
                        $("#user_usuario").focus();
                        return false;
                    }
                    if (password1_usuario.length == 0) {
                        $("#password1_usuario").focus();
                        return false;
                    }
                    if (password2_usuario.length == 0) {
                        $("#password2_usuario").focus();
                        return false;
                    }
                    if (password_ok == 0) {
                        $("#password2_usuario").focus();
                        return false;
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('select[name=tipo_identificacion_usuario]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=identificacion_usuario]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=nombre_usuario]').tooltip({
                container: "body",
                placement: "right"
            });
            $('select[name=area_interna_usuario]').tooltip({
                container : "body",
                placement : "right"
            });
            $('label[name=label_activo]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=user_usuario]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=password][name=password1_usuario]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=password][name=password2_usuario]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=correo_electronico_usuario]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_seguridad").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>