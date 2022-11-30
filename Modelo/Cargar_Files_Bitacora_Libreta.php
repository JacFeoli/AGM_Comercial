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
        $table = "";
        $theList = "";
        $tag = "";
        $i = 0;
        $total_size = 0;
        $total_files = 0;
        $info_bitacora = array();
        $info_files = array();
        $info_id_bitacora = array();
        $info_files_path = array();
        $id_tabla_archivo = $_POST['id_tabla_archivo'];
        $file_id = $_POST['file_id'];
        $query_select_files_bitacora_libreta = mysqli_query($connection, "SELECT * "
                                                                       . "  FROM bitacora_libretas_archivos_2 "
                                                                       . " WHERE ID_TABLA_BITACORA = " . $file_id);
        while ($row_files = mysqli_fetch_assoc($query_select_files_bitacora_libreta)) {
            $info_bitacora[] = $row_files['NOMBRE_ARCHIVO'];
            $info_id_bitacora[] = $row_files['ID_TABLA'];
        }
        $query_select_info_tipo_visita = mysqli_query($connection, "SELECT * "
                                                                 . "  FROM bitacora_libretas_2 BT, tipo_visitas_2 TP "
                                                                 . " WHERE BT.ID_TIPO_VISITA = TP.ID_TIPO_VISITA "
                                                                 . "   AND BT.ID_TABLA = " . $file_id);
        $row_info_tipo_visita = mysqli_fetch_array($query_select_info_tipo_visita);
        $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                             . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_info_tipo_visita['ID_MUNICIPIO_LIBRETA']);
        $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
        $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                 . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                 . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
        $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
        $table = $table . "<div class='table-responsive'>";
            $table = $table . "<table class='table table-condensed table-detalle'>";
                $table = $table . "<thead>";
                    $table = $table . "<th style='width: 40%;'>NOMBRE ARCHIVO</th>";
                    $table = $table . "<th style='width: 12%;'>TAMAÑO ARCHIVO</th>";
                    $table = $table . "<th style='width: 12%;'>EXTENSIÓN ARCHIVO</th>";
                    $table = $table . "<th style='width: 5%;'>ELIMINAR</th>";
                $table = $table . "</thead>";
                $path = "../Files/" . $row_nombre_municipio['NOMBRE'] . "/";
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
                                  . "   <td style='vertical-align: middle;'><a href='" . "AGM/" . $path . $files . "' target='_blank' title='" . $files . "'>" . $files . "</a></td>"
                                  . "   <td style='vertical-align: middle;'>" . file_size($path . $files) . "</td>"
                                  . "   <td style='vertical-align: middle;'>" . $tag . " - " . strtoupper(pathinfo($path . $files, PATHINFO_EXTENSION)) . "</td>"
                                  . "   <td style='vertical-align: middle;'><a href='Modelo/Eliminar_Archivos.php?file=" . $files . "&id_tabla_archivo=" . $id_tabla_archivo . "&file_id=" . $id_files . "&archivo=bitacora'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16' /></button></a></td>"
                                  . "</tr>";
                    }
                    closedir($handle);
                }
                $table = $table . $theList;
            $table = $table . "</table>";
        $table = $table . "</div>";
        $info_files[0] = "Archivos Tipo Visita: " . $row_info_tipo_visita['NOMBRE'] . " - Fecha: " . $row_info_tipo_visita['FECHA_VISITA'];
        $info_files[1] = $table;
        echo json_encode($info_files);
        exit();
    }
?>