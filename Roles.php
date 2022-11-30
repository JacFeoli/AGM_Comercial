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
        <title>AGM - Roles</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
    </head>
    <!--Eliminar Rol Modal-->
    <div class="modal fade" id="modalEliminarRol" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                            &times;
                    </button>
                    <h4 class="modal-title">Eliminar Rol</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Rol?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_rol" name="eliminar_rol"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Rol Modal-->
    <!--Eliminar Rol Error-->
    <div class="modal fade" id="modalEliminarRolError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                            &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Rol</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Rol, ya que existen Usuarios asignados. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Rol Error-->
    <!--Editar Rol Error-->
    <div class="modal fade" id="modalEditarRolError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                            &times;
                    </button>
                    <h4 class="modal-title">Error Editar Rol</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Editar el Rol, ya que existen Usuarios asignados. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Editar Rol Error-->
    <!--Rol Existe Error-->
    <div class="modal fade" id="modalRolExisteError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                            &times;
                    </button>
                    <h4 class="modal-title">Error Rol Existente</h4>
                </div>
                <div class="modal-body">
                    <p>El Rol y/o la Abreviatura digitada, ya se encuentra Creada en el Área. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" id="cerrar_rol_existente" name="cerrar_rol_existente" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Rol Existe Error-->
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
                                                                                <a href='Roles.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-users"></i>
                                                                                    <span>Roles</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Roles_Usuarios.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-server"></i>
                                                                                    <span>Roles - Usuarios</span>
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
                                            <h1>Roles</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_rol_tab" id="tab_info_rol" aria-controls="informacion_rol_tab" role="tab" data-toggle="tab">Información Roles</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_rol_tab" id="tab_crear_rol" aria-controls="crear_rol_tab" role="tab" data-toggle="tab">Crear Rol</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_rol_tab">
                                                    <ul class="nav nav-pills" role="tablist">
                                                        <?php
                                                            $sw = 0;
                                                            $query_tipo_companias = mysqli_query($connection, "SELECT DISTINCT(TP.NOMBRE) AS NOMBRE_COMPANIA, TP.ID_TIPO_COMPANIA
                                                                                                   FROM roles_2 ROL, tipo_companias_2 TP
                                                                                                  WHERE ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                  ORDER BY ROL.ID_TIPO_COMPANIA");
                                                            while ($tipo_companias = mysqli_fetch_assoc($query_tipo_companias)) {
                                                                $nombre_compania = strtolower(str_replace(' ', '_', $tipo_companias['NOMBRE_COMPANIA']));
                                                                if ($sw == 0) {
                                                                    $sw = 1;
                                                                ?>
                                                                    <li role="presentation" class="active">
                                                                        <a href="#informacion_<?php echo $nombre_compania; ?>_tab" id="tab_info_<?php echo $nombre_compania; ?>" aria-controls="informacion_<?php echo $nombre_compania; ?>_tab" role="tab" data-toggle="tab"><?php echo ucwords(strtolower($tipo_companias['NOMBRE_COMPANIA'])); ?></a>
                                                                    </li>
                                                                <?php
                                                                } else { ?>
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
                                                            $query_tipo_companias = mysqli_query($connection, "SELECT DISTINCT(TP.NOMBRE) AS NOMBRE_COMPANIA, TP.ID_TIPO_COMPANIA
                                                                                                  FROM roles_2 ROL, tipo_companias_2 TP
                                                                                                 WHERE ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                                                                                 ORDER BY ROL.ID_TIPO_COMPANIA");
                                                            while ($tipo_companias = mysqli_fetch_assoc($query_tipo_companias)) {
                                                                    $nombre_compania = strtolower(str_replace(' ', '_', $tipo_companias['NOMBRE_COMPANIA']));
                                                                    if ($sw == 0) {
                                                                        $id_tipo_compania = $tipo_companias['ID_TIPO_COMPANIA'];
                                                                        $sw = 1;
                                                                        ?>
                                                                        <div role="tabpanel" class="tab-pane fade in active" id="informacion_<?php echo $nombre_compania; ?>_tab">
                                                                            <?php
                                                                                $query_roles = mysqli_query($connection, "SELECT *
                                                                                                              FROM roles_2
                                                                                                             WHERE ID_TIPO_COMPANIA = " . $id_tipo_compania . "
                                                                                                             ORDER BY NOMBRE");
                                                                                if (mysqli_num_rows($query_roles) != 0) {
                                                                                    echo "<div class='table-responsive'>";
                                                                                        echo "<table class='table table-condensed table-hover'>";
                                                                                            echo "<thead>";
                                                                                                echo "<tr>";
                                                                                                    echo "<th width=70%>NOMBRE</th>";
                                                                                                    echo "<th width=20%>ABREVIATURA</th>";
                                                                                                    echo "<th width=5%>EDITAR</th>";
                                                                                                    echo "<th width=5%>ELIMINAR</th>";
                                                                                                echo "</tr>";
                                                                                            echo "</thead>";
                                                                                            echo "<tbody id='resultado_usuario_" . $nombre_compania . "'>";
                                                                                                while ($row_rol = mysqli_fetch_assoc($query_roles)) {
                                                                                                    echo "<tr>";
                                                                                                        echo "<td style='vertical-align:middle;'>" . $row_rol['NOMBRE'] . "</td>";
                                                                                                        echo "<td style='vertical-align:middle;'>" . $row_rol['ABREVIATURA_ROL'] . "</td>";
                                                                                                        echo "<td style='vertical-align:middle;'><button><a href='Roles.php?id_rol_editar=" . $row_rol['ID_ROL'] . "&id_area_interna=" . $id_tipo_compania . "'><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></a></button></td>";
                                                                                                        echo "<td style='vertical-align:middle;'><button><a href='Roles.php?id_rol_eliminar=" . $row_rol['ID_ROL'] . "&id_area_interna=" . $id_tipo_compania . "'><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></a></button></td>";
                                                                                                    echo "</tr>";
                                                                                                }
                                                                                            echo "</tbody>";
                                                                                        echo "</table>";
                                                                                    echo "</div>";
                                                                                } else {
                                                                                    echo "<br />";
                                                                                    echo "<p class='message'>No se encontraron Roles Creados.</p>";
                                                                                }
                                                                            ?>
                                                                        </div>
                                                                <?php
                                                                    } else {
                                                                        $id_tipo_compania = $tipo_companias['ID_TIPO_COMPANIA'];
                                                                ?>
                                                                    <div role="tabpanel" class="tab-pane fade" id="informacion_<?php echo $nombre_compania; ?>_tab">
                                                                        <?php
                                                                            $query_roles = mysqli_query($connection, "SELECT *
                                                                                                          FROM roles_2
                                                                                                         WHERE ID_TIPO_COMPANIA = " . $id_tipo_compania . "
                                                                                                         ORDER BY NOMBRE");
                                                                            if (mysqli_num_rows($query_roles) != 0) {
                                                                                echo "<div class='table-responsive'>";
                                                                                    echo "<table class='table table-condensed table-hover'>";
                                                                                        echo "<thead>";
                                                                                            echo "<tr>";
                                                                                                echo "<th width=70%>NOMBRE</th>";
                                                                                                echo "<th width=20%>ABREVIATURA</th>";
                                                                                                echo "<th width=5%>EDITAR</th>";
                                                                                                echo "<th width=5%>ELIMINAR</th>";
                                                                                            echo "</tr>";
                                                                                        echo "</thead>";
                                                                                        echo "<tbody id='resultado_usuario_" . $nombre_compania . "'>";
                                                                                            while ($row_rol = mysqli_fetch_assoc($query_roles)) {
                                                                                                echo "<tr>";
                                                                                                    echo "<td style='vertical-align:middle;'>" . $row_rol['NOMBRE'] . "</td>";
                                                                                                    echo "<td style='vertical-align:middle;'>" . $row_rol['ABREVIATURA_ROL'] . "</td>";
                                                                                                    echo "<td style='vertical-align:middle;'><a href='Roles.php?id_rol_editar=" . $row_rol['ID_ROL'] . "&id_area_interna=" . $id_tipo_compania . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                                                                                                    echo "<td style='vertical-align:middle;'><a href='Roles.php?id_rol_eliminar=" . $row_rol['ID_ROL'] . "&id_area_interna=" . $id_tipo_compania . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
                                                                                                echo "</tr>";
                                                                                            }
                                                                                        echo "</tbody>";
                                                                                    echo "</table>";
                                                                                echo "</div>";
                                                                            } else {
                                                                                echo "<br />";
                                                                                echo "<p class='message'>No se encontraron Roles Creados.</p>";
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                <?php
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_rol_tab">
                                                    <?php
                                                        if (isset($_GET['id_rol_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_rol" name="crear_rol" action="<?php echo "Modelo/Crear_Rol.php?editar=" . $_GET['id_rol_editar']. "&id_area_interna=" . $_GET['id_area_interna']; ?>" method="post">
                                                        <?php
                                                            $query_select_roles = mysqli_query($connection, "SELECT * FROM roles_2 WHERE ID_ROL = " . $_GET['id_rol_editar'] . " AND ID_TIPO_COMPANIA = " . $_GET['id_area_interna']);
                                                            $row_rol = mysqli_fetch_array($query_select_roles);
                                                        ?>
                                                                    <input type="hidden" id="id_tabla_editar_hidden" name="id_tabla_editar_hidden" value="<?php echo $row_rol['ID_ROL']; ?>" />
                                                        <?php
                                                        } else {
                                                                if (isset($_GET['id_rol_eliminar'])) { ?>
                                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_rol" name="crear_rol" action="<?php echo "Modelo/Crear_Rol.php?eliminar=" . $_GET['id_rol_eliminar']. "&id_area_interna=" . $_GET['id_area_interna']; ?>" method="post">
                                                                <?php
                                                                    $query_select_roles = mysqli_query($connection, "SELECT * FROM roles_2 WHERE ID_ROL = " . $_GET['id_rol_eliminar'] . " AND ID_TIPO_COMPANIA = " . $_GET['id_area_interna']);
                                                                    $row_rol = mysqli_fetch_array($query_select_roles);
                                                                ?>
                                                                    <input type="hidden" id="id_tabla_eliminar_hidden" name="id_tabla_eliminar_hidden" value="<?php echo $row_rol['ID_ROL']; ?>" />
                                                                <?php
                                                                } else { ?>
                                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_rol" name="crear_rol" action="<?php echo "Modelo/Crear_Rol.php"; ?>" method="post">
                                                                <?php
                                                                }
                                                                ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_rol_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_rol" name="nombre_rol" value="<?php echo $row_rol['NOMBRE']; ?>" maxlength="50" placeholder="Nombre Rol" data-toggle="tooltip" title="NOMBRE ROL" required />
                                                                        <input type="hidden" id="nombre_rol_hidden" name="nombre_rol_hidden" value="<?php echo $row_rol['NOMBRE']; ?>" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_rol_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm readonly" id="nombre_rol" name="nombre_rol" value="<?php echo $row_rol['NOMBRE']; ?>" maxlength="50" placeholder="Nombre Rol" data-toggle="tooltip" title="NOMBRE ROL" required readonly="readonly" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_rol" name="nombre_rol" value="" maxlength="50" placeholder="Nombre Rol" data-toggle="tooltip" title="NOMBRE ROL" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_rol_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="abreviatura_rol" name="abreviatura_rol" value="<?php echo $row_rol['ABREVIATURA_ROL']; ?>" maxlength="5" placeholder="Abreviatura Rol" data-toggle="tooltip" title="ABREVIATURA ROL" required />
                                                                        <input type="hidden" id="abreviatura_rol_hidden" name="abreviatura_rol_hidden" value="<?php echo $row_rol['ABREVIATURA_ROL']; ?>" />
                                                                    <?php
                                                                    } else {
                                                                            if (isset($_GET['id_rol_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm readonly" id="abreviatura_rol" name="abreviatura_rol" value="<?php echo $row_rol['ABREVIATURA_ROL']; ?>" maxlength="5" placeholder="Abreviatura Rol" data-toggle="tooltip" title="ABREVIATURA ROL" required readonly="readonly" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" id="abreviatura_rol" name="abreviatura_rol" value="" maxlength="5" placeholder="Abreviatura Rol" data-toggle="tooltip" title="ABREVIATURA ROL" required />
                                                                            <?php
                                                                            }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_rol_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="area_interna_rol" name="area_interna_rol" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_rol_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="area_interna_rol" name="area_interna_rol" disabled="disabled" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="area_interna_rol" name="area_interna_rol" data-toogle="tooltip" title="ÁREA INTERNA" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <option value="" selected="selected">-</option>
                                                                        <?php
                                                                            $query_select_area_interna_rol = "SELECT * FROM tipo_companias_2 ORDER BY NOMBRE";
                                                                            $sql_select_area_interna_rol = mysqli_query($connection, $query_select_area_interna_rol);
                                                                            while ($row_area_interna_rol = mysqli_fetch_assoc($sql_select_area_interna_rol)) {
                                                                                echo "<option value='" . $row_area_interna_rol['ID_TIPO_COMPANIA'] . "'>" . $row_area_interna_rol['NOMBRE'] . "</option>";
                                                                            }
                                                                            if (isset($_GET['id_rol_editar']) || isset($_GET['id_rol_eliminar'])) { ?>
                                                                                <input type="hidden" id="area_interna_rol_hidden" name="area_interna_rol_hidden" value="<?php echo $row_rol['ID_TIPO_COMPANIA'] ?>" />
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
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <?php
                                                                    if (isset($_GET['id_rol_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_rol" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Rol</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Roles.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_rol_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_rol" type="button" data-toggle="modal" data-target="#modalEliminarRol"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Rol</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Roles.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_rol" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Rol</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetField();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    <?php
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
    <script src="Javascript/menu.js"></script>
    <script>
        function resetField() {
            document.getElementById('nombre_rol').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            var id_tabla_editar = $("#id_tabla_editar_hidden").val();
            var id_tabla_eliminar = $("#id_tabla_eliminar_hidden").val();
            if (id_tabla_editar != undefined) {
                $(".nav-pills a[href='#crear_rol_tab']").tab("show");
                $(".nav-pills a[href='#crear_rol_tab']").text("Actualizar Rol");
                $("#area_interna_rol").val($("#area_interna_rol_hidden").val());
            } else {
                if (id_tabla_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_rol_tab']").tab("show");
                    $(".nav-pills a[href='#crear_rol_tab']").text("Eliminar Rol");
                    $("#area_interna_rol").val($("#area_interna_rol_hidden").val());
                }
            }
            $("#tab_crear_rol").on("shown.bs.tab", function() {
                $("#nombre_rol").focus();
            });
            $("#tab_crear_rol").on("click", function() {
                $("#nombre_rol").focus();
            });
            $("#btn_crear_rol").click(function() {
                if (id_tabla_eliminar == undefined) {
                    var nombre_rol = $("#nombre_rol").val().toUpperCase();
                    var abreviatura_rol = $("#abreviatura_rol").val().toUpperCase();
                    var area_interna_rol = $("#area_interna_rol").val();
                    //alert(nombre_rol + ' - ' + nombre_rol_hidden + ' - ' + abreviatura_rol + ' - ' + abreviatura_rol_hidden + ' - ' + area_interna_rol + ' - ' + area_interna_rol_hidden);
                    if (nombre_rol.length == 0) {
                        $("#nombre_rol").focus();
                        return false;
                    }
                    if (abreviatura_rol.length == 0) {
                        $("#abreviatura_rol").focus();
                        return false;
                    }
                    if (area_interna_rol.length == 0) {
                        $("#area_interna_rol").focus();
                        return false;
                    }
                    if (id_tabla_editar != undefined) {
                        var nombre_rol_hidden = $("#nombre_rol_hidden").val().toUpperCase();
                        var abreviatura_rol_hidden = $("#abreviatura_rol_hidden").val().toUpperCase();
                        var area_interna_rol_hidden = $("#area_interna_rol_hidden").val();
                        if (nombre_rol != nombre_rol_hidden || abreviatura_rol != abreviatura_rol_hidden || area_interna_rol != area_interna_rol_hidden) {
                            $.ajax({
                                type: "POST",
                                url: "Verify/Verificar_Roles.php",
                                data: "editar='editar'&nombre_rol="+nombre_rol+"&area_interna_rol="+area_interna_rol+'&abreviatura_rol='+abreviatura_rol+
                                      '&nombre_rol_hidden='+nombre_rol_hidden+'&area_interna_rol_hidden='+area_interna_rol_hidden+
                                      '&abreviatura_rol_hidden='+abreviatura_rol_hidden,
                                success: function(data) {
                                    if (data == 0) {
                                        $.ajax({
                                            type: "POST",
                                            url: "Verify/Verificar_Roles_Usuarios.php",
                                            data: "id_tabla_eliminar="+id_tabla_editar+"&area_interna_rol="+area_interna_rol,
                                            success: function(data) {
                                                if (data == 0) {
                                                    $("#crear_rol").submit();
                                                } else {
                                                    $("#modalEditarRolError").modal("show");
                                                }
                                            }
                                        });
                                    } else {
                                        $("#modalRolExisteError").modal("show");
                                    }
                                }
                            });
                        } else {
                            $("#crear_rol").submit();
                        }
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "Verify/Verificar_Roles.php",
                            data: "nombre_rol="+nombre_rol+"&area_interna_rol="+area_interna_rol+'&abreviatura_rol='+abreviatura_rol,
                            success: function(data) {
                                if (data == 0) {
                                    $("#crear_rol").submit();
                                } else {
                                    $("#modalRolExisteError").modal("show");
                                }
                        }
                        });
                    }
                }
            });
            $("#cerrar_rol_existente").click(function() {
                //$("#nombre_rol").val("");
                $("#nombre_rol").focus();
            });
            $("#eliminar_rol").click(function() {
                var id_tabla_eliminar = $("#id_tabla_eliminar_hidden").val();
                var area_interna_rol = $("#area_interna_rol").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Roles_Usuarios.php",
                    data: "id_tabla_eliminar="+id_tabla_eliminar+"&area_interna_rol="+area_interna_rol,
                    success: function(data) {
                        if (data == 0) {
                            $("#area_interna_rol").removeAttr('disabled');
                            $("#crear_rol").submit();
                        } else {
                            $("#modalEliminarRolError").modal("show");
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=text][name=nombre_rol]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=abreviatura_rol]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=area_interna_rol]').tooltip({
                container : "body",
                placement : "right"
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