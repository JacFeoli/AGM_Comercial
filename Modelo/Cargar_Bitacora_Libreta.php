<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Bitacora_Municipio.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_bitacora_libreta']) != "") {
                if ($_POST['busqueda_bitacora_libreta'] != "") {
                    $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_bitacora_libreta'] . "%' ";
                } else {
                    $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_BITACORA_MUNICIPIO * ($page - 1);
        }
        $query_bitacora_municipio = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                      ML.ID_MUNICIPIO_LIBRETA
                                                                 FROM municipios_libreta_2 ML, departamentos_visitas_2 DEPT,
                                                                      municipios_visitas_2 MUN
                                                                 $busqueda_bitacora_libreta
                                                                  AND ML.ID_DEPARTAMENTO = DEPT.ID_DEPARTAMENTO
                                                                  AND ML.ID_MUNICIPIO = MUN.ID_MUNICIPIO
                                                                  AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                ORDER BY DEPT.NOMBRE, MUN.NOMBRE
                                                                LIMIT " . $pageLimit . ", " . PAGE_PER_BITACORA_MUNICIPIO);
        $mun_count = 1;
        $table = "";
        while ($row_bitacora_municipio = mysqli_fetch_assoc($query_bitacora_municipio)) {
            $table = $table . "<div class='panel panel-default'>";
                $table = $table . "<div style='padding: 5px 5px;' class='panel-heading'>";
                    $table = $table . "<h4 style='font-size: 11px;' class='panel-title'>";
                        $table = $table . "<a style='font-size: 11px;' data-toggle='collapse' data-parent='#accordion_bitacora_libretas' href='#collapse" . $mun_count . "'>" . $row_bitacora_municipio['DEPARTAMENTO'] . " - " . $row_bitacora_municipio['MUNICIPIO'] . "</a>";
                    $table = $table . "</h4>";
                $table = $table . "</div>";
                $table = $table . "<div id='collapse" . $mun_count . "' class='panel-collapse collapse'>";
                    $table = $table . "<div style='font-size: 11px; background-color: #D0DEE7;' class='panel-body'>";
                        $query_select_info_libreta_municipio = mysqli_query($connection, "SELECT * FROM bitacora_libretas_2 "
                                                                                       . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_bitacora_municipio['ID_MUNICIPIO_LIBRETA']);
                        if (mysqli_num_rows($query_select_info_libreta_municipio) != 0) {
                            $table = $table . "<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_editar=" . $row_bitacora_municipio['ID_MUNICIPIO_LIBRETA'] . "'><button class='btn_libreta' data-tooltip='tooltip' title='Crear Observación'><img src='Images/edit-icon.png' width='16' height='16' /></button></a>";
                            $table = $table . "<br />";
                            $table = $table . "<br />";
                            $table = $table . "<div class='divider'></div>";
                            $table = $table . "<br />";
                            while ($row_info_libreta_municipio = mysqli_fetch_assoc($query_select_info_libreta_municipio)) {
                                $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE FROM tipo_visitas_2 "
                                                                                    . " WHERE ID_TIPO_VISITA = " . $row_info_libreta_municipio['ID_TIPO_VISITA']);
                                $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_info_libreta_municipio['ID_USUARIO']);
                                $row_usuario = mysqli_fetch_array($query_select_usuario);
                                $table = $table . "<b>FECHA CREACIÓN: </b>" . $row_info_libreta_municipio['FECHA_CREACION'] .
                                                  "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;" .
                                                  "<b>FECHA VISITA: </b>" . $row_info_libreta_municipio['FECHA_VISITA'];
                                $table = $table . "<br />";
                                $table = $table . "<b>TIPO VISITA: </b>" . $row_tipo_visita['NOMBRE'] .
                                                  "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;" .
                                                  "<b>USUARIO: </b>" . $row_usuario['NOMBRE'];
                                //$table = $table . "<br />";
                                //$table = $table . "<b>OBSERVACIÓN: </b>" . "<button type='button' title='Observaciones' data-toggle='modal' id='" . $row_info_libreta_municipio['ID_TABLA'] . "' data-target='#modalObservaciones'><img src='Images/view.png' title='Observaciones' width='16' height='16' /></button>";
                                $table = $table . "<br />";
                                $table = $table . "<br />";
                                $query_select_files = mysqli_query($connection, "SELECT COUNT(*) AS TOTAL FROM bitacora_libretas_archivos_2 "
                                                                              . " WHERE ID_TABLA_BITACORA = " . $row_info_libreta_municipio['ID_TABLA']);
                                $row_files = mysqli_fetch_array($query_select_files);
                                if ($row_files['TOTAL'] != 0) {
                                    $table = $table . "<button type='button' title='Observaciones' data-toggle='modal' id='" . $row_info_libreta_municipio['ID_TABLA'] . "' data-target='#modalObservaciones'><img src='Images/view.png' title='Observaciones' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;<button type='button' title='Archivos' data-toggle='modal' id='" . $row_info_libreta_municipio['ID_TABLA'] . "' data-target='#modalFiles'><img src='Images/documents_2.png' title='Archivos' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;" . "<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_archivo=" . $row_info_libreta_municipio['ID_TABLA'] . "'><button class='btn_archivo' data-tooltip='tooltip' title='Cargar Archivo'><img src='Images/upload_file.png' width='16' height='16' /></button></a>&nbsp;&nbsp;&nbsp;<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_correo=" . $row_info_libreta_municipio['ID_TABLA'] . "'><button class='btn_email' data-tooltip='tooltip' title='Enviar Correo'><img src='Images/email_2.png' width='16' height='16' /></button></a>";
                                    //$table = $table . "<button type='button' title='Observaciones' data-toggle='modal' id='" . $row_info_libreta_municipio['ID_TABLA'] . "' data-target='#modalObservaciones'><img src='Images/view.png' title='Observaciones' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;<button type='button' title='Archivos' data-toggle='modal' id='" . $row_info_libreta_municipio['ID_TABLA'] . "' data-target='#modalFiles'><img src='Images/documents_2.png' title='Archivos' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;" . "<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_archivo=" . $row_info_libreta_municipio['ID_TABLA'] . "'><button class='btn_archivo' data-tooltip='tooltip' title='Cargar Archivo'><img src='Images/upload_file.png' width='16' height='16' /></button></a>";
                                } else {
                                    $table = $table . "<button type='button' title='Observaciones' data-toggle='modal' id='" . $row_info_libreta_municipio['ID_TABLA'] . "' data-target='#modalObservaciones'><img src='Images/view.png' title='Observaciones' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;<button style='pointer-events: none;' type='button' title='Archivos'><img src='Images/documents_2.png' title='Archivos' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;" . "<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_archivo=" . $row_info_libreta_municipio['ID_TABLA'] . "'><button class='btn_archivo' data-tooltip='tooltip' title='Cargar Archivo'><img src='Images/upload_file.png' width='16' height='16' /></button></a>&nbsp;&nbsp;&nbsp;<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_correo=" . $row_info_libreta_municipio['ID_TABLA'] . "'><button class='btn_email' data-tooltip='tooltip' title='Enviar Correo'><img src='Images/email_2.png' width='16' height='16' /></button></a>";
                                    //$table = $table . "<button type='button' title='Observaciones' data-toggle='modal' id='" . $row_info_libreta_municipio['ID_TABLA'] . "' data-target='#modalObservaciones'><img src='Images/view.png' title='Observaciones' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;<button style='pointer-events: none;' type='button' title='Archivos'><img src='Images/documents_2.png' title='Archivos' width='16' height='16' /></button>&nbsp;&nbsp;&nbsp;" . "<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_archivo=" . $row_info_libreta_municipio['ID_TABLA'] . "'><button class='btn_archivo' data-tooltip='tooltip' title='Cargar Archivo'><img src='Images/upload_file.png' width='16' height='16' /></button></a>";
                                }
                                $table = $table . "<br />";
                                $table = $table . "<br />";
                                $table = $table . "<div class='divider'></div>";
                                $table = $table . "<br />";
                            }
                        } else {
                            $table = $table . "<a href='Bitacora_Acuerdos.php?id_bitacora_libreta_editar=" . $row_bitacora_municipio['ID_MUNICIPIO_LIBRETA'] . "'><button class='btn_libreta' data-tooltip='tooltip' title='Crear Observación'><img src='Images/edit-icon.png' width='16' height='16' /></button></a>";
                            $table = $table . "<br />";
                            $table = $table . "<br />";
                            $table = $table . "<div class='divider'></div>";
                            $table = $table . "<br />";
                            $table = $table . "<p class='message'>No existe Libreta creada para este Municipio.</p>";
                        }
                    $table = $table . "</div>";
                $table = $table . "</div>";
            $table = $table . "</div>";
            $mun_count = $mun_count + 1;
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>
<script>
    $(document).ready(function() {
        $('.btn_libreta').tooltip({
            container : "body",
            placement : "right"
        });
        $('.btn_archivo').tooltip({
            container : "body",
            placement : "right"
        });
        $('.btn_email').tooltip({
            container : "body",
            placement : "right"
        });
    });
</script>