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
        <title>AGM - Roles - Usuarios</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
    </head>
    <!--Eliminar Rol - Usuario Modal-->
    <div class="modal fade" id="modalEliminarRol" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Rol - Usuario</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Rol del Usuario?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_rol" name="eliminar_rol"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Rol - Usuario Modal-->
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-users"></i>
                                                                        <span>Roles</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Roles_Usuarios.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-server"></i>
                                                                                    <span>Roles - Usuarios</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Roles.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-users"></i>
                                                                                    <span>Roles</span>
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
                                            <h1>Roles - Usuarios</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_rol_usuario_tab" id="tab_info_rol_usuario" aria-controls="informacion_rol_usuario_tab" role="tab" data-toggle="tab">Información Roles - Usuarios</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#asignar_rol_usuario_tab" id="tab_asignar_rol_usuario" aria-controls="asignar_rol_usuario_tab" role="tab" data-toggle="tab">Asignar Rol - Usuario</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_rol_usuario_tab">
                                                    <ul class="nav nav-pills" role="tablist">
                                                        <?php
                                                            $sw = 0;
                                                            $array_id_area_interna = array();
                                                            $array_nombre_compania = array();
                                                            $query_tipo_companias = mysqli_query($connection, "SELECT DISTINCT(TP.NOMBRE) AS NOMBRE_COMPANIA, TP.ID_TIPO_COMPANIA
                                                                                                                 FROM roles_2 ROL, tipo_companias_2 TP, roles_usuarios_2 ROL_USU, usuarios_2 USU
                                                                                                                WHERE ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                  AND ROL.ID_ROL = ROL_USU.ID_ROL
                                                                                                                  AND ROL_USU.ID_USUARIO = USU.ID_USUARIO
                                                                                                                ORDER BY TP.ID_TIPO_COMPANIA");
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
                                                                                                                 FROM roles_2 ROL, tipo_companias_2 TP, roles_usuarios_2 ROL_USU, usuarios_2 USU
                                                                                                                WHERE ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                  AND ROL.ID_ROL = ROL_USU.ID_ROL
                                                                                                                  AND ROL_USU.ID_USUARIO = USU.ID_USUARIO
                                                                                                                ORDER BY TP.ID_TIPO_COMPANIA");
                                                            while ($tipo_companias = mysqli_fetch_assoc($query_tipo_companias)) {
                                                                $nombre_compania = strtolower(str_replace(' ', '_', $tipo_companias['NOMBRE_COMPANIA']));
                                                                if ($sw == 0) {
                                                                    $id_tipo_compania = $tipo_companias['ID_TIPO_COMPANIA'];
                                                                    $sw = 1;
                                                                    $array_pagination[] = "pagination-" . $nombre_compania;
                                                                ?>
                                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_<?php echo $nombre_compania; ?>_tab">
                                                                    <?php
                                                                        $query_roles_usuarios = mysqli_query($connection, "SELECT *
                                                                                                                             FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP, roles_2 ROL, roles_usuarios_2 ROLUSU, tipo_companias_2 TP
                                                                                                                            WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                                                                                                              AND USU.ID_USUARIO = ROLUSU.ID_USUARIO
                                                                                                                              AND ROL.ID_ROL = ROLUSU.ID_ROL
                                                                                                                              AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                              AND ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                              AND USTP.ID_TIPO_COMPANIA = " . $id_tipo_compania . "
                                                                                                                            ORDER BY USU.ID_USUARIO, ROL.NOMBRE");
                                                                        if (mysqli_num_rows($query_roles_usuarios) != 0) {
                                                                            echo "<div class='table-responsive'>";
                                                                                echo "<table class='table table-condensed table-hover'>";
                                                                                    echo "<thead>";
                                                                                        echo "<tr>";
                                                                                            echo "<th width=25%>NOMBRE</th>";
                                                                                            echo "<th width=10%>IDENTIFICACIÓN</th>";
                                                                                            echo "<th width=22%>CORREO ELECTRÓNICO</th>";
                                                                                            echo "<th width=12%>USUARIO</th>";
                                                                                            echo "<th width=15%>ROL</th>";
                                                                                            echo "<th width=5%>EDITAR</th>";
                                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                                        echo "</tr>";
                                                                                    echo "</thead>";
                                                                                    echo "<tbody id='resultado_rol_usuario_" . $nombre_compania . "'>";

                                                                                    echo "</tbody>";
                                                                                echo "</table>";
                                                                            echo "</div>";
                                                                        } else {
                                                                            echo "<br />";
                                                                            echo "<p class='message'>No se encontraron Roles - Usuarios Asignados.</p>";
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
                                                                            $query_roles_usuarios = mysqli_query($connection, "SELECT *
                                                                                                                                 FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP, roles_2 ROL, roles_usuarios_2 ROLUSU, tipo_companias_2 TP
                                                                                                                                WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                                                                                                                  AND USU.ID_USUARIO = ROLUSU.ID_USUARIO
                                                                                                                                  AND ROL.ID_ROL = ROLUSU.ID_ROL
                                                                                                                                  AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                                  AND ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                                  AND USTP.ID_TIPO_COMPANIA = " . $id_tipo_compania . "
                                                                                                                                ORDER BY USU.ID_USUARIO, ROL.NOMBRE");
                                                                            if (mysqli_num_rows($query_roles_usuarios) != 0) {
                                                                                echo "<div class='table-responsive'>";
                                                                                    echo "<table class='table table-condensed table-hover'>";
                                                                                        echo "<thead>";
                                                                                            echo "<tr>";
                                                                                                echo "<th width=25%>NOMBRE</th>";
                                                                                                echo "<th width=10%>IDENTIFICACIÓN</th>";
                                                                                                echo "<th width=22%>CORREO ELECTRÓNICO</th>";
                                                                                                echo "<th width=12%>USUARIO</th>";
                                                                                                echo "<th width=15%>ROL</th>";
                                                                                                echo "<th width=5%>EDITAR</th>";
                                                                                                echo "<th width=5%>ELIMINAR</th>";
                                                                                            echo "</tr>";
                                                                                        echo "</thead>";
                                                                                        echo "<tbody id='resultado_rol_usuario_" . $nombre_compania . "'>";

                                                                                        echo "</tbody>";
                                                                                    echo "</table>";
                                                                                echo "</div>";
                                                                            } else {
                                                                                echo "<br />";
                                                                                echo "<p class='message'>No se encontraron Roles - Usuarios Asignados.</p>";
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
                                                <div role="tabpanel" class="tab-pane fade" id="asignar_rol_usuario_tab">
                                                    <?php
                                                        if (isset($_GET['id_rol_usuario_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="asignar_rol_usuario" name="asignar_rol_usuario" action="<?php echo "Modelo/Asignar_Rol_Usuario.php?editar=" . $_GET['id_rol_usuario_editar'] . "&id_area_interna=" . $_GET['id_area_interna']; ?>" method="post">
                                                        <?php
                                                            $query_select_rol_usuario = mysqli_query($connection, "SELECT USU.NOMBRE AS NOMBRE_USUARIO, USU.IDENTIFICACION AS IDENTIFICACION_USUARIO,
                                                                                                                          CORREO_ELECTRONICO AS CORREO_ELECTRONICO_USUARIO, USU.USUARIO AS USUARIO,
                                                                                                                          ROL.NOMBRE AS NOMBRE_ROL, ROLUSU.ID_TABLA AS ID_ROL_USUARIO,
                                                                                                                          USTP.ID_TIPO_COMPANIA AS ID_TIPO_COMPANIA,
                                                                                                                          USU.ID_USUARIO AS ID_USUARIO
                                                                                                                     FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP,
                                                                                                                          roles_2 ROL, roles_usuarios_2 ROLUSU, tipo_companias_2 TP
                                                                                                                    WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                                                                                                      AND USU.ID_USUARIO = ROLUSU.ID_USUARIO
                                                                                                                      AND ROL.ID_ROL = ROLUSU.ID_ROL
                                                                                                                      AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                      AND ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                      AND ROLUSU.ID_TABLA = " . $_GET['id_rol_usuario_editar'] . "
                                                                                                                      AND USTP.ID_TIPO_COMPANIA = " . $_GET['id_area_interna']);
                                                            $row_rol_usuario = mysqli_fetch_array($query_select_rol_usuario);
                                                        ?>
                                                            <input type="hidden" id="id_rol_usuario_editar_hidden" name="id_rol_usuario_editar_hidden" value="<?php echo $row_rol_usuario['ID_ROL_USUARIO']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_rol_usuario_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="asignar_rol_usuario" name="asignar_rol_usuario" action="<?php echo "Modelo/Asignar_Rol_Usuario.php?eliminar=" . $_GET['id_rol_usuario_eliminar'] . "&id_area_interna=" . $_GET['id_area_interna']; ?>" method="post">
                                                                <?php
                                                                        $query_select_rol_usuario = mysqli_query($connection, "SELECT USU.NOMBRE AS NOMBRE_USUARIO, USU.IDENTIFICACION AS IDENTIFICACION_USUARIO,
                                                                                                                                      CORREO_ELECTRONICO AS CORREO_ELECTRONICO_USUARIO, USU.USUARIO AS USUARIO,
                                                                                                                                      ROL.NOMBRE AS NOMBRE_ROL, ROLUSU.ID_TABLA AS ID_ROL_USUARIO,
                                                                                                                                      USTP.ID_TIPO_COMPANIA AS ID_TIPO_COMPANIA,
                                                                                                                                      USU.ID_USUARIO AS ID_USUARIO
                                                                                                                                 FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP,
                                                                                                                                      roles_2 ROL, roles_usuarios_2 ROLUSU, tipo_companias_2 TP
                                                                                                                                WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                                                                                                                  AND USU.ID_USUARIO = ROLUSU.ID_USUARIO
                                                                                                                                  AND ROL.ID_ROL = ROLUSU.ID_ROL
                                                                                                                                  AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                                  AND ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                                  AND ROLUSU.ID_TABLA = " . $_GET['id_rol_usuario_eliminar'] . "
                                                                                                                                  AND USTP.ID_TIPO_COMPANIA = " . $_GET['id_area_interna']);
                                                                        $row_rol_usuario = mysqli_fetch_array($query_select_rol_usuario);
                                                                ?>
                                                                        <input type="hidden" id="id_rol_usuario_eliminar_hidden" name="id_rol_usuario_eliminar_hidden" value="<?php echo $row_rol_usuario['ID_ROL_USUARIO']; ?>" />
                                                                <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="asignar_rol_usuario" name="asignar_rol_usuario" action="<?php echo "Modelo/Asignar_Rol_Usuario.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                        }
                                                    ?>
                                                    <div class="form-group">
                                                        <div class="col-xs-6">
                                                            <div class="styled-select">
                                                                <!--<input type="hidden" id="id_area_interna" name="id_area_interna" value="" />
                                                                <input type="text" class="form-control input-text input-sm" id="area_interna" name="area_interna" placeholder="Área Interna" required data-toogle="tooltip" readonly="readonly" title="ÁREA INTERNA" onclick="areaInterna()" />-->
                                                                <?php
                                                                    if (isset($_GET['id_rol_usuario_editar'])) { ?>
                                                                        <select class="form-control input-text input-sm" id="area_interna_rol_usuario" name="area_interna_rol_usuario" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_rol_usuario_eliminar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="area_interna_rol_usuario" disabled="disabled" name="area_interna_rol_usuario" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                        <?php
                                                                        } else { ?>
                                                                            <select class="form-control input-text input-sm" id="area_interna_rol_usuario" name="area_interna_rol_usuario" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <option value="" selected="selected">-</option>
                                                                    <?php
                                                                        $query_select_area_interna_rol_usuario = "SELECT * FROM tipo_companias_2 ORDER BY NOMBRE";
                                                                        $sql_select_area_interna_rol_usuario = mysqli_query($connection, $query_select_area_interna_rol_usuario);
                                                                        while ($row_area_interna_rol_usuario = mysqli_fetch_assoc($sql_select_area_interna_rol_usuario)) {
                                                                            echo "<option value='" . $row_area_interna_rol_usuario['ID_TIPO_COMPANIA'] . "'>" . $row_area_interna_rol_usuario['NOMBRE'] . "</option>";
                                                                        }
                                                                        if (isset($_GET['id_rol_usuario_editar']) || isset($_GET['id_rol_usuario_eliminar'])) { ?>
                                                                            <input type="hidden" id="area_interna_rol_usuario_hidden" name="area_interna_rol_usuario_hidden" value="<?php echo $row_rol_usuario['ID_TIPO_COMPANIA'] ?>" />
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-xs-9">
                                                            <?php
                                                                if (isset($_GET['id_rol_usuario_editar'])) {
                                                                    $query_select_usuario = mysqli_query($connection, "SELECT USU.ID_USUARIO AS ID_USUARIO,
                                                                                                                              USU.NOMBRE AS NOMBRE_USUARIO,
                                                                                                                              TP.ID_TIPO_COMPANIA AS ID_TIPO_COMPANIA,
                                                                                                                              TP.NOMBRE AS NOMBRE_COMPANIA
                                                                                                                         FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP, tipo_companias_2 TP
                                                                                                                        WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                                                                                                          AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                          AND USTP.ID_TIPO_COMPANIA = " . $row_rol_usuario['ID_TIPO_COMPANIA'] . "
                                                                                                                          AND USU.ID_USUARIO = " . $row_rol_usuario['ID_USUARIO'] . "
                                                                                                                        ORDER BY NOMBRE_COMPANIA, NOMBRE_USUARIO");
                                                                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                                                                ?>
                                                                    <input type="hidden" id="id_usuario_rol" name="id_usuario_rol" value="<?php echo $row_usuario['ID_USUARIO']; ?>" />
                                                                    <input type="text" class="form-control input-text input-sm" id="nombre_usuario_rol" name="nombre_usuario_rol" value="<?php echo $row_usuario['NOMBRE_USUARIO']; ?>" placeholder="Nombre Usuario" data-toogle="tooltip" readonly="readonly" title="NOMBRE USUARIO" onclick="nombreUsuarioRol()" />
                                                                <?php
                                                                } else {
                                                                    if (isset($_GET['id_rol_usuario_eliminar'])) {
                                                                        $query_select_usuario = mysqli_query($connection, "SELECT USU.ID_USUARIO AS ID_USUARIO,
                                                                                                                                  USU.NOMBRE AS NOMBRE_USUARIO,
                                                                                                                                  TP.ID_TIPO_COMPANIA AS ID_TIPO_COMPANIA,
                                                                                                                                  TP.NOMBRE AS NOMBRE_COMPANIA
                                                                                                                             FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP, tipo_companias_2 TP
                                                                                                                            WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                                                                                                              AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                                              AND USTP.ID_TIPO_COMPANIA = " . $row_rol_usuario['ID_TIPO_COMPANIA'] . "
                                                                                                                              AND USU.ID_USUARIO = " . $row_rol_usuario['ID_USUARIO'] . "
                                                                                                                            ORDER BY NOMBRE_COMPANIA, NOMBRE_USUARIO");
                                                                        $row_usuario = mysqli_fetch_array($query_select_usuario);
                                                                    ?>
                                                                        <input type="hidden" id="id_usuario_rol" name="id_usuario_rol" value="<?php echo $row_usuario['ID_USUARIO']; ?>" />
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_usuario_rol" name="nombre_usuario_rol" value="<?php echo $row_usuario['NOMBRE_USUARIO']; ?>" placeholder="Nombre Usuario" data-toogle="tooltip" readonly="readonly" title="NOMBRE USUARIO" />
                                                                    <?php
                                                                    } else { ?>
                                                                        <input type="hidden" id="id_usuario_rol" name="id_usuario_rol" value="" />
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_usuario_rol" name="nombre_usuario_rol" placeholder="Nombre Usuario" data-toogle="tooltip" readonly="readonly" title="NOMBRE USUARIO" onclick="nombreUsuarioRol()" />
                                                                    <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-xs-9">
                                                            <?php
                                                                if (isset($_GET['id_rol_usuario_editar'])) {
                                                                    $query_select_rol = mysqli_query($connection, "SELECT ROL.ID_ROL AS ID_ROL,
                                                                                                                          ROL.NOMBRE AS NOMBRE_ROL
                                                                                                                     FROM roles_2 ROL, roles_usuarios_2 ROLUSU
                                                                                                                    WHERE ROL.ID_ROL = ROLUSU.ID_ROL
                                                                                                                      AND ROLUSU.ID_TABLA = " . $row_rol_usuario['ID_ROL_USUARIO']);
                                                                    $row_rol = mysqli_fetch_array($query_select_rol);
                                                                ?>
                                                                    <input type="hidden" id="id_rol" name="id_rol" value="<?php echo $row_rol['ID_ROL']; ?>" />
                                                                    <input type="text" class="form-control input-text input-sm" id="nombre_rol" name="nombre_rol" value="<?php echo $row_rol['NOMBRE_ROL']; ?>" placeholder="Nombre Rol" data-toogle="tooltip" readonly="readonly" title="NOMBRE ROL" onclick="nombreRol()" />
                                                                <?php
                                                                } else {
                                                                    if (isset($_GET['id_rol_usuario_eliminar'])) {
                                                                        $query_select_rol = mysqli_query($connection, "SELECT ROL.ID_ROL AS ID_ROL,
                                                                                                                              ROL.NOMBRE AS NOMBRE_ROL
                                                                                                                         FROM roles_2 ROL, roles_usuarios_2 ROLUSU
                                                                                                                        WHERE ROL.ID_ROL = ROLUSU.ID_ROL
                                                                                                                          AND ROLUSU.ID_TABLA = " . $row_rol_usuario['ID_ROL_USUARIO']);
                                                                        $row_rol = mysqli_fetch_array($query_select_rol);
                                                                    ?>
                                                                        <input type="hidden" id="id_rol" name="id_rol" value="<?php echo $row_rol['ID_ROL']; ?>" />
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_rol" name="nombre_rol" value="<?php echo $row_rol['NOMBRE_ROL']; ?>" placeholder="Nombre Rol" data-toogle="tooltip" readonly="readonly" title="NOMBRE ROL" />
                                                                    <?php
                                                                    } else { ?>
                                                                        <input type="hidden" id="id_rol" name="id_rol" value="" />
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_rol" name="nombre_rol" placeholder="Nombre Rol" data-toogle="tooltip" readonly="readonly" title="NOMBRE ROL" onclick="nombreRol()" />
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
                                                                if (isset($_GET['id_rol_usuario_editar'])) { ?>
                                                                    <button class="btn btn-primary btn-sm font background cursor" id="btn_asignar_rol" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Asignación Rol</button>&nbsp;&nbsp;
                                                                    <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Roles_Usuarios.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                <?php
                                                                } else {
                                                                    if (isset($_GET['id_rol_usuario_eliminar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_asignar_rol" type="button" data-toggle="modal" data-target="#modalEliminarRol"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Asignación Rol</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Roles_Usuarios.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_asignar_rol" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Asignar Rol</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldRol();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    }
                                                                    ?>
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
        function resetFieldRol() {
            document.getElementById('area_interna_rol_usuario').focus();
        }
        //POPUPS
        function infoUsuariosRol(id_usuario_rol, nombre_usuario_rol) {
            $("#id_usuario_rol").val(id_usuario_rol);
            $("#nombre_usuario_rol").val(nombre_usuario_rol);
            $("#id_rol").val("");
            $("#nombre_rol").val("");
            $("#nombre_rol").focus();
        }
        function nombreUsuarioRol() {
            var id_tipo_compania = $("#area_interna_rol_usuario").val();
            window.open('Combos/Usuarios.php?id_tipo_compania='+id_tipo_compania, 'Popup', 'width=500, height=500');
        }
        function infoRol(id_rol, nombre_rol) {
            $("#id_rol").val(id_rol);
            $("#nombre_rol").val(nombre_rol);
        }
        function nombreRol() {
            var id_tipo_compania = $("#area_interna_rol_usuario").val();
            var id_usuario_rol = $("#id_usuario_rol").val();
            window.open('Combos/Roles.php?id_tipo_compania='+id_tipo_compania+'&id_usuario_rol='+id_usuario_rol, 'Popup', 'width=500, height=500');
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            var id_rol_usuario_editar = $("#id_rol_usuario_editar_hidden").val();
            var id_rol_usuario_eliminar = $("#id_rol_usuario_eliminar_hidden").val();
            var array_id_area_interna = <?php echo json_encode($array_id_area_interna); ?>;
            var array_pagination = <?php echo json_encode($array_pagination); ?>;
            var array_nombre_compania = <?php echo json_encode($array_nombre_compania); ?>;
            for (var i=0; i<array_id_area_interna.length; i++) {
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Roles_Usuario.php",
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
                                    url: "Modelo/Cargar_Roles_Usuario.php",
                                    dataType: "json",
                                    data: "id_area_interna="+data[3]+"&nombre_compania="+data[2]+"&page="+page,
                                    success: function(data) {
                                        $("#loading-spinner-"+data[2]).css('display', 'none');
                                        $("#resultado_rol_usuario_"+data[0]).html(data[1]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
            if (id_rol_usuario_editar != undefined) {
                $(".nav-pills a[href='#asignar_rol_usuario_tab']").tab("show");
                $(".nav-pills a[href='#asignar_rol_usuario_tab']").text("Actualizar Rol - Usuario");
                $("#area_interna_rol_usuario").val($("#area_interna_rol_usuario_hidden").val());
            } else {
                if (id_rol_usuario_eliminar != undefined) {
                    $(".nav-pills a[href='#asignar_rol_usuario_tab']").tab("show");
                    $(".nav-pills a[href='#asignar_rol_usuario_tab']").text("Eliminar Rol - Usuario");
                    $("#area_interna_rol_usuario").val($("#area_interna_rol_usuario_hidden").val());
                }
            }
            $("#tab_asignar_rol_usuario").on("shown.bs.tab", function(){
                $("#area_interna_rol_usuario").focus();
            });
            $("#tab_asignar_rol_usuario").on("click", function(){
                $("#area_interna_rol_usuario").focus();
            });
            $("#area_interna_rol_usuario").change(function() {
                $("#id_usuario_rol").val("");
                $("#nombre_usuario_rol").val("");
                $("#id_rol").val("");
                $("#nombre_rol").val("");
                $("#nombre_usuario_rol").focus();
            });
            $("#btn_asignar_rol").click(function() {
                var area_interna_rol_usuario = $("#area_interna_rol_usuario").val();
                var nombre_usuario_rol = $("#nombre_usuario_rol").val();
                var nombre_rol = $("#nombre_rol").val();
                if (area_interna_rol_usuario.length == 0) {
                    $("#area_interna_rol_usuario").focus();
                    return false;
                }
                if (nombre_usuario_rol.length == 0) {
                    $("#nombre_usuario_rol").focus();
                    return false;
                }
                if (nombre_rol.length == 0) {
                    $("#nombre_rol").focus();
                    return false;
                }
                $("#area_interna_rol_usuario").attr("disabled", false);
            });
            $("#eliminar_rol").click(function() {
                $("#asignar_rol_usuario").submit();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('select[name=area_interna_rol_usuario]').tooltip({
                container : "body",
                placement : "right"
            });
            $('input[type=text][name=nombre_usuario_rol]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=nombre_rol]').tooltip({
                container : "body",
                placement : "top"
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