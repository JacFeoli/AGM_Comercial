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
        $archivo = $_GET['archivo'];
        switch ($archivo) {
            case 'bitacora':
                $id_tabla_archivo = $_POST['id_tabla_archivo'];
                $fecha_subida = date('Y-m-d H:i:s');
                $id_usuario = $_SESSION['id_user'];
                $query_select_id_municipio_libreta = mysqli_query($connection, "SELECT ID_MUNICIPIO_LIBRETA FROM bitacora_libretas_2 WHERE ID_TABLA = " . $id_tabla_archivo);
                $row_id_municipio_libreta = mysqli_fetch_array($query_select_id_municipio_libreta);
                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                                     . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_id_municipio_libreta['ID_MUNICIPIO_LIBRETA']);
                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                $tmpFilePath = $_FILES['files']['tmp_name'];
                if ($tmpFilePath != "") {
                    $newFilePath = "../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . strtoupper($_FILES['files']['name']);
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        mysqli_query($connection, "INSERT INTO bitacora_libretas_archivos_2 (ID_TABLA_BITACORA, NOMBRE_ARCHIVO, FECHA_SUBIDA, ID_USUARIO) "
                                                      . " VALUES ('$id_tabla_archivo', '" . strtoupper($_FILES['files']['name']) . "', '$fecha_subida', '$id_usuario')");
                    }
                }
                echo "<script>";
                    echo "document.location.href = '../Bitacora_Acuerdos.php?id_bitacora_libreta_archivo=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'acuerdo':
                $id_tabla_archivo = $_POST['id_tabla_archivo'];
                $estado_archivo_acuerdo = $_POST['estado_archivo_acuerdo'];
                $fecha_subida = date('Y-m-d H:i:s');
                $id_usuario = $_SESSION['id_user'];
                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                                     . " WHERE ID_MUNICIPIO_LIBRETA = " . $id_tabla_archivo);
                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                $tmpFilePath = $_FILES['files']['tmp_name'];
                if ($tmpFilePath != "") {
                    $newFilePath = "../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . strtoupper($_FILES['files']['name']);
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        mysqli_query($connection, "INSERT INTO municipios_libreta_archivos_2 (ID_TABLA_MUNICIPIO, NOMBRE_ARCHIVO, ESTADO_ACUERDO, FECHA_SUBIDA, ID_USUARIO) "
                                                      . " VALUES ('$id_tabla_archivo', '" . strtoupper($_FILES['files']['name']) . "', '" . $estado_archivo_acuerdo . "', '$fecha_subida', '$id_usuario')");
                    }
                }
                echo "<script>";
                    echo "document.location.href = '../Municipios_Libretas.php?id_municipio_libreta_editar=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'recaudo':
                $id_tabla_archivo = $_POST['id_tabla_archivo'];
                $fecha_subida = date('Y-m-d H:i:s');
                $id_usuario = $_SESSION['id_user'];
                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                                     . " WHERE ID_MUNICIPIO_LIBRETA = " . $id_tabla_archivo);
                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                $tmpFilePath = $_FILES['files']['tmp_name'];
                if ($tmpFilePath != "") {
                    $newFilePath = "../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . strtoupper($_FILES['files']['name']);
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        mysqli_query($connection, "INSERT INTO recaudo_especiales_archivos_2 (ID_TABLA_RECAUDO, NOMBRE_ARCHIVO, FECHA_SUBIDA, ID_USUARIO) "
                                                      . " VALUES ('$id_tabla_archivo', '" . strtoupper($_FILES['files']['name']) . "', '$fecha_subida', '$id_usuario')");
                    }
                }
                echo "<script>";
                    echo "document.location.href = '../Recaudo_Especiales.php?id_reca_especial_editar=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'recaudo_municipal':
                $id_tabla_archivo = $_POST['id_tabla_archivo'];
                $fecha_subida = date('Y-m-d H:i:s');
                $id_usuario = $_SESSION['id_user'];
                $query_select_id_facturacion = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                     . " WHERE ID_RECAUDO = " . $id_tabla_archivo);
                $row_id_facturacion = mysqli_fetch_array($query_select_id_facturacion);
                $query_select_info_facturacion = mysqli_query($connection, "SELECT * "
                                                                         . "  FROM facturacion_municipales_2 "
                                                                         . " WHERE ID_FACTURACION = " . $row_id_facturacion['ID_FACTURACION']);
                $row_info_facturacion = mysqli_fetch_array($query_select_info_facturacion);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_info_facturacion['ID_COD_DPTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_info_facturacion['ID_COD_MPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                $tmpFilePath = $_FILES['files']['tmp_name'];
                if ($tmpFilePath != "") {
                    $newFilePath = "../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . strtoupper($_FILES['files']['name']);
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        mysqli_query($connection, "INSERT INTO recaudo_municipales_archivos_2 (ID_TABLA_RECAUDO, NOMBRE_ARCHIVO, FECHA_SUBIDA, ID_USUARIO) "
                                                      . " VALUES ('$id_tabla_archivo', '" . strtoupper($_FILES['files']['name']) . "', '$fecha_subida', '$id_usuario')");
                    }
                }
                echo "<script>";
                    echo "document.location.href = '../Recaudo_Municipales.php?id_reca_municipal_editar=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'pqr':
                $id_tabla_archivo = $_POST['id_tabla_archivo'];
                $fecha_subida = date('Y-m-d H:i:s');
                $id_usuario = $_SESSION['id_user'];
                $query_select_info_pqr = mysqli_query($connection, "SELECT * "
                                                                 . "  FROM pqr_2 "
                                                                 . " WHERE ID_PQR = " . $id_tabla_archivo);
                $row_info_pqr = mysqli_fetch_array($query_select_info_pqr);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_info_pqr['ID_COD_DPTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_info_pqr['ID_COD_MPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                $tmpFilePath = $_FILES['files']['tmp_name'];
                if ($tmpFilePath != "") {
                    $newFilePath = "../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . strtoupper($_FILES['files']['name']);
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        mysqli_query($connection, "INSERT INTO pqr_archivos_2 (ID_TABLA_PQR, NOMBRE_ARCHIVO, FECHA_SUBIDA, ID_USUARIO) "
                                                      . " VALUES ('$id_tabla_archivo', '" . strtoupper($_FILES['files']['name']) . "', '$fecha_subida', '$id_usuario')");
                    }
                }
                echo "<script>";
                    echo "document.location.href = '../P_Q_R.php?id_pqr_editar=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
        }
    }
?>